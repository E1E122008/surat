@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><strong>📂 Surat Umum</strong> / <span style="color: gray;"> Surat Keluar</span></h2>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4">
                <!-- Alert Section -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 tracking-wide">
                        Surat Keluar
                    </h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('surat-keluar.index') }}" method="GET" class="flex items-center">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari surat keluar..." 
                                   class="form-control"
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Surat Keluar
                        </a>
                        <a href="{{ route('surat-keluar.export') }}" class="btn btn-success">
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
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Perihal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Lampiran</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suratKeluar as $index => $surat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->perihal }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="window.location.href='{{ asset('storage/' . $surat->lampiran) }}'" class="btn btn-primary">
                                            <i class="fas fa-eye"></i> Lihat Lampiran
                                        </button>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="{{ route('surat-keluar.edit', $surat->id) }}" class="btn btn-info btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form id="delete-form-{{ $surat->id }}" action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $surat->id }})" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $suratKeluar->links() }}
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

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert i {
            margin-right: 0.5rem;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .btn-close:hover {
            opacity: 1;
        }
    </style>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        function confirmDelete(id) {
            console.log('confirmDelete called with id:', id); // Debug log
            
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
                console.log('SweetAlert result:', result); // Debug log
                if (result.isConfirmed) {
                    console.log('User confirmed delete, submitting form...'); // Debug log
                    const form = document.getElementById('delete-form-' + id);
                    console.log('Form element:', form); // Debug log
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form not found for id:', id);
                    }
                }
            }).catch((error) => {
                console.error('SweetAlert error:', error); // Debug log
            });
        }

        function searchTable() {
            const input = document.getElementById('search');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('table');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
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
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "emptyTable": "No data available"
                }
            });
        });
    </script>
@endsection