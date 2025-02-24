<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden; /* Mencegah scrolling */
        }

        .login-page {
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('/images/KAntor-Gubernur.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed; /* Membuat background tetap */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px; /* Tambahkan padding untuk mobile */
        }

        .login-card {
            background: rgba(173, 216, 230, 0.5); /* Increased transparency for more blur effect */
            backdrop-filter: blur(20px); /* Further increased blur effect */
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); /* Slightly darker shadow for better contrast */
            width: 450px;
            max-width: 100%;
            margin: auto;
            animation: fadeIn 0.5s ease-in-out;
        }

        .login-title {
            color: #333;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #80bdff;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            border-radius: 10px;
            background: linear-gradient(45deg, #007bff, #00bfff);
            border: none;
            color: rgb(255, 255, 255);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #0056b3, #0098cc);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #ffffff;
        }

        .alert {
            border-radius: 10px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }

        .logo-text {
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            cursor: pointer;
            color: #6c757d;
        }

        .password-toggle:hover {
            color: #495057;
        }

        .form-control.password-input {
            padding-right: 2.5rem;
        }

        @media (max-height: 800px) {
            .login-card {
                padding: 2rem;
                margin: 1rem auto;
            }

            .logo-container img {
                width: 100px;
            }

            .logo-text {
                font-size: 1.1rem;
            }

            .login-title {
                margin-bottom: 1.5rem;
            }

            .input-group {
                margin-bottom: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-page d-flex align-items-center justify-content-center">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <h4 class="login-title mb-2 semibold">Selamat Datang</h4>
                <p class="login-subtitle">Silahkan Masuk Ke Akun Anda</p>
                
            </div>

            

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Email"
                           required 
                           autocomplete="email" 
                           autofocus>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control password-input @error('password') is-invalid @enderror" 
                           name="password" 
                           id="password"
                           placeholder="Password"
                           required 
                           autocomplete="current-password">
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>

            <div class="login-footer">
                <p class="mb-0">&copy; 2025 Pemerintah Provinsi Sulawesi Tenggara</p>
            </div>
        </div>
    </div>

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