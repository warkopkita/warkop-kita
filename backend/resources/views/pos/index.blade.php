<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warkop Kita - Coffeehouse POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-canvas: #1A1A1A;
            --bg-surface: #242424;
            --bg-card: #2C2C2C;
            --bg-card-hover: #363636;
            --bg-input: #1F1F1F;
            --border: #383838;
            --primary: #EB7943;
            --primary-hover: #D86833;
            --primary-light: rgba(235, 121, 67, 0.15);
            --text-main: #FFFFFF;
            --text-muted: #9E9E9E;
            --text-sub: #C4C4C4;
            --accent-green: #22C55E;
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
            user-select: none;
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

        /* App Outer Frame */
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
            gap: 20px;
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
            transition: all 0.2s;
        }

        .app-logo:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .nav-icons-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 10px;
            flex: 1;
        }

        .nav-icon-btn {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: transparent;
            border: none;
            color: #7A7A7A;
            font-size: 18px;
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

        /* 2. CENTER MAIN CONTENT */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 24px 28px;
            background: var(--bg-canvas);
        }

        /* Top Header */
        .center-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
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

        .header-search {
            position: relative;
            width: 320px;
        }

        .header-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #7A7A7A;
            font-size: 14px;
        }

        .header-search input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 13px;
            outline: none;
            transition: all 0.2s;
        }

        .header-search input:focus {
            border-color: var(--primary);
        }

        /* Horizontal Category Cards */
        .category-row {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 8px;
            margin-bottom: 24px;
            scrollbar-width: none;
        }

        .category-row::-webkit-scrollbar {
            display: none;
        }

        .category-item {
            width: 86px;
            height: 96px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .category-item .cat-icon {
            width: 38px;
            height: 38px;
            background: #2D2D2D;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--primary);
        }

        .category-item span {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
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

        /* Menu Section Title */
        .section-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-bar h2 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
        }

        .section-bar span {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Products Grid */
        .products-scroll {
            flex: 1;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding-right: 4px;
            align-content: start;
        }

        .products-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .products-scroll::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 4px;
        }

        /* Coffeehouse Product Card */
        .product-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: all 0.2s;
        }

        .product-card:hover {
            border-color: #4A4A4A;
            background: var(--bg-card);
        }

        .product-top {
            display: flex;
            gap: 14px;
        }

        .product-thumb {
            width: 90px;
            height: 90px;
            background: #181818;
            border-radius: var(--radius-md);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 32px;
            color: var(--primary);
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-info h3 {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.3;
        }

        .product-info p {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-info .price {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-main);
            margin-top: auto;
        }

        /* Option Pills inside Card */
        .variant-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .variant-label {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .pill-options {
            display: flex;
            gap: 6px;
        }

        .opt-pill {
            padding: 4px 8px;
            background: #1C1C1C;
            border: 1px solid var(--border);
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
        }

        .opt-pill:hover {
            color: var(--text-main);
            border-color: #555;
        }

        .opt-pill.active {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(235, 121, 67, 0.1);
        }

        .btn-add-billing {
            width: 100%;
            padding: 10px;
            background: var(--primary);
            border: none;
            border-radius: var(--radius-md);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-add-billing:hover {
            background: var(--primary-hover);
        }

        .btn-view-details {
            width: 100%;
            padding: 10px;
            background: #333333;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-sub);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            text-align: center;
        }

        .btn-view-details:hover {
            background: #3D3D3D;
            color: #FFF;
        }

        /* 3. RIGHT PANEL (BILLS / CART) */
        .bills-sidebar {
            width: 350px;
            background: #1E1E1E;
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 24px;
            flex-shrink: 0;
        }

        /* Profile Bar */
        .bills-profile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .profile-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-avatar {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            background: #333;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--primary);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details span {
            font-size: 11px;
            color: var(--text-muted);
            display: block;
        }

        .profile-details strong {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-main);
        }

        .btn-bell {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: #282828;
            border: 1px solid var(--border);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .bills-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bills-table-select {
            padding: 4px 8px;
            background: #141414;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            outline: none;
        }

        /* Cart Items List */
        .bills-items {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding-right: 4px;
        }

        .bills-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bills-item-img {
            width: 50px;
            height: 50px;
            background: #141414;
            border-radius: var(--radius-md);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary);
            flex-shrink: 0;
        }

        .bills-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bills-item-info {
            flex: 1;
        }

        .bills-item-info h4 {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        .bills-item-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }

        .qty-badge {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
        }

        .notes-pill {
            padding: 2px 6px;
            background: #2D2D2D;
            border-radius: 4px;
            font-size: 10px;
            color: #7A7A7A;
            cursor: pointer;
        }

        .bills-item-price {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--text-main);
            text-align: right;
        }

        .empty-bills {
            margin: auto;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* Summary & Checkout Button */
        .bills-summary {
            padding-top: 18px;
            border-top: 1px dashed var(--border);
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 14px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--text-muted);
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 800;
            color: var(--text-main);
            margin-top: 4px;
            padding-top: 6px;
        }

        .btn-checkout {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            border: none;
            border-radius: var(--radius-md);
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(235, 121, 67, 0.3);
            margin-top: 14px;
            transition: all 0.2s;
        }

        .btn-checkout:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Modal Simple */
        .pos-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .pos-modal.active {
            display: flex;
        }

        .pos-modal-box {
            background: #242424;
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            width: 100%;
            max-width: 440px;
            padding: 28px;
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
                <a href="{{ route('pos.index') }}" class="nav-icon-btn active" title="POS Kasir">
                    <i class="fa-solid fa-house"></i>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="nav-icon-btn" title="Dashboard Admin">
                    <i class="fa-solid fa-chart-pie"></i>
                </a>
                <a href="{{ route('kds.index') }}" class="nav-icon-btn" title="KDS Dapur / Barista">
                    <i class="fa-solid fa-fire-burner"></i>
                </a>
                <a href="{{ route('landing') }}" class="nav-icon-btn" title="Landing Page">
                    <i class="fa-solid fa-globe"></i>
                </a>
            </div>

            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-icon-btn" title="Logout">
                <i class="fa-solid fa-gear"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </aside>

        <!-- 2. CENTER MAIN CONTENT -->
        <main class="main-content">
            <!-- Header Bar -->
            <header class="center-header">
                <div class="welcome-text">
                    <h1>Welcome to {{ $storeSettings['name'] ?? 'Coffeehouse' }}</h1>
                    <p>Choose the category & customize your order</p>
                </div>

                <div class="header-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search something..." oninput="searchProducts()">
                </div>
            </header>

            <!-- Category Horizontal Cards -->
            <div class="category-row">
                <div class="category-item active" onclick="filterCategory('all', this)">
                    <div class="cat-icon"><i class="fa-solid fa-border-all"></i></div>
                    <span>All menu</span>
                </div>
                @foreach($categories as $cat)
                    <div class="category-item" onclick="filterCategory('{{ $cat->id }}', this)">
                        <div class="cat-icon">
                            @if(stripos($cat->name, 'kopi') !== false || stripos($cat->name, 'coffee') !== false)
                                <i class="fa-solid fa-mug-hot"></i>
                            @elseif(stripos($cat->name, 'susu') !== false || stripos($cat->name, 'milk') !== false)
                                <i class="fa-solid fa-glass-water"></i>
                            @elseif(stripos($cat->name, 'makan') !== false || stripos($cat->name, 'mie') !== false)
                                <i class="fa-solid fa-bowl-food"></i>
                            @elseif(stripos($cat->name, 'snack') !== false || stripos($cat->name, 'roti') !== false)
                                <i class="fa-solid fa-cookie-bite"></i>
                            @else
                                <i class="fa-solid fa-utensils"></i>
                            @endif
                        </div>
                        <span>{{ $cat->name }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Menu Title Bar -->
            <div class="section-bar">
                <h2 id="currentCategoryTitle">Coffee menu</h2>
                <span id="resultCountText">{{ $products->count() }} items result</span>
            </div>

            <!-- Products Grid with In-Card Variant Pickers -->
            <div class="products-scroll" id="productsGrid">
                @foreach($products as $p)
                    <div class="product-card" data-category="{{ $p->category_id }}" data-name="{{ strtolower($p->name) }}">
                        <div class="product-top">
                            <div class="product-thumb">
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
                            <div class="product-info">
                                <h3>{{ $p->name }}</h3>
                                <p>{{ $p->description ?? 'Racikan kopi spesial Warkop Kita dengan cita rasa otentik.' }}</p>
                                <div class="price">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <!-- Variant Pill Options (Sugar & Ice) -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <div class="variant-group">
                                <span class="variant-label">Sugar</span>
                                <div class="pill-options">
                                    <span class="opt-pill active" onclick="selectPill(this)">Normal</span>
                                    <span class="opt-pill" onclick="selectPill(this)">Less</span>
                                    <span class="opt-pill" onclick="selectPill(this)">0%</span>
                                </div>
                            </div>
                            <div class="variant-group">
                                <span class="variant-label">Ice</span>
                                <div class="pill-options">
                                    <span class="opt-pill active" onclick="selectPill(this)">Normal</span>
                                    <span class="opt-pill" onclick="selectPill(this)">Less</span>
                                    <span class="opt-pill" onclick="selectPill(this)">Hot</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-add-billing" onclick="addToBills({{ json_encode($p) }})">
                            <i class="fa-solid fa-plus"></i> Add to billing
                        </button>
                    </div>
                @endforeach
            </div>
        </main>

        <!-- 3. RIGHT PANEL (BILLS / CART) -->
        <aside class="bills-sidebar">
            <!-- Staff Profile Header -->
            <div class="bills-profile">
                <div class="profile-user">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="profile-details">
                        <span>{{ Auth::user()->role ?? 'Admin' }}</span>
                        <strong>{{ Auth::user()->name ?? 'Sheilla Poetri' }}</strong>
                    </div>
                </div>

                <div class="btn-bell" title="Notifikasi">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>

            <!-- Bills Section Header -->
            <div class="bills-title">
                <span>Bills</span>
                <select class="bills-table-select" id="tableSelector">
                    <option value="">-- Meja --</option>
                    @foreach($tables as $tbl)
                        <option value="{{ $tbl->id }}">Meja {{ $tbl->number }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Items in Bills -->
            <div class="bills-items" id="billsList">
                <div class="empty-bills" id="emptyBillsMsg">
                    <i class="fa-solid fa-bag-shopping" style="font-size: 32px; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                    No item in billing yet.<br>Click "Add to billing" to start.
                </div>
            </div>

            <!-- Summary & Checkout Button -->
            <div class="bills-summary">
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span id="subtotalText">Rp 0</span>
                </div>
                <div class="summary-line">
                    <span>Discount</span>
                    <span style="color: var(--accent-green);" id="discountText">Rp 0</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="totalText" style="color: var(--primary);">Rp 0</span>
                </div>

                <button type="button" class="btn-checkout" onclick="openCheckoutModal()">
                    Checkout
                </button>
            </div>
        </aside>
    </div>

    <!-- Modal Checkout Simple -->
    <div class="pos-modal" id="checkoutModal">
        <div class="pos-modal-box">
            <h3 style="font-size: 18px; font-weight: 800; color: #FFF; margin-bottom: 6px;">Konfirmasi Checkout</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Selesaikan pembayaran pesanan</p>

            <div style="background: #1A1A1A; padding: 14px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 16px;">
                <div style="font-size: 12px; color: var(--text-muted);">Total Tagihan:</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--primary);" id="modalTotalPay">Rp 0</div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Metode Pembayaran</label>
                <select id="modalPayMethod" style="width: 100%; padding: 10px; background: #1A1A1A; border: 1px solid var(--border); border-radius: 8px; color: #FFF;">
                    <option value="cash">Tunai (Cash)</option>
                    <option value="qris">QRIS (Scan Barcode)</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn-view-details" style="flex: 1;" onclick="document.getElementById('checkoutModal').classList.remove('active')">Batal</button>
                <button type="button" class="btn-add-billing" style="flex: 2; margin-top: 0;" onclick="submitCheckout()">
                    <i class="fa-solid fa-print"></i> Bayar & Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <script>
        let bills = [];
        let grandTotal = 0;

        function selectPill(elem) {
            const siblings = elem.parentElement.querySelectorAll('.opt-pill');
            siblings.forEach(s => s.classList.remove('active'));
            elem.classList.add('active');
        }

        function filterCategory(catId, elem) {
            document.querySelectorAll('.category-item').forEach(c => c.classList.remove('active'));
            elem.classList.add('active');

            const cards = document.querySelectorAll('.product-card');
            let count = 0;
            cards.forEach(card => {
                if (catId === 'all' || card.dataset.category === catId) {
                    card.style.display = 'flex';
                    count++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('currentCategoryTitle').innerText = elem.querySelector('span').innerText + ' menu';
            document.getElementById('resultCountText').innerText = count + ' items result';
        }

        function searchProducts() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let count = 0;
            cards.forEach(card => {
                if (card.dataset.name.includes(query)) {
                    card.style.display = 'flex';
                    count++;
                } else {
                    card.style.display = 'none';
                }
            });
            document.getElementById('resultCountText').innerText = count + ' items result';
        }

        function addToBills(product) {
            const existing = bills.find(b => b.product.id === product.id);
            if (existing) {
                existing.quantity += 1;
            } else {
                bills.push({
                    product: product,
                    quantity: 1,
                    unit_price: parseFloat(product.price)
                });
            }
            renderBills();
        }

        function renderBills() {
            const container = document.getElementById('billsList');
            const emptyMsg = document.getElementById('emptyBillsMsg');

            if (bills.length === 0) {
                container.innerHTML = '';
                container.appendChild(emptyMsg);
                document.getElementById('subtotalText').innerText = 'Rp 0';
                document.getElementById('totalText').innerText = 'Rp 0';
                grandTotal = 0;
                return;
            }

            container.innerHTML = '';
            let subtotal = 0;

            bills.forEach((item, idx) => {
                const itemSub = item.unit_price * item.quantity;
                subtotal += itemSub;

                const div = document.createElement('div');
                div.className = 'bills-item';
                div.innerHTML = `
                    <div class="bills-item-img">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <div class="bills-item-info">
                        <h4>${item.product.name}</h4>
                        <div class="bills-item-meta">
                            <span class="qty-badge">x${item.quantity}</span>
                            <span class="notes-pill" onclick="changeQty(${idx}, 1)">+</span>
                            <span class="notes-pill" onclick="changeQty(${idx}, -1)">-</span>
                        </div>
                    </div>
                    <div class="bills-item-price">Rp ${itemSub.toLocaleString('id-ID')}</div>
                `;
                container.appendChild(div);
            });

            grandTotal = subtotal;
            document.getElementById('subtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('totalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }

        function changeQty(idx, change) {
            bills[idx].quantity += change;
            if (bills[idx].quantity <= 0) {
                bills.splice(idx, 1);
            }
            renderBills();
        }

        function openCheckoutModal() {
            if (bills.length === 0) {
                alert('Pilih menu terlebih dahulu!');
                return;
            }
            document.getElementById('modalTotalPay').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            document.getElementById('checkoutModal').classList.add('active');
        }

        async function submitCheckout() {
            const tableId = document.getElementById('tableSelector').value;
            const payMethod = document.getElementById('modalPayMethod').value;

            const payload = {
                type: tableId ? 'dine_in' : 'takeaway',
                table_id: tableId || null,
                customer_name: 'Pelanggan Walk-in',
                payment_method: payMethod,
                paid_amount: grandTotal,
                items: bills.map(b => ({
                    product_id: b.product.id,
                    quantity: b.quantity
                }))
            };

            try {
                const res = await fetch('{{ route("pos.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.success) {
                    document.getElementById('checkoutModal').classList.remove('active');
                    window.open('/pos/receipt/' + data.data.id, '_blank', 'width=400,height=600');
                    bills = [];
                    renderBills();
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan.');
            }
        }
    </script>
</body>
</html>
