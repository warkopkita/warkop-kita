@extends('layouts.app')

@section('title', 'Stok Bahan Baku')
@section('page_title', 'Inventaris & Stok Bahan Baku Warkop')

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
    <button type="button" class="btn btn-primary" onclick="document.getElementById('addMaterialModal').style.display='flex'">
        <i class="fa-solid fa-plus"></i> Tambah Bahan Baku Baru
    </button>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Stock Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-boxes-stacked text-amber"></i> Daftar Stok Bahan</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Stok Sekarang</th>
                        <th>Batas Min.</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $mat)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--text-main);">{{ $mat->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">SKU: {{ $mat->sku }} | {{ $mat->supplier }}</div>
                            </td>
                            <td style="font-weight: 800; font-size: 15px; color: {{ $mat->isLowStock() ? 'var(--accent-red)' : 'var(--primary-light)' }};">
                                {{ $mat->current_stock }} {{ $mat->unit }}
                            </td>
                            <td>{{ $mat->min_stock_alert }} {{ $mat->unit }}</td>
                            <td>
                                @if($mat->isLowStock())
                                    <span class="badge badge-danger">Kritis</span>
                                @else
                                    <span class="badge badge-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-secondary" style="padding: 6px 10px; font-size: 11px;" onclick="openAdjustModal('{{ $mat->id }}', '{{ $mat->name }}', '{{ $mat->unit }}')">
                                    <i class="fa-solid fa-sliders"></i> Sesuaikan
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock Logs -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-clock-rotate-left text-amber"></i> Log Penyesuaian</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 12px;">
            @forelse($recentLogs as $log)
                <div style="padding-bottom: 10px; border-bottom: 1px solid rgba(56, 48, 42, 0.4);">
                    <div style="display: flex; justify-content: space-between; font-weight: 700;">
                        <span style="color: var(--text-main);">{{ $log->rawMaterial->name ?? 'Bahan' }}</span>
                        <span style="color: {{ $log->type === 'in' ? 'var(--accent-green)' : 'var(--accent-red)' }};">
                            {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }} {{ $log->rawMaterial->unit ?? '' }}
                        </span>
                    </div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-top: 2px;">
                        {{ $log->reference ?? 'Penyesuaian' }} &bull; {{ $log->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">Belum ada log stok.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Add Material -->
<div id="addMaterialModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; width: 100%; max-width: 480px; padding: 28px;">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 18px;">Tambah Bahan Baku Baru</h3>
        <form action="{{ route('admin.inventory.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Bahan Baku</label>
                <input type="text" name="name" placeholder="contoh: Biji Kopi Arabika Gayo" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Satuan (Unit)</label>
                    <input type="text" name="unit" placeholder="kg / liter / pcs / kaleng" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Stok Awal</label>
                    <input type="number" step="0.1" name="current_stock" placeholder="10" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Batas Peringatan Min.</label>
                    <input type="number" step="0.1" name="min_stock_alert" value="5" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Supplier (Opsional)</label>
                    <input type="text" name="supplier" placeholder="Agen Kopi" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addMaterialModal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Bahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Adjust Stock -->
<div id="adjustModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; width: 100%; max-width: 440px; padding: 28px;">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">Sesuaikan Stok</h3>
        <p id="adjustMatTitle" style="font-size: 13px; color: var(--primary); margin-bottom: 18px;"></p>
        <form id="adjustForm" method="POST">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jenis Penyesuaian</label>
                <select name="type" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
                    <option value="in">Stok Masuk (Restock +)</option>
                    <option value="out">Stok Keluar (-)</option>
                    <option value="waste">Bahan Rusak / Basi (Waste -)</option>
                    <option value="adjustment">Koreksi Stok Aktual (=)</option>
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label id="adjustQtyLabel" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jumlah</label>
                <input type="number" step="0.01" name="quantity" placeholder="0" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Alasan / Catatan</label>
                <input type="text" name="notes" placeholder="contoh: Belanja mingguan" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('adjustModal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAdjustModal(id, name, unit) {
        document.getElementById('adjustMatTitle').innerText = name;
        document.getElementById('adjustQtyLabel').innerText = 'Jumlah (' + unit + ')';
        document.getElementById('adjustForm').action = '/admin/inventory/' + id + '/adjust';
        document.getElementById('adjustModal').style.display = 'flex';
    }
</script>
@endsection
