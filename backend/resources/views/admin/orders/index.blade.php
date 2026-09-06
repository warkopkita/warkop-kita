@extends('layouts.app')

@section('title', 'Riwayat Pesanan')
@section('page_title', 'Daftar & Riwayat Pesanan')

@section('content')
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Order / Pelanggan..." style="padding: 10px 14px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 13px;">
            <select name="status" style="padding: 10px 14px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 13px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Sedang Dimasak (Preparing)</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Disajikan (Ready)</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <input type="date" name="date" value="{{ request('date') }}" style="padding: 10px 14px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 13px;">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Waktu</th>
                    <th>Pelanggan / Meja</th>
                    <th>Tipe</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status Order</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $ord)
                    <tr>
                        <td style="font-weight: 700; color: var(--primary);">{{ $ord->order_number }}</td>
                        <td>{{ $ord->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">{{ $ord->customer_name ?? 'Tamu' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $ord->table ? 'Meja ' . $ord->table->number : 'Tanpa Meja' }}</div>
                        </td>
                        <td>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: {{ $ord->type === 'dine_in' ? 'var(--primary)' : 'var(--accent-green)' }};">
                                {{ $ord->type === 'dine_in' ? 'Dine In' : 'Takeaway' }}
                            </span>
                        </td>
                        <td style="font-weight: 800; color: var(--primary-light);">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span style="font-size: 12px; text-transform: uppercase; font-weight: 600; color: var(--text-muted);">
                                {{ $ord->payment_method ?? 'Belum Bayar' }}
                            </span>
                        </td>
                        <td>
                            @if($ord->status === 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($ord->status === 'preparing')
                                <span class="badge badge-warning">Preparing</span>
                            @elseif($ord->status === 'ready')
                                <span class="badge badge-success" style="background: rgba(59, 130, 246, 0.2); color: #60A5FA;">Siap Diantar</span>
                            @elseif($ord->status === 'cancelled')
                                <span class="badge badge-danger">Batal</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($ord->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px;" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('pos.receipt', $ord->id) }}" target="_blank" class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px;" title="Cetak Struk">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">Belum ada riwayat pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
