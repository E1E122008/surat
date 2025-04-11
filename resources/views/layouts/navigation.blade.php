<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="margin: 0; padding: 1rem 0; width: 100%;">
    <div class="container-fluid">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image mx-auto mb-2">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">SIAP BROH !!!</a>
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
                    <a class="nav-link {{ request()->routeIs('transaksi-surat.*') ? 'active' : '' }}" 
                       href="{{ route('transaksi-surat.index') }}">Transaksi Surat</a>
                </li>
                @if(auth()->user()->role === 'user')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('data-requests.*') ? 'active' : '' }}" 
                       href="{{ route('data-requests.index') }}">
                        Permintaan Data
                        @if(auth()->user()->role == 'admin')
                            @php
                                $pendingCount = \App\Models\DataRequest::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
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
                            <small class="text-muted">{{ Auth::user()->role }}</small>
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
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

<!-- Responsive Navigation Menu -->
<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('data-requests.index')" :active="request()->routeIs('data-requests.*')">
            <i class="fas fa-file-alt mr-2"></i>
            {{ __('Permintaan Data') }}
        </x-responsive-nav-link>
    </div>
</div> 