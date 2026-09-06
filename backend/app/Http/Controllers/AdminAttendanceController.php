<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $attendances = Attendance::whereDate('date', $date)
            ->with(['user', 'shift'])
            ->latest('clock_in')
            ->get();

        $staffUsers = User::whereIn('role', ['manager', 'cashier', 'barista', 'waiter'])
            ->where('is_active', true)
            ->get();

        $shifts = Shift::where('is_active', true)->get();

        return view('admin.attendance.index', compact('attendances', 'staffUsers', 'shifts', 'date'));
    }
}
