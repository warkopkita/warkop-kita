<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Warkop Kita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-body: #12100E;
            --bg-sidebar: #1A1613;
            --bg-card: #221D18;
            --bg-card-hover: #2B2520;
            --border-color: #38302A;
            --primary: #D4A373;
            --primary-dark: #A67342;
            --primary-light: #FAEDCD;
            --text-main: #F4EAE0;
            --text-muted: #A89F91;
            --accent-green: #10B981;
            --accent-red: #EF4444;
            --accent-yellow: #F59E0B;
            --accent-blue: #3B82F6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1A120B;
            font-size: 20px;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(212, 163, 115, 0.3);
        }

        .brand-text h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-light);
            letter-spacing: -0.5px;
        }

        .brand-text p {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            padding: 20px 12px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .nav-item:hover {
            background: var(--bg-card);
            color: var(--text-main);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(212, 163, 115, 0.15), rgba(212, 163, 115, 0.05));
            color: var(--primary);
            font-weight: 600;
            border-left: 3px solid var(--primary);
        }

        .user-profile {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: #1A120B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .user-details h4 {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-details span {
            font-size: 11px;
            color: var(--primary);
            text-transform: capitalize;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-navbar {
            height: 70px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .page-title h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
        }

        .quick-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #1A120B;
            box-shadow: 0 4px 12px rgba(212, 163, 115, 0.25);
        }

        .btn-primary:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-card-hover);
        }

        .btn-success {
            background: var(--accent-green);
            color: #fff;
        }

        .btn-danger {
            background: var(--accent-red);
            color: #fff;
        }

        .content-body {
            padding: 32px;
            flex: 1;
        }

        /* Card Styles */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Alert notifications */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34D399;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #F87171;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <div class="brand-text">
                <h2>Warkop Kita</h2>
                <p>Ecosystem Hub</p>
            </div>
        </div>

        <nav class="nav-menu">
            <div class="nav-label">Operasional Kasir</div>
            <a href="{{ route('pos.index') }}" class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}" target="_blank">
                <i class="fa-solid fa-cash-register"></i>
                <span>POS Kasir</span>
            </a>
            <a href="{{ route('kds.index') }}" class="nav-item {{ request()->routeIs('kds.*') ? 'active' : '' }}" target="_blank">
                <i class="fa-solid fa-fire-burner"></i>
                <span>Kitchen Display (KDS)</span>
            </a>
            <a href="{{ route('landing') }}" class="nav-item" target="_blank">
                <i class="fa-solid fa-globe"></i>
                <span>Lihat Landing Page</span>
            </a>

            <div class="nav-label">Manajemen Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard Analitik</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i>
                <span>Riwayat Pesanan</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i>
                <span>Menu & Produk</span>
            </a>
            <a href="{{ route('admin.tables.index') }}" class="nav-item {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                <i class="fa-solid fa-qrcode"></i>
                <span>Meja & QR Code</span>
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Stok Bahan Baku</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-check"></i>
                <span>Absensi & GPS</span>
            </a>
            <a href="{{ route('admin.expenses.index') }}" class="nav-item {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i>
                <span>Kas Kecil & Bon</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i>
                <span>Pengaturan Warkop</span>
            </a>
        </nav>

        <div class="user-profile">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="user-details">
                    <h4>{{ Auth::user()->name ?? 'Guest User' }}</h4>
                    <span>{{ Auth::user()->role ?? 'Staff' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" style="padding: 8px 12px;" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="page-title">
                <h1>@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="quick-nav">
                <a href="{{ route('pos.index') }}" class="btn btn-primary" target="_blank">
                    <i class="fa-solid fa-cash-register"></i>
                    Buka Kasir POS
                </a>
                <a href="{{ route('kds.index') }}" class="btn btn-secondary" target="_blank">
                    <i class="fa-solid fa-fire-burner"></i>
                    Buka KDS Barista
                </a>
            </div>
        </header>

        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
