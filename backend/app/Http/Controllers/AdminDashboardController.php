<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Expense;
use App\Models\MaterialStockLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Shift;
use App\Models\StoreSetting;
use App\Models\Table;
use App\Models\User;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = today();
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // 1. Stats & Analitik
        $todayOrders = Order::whereDate('created_at', $today)->where('status', '!=', 'cancelled');
        $todayRevenue = (float) (clone $todayOrders)->where('payment_status', 'paid')->sum('total_amount');
        $todayOrderCount = (clone $todayOrders)->count();
        $todayCompletedCount = (clone $todayOrders)->where('status', 'completed')->count();

        $monthOrders = Order::whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->where('status', '!=', 'cancelled');
        $monthRevenue = (float) (clone $monthOrders)->where('payment_status', 'paid')->sum('total_amount');

        $todayExpenses = (float) Expense::whereDate('expense_date', $today)->where('status', 'approved')->sum('amount');
        $monthExpenses = (float) Expense::whereMonth('expense_date', $thisMonth)->whereYear('expense_date', $thisYear)->where('status', 'approved')->sum('amount');
        $monthProfit = $monthRevenue - $monthExpenses;

        $occupiedTables = Table::where('status', 'occupied')->count();
        $totalTables = Table::count();

        $lowStockMaterials = RawMaterial::whereRaw('current_stock <= min_stock_alert')->get();

        // 2. Data Menu & Kategori
        $categories = Category::where('is_active', true)->withCount('products')->orderBy('sort_order')->get();
        $products = Product::with(['category', 'variants'])->latest()->get();

        // 3. Data Pesanan
        $orders = Order::with(['items.product', 'table', 'cashier', 'server'])->latest()->limit(50)->get();

        // 4. Data Meja & QR
        $tables = Table::with('activeOrder')->orderBy('number')->get();

        // 5. Data Stok Bahan Baku & Log
        $materials = RawMaterial::with('stockLogs')->orderBy('name')->get();
        $stockLogs = MaterialStockLog::with(['rawMaterial', 'user'])->latest()->limit(20)->get();

        // 6. Data Absensi Staf
        $attendances = Attendance::whereDate('date', $today)->with(['user', 'shift'])->latest('clock_in')->get();
        $allStaff = User::whereIn('role', ['manager', 'cashier', 'barista', 'waiter'])->get();

        // 7. Data Kas Kecil & Bon
        $expenses = Expense::with(['recorder', 'approver'])->latest('expense_date')->limit(30)->get();
        $totalApprovedExpenses = Expense::where('status', 'approved')->sum('amount');
        $totalPendingExpenses = Expense::where('status', 'pending')->sum('amount');

        // 8. Pengaturan Toko & Geofence
        $settings = [
            'store_name' => StoreSetting::get('store_name', 'Warkop Dadang'),
            'store_tagline' => StoreSetting::get('store_tagline', 'Nongkrong Santai, Kopi Nikmat, Wi-Fi Kencang'),
            'store_phone' => StoreSetting::get('store_phone', '0812-3456-7890'),
            'store_address' => StoreSetting::get('store_address', 'Jl. Pemuda No. 88, Rawamangun, Jakarta Timur'),
            'store_open_hours' => StoreSetting::get('store_open_hours', 'Buka Setiap Hari: 08.00 - 03.00 WIB'),
            'store_wifi_ssid' => StoreSetting::get('store_wifi_ssid', 'WARKOP DADANG 5G'),
            'store_wifi_pass' => StoreSetting::get('store_wifi_pass', 'kopidandangjuara'),
            'store_latitude' => StoreSetting::get('store_latitude', '-6.208800'),
            'store_longitude' => StoreSetting::get('store_longitude', '106.845600'),
            'geofence_radius_meters' => StoreSetting::get('geofence_radius_meters', '50'),
            'loyalty_points_per_10k' => StoreSetting::get('loyalty_points_per_10k', '1'),
            'tax_percentage' => StoreSetting::get('tax_percentage', '0'),
            'qris_account_name' => StoreSetting::get('qris_account_name', 'WARKOP DADANG NUSANTARA'),
        ];

        // 9. Top 5 Menu
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($q) {
                $q->where('status', '!=', 'cancelled');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 10. 7-Day Chart Data
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $chartLabels[] = $d->translatedFormat('D, d M');
            $rev = Order::whereDate('created_at', $d)->where('payment_status', 'paid')->sum('total_amount');
            $cnt = Order::whereDate('created_at', $d)->where('status', '!=', 'cancelled')->count();
            $chartRevenue[] = (float) $rev;
            $chartOrders[] = $cnt;
        }

        return view('admin.dashboard', compact(
            'todayRevenue',
            'todayOrderCount',
            'todayCompletedCount',
            'monthRevenue',
            'todayExpenses',
            'monthExpenses',
            'monthProfit',
            'occupiedTables',
            'totalTables',
            'lowStockMaterials',
            'categories',
            'products',
            'orders',
            'tables',
            'materials',
            'stockLogs',
            'attendances',
            'allStaff',
            'expenses',
            'totalApprovedExpenses',
            'totalPendingExpenses',
            'settings',
            'topProducts',
            'chartLabels',
            'chartRevenue',
            'chartOrders'
        ));
    }
}
