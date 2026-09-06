<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDS - Kitchen Display System - Warkop Kita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-dark: #0A0908;
            --bg-ticket: #181512;
            --border: #332B25;
            --primary: #D4A373;
            --text-main: #F4EAE0;
            --text-muted: #A89F91;
            --yellow: #F59E0B;
            --blue: #3B82F6;
            --green: #10B981;
            --red: #EF4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            user-select: none;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top KDS Header */
        .kds-header {
            height: 64px;
            background: #14110E;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }

        .kds-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .kds-brand i {
            font-size: 24px;
            color: var(--primary);
        }

        .kds-brand h1 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .kds-stats {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .stat-pill {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-pill.active-count {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #FBBF24;
        }

        /* Tickets Canvas */
        .kds-body {
            flex: 1;
            padding: 24px;
            overflow-x: auto;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .ticket-card {
            width: 320px;
            flex-shrink: 0;
            background: var(--bg-ticket);
            border: 2px solid var(--ticket-border, var(--border));
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: all 0.2s;
        }

        .ticket-header {
            padding: 14px 18px;
            background: var(--ticket-head-bg, #1F1A15);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-table {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
        }

        .ticket-timer {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.3);
        }

        .ticket-meta {
            padding: 10px 18px;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .ticket-items {
            padding: 16px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
        }

        .item-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(56, 48, 42, 0.5);
        }

        .item-qty {
            width: 28px;
            height: 28px;
            background: rgba(212, 163, 115, 0.15);
            border: 1px solid var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            color: var(--primary);
            flex-shrink: 0;
        }

        .item-text h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
        }

        .item-text span {
            font-size: 12px;
            color: var(--primary);
            display: block;
        }

        .item-notes {
            font-size: 11px;
            color: #F87171;
            font-weight: 600;
            margin-top: 2px;
        }

        .ticket-footer {
            padding: 14px 18px;
            background: #14110E;
            border-top: 1px solid var(--border);
        }

        .btn-action {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-action.btn-preparing {
            background: linear-gradient(135deg, var(--yellow), #D97706);
            color: #000;
        }

        .btn-action.btn-ready {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: #fff;
        }

        .btn-action.btn-complete {
            background: linear-gradient(135deg, var(--green), #059669);
            color: #fff;
        }

        .empty-kds {
            margin: auto;
            text-align: center;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <header class="kds-header">
        <div class="kds-brand">
            <i class="fa-solid fa-fire-burner"></i>
            <div>
                <h1>KITCHEN DISPLAY SYSTEM (KDS)</h1>
                <span style="font-size: 11px; color: var(--text-muted);">Layar Antrian Dapur & Barista Warkop Kita</span>
            </div>
        </div>

        <div class="kds-stats">
            <div class="stat-pill active-count" id="activeCountBadge">
                <i class="fa-solid fa-clock"></i>
                <span id="ticketCountText">{{ $orders->count() }} Pesanan Aktif</span>
            </div>
            <a href="{{ route('pos.index') }}" target="_blank" style="color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-cash-register"></i> Kasir POS
            </a>
        </div>
    </header>

    <main class="kds-body" id="ticketsContainer">
        @forelse($orders as $ord)
            <div class="ticket-card" id="ticket-{{ $ord->id }}" style="--ticket-border: {{ $ord->status === 'preparing' ? '#F59E0B' : ($ord->status === 'ready' ? '#3B82F6' : '#D4A373') }};">
                <div class="ticket-header" style="background: {{ $ord->status === 'preparing' ? 'rgba(245, 158, 11, 0.15)' : ($ord->status === 'ready' ? 'rgba(59, 130, 246, 0.15)' : 'rgba(212, 163, 115, 0.15)') }};">
                    <div class="ticket-table">
                        {{ $ord->type === 'dine_in' ? ($ord->table ? 'Meja ' . $ord->table->number : 'Dine In') : 'TAKEAWAY (BUNGKUS)' }}
                    </div>
                    <div class="ticket-timer">
                        {{ $ord->created_at->diffForHumans(null, true) }}
                    </div>
                </div>

                <div class="ticket-meta">
                    <span>#{{ $ord->order_number }}</span>
                    <span>{{ $ord->customer_name ?? 'Tamu' }}</span>
                </div>

                <div class="ticket-items">
                    @foreach($ord->items as $item)
                        <div class="item-row">
                            <div class="item-qty">{{ $item->quantity }}x</div>
                            <div class="item-text">
                                <h4>{{ $item->product_name }}</h4>
                                @if(!empty($item->variant_options))
                                    @foreach($item->variant_options as $k => $v)
                                        <span>+ {{ is_array($v) ? ($v['name'] ?? '') : $v }}</span>
                                    @endforeach
                                @endif
                                @if($item->notes)
                                    <div class="item-notes"><i class="fa-solid fa-comment-dots"></i> {{ $item->notes }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($ord->notes)
                        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); padding: 8px; border-radius: 8px; font-size: 11px; color: #FCA5A5;">
                            <strong>Note Order:</strong> {{ $ord->notes }}
                        </div>
                    @endif
                </div>

                <div class="ticket-footer">
                    @if($ord->status === 'pending' || $ord->status === 'confirmed')
                        <button class="btn-action btn-preparing" onclick="updateOrderStatus({{ $ord->id }}, 'preparing')">
                            <i class="fa-solid fa-fire"></i> Mulai Masak / Racik
                        </button>
                    @elseif($ord->status === 'preparing')
                        <button class="btn-action btn-ready" onclick="updateOrderStatus({{ $ord->id }}, 'ready')">
                            <i class="fa-solid fa-bell"></i> Tandai Siap Diantar
                        </button>
                    @elseif($ord->status === 'ready')
                        <button class="btn-action btn-complete" onclick="updateOrderStatus({{ $ord->id }}, 'completed')">
                            <i class="fa-solid fa-circle-check"></i> Selesai Disajikan
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-kds" id="emptyMessage">
                <i class="fa-solid fa-check-circle" style="font-size: 48px; color: var(--green); margin-bottom: 12px; display: block;"></i>
                <h2>Semua Pesanan Selesai!</h2>
                <p style="margin-top: 4px;">Dapur & Barista siap menerima pesanan berikutnya.</p>
            </div>
        @endforelse
    </main>

    <script>
        async function updateOrderStatus(orderId, status) {
            try {
                const res = await fetch('/kds/orders/' + orderId + '/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                });

                if (res.ok) {
                    location.reload();
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Auto polling every 6 seconds for new orders
        setInterval(async () => {
            try {
                const res = await fetch('{{ route("kds.api.orders") }}');
                const json = await res.json();
                if (json.success) {
                    document.getElementById('ticketCountText').innerText = json.data.length + ' Pesanan Aktif';
                }
            } catch (e) {}
        }, 6000);
    </script>
</body>
</html>
