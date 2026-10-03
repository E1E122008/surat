<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Styles -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- ANTI FOUC SIDEBAR: Render Blocking execution for sidebar state -->

    <script>
        const userPrefix = 'sidebarCollapsed';
        if (localStorage.getItem(userPrefix) === 'true') {
            document.documentElement.classList.add('sidebar-mode-collapsed');
        }

        document.documentElement.classList.add('preload-no-transition');
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.documentElement.classList.remove('preload-no-transition');
            }, 100);
        });

        document.addEventListener('show.bs.modal', function(event) {
            setTimeout(() => {
                const zIndex = 1050 + (10 * document.querySelectorAll('.modal.show').length);
                event.target.style.zIndex = zIndex;
                const backdrop = document.querySelector('.modal-backdrop:not(.configured)');
                if (backdrop) {
                    backdrop.style.zIndex = zIndex - 1;
                    backdrop.classList.add('configured');
                }
            }, 10);
        });

        function forceCleanBackdrops() {
            if (!document.querySelector('.modal.show')) {
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
                document.body.style.removeProperty('overflow');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            }
        }
        window.addEventListener('pageshow', forceCleanBackdrops);

        document.addEventListener('DOMContentLoaded', forceCleanBackdrops);

        setTimeout(forceCleanBackdrops, 500);

        setTimeout(forceCleanBackdrops, 1500);
    </script>

    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->

    <style>
        /* Root Design System Tokens */

        :root {

            --navy-utama: #0f1b3d;

            --gold-aksen: #eab308;

            --gold-tint: rgba(234, 179, 8, 0.12);

            --bg-halaman: #f1f3f7;

            --putih-kartu: #ffffff;

            --border-tipis: #e2e8f0;

            --border-sangat-tipis: #eef2f7;

            --bg-kotak: #f8fafc;

            --slate-800: #1e293b;

            --slate-700: #334155;

            --slate-600: #475569;

            --slate-500: #64748b;

            --slate-400: #94a3b8;

            --font-heading: 'Poppins', sans-serif;

            --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;

            --sidebar-expanded-width: 250px;

            --sidebar-collapsed-width: 72px;

            --rail-slot-height: 56px;

            --rail-header-height: 70px;

            --sidebar-transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);

            --chart-biru: #3b82f6;

            --chart-teal: #0d9488;

            --chart-amber: #d97706;

            --chart-ungu: #7c3aed;

            --chart-slate: #64748b;

        }

        /* Reset link styles */
        a {
            text-decoration: none !important;
        }

        /* ANTI-GLITCH FOUC TRANSITIONS */
        .preload-no-transition * {
            transition: none !important;
        }

        body {

            font-family: var(--font-body) !important;

            background-color: var(--bg-halaman) !important;

        }

        h1,

        h2,

        h3,

        h4,

        h5,

        h6,

        .card-title,

        .navbar-brand,

        .dashboard-welcome .h4 {

            font-family: var(--font-heading) !important;

        }

        /* Navbar Styles */

        .navbar {

            padding: 0.5rem 1rem;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        }

        .navbar-logo {

            padding-right: 10px;

            /* Jarak di sebelah kanan logo */

        }

        .navbar-dark .navbar-nav .nav-link {

            color: rgba(255, 255, 255, .85);

            padding: 0.5rem 1rem;

            transition: all 0.3s ease;

        }

        .navbar-dark .navbar-nav .nav-link:hover {

            color: #fff;

            background: rgba(255, 255, 255, 0.1);

        }

        .navbar-dark .navbar-nav .nav-link.active {

            color: #fff;

            background: rgba(255, 255, 255, 0.15);

        }

        .navbar-nav .nav-item {

            display: flex;

            align-items: center;

        }

        .navbar-nav .nav-item span {

            margin-right: 10px;

            /* Jarak antara tanggal dan profil */

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

        .btn+.btn {

            margin-left: 0.5rem;

        }

        .btn-outline-light {

            border: 1px solid rgba(255, 255, 255, 0.5);

        }

        .btn-outline-light:hover {

            background: rgba(255, 255, 255, 0.1);

        }

        /* Table Styles */

        .table thead th,

        .table th,

        .table-bordered thead th,

        .table-bordered th {

            background-color: var(--navy-utama) !important;

            color: #FFFFFF !important;

            border-bottom: 2px solid var(--navy-utama) !important;

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

            background-color: rgba(0, 0, 0, .01);

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

            width: 100%;

            /* Atur lebar menjadi 100% dari kolom */

            max-width: 300px;

            /* Atur lebar maksimum sesuai kebutuhan */

            margin: 0 auto;

            /* Pusatkan kartu */

            border-radius: 10px;

            /* Pastikan sudut kartu melengkung */

            overflow: hidden;

            /* Sembunyikan konten yang melampaui batas */

            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

            /* Tambahkan bayangan untuk efek visual */

        }

        .dashboard-card h2 {

            font-size: 2rem;

            /* Ukuran font untuk nilai kartu */

        }

        .dashboard-card p {

            font-size: 1rem;

            /* Ukuran font untuk judul kartu */

        }

        .dashboard-card:hover {

            transform: translateY(-5px);

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);

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

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

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

            border: 1px solid rgba(0, 0, 0, 0.03);

            background: #ffffff;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);

            border-radius: 12px;

            transition: transform 0.3s ease, box-shadow 0.3s ease;

        }

        .card:hover {

            transform: translateY(-4px);

            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.07);

        }

        .card-header {

            background: none;

            border-bottom: 1px solid rgba(0, 0, 0, 0.05);

            padding: 1.5rem;

        }

        .card-body {

            padding: 1.5rem;

        }

        /* Utility classes */

        .shadow-sm {

            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;

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

            border: 1px solid #e5e7eb !important;

            /* Warna border abu-abu sangat terang */

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

            border-color: #d1d5db !important;

            /* Warna hover abu-abu medium */

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

            background-color: var(--navy-utama);

            color: white;

        }

        .btn-submit:hover {

            background-color: #172a5a;

            color: white;

        }

        .btn-primary {

            background-color: var(--navy-utama) !important;

            border-color: var(--navy-utama) !important;

            color: #ffffff !important;

        }

        .btn-primary:hover {

            background-color: #172a5a !important;

            border-color: #172a5a !important;

            color: #ffffff !important;

        }

        .sidebar {

            width: var(--sidebar-expanded-width);

            background-color: var(--navy-utama);

            padding: 20px;

            position: fixed;

            top: 0;

            left: 0;

            /* Default Terbuka di Desktop */

            height: 100vh;

            overflow-y: auto;

            padding-bottom: 80px;

            transition: left var(--sidebar-transition), width var(--sidebar-transition);

            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);

            z-index: 1050;

        }

        /* Responsive Sidebar Close on HP */

        @media (max-width: 991px) {

            .sidebar {

                left: calc(var(--sidebar-expanded-width) * -1);

            }

            .sidebar.active {

                left: 0;

            }

        }

        /* Tweak margin pada layar lebar agar konten tak tertutup */

        @media (min-width: 992px) {

            #main-content,

            .navbar {

                margin-left: var(--sidebar-expanded-width) !important;

                width: calc(100% - var(--sidebar-expanded-width)) !important;

                transition: margin-left var(--sidebar-transition), width var(--sidebar-transition);

            }

            .navbar {

                height: var(--rail-header-height) !important;

                min-height: var(--rail-header-height) !important;

            }

            /* Sidebar Collapsed di Desktop */

            .sidebar.collapsed,

            html.sidebar-mode-collapsed body .sidebar {

                width: var(--sidebar-collapsed-width, 72px) !important;

                padding: 0 !important;

            }

            .sidebar.collapsed~#main-content,

            .sidebar.collapsed~.navbar,

            html.sidebar-mode-collapsed body #main-content,

            html.sidebar-mode-collapsed body .navbar {

                margin-left: var(--sidebar-collapsed-width, 72px) !important;

                width: calc(100% - var(--sidebar-collapsed-width, 72px)) !important;

            }

            .close-sidebar {

                display: none !important;

            }

            /* Sembunyikan ikon X di desktop */

        }

        .sidebar-toggler {

            color: var(--gold-aksen) !important;

            transition: color 0.3s ease;

        }

        .sidebar.active~#main-content .sidebar-toggler,

        .sidebar.active~.navbar .sidebar-toggler {

            color: #ffffff !important;

        }

        /* Responsive Mobile: Sidebar Tertutup secara default */

        @media (max-width: 991px) {

            .sidebar-toggler {

                color: #ffffff !important;

            }

            .sidebar.active~#main-content .sidebar-toggler,

            .sidebar.active~.navbar .sidebar-toggler {

                color: var(--gold-aksen) !important;

            }

        }

        .sidebar a {

            color: #cbd5e1;

            font-size: 13.5px;

            font-weight: 400;

            text-decoration: none;

            display: flex !important;

            align-items: center;

            padding: 10px 16px;

            border-radius: 8px;

            transition: all 0.2s ease-out;

            margin-bottom: 4px;

        }

        .sidebar a:hover {

            color: #ffffff;

        }

        .sidebar a.active {

            color: var(--gold-aksen) !important;

            font-weight: 600;

        }

        .sidebar a.active i,

        .sidebar a.active svg {

            color: var(--gold-aksen) !important;

            transform: scale(1.05);

        }

        .sidebar i,

        .sidebar svg {

            color: var(--slate-500);

            margin-right: 12px;

            width: 20px;

            text-align: center;

            transition: all 0.2s ease-out;

        }

        .sidebar .navbar-brand {

            color: #f8fafc !important;

            font-size: 18px;

            padding: 10px 0;

            margin-bottom: 25px;

            text-align: center;

            display: block;

            border-bottom: 1px solid rgba(148, 163, 184, 0.15);

            /* Divider sidebar */

            font-family: var(--font-heading);

            font-weight: 700;

        }

        /* Hover effect untuk menu items */

        .sidebar a:hover i,

        .sidebar a:hover svg {

            transform: translateX(3px);

            transition: transform 0.3s ease;

        }

        .date-display {

            white-space: nowrap;

            /* Mencegah text wrap ke bawah */

            display: inline-block;

            /* Membuat elemen tetap dalam satu baris */

            margin-right: 15px;

            /* Jarak dengan profil */

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

            padding: 3px;

            /* Menambah padding untuk ruang indikator */

        }

        .profile-image {

            width: 40px;

            height: 40px;

            border-radius: 50%;

            border: 2px solid rgba(255, 255, 255, 0.8);

            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);

            transition: all 0.3s ease;

            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.3) 100%);

            white-space: nowrap;

            /* Mencegah teks membungkus ke baris berikutnya */

            overflow: hidden;

            /* Sembunyikan teks yang melampaui batas */

            text-overflow: ellipsis;

            /* Tambahkan ellipsis (...) untuk teks yang terpotong */

            max-width: 150px;

            /* Atur lebar maksimum sesuai kebutuhan */

            display: inline-block;

            /* Pastikan elemen bersifat inline-block */

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

            white-space: nowrap;

            /* Mencegah teks membungkus ke baris berikutnya */

            overflow: hidden;

            /* Sembunyikan teks yang melampaui batas */

            text-overflow: ellipsis;

            /* Tambahkan ellipsis (...) untuk teks yang terpotong */

            max-width: 150px;

            /* Atur lebar maksimum sesuai kebutuhan */

            display: inline-block;

            /* Pastikan elemen bersifat inline-block */

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

            background: var(--navy-utama);

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

            transform: translate(25%, 25%);

            /* Menggeser indikator ke luar lingkaran */

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

            background-color: var(--navy-utama) !important;

            border-bottom: 1px solid rgba(148, 163, 184, 0.15) !important;

            padding: 0.5rem 1rem !important;

            position: relative;

            z-index: 999;

        }

        .navbar-dark .navbar-nav .nav-link {

            color: #cbd5e1 !important;

        }

        .navbar-dark .navbar-nav .nav-link:hover {

            color: #ffffff !important;

            background: rgba(255, 255, 255, 0.05);

        }

        body {

            background: #f1f5f9;

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

            background: linear-gradient(135deg, #4C1D95, #D8B4FE);

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

        select,

        input,

        .btn {

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

            background-color: #ffffff !important;

            /* Light blue */

            color: #1e3a8a !important;

            border: 1px solid #D1D5DB !important;

            /* Added grey border */

            border-radius: 8px;

            padding: 8px 16px;

        }

        /* Style untuk dropdown Subpoint */

        select[name="subpoint"] {

            background-color: #f1c75b !important;

            /* Light yellow */

            color: #8a5e1e !important;

            border: none !important;

        }

        /* Style untuk dropdown Status */

        select[name="status"] {

            background-color: #ffffff !important;

            /* Light green */

            color: #166534 !important;

            border: 1px solid #D1D5DB !important;

            /* Added grey border */

            border-radius: 8px;

            padding: 8px 16px;

            /* Maintain border-radius */

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

            width: 200px !important;

            /* Memperlebar textarea */

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

            overflow: visible !important;

            /* Ubah dari hidden ke visible */

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

            z-index: 10 !important;

            /* Memastikan dot muncul di atas */

        }

        .header h2 {

            border-bottom: 2px solid #ccc;

            /* Garis bawah */

            padding-bottom: 5px;

            /* Jarak antara teks dan garis */

        }

        .header h3 {

            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);

            /* Bayangan */

        }

        .table-container table td {

            text-align: center;

            /* Rata tengah untuk semua sel */

        }

        #customSearch {

            margin-bottom: 20px;

            /* Atur jarak sesuai kebutuhan */

        }

        .table-container th,

        .table-container td {

            max-width: 200px;

            /* Adjust the width as needed */

            overflow: hidden;

            /* Hide overflow content */

            text-overflow: ellipsis;

            /* Add ellipsis for overflow text */

            white-space: normal;

            /* Allow text to wrap */

        }

        /* --- Scrollbar Customization --- */

        .sidebar::-webkit-scrollbar {

            width: 5px;

        }

        .sidebar::-webkit-scrollbar-track {

            background: transparent;

        }

        .sidebar::-webkit-scrollbar-thumb {

            background: rgba(255, 255, 255, 0.15);

            border-radius: 10px;

        }

        .sidebar::-webkit-scrollbar-thumb:hover {

            background: rgba(255, 255, 255, 0.3);

        }

        /* Body Scrollbar */

        ::-webkit-scrollbar {

            width: 8px;

        }

        ::-webkit-scrollbar-track {

            background: transparent;

        }

        ::-webkit-scrollbar-thumb {

            background: #cbd5e1;

            border-radius: 4px;

        }

        ::-webkit-scrollbar-thumb:hover {

            background: #94a3b8;

        }

        /* Breadcrumb UI */

        .breadcrumb-custom {

            font-size: 0.9rem;

            margin-bottom: 1.5rem;

            color: #94a3b8;

            background: #ffffff;

            padding: 0.75rem 1.25rem;

            border-radius: 8px;

            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);

            display: inline-flex;

            align-items: center;

        }

        .breadcrumb-custom a {

            color: #94a3b8;

            text-decoration: none;

            transition: color 0.2s;

        }

        .breadcrumb-custom a:hover {

            color: var(--navy-utama);

        }

        .breadcrumb-custom .separator {

            margin: 0 0.5rem;

            color: #cbd5e1;

            font-size: 0.8em;

        }

        <style>.breadcrumb-navbar {

            font-size: 1rem;

            font-weight: 600;

            margin-left: 0.5rem;

            color: rgba(255, 255, 255, 0.85);

            white-space: nowrap;

            overflow-x: auto;

            max-width: 100%;

            display: flex;

            align-items: center;

            -ms-overflow-style: none;

            /* IE and Edge */

            scrollbar-width: none;

            /* Firefox */

        }

        .breadcrumb-navbar::-webkit-scrollbar {

            display: none;

        }

        .breadcrumb-navbar .separator {

            margin: 0 0.5rem;

            font-size: 0.9rem;

            color: rgba(255, 255, 255, 0.8) !important;

            flex-shrink: 0;

        }

        .breadcrumb-navbar .hover-white:hover {

            color: #ffffff !important;

        }

        .breadcrumb-navbar a {

            display: inline-flex;

            align-items: center;

            white-space: nowrap;

            flex-shrink: 0;

        }

        .breadcrumb-navbar span {

            display: inline-flex;

            white-space: nowrap;

            align-items: center;

            flex-shrink: 0;

        }

        .breadcrumb-navbar span i,

        .breadcrumb-navbar a i {

            margin-right: 0.5rem;

            font-size: 1rem;

        }

        /* Responsive Table No Column Fix */

        .table th:first-child,

        .table td:first-child {

            width: 5% !important;

            min-width: 50px !important;

            white-space: nowrap !important;

        }

        /* KELUARKAN TRANSISI DARI HTML JIKA PRELOAD AKTIF (Flicker prevention) */

        html.preload-no-transition * {

            transition: none !important;

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

<body style="font-family: 'Plus Jakarta Sans', sans-serif;" class="antialiased font-sans bg-gray-50">

    <div class="min-h-screen bg-gray-50" id="main-wrapper">

        <!-- Sidebar -->

        @include('layouts.sidebar')

        <!-- Navigation -->

        @include('layouts.navigation')

        <!-- Page Content -->

        <main id="main-content"
            style="margin-top: 0; transition: margin-left 0.3s cubic-bezier(0.16, 1, 0.3, 1), width 0.3s cubic-bezier(0.16, 1, 0.3, 1);">

            <div class="container py-4">

                @yield('content')

            </div>

        </main>

    </div>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Sidebar Navigasi dihapus karena sudah di-handle oleh sidebar.blade.php
    </script>

    <!-- SweetAlert2 JS -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script untuk notifikasi -->

    <script>
        // Notifikasi sukses

        @if (session('success'))

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

        @if (session('error'))

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
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.sidebar a[href="#"]').forEach(item => {

                item.addEventListener('click', function(event) {

                    event.preventDefault();

                    const submenu = item.nextElementSibling;

                    if (submenu) submenu.classList.toggle('hidden');

                    // Putar ikon chevron

                    const iconChevron = item.querySelector('.fa-chevron-down');

                    if (iconChevron) iconChevron.classList.toggle('rotate-180');

                });

            });

        });
    </script>

    <!-- SEAMLESS AJAX LIVE SEARCH & NAVIGATION -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Global listener untuk input ketikan (Search) dan Select (Filter Role/Aksi)

            document.addEventListener('input', function(e) {

                if ((e.target.name === 'search' || e.target.tagName === 'SELECT') && !e.target.closest(
                        '.modal')) {

                    const form = e.target.closest('form');

                    // Pastikan ini adalah form pencarian (method GET)

                    if (form && form.method.toUpperCase() === 'GET') {

                        clearTimeout(window.liveSearchTimeout);

                        // Berikan efek visual subtle

                        e.target.style.transition = 'opacity 0.2s';

                        e.target.style.opacity = '0.7';

                        window.liveSearchTimeout = setTimeout(() => {

                            const formData = new FormData(form);

                            const query = new URLSearchParams(formData).toString();

                            const url = form.action + (form.action.includes('?') ? '&' : '?') +

                                query;

                            window.history.pushState(null, '', url);

                            // Simpan status fokus aktif pengguna

                            const activeId = document.activeElement.id;

                            const activeName = document.activeElement.name;

                            let selStart = 0,

                                selEnd = 0;

                            try {

                                selStart = document.activeElement.selectionStart;

                                selEnd = document.activeElement.selectionEnd;

                            } catch (err) {}

                            fetch(url, {

                                    headers: {

                                        'X-Ajax-Live-Search': 'true'

                                    }

                                })

                                .then(res => res.text())

                                .then(html => {

                                    const parser = new DOMParser();

                                    const doc = parser.parseFromString(html, 'text/html');

                                    const mainContent = document.getElementById('main-content');

                                    const newMainContent = doc.getElementById('main-content');

                                    if (mainContent && newMainContent) {

                                        mainContent.innerHTML = newMainContent.innerHTML;

                                    }

                                    // Kembalikan fokus kursor agar nyaman dilanjut mengetik

                                    let toFocus = null;

                                    if (activeId) toFocus = document.getElementById(activeId);

                                    if (!toFocus && activeName) toFocus = document

                                        .querySelector(`input[name="${activeName}"]`);

                                    if (toFocus) {

                                        toFocus.focus();

                                        try {

                                            toFocus.setSelectionRange(selStart, selEnd);

                                            toFocus.style.opacity = '1';

                                        } catch (err) {}

                                    }

                                }).catch(err => {

                                    console.error('Live Search Error:', err);

                                    e.target.style.opacity = '1';

                                });

                        }, 400); // Debounce jeda 400ms 

                    }

                }

            });

            // Global listener untuk men-AJAX-kan click pada Sort Dropdown dan Pagination

            document.addEventListener('click', function(e) {

                const link = e.target.closest('.pagination a, .dropdown-menu .dropdown-item');

                // Pengecualian batas aman: Jika link memiliki target/method/bukan GET biasa, abaikan

                if (link && link.href && !link.href.includes('#') && !link.hasAttribute('data-bs-toggle') &&

                    link.href.includes(window.location.origin)) {

                    e.preventDefault();

                    window.history.pushState(null, '', link.href);

                    document.body.style.cursor = 'wait';

                    if (e.target.style) e.target.style.opacity = '0.5';

                    fetch(link.href, {

                            headers: {

                                'X-Ajax-Live-Search': 'true'

                            }

                        })

                        .then(res => res.text())

                        .then(html => {

                            document.body.style.cursor = 'default';

                            const parser = new DOMParser();

                            const doc = parser.parseFromString(html, 'text/html');

                            // Update Title
                            const title = doc.querySelector('title');
                            if (title) document.title = title.innerText;

                            // Update breadcrumb
                            const breadcrumbContainer = document.getElementById('breadcrumb-container');
                            const newBreadcrumbContainer = doc.getElementById('breadcrumb-container');
                            if (breadcrumbContainer && newBreadcrumbContainer) {
                                breadcrumbContainer.innerHTML = newBreadcrumbContainer.innerHTML;
                            }

                            // Update Sidebar
                            const sidebar = document.getElementById('sidebar');
                            const newSidebar = doc.getElementById('sidebar');
                            if (sidebar && newSidebar) {
                                sidebar.innerHTML = newSidebar.innerHTML;
                                if (typeof initFlyoutHoverLogic === 'function') initFlyoutHoverLogic();
                            }

                            const mainContent = document.getElementById('main-content');
                            const newMainContent = doc.getElementById('main-content');
                            if (mainContent && newMainContent) {
                                mainContent.innerHTML = newMainContent.innerHTML;

                                // Scroll lembut kembali ke atas daftar
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'auto'
                                });
                            }

                        }).catch(err => {

                            document.body.style.cursor = 'default';

                            window.location.href = link.href; // Fallback jika gagal

                        });

                }

            });

            // Handle tombol back/forward di browser
            window.addEventListener('popstate', function() {
                fetch(window.location.href)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Update Title
                        const title = doc.querySelector('title');
                        if (title) document.title = title.innerText;

                        // Update breadcrumb
                        const breadcrumbContainer = document.getElementById('breadcrumb-container');
                        const newBreadcrumbContainer = doc.getElementById('breadcrumb-container');
                        if (breadcrumbContainer && newBreadcrumbContainer) {
                            breadcrumbContainer.innerHTML = newBreadcrumbContainer.innerHTML;
                        }

                        // Update Sidebar
                        const sidebar = document.getElementById('sidebar');
                        const newSidebar = doc.getElementById('sidebar');
                        if (sidebar && newSidebar) {
                            sidebar.innerHTML = newSidebar.innerHTML;
                            if (typeof initFlyoutHoverLogic === 'function') initFlyoutHoverLogic();
                        }

                        const mainContent = document.getElementById('main-content');
                        const newMainContent = doc.getElementById('main-content');
                        if (mainContent && newMainContent) mainContent.innerHTML = newMainContent
                            .innerHTML;
                    });
            });

        });
    </script>

    @stack('scripts')

    @yield('modals')

</body>

</html>
