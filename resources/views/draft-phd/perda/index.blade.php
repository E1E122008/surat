@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><strong>📂 Registrasi Draft PHD </strong> / <span style="color: gray;"> Peraturan Daerah</span></h2>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4">
                {{-- ALERT SECTION --}}
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
                        Peraturan Daerah
                    </h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('draft-phd.perda.index') }}" method="GET" class="flex items-center">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari..." 
                                   class="form-control">
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('draft-phd.perda.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Perda
                        </a>
                        <a href="{{ route('draft-phd.perda.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>  
                </div>
                
                <div class="table-responsive" style="max-width: 1200px; margin: auto;">
                    <table class="table" id="suratTable">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Agenda</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal Terima</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Disposisi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perdas as $index => $perda)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $index + 1 + ($perdas->currentPage() - 1) * $perdas->perPage() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $perda->no_agenda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $perda->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $perda->pengirim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $perda->tanggal_terima->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($perda->disposisi)
                                            @php $disposisiParts = explode('|', $perda->disposisi); @endphp
                                            @foreach($disposisiParts as $part)
                                                {{ trim($part) }}<br>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($perda->status == 'tercatat')
                                            <span class="bg-tercatat">Tercatat</span>
                                        @elseif($perda->status == 'terdisposisi')
                                            <span class="bg-terdisposisi">Terdisposisi</span>
                                        @elseif($perda->status == 'diproses')
                                            <span class="bg-diproses">Diproses</span>
                                        @elseif($perda->status == 'koreksi')
                                            <span class="bg-koreksi">Koreksi</span>
                                        @elseif($perda->status == 'diambil')
                                            <span class="bg-diambil">Diambil</span>
                                        @elseif($perda->status == 'selesai')
                                            <span class="bg-selesai">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" type="button" onclick="openDisposisiModal({{ $perda->id }})"><i class="fas fa-sync-alt fa-fw me-2 text-warning"></i>Disposisi</button></li>
                                                <li><button class="dropdown-item" type="button" onclick="openStatusModal({{ $perda->id }}, '{{ $perda->status }}')"><i class="fas fa-check-circle fa-fw me-2 text-success"></i>Status</button></li>
                                                <li><a class="dropdown-item" href="{{ route('draft-phd.perda.detail', $perda->id) }}"><i class="fas fa-eye fa-fw me-2 text-primary"></i>Detail</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $perda->id }})">
                                                        <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $perda->id }}" action="{{ route('draft-phd.perda.destroy', $perda->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data surat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $perdas->links('pagination::bootstrap-4') }}
                </div>
                <div class="d-flex justify-content-center">
                    <span class="surat-badge surat-badge-sm mt-2 d-inline-block">
                        <i class="fas fa-envelope"></i> Jumlah Surat Peraturan Daerah: {{ $perdas->total() }}
                    </span>
                </div>
                <style>
                    .pagination .page-item:first-child, .pagination .page-item:last-child {
                        display: none !important;
                    }
                </style>
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Perda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="tercatat">Tercatat</option>
                                <option value="terdisposisi">Terdisposisi</option>
                                <option value="diproses">Diproses</option>
                                <option value="koreksi">Koreksi</option>
                                <option value="diambil">Diambil</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disposisiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Disposisi Perda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="disposisiForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="disposisi" class="form-label">Tujuan Disposisi</label>
                            <select class="form-select" id="disposisi" name="disposisi" required>
                                <option value="">Pilih Tujuan Disposisi</option>
                                <option value="Kabag Perancangan Per-UU kab/kota">Kabag Perancangan Per-UU kab/kota</option>
                                <option value="Kabag Bantuan Hukum dan HAM">Kabag Bantuan Hukum dan HAM</option>
                                <option value="Perancangan Per-UU Ahli Madya">Perancangan Per-UU Ahli Madya</option>
                                <option value="Kasubag Tata Usaha">Kasubag Tata Usaha</option>
                            </select>
                        </div>

                        <div class="mb-3" id="subDisposisiContainer" style="display: none;">
                            <label for="sub_disposisi" class="form-label">Diteruskan Kepada</label>
                            <select class="form-select" id="sub_disposisi" name="sub_disposisi">
                                <option value="">Pilih Tujuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_disposisi" class="form-label">Tanggal Disposisi</label>
                            <input type="date" class="form-control" id="tanggal_disposisi" name="tanggal_disposisi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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

        .bg-ktu {
            background-color: rgba(255, 0, 0, 0.2);
            color: red;
            padding: 2px 5px;
            border-radius: 3px;
        }   

        .bg-sekretaris {
            background-color: rgba(0, 0, 255, 0.2);
            color: blue;
            padding: 2px 5px;
            border-radius: 3px;
        }       

        .bg-kepala {
            background-color: rgba(0, 255, 0, 0.2);
            color: green;
            padding: 2px 5px;
            border-radius: 3px;
        }   

        .bg-kasubag {
            background-color: rgba(255, 165, 0, 0.2);
            color: orange;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-tercatat {
            background-color: #D1D5DB;
            color: #374151;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-terdisposisi {
            background-color: rgba(0, 0, 255, 0.2);
            color: blue;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-diproses {
            background-color: #FEF08A;
            color: #713F12;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-koreksi {
            background-color: rgba(255, 165, 0, 0.2);
            color: orange;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-diambil {
            background-color: rgba(0, 255, 0, 0.2);
            color: green;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-selesai {
            background-color: #D8B4FE;
            color: #4C1D95;
            padding: 2px 5px;
            border-radius: 3px;
        }

        /* Remove table borders */
        .table {
            border: none !important;
            margin-bottom: 0 !important;
        }

        .table thead tr {
            background-color: #4a69bd !important;
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
            background-color: #f9fafb;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Remove DataTables default styling */
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

        .surat-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%);
            color: #fff;
            font-weight: 500;
            border-radius: 2rem;
            padding: 0.3rem 1rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(91,126,241,0.08);
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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
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

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF4757', // Merah yang lebih cerah
                cancelButtonColor: '#747D8C', // Abu-abu yang lebih kontras
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

        function openStatusModal(id, currentStatus) {
            console.log('Opening modal for Perda ID:', id);
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            form.action = `/draft-phd/perda/${id}/update-status`;
            document.getElementById('status').value = currentStatus;
            
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(modal).show();
            } else {
                console.error('Bootstrap Modal tidak tersedia');
            }
        }

        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });

        function editCatatan(suratId, currentCatatan) { 
            const container = document.querySelector(`[data-surat-id="${suratId}"]`);
            const textarea = container.querySelector('.catatan-textarea');
            
            textarea.readOnly = !textarea.readOnly;     
            
            if (!textarea.readOnly) {
                textarea.focus();
                container.querySelector('.btn-success i').classList.remove('fa-sync-alt');
                container.querySelector('.btn-success i').classList.add('fa-save');
            } else {    
                container.querySelector('.btn-success i').classList.remove('fa-save');
                container.querySelector('.btn-success i').classList.add('fa-sync-alt');
                
                fetch(`/draft-phd/perda/${suratId}/update-catatan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        catatan: textarea.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // showSuccess('Catatan berhasil diperbarui');
                    } else {
                        // showError('Gagal memperbarui catatan');
                        textarea.value = currentCatatan;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // showError('Terjadi kesalahan sistem');
                    textarea.value = currentCatatan;
                });
            }
        }

        const subDisposisiOptions = {
            'Kabag Perancangan Per-UU kab/kota': [
                'Belum/Tidak diteruskan',
                'Analisis Hukum Wilayah 1',
                'Analisis Hukum Wilayah 2',
                'Analisis Hukum Wilayah 3'
            ],
            'Kabag Bantuan Hukum dan HAM': [
                'Belum/Tidak diteruskan',
                'Analisis Hukum Litigasi',
                'Analisis Hukum Non-Litigasi',
                'Kasubag Tata Usaha'
            ],
            'Perancangan Per-UU Ahli Madya': [
                'Belum/Tidak diteruskan',
                'Sub Kordinator Penetapan',
                'Sub Kordinator Pengaturan',
                'Sub Kordinator Dokumentasi NHL'
            ],
            'Kasubag Tata Usaha': [
                'Belum/Tidak diteruskan'
            ]
        };

        function setDefaultDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_disposisi').value = today;
        }

        function openDisposisiModal(id) {
            document.getElementById('disposisiForm').action = `/draft-phd/perda/${id}/disposisi`;
            document.getElementById('disposisi').value = '';
            document.getElementById('sub_disposisi').value = '';
            document.getElementById('subDisposisiContainer').style.display = 'none';
            setDefaultDate();
            new bootstrap.Modal(document.getElementById('disposisiModal')).show();
        }

        document.getElementById('disposisi').addEventListener('change', function() {
            const selectedDisposisi = this.value;
            const subDisposisiContainer = document.getElementById('subDisposisiContainer');
            const subDisposisiSelect = document.getElementById('sub_disposisi');
            
            subDisposisiSelect.innerHTML = '<option value="">Pilih Tujuan</option>';
            
            if (selectedDisposisi && subDisposisiOptions[selectedDisposisi]) {
                subDisposisiContainer.style.display = 'block';
                
                subDisposisiOptions[selectedDisposisi].forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    subDisposisiSelect.appendChild(optionElement);
                });
                
                subDisposisiSelect.required = (selectedDisposisi !== 'Kasubag Tata Usaha');
            } else {
                subDisposisiContainer.style.display = 'none';
                subDisposisiSelect.required = false;
            }
        });

        document.getElementById('disposisiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const disposisi = document.getElementById('disposisi').value;
            const subDisposisi = document.getElementById('sub_disposisi').value;
            const tanggalDisposisi = document.getElementById('tanggal_disposisi').value;
            
            if (!disposisi) {
                alert('Silakan pilih tujuan disposisi');
                return;
            }
            
            if (disposisi !== 'Kasubag Tata Usaha' && !subDisposisi) {
                alert('Silakan pilih sub disposisi');
                return;
            }
            
            if (!tanggalDisposisi) {
                alert('Silakan isi tanggal disposisi');
                return;
            }
            
            this.submit();
        });

        // Auto-hide alert
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let alert = document.getElementById('alertBox');
                if (alert) {
                    new bootstrap.Alert(alert).close();
                }
            }, 5000);
        });
    </script>  
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // The DataTables initialization is removed as per the new_code.
            // The pagination links are now handled by the Blade template's {{ $perdas->links('pagination::bootstrap-4') }}
        });
    </script>
@endsection