<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        /* Reset link styles */
        a {
            text-decoration: none !important;
        }

        /* Navbar Styles */
        .navbar {
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,.85);
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }

        .navbar-dark .navbar-nav .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
        }

        /* Button Styles */
        .btn {
            border-radius: 5px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
        }

        .btn-outline-light {
            border: 1px solid rgba(255,255,255,0.5);
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Table Styles */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        
        .table th {
            padding: 1rem;
            vertical-align: middle;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .table tr:hover {
            background-color: rgba(0,0,0,.01);
        }

        /* Action Buttons in Tables */
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin-right: 0.25rem;
            border-radius: 4px;
        }

        /* Dashboard Specific Styles */
        .dashboard-card {
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .dashboard-card .card-body {
            padding: 1.5rem;
        }

        .dashboard-card .card-title {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .dashboard-card .card-text {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        .dashboard-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .dashboard-stats h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-stats p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        .dashboard-welcome {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .dashboard-welcome h2 {
            color: #1a202c;
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .dashboard-menu-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .dashboard-menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-menu-card .icon {
            width: 50px;
            height: 50px;
            background: #ebf4ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .dashboard-menu-card .icon svg {
            width: 24px;
            height: 24px;
            color: #4299e1;
        }

        .dashboard-menu-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .dashboard-menu-card p {
            color: #718096;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .dashboard-menu-card .btn {
            width: 100%;
            padding: 0.75rem;
            font-weight: 500;
        }

        /* Responsive adjustments for dashboard */
        @media (max-width: 768px) {
            .dashboard-stats {
                margin-bottom: 1rem;
            }
            
            .dashboard-menu-card {
                margin-bottom: 1rem;
            }
        }

        /* Container padding */
        .container {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        /* Card styles */
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            border-radius: 10px;
        }

        .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Utility classes */
        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        }

        /* Form Styles */
        .form-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-header h2 {
            color: #1a202c;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            outline: none;
        }

        .form-error {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-help {
            color: #718096;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .form-grid-full {
            grid-column: 1 / -1;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-cancel {
            background-color: #718096;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #4a5568;
        }

        .btn-submit {
            background-color: #4299e1;
            color: white;
        }

        .btn-submit:hover {
            background-color: #3182ce;
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BPKD BANJAR</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}" 
                               href="{{ route('surat-masuk.index') }}">Surat Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}" 
                               href="{{ route('surat-keluar.index') }}">Surat Keluar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sppd-dalam-daerah.*') ? 'active' : '' }}" 
                               href="{{ route('sppd-dalam-daerah.index') }}">SPPD Dalam</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sppd-luar-daerah.*') ? 'active' : '' }}" 
                               href="{{ route('sppd-luar-daerah.index') }}">SPPD Luar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('spt-dalam-daerah.*') ? 'active' : '' }}" 
                               href="{{ route('spt-dalam-daerah.index') }}">SPT Dalam</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('spt-luar-daerah.*') ? 'active' : '' }}" 
                               href="{{ route('spt-luar-daerah.index') }}">SPT Luar</a>
                        </li>
                    </ul>
                    <div class="navbar-nav">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="container py-4">
            {{ $slot }}
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 