<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyLog;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoyaltyApiController extends Controller
{
    public function balance(Request $request)
    {
        $user = $request->user();

        // Available vouchers that can be claimed with points or used
        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', today());
            })
            ->get();

        $history = LoyaltyLog::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'current_points' => $user->loyalty_points,
                'vouchers' => $vouchers,
                'history' => $history,
            ],
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $user = $request->user();
        $voucher = Voucher::findOrFail($request->voucher_id);

        if ($voucher->points_required <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher ini tidak memerlukan penukaran poin. Gunakan langsung kode: ' . $voucher->code,
            ], 422);
        }

        if ($user->loyalty_points < $voucher->points_required) {
            return response()->json([
                'success' => false,
                'message' => "Poin Anda ({$user->loyalty_points}) tidak mencukupi untuk menukar voucher ini (Butuh {$voucher->points_required} poin).",
            ], 422);
        }

        return DB::transaction(function () use ($user, $voucher) {
            $user->decrement('loyalty_points', $voucher->points_required);

            LoyaltyLog::create([
                'user_id' => $user->id,
                'type' => 'redeemed',
                'points' => -$voucher->points_required,
                'balance_after' => $user->loyalty_points,
                'description' => 'Penukaran voucher: ' . $voucher->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Selamat! Voucher berhasil ditukarkan. Gunakan kode: ' . $voucher->code . ' saat checkout.',
                'data' => [
                    'voucher_code' => $voucher->code,
                    'remaining_points' => $user->loyalty_points,
                ],
            ]);
        });
    }
}
