@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-user-circle me-1"></i> Informasi Pribadi</span>
@endsection

@section('content')
    <style>
        .profile-page {
            background-color: #f3f4f6;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .avatar-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .camera-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 35px;
            height: 35px;
            background: var(--navy-utama);
            border: none;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .camera-btn:hover {
            background: #172a5a;
            transform: scale(1.1);
        }

        .online-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .info-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-header h5 {
            margin: 0;
            color: var(--navy-utama);
            font-weight: 600;
        }

        .info-body {
            padding: 1.5rem;
        }

        .info-item {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            width: 150px;
        }

        .info-value {
            color: #6b7280;
            flex: 1;
        }

        .action-btn {
            width: 100%;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 0.75rem;
        }

        .action-btn i {
            margin-right: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: var(--navy-utama);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header .btn-close {
            color: white;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--navy-utama);
            box-shadow: 0 0 0 0.2rem rgba(15, 27, 61, 0.25);
        }
    </style>

    <div class="profile-page">
        <div class="container">
            <div class="row">
                <!-- Kartu Profil Utama -->
                <div class="col-lg-4">
                    <div class="profile-card p-4">
                        <div class="avatar-container mb-4">
                            @if (auth()->user()->avatar && file_exists(public_path('storage/' . auth()->user()->avatar)))
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                    style="background-color: var(--navy-utama); width: 100%; height: 100%; font-size: 5rem; text-transform: uppercase;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <button class="camera-btn" data-bs-toggle="modal" data-bs-target="#avatarModal">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>

                        <div class="text-center mb-4">
                            <span class="online-badge">
                                <i class="fas fa-circle me-1"></i> Online
                            </span>
                        </div>

                        <div class="text-center mb-4">
                            <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                            <p class="text-muted">{{ auth()->user()->role }}</p>
                            <!-- Action Buttons Stack -->
                            <div class="d-flex flex-column mt-4" style="gap: 10px;">
                                <!-- Edit Profil -->
                                <button class="btn w-100 d-flex justify-content-center align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#editProfileModal"
                                    style="background-color: #eab308; border: none; color: white; height: 44px; border-radius: 9px; font-weight: 600; font-size: 13.5px;">
                                    <i class="fas fa-pencil-alt me-2"></i>Edit Profil
                                </button>

                                <!-- Ganti Password -->
                                <button class="btn w-100 d-flex justify-content-center align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#changePasswordModal"
                                    style="background-color: white; border: 1px solid #e2e8f0; color: #0f1b3d; height: 44px; border-radius: 9px; font-weight: 600; font-size: 13.5px;">
                                    <i class="fas fa-lock me-2"></i>Ganti Kata Sandi
                                </button>

                                <!-- Kembali ke Beranda -->
                                <a href="{{ route('dashboard') }}"
                                    class="btn w-100 d-flex justify-content-center align-items-center"
                                    style="background-color: white; border: 1px solid #e2e8f0; color: #0f1b3d; height: 44px; border-radius: 9px; font-weight: 600; font-size: 13.5px;">
                                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                                </a>

                                <hr style="margin: 4px 0; border: none; border-top: 1px solid #f1f5f9;">

                                <!-- Keluar -->
                                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                    @csrf
                                    <button type="submit"
                                        class="btn w-100 d-flex justify-content-center align-items-center"
                                        style="background-color: white; border: 1px solid #fecaca; color: #dc2626; height: 44px; border-radius: 9px; font-weight: 600; font-size: 13.5px;">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Detail -->
                <div class="col-lg-8">
                    <div class="profile-info-card">
                        <div class="info-header">
                            <h5><i class="fas fa-user me-2"></i>Informasi Pribadi</h5>
                        </div>
                        <div class="info-body">
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-id-card me-2"></i>Nama
                                    Lengkap</div>
                                <div class="col-8">{{ auth()->user()->name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-envelope me-2"></i>Email
                                </div>
                                <div class="col-8">{{ auth()->user()->email }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-phone me-2"></i>Nomor
                                    Telepon</div>
                                <div class="col-8">{{ auth()->user()->phone ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i
                                        class="fas fa-briefcase me-2"></i>Jabatan</div>
                                <div class="col-8">{{ auth()->user()->jabatan ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i
                                        class="fas fa-building me-2"></i>Dinas/Instansi</div>
                                <div class="col-8">{{ auth()->user()->dinas ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-id-badge me-2"></i>NIP
                                </div>
                                <div class="col-8">{{ auth()->user()->nip ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-user-tag me-2"></i>Role
                                </div>
                                <div class="col-8">{{ auth()->user()->role }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-end text-muted fw-semibold"><i
                                        class="fas fa-calendar-alt me-2"></i>Bergabung Sejak</div>
                                <div class="col-8">{{ auth()->user()->created_at->format('d F Y') }}</div>
                            </div>
                        </div>

                        {{-- ===== Kartu Aktivitas Terbaru ===== --}}
                        <div class="profile-info-card mt-4">
                            <div class="info-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
                                <a href="{{ route('profile.activity') }}" class="text-decoration-none fw-semibold"
                                    style="color: #eab308; font-size: 13.5px;">Lihat semua &rarr;</a>
                            </div>
                            <div class="info-body py-0 px-3">
                                @forelse($recentActivities as $log)
                                    @php
                                        $icon = 'info-circle';
                                        $iconColor = '#64748b';
                                        $iconBg = '#f1f5f9';
                                        if (
                                            str_contains($log->action, 'login') ||
                                            str_contains($log->action, 'logout')
                                        ) {
                                            $icon = 'sign-in-alt';
                                            $iconColor = '#2563eb';
                                            $iconBg = '#eff6ff';
                                        } elseif (
                                            str_contains($log->action, 'create') ||
                                            str_contains($log->action, 'accept')
                                        ) {
                                            $icon = 'plus-circle';
                                            $iconColor = '#16a34a';
                                            $iconBg = '#f0fdf4';
                                        } elseif (
                                            str_contains($log->action, 'update') ||
                                            str_contains($log->action, 'change') ||
                                            str_contains($log->action, 'reset')
                                        ) {
                                            $icon = 'edit';
                                            $iconColor = '#d97706';
                                            $iconBg = '#fffbeb';
                                        } elseif (
                                            str_contains($log->action, 'delete') ||
                                            str_contains($log->action, 'reject')
                                        ) {
                                            $icon = 'trash-alt';
                                            $iconColor = '#dc2626';
                                            $iconBg = '#fef2f2';
                                        } elseif (str_contains($log->action, 'export')) {
                                            $icon = 'download';
                                            $iconColor = '#7c3aed';
                                            $iconBg = '#f5f3ff';
                                        }
                                    @endphp
                                    <div class="d-flex align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                                        style="border-color: #f1f5f9 !important;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                            style="width: 36px; height: 36px; background: {{ $iconBg }};">
                                            <i class="fas fa-{{ $icon }}"
                                                style="color: {{ $iconColor }}; font-size: 14px;"></i>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark"
                                                style="font-size: 13.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $log->description ?? '-' }}
                                            </div>
                                            <div class="text-muted" style="font-size: 11.5px;">
                                                {{ $log->created_at->diffForHumans() }}</div>
                                        </div>
                                        <div class="ms-3 text-muted text-end flex-shrink-0"
                                            style="font-size: 11px; line-height: 1.4;">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <span style="color: #94a3b8;">{{ $log->created_at->format('H:i') }} WIB</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-history mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                        <p class="mb-0 small">Belum ada aktivitas tercatat.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Profil -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ auth()->user()->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ auth()->user()->phone }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan"
                                    value="{{ auth()->user()->jabatan }}">
                            </div>
                            @if (auth()->user()->role === 'admin')
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select class="form-select" name="role">
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ auth()->user()->role == 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="user" {{ auth()->user()->role == 'user' ? 'selected' : '' }}>User
                                        </option>
                                        <option value="monitor" {{ auth()->user()->role == 'monitor' ? 'selected' : '' }}>
                                            Monitor</option>
                                    </select>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" class="form-control" name="nip"
                                    value="{{ auth()->user()->nip }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Ganti Password -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ganti Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Password Lama</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control pe-5" id="current_password"
                                        name="current_password" required>
                                    <i class="fas fa-eye-slash" onclick="togglePassword('current_password', this)"
                                        style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; transition: color 0.3s;"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control pe-5" id="password" name="password"
                                        required>
                                    <i class="fas fa-eye-slash" onclick="togglePassword('password', this)"
                                        style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; transition: color 0.3s;"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control pe-5" id="password_confirmation"
                                        name="password_confirmation" required>
                                    <i class="fas fa-eye-slash" onclick="togglePassword('password_confirmation', this)"
                                        style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; transition: color 0.3s;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Upload Avatar -->
        <div class="modal fade" id="avatarModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Foto Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Pilih Foto</label>
                                <input type="file" class="form-control" name="avatar" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .timeline-icon {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .timeline-item {
                position: relative;
            }

            .timeline-item:not(:last-child)::after {
                content: '';
                position: absolute;
                left: 20px;
                top: 40px;
                bottom: 0;
                width: 1px;
                background: #dee2e6;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function togglePassword(inputId, iconElement) {
                const input = document.getElementById(inputId);
                if (input.type === "password") {
                    input.type = "text";
                    iconElement.classList.remove('fa-eye-slash');
                    iconElement.classList.add('fa-eye');
                    iconElement.style.color = '#3b82f6';
                } else {
                    input.type = "password";
                    iconElement.classList.remove('fa-eye');
                    iconElement.classList.add('fa-eye-slash');
                    iconElement.style.color = '#94a3b8';
                }
            }
        </script>
    @endpush
