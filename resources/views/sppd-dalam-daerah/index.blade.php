@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-route me-1"></i> SPPD Dalam Daerah</span>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><i class="fas fa-route text-primary me-2"></i> <strong>Surat Perintah Perjalanan Dinas -
                    Dalam Daerah</strong></h2>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4">
                <!-- STANDARDIZED ACTION BAR -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-4"
                    style="border-bottom: 1px solid rgba(0,0,0,0.05);">

                    <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                        style="font-size: 12px; color: #94a3b8; letter-spacing: 1px;">Manajemen SPPD</span>

                    <div class="d-flex flex-column flex-md-row w-100 align-items-stretch align-items-md-center flex-grow-1"
                        style="gap: 12px;">
                        <form method="GET" action="{{ route('sppd-dalam-daerah.index') }}"
                            class="d-flex flex-column flex-md-row m-0 flex-grow-1" style="gap: 12px;">
                            <!-- SEARCH -->
                            <div class="position-relative flex-grow-1">
                                <button type="submit" class="position-absolute"
                                    style="top: 50%; left: 15px; transform: translateY(-50%); background: none; border: none; padding: 0;">
                                    <i class="fas fa-search text-muted" style="font-size: 1rem; cursor: pointer;"></i>
                                </button>
                                <input type="text" name="search" placeholder="Cari SPPD Dalam Daerah..."
                                    class="form-control shadow-sm w-100 custom-search-input"
                                    style="padding-left: 45px; border-radius: 30px; height: 42px; font-size: 0.95rem; font-weight: 500;"
                                    value="{{ request('search') }}">
                                @if (request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                            </div>
                        </form>

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
                                <a href="{{ route('sppd-dalam-daerah.create') }}"
                                    class="btn btn-primary shadow-sm flex-fill text-nowrap"
                                    style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <i class="fas fa-plus me-2"></i> SPPD Baru
                                </a>
                                <a href="{{ route('sppd-dalam-daerah.export') }}"
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
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">
                                    No.Surat</th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">
                                    Tanggal</th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">
                                    Tujuan</th>
                                <th translate="no"
                                    class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sppd as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        {{ $item->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tujuan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('sppd-dalam-daerah.detail', $item->id) }}">
                                                        <i class="fas fa-eye fa-fw me-2 text-primary"></i>Detail
                                                    </a>
                                                </li>
                                                @if (auth()->user()->role !== 'monitor')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('sppd-dalam-daerah.edit', $item->id) }}">
                                                            <i class="fas fa-edit fa-fw me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDelete({{ $item->id }}, this)"
                                                            data-no-surat="{{ $item->no_surat }}"
                                                            data-tanggal="{{ $item->tanggal->format('d/m/Y') }}"
                                                            data-tujuan="{{ e($item->tujuan) }}">
                                                            <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('sppd-dalam-daerah.destroy', $item->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $sppd->links('pagination::bootstrap-4') }}
                </div>
                <div class="mt-3 mb-2 d-flex justify-content-center">
                    <span class="surat-badge surat-badge-sm d-inline-flex">
                        <i class="fas fa-route me-2"></i> Jumlah SPPD Dalam Daerah:
                        {{ method_exists($sppd, 'total') ? $sppd->total() : $sppd->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <style>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showSuccess(message) {
            Swal.fire({
                title: "Berhasil!",
                text: message,
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                },
                background: '#10B981',
                color: '#ffffff'
            });
        }

        function showError(message) {
            Swal.fire({
                title: "Error!",
                text: message,
                icon: "error",
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                },
                background: '#EF4444',
                color: '#ffffff'
            });
        }

        function confirmDelete(id, el) {
            const noSurat = el ? (el.dataset.noSurat || '-') : '-';
            const tanggal = el ? (el.dataset.tanggal || '-') : '-';
            const tujuan = el ? (el.dataset.tujuan || '-') : '-';

            Swal.fire({
                title: '<strong style="color:#ef4444;">Hapus Data Ini?</strong>',
                icon: 'warning',
                html: `
                    <div style="text-align:left; font-size:0.88rem; line-height:2;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="color:#64748b; width:38%; padding:2px 0;">No. Surat</td>
                                <td style="font-weight:600;">${noSurat}</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b; padding:2px 0;">Tanggal</td>
                                <td style="font-weight:600;">${tanggal}</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b; padding:2px 0;">Tujuan</td>
                                <td style="font-weight:600;">${tujuan}</td>
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
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const banners = document.querySelectorAll('.alert');
            if (banners.length > 0) {
                setTimeout(() => {
                    banners.forEach(banner => {
                        banner.style.transition = 'opacity 0.5s ease';
                        banner.style.opacity = '0';
                        setTimeout(() => banner.remove(), 500); // remove after fade out
                    });
                }, 5000); // 5 seconds
            }
        });
    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
