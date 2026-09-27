<!-- Navigation -->
<style>
    /* Base Desktop Styles */
    .custom-navbar {
        margin: 0;
        padding: 0 1rem;
        background-color: #0f1b3d !important;
        position: sticky;
        top: 0;
        z-index: 1040;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
    }

    /* Paksa elemen navbar agar tidak pernah turun baris (wrap) */
    .custom-navbar .container-fluid {
        flex-wrap: nowrap !important;
    }

    /* Anulir bawaan bootstrap .navbar-collapse yang mematikan offset absolut di bawah mode LG */
    .custom-navbar .navbar-nav .dropdown-menu {
        position: absolute !important;
    }

    /* Base avatar initials */
    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .avatar-initials {
        color: #eab308;
        font-weight: 700;
    }

    /* Mobile Topbar Specific Styles (< 768px) */
    @media (max-width: 767.98px) {
        .custom-navbar {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            height: 60px !important;
            padding: 0 16px !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1040 !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08) !important;
        }

        .custom-navbar .container-fluid {
            padding: 0 !important;
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
        }

        .mobile-left-group {
            display: flex !important;
            align-items: center !important;
        }

        .mobile-brand-title {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            color: #f8fafc !important;
            margin-left: 8px !important;
            display: block !important;
        }

        .mobile-right-group {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 16px !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .touch-target {
            width: 44px !important;
            height: 44px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
        }

        /* Hamburger padding override */
        .btn.sidebar-toggler {
            margin: 0 !important;
            width: 44px !important;
            height: 44px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Icon styling - 20-22px size, stroke #cbd5e1 */
        .icon-mobile {
            color: #cbd5e1 !important;
            font-size: 22px !important;
        }

        /* Avatar mobile */
        .avatar-circle.mobile-avatar {
            width: 32px !important;
            height: 32px !important;
        }

        .avatar-initials.mobile-avatar-text {
            font-size: 13px !important;
        }

        /* Override Desktop spacing classes that interfere on mobile */
        .nav-item.dropdown {
            margin: 0 !important;
            padding: 0 !important;
        }

        .nav-link {
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* DROPDOWN PROFIL (MOBILE ABSOLUTE OVERRIDE) */
        .profile-nav-item {
            position: relative !important;
        }

        .profile-dropdown-menu.show {
            position: absolute !important;
            top: calc(100% + 8px) !important;
            right: 16px !important;
            /* Aman dari tepi layar */
            left: auto !important;
            width: 250px !important;
            z-index: 101 !important;
            transform: none !important;
            background: #ffffff !important;
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.18) !important;
            padding: 4px 0 !important;
        }
    }

    /* Desktop Fallbacks */
    @media (min-width: 768px) {
        .mobile-brand-title {
            display: none !important;
        }

        .mobile-left-group {
            display: flex;
            align-items: center;
        }

        .avatar-circle.mobile-avatar {
            width: 40px;
            height: 40px;
        }

        .avatar-initials.mobile-avatar-text {
            font-size: 14px;
        }

        .mobile-right-group {
            /* Dihapus agar elemen melekat pada batas pinggir pada default container padding */
        }

        .profile-dropdown-menu.show {
            width: 250px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            padding: 4px 0;
        }
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark w-100 custom-navbar">
    <div class="container-fluid">
        <!-- GROUP KIRI (Hamburger + Title) -->
        <div class="mobile-left-group">
            <div class="hamburger-wrapper rail-slot">
                <button
                    class="btn btn-outline-light border-0 shadow-none bg-transparent sidebar-toggler touch-target m-0"
                    onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1"
                        style="width: 28px; height: 28px; stroke-width: 2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <a href="{{ route('dashboard') }}" class="mobile-brand-title" style="text-decoration: none;">SIAP
                BROH!!!</a>
        </div>

        <div class="me-auto d-none d-md-flex align-items-center" style="min-width: 0;">
            @hasSection('breadcrumb')
                <div class="breadcrumb-navbar d-flex flex-nowrap align-items-center"
                    style="margin-top: 1px; max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <a href="{{ route('dashboard') }}" class="text-white-50 hover-white flex-shrink-0"
                        style="text-decoration: none; transition: color 0.2s;">
                        <i class="fas fa-home"></i> <span class="d-none d-md-inline">Beranda</span>
                    </a>
                    <div class="text-truncate ms-2" style="font-size: 0.9rem;">
                        @yield('breadcrumb')
                    </div>
                </div>
            @endif
        </div>
        <!-- Menu navbar yang ganda (Dashboard, Transaksi Surat) dihapus agar hanya fokus pada Sidebar dan membebaskan ruang horizontal -->

        <div class="navbar-nav flex-row align-items-center d-none d-sm-flex">
            <span class="nav-item me-3 d-flex align-items-center" style="color: #cbd5e1;">
                <span class="date-display d-none d-lg-inline"
                    style="font-size: 13.5px; font-weight: 500;">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                <i class="fas fa-calendar-alt ms-2"></i>
            </span>
        </div>

        <!-- GROUP KANAN (Notifikasi + Avatar) -->
        <div class="navbar-nav mobile-right-group flex-row align-items-center">
            <!-- Notification Dropdown -->
            <div class="nav-item dropdown me-md-3">
                <a class="nav-link position-relative touch-target" href="#" id="notificationDropdown"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,15">
                    <i class="fas fa-bell icon-mobile"></i>
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                            style="font-size: 0.6rem; margin-top: 5px; margin-left: -5px; background-color: #dc2626; border: 2px solid #0f1b3d;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown"
                    style="width: 320px; max-height: 400px; overflow-y: auto; margin-top: 0.5rem;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center bg-white"
                        style="border-bottom: 1px solid #f1f5f9; padding: 15px 20px;">
                        <h6 class="mb-0 fw-bold" style="color: #334155; font-size: 0.95rem;">Notifikasi</h6>
                    </li>

                    @forelse(Auth::user()->unreadNotifications->take(4) as $notification)
                        @php
                            $title = $notification->data['title'] ?? 'Pemberitahuan Sistem';
                            $titleLower = strtolower($title);

                            $iconClass = 'fas fa-info-circle text-secondary';
                            $iconBg = 'background: rgba(100, 116, 139, 0.1); color: #64748b;';

                            if (
                                str_contains($titleLower, 'surat') ||
                                str_contains($titleLower, 'masuk') ||
                                str_contains($titleLower, 'keluar')
                            ) {
                                $iconClass = 'fas fa-envelope text-warning';
                                $iconBg = 'background: rgba(245, 158, 11, 0.1); color: #f59e0b;';
                            } elseif (
                                str_contains($titleLower, 'disetujui') ||
                                str_contains($titleLower, 'selesai') ||
                                str_contains($titleLower, 'sukses') ||
                                str_contains($titleLower, 'aktif')
                            ) {
                                $iconClass = 'fas fa-check text-success';
                                $iconBg = 'background: rgba(16, 185, 129, 0.1); color: #10b981;';
                            } elseif (
                                str_contains($titleLower, 'sandi') ||
                                str_contains($titleLower, 'password') ||
                                str_contains($titleLower, 'kredensial') ||
                                str_contains($titleLower, 'kunci')
                            ) {
                                $iconClass = 'fas fa-lock text-info';
                                $iconBg = 'background: rgba(56, 189, 248, 0.1); color: #38bdf8;';
                            } elseif (
                                str_contains($titleLower, 'ditolak') ||
                                str_contains($titleLower, 'gagal') ||
                                str_contains($titleLower, 'error')
                            ) {
                                $iconClass = 'fas fa-times text-danger';
                                $iconBg = 'background: rgba(239, 68, 68, 0.1); color: #ef4444;';
                            }
                        @endphp
                        <li>
                            <a class="dropdown-item py-3 px-4 d-flex align-items-start"
                                href="{{ route('notifications.read', $notification->id) }}"
                                style="border-bottom: 1px solid #f8fafc; white-space: normal; background-color: #fffbeb; transition: background-color 0.2s;">

                                <span class="rounded-circle mt-1"
                                    style="min-width: 6px; height: 6px; background-color: #f59e0b; display: inline-block; margin-right: 12px;"></span>

                                <div class="flex-grow-1">
                                    <h6 class="mb-1" style="font-size: 0.85rem; font-weight: 600; color: #1e293b;">
                                        {{ $title }}
                                    </h6>
                                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li>
                            <div class="dropdown-item text-center py-4" style="background: transparent;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                    style="width: 50px; height: 50px; background: rgba(226, 232, 240, 0.5);">
                                    <i class="fas fa-bell-slash text-slate-400" style="color: #94a3b8;"></i>
                                </div>
                                <span class="text-muted" style="font-size: 0.85rem;">Tidak ada notifikasi baru</span>
                            </div>
                        </li>
                    @endforelse

                    <div style="background-color: #f8fafc; border-top: 1px solid #f1f5f9;">
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.read.all') }}" method="POST" class="px-3 pt-2 pb-1">
                                @csrf
                                <button type="submit" class="dropdown-item text-center fw-bold"
                                    style="font-size: 0.8rem; color: #64748b; background: transparent;">Tandai Semua
                                    Dibaca</button>
                            </form>
                        @endif
                        <div class="px-3 pb-2 pt-1 text-center">
                            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center fw-bold"
                                style="font-size: 0.85rem; color: #2563eb; background: transparent;">
                                Liha Semua Notifikasi
                            </a>
                        </div>
                    </div>
                </ul>
            </div>

            <!-- Profile Dropdown -->
            <div class="nav-item dropdown profile-nav-item">
                <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,15">
                    <span class="me-2 d-none d-lg-inline profile-name"
                        style="color: #f8fafc; font-weight: 600; font-size: 13.5px;">{{ Auth::user()->name }}</span>
                    <div class="position-relative">
                        <div class="avatar-circle mobile-avatar touch-target">
                            @if (Auth::user()->avatar && file_exists(public_path('storage/' . Auth::user()->avatar)))
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div
                                    class="avatar-initials mobile-avatar-text w-100 h-100 d-flex align-items-center justify-content-center">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu shadow">
                    <li class="dropdown-header d-flex align-items-center" style="padding: 16px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background-color: #0f1b3d; overflow: hidden; flex-shrink: 0;">
                            @if (Auth::user()->avatar && file_exists(public_path('storage/' . Auth::user()->avatar)))
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="color: #eab308; font-weight: 700; font-size: 16px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ms-3 overflow-hidden">
                            <div
                                style="font-weight: 700; font-size: 14px; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ Auth::user()->name }}</div>
                            <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">
                                {{ ucfirst(Auth::user()->role) }}</div>
                        </div>
                    </li>
                    <li style="padding: 0 16px;">
                        <hr class="dropdown-divider m-0" style="border-color: #f1f5f9;">
                    </li>
                    <li style="padding: 8px 16px;">
                        <a class="dropdown-item d-flex align-items-center px-0 py-2" href="{{ route('profile') }}"
                            style="color: #1e293b; font-size: 14px; font-weight: 500; background: transparent;">
                            <i class="fas fa-user-cog me-3"
                                style="color: #64748b; font-size: 16px; width: 20px; text-align: center;"></i>
                            Pengaturan Akun
                        </a>
                    </li>
                    <li style="padding: 0 16px;">
                        <hr class="dropdown-divider m-0" style="border-color: #f1f5f9;">
                    </li>
                    <li style="padding: 8px 16px;">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center px-0 py-2 w-100 text-start"
                                type="submit"
                                style="color: #dc2626; font-size: 14px; font-weight: 500; background: transparent;">
                                <i class="fas fa-sign-out-alt me-3"
                                    style="font-size: 16px; width: 20px; text-align: center;"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>




<!-- Responsive Navigation Menu -->
<div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        <a :href="route('data-requests.index')" :active="request()->routeIs('data-requests.*')">
            <i class="fas fa-file-alt mr-2"></i>
            {{ __('Permintaan Data') }}
        </a>
    </div>
</div>
