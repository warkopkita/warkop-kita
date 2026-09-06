@extends('layouts.app')

@section('title', 'Meja & QR Code')
@section('page_title', 'Manajemen Meja & QR Self-Order')

@section('styles')
<style>
    .tables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }

    .table-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 22px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
    }

    .table-badge {
        position: absolute;
        top: 14px;
        right: 14px;
    }

    .qr-box {
        width: 110px;
        height: 110px;
        background: #fff;
        border-radius: 12px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 14px 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .qr-box img {
        width: 100%;
        height: 100%;
    }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <p style="font-size: 13px; color: var(--text-muted);">
            Scan QR Code di setiap meja untuk membuka menu & self-order langsung dari HP pelanggan.
        </p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('tableModal').style.display='flex'">
        <i class="fa-solid fa-plus"></i> Tambah Meja Baru
    </button>
</div>

<div class="tables-grid">
    @foreach($tables as $tbl)
        <div class="table-card">
            <div class="table-badge">
                @if($tbl->status === 'occupied')
                    <span class="badge badge-warning"><i class="fa-solid fa-user"></i> Terisi</span>
                @else
                    <span class="badge badge-success"><i class="fa-solid fa-check"></i> Kosong</span>
                @endif
            </div>

            <div style="font-size: 18px; font-weight: 800; color: var(--primary-light);">
                {{ $tbl->name ?? 'Meja ' . $tbl->number }}
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Kapasitas: {{ $tbl->capacity }} Orang</div>

            <!-- QR Code generated via free Google Chart API / QR API -->
            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/self-order?meja=' . $tbl->qr_code)) }}" alt="QR Meja {{ $tbl->number }}">
            </div>

            <div style="font-size: 12px; font-weight: 700; color: var(--primary); font-family: monospace;">
                {{ $tbl->qr_code }}
            </div>

            <div style="margin-top: 14px; display: flex; gap: 6px; width: 100%;">
                <a href="{{ url('/self-order?meja=' . $tbl->qr_code) }}" target="_blank" class="btn btn-secondary" style="flex: 1; padding: 6px; font-size: 11px; justify-content: center;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Tes Buka
                </a>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal Add Table -->
<div id="tableModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; width: 100%; max-width: 440px; padding: 28px;">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 18px;">Tambah Meja Baru</h3>
        <form action="{{ route('admin.tables.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nomor Meja</label>
                <input type="text" name="number" placeholder="contoh: 13 atau VIP-3" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Label Meja (Opsional)</label>
                <input type="text" name="name" placeholder="contoh: Meja Lesehan 3" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kapasitas Duduk (Orang)</label>
                <input type="number" name="capacity" value="4" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('tableModal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Meja</button>
            </div>
        </form>
    </div>
</div>
@endsection
