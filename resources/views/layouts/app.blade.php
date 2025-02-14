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

        .navbar-nav .nav-item {
        display: flex;
        align-items: center;
        }

        .navbar-nav .nav-item span {
            margin-right: 10px; /* Jarak antara tanggal dan profil */
        }

        /* Button Styles */
        .btn {
            border-radius: 5px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #3232f7;  
            border: none;
        }

        .btn-primary:hover {
            background: #3232f7;  /* Ungu yang lebih gelap untuk hover */
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
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        /* Card styles */
        .card {
            border: none;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                       0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 
                       0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                       0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
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

        .sidebar {
            width: 250px;
            background: rgba(30, 58, 138, 0.85); /* Navy Blue dengan transparansi */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px;
            position: fixed;
            top: 0;
            left: -250px;
            height: 100%;
            transition: left 0.3s ease;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 5px;
            background: rgba(255, 255, 255, 0.05);
            position: relative;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #FFD700;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding-left: 20px;  /* Memberikan ruang untuk border */
            border-left: 4px solid #FFD700;  /* Garis kuning di sisi kiri */
        }

        /* Styling untuk ikon di menu active */
        .sidebar a.active i,
        .sidebar a.active svg {
            color: #FFD700;
            transform: scale(1.1);  /* Membuat ikon sedikit lebih besar */
            transition: transform 0.3s ease;
        }

        /* Menambahkan efek glow pada menu active */
        .sidebar a.active::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);  /* Efek glow kuning */
            pointer-events: none;
        }

        /* Menambahkan indikator kecil di sebelah kanan menu active */
        .sidebar a.active::before {
            content: '•';
            position: absolute;
            right: 15px;
            color: #FFD700;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

        /* Styling untuk ikon di sidebar */
        .sidebar i, .sidebar svg {
            color: #ffffff;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Styling untuk header sidebar */
        .sidebar .navbar-brand {
            color: #ffffff !important;
            font-size: 1.5rem;
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            display: block;
        }

        /* Hover effect untuk menu items */
        .sidebar a:hover i,
        .sidebar a:hover svg {
            transform: translateX(3px);
            transition: transform 0.3s ease;
        }

        .date-display {
            white-space: nowrap; /* Mencegah text wrap ke bawah */
            display: inline-block; /* Membuat elemen tetap dalam satu baris */
            margin-right: 15px; /* Jarak dengan profil */
        }

        /* Styling untuk tanggal */
        .date-display {
            white-space: nowrap;
            display: inline-block;
            margin-right: 15px;
        }

        /* Styling untuk profil */
        .profile-container {
            position: relative;
            transition: all 0.3s ease;
            padding: 3px; /* Menambah padding untuk ruang indikator */
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            background: linear-gradient(145deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.3) 100%);
        }

        .profile-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(145deg, #4a90e2 0%, #357abd 100%);
            color: white;
            font-weight: bold;
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .profile-name {
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Hover Effects */
        .profile-link:hover .profile-image,
        .profile-link:hover .profile-initial {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-color: white;
        }

        .profile-link:hover .profile-name {
            color: #e6e6e6 !important;
        }

        /* Dropdown styling */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-item {
            transition: all 0.2s ease-in-out;
        }

        .dropdown-item:hover {
            background-color: #f0f2f5;
            transform: translateX(5px);
        }

        /* Dropdown header styling */
        .dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        .dropdown-header .profile-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 0.2rem;
        }

        .dropdown-header .profile-role {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Logout button styling */
        .dropdown-item.logout-item {
            color: #dc2626;
            font-weight: 500;
        }

        .dropdown-item.logout-item:hover {
            background-color: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .dropdown-item.logout-item i {
            color: #dc2626;
            margin-right: 0.5rem;
        }

        /* Profile image/initial styling */
        .dropdown-header .profile-image {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-right: 1rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .dropdown-header .profile-initial {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        /* Hover effects */
        .dropdown-header:hover .profile-image,
        .dropdown-header:hover .profile-initial {
            transform: scale(1.05);
            border-color: #3b82f6;
        }

        /* Menu item icons */
        .dropdown-item i {
            width: 20px;
            margin-right: 0.5rem;
            color: #6b7280;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover i {
            color: #1e3a8a;
            transform: translateX(2px);
        }

        /* Status Indicator Styling yang diperbarui */
        .status-indicator {
            position: absolute;
            bottom: 0px;
            right: 0px;
            width: 12px;
            height: 12px;
            background-color: #2ecc71;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.3);
            animation: pulse 2s infinite;
            transform: translate(25%, 25%); /* Menggeser indikator ke luar lingkaran */
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(46, 204, 113, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }

        /* Menyesuaikan hover effect */
        .profile-link:hover .status-indicator {
            transform: translate(25%, 25%) scale(1.1);
        }

        /* Styling untuk dropdown icon */
        .dropdown-icon {
            font-size: 0.8em;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            margin-left: 5px !important;
        }

        /* Efek rotasi saat dropdown terbuka */
        .show .dropdown-icon {
            transform: rotate(180deg);
        }

        /* Hover effect untuk icon */
        .profile-link:hover .dropdown-icon {
            color: white;
            transform: translateY(2px);
        }

        .show .profile-link:hover .dropdown-icon {
            transform: rotate(180deg) translateY(-2px);
        }

        /* Navbar Styles */
        .navbar.navbar-dark.bg-primary {
            background-color: #191970 !important;
        }

        /* Update warna text-primary jika digunakan */
        .text-primary {
            color: #191970 !important;
        }

        body {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        }

        /* Table container update */
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                       0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 1rem;
            margin-bottom: 2rem;
        }

        /* Dashboard Card Colors */
        .dashboard-card.surat-masuk {
            background: linear-gradient(135deg, #6B46C1, #9F7AEA);
            color: white;
        }

        .dashboard-card.surat-keluar {
            background: linear-gradient(135deg, #10B981, #34D399);
            color: white;
        }

        .dashboard-card.sppd {
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            color: white;
        }

        .dashboard-card.spt {
            background: linear-gradient(135deg, #F97316, #FB923C);
            color: white;
        }

        /* Dashboard Card Styling */
        .dashboard-card {
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                       0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 
                       0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .dashboard-card .card-icon {
            position: absolute;
            bottom: -10px;
            right: -10px;
            font-size: 4rem;
            opacity: 0.15;
            transform: rotate(-15deg);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-icon {
            transform: rotate(0deg) scale(1.1);
            opacity: 0.2;
        }

        .dashboard-card .card-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .dashboard-card .card-title {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0;
            position: relative;
            z-index: 2;
            color: rgba(255, 255, 255, 0.9);
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">SIAP BRO!</a>
                <button class="btn btn-outline-light" onclick="toggleSidebar()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
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
                </div>

                <div class="navbar-nav">
                    <span class="nav-item text-white me-3 d-flex align-items-center">
                        <span class="date-display">{{ now()->format('d F Y') }}</span>
                    </span>
                    
                    <!-- Profil Pengguna dengan Custom Dropdown Icon -->
                    <div class="nav-item dropdown profile-container">
                        <a class="nav-link d-flex align-items-center profile-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-chevron-down ms-2 dropdown-icon"></i>
                            <span class="ms-2 d-none d-lg-inline text-white profile-name">{{ Auth::user()->name }}</span>
                        
                            <div class="position-relative">
                                @if(Auth::user()->profile_photo_url)
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="User Avatar" class="profile-image">
                                @else
                                    <div class="profile-initial">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="status-indicator"></span>
                            </div>
                            
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li class="dropdown-header d-flex align-items-center">
                                @if(Auth::user()->profile_photo_url)
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="User Avatar" class="profile-image">
                                @else
                                    <div class="profile-initial">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div class="ms-2">
                                    <strong>{{ Auth::user()->name }}</strong><br>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Page Content -->
        <main class="container py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        sidebar.classList.toggle('active'); // Menampilkan sidebar
        
        if (sidebar.classList.contains('active')) {
            mainContent.style.marginLeft = "16rem"; // Geser konten utama ke kanan
        } else {
            mainContent.style.marginLeft = "0"; // Kembalikan ke posisi awal
        }
    }
    </script>
</body>
</html> 