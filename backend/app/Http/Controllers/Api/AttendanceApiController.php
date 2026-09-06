<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ShiftAssignment;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceApiController extends Controller
{
    public function today(Request $request)
    {
        $user = $request->user();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->with('shift')
            ->first();

        $shiftAssignment = ShiftAssignment::where('user_id', $user->id)
            ->whereDate('date', today())
            ->with('shift')
            ->first();

        $storeLat = (float) StoreSetting::get('store_latitude', -6.208800);
        $storeLng = (float) StoreSetting::get('store_longitude', 106.845600);
        $allowedRadius = (float) StoreSetting::get('geofence_radius_meters', 50);

        return response()->json([
            'success' => true,
            'data' => [
                'has_clocked_in' => $todayAttendance && $todayAttendance->clock_in !== null,
                'has_clocked_out' => $todayAttendance && $todayAttendance->clock_out !== null,
                'attendance' => $todayAttendance,
                'scheduled_shift' => $shiftAssignment ? $shiftAssignment->shift : null,
                'geofence_config' => [
                    'store_latitude' => $storeLat,
                    'store_longitude' => $storeLng,
                    'allowed_radius_meters' => $allowedRadius,
                ],
            ],
        ]);
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|max:2048', // max 2MB selfie
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $storeLat = (float) StoreSetting::get('store_latitude', -6.208800);
        $storeLng = (float) StoreSetting::get('store_longitude', 106.845600);
        $allowedRadius = (float) StoreSetting::get('geofence_radius_meters', 50);

        // Calculate distance
        $distance = Attendance::calculateDistance(
            $request->latitude,
            $request->longitude,
            $storeLat,
            $storeLng
        );

        if ($distance > $allowedRadius) {
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar radius warkop ({$distance} meter). Maksimal radius yang diizinkan adalah {$allowedRadius} meter.",
                'distance_meters' => $distance,
            ], 422);
        }

        // Check if already clocked in today
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        if ($attendance && $attendance->clock_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen masuk hari ini.',
            ], 422);
        }

        // Save Selfie photo
        $photoPath = $request->file('photo')->store('attendances/' . date('Y/m'), 'public');

        // Check shift
        $assignment = ShiftAssignment::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        $status = 'present';
        if ($assignment && $assignment->shift) {
            $shiftStartTime = $assignment->shift->start_time;
            if (now()->format('H:i:s') > $shiftStartTime) {
                $status = 'late';
            }
        }

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => today()],
            [
                'shift_id' => $assignment ? $assignment->shift_id : null,
                'clock_in' => now()->format('H:i:s'),
                'clock_in_photo' => $photoPath,
                'clock_in_latitude' => $request->latitude,
                'clock_in_longitude' => $request->longitude,
                'clock_in_distance_meters' => $distance,
                'status' => $status,
                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Absen masuk berhasil tercatat! Selamat bertugas.',
            'data' => $attendance,
        ]);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $storeLat = (float) StoreSetting::get('store_latitude', -6.208800);
        $storeLng = (float) StoreSetting::get('store_longitude', 106.845600);

        $distance = Attendance::calculateDistance(
            $request->latitude,
            $request->longitude,
            $storeLat,
            $storeLng
        );

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absen masuk hari ini.',
            ], 422);
        }

        if ($attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen pulang hari ini.',
            ], 422);
        }

        $photoPath = $request->file('photo')->store('attendances/' . date('Y/m'), 'public');

        $attendance->update([
            'clock_out' => now()->format('H:i:s'),
            'clock_out_photo' => $photoPath,
            'clock_out_latitude' => $request->latitude,
            'clock_out_longitude' => $request->longitude,
            'clock_out_distance_meters' => $distance,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil. Terima kasih atas kerja keras Anda!',
            'data' => $attendance,
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with('shift')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }
}
