<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warkop Kita Ecosystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-body: #0E0C0A;
            --bg-card: #1A1613;
            --border-color: #38302A;
            --primary: #D4A373;
            --primary-dark: #A67342;
            --primary-light: #FAEDCD;
            --text-main: #F4EAE0;
            --text-muted: #A89F91;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            background-image: radial-gradient(circle at 50% 10%, rgba(212, 163, 115, 0.12) 0%, transparent 60%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 58px;
            height: 58px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1A120B;
            font-size: 26px;
            margin-bottom: 16px;
            box-shadow: 0 8px 25px rgba(212, 163, 115, 0.35);
        }

        .brand-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-light);
            letter-spacing: -0.5px;
        }

        .brand-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: #12100E;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-main);
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.2);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: #1A120B;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
            box-shadow: 0 6px 20px rgba(212, 163, 115, 0.25);
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0 20px;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            padding: 0 12px;
        }

        .demo-roles {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .btn-demo {
            padding: 10px 8px;
            background: #12100E;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-demo:hover {
            background: rgba(212, 163, 115, 0.15);
            color: var(--primary);
            border-color: var(--primary);
        }

        .error-box {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #F87171;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-icon">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <h1>WARKOP KITA</h1>
            <p>Ecosystem Hub & Management Portal</p>
        </div>

        @if($errors->any())
            <div class="error-box">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Email atau No. Telepon</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="text" name="email" id="loginInput" class="form-control" placeholder="dadang@warkop.com" value="{{ old('email', 'dadang@warkop.com') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" value="password123" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
            </button>
        </form>

        <div class="divider">
            <span>Uji Coba 1-Klik Role Akun</span>
        </div>

        <div class="demo-roles">
            <button type="button" class="btn-demo" onclick="fillLogin('dadang@warkop.com', 'password123')">
                <i class="fa-solid fa-crown text-amber"></i> Owner
            </button>
            <button type="button" class="btn-demo" onclick="fillLogin('siti@warkop.com', 'password123')">
                <i class="fa-solid fa-cash-register"></i> Kasir POS
            </button>
            <button type="button" class="btn-demo" onclick="fillLogin('agus@warkop.com', 'password123')">
                <i class="fa-solid fa-fire-burner"></i> Barista (KDS)
            </button>
            <button type="button" class="btn-demo" onclick="fillLogin('budi@warkop.com', 'password123')">
                <i class="fa-solid fa-user-tie"></i> Manager
            </button>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('loginInput').value = email;
            document.getElementById('passwordInput').value = password;
            document.getElementById('loginForm').submit();
        }
    </script>
</body>
</html>
