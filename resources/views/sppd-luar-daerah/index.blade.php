@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><strong>📂 Surat Perintah Perjalanan Dinas</strong> / <span style="color: gray;"> SPPD Luar Daerah</span></h2>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertBox">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertBox">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 tracking-wide">
                        SPPD Luar Daerah
                    </h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('sppd-luar-daerah.index') }}" method="GET" class="flex items-center">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari SPPD Luar Daerah..." 
                                   class="form-control">
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('sppd-luar-daerah.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> SPPD Baru
                        </a>
                        <a href="{{ route('sppd-luar-daerah.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div class="table-responsive" style="max-width: 1200px; margin: auto;">
                    <table class="table" id="suratTable">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">No Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sppdLuarDaerah as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tujuan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('sppd-luar-daerah.detail', $item->id) }}">
                                                        <i class="fas fa-eye fa-fw me-2 text-primary"></i>Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('sppd-luar-daerah.edit', $item->id) }}">
                                                        <i class="fas fa-edit fa-fw me-2 text-warning"></i>Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('sppd-luar-daerah.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $sppdLuarDaerah->links() }}
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

        .btn-info, .btn-danger {
            margin: 0 0.25rem;
        }

        .fas {
            font-size: 1rem;
        }

        /* Adjust the action buttons container */
        .flex.justify-center.items-center {
            gap: 0.5rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF4757',
                cancelButtonColor: '#747D8C',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__bounceIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                },
                customClass: {
                    popup: 'rounded-lg shadow-lg',
                    confirmButton: 'rounded-md px-4 py-2',
                    cancelButton: 'rounded-md px-4 py-2'
                },
                background: '#FFFFFF',
                backdrop: 'rgba(0,0,0,0.4)',
                padding: '2em'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#suratTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "dom": "<'row'<'col-sm-12'tr>>" +
                       "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                "language": {
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Berikutnya"
                    },
                    "emptyTable": "Tidak ada data yang tersedia",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ada data yang cocok ditemukan"
                }
            });
        });
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
@endsection