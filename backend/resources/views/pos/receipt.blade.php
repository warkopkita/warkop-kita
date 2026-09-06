<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
        }

        body {
            background: #fff;
            color: #000;
            padding: 10px;
            width: 320px;
            margin: 0 auto;
            font-size: 12px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .header {
            margin-bottom: 10px;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .item-row {
            margin-bottom: 6px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        @media print {
            .btn-print {
                display: none;
            }
            body {
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header text-center">
        <div class="store-name">{{ $storeSettings['name'] }}</div>
        <div>{{ $storeSettings['address'] }}</div>
        <div>Telp/WA: {{ $storeSettings['phone'] }}</div>
        <div style="font-size: 10px; margin-top: 4px;">{{ $storeSettings['tagline'] }}</div>
    </div>

    <div class="divider"></div>

    <div class="info-row">
        <span>No. Order:</span>
        <span class="bold">{{ $order->order_number }}</span>
    </div>
    <div class="info-row">
        <span>Waktu:</span>
        <span>{{ $order->created_at->format('d/m/y H:i') }}</span>
    </div>
    <div class="info-row">
        <span>Kasir:</span>
        <span>{{ $order->cashier->name ?? 'Kasir' }}</span>
    </div>
    <div class="info-row">
        <span>Tipe / Meja:</span>
        <span class="bold">{{ $order->type === 'dine_in' ? ($order->table ? 'Meja ' . $order->table->number : 'Dine In') : 'Takeaway' }}</span>
    </div>
    @if($order->customer_name)
    <div class="info-row">
        <span>Pelanggan:</span>
        <span>{{ $order->customer_name }}</span>
    </div>
    @endif

    <div class="divider"></div>

    @foreach($order->items as $item)
        <div class="item-row">
            <div class="bold">{{ $item->product_name }}</div>
            <div class="item-details">
                <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                <span class="bold">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @if(!empty($item->variant_options))
                <div style="font-size: 10px; color: #555;">
                    @foreach($item->variant_options as $k => $v)
                        + {{ is_array($v) ? ($v['name'] ?? '') : $v }}
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <div class="divider"></div>

    <div class="info-row">
        <span>Subtotal:</span>
        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
    </div>
    @if($order->discount_amount > 0)
    <div class="info-row">
        <span>Diskon:</span>
        <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
    </div>
    @endif
    <div class="info-row bold" style="font-size: 14px; margin: 4px 0;">
        <span>TOTAL:</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
    </div>
    <div class="info-row">
        <span>Bayar ({{ strtoupper($order->payment_method ?? 'TUNAI') }}):</span>
        <span>Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
    </div>
    <div class="info-row">
        <span>Kembalian:</span>
        <span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
    </div>

    <div class="divider"></div>

    <div class="text-center" style="font-size: 11px; margin-top: 8px;">
        <div class="bold">Wi-Fi: {{ $storeSettings['wifi_ssid'] }}</div>
        <div>Password: {{ $storeSettings['wifi_pass'] }}</div>
        <div style="margin-top: 10px;">Terima Kasih Atas Kunjungan Anda!</div>
        <div>Nikmati Hari Santaimu di Warkop Kita</div>
    </div>

    <button type="button" class="btn-print" onclick="window.print()">CETAK ULANG</button>
</body>
</html>
