<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
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

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            StoreSetting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan warkop berhasil disimpan!');
    }
}
