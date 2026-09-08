<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="margin: 0; padding: 1rem 0; width: 100%;">
    <div class="container-fluid">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image mx-auto mb-2">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">SIAP BROH !!!</a>
        <button class="btn btn-outline-light" onclick="toggleSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
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
                    <a class="nav-link {{ request()->routeIs('transaksi-surat.*') ? 'active' : '' }}"
                        href="{{ route('transaksi-surat.index') }}">Transaksi Surat</a>
                </li>
                @if (auth()->user()->role === 'user')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('data-requests.*') ? 'active' : '' }}"
                            href="{{ route('data-requests.index') }}">
                            Permintaan Data
                            @if (auth()->user()->role == 'admin')
                                @php
                                    $pendingCount = \App\Models\DataRequest::where('status', 'pending')->count();
                                @endphp
                                @if ($pendingCount > 0)
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $pendingCount }}</span>
                                @endif
                            @endif
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="navbar-nav">
            <span class="nav-item text-white me-3 d-flex align-items-center">
                <span class="date-display">{{ now()->format('l, d F Y') }}</span>
                <i class="fas fa-calendar-alt ms-2"></i>
            </span>
        </div>
        <div class="navbar-nav me-4">
            <!-- Notification Dropdown -->
            <div class="nav-item dropdown me-3 d-flex align-items-center">
                <a class="nav-link position-relative py-0" href="#" id="notificationDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fs-5 text-white"></i>
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 0.6rem; margin-top: 5px; margin-left: -5px;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown"
                    style="width: 320px; max-height: 400px; overflow-y: auto;">
                    <li class="dropdown-header">
                        <h6 class="mb-0 fw-bold">Pemberitahuan Sistem</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    @forelse(Auth::user()->unreadNotifications as $notification)
                        <li>
                            <a class="dropdown-item py-2 {{ isset($notification->data['type']) && $notification->data['type'] == 'error' ? 'text-danger' : (isset($notification->data['type']) && $notification->data['type'] == 'success' ? 'text-success' : 'text-primary') }}"
                                href="{{ route('notifications.read', $notification->id) }}"
                                style="white-space: normal;">
                                <div class="d-flex w-100 justify-content-between">
                                    <small><i class="fas fa-circle me-1" style="font-size:8px;"></i>
                                        @if (isset($notification->data['type']) && $notification->data['type'] == 'info')
                                            Permintaan Baru
                                        @else
                                            Pembaruan Status
                                        @endif
                                    </small>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 mt-1" style="font-size: 0.85rem; white-space: pre-wrap;">
                                    {{ $notification->data['message'] ?? 'Ada pemberitahuan baru' }}</p>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    @empty
                        <li><span class="dropdown-item text-center text-muted py-3">Tidak ada notifikasi baru</span>
                        </li>
                    @endforelse

                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <li>
                            <form action="{{ route('notifications.read.all') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-center text-primary fw-bold"
                                    style="font-size: 0.9rem;">Tandai Semua Dibaca</button>
                            </form>
                        </li>
                    @endif

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-secondary"
                            style="font-size: 0.9rem;">
                            Tampilkan Semua Notifikasi
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Profile Dropdown -->
            <div class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2 d-none d-lg-inline text-white profile-name">{{ Auth::user()->name }}</span>
                    <div class="position-relative">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; overflow: hidden;">
                            @if (Auth::user()->avatar && file_exists(public_path('storage/' . Auth::user()->avatar)))
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="text-primary fw-bold d-flex align-items-center justify-content-center w-100 h-100"
                                    style="font-size: 1.25rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li class="dropdown-header d-flex align-items-center">
                        @if (Auth::user()->avatar && file_exists(public_path('storage/' . Auth::user()->avatar)))
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar"
                                class="profile-image">
                        @else
                            <div class="profile-initial">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="ms-2">
                            <strong>{{ Auth::user()->name }}</strong><br>
                            <small class="text-muted">{{ Auth::user()->role }}</small>
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>
                            Profil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
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

<!-- Responsive Navigation Menu -->
<div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        <a :href="route('data-requests.index')" :active="request()->routeIs('data-requests.*')">
            <i class="fas fa-file-alt mr-2"></i>
            {{ __('Permintaan Data') }}
        </a>
    </div>
</div>
