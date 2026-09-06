@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)
@section('page_title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-list-check text-amber"></i> Rincian Menu Pesanan</h3>
            <a href="{{ route('pos.receipt', $order->id) }}" target="_blank" class="btn btn-primary" style="padding: 8px 14px; font-size: 13px;">
                <i class="fa-solid fa-print"></i> Cetak Struk Kasir
            </a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--text-main);">{{ $item->product_name }}</div>
                                @if(!empty($item->variant_options))
                                    <div style="font-size: 11px; color: var(--primary);">
                                        @foreach($item->variant_options as $k => $v)
                                            <span>{{ is_array($v) ? ($v['name'] ?? '') : $v }} </span>
                                        @endforeach
                                    </div>
                                @endif
                                @if($item->notes)
                                    <div style="font-size: 11px; color: var(--text-muted); font-style: italic;">Catatan: {{ $item->notes }}</div>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td style="font-weight: 700;">x{{ $item->quantity }}</td>
                            <td style="font-weight: 800; color: var(--primary-light);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 8px; max-width: 320px; margin-left: auto;">
            <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted);">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--accent-green);">
                    <span>Diskon Voucher</span>
                    <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($order->tax_amount > 0)
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted);">
                    <span>Pajak (PB1)</span>
                    <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; color: var(--primary); padding-top: 8px; border-top: 1px solid rgba(56, 48, 42, 0.5);">
                <span>Total Akhir</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Order Metadata & Actions -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-circle-info text-amber"></i> Info Pesanan</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                <div>
                    <span style="color: var(--text-muted);">Nama Pelanggan:</span>
                    <strong style="color: var(--text-main); display: block;">{{ $order->customer_name ?? 'Tamu' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted);">Nomor Meja:</span>
                    <strong style="color: var(--primary); display: block;">{{ $order->table ? 'Meja ' . $order->table->number : 'Takeaway (Bungkus)' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted);">Waktu Transaksi:</span>
                    <strong style="color: var(--text-main); display: block;">{{ $order->created_at->format('d F Y, H:i:s') }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted);">Status Pembayaran:</span>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-danger' }}" style="margin-top: 4px;">
                        {{ strtoupper($order->payment_status) }} ({{ strtoupper($order->payment_method ?? 'TUNAI') }})
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-rotate text-amber"></i> Ubah Status Pesanan</h3>
            </div>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group" style="margin-bottom: 14px;">
                    <select name="status" style="width: 100%; padding: 10px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main);">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Sedang Dimasak (Preparing)</option>
                        <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Siap Disajikan (Ready)</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Perbarui Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
