@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><strong>📂 Registrasi Draft PHD </strong> / <span style="color: gray;"> Surat Keputusan</span></h2>
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
                        Surat Keputusan
                    </h2>
                    <div class="flex space-x-2">
                            <form action="{{ route('draft-phd.sk.index') }}" method="GET" class="flex items-center">
                                <input type="text" 
                                       name="search" 
                                       placeholder="Cari SK..." 
                                   class="form-control"
                                   value="{{ request('search') }}"> 
                                <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-search"></i>
                                </button>
                            </form>
                            <a href="{{ route('draft-phd.sk.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah SK
                            </a>
                            <a href="{{ route('draft-phd.sk.export') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
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
                        @forelse($sks as $index => $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $index + 1 + ($sks->currentPage() - 1) * $sks->perPage() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $item->no_agenda }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $item->no_surat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $item->pengirim }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $item->tanggal_terima->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    @if($item->disposisi)
                                        @php
                                            $disposisiParts = explode('|', $sk->disposisi);
                                            $mainDisposisi = trim($disposisiParts[0]);
                                        @endphp
                                        <span class="bg-{{ strtolower(str_replace(' ', '-', $mainDisposisi)) }}">
                                            {{ $mainDisposisi }}
                                        </span>
                                        @if(count($disposisiParts) > 1)
                                            <br>
                                            <small class="text-muted">
                                                @foreach(array_slice($disposisiParts, 1) as $part)
                                                    {{ trim($part) }}<br>
                                                @endforeach
                                            </small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($item->status == 'tercatat')
                                            <span class="bg-tercatat">Tercatat</span>
                                        @elseif($item->status == 'terdisposisi')
                                            <span class="bg-terdisposisi">Terdisposisi</span>
                                        @elseif($item->status == 'diproses')
                                            <span class="bg-diproses">Diproses</span>
                                        @elseif($item->status == 'koreksi')
                                            <span class="bg-koreksi">Koreksi</span>
                                        @elseif($item->status == 'diambil')
                                            <span class="bg-diambil">Diambil</span>
                                        @elseif($item->status == 'selesai')
                                            <span class="bg-selesai">Selesai</span>
                                        @endif
                                    </td>  
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" type="button" onclick="openDisposisiModal({{ $item->id }})"><i class="fas fa-sync-alt fa-fw me-2 text-warning"></i>Disposisi</button></li>
                                                <li><button class="dropdown-item" type="button" onclick="openStatusModal({{ $item->id }}, '{{ $item->status }}')"><i class="fas fa-check-circle fa-fw me-2 text-success"></i>Status</button></li>
                                                <li><a class="dropdown-item" href="{{ route('draft-phd.sk.detail', $item->id) }}"><i class="fas fa-eye fa-fw me-2 text-primary"></i>Detail</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('draft-phd.sk.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data surat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $sks->links('pagination::bootstrap-4') }}
                </div>
                <div class="d-flex justify-content-center">
                    <span class="surat-badge surat-badge-sm mt-2 d-inline-block">
                        <i class="fas fa-envelope"></i> Jumlah Surat Keputusan: {{ $sks->total() }}
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
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        @csrf
                        <input type="hidden" id="suratId" name="id">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="tercatat">Tercatat</option>
                                <option value="terdisposisi">Terdisposisi</option>
                                <option value="diproses">Diproses</option>
                                <option value="koreksi">Koreksi</option>
                                <option value="diambil">Diambil</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveStatus">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="disposisiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Disposisi Surat</h5>
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

        tr:hover {
            background-color: #f2f2f2;
        }

        .bg-ktu {
            background-color: rgba(0, 255, 0, 0.2);
            color: green;
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
            background-color: rgba(255, 0, 0, 0.2);
            color: red;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-kasubag {
            background-color: rgba(255, 165, 0, 0.2);
            color: orange;
            padding: 2px 5px;
            border-radius: 3px;
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
            
            fetch(`/draft-phd/sk/${suratId}/update-catatan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    catatan: textarea.value
                })
            })
            .then(response => {
                console.log('Response:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);
                if (data.success) {
                    showSuccess('Catatan berhasil diperbarui');
                } else {
                    showError('Gagal memperbarui catatan');
                    textarea.value = currentCatatan;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan sistem');
                textarea.value = currentCatatan;
            });
        }
    }

    function openStatusModal(id, currentStatus) {
            document.getElementById('statusForm').action = `/sk/update-status/${id}`;
        document.getElementById('status').value = currentStatus;
            document.getElementById('suratId').value = id;
            new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    document.getElementById('saveStatus').addEventListener('click', function() {
            const id = document.getElementById('suratId').value;
        const status = document.getElementById('status').value;
        const token = document.querySelector('meta[name="csrf-token"]').content;

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/sk/update-status/${id}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = token;
        form.appendChild(csrfInput);
        
        // Add status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    });

    // Definisikan subDisposisiOptions di luar event listener
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

    // Tambahkan fungsi untuk set tanggal otomatis
    function setDefaultDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_disposisi').value = today;
    }

    function openDisposisiModal(id) {
        document.getElementById('disposisiForm').action = `/draft-phd/sk/${id}/disposisi`;
        document.getElementById('disposisi').value = '';
        document.getElementById('sub_disposisi').value = '';
        document.getElementById('subDisposisiContainer').style.display = 'none';
        setDefaultDate();
        new bootstrap.Modal(document.getElementById('disposisiModal')).show();
    }

    // Event listener untuk perubahan disposisi
    document.getElementById('disposisi').addEventListener('change', function() {
        const selectedDisposisi = this.value;
        const subDisposisiContainer = document.getElementById('subDisposisiContainer');
        const subDisposisiSelect = document.getElementById('sub_disposisi');
        
        // Reset sub disposisi
        subDisposisiSelect.innerHTML = '<option value="">Pilih Tujuan</option>';
        
        if (selectedDisposisi && subDisposisiOptions[selectedDisposisi]) {
            // Tampilkan container dan tambahkan opsi
            subDisposisiContainer.style.display = 'block';
            
            subDisposisiOptions[selectedDisposisi].forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                subDisposisiSelect.appendChild(optionElement);
            });
            
            // Set required jika bukan Kasubag Tata Usaha
            subDisposisiSelect.required = (selectedDisposisi !== 'Kasubag Tata Usaha');
        } else {
            subDisposisiContainer.style.display = 'none';
            subDisposisiSelect.required = false;
        }
    });

    // Handle form submission
    document.getElementById('disposisiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const disposisi = document.getElementById('disposisi').value;
        const subDisposisi = document.getElementById('sub_disposisi').value;
        
        // Validasi khusus
        if (disposisi !== 'Kasubag Tata Usaha' && !subDisposisi) {
            alert('Silakan pilih sub disposisi');
            return;
        }
        
        this.submit();
    });
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // The DataTables initialization is removed as per the new_code.
            // The pagination links are now handled by the default Laravel pagination.

            // Automatically hide alerts after 5 seconds
            window.setTimeout(function() {
                $(".alert-dismissible").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 5000); // 5 seconds
        });
    </script>   
@endsection