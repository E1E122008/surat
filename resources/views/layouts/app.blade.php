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

        .navbar-logo {
        padding-right: 10px; /* Jarak di sebelah kanan logo */
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 5px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Tombol Tambah */
        .btn-primary {
            background: #5b7ef1 !important;
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: #1e5add !important;
        }

        /* Tombol Export */
        .btn-success, 
        .btn-success:hover,
        .btn-success:active,
        .btn-success:focus {
            background: #10b92c !important;
            border: none;
            color: rgb(255, 255, 255);
        }

        /* Icon dalam button */
        .btn i {
            margin-right: 0.5rem;
        }

        /* Spacing antara buttons */
        .btn + .btn {
            margin-left: 0.5rem;
        }

        .btn-outline-light {
            border: 1px solid rgba(255,255,255,0.5);
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Table Styles */
        .table thead th,
        .table th,
        .table-bordered thead th,
        .table-bordered th {
            background-color: #4F75E6 !important;
            color: #FFFFFF !important;
            border-bottom: 2px solid #4F75E6 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table td,
        .table-bordered td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
            color: #475569;
            
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
            width: 100%; /* Atur lebar menjadi 100% dari kolom */
            max-width: 300px; /* Atur lebar maksimum sesuai kebutuhan */
            margin: 0 auto; /* Pusatkan kartu */
            border-radius: 10px; /* Pastikan sudut kartu melengkung */
            overflow: hidden; /* Sembunyikan konten yang melampaui batas */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan untuk efek visual */
        }

        .dashboard-card h2 {
            font-size: 2rem; /* Ukuran font untuk nilai kartu */
        }

        .dashboard-card p {
            font-size: 1rem; /* Ukuran font untuk judul kartu */
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

        .form-select {
        background-color: white !important;
        border: 1px solid #e5e7eb !important;  /* Warna border abu-abu sangat terang */
        border-radius: 6px !important;
        padding: 8px 12px !important;
        width: 100% !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }

        .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
        }

        .form-select:hover {
            border-color: #d1d5db !important;  /* Warna hover abu-abu medium */
        }

        /* Tambahan untuk memastikan border benar-benar abu-abu */
        select.form-select {
            border-color: #e5e7eb !important;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(165, 165, 165, 0.712);
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
            background-color: #6B7280 !important;
            color: white !important;
            padding: 0.625rem 1.25rem !important;
            border-radius: 0.5rem !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            border: none !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            cursor: pointer !important;
        }

        .btn-cancel:hover {
            background-color: #4B5563 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2) !important;
        }

        .btn-cancel:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-submit {
            background-color: #4299e1;
            color: white;
        }

        .btn-submit:hover {
            background-color: #3182ce;
        }

        .sidebar {
            width: 290px;
            background: rgba(30, 59, 138, 0.678); /* Navy Blue dengan transparansi */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px;
            position: fixed;
            top: 0;
            left: -290px;
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
            white-space: nowrap; /* Mencegah teks membungkus ke baris berikutnya */
            overflow: hidden; /* Sembunyikan teks yang melampaui batas */
            text-overflow: ellipsis; /* Tambahkan ellipsis (...) untuk teks yang terpotong */
            max-width: 150px; /* Atur lebar maksimum sesuai kebutuhan */
            display: inline-block; /* Pastikan elemen bersifat inline-block */
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
            white-space: nowrap; /* Mencegah teks membungkus ke baris berikutnya */
            overflow: hidden; /* Sembunyikan teks yang melampaui batas */
            text-overflow: ellipsis; /* Tambahkan ellipsis (...) untuk teks yang terpotong */
            max-width: 150px; /* Atur lebar maksimum sesuai kebutuhan */
            display: inline-block; /* Pastikan elemen bersifat inline-block */
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
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Dashboard Card Colors */
        .dashboard-card.surat-masuk {
            background: linear-gradient(135deg,#4C1D95, #D8B4FE);
            color: white;
        }

        .dashboard-card.surat-keluar {
            background: linear-gradient(135deg, rgba(0, 255, 0, 0.2), green);
            color: white;
        }

        .dashboard-card.draft-phd {
            background: linear-gradient(135deg, #713F12, #FEF08A);
            color: white;
        }

        .dashboard-card.sppd {
            background: linear-gradient(135deg, rgba(0, 0, 255, 0.2), blue);
            color: white;
        }

        .dashboard-card.spt {
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.2), orange);
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

        /* Form Controls */
        select, input, .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            border: 1px solid #D1D5DB;
            transition: all 0.3s ease-in-out;
        }

        /* Dropdown Styles */
        select {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        /* Style untuk dropdown Disposisi */
        select[name="disposisi"] {
            background-color: #ffffff !important;  /* Light blue */
            color: #1e3a8a !important;
            border: 1px solid #D1D5DB !important; /* Added grey border */
            border-radius: 8px;
            padding: 8px 16px;
        }

        /* Style untuk dropdown Subpoint */
        select[name="subpoint"] {
            background-color: #f1c75b !important;  /* Light yellow */
            color: #8a5e1e !important; 
            border: none !important;
        }

        /* Style untuk dropdown Status */
        select[name="status"] {
            background-color: #ffffff !important;  /* Light green */
            color: #166534 !important;
            border: 1px solid #D1D5DB !important; /* Added grey border */
            border-radius: 8px;
            padding: 8px 16px; /* Maintain border-radius */
        }

        /* Hover effect untuk kedua dropdown */
        select:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Focus effect untuk kedua dropdown */
        select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* Status Badge */
        .status {
            background-color: #D1FAE5;
            color: #047857;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-block;
        }

        .status.pending {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .status.rejected {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Table Header Section */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Search and Filter Controls */
        .table-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Pagination Styling */
        .pagination {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.25rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid #D1D5DB;
            color: #374151;
            background: #F9FAFB;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: #F3F4F6;
            color: #2563EB;
        }

        .page-item.active .page-link {
            background: #2563EB;
            color: white;
            border-color: #2563EB;
        }

        /* Textarea styling */
        textarea.form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            min-height: 120px;
            resize: vertical;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        textarea.form-textarea:hover {
            border-color: #cbd5e1;
        }

        textarea.form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            ring: 2px;
            ring-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button container */
        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        /* Button styling */
        .btn-update {
            background-color: #3B82F6 !important;
            color: white !important;
            padding: 0.625rem 1.25rem !important;
            border-radius: 0.5rem !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            border: none !important;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3) !important;
            margin-left: 12px !important;
            cursor: pointer !important;
        }

        .btn-update:hover {
            background-color: #2563EB !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4) !important;
        }

        .btn-update:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3) !important;
        }

        /* Textarea catatan styling */
        .catatan-textarea {
            width: 200px !important; /* Memperlebar textarea */
            height: 60px !important;
            min-height: 60px !important;
            max-height: 60px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            padding: 10px !important;
            font-size: 14px !important;
            outline: none !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            resize: none !important;
        }

        .catatan-textarea::placeholder {
            color: #888 !important;
        }

        .catatan-textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15) !important;
        }

        .catatan-textarea:hover {
            border-color: #9ca3af !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12) !important;
        }

        /* Catatan container dan grid styling */
        .catatan-container {
            width: 100% !important;
            padding: 10px !important;
        }

        .catatan-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 15px !important;
        }

        .catatan-item {
            width: 100% !important;
        }

        .profile-avatar {
            position: relative !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            overflow: visible !important; /* Ubah dari hidden ke visible */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        .profile-avatar::after {
            content: "" !important;
            position: absolute !important;
            width: 8px !important;
            height: 8px !important;
            background: #22c55e !important;
            border: 1.5px solid #1e1b4b !important;
            border-radius: 50% !important;
            bottom: 1px !important;
            right: 1px !important;
            z-index: 10 !important; /* Memastikan dot muncul di atas */
        }

        

        .header h2 {
            border-bottom: 2px solid #ccc; /* Garis bawah */
            padding-bottom: 5px; /* Jarak antara teks dan garis */
        }

        .header h3 {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Bayangan */
        }

        .table-container table td {
            text-align: center; /* Rata tengah untuk semua sel */
        }

        #customSearch {
            margin-bottom: 20px; /* Atur jarak sesuai kebutuhan */
        }

        .table-container th,
        .table-container td {
            max-width: 200px; /* Adjust the width as needed */
            overflow: hidden; /* Hide overflow content */
            text-overflow: ellipsis; /* Add ellipsis for overflow text */
            white-space: normal; /* Allow text to wrap */
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Load SweetAlert2 setelah Vite -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="margin: 0; padding: 1rem 0; width: 100%;">
            <div class="container-fluid">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image mx-auto mb-2">
                <a class="navbar-brand fw-bold"   href="{{ route('dashboard') }}">SIAP BROH !!!</a>
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
                        <span class="date-display">{{ now()->format('l, d F Y') }}</span>
                        <i class="fas fa-calendar-alt ms-2"></i>
                    </span>
                </div>
                <div class="navbar-nav me-4">
                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationsDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                            <h6 class="dropdown-header">Notifikasi</h6>
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <a class="dropdown-item {{ $notification->read_at ? '' : 'bg-light' }}" href="#">
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark">{{ $notification->data['message'] }}</strong>
                                        @if(isset($notification->data['notes']))
                                            <small class="text-muted">{{ $notification->data['notes'] }}</small>
                                        @endif
                                        <small class="text-muted mt-1">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @empty
                                <div class="dropdown-item text-muted">Tidak ada notifikasi</div>
                            @endforelse
                            @if(auth()->user()->notifications->count() > 5)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-primary text-center" href="#">Lihat semua notifikasi</a>
                            @endif
                        </div>
                    </div>
                    <!-- Profile Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-white profile-name">{{ Auth::user()->name }}</span>
                            <div class="position-relative">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; overflow: hidden;">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                             alt="Profile"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span class="text-primary fw-bold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li class="dropdown-header d-flex align-items-center">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                         alt="User Avatar" 
                                         class="profile-image">
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
        <main class="container py-4" style="margin-top: 0;">
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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script untuk notifikasi -->
    <script>
        // Notifikasi sukses
        @if(session('success'))
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                },
                background: '#10B981',
                color: '#ffffff'
            });
        @endif

        // Notifikasi error
        @if(session('error'))
            Swal.fire({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error",
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                },
                background: '#EF4444',
                color: '#ffffff'
            });
        @endif

        // Konfirmasi delete dengan loading state
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                imageUrl: "https://cdn-icons-png.flaticon.com/512/564/564619.png",
                imageWidth: 80,
                imageHeight: 80,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__bounceIn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    });
                }
            });
        }
    </script>
</body>
</html> 