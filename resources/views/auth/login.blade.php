<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAP BROH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            overflow-x: hidden;
        }

        /* --- Layout Induk: Flexbox Dinamis --- */
        .split-layout {
            position: relative;
            min-height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            align-items: center;
            /* Rata tengah vertikal */
            justify-content: center;
            /* Rata tengah horizontal */
            gap: 8%;
            /* Jarak rongga ideal persis di tengah layar */
            padding: 2rem 5%;
        }

        /* --- Background Utama Layar Penuh --- */
        .background-layer {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* Dark Overlay tegas dan merata */
            background: linear-gradient(135deg, rgba(8, 15, 30, 0.88), rgba(0, 0, 0, 0.92)),
                url('/images/KAntor-Gubernur.jpg') center/cover no-repeat;
            z-index: 1;
        }

        /* --- Sisi Kiri (Floating Card Area) --- */
        .form-side {
            position: relative;
            /* Menetap sesuai grid */
            z-index: 20;
            width: 100%;
            max-width: 440px;
            /* Ukuran presisi card login */
        }

        /* Ambient Light di belakang Glass Card */
        .blob-1 {
            position: absolute;
            top: -15%;
            left: -20%;
            width: 400px;
            height: 400px;
            background: rgba(56, 189, 248, 0.2);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            animation: floatBlob 10s ease-in-out infinite alternate;
            pointer-events: none;
        }

        .blob-2 {
            position: absolute;
            bottom: -15%;
            right: -20%;
            width: 350px;
            height: 350px;
            background: rgba(79, 70, 229, 0.2);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            animation: floatBlob 8s ease-in-out infinite alternate-reverse;
            pointer-events: none;
        }

        .form-container {
            width: 100%;
            position: relative;
            z-index: 10;
            /* Efek Kaca lebih pekat */
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(35px);
            -webkit-backdrop-filter: blur(35px);
            padding: 2.5rem 2rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
        }

        /* --- Pembungkus Kanan (Teks SIAP BROH Area) --- */
        .info-side {
            position: relative;
            z-index: 20;
            width: 100%;
            max-width: 550px;
            /* Alokasi ideal untuk porsi teks */
        }

        .info-content {
            width: 100%;
            text-align: center;
            opacity: 0;
            animation: fadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) 0.8s forwards;
        }

        /* --- Animasi --- */
        .animate-up {
            opacity: 0;
            transform: translateY(25px);
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .delay-4 {
            animation-delay: 0.4s;
        }

        .delay-5 {
            animation-delay: 0.5s;
        }

        .delay-6 {
            animation-delay: 0.6s;
        }

        .delay-7 {
            animation-delay: 0.7s;
        }

        /* Fix info delay */
        .delay-info {
            animation-delay: 0.1s;
            transform: translateX(40px);
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatBlob {
            0% {
                transform: scale(1) translate(0, 0);
            }

            100% {
                transform: scale(1.1) translate(30px, -20px);
            }
        }

        /* --- Teks, Header & Input --- */
        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.4rem;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-control {
            border-radius: 8px;
            padding: 1.1rem 1rem 1.1rem 3.5rem;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            /* Transparansi pekat agar elegan di background gedung */
            background-color: rgba(15, 23, 42, 0.7);
            color: #f8fafc;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.95);
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
            border-color: #38bdf8;
            outline: none;
            transform: translateY(-2px);
            color: #ffffff;
        }

        /* AutoFill Override Fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 50px #1e293b inset !important;
            -webkit-text-fill-color: #f8fafc !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1.2rem;
            width: 100%;
        }

        .input-icon {
            position: absolute;
            left: 1.4rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 10;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .password-toggle {
            position: absolute;
            right: 1.4rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            cursor: pointer;
            color: #64748b;
            transition: color 0.2s;
        }

        .password-toggle:hover,
        .form-control:focus+.input-icon,
        .form-control:focus~.input-icon {
            color: #38bdf8;
        }

        .btn-login {
            width: 100%;
            padding: 1.1rem;
            border-radius: 8px;
            background: linear-gradient(135deg, #2563eb 0%, #38bdf8 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -60%;
            width: 25%;
            height: 200%;
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(35deg);
            transition: 0.6s ease-in-out;
        }

        .btn-login:hover::after {
            left: 120%;
        }

        .btn-login:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(56, 189, 248, 0.4);
            color: #ffffff;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: #64748b;
            font-size: 0.85rem;
        }

        /* --- Elemen Teks Info SIAP BROH --- */
        .brand-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .app-logo {
            width: 125px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.5));
            animation: floating 6s ease-in-out infinite;
        }

        .app-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -2px;
            color: #ffffff;
            text-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        .app-subtitle {
            font-size: 1.25rem;
            font-weight: 600;
            color: #38bdf8;
            margin-top: 0.5rem;
            letter-spacing: -0.5px;
        }

        .app-desc {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #cbd5e1;
            font-weight: 400;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .features-text {
            font-size: 0.85rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
            font-weight: 500;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            display: inline-block;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* --- Mode Mobile Yang Dirapihkan Total --- */
        @media (max-width: 992px) {
            .split-layout {
                flex-direction: column;
                /* SANGAT KRUSIAL: Memastikan Logo SIAP BROH nongol di Atas Form pd HP */
                padding: 4rem 1.5rem;
                gap: 3rem;
                /* Jarak pas antara form dengan foto logo di HP */
            }

            .info-side,
            .form-side {
                max-width: 100%;
                /* Lebar penuh di layar sentuh */
                width: 100%;
            }

            .app-desc,
            .features-text,
            .app-subtitle {
                display: none;
            }

            .app-logo {
                width: 100px;
                margin-bottom: 0.5rem;
            }

            .app-title {
                font-size: 2.8rem;
            }

            .form-container {
                padding: 2.5rem 1.5rem;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
            }

            .blob-1,
            .blob-2 {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="split-layout">

        <!-- Wallpaper Background Layer Tertutup Layar Penuh -->
        <div class="background-layer"></div>

        <!-- Sisi Kiri / Teks Info Aplikasi -->
        <div class="info-side">
            <div class="info-content delay-info">
                <div class="brand-header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Sultra" class="app-logo">
                    <h1 class="app-title">SIAP BROH!!!</h1>
                    <h3 class="app-subtitle">Sistem Informasi Administrasi Persuratan Biro Hukum</h3>
                </div>

                <p class="app-desc">
                    Platform terintegrasi untuk mendukung pengelolaan administrasi persuratan Biro Hukum secara tertib,
                    cepat, dan efisien.
                </p>

                <div class="info-footer">
                    <p class="features-text">Administrasi Terintegrasi &bull; Dokumen Terpusat &bull; Akses Terlindungi
                    </p>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan / Floating Glass Card Form Login -->
        <div class="form-side">
            <div class="blob-1"></div>
            <div class="blob-2"></div>

            <div class="form-container">

                <div class="form-header animate-up delay-2">
                    <h2>Selamat Datang</h2>
                    <p>Silakan masuk menggunakan akun yang telah terdaftar</p>
                </div>

                @if (session('error'))
                    <div class="alert animate-up delay-3 pb-2 pt-2 pr-4 pl-3 mb-4"
                        style="border-radius:8px; font-size:14px; background:rgba(239, 68, 68, 0.1); border:1px solid #ef4444; color:#fca5a5;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert animate-up delay-3 pb-2 pt-2 pr-4 pl-3 mb-4"
                        style="border-radius:8px; font-size:14px; background:rgba(16, 185, 129, 0.1); border:1px solid #10b981; color:#34d399;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-wrapper animate-up delay-3">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" placeholder="Alamat Email / ID Pengguna" required
                            autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback ps-2 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-wrapper animate-up delay-4">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password"
                            class="form-control password-input @error('password') is-invalid @enderror" name="password"
                            id="password" placeholder="Kata Sandi Kredensial" required autocomplete="current-password">
                        <i class="fas fa-eye password-toggle" id="togglePassword" title="Tampilkan/Sembunyikan"></i>
                        @error('password')
                            <div class="invalid-feedback ps-2 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login animate-up delay-5">
                        Masuk
                    </button>

                </form>

                <div class="mt-4 pt-4 text-center animate-up delay-6"
                    style="border-top: 1px solid rgba(255,255,255,0.08);">
                    <p
                        style="color: #cbd5e1; font-size: 0.85rem; margin-bottom: 0; line-height: 1.6; font-weight: 400;">
                        Akun dikelola internal oleh Administrator Biro Hukum.<br>
                        <a href="{{ route('bantuan.akses') }}"
                            style="color: #38bdf8; text-decoration: none; font-weight: 600; transition: opacity 0.2s;"
                            onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">Butuh Bantuan?
                        </a>
                    </p>
                </div>

                <div class="footer-text animate-up delay-7">
                    &copy; 2026 Biro Hukum Sekretariat Daerah Provinsi Sulawesi Tenggara
                </div>

            </div>
        </div>

    </div>

    <!-- Script toggle hapus jika tidak butuh dsb -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const toggleIcon = this;

            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
