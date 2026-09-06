@extends('layouts.app')

@section('title', 'Manajemen Kas Kecil & Pengeluaran')
@section('page_title', 'Kas Kecil & Pengeluaran Operasional')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px;">
        <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Total Disetujui</div>
        <div style="font-size: 22px; font-weight: 800; color: var(--accent-green); margin-top: 4px;">
            Rp {{ number_format($totalApproved, 0, ',', '.') }}
        </div>
    </div>
    <div class="card" style="padding: 18px;">
        <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Menunggu Approval</div>
        <div style="font-size: 22px; font-weight: 800; color: var(--accent-yellow); margin-top: 4px;">
            Rp {{ number_format($totalPending, 0, ',', '.') }}
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
        <h3 class="card-title"><i class="fa-solid fa-receipt text-amber"></i> Rekap Bon & Pengeluaran</h3>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('expenseModal').style.display='flex'">
            <i class="fa-solid fa-plus"></i> Catat Pengeluaran Baru
        </button>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Judul Pengeluaran</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                    <th>Dicatat Oleh</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                    <tr>
                        <td>{{ $exp->expense_date->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $exp->title }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $exp->description }}</div>
                        </td>
                        <td>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--primary);">
                                {{ $exp->category }}
                            </span>
                        </td>
                        <td style="font-weight: 800; color: var(--primary-light);">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        <td>{{ $exp->recorder->name ?? '-' }}</td>
                        <td>
                            @if($exp->status === 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($exp->status === 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Pending Review</span>
                            @endif
                        </td>
                        <td>
                            @if($exp->status === 'pending' && (Auth::user()->isOwner() || Auth::user()->isManager()))
                                <div style="display: flex; gap: 6px;">
                                    <form action="{{ route('admin.expenses.approve', $exp->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.expenses.reject', $exp->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span style="font-size: 12px; color: var(--text-muted);">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">Belum ada catatan pengeluaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Expense -->
<div id="expenseModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; width: 100%; max-width: 480px; padding: 28px;">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 18px;">Catat Pengeluaran Kas Baru</h3>
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kategori</label>
                <select name="category" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
                    <option value="operational">Operasional (Es batu, Gas, Galon, Plastik)</option>
                    <option value="raw_material">Beli Bahan Baku Tambahan</option>
                    <option value="utility">Listrik & Internet Wi-Fi</option>
                    <option value="cash_advance">Kas Bon Karyawan</option>
                    <option value="maintenance">Perawatan Alat & Toko</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Judul Pengeluaran</label>
                <input type="text" name="title" placeholder="contoh: Beli Es Batu 2 Karung" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nominal (Rp)</label>
                    <input type="number" name="amount" placeholder="30000" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tanggal</label>
                    <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);" required>
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Keterangan Tambahan</label>
                <textarea name="description" rows="2" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('expenseModal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection
