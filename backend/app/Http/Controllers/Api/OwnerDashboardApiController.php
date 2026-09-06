<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerDashboardApiController extends Controller
{
    public function summary(Request $request)
    {
        // Penjualan Hari Ini
        $todayOrders = Order::whereDate('created_at', today())
            ->where('status', '!=', 'cancelled');

        $todayRevenue = (float) (clone $todayOrders)->where('payment_status', 'paid')->sum('total_amount');
        $todayOrderCount = (clone $todayOrders)->count();
        $completedOrderCount = (clone $todayOrders)->where('status', 'completed')->count();
        $pendingOrderCount = (clone $todayOrders)->whereIn('status', ['pending', 'preparing', 'ready'])->count();

        // Pengeluaran Hari Ini
        $todayExpenses = (float) Expense::whereDate('expense_date', today())
            ->where('status', 'approved')
            ->sum('amount');

        // Net Profit Hari Ini
        $netProfitToday = $todayRevenue - $todayExpenses;

        // Top 5 Menu Hari Ini
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($q) {
                $q->whereDate('created_at', today())->where('status', '!=', 'cancelled');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Absensi Staf Hari Ini
        $staffAttendances = Attendance::whereDate('date', today())
            ->with(['user', 'shift'])
            ->get()
            ->map(function ($att) {
                return [
                    'staff_name' => $att->user->name,
                    'role' => $att->user->role,
                    'clock_in' => $att->clock_in,
                    'clock_out' => $att->clock_out,
                    'clock_in_photo' => $att->clock_in_photo ? asset('storage/' . $att->clock_in_photo) : null,
                    'status' => $att->status,
                    'distance_meters' => $att->clock_in_distance_meters,
                ];
            });

        // 7 Hari Terakhir Trend Penjualan
        $weeklyTrend = Order::select(
            DB::raw('DATE(created_at) as order_date'),
            DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as revenue'),
            DB::raw('COUNT(*) as total_orders')
        )
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->where('status', '!=', 'cancelled')
        ->groupBy('order_date')
        ->orderBy('order_date')
        ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'revenue_today' => $todayRevenue,
                    'expenses_today' => $todayExpenses,
                    'net_profit_today' => $netProfitToday,
                    'total_orders_today' => $todayOrderCount,
                    'completed_orders_today' => $completedOrderCount,
                    'pending_orders_today' => $pendingOrderCount,
                ],
                'top_products' => $topProducts,
                'staff_attendances' => $staffAttendances,
                'weekly_trend' => $weeklyTrend,
            ],
        ]);
    }

    public function expenses(Request $request)
    {
        $expenses = Expense::with(['recorder', 'approver'])
            ->latest('expense_date')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $expenses,
        ]);
    }

    public function approveExpense(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $expense = Expense::findOrFail($id);
        $expense->status = $request->status;
        $expense->approved_by = $request->user()->id;
        $expense->save();

        return response()->json([
            'success' => true,
            'message' => "Pengeluaran kas telah di-{$request->status}.",
            'data' => $expense,
        ]);
    }
}
