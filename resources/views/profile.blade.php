@extends('layouts.app')

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
        background: #4a69bd;
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
        background: #3d5aa1;
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
        color: #4a69bd;
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
        background: #4a69bd;
        color: white;
        border-radius: 15px 15px 0 0;
    }
    
    .modal-header .btn-close {
        color: white;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4a69bd;
        box-shadow: 0 0 0 0.2rem rgba(74, 105, 189, 0.25);
    }
</style>

<div class="profile-page">
    <div class="container">
        <div class="row">
            <!-- Kartu Profil Utama -->
            <div class="col-lg-4">
                <div class="profile-card p-4">
                    <div class="avatar-container mb-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                 class="rounded-circle">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 class="rounded-circle">
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
                        <!-- Dropdown Action -->
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle w-100" type="button" id="profileActionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-2"></i> Aksi
                            </button>
                            <ul class="dropdown-menu w-100 text-center" aria-labelledby="profileActionDropdown">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                        <i class="fas fa-edit me-2"></i>Edit Profil
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="fas fa-key me-2"></i>Ganti Password
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-info w-100 mt-2">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
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
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-id-card me-2"></i>Nama Lengkap</div>
                            <div class="col-8">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-envelope me-2"></i>Email</div>
                            <div class="col-8">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-phone me-2"></i>Nomor Telepon</div>
                            <div class="col-8">{{ auth()->user()->phone ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-briefcase me-2"></i>Jabatan</div>
                            <div class="col-8">{{ auth()->user()->jabatan ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-building me-2"></i>Dinas/Instansi</div>
                            <div class="col-8">{{ auth()->user()->dinas ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-id-badge me-2"></i>NIP</div>
                            <div class="col-8">{{ auth()->user()->nip ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-user-tag me-2"></i>Role</div>
                            <div class="col-8">{{ auth()->user()->role }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-end text-muted fw-semibold"><i class="fas fa-calendar-alt me-2"></i>Bergabung Sejak</div>
                            <div class="col-8">{{ auth()->user()->created_at->format('d F Y') }}</div>
                        </div>
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
                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                            <option value="">Pilih Role</option>
                            <option value="Admin" {{ auth()->user()->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="User" {{ auth()->user()->role == 'User' ? 'selected' : '' }}>User</option>
                            
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" value="{{ auth()->user()->nip }}">
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
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
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
