@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i>
    <span style="color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"></line>
            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
        Surat Keluar
    </span>
@endsection

@section('content')
    <style>
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
    </style>
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2
                style="display:flex; align-items:center; gap:10px; font-size:1.5rem; font-weight:700; color:#0d1b4b; margin:0;">
                <div style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="flex-shrink:0;">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>
                <strong>Surat Keluar</strong>
            </h2>
        </div>
        <div class="bg-white shadow-sm rounded-lg mb-4" style="border-radius: 12px;">
            <div class="p-4">
                <!-- Alert Section -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}

                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}

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

                <!-- STANDARDIZED ACTION BAR -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-4"
                    style="border-bottom: 1px solid rgba(0,0,0,0.05);">

                    <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                        style="font-size: 12px; color: #94a3b8; letter-spacing: 1px;">Manajemen Surat Keluar</span>

                    <div class="d-flex flex-column flex-md-row w-100 align-items-stretch align-items-md-center flex-grow-1"
                        style="gap: 12px;">
                        <form method="GET" action="{{ route('surat-keluar.index') }}"
                            class="d-flex flex-column flex-md-row m-0 flex-grow-1" style="gap: 12px;">
                            <!-- SEARCH -->
                            <div class="position-relative flex-grow-1">
                                <button type="submit" class="position-absolute"
                                    style="top: 50%; left: 15px; transform: translateY(-50%); background: none; border: none; padding: 0;">
                                    <i class="fas fa-search text-muted" style="font-size: 1rem; cursor: pointer;"></i>
                                </button>
                                <input type="text" name="search" placeholder="Cari Surat Keluar..."
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
                                <a href="{{ route('surat-keluar.create') }}"
                                    class="btn btn-primary shadow-sm flex-fill text-nowrap"
                                    style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <i class="fas fa-plus me-2"></i> Tambah Surat Keluar
                                </a>
                                <a href="{{ route('surat-keluar.export') }}"
                                    class="btn btn-success shadow-sm flex-fill text-nowrap"
                                    style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <i class="fas fa-file-excel me-2"></i> Ekspor
                                </a>
                            </div>
                        @endif
                    </div>


                </div>

                <div class="table-responsive" style="max-width: 1200px; margin: auto;">
                    <table class="table" id="suratTable">
                        <thead>
                            <tr>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">No
                                </th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">No
                                    Surat</th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">
                                    Tanggal</th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">
                                    Perihal</th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suratKeluar as $index => $surat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        {{ $index + 1 + ($suratKeluar->currentPage() - 1) * $suratKeluar->perPage() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        {{ $surat->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="perihal-cell" title="{{ $surat->perihal }}">
                                            {{ $surat->perihal }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Aksi
                                            </button>
                                            <ul class="dropdown-menu lampiran-dropdown">
                                                @php
                                                    $lampiran = is_array($surat->lampiran)
                                                        ? $surat->lampiran
                                                        : json_decode($surat->lampiran, true);
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
                                                        <a class="dropdown-item"
                                                            href="{{ route('surat-keluar.edit', $surat->id) }}">
                                                            <i class="fas fa-edit fa-fw me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDelete({{ $surat->id }}, this)"
                                                            data-no-surat="{{ $surat->no_surat }}"
                                                            data-perihal="{{ e($surat->perihal) }}">
                                                            <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $surat->id }}"
                                            action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data surat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $suratKeluar->links('pagination::bootstrap-4') }}
                </div>
                <div class="mt-3 mb-2 d-flex justify-content-center">
                    <span class="surat-badge surat-badge-sm d-inline-flex">
                        <i class="fas fa-paper-plane me-2"></i> Jumlah Surat Keluar:
                        {{ method_exists($suratKeluar, 'total') ? $suratKeluar->total() : $suratKeluar->count() }}
                    </span>
                </div>


            </div>
        </div>
    </div>

    <style>
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

        body {
            background-color: #f3f4f6 !important;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1400px !important;
            margin: auto;
            padding: 20px;
            background-color: #f3f4f6;
        }

        .table-responsive {
            background-color: white;
            border: none;
            margin: 0;
            padding: 0;
        }

        .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }

        /* Table styling */
        .table {
            border: none !important;
            margin-bottom: 0 !important;
        }

        .table thead tr {
            background-color: #4a69bd !important;
            color: white;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table th {
            border: none !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            padding: 12px;
            color: white;
        }

        .table td {
            border: none !important;
            padding: 12px;
            font-size: 14px;
            font-weight: normal;
            color: #333;
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

        /* DataTables styling */
        .dataTables_wrapper {
            margin-top: 1rem;
        }

        .dataTables_info {
            font-size: 0.875rem;
            color: #6b7280;
            padding: 0.5rem 0;
        }

        .dataTables_paginate {
            padding: 0.5rem 0;
        }

        .dataTables_paginate .paginate_button {
            padding: 0.3rem 0.6rem;
            margin: 0 0.2rem;
            border: none;
            background: #f3f4f6;
            color: #374151;
            border-radius: 0.25rem;
        }

        .dataTables_paginate .paginate_button.current {
            background: #4a69bd;
            color: white;
        }

        .btn-info {
            background-color: #4a69bd;
            color: white;
            border: none;
        }

        .btn-info:hover {
            background-color: #3c5aa8;
        }

        .btn-sm {
            padding: 0.5rem 0.75rem !important;
            font-size: 1rem !important;
        }

        .btn-info,
        .btn-danger {
            margin: 0 0.25rem;
        }

        .fas {
            font-size: 1rem;
        }

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

        /* Adjust the action buttons container */
        .flex.justify-center.items-center {
            gap: 0.5rem;
        }
    </style>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    new bootstrap.Alert(alert).close();
                }, 5000); // 5 detik
            });
        });

        // Inisialisasi DataTables
        $(document).ready(function() {
            // This script block is no longer needed as DataTables is removed.
            // Keeping it for now as it might be used elsewhere or for future reference.
        });

        function confirmDelete(id, el) {
            const noSurat = el ? (el.dataset.noSurat || '-') : '-';
            const perihal = el ? (el.dataset.perihal || '-') : '-';

            Swal.fire({
                title: '<strong style="color:#ef4444;">Hapus Surat Ini?</strong>',
                icon: 'warning',
                html: `
                    <div style="text-align:left; font-size:0.88rem; line-height:2;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="color:#64748b; width:38%; padding:2px 0;">No. Surat</td>
                                <td style="font-weight:600;">${noSurat}</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b; padding:2px 0;">Perihal</td>
                                <td style="font-weight:600;">${perihal}</td>
                            </tr>
                        </table>
                        <p style="margin-top:10px; color:#ef4444; font-size:0.82rem; font-weight:500;">
                            âš ï¸ Data ini akan dihapus secara permanen!
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

        // Custom filtering has been replaced with server side filtering
    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
@endsection
