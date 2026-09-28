@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-file-alt me-1"></i> SK Kepala Biro</span>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><i class="fas fa-file-signature text-primary me-2"></i> <strong>Surat Keputusan Kepala
                    Biro</strong></h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <style>
            @media (min-width: 768px) {
                .fixed-action-width {
                    width: 160px !important;
                    flex: 0 0 160px !important;
                }

                .fit-content-action {
                    width: auto !important;
                    flex: 0 0 auto !important;
                }
            }

            .table-cell-truncate {
                max-width: 200px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .perihal-cell {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: normal;
                max-width: 280px;
                margin: 0 auto;
            }

            @media (min-width: 1024px) {
                .perihal-cell {
                    max-width: 320px;
                }
            }

            .lampiran-dropdown {
                max-width: 280px;
            }

            .lampiran-name-truncate {
                display: inline-block;
                max-width: 180px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
            }

            .sk-table-wrapper {
                box-shadow: inset -10px 0 10px -10px rgba(0, 0, 0, 0.1);
            }

            /* Remove table borders */
            .table {
                border: none !important;
                margin-bottom: 0 !important;
            }

            .table thead tr {
                background-color: #0f1b3d !important;
                color: white;
            }

            .table th {
                border: none !important;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.875rem;
            }

            .table td {
                border: none !important;
                padding: 0.75rem;
            }

            .table tbody tr {
                border-bottom: 1px solid #f3f4f6;
            }

            .table tbody tr:last-child {
                border-bottom: 2px solid #000;
            }

            /* Hover effect */
            .table tbody tr:hover {
                background-color: #f9fafb;
            }

            /* Header styling */
            .table thead th {
                background-color: #0f1b3d !important;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: white !important;
            }

            .custom-search-input {
                border: 1px solid #cbd5e1 !important;
                background-color: #fff !important;
                transition: all 0.2s ease;
            }

            .custom-search-input:focus {
                border-color: #2563eb !important;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
                outline: none;
            }
        </style>

        <div class="bg-white shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="p-4">
                <!-- STANDARDIZED ACTION BAR -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-4"
                    style="border-bottom: 1px solid rgba(0,0,0,0.05);">

                    <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                        style="font-size: 12px; color: #94a3b8; letter-spacing: 1px;">Manajemen SK</span>

                    <div class="d-flex flex-column flex-md-row w-100 align-items-stretch align-items-md-center flex-grow-1"
                        style="gap: 12px;">
                        <form method="GET" action="{{ route('sk-karo.index') }}"
                            class="d-flex flex-column flex-md-row m-0 flex-grow-1" style="gap: 12px;">
                            <!-- SEARCH -->
                            <div class="position-relative flex-grow-1">
                                <button type="submit" class="position-absolute border-0 bg-transparent text-muted"
                                    style="top: 50%; left: 15px; transform: translateY(-50%); z-index: 4;">
                                    <i class="fas fa-search" style="font-size: 1rem;"></i>
                                </button>
                                <input type="text" name="search" placeholder="Cari SK KARO..."
                                    class="form-control shadow-sm w-100 custom-search-input"
                                    style="padding-left: 45px; border-radius: 30px; height: 42px; font-size: 0.95rem; font-weight: 500;"
                                    value="{{ request('search') }}">
                                @if (request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                            </div>
                        </form>

                        @php $sortOrder = request('sort', 'asc'); @endphp
                        <!-- SORT URUTKAN -->
                        <div class="dropdown d-flex justify-content-stretch fixed-action-width">
                            <button class="btn btn-outline-secondary dropdown-toggle shadow-sm w-100 m-0 text-nowrap"
                                type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                title="Urutkan"
                                style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 500;">
                                <i class="fas fa-sort-amount-{{ $sortOrder == 'desc' ? 'down' : 'up' }} me-2"></i>
                                Urutkan
                            </button>
                            <ul class="dropdown-menu shadow" aria-labelledby="sortDropdown">
                                <li>
                                    <a class="dropdown-item {{ $sortOrder == 'desc' ? 'active bg-primary text-white' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}">
                                        Terbaru ke Terlama
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ $sortOrder == 'asc' ? 'active bg-primary text-white' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}">
                                        Terlama ke Terbaru
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- ACTION BUTTONS -->
                        @if (auth()->user()->role !== 'monitor')
                            <div class="d-flex flex-column flex-sm-row justify-content-stretch" style="gap: 12px;">
                                <a href="{{ route('sk-karo.create') }}"
                                    class="btn btn-primary shadow-sm flex-fill text-nowrap"
                                    style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <i class="fas fa-plus me-2"></i> Tambah SK
                                </a>
                                <a href="{{ route('sk-karo.export') }}"
                                    class="btn btn-success shadow-sm flex-fill text-nowrap"
                                    style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <i class="fas fa-file-excel me-2"></i> Ekspor
                                </a>
                            </div>
                        @endif
                    </div>


                </div>

                <div class="table-responsive w-100" style="margin: auto;">
                    <table class="table w-100" id="skKaroTable">
                        <thead>
                            <tr>
                                <th translate="no"
                                    class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">No
                                </th>
                                <th translate="no"
                                    class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">No
                                    SK
                                </th>
                                <th translate="no"
                                    class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th translate="no"
                                    class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">
                                    Perihal
                                </th>
                                <th translate="no"
                                    class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">
                                    Pejabat
                                    TTD</th>
                                <th translate="no"
                                    class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sk_karos as $index => $sk)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        {{ $index + 1 + ($sk_karos->currentPage() - 1) * $sk_karos->perPage() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        {{ $sk->no_sk }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        {{ \Carbon\Carbon::parse($sk->tanggal_sk)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-center">
                                        <div class="perihal-cell" title="{{ $sk->perihal }}">
                                            {{ $sk->perihal }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        {{ $sk->pejabat_ttd }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                style="border-radius: 8px;">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow lampiran-dropdown">
                                                @php
                                                    $lampiran = $sk->file_surat
                                                        ? json_decode($sk->file_surat, true)
                                                        : null;
                                                @endphp
                                                @if ($lampiran && count($lampiran))
                                                    @if (count($lampiran) == 1)
                                                        @php
                                                            $file = is_string($lampiran[0])
                                                                ? [
                                                                    'path' => $lampiran[0],
                                                                    'name' => basename($lampiran[0]),
                                                                ]
                                                                : $lampiran[0];
                                                        @endphp
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center"
                                                                href="{{ asset('storage/' . $file['path']) }}"
                                                                target="_blank" title="{{ $file['name'] }}">
                                                                <i class="fas fa-eye fa-fw me-2 text-primary"></i>
                                                                <span class="lampiran-name-truncate">Lihat Lampiran</span>
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <h6 class="dropdown-header">Lampiran ({{ count($lampiran) }})
                                                            </h6>
                                                        </li>
                                                        @foreach ($lampiran as $file)
                                                            @php
                                                                if (is_string($file)) {
                                                                    $file = [
                                                                        'path' => $file,
                                                                        'name' => basename($file),
                                                                    ];
                                                                }
                                                            @endphp
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                                                    href="{{ asset('storage/' . $file['path']) }}"
                                                                    target="_blank" title="{{ $file['name'] }}">
                                                                    <div class="d-flex align-items-center flex-grow-1">
                                                                        <i
                                                                            class="fas fa-file fa-fw me-2 text-primary flex-shrink-0"></i>
                                                                        <span
                                                                            class="lampiran-name-truncate">{{ $file['name'] }}</span>
                                                                    </div>
                                                                    <i class="fas fa-download fa-fw ms-2 text-secondary flex-shrink-0"
                                                                        style="font-size: 0.8rem;"></i>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <li><span class="dropdown-item-text text-muted">Tidak ada
                                                            lampiran</span></li>
                                                @endif

                                                @if (auth()->user()->role !== 'monitor')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('sk-karo.edit', $sk->id) }}">
                                                            <i class="fas fa-edit fa-fw me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDelete({{ $sk->id }}, this)"
                                                            data-no-sk="{{ $sk->no_sk }}"
                                                            data-perihal="{{ e($sk->perihal) }}"
                                                            data-pejabat="{{ e($sk->pejabat_ttd) }}">
                                                            <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $sk->id }}"
                                            action="{{ route('sk-karo.destroy', $sk->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr style="border-bottom: none !important;">
                                    <td colspan="6" class="text-center py-5" style="border: none !important;">
                                        <div class="mb-3">
                                            <i class="fas fa-folder-open text-muted"
                                                style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Belum ada data SK KARO</h6>
                                        <p class="text-muted small">Silakan tambah SK baru atau ubah kata kunci pencarian.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($sk_karos->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $sk_karos->links('pagination::bootstrap-4') }}
                    </div>
                @endif
                <div class="mt-3 mb-2 d-flex justify-content-center">
                    <span class="surat-badge surat-badge-sm d-inline-flex">
                        <i class="fas fa-file-contract me-2"></i> Jumlah SK:
                        {{ method_exists($sk_karos, 'total') ? $sk_karos->total() : $sk_karos->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <style>
        .surat-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%);
            color: #fff;
            font-weight: 500;
            border-radius: 2rem;
            padding: 0.3rem 1rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(91, 126, 241, 0.08);
            gap: 0.5rem;
        }

        .surat-badge-sm {
            font-size: 0.95rem;
            padding: 0.2rem 0.8rem;
        }

        .surat-badge i {
            font-size: 1em;
            margin-right: 0.5rem;
        }
</style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    new bootstrap.Alert(alert).close();
                }, 5000);
            });
        });

        function confirmDelete(id, el) {
            const noSk = el ? (el.dataset.noSk || '-') : '-';
            const perihal = el ? (el.dataset.perihal || '-') : '-';
            const pejabat = el ? (el.dataset.pejabat || '-') : '-';

            Swal.fire({
                title: '<strong style="color:#ef4444;">Hapus SK Ini?</strong>',
                icon: 'warning',
                html: `
                    <div style="text-align:left; font-size:0.88rem; line-height:2;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="color:#64748b; width:38%; padding:2px 0;">No. SK</td>
                                <td style="font-weight:600;">${noSk}</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b; padding:2px 0;">Perihal</td>
                                <td style="font-weight:600;">${perihal}</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b; padding:2px 0;">Pejabat TTD</td>
                                <td style="font-weight:600;">${pejabat}</td>
                            </tr>
                        </table>
                        <p style="margin-top:10px; color:#ef4444; font-size:0.82rem; font-weight:500;">
                            Ã¢Å¡Â Ã¯Â¸Â Data ini akan dihapus secara permanen!
                        </p>
                    </div>`,
                showCancelButton: true,
                confirmButtonColor: '#FF4757',
                cancelButtonColor: '#747D8C',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#FFFFFF',
                backdrop: 'rgba(0,0,0,0.4)',
                padding: '2em'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
@endsection
