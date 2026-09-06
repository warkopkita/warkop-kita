<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warkop Kita - Coffeehouse Admin Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-canvas: #1A1A1A;
            --bg-surface: #242424;
            --bg-card: #2C2C2C;
            --bg-card-hover: #363636;
            --border: #383838;
            --primary: #EB7943;
            --primary-hover: #D86833;
            --primary-light: rgba(235, 121, 67, 0.15);
            --text-main: #FFFFFF;
            --text-muted: #9E9E9E;
            --text-sub: #C4C4C4;
            --accent-green: #22C55E;
            --accent-red: #EF4444;
            --accent-yellow: #F59E0B;
            --radius-xl: 24px;
            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #0D0D0D;
            color: var(--text-main);
            height: 100vh;
            padding: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .app-frame {
            width: 100%;
            height: 100%;
            background: var(--bg-canvas);
            border-radius: var(--radius-xl);
            display: flex;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
        }

        /* 1. SLIM LEFT ICON SIDEBAR */
        .icon-sidebar {
            width: 76px;
            background: #181818;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            gap: 16px;
            flex-shrink: 0;
        }

        .app-logo {
            width: 46px;
            height: 46px;
            background: #282828;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
            border: 1px solid var(--border);
            cursor: pointer;
        }

        .nav-icons-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
            margin-top: 6px;
        }

        .nav-icon-btn {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: transparent;
            border: none;
            color: #7A7A7A;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .nav-icon-btn:hover {
            color: var(--text-main);
            background: #252525;
        }

        .nav-icon-btn.active {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid rgba(235, 121, 67, 0.4);
        }

        /* 2. MAIN ADMIN CONTENT */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 24px 28px;
            background: var(--bg-canvas);
        }

        .center-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .welcome-text h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .welcome-text p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: var(--bg-surface);
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg-card);
        }

        /* Category / Module Horizontal Tabs */
        .category-row {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 6px;
            margin-bottom: 20px;
            flex-shrink: 0;
            scrollbar-width: none;
        }

        .category-row::-webkit-scrollbar { display: none; }

        .category-item {
            width: 90px;
            height: 90px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .category-item .cat-icon {
            width: 36px;
            height: 36px;
            background: #2D2D2D;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--primary);
        }

        .category-item span {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-align: center;
        }

        .category-item:hover {
            background: var(--bg-card);
            border-color: #555;
        }

        .category-item.active {
            background: #252525;
            border: 2px solid var(--primary);
        }

        .category-item.active span {
            color: var(--text-main);
        }

        .category-item.active .cat-icon {
            background: var(--primary);
            color: #1A120B;
        }

        /* Content Panes */
        .content-scroll {
            flex: 1;
            overflow-y: auto;
            padding-right: 4px;
        }

        .content-scroll::-webkit-scrollbar { width: 6px; }
        .content-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }

        .tab-pane {
            display: none;
            animation: fadeIn 0.2s ease;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-info h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
        }

        .stat-info p {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 18px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        th {
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(56, 56, 56, 0.4);
        }

        .badge {
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .badge-success { background: rgba(34, 197, 94, 0.15); color: #4ADE80; }
        .badge-warning { background: rgba(245, 158, 11, 0.15); color: #FBBF24; }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #F87171; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .prod-box {
            background: #1C1C1C;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 12px;
            display: flex;
            flex-direction: column;
        }

        .prod-thumb {
            height: 100px;
            background: #121212;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 8px;
            overflow: hidden;
        }

        .prod-thumb img { width: 100%; height: 100%; object-fit: cover; }

        /* 3. RIGHT PROFILE / SUMMARY PANEL */
        .admin-sidebar-right {
            width: 320px;
            background: #1E1E1E;
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 24px;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: #2D2D2D;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--primary);
            font-size: 16px;
        }

        .quick-tile {
            background: #242424;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="app-frame">
        <!-- 1. SLIM LEFT ICON SIDEBAR -->
        <aside class="icon-sidebar">
            <div class="app-logo" title="Warkop Kita">
                <i class="fa-solid fa-mug-hot"></i>
            </div>

            <div class="nav-icons-group">
                <button class="nav-icon-btn active" onclick="switchNavTab('tab-dashboard', this)" title="Dashboard">
                    <i class="fa-solid fa-house"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-orders', this)" title="Pesanan">
                    <i class="fa-solid fa-receipt"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-products', this)" title="Menu & Produk">
                    <i class="fa-solid fa-utensils"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-tables', this)" title="Meja & QR">
                    <i class="fa-solid fa-qrcode"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-inventory', this)" title="Stok Bahan">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-attendance', this)" title="Absensi GPS">
                    <i class="fa-solid fa-user-check"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-expenses', this)" title="Kas Kecil">
                    <i class="fa-solid fa-wallet"></i>
                </button>
                <button class="nav-icon-btn" onclick="switchNavTab('tab-settings', this)" title="Pengaturan">
                    <i class="fa-solid fa-gear"></i>
                </button>
            </div>

            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-admin').submit();" class="nav-icon-btn" title="Logout">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
            <form id="logout-admin" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </aside>

        <!-- 2. MAIN CONTENT -->
        <main class="main-content">
            <header class="center-header">
                <div class="welcome-text">
                    <h1 id="headerTitle">Welcome to Admin Command Center</h1>
                    <p id="headerSubtitle">Kelola transaksi, menu, stok bahan, dan staf warkop</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('pos.index') }}" target="_blank" class="btn btn-primary">
                        <i class="fa-solid fa-cash-register"></i> Buka POS Kasir
                    </a>
                    <a href="{{ route('kds.index') }}" target="_blank" class="btn btn-secondary">
                        <i class="fa-solid fa-fire-burner"></i> KDS Barista
                    </a>
                </div>
            </header>

            <!-- Horizontal Module Tabs -->
            <div class="category-row">
                <div class="category-item active" onclick="switchNavTab('tab-dashboard', this)">
                    <div class="cat-icon"><i class="fa-solid fa-chart-pie"></i></div>
                    <span>Analitik</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-orders', this)">
                    <div class="cat-icon"><i class="fa-solid fa-receipt"></i></div>
                    <span>Pesanan</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-products', this)">
                    <div class="cat-icon"><i class="fa-solid fa-utensils"></i></div>
                    <span>Menu</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-tables', this)">
                    <div class="cat-icon"><i class="fa-solid fa-qrcode"></i></div>
                    <span>Meja & QR</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-inventory', this)">
                    <div class="cat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <span>Stok Bahan</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-attendance', this)">
                    <div class="cat-icon"><i class="fa-solid fa-user-check"></i></div>
                    <span>Absensi GPS</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-expenses', this)">
                    <div class="cat-icon"><i class="fa-solid fa-wallet"></i></div>
                    <span>Kas Bon</span>
                </div>
                <div class="category-item" onclick="switchNavTab('tab-settings', this)">
                    <div class="cat-icon"><i class="fa-solid fa-sliders"></i></div>
                    <span>Pengaturan</span>
                </div>
            </div>

            <!-- Content Body Panes -->
            <div class="content-scroll">
                <!-- TAB 1: ANALYTICS -->
                <div class="tab-pane active" id="tab-dashboard">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fa-solid fa-coins"></i></div>
                            <div class="stat-info">
                                <h3>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                                <p>Omset Hari Ini ({{ $todayOrderCount }} order)</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="color: var(--accent-green); background: rgba(34, 197, 94, 0.15);"><i class="fa-solid fa-arrow-trend-up"></i></div>
                            <div class="stat-info">
                                <h3>Rp {{ number_format($monthRevenue, 0, ',', '.') }}</h3>
                                <p>Omset Bulan Ini</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="color: var(--accent-yellow); background: rgba(245, 158, 11, 0.15);"><i class="fa-solid fa-scale-balanced"></i></div>
                            <div class="stat-info">
                                <h3>Rp {{ number_format($monthProfit, 0, ',', '.') }}</h3>
                                <p>Laba Bersih Bulan Ini</p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Trend Omset 7 Hari Terakhir</h3>
                        </div>
                        <div style="height: 220px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: ORDERS -->
                <div class="tab-pane" id="tab-orders">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-receipt" style="color: var(--primary);"></i> Riwayat Pesanan Masuk</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>No. Order</th>
                                    <th>Waktu</th>
                                    <th>Pelanggan</th>
                                    <th>Tipe</th>
                                    <th>Total</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $ord)
                                    <tr>
                                        <td style="font-weight: 800; color: var(--primary);">{{ $ord->order_number }}</td>
                                        <td>{{ $ord->created_at->format('H:i') }}</td>
                                        <td>{{ $ord->customer_name ?? 'Tamu' }}</td>
                                        <td><span class="badge {{ $ord->type === 'dine_in' ? 'badge-warning' : 'badge-success' }}">{{ strtoupper($ord->type) }}</span></td>
                                        <td style="font-weight: 800;">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                                        <td>{{ strtoupper($ord->payment_method ?? 'CASH') }}</td>
                                        <td><span class="badge badge-success">{{ ucfirst($ord->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: PRODUCTS -->
                <div class="tab-pane" id="tab-products">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-utensils" style="color: var(--primary);"></i> Menu & Produk Warkop</h3>
                            <button type="button" class="btn btn-primary" onclick="openAddMenuModal()">
                                <i class="fa-solid fa-plus"></i> Tambah Menu Baru
                            </button>
                        </div>
                        <div class="products-grid">
                            @foreach($products as $p)
                                <div class="prod-box">
                                    <div class="prod-thumb">
                                        @if($p->image && file_exists(public_path('storage/' . $p->image)))
                                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                                        @else
                                            @if(stripos($p->name, 'kopi') !== false || stripos($p->name, 'coffee') !== false)
                                                <i class="fa-solid fa-mug-hot"></i>
                                            @elseif(stripos($p->name, 'teh') !== false || stripos($p->name, 'jeruk') !== false || stripos($p->name, 'susu') !== false)
                                                <i class="fa-solid fa-glass-water"></i>
                                            @elseif(stripos($p->name, 'indomie') !== false || stripos($p->name, 'nasi') !== false || stripos($p->name, 'mie') !== false)
                                                <i class="fa-solid fa-bowl-food"></i>
                                            @else
                                                <i class="fa-solid fa-cookie-bite"></i>
                                            @endif
                                        @endif
                                    </div>
                                    <div style="font-size: 11px; color: var(--primary); font-weight: 700; text-transform: uppercase;">{{ $p->category->name ?? 'Menu' }}</div>
                                    <div style="font-size: 13.5px; font-weight: 700; margin: 4px 0;">{{ $p->name }}</div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 6px;">
                                        <div style="font-size: 14px; font-weight: 800; color: #FFF;">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                                        <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #7A7A7A; cursor: pointer;" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- TAB 4: TABLES -->
                <div class="tab-pane" id="tab-tables">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-qrcode" style="color: var(--primary);"></i> Manajemen Meja & QR Self-Order</h3>
                            <button type="button" class="btn btn-primary" onclick="openAddTableModal()">
                                <i class="fa-solid fa-plus"></i> Tambah Meja Baru
                            </button>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                            @foreach($tables as $tbl)
                                <div style="background: #1C1C1C; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 16px; text-align: center; display: flex; flex-direction: column; align-items: center;">
                                    <div style="font-weight: 800; font-size: 16px; color: #FFF;">{{ $tbl->name ?? 'Meja ' . $tbl->number }}</div>
                                    <span class="badge {{ $tbl->status === 'occupied' ? 'badge-warning' : 'badge-success' }}" style="margin: 6px 0 10px;">{{ $tbl->status === 'occupied' ? 'Terisi (Occupied)' : 'Kosong (Available)' }}</span>
                                    
                                    <div style="width: 110px; height: 110px; background: #fff; border-radius: 8px; padding: 6px; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/self-order?meja=' . $tbl->qr_code)) }}" style="width: 100%; height: 100%;">
                                    </div>
                                    
                                    <div style="font-size: 11px; color: var(--primary); font-family: monospace; font-weight: 700; margin-bottom: 10px;">{{ $tbl->qr_code }}</div>

                                    <div style="display: flex; gap: 6px; width: 100%; margin-top: auto;">
                                        <a href="{{ url('/self-order?meja=' . $tbl->qr_code) }}" target="_blank" class="btn btn-secondary" style="flex: 1; padding: 6px; font-size: 11px; justify-content: center;" title="Buka Menu Meja">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Tes
                                        </a>
                                        <form action="{{ route('admin.tables.destroy', $tbl->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary" style="padding: 6px 10px; font-size: 11px; color: #F87171;" title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- TAB 5: INVENTORY -->
                <div class="tab-pane" id="tab-inventory">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-boxes-stacked" style="color: var(--primary);"></i> Stok & Bahan Baku Warkop</h3>
                            <button type="button" class="btn btn-primary" onclick="openAddMaterialModal()">
                                <i class="fa-solid fa-plus"></i> Tambah Bahan Baku Baru
                            </button>
                        </div>
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
                                            <div style="font-weight: 700;">{{ $mat->name }}</div>
                                            <div style="font-size: 11px; color: var(--text-muted);">SKU: {{ $mat->sku }}</div>
                                        </td>
                                        <td style="font-weight: 800; font-size: 14px; color: {{ $mat->isLowStock() ? 'var(--accent-red)' : 'var(--primary)' }};">{{ $mat->current_stock }} {{ $mat->unit }}</td>
                                        <td>{{ $mat->min_stock_alert }} {{ $mat->unit }}</td>
                                        <td><span class="badge {{ $mat->isLowStock() ? 'badge-danger' : 'badge-success' }}">{{ $mat->isLowStock() ? 'Kritis' : 'Aman' }}</span></td>
                                        <td>
                                            <button type="button" class="btn btn-secondary" style="padding: 5px 10px; font-size: 11px;" onclick="openAdjustStockModal('{{ $mat->id }}', '{{ $mat->name }}', '{{ $mat->unit }}')">
                                                <i class="fa-solid fa-sliders"></i> Sesuaikan / Restock
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 6: ATTENDANCE -->
                <div class="tab-pane" id="tab-attendance">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-user-check" style="color: var(--primary);"></i> Kehadiran GPS Staf</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Jam Masuk</th>
                                    <th>Jarak GPS</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $att)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 700;">{{ $att->user->name }}</div>
                                            <div style="font-size: 11px; color: var(--primary);">{{ $att->user->role }}</div>
                                        </td>
                                        <td style="color: var(--accent-green); font-weight: 700;">{{ $att->clock_in }}</td>
                                        <td>{{ round($att->clock_in_distance_meters ?? 0) }} meter</td>
                                        <td><span class="badge badge-success">Valid</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada absensi hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 7: EXPENSES -->
                <div class="tab-pane" id="tab-expenses">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-wallet" style="color: var(--primary);"></i> Kas Kecil & Bon</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengeluaran</th>
                                    <th>Kategori</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $exp)
                                    <tr>
                                        <td>{{ $exp->expense_date->format('d/m/Y') }}</td>
                                        <td>{{ $exp->title }}</td>
                                        <td><span style="font-size: 11px; color: var(--primary); text-transform: uppercase;">{{ $exp->category }}</span></td>
                                        <td style="font-weight: 800;">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                        <td><span class="badge badge-success">Disetujui</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 8: SETTINGS -->
                <div class="tab-pane" id="tab-settings">
                    <div class="card" style="max-width: 650px;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa-solid fa-sliders" style="color: var(--primary);"></i> Profil & Geofence GPS</h3>
                        </div>
                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 12px;">
                                <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Warkop</label>
                                <input type="text" name="store_name" value="{{ $settings['store_name'] }}" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">No. WhatsApp</label>
                                <input type="text" name="store_phone" value="{{ $settings['store_phone'] }}" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Radius Geofence (Meter)</label>
                                <input type="number" name="geofence_radius_meters" value="{{ $settings['geofence_radius_meters'] }}" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- 3. RIGHT PANEL (PROFILE & SUMMARY) -->
        <aside class="admin-sidebar-right">
            <div class="profile-card">
                <div class="profile-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); display: block;">{{ Auth::user()->role ?? 'Admin' }}</span>
                    <strong style="font-size: 14px; color: var(--text-main);">{{ Auth::user()->name ?? 'Owner Warkop Kita' }}</strong>
                </div>
            </div>

            <div style="font-size: 13px; font-weight: 800; color: var(--text-main); margin-bottom: 12px;">
                Top 5 Menu Terlaris
            </div>

            @foreach($topProducts as $idx => $tp)
                <div class="quick-tile">
                    <div style="display: flex; justify-content: space-between; font-size: 12.5px; font-weight: 700;">
                        <span>#{{ $idx + 1 }} {{ $tp->product_name }}</span>
                        <span style="color: var(--primary);">Rp {{ number_format($tp->total_sales, 0, ',', '.') }}</span>
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ $tp->total_qty }} porsi terjual</div>
                </div>
            @endforeach

            <div style="margin-top: auto; padding-top: 20px;">
                <div style="background: rgba(235, 121, 67, 0.1); border: 1px solid rgba(235, 121, 67, 0.3); border-radius: var(--radius-md); padding: 14px; text-align: center;">
                    <div style="font-size: 12px; color: var(--primary); font-weight: 700;">Warkop Kita Ecosystem</div>
                    <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 4px;">Laravel 11 &bull; Docker MySQL &bull; Flutter App</div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Modal Tambah Menu -->
    <div class="pos-modal" id="modalAddMenu" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #242424; border: 1px solid var(--border); border-radius: var(--radius-xl); width: 100%; max-width: 500px; padding: 28px;">
            <h3 style="font-size: 18px; font-weight: 800; color: #FFF; margin-bottom: 16px;">
                <i class="fa-solid fa-plus" style="color: var(--primary);"></i> Tambah Menu Baru
            </h3>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Kategori Menu</label>
                    <select name="category_id" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Menu / Makanan / Minuman</label>
                    <input type="text" name="name" placeholder="contoh: Kopi Sanger Dingin" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Harga Jual (Rp)</label>
                    <input type="number" name="price" placeholder="10000" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="contoh: Kopi sanger khas Aceh dengan susu kental manis legit" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddMenuModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Menu Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Meja -->
    <div class="pos-modal" id="modalAddTable" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #242424; border: 1px solid var(--border); border-radius: var(--radius-xl); width: 100%; max-width: 440px; padding: 28px;">
            <h3 style="font-size: 18px; font-weight: 800; color: #FFF; margin-bottom: 16px;">
                <i class="fa-solid fa-plus" style="color: var(--primary);"></i> Tambah Meja Baru
            </h3>
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Nomor Meja</label>
                    <input type="text" name="number" placeholder="contoh: 11 atau VIP-03" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Label Meja (Opsional)</label>
                    <input type="text" name="name" placeholder="contoh: Meja Lesehan Depan" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Kapasitas Duduk (Orang)</label>
                    <input type="number" name="capacity" value="4" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddTableModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Meja Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Bahan Baku -->
    <div class="pos-modal" id="modalAddMaterial" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #242424; border: 1px solid var(--border); border-radius: var(--radius-xl); width: 100%; max-width: 480px; padding: 28px;">
            <h3 style="font-size: 18px; font-weight: 800; color: #FFF; margin-bottom: 16px;">
                <i class="fa-solid fa-plus" style="color: var(--primary);"></i> Tambah Bahan Baku Baru
            </h3>
            <form action="{{ route('admin.inventory.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Bahan Baku</label>
                    <input type="text" name="name" placeholder="contoh: Biji Kopi Arabika Gayo" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Satuan (Unit)</label>
                        <input type="text" name="unit" placeholder="kg / liter / pcs / kaleng" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Stok Awal</label>
                        <input type="number" step="0.1" name="current_stock" placeholder="10" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Batas Peringatan Min.</label>
                        <input type="number" step="0.1" name="min_stock_alert" value="5" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Supplier (Opsional)</label>
                        <input type="text" name="supplier" placeholder="Agen Kopi" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddMaterialModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Bahan Baku</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Adjust Stok -->
    <div class="pos-modal" id="modalAdjustStock" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #242424; border: 1px solid var(--border); border-radius: var(--radius-xl); width: 100%; max-width: 440px; padding: 28px;">
            <h3 style="font-size: 18px; font-weight: 800; color: #FFF; margin-bottom: 4px;">
                <i class="fa-solid fa-sliders" style="color: var(--primary);"></i> Sesuaikan / Restock
            </h3>
            <p id="adjustMatNameLabel" style="font-size: 13px; color: var(--primary); margin-bottom: 16px;"></p>
            <form id="adjustStockForm" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Jenis Penyesuaian</label>
                    <select name="type" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;">
                        <option value="in">Stok Masuk (Restock +)</option>
                        <option value="out">Stok Keluar (-)</option>
                        <option value="waste">Bahan Rusak / Basi (Waste -)</option>
                        <option value="adjustment">Koreksi Stok Aktual (=)</option>
                    </select>
                </div>

                <div style="margin-bottom: 14px;">
                    <label id="adjustQtyUnitLabel" style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Jumlah</label>
                    <input type="number" step="0.01" name="quantity" placeholder="0" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;" required>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Catatan / Keterangan</label>
                    <input type="text" name="notes" placeholder="contoh: Belanja mingguan pasar" style="width: 100%; padding: 10px; background: #1C1C1C; border: 1px solid var(--border); border-radius: 8px; color: #FFF;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAdjustStockModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Stok</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddMenuModal() {
            document.getElementById('modalAddMenu').style.display = 'flex';
        }

        function closeAddMenuModal() {
            document.getElementById('modalAddMenu').style.display = 'none';
        }

        function openAddTableModal() {
            document.getElementById('modalAddTable').style.display = 'flex';
        }

        function closeAddTableModal() {
            document.getElementById('modalAddTable').style.display = 'none';
        }

        function openAddMaterialModal() {
            document.getElementById('modalAddMaterial').style.display = 'flex';
        }

        function closeAddMaterialModal() {
            document.getElementById('modalAddMaterial').style.display = 'none';
        }

        function openAdjustStockModal(id, name, unit) {
            document.getElementById('adjustMatNameLabel').innerText = name;
            document.getElementById('adjustQtyUnitLabel').innerText = 'Jumlah (' + unit + ')';
            document.getElementById('adjustStockForm').action = '/admin/inventory/' + id + '/adjust';
            document.getElementById('modalAdjustStock').style.display = 'flex';
        }

        function closeAdjustStockModal() {
            document.getElementById('modalAdjustStock').style.display = 'none';
        }

        function switchNavTab(tabId, elem) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.category-item').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.nav-icon-btn').forEach(b => b.classList.remove('active'));

            const target = document.getElementById(tabId);
            if (target) {
                target.classList.add('active');
            }

            if (elem.classList.contains('category-item') || elem.classList.contains('nav-icon-btn')) {
                elem.classList.add('active');
            }
        }

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Omset',
                    data: {!! json_encode($chartRevenue) !!},
                    borderColor: '#EB7943',
                    backgroundColor: 'rgba(235, 121, 67, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#EB7943',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(56, 56, 56, 0.4)' }, ticks: { color: '#9E9E9E', font: { size: 10 } } },
                    y: { grid: { color: 'rgba(56, 56, 56, 0.4)' }, ticks: { color: '#9E9E9E', font: { size: 10 }, callback: v => 'Rp ' + (v/1000) + 'k' } }
                }
            }
        });
    </script>
</body>
</html>
