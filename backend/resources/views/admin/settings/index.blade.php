@extends('layouts.app')

@section('title', 'Pengaturan Warkop')
@section('page_title', 'Pengaturan Warkop & Konfigurasi Sistem')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-sliders text-amber"></i> Profil Toko & Pengaturan Geofence</h3>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <h4 style="font-size: 14px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
            1. Informasi Warkop
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Brand Warkop</label>
                <input type="text" name="store_name" value="{{ $settings['store_name'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">No. WhatsApp / Telepon</label>
                <input type="text" name="store_phone" value="{{ $settings['store_phone'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tagline / Slogan</label>
            <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Alamat Lengkap Warkop</label>
            <textarea name="store_address" rows="2" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">{{ $settings['store_address'] }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Wi-Fi (SSID)</label>
                <input type="text" name="store_wifi_ssid" value="{{ $settings['store_wifi_ssid'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Password Wi-Fi</label>
                <input type="text" name="store_wifi_pass" value="{{ $settings['store_wifi_pass'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
            </div>
        </div>

        <h4 style="font-size: 14px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
            2. Titik Geofencing & Validasi GPS Absensi
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Latitude Warkop</label>
                <input type="text" name="store_latitude" value="{{ $settings['store_latitude'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Longitude Warkop</label>
                <input type="text" name="store_longitude" value="{{ $settings['store_longitude'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Radius Izin (Meter)</label>
                <input type="number" name="geofence_radius_meters" value="{{ $settings['geofence_radius_meters'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>
        </div>

        <h4 style="font-size: 14px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
            3. Loyalty Point & POS
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Poin per Kelipatan Rp 10.000</label>
                <input type="number" name="loyalty_points_per_10k" value="{{ $settings['loyalty_points_per_10k'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Akun QRIS Struk</label>
                <input type="text" name="qris_account_name" value="{{ $settings['qris_account_name'] }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Pengaturan
        </button>
    </form>
</div>
@endsection
