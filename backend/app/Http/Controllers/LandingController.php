<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\Table;
use App\Models\Voucher;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->with('variants');
            }])
            ->orderBy('sort_order')
            ->get();

        $favoriteProducts = Product::where('is_active', true)
            ->where('is_favorite', true)
            ->with(['category', 'variants'])
            ->limit(6)
            ->get();

        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', today());
            })
            ->limit(3)
            ->get();

        $storeInfo = [
            'name' => StoreSetting::get('store_name', 'Warkop Dadang'),
            'tagline' => StoreSetting::get('store_tagline', 'Nongkrong Santai, Kopi Nikmat, Wi-Fi Kencang'),
            'phone' => StoreSetting::get('store_phone', '0812-3456-7890'),
            'address' => StoreSetting::get('store_address', 'Jl. Pemuda No. 88, Rawamangun, Jakarta Timur'),
            'open_hours' => StoreSetting::get('store_open_hours', 'Buka Setiap Hari: 08.00 - 03.00 WIB'),
            'wifi_ssid' => StoreSetting::get('store_wifi_ssid', 'WARKOP DADANG 5G'),
            'wifi_pass' => StoreSetting::get('store_wifi_pass', 'kopidandangjuara'),
            'latitude' => StoreSetting::get('store_latitude', '-6.208800'),
            'longitude' => StoreSetting::get('store_longitude', '106.845600'),
        ];

        return view('landing.index', compact('categories', 'favoriteProducts', 'vouchers', 'storeInfo'));
    }

    public function selfOrder(Request $request)
    {
        $tableCode = $request->query('meja');
        $table = null;
        if ($tableCode) {
            $table = Table::where('qr_code', $tableCode)
                ->orWhere('number', $tableCode)
                ->first();
        }

        $categories = Category::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->with('variants');
            }])
            ->orderBy('sort_order')
            ->get();

        $tables = Table::orderBy('number')->get();

        $storeInfo = [
            'name' => StoreSetting::get('store_name', 'Warkop Dadang'),
            'tax_percentage' => StoreSetting::get('tax_percentage', 0),
        ];

        return view('landing.self-order', compact('categories', 'tables', 'table', 'storeInfo'));
    }
}
