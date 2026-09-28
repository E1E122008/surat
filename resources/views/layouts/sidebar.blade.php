@php
    $userRole = Auth::check() ? Auth::user()->role : 'user';
    $pendingApprovalCount = 0;
    $pendingHelpCount = 0;

    if ($userRole === 'admin') {
        $pendingApprovalCount = \App\Models\ApprovalRequest::where('status', 'pending')->count();
        $pendingHelpCount = \App\Models\HelpRequest::where('status', 'menunggu')->count();
    }

    $menus = [
        [
            'category' => 'Menu Utama',
            'items' => [
                [
                    'title' => 'Beranda',
                    'icon' => 'fas fa-home',
                    'url' => route('dashboard'),
                    'active' => request()->routeIs('dashboard'),
                    'roles' => ['admin', 'monitor', 'user', 'superadmin'],
                ],
                [
                    'title' => 'Surat Umum',
                    'icon' => 'fas fa-envelope',
                    'active' => request()->routeIs('surat-masuk.*') || request()->routeIs('surat-keluar.*'),
                    'roles' => ['admin', 'monitor', 'superadmin'],
                    'submenu' => [
                        [
                            'title' => 'Surat Masuk',
                            'icon' => 'fas fa-envelope',
                            'url' => route('surat-masuk.index'),
                            'active' => request()->routeIs('surat-masuk.*'),
                        ],
                        [
                            'title' => 'Surat Keluar',
                            'icon' => 'fas fa-paper-plane',
                            'url' => route('surat-keluar.index'),
                            'active' => request()->routeIs('surat-keluar.*'),
                        ],
                    ],
                ],
                [
                    'title' => 'SK Kepala Biro',
                    'icon' => 'fas fa-file-contract',
                    'url' => route('sk-karo.index'),
                    'active' => request()->routeIs('sk-karo.*'),
                    'roles' => ['admin', 'monitor', 'superadmin'],
                ],
                [
                    'title' => 'Regis Draft PHD',
                    'icon' => 'fas fa-scroll',
                    'active' => request()->routeIs('draft-phd.*'),
                    'roles' => ['admin', 'monitor', 'superadmin'],
                    'submenu' => [
                        [
                            'title' => 'SK',
                            'icon' => 'fas fa-gavel',
                            'url' => route('draft-phd.sk.index'),
                            'active' => request()->routeIs('draft-phd.sk.*'),
                        ],
                        [
                            'title' => 'PERDA',
                            'icon' => 'fas fa-scroll',
                            'url' => route('draft-phd.perda.index'),
                            'active' => request()->routeIs('draft-phd.perda.*'),
                        ],
                        [
                            'title' => 'PERGUB',
                            'icon' => 'fas fa-scroll',
                            'url' => route('draft-phd.pergub.index'),
                            'active' => request()->routeIs('draft-phd.pergub.*'),
                        ],
                    ],
                ],
                [
                    'title' => 'SPT',
                    'icon' => 'fas fa-file-signature',
                    'active' =>
                        request()->routeIs('spt.*') ||
                        request()->routeIs('spt-dalam-daerah.*') ||
                        request()->routeIs('spt-luar-daerah.*'),
                    'roles' => ['admin', 'monitor', 'superadmin'],
                    'submenu' => [
                        [
                            'title' => 'SPT DD',
                            'icon' => 'fas fa-file-signature',
                            'url' => route('spt-dalam-daerah.index'),
                            'active' => request()->routeIs('spt-dalam-daerah.*'),
                        ],
                        [
                            'title' => 'SPT LD',
                            'icon' => 'fas fa-file-alt',
                            'url' => route('spt-luar-daerah.index'),
                            'active' => request()->routeIs('spt-luar-daerah.*'),
                        ],
                    ],
                ],
                [
                    'title' => 'SPPD',
                    'icon' => 'fas fa-car',
                    'active' =>
                        request()->routeIs('sppd.*') ||
                        request()->routeIs('sppd-dalam-daerah.*') ||
                        request()->routeIs('sppd-luar-daerah.*'),
                    'roles' => ['admin', 'monitor', 'superadmin'],
                    'submenu' => [
                        [
                            'title' => 'SPPD DD',
                            'icon' => 'fas fa-car',
                            'url' => route('sppd-dalam-daerah.index'),
                            'active' => request()->routeIs('sppd-dalam-daerah.*'),
                        ],
                        [
                            'title' => 'SPPD LD',
                            'icon' => 'fas fa-plane',
                            'url' => route('sppd-luar-daerah.index'),
                            'active' => request()->routeIs('sppd-luar-daerah.*'),
                        ],
                    ],
                ],
                [
                    'title' => 'Transaksi Surat',
                    'icon' => 'fas fa-exchange-alt',
                    'url' => route('transaksi-surat.index'),
                    'active' => request()->routeIs('transaksi-surat.*'),
                    'roles' => ['user'],
                ],
                [
                    'title' => 'Pengajuan Berkas Baru',
                    'icon' => 'fas fa-folder-plus',
                    'url' => route('data-requests.index'),
                    'active' => request()->routeIs('data-requests.*'),
                    'roles' => ['user', 'monitor'],
                ],
            ],
        ],
        [
            'category' => 'Menu Tambahan',
            'items' => [
                [
                    'title' => 'Arsip',
                    'icon' => 'fas fa-file-signature',
                    'active' => request()->routeIs('buku-agenda.*'),
                    'roles' => ['admin', 'superadmin'],
                    'submenu' => [
                        [
                            'title' => 'Surat Masuk',
                            'icon' => 'fas fa-book',
                            'url' => route('buku-agenda.index'),
                            'active' => request()->routeIs('buku-agenda.index'),
                        ],
                        [
                            'title' => 'Surat Keluar',
                            'icon' => 'fas fa-book',
                            'url' => route('buku-agenda.kategori-keluar.index'),
                            'active' => request()->routeIs('buku-agenda.kategori-keluar.index'),
                        ],
                    ],
                ],
                [
                    'title' => 'Manajemen Pengguna',
                    'icon' => 'fas fa-users',
                    'url' => route('users.index'),
                    'active' => request()->routeIs('users.index'),
                    'roles' => ['admin', 'superadmin'],
                ],
                [
                    'title' => 'Persetujuan',
                    'icon' => 'fas fa-clipboard-check',
                    'url' => route('admin.approval-requests.index'),
                    'active' => Request::is('admin/approval-requests*'),
                    'roles' => ['admin', 'superadmin'],
                    'badge' => $pendingApprovalCount > 0 ? $pendingApprovalCount : null,
                    'badgeClass' => 'bg-warning text-dark',
                ],
                [
                    'title' => $userRole === 'admin' ? 'Pelaporan' : 'Bantuan & Kontak',
                    'icon' => $userRole === 'admin' ? 'fas fa-bullhorn' : 'fas fa-question-circle',
                    'url' => route('bantuan.index'),
                    'active' => request()->routeIs('bantuan.index'),
                    'roles' => ['admin', 'monitor', 'user', 'superadmin'],
                    'iconStyle' => request()->routeIs('bantuan.index') ? 'color: #fbbf24;' : '',
                    'badge' => $pendingHelpCount > 0 ? $pendingHelpCount : null,
                    'badgeClass' => 'bg-danger text-white',
                ],
            ],
        ],
    ];
@endphp

<style>
    :root {
        --sidebar-collapsed-width: 72px;
        --gold-aksen: #eab308;
        --navy-utama: #0f1b3d;
    }

    /* Scroll flekisbel - container menu */
    .menu-scroll-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: 0;
        /* Penting untuk nested flex scrolling */
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.3) transparent;
    }

    .menu-scroll-container::-webkit-scrollbar {
        width: 4px;
    }

    .menu-scroll-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .menu-scroll-container::-webkit-scrollbar-thumb {
        background-color: rgba(148, 163, 184, 0.3);
        border-radius: 10px;
    }

    /* Scrim Mobile overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(6, 10, 18, 0.45);
        z-index: 1040;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar-footer .footer-chevron {
        transition: transform 0.3s ease;
    }

    /* Responsive Mobile Layout */
    @media (max-width: 991px) {
        .sidebar {
            width: 85% !important;
            max-width: 320px;
            left: -100% !important;
            /* Tersembunyi */
            top: 0;
            bottom: 0;
            z-index: 1050;
        }

        .sidebar.active {
            left: 0 !important;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    }

    /* Desktop Collapsed Mode */
    @media (min-width: 992px) {
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width) !important;
            overflow: visible !important;
        }

        /* Hilangkan Scrollbar asli secara keseluruhan pada rel saat layarnya menciut 72px */
        .sidebar.collapsed .menu-scroll-container {
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 0 !important;
        }

        .sidebar.collapsed .menu-scroll-container::-webkit-scrollbar {
            display: none;
        }

        /* --- RAIL SLOT STANDARD COMPONENT --- */
        .sidebar.collapsed .rail-slot,
        .sidebar.collapsed a.menu-link,
        .navbar .rail-slot {
            width: var(--sidebar-collapsed-width, 72px) !important;
            height: var(--rail-slot-height, 56px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        /* Override khusus Header Logo agar senada 70px dengan Navbar Baru */
        .sidebar.collapsed .logo-container.rail-slot {
            height: var(--rail-header-height, 70px) !important;
        }

        .sidebar.collapsed .rail-slot img,
        .sidebar.collapsed .rail-slot svg,
        .sidebar.collapsed .rail-slot i,
        .navbar .rail-slot img,
        .navbar .rail-slot svg,
        .navbar .rail-slot i {
            max-width: 28px !important;
            max-height: 28px !important;
            object-fit: contain !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ------------------------------------ */

        .sidebar.collapsed .navbar-brand,
        .sidebar.collapsed .category-title,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .chevron-icon {
            display: none !important;
        }

        /* RESTRUKTURISASI BADGE SECARA BULLETPROOF */
        .badge-collapsed {
            display: none !important;
        }

        .badge-expanded {
            display: inline-block !important;
            /* Gunakan display natif badge pada mode lainnya */
        }

        /* HANYA ketika di Desktop dan Sidebar TERLIPAT, barulah peran mereka ditukar */
        @media (min-width: 992px) {
            .sidebar.collapsed .badge-collapsed {
                display: flex !important;
                position: absolute !important;
                top: -4px !important;
                right: -4px !important;
                margin: 0 !important;
                font-size: 0.55rem !important;
                padding: 2px 4px !important;
                min-width: 14px;
                height: 14px;
                justify-content: center;
                align-items: center;
                border-radius: 50% !important;
                z-index: 10;
            }

            .sidebar.collapsed .badge-expanded {
                display: none !important;
            }
        }

        .sidebar.collapsed a.menu-link {
            justify-content: center !important;
            padding: 0 !important;
            position: relative;
        }

        .sidebar.collapsed a.menu-link:hover {
            transform: none !important;
            /* Jangan bergeser 4px! */
        }

        .sidebar.collapsed .icon-wrapper {
            margin-right: 0 !important;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .sidebar.collapsed i {
            margin-right: 0 !important;
            font-size: 16px;
        }

        /* STATE AKTIF MODE COLLAPSED (Center, Highlight Gold) */
        .sidebar.collapsed a.menu-link.active {
            border-left: none !important;
            background-color: transparent !important;
        }

        .sidebar.collapsed a.menu-link.active .icon-wrapper {
            background-color: rgba(234, 179, 8, 0.14) !important;
            border-left: 3px solid var(--gold-aksen);
        }

        .sidebar.collapsed a.menu-link.active i {
            color: var(--gold-aksen) !important;
        }

        /* STATE HOVER MODE COLLAPSED */
        .sidebar.collapsed a.menu-link:hover .icon-wrapper {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Footer Toggle arrow animation */
        .sidebar.collapsed .footer-chevron {
            transform: rotate(180deg);
        }

        .submenu-indicator {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background-color: var(--gold-aksen);
            border-radius: 50%;
            display: none;
        }

        .sidebar.collapsed .menu-item.has-submenu .submenu-indicator {
            display: block;
            /* Tampilkan titik kuning saat collapse. */
        }

        /* --- FLYOUT JS BASED CSS --- */
        .sidebar.collapsed .submenu-container {
            display: none;
        }

        .sidebar.collapsed .submenu-container.show-flyout {
            display: block !important;
            position: fixed;
            left: var(--sidebar-collapsed-width);
            z-index: 1060;
            min-width: 220px;
            background-color: transparent;
            padding-left: 14px;
            /* Jembatan padat diperlebar agar no-deadzone telak */
            animation: flyoutFade .2s ease forwards;
        }

        @keyframes flyoutFade {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar.collapsed .submenu-container .submenu-panel {
            background-color: var(--navy-utama);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            margin: 0;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar.collapsed .submenu-container .submenu-text {
            display: inline !important;
        }

        .sidebar.collapsed .submenu-container i {
            margin-right: 12px !important;
            font-size: 14px;
        }

        .sidebar.collapsed .submenu-container a {
            padding: 10px 16px !important;
            justify-content: flex-start !important;
        }

        /* KEMBALIKAN STYLE AKTIF & HOVER DIMODE EXPANDED (Menghidupkan highlight sebari penuh) */
        .sidebar:not(.collapsed) a.menu-link.active {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-left: 4px solid var(--gold-aksen) !important;
        }

        .sidebar:not(.collapsed) a.menu-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        /* Normal expanded behavior (Accordion Panel) */
        .sidebar:not(.collapsed) .submenu-container {
            position: static;
            padding-left: 0;
            background: transparent;
            transform: none;
            opacity: 1;
            visibility: visible;
            display: none;
        }

        .sidebar:not(.collapsed) .submenu-panel {
            box-shadow: none;
            background: transparent;
            border: none;
            padding: 0 0 0 16px;
        }

        .sidebar:not(.collapsed) .menu-item.open .submenu-container {
            display: block;
        }
    }
</style>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Struktur Inti DIV Sidebar ditata 3 LAPIS VERTIKAL YANG STRICT -->
<div class="sidebar flex flex-col overflow-hidden" id="sidebar" style="height: 100vh;">

    <!-- 1. HEADER LOGO CONTAINER (TIDAK IKUT SCROLL) -->
    <div class="flex-shrink-0 logo-container rail-slot d-flex align-items-center position-relative"
        style="border-bottom: 1px solid rgba(148, 163, 184, 0.15); min-height: 70px; width: 100%; padding-bottom: 12px;">
        <img src="{{ !empty($systemSetting) && $systemSetting->logo ? asset('storage/' . $systemSetting->logo) : asset('images/logo.png') }}"
            alt="Logo" class="rail-slot-logo"
            style="height: 42px; width: auto; object-fit: contain; margin-right: 12px; margin-left: 0;">
        <a class="navbar-brand mb-0 pb-0 border-0 text-start overflow-hidden whitespace-nowrap"
            href="{{ route('dashboard') }}"
            style="font-family: var(--font-heading); font-weight: 700; font-size: 17px; color: #f8fafc; letter-spacing: 0; text-overflow: ellipsis;">
            SIAP BROH!!!
        </a>
        <!-- Ikon Tutup Sidebar (Hanya Mobile) -->
        <button class="close-sidebar d-lg-none ms-auto me-3 d-flex align-items-center justify-content-center"
            onclick="closeSidebar()" style="background: none; border: none; cursor: pointer; z-index: 20;">
            <i class="fas fa-times" style="font-size: 20px; color: #ffffff;"></i>
        </button>
    </div>

    <!-- 2. SCROLLABLE MENU (FLEKSIBEL MEMAKAI SISA RUANG) -->
    <div class="menu-scroll-container pb-2 pt-3 px-2" id="menuScrollContainer">
        @foreach ($menus as $group)
            <h2 class="category-title text-xs font-bold mb-2 tracking-wider text-slate-400 uppercase ms-2"
                style="color: #94a3b8; letter-spacing: 1.5px; font-size: 0.75rem;">{{ $group['category'] }}</h2>

            <!-- Menggunakan display flex col dengan gap konsisten untuk jarak setara seluruh item -->
            <ul class="list-none p-0 mb-0 relative d-flex flex-column" style="gap: 6px;">
                @foreach ($group['items'] as $item)
                    @if (in_array($userRole, $item['roles']))
                        @php
                            $hasSubmenu = isset($item['submenu']);
                        @endphp
                        <li
                            class="menu-item {{ $hasSubmenu ? 'has-submenu' : '' }} {{ $item['active'] ? 'open' : '' }} relative">
                            <a class="relative flex items-center p-2 rounded-lg menu-link {{ $item['active'] ? 'active' : '' }}"
                                href="{{ $hasSubmenu ? '#' : $item['url'] }}"
                                @if (!$hasSubmenu) title="{{ $item['title'] }}" data-tippy="true" @endif
                                @if ($hasSubmenu) onclick="toggleSubmenu(event, this)" @endif>

                                <div class="icon-wrapper">
                                    <i class="{{ $item['icon'] }}"
                                        style="{{ isset($item['iconStyle']) ? $item['iconStyle'] : '' }}"></i>
                                    @if ($hasSubmenu)
                                        <span class="submenu-indicator"></span>
                                    @endif

                                    <!-- BADGE COLLAPSED -->
                                    @if (isset($item['badge']))
                                        <span class="badge {{ $item['badgeClass'] }} badge-collapsed"
                                            style="display: none;">{{ $item['badge'] }}</span>
                                    @endif
                                </div>

                                <span class="menu-text whitespace-nowrap overflow-hidden ms-2"
                                    style="text-overflow: ellipsis;">{{ $item['title'] }}</span>

                                <!-- BADGE EXPANDED -->
                                @if (isset($item['badge']))
                                    <span
                                        class="badge {{ $item['badgeClass'] }} rounded-full px-2 ml-auto badge-expanded">{{ $item['badge'] }}</span>
                                @endif

                                @if ($hasSubmenu)
                                    <i
                                        class="fas fa-chevron-down ml-auto transform transition-transform duration-200 chevron-icon {{ $item['active'] ? 'rotate-180' : '' }}"></i>
                                @endif
                            </a>

                            @if ($hasSubmenu)
                                <div class="submenu-container">
                                    <ul class="submenu-panel list-none w-100 m-0">
                                        @foreach ($item['submenu'] as $sub)
                                            <li class="my-1">
                                                <a class="flex items-center p-2 rounded-lg {{ $sub['active'] ? 'active' : '' }}"
                                                    href="{{ $sub['url'] }}">
                                                    <i class="{{ $sub['icon'] }} mr-2 w-5 text-center"></i>
                                                    <span
                                                        class="submenu-text whitespace-nowrap">{{ $sub['title'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>

            @if (!$loop->last)
                <!-- Garis sekat tipis dan membentang 100% tanpa padding samping sisa -->
                <div style="height: 1px; width: 100%; background: rgba(148,163,184,0.15); margin: 12px 0;"></div>
            @endif
        @endforeach
    </div>

</div>

<script>
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (window.innerWidth < 992) {
            sidebar.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');

            // Sync with FOUC body classes
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);

            if (isCollapsed) {
                document.documentElement.classList.add('sidebar-mode-collapsed');
            } else {
                document.documentElement.classList.remove('sidebar-mode-collapsed');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Init state already handled by FOUC `<head>` blocking script!
        if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
            document.getElementById('sidebar').classList.add('collapsed');
        }
        initFlyoutHoverLogic();
    });

    function toggleSubmenu(e, element) {
        e.preventDefault();

        const sidebar = document.getElementById('sidebar');
        const parentLi = element.closest('.menu-item');
        const chevron = element.querySelector('.chevron-icon');

        // Expand 260px via click ikon pada mode collapsed
        if (window.innerWidth >= 992 && sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
            document.documentElement.classList.remove('sidebar-mode-collapsed');
            localStorage.setItem('sidebarCollapsed', 'false');

            // Clean up left over flyouts
            document.querySelectorAll('.submenu-container.show-flyout').forEach(el => el.classList.remove(
                'show-flyout'));

            // Auto open panel
            parentLi.classList.add('open');
            if (chevron) chevron.classList.add('rotate-180');
            return;
        }

        // Accordion switch (mobile/expanded desktop)
        parentLi.classList.toggle('open');
        if (chevron) {
            chevron.classList.toggle('rotate-180');
        }
    }

    // LOGIC HOVER DELAY (FLYOUT)
    function initFlyoutHoverLogic() {
        let flyoutTimeout;
        const menuItems = document.querySelectorAll('.menu-item.has-submenu');

        menuItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth >= 992 && sidebar.classList.contains('collapsed')) {
                    clearTimeout(flyoutTimeout);

                    // Bersihkan flyout tetangga
                    document.querySelectorAll('.submenu-container.show-flyout').forEach(el => {
                        if (el.closest('.menu-item') !== item) {
                            el.classList.remove('show-flyout');
                        }
                    });

                    const container = this.querySelector('.submenu-container');
                    const rect = this.getBoundingClientRect();

                    // Posisikan Fixed untuk menerobos kotak kliping dari overflow
                    container.style.top = rect.top + 'px';
                    container.classList.add('show-flyout');
                }
            });

            item.addEventListener('mouseleave', function() {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth >= 992 && sidebar.classList.contains('collapsed')) {
                    const container = this.querySelector('.submenu-container');
                    // Timeout delay sebelum menutup saat kursor kabur
                    flyoutTimeout = setTimeout(() => {
                        container.classList.remove('show-flyout');
                    }, 300); // 300ms 
                }
            });
        });
    }
</script>
