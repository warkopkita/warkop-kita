<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warkop Kita - Nongkrong Santai, Kopi Nikmat, Wi-Fi Kencang</title>
    <meta name="description" content="Warkop Kita adalah tempat nongkrong kekinian dengan kopi nusantara otentik, Indomie racikan legendaris, Wi-Fi 100Mbps dan suasana hangat bersahabat.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-body: #0E0C0A;
            --bg-surface: #181411;
            --bg-card: #221C17;
            --border: #3A3027;
            --primary: #D4A373;
            --primary-dark: #A67342;
            --primary-light: #FAEDCD;
            --text-main: #F4EAE0;
            --text-muted: #A89F91;
            --green: #10B981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Navbar */
        .landing-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 76px;
            background: rgba(14, 12, 10, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            z-index: 100;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 44px;
            height: 44px;
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

        .nav-logo-text h2 {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary-light);
        }

        .nav-logo-text p {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-cta {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #1A120B;
            box-shadow: 0 4px 15px rgba(212, 163, 115, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 163, 115, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: var(--bg-surface);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Hero Section */
        .hero {
            padding: 160px 5% 100px;
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            background-image: radial-gradient(circle at 80% 30%, rgba(212, 163, 115, 0.15) 0%, transparent 60%);
        }

        .hero-content {
            max-width: 650px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(212, 163, 115, 0.12);
            border: 1px solid rgba(212, 163, 115, 0.3);
            border-radius: 30px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.15;
            color: var(--primary-light);
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--primary), #FFF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 16px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-stats {
            display: flex;
            gap: 36px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid var(--border);
        }

        .stat h3 {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
        }

        .stat p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
            margin-bottom: 0;
        }

        /* Facilities */
        .section {
            padding: 80px 5%;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 50px;
        }

        .section-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-light);
            letter-spacing: -0.5px;
        }

        .section-header p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 8px;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .facility-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 28px 22px;
            text-align: center;
            transition: all 0.2s;
        }

        .facility-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
        }

        .facility-icon {
            width: 56px;
            height: 56px;
            background: rgba(212, 163, 115, 0.1);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 16px;
        }

        .facility-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .facility-card p {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Menu Highlights */
        .menu-grid-catalog {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .menu-item-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.2s;
        }

        .menu-item-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .menu-img {
            height: 170px;
            background: #1F1914;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 44px;
            color: var(--primary);
            position: relative;
        }

        .menu-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(212, 163, 115, 0.9);
            color: #1A120B;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .menu-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .menu-cat {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
        }

        .menu-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            margin: 4px 0 8px;
        }

        .menu-text {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 16px;
            flex: 1;
        }

        .menu-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .menu-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary-light);
        }

        /* App Download Banner */
        .app-banner {
            background: linear-gradient(135deg, #2A2017, #17120D);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 50px;
            margin: 40px 5%;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .app-banner h2 {
            font-size: 34px;
            font-weight: 800;
            color: var(--primary-light);
            margin-bottom: 14px;
        }

        .app-banner p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        /* Footer */
        footer {
            background: var(--bg-surface);
            border-top: 1px solid var(--border);
            padding: 60px 5% 30px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 14px;
        }

        .footer-col p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer-bottom {
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-muted);
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 36px; }
            .nav-links { display: none; }
            .app-banner { grid-template-columns: 1fr; padding: 30px; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="landing-nav">
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon"><i class="fa-solid fa-mug-hot"></i></div>
            <div class="nav-logo-text">
                <h2>{{ $storeInfo['name'] }}</h2>
                <p>NONGKRONG & KOPI</p>
            </div>
        </a>

        <div class="nav-links">
            <a href="#menu" class="nav-link">Menu Kami</a>
            <a href="#fasilitas" class="nav-link">Fasilitas</a>
            <a href="#lokasi" class="nav-link">Lokasi & Jam</a>
            <a href="{{ route('self-order') }}" class="nav-link" style="color: var(--primary);">Self Order QR</a>
        </div>

        <div class="nav-cta">
            <a href="{{ route('self-order') }}" class="btn btn-primary">
                <i class="fa-solid fa-qrcode"></i> Pesan Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline">
                <i class="fa-solid fa-user-lock"></i> Staff Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-fire"></i> Tempat Nongkrong Paling Hits & Nyaman
            </div>
            <h1>Racikan Kopi Otentik, <span>Suasana Hangat.</span></h1>
            <p>Nikmati seduhan kopi Nusantara pilihan, racikan Indomie internet gurih nikmat, koneksi Wi-Fi 100Mbps super ngebut, serta area nongkrong nyaman 24 jam.</p>
            
            <div class="hero-buttons">
                <a href="{{ route('self-order') }}" class="btn btn-primary" style="padding: 14px 28px; font-size: 15px;">
                    <i class="fa-solid fa-mobile-screen-button"></i> Self Order dari Meja
                </a>
                <a href="#menu" class="btn btn-outline" style="padding: 14px 28px; font-size: 15px;">
                    <i class="fa-solid fa-utensils"></i> Lihat Daftar Menu
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat">
                    <h3>20+</h3>
                    <p>Pilihan Menu Otentik</p>
                </div>
                <div class="stat">
                    <h3>100 Mbps</h3>
                    <p>Dedicated Wi-Fi</p>
                </div>
                <div class="stat">
                    <h3>4.9 ★</h3>
                    <p>Rating Ribuan Pelanggan</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Facilities -->
    <section class="section" id="fasilitas" style="background: #110E0C;">
        <div class="section-header">
            <h2>Fasilitas Nongkrong Lengkap</h2>
            <p>Semua yang kamu butuhkan untuk nugas, mabar, nobar, atau sekadar santai bersama sahabat.</p>
        </div>

        <div class="facilities-grid">
            <div class="facility-card">
                <div class="facility-icon"><i class="fa-solid fa-wifi"></i></div>
                <h3>Wi-Fi 100 Mbps</h3>
                <p>Internet super cepat dan stabil untuk streaming, mabar ML/PUBG, hingga meeting online.</p>
            </div>
            <div class="facility-card">
                <div class="facility-icon"><i class="fa-solid fa-plug"></i></div>
                <h3>Stopkontak di Tiap Meja</h3>
                <p>Nggak perlu takut baterai laptop atau HP habis, colokan tersedia di setiap sudut meja.</p>
            </div>
            <div class="facility-card">
                <div class="facility-icon"><i class="fa-solid fa-snowflake"></i></div>
                <h3>Area AC & Outdoor Smoking</h3>
                <p>Pilihan ruangan ber-AC bebas asap rokok atau area outdoor santai semi-terbuka.</p>
            </div>
            <div class="facility-card">
                <div class="facility-icon"><i class="fa-solid fa-tv"></i></div>
                <h3>Layar Nobar Besar</h3>
                <p>Nonton bareng pertandingan bola Liga Inggris, Champions, dan Timnas setiap minggu.</p>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="section" id="menu">
        <div class="section-header">
            <h2>Menu Andalan Warkop Kita</h2>
            <p>Dari kopi seduh tubruk hingga camilan hangat renyah yang bikin betah berjam-jam.</p>
        </div>

        <div class="menu-grid-catalog">
            @foreach($favoriteProducts as $prod)
                <div class="menu-item-card">
                    <div class="menu-img">
                        @if($prod->image)
                            <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}">
                        @else
                            <i class="fa-solid fa-mug-hot"></i>
                        @endif
                        <span class="menu-badge"><i class="fa-solid fa-star"></i> Best Seller</span>
                    </div>
                    <div class="menu-body">
                        <span class="menu-cat">{{ $prod->category->name ?? 'Menu' }}</span>
                        <h3 class="menu-title">{{ $prod->name }}</h3>
                        <p class="menu-text">{{ $prod->description }}</p>
                        <div class="menu-bottom">
                            <span class="menu-price">Rp {{ number_format($prod->price, 0, ',', '.') }}</span>
                            <a href="{{ route('self-order') }}" class="btn btn-primary" style="padding: 8px 14px; font-size: 12px;">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- App Download Banner -->
    <div class="app-banner">
        <div>
            <h2>Pesan & Kumpulkan Poin di Aplikasi Android</h2>
            <p>Download aplikasi Android **Warkop Kita** untuk scan QR di meja, pesan tanpa antre, dan tukarkan loyalty poin dengan kopi gratis setiap minggu!</p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('self-order') }}" class="btn btn-primary">
                    <i class="fa-solid fa-qrcode"></i> Buka Web Self-Order
                </a>
                <a href="#" class="btn btn-outline" onclick="alert('File APK Android Warkop Kita sedang disiapkan.')">
                    <i class="fa-brands fa-android"></i> Download APK Android
                </a>
            </div>
        </div>
        <div style="text-align: center;">
            <div style="width: 180px; height: 180px; background: #fff; border-radius: 16px; padding: 12px; margin: 0 auto 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('/self-order')) }}" alt="QR App" style="width: 100%; height: 100%;">
            </div>
            <span style="font-size: 12px; color: var(--primary);">Scan QR untuk Buka Menu Digital</span>
        </div>
    </div>

    <!-- Store Info & Location -->
    <section class="section" id="lokasi" style="background: #110E0C;">
        <div class="section-header">
            <h2>Lokasi & Jam Operasional</h2>
            <p>Kunjungi warkop kami atau hubungi via WhatsApp untuk reservasi meja rombongan.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 900px; margin: 0 auto;">
            <div style="background: var(--bg-surface); padding: 24px; border-radius: 18px; border: 1px solid var(--border);">
                <h3 style="font-size: 16px; color: var(--primary); margin-bottom: 12px;"><i class="fa-solid fa-location-dot"></i> Alamat Kami</h3>
                <p style="font-size: 14px; color: var(--text-main); line-height: 1.5;">{{ $storeInfo['address'] }}</p>
                
                <h3 style="font-size: 16px; color: var(--primary); margin-top: 20px; margin-bottom: 8px;"><i class="fa-solid fa-clock"></i> Jam Operasional</h3>
                <p style="font-size: 14px; color: var(--text-main);">{{ $storeInfo['open_hours'] }}</p>
            </div>

            <div style="background: var(--bg-surface); padding: 24px; border-radius: 18px; border: 1px solid var(--border);">
                <h3 style="font-size: 16px; color: var(--primary); margin-bottom: 12px;"><i class="fa-solid fa-wifi"></i> Akses Wi-Fi Pelanggan</h3>
                <p style="font-size: 14px; color: var(--text-main);"><strong>SSID:</strong> {{ $storeInfo['wifi_ssid'] }}</p>
                <p style="font-size: 14px; color: var(--text-main); margin-top: 4px;"><strong>Password:</strong> {{ $storeInfo['wifi_pass'] }}</p>

                <h3 style="font-size: 16px; color: var(--primary); margin-top: 20px; margin-bottom: 8px;"><i class="fa-brands fa-whatsapp"></i> Kontak & Reservasi</h3>
                <p style="font-size: 14px; color: var(--text-main);">WhatsApp: {{ $storeInfo['phone'] }}</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <div class="nav-logo" style="margin-bottom: 16px;">
                    <div class="nav-logo-icon"><i class="fa-solid fa-mug-hot"></i></div>
                    <div class="nav-logo-text">
                        <h2>{{ $storeInfo['name'] }}</h2>
                        <p>Ecosystem Hub</p>
                    </div>
                </div>
                <p>{{ $storeInfo['tagline'] }}</p>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <p><a href="#menu" style="color: var(--text-muted); text-decoration: none;">Menu</a></p>
                <p><a href="#fasilitas" style="color: var(--text-muted); text-decoration: none;">Fasilitas</a></p>
                <p><a href="{{ route('self-order') }}" style="color: var(--text-muted); text-decoration: none;">Self-Order</a></p>
            </div>
            <div class="footer-col">
                <h4>Sistem Warkop</h4>
                <p><a href="{{ route('pos.index') }}" style="color: var(--text-muted); text-decoration: none;">POS Kasir</a></p>
                <p><a href="{{ route('kds.index') }}" style="color: var(--text-muted); text-decoration: none;">KDS Dapur</a></p>
                <p><a href="{{ route('login') }}" style="color: var(--text-muted); text-decoration: none;">Admin Panel</a></p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $storeInfo['name'] }}. All Rights Reserved.</span>
            <span>Dibangun dengan Laravel 11, Docker, REST API Sanctum, & Flutter.</span>
        </div>
    </footer>
</body>
</html>
