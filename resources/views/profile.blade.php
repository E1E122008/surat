@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Kartu Profil Utama -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="position-relative mb-4 mx-auto" style="width: 150px;">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 class="rounded-circle img-thumbnail"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                data-bs-toggle="modal" 
                                data-bs-target="#avatarModal"
                                style="width: 35px; height: 35px;">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    
                    <div class="online-status mb-2">
                        <span class="badge bg-success"><i class="fas fa-circle me-1"></i> Online</span>
                    </div>

                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->jabatan }}</p>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </button>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i>Ganti Password
                        </button>
                        
                        <!-- Tombol Kembali ke Dashboard -->
                        <a href="{{ route('dashboard') }}" class="btn btn-info">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                        
                        <!-- Tombol Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kartu Aktivitas Terbaru -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item pb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="timeline-icon bg-light rounded-circle p-2">
                                        <i class="fas fa-file-alt text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 fw-bold">{{ $activity->description }}</p>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Detail dan Pengaturan -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Nama Lengkap</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ auth()->user()->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Email</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Nomor Telepon</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ auth()->user()->phone ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Jabatan</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ auth()->user()->jabatan }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Bergabung Sejak</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ auth()->user()->created_at->format('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Login -->
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Riwayat Login</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>IP Address</th>
                                    <th>Perangkat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loginHistory as $login)
                                <tr>
                                    <td>{{ $login->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $login->ip_address }}</td>
                                    <td>{{ $login->user_agent }}</td>
                                    <td>
                                        <span class="badge bg-success">Berhasil</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        <label class="form-label">Jabatan</label>
                        <select class="form-select" name="jabatan">
                            <option value="">Pilih Jabatan</option>
                            <option value="Admin" {{ auth()->user()->jabatan == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Staff" {{ auth()->user()->jabatan == 'Staff' ? 'selected' : '' }}>Staff</option>
                            
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
