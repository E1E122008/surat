@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-users-cog me-1"></i> Manajemen Pengguna</span>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6" style="max-width: 1400px;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h2 class="header h2 mb-0"><i class="fas fa-users-cog text-primary me-2"></i> <strong>Manajemen Pengguna</strong>
            </h2>
            <div class="d-flex align-items-center gap-2">
                <span class="surat-badge d-inline-flex align-items-center"
                    style="background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%); color: #fff; border-radius: 2rem; padding: 0.4rem 1rem; font-size: 0.88rem; font-weight: 500; white-space: nowrap;">
                    <i class="fas fa-users me-2"></i> Jumlah Pengguna: {{ $totalUsers }}
                </span>
                <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm"
                    style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; padding: 0 1.25rem;">
                    <i class="fas fa-plus me-2"></i> Akun Baru
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg mb-4" style="border-radius: 12px; padding: 1.5rem;">
            <!-- STANDARDIZED ACTION BAR -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-4"
                style="border-bottom: 1px solid rgba(0,0,0,0.05);">

                <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                    style="font-size: 12px; color: #94a3b8; letter-spacing: 1px;">Navigasi Data</span>

                <div class="d-flex flex-column flex-md-row w-100 align-items-stretch align-items-md-center flex-grow-1"
                    style="gap: 12px;">
                    <form method="GET" action="{{ route('users.index') }}"
                        class="d-flex flex-column flex-md-row m-0 flex-grow-1" style="gap: 12px;">

                        <!-- SEARCH -->
                        <div class="position-relative flex-grow-1">
                            <i class="fas fa-search position-absolute text-muted"
                                style="top: 50%; left: 15px; transform: translateY(-50%); font-size: 1rem;"></i>
                            <input type="text" name="search" placeholder="Cari nama atau email..."
                                class="form-control shadow-sm w-100"
                                style="padding-left: 45px; border-radius: 30px; height: 42px; font-size: 0.95rem; font-weight: 500;"
                                value="{{ request('search') }}">
                            @if (request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </div>

                        <!-- FILTER ROLE -->
                        <select name="role" class="form-select shadow-sm" onchange="this.form.submit()"
                            style="border-radius: 30px; height: 42px; font-weight: 500; width: 100%; max-width: 180px;">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="monitor" {{ request('role') == 'monitor' ? 'selected' : '' }}>Monitor</option>
                        </select>
                    </form>

                    @php $sortOrder = request('sort', 'asc'); @endphp
                    <!-- SORT URUTKAN -->
                    <div class="dropdown d-flex justify-content-stretch" style="min-width: 140px;">
                        <button class="btn btn-outline-secondary dropdown-toggle shadow-sm w-100 m-0 text-nowrap"
                            type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Urutkan"
                            style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 500;">
                            <i class="fas fa-sort-amount-{{ $sortOrder == 'desc' ? 'down' : 'up' }} me-2"></i> Urutkan
                        </button>
                        <ul class="dropdown-menu shadow" aria-labelledby="sortDropdown">
                            <li>
                                <a class="dropdown-item {{ $sortOrder == 'desc' ? 'active bg-primary text-white' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}">Terbaru ke Terlama</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sortOrder == 'asc' ? 'active bg-primary text-white' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}">Terlama ke Terbaru</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden border">
                <div class="max-h-96 overflow-y-auto">
                    <div class="overflow-x-auto">
                        <table class="table min-w-full divide-y divide-gray-200 m-0">
                            <thead class="bg-gray-50" style="background-color: #0f1b3d; color: white;">
                                <tr>
                                    <th translate="no"
                                        class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider"
                                        style="border: none !important;">
                                        Nama</th>
                                    <th translate="no"
                                        class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider"
                                        style="border: none !important;">
                                        Email</th>
                                    <th translate="no"
                                        class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider"
                                        style="border: none !important;">
                                        Peran</th>
                                    <th translate="no"
                                        class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider"
                                        style="border: none !important;">
                                        Tanggal Dibuat</th>
                                    <th translate="no"
                                        class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider"
                                        style="border: none !important;">
                                        Dinas</th>
                                    <th translate="no"
                                        class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider"
                                        style="border: none !important;">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if ($user->role == 'admin') bg-purple-100 text-purple-800
                                        @elseif($user->role == 'monitor') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->dinas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                            @if (auth()->user()->role === 'superadmin' || (auth()->user()->role === 'admin' && $user->role !== 'superadmin'))
                                                <button type="button" class="text-orange-600 hover:text-orange-900 mr-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#resetPasswordModal{{ $user->id }}">
                                                    Reset Password
                                                </button>

                                                <!-- Modal Reset Password -->
                                                <div class="modal fade text-start"
                                                    id="resetPasswordModal{{ $user->id }}" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content"
                                                            style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h5 class="modal-title fw-bold text-dark">Reset Password
                                                                    Akun</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body pt-3">
                                                                <p class="text-muted small mb-4"
                                                                    style="text-transform: none; white-space: normal;">Anda
                                                                    akan me-reset sandi untuk akun
                                                                    <strong>{{ $user->name }}</strong>
                                                                    ({{ $user->email }})
                                                                    .
                                                                </p>
                                                                <form
                                                                    action="{{ route('users.resetPassword', $user->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="mb-4">
                                                                        <label
                                                                            class="form-label fw-bold small text-dark">Password
                                                                            Baru</label>
                                                                        <input type="text" name="password"
                                                                            id="newPass{{ $user->id }}"
                                                                            class="form-control mb-2 w-100" required
                                                                            minlength="8"
                                                                            placeholder="Ketik manual atau buat otomatis"
                                                                            autocomplete="off"
                                                                            style="height: 48px; border-radius: 8px; border: 1px solid #d1d5db; box-shadow: none;">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mt-2">
                                                                            <small class="text-muted"
                                                                                style="font-size: 0.75rem;">Minimal 8
                                                                                karakter.</small>
                                                                            <button type="button"
                                                                                class="text-primary text-decoration-none fw-bold border-0 bg-transparent p-0"
                                                                                style="font-size: 0.85rem;"
                                                                                onclick="generatePassword('newPass{{ $user->id }}')">
                                                                                <i class="fas fa-magic me-1"></i> Buat
                                                                                Sandi
                                                                                Otomatis
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                                                        <button type="button"
                                                                            class="btn btn-light rounded-pill px-4 fw-medium"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary rounded-pill px-4 fw-medium"
                                                                            onclick="return confirm('Anda yakin ingin mereset password pengguna ini?');">Simpan
                                                                            Password Baru</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (auth()->user()->role === 'superadmin')
                                                <a href="{{ route('users.edit', $user) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                    class="inline" onsubmit="return confirmDeleteUser(this);">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4 mb-2 d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>

            <style>
                .pagination .page-item:first-child,
                .pagination .page-item:last-child {
                    display: none;
                }

                @media (min-width: 768px) {

                    .pagination .page-item:first-child,
                    .pagination .page-item:last-child {
                        display: block;
                    }
                }
            </style>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDeleteUser(form) {
                Swal.fire({
                    title: 'Yakin ingin menghapus user ini?',
                    text: "User yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                return false;
            }
            // Auto close success alert after 3 seconds
            window.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    setTimeout(() => {
                        alert.classList.remove('show');
                        alert.classList.add('hide');
                        setTimeout(() => alert.remove(), 500);
                    }, 3000);
                }
            });

            function generatePassword(inputId) {
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                let password = "";
                // Memastikan kombinasi kuat
                password += "ABCDEFGHIJKLMNOPQRSTUVWXYZ" [Math.floor(Math.random() * 26)];
                password += "abcdefghijklmnopqrstuvwxyz" [Math.floor(Math.random() * 26)];
                password += "0123456789" [Math.floor(Math.random() * 10)];
                for (let i = 0, n = charset.length; i < 9; ++i) {
                    password += charset.charAt(Math.floor(Math.random() * n));
                }
                // Shuffle array (Fischer-Yates simple)
                password = password.split('').sort(function() {
                    return 0.5 - Math.random()
                }).join('');
                document.getElementById(inputId).value = password;
            }

            function copyPassword(inputId) {
                const copyText = document.getElementById(inputId);
                if (copyText.value === "") {
                    return;
                }
                copyText.select();
                copyText.setSelectionRange(0, 99999); /* For mobile devices */
                navigator.clipboard.writeText(copyText.value).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Kata sandi disalin ke clipboard!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            }
        </script>
    @endpush

    <style>
        .alert-success {
            background: linear-gradient(90deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #34d399;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(52, 211, 153, 0.08);
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            transition: opacity 0.5s, transform 0.5s;
        }

        .alert-success.hide {
            opacity: 0;
            transform: translateY(-10px);
        }
    </style>
