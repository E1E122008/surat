@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100" style="max-width: 1400px; margin: auto; padding: 20px;">
        <div class="mb-4">
            <h2 class="header h2"><strong>📂 Surat Umum</strong> / <span style="color: gray;"> Surat Masuk</span></h2>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 tracking-wide">
                        Surat Masuk
                    </h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex items-center">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari surat masuk..." 
                                   class="form-control"
                                   value="{{ request('search') }}"> 
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('surat-masuk.create') }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i> Surat Masuk
                        </a>
                        <a href="{{ route('surat-masuk.export') }}" 
                           class="btn btn-success">
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
                            @forelse($suratMasuk as $index => $surat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $index + 1 + ($suratMasuk->currentPage() - 1) * $suratMasuk->perPage() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $surat->no_agenda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $surat->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $surat->pengirim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($surat->disposisi)
                                            @php
                                                $disposisiParts = explode('|', $surat->disposisi);
                                                $persetujuanKetua = null;
                                                $tujuanDisposisi = null;
                                                $otherParts = [];
                                                
                                                // Pisahkan status persetujuan dari bagian lainnya
                                                // Format baru: "Sudah di Setujui Ketua Biro Hukum" atau "Belum di Setujui Ketua Biro Hukum" (biasanya di bagian pertama)
                                                foreach($disposisiParts as $index => $part) {
                                                    $trimmedPart = trim($part);
                                                    if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i', $trimmedPart)) {
                                                        $persetujuanKetua = $trimmedPart;
                                                    } elseif (strpos($trimmedPart, 'Persetujuan Ketua Biro Hukum:') !== false) {
                                                        // Fallback untuk format lama
                                                        $persetujuanKetua = $trimmedPart;
                                                    } elseif ($index === 0 && !$persetujuanKetua) {
                                                        // Jika bagian pertama bukan persetujuan, berarti itu tujuan disposisi
                                                        $tujuanDisposisi = $trimmedPart;
                                                    } else {
                                                        $otherParts[] = $trimmedPart;
                                                    }
                                                }
                                                
                                                
                                                // Jika tidak ada tujuan disposisi yang terpisah, ambil dari otherParts
                                                if (!$tujuanDisposisi && count($otherParts) > 0) {
                                                    $tujuanDisposisi = $otherParts[0];
                                                    $otherParts = array_slice($otherParts, 1);
                                                }
                                            @endphp
                                            <div class="text-center">
                                                {{-- Tampilkan Status Persetujuan Terlebih Dahulu --}}
                                                @if($persetujuanKetua)
                                                    <div class="mb-2">
                                                        <span class="badge {{ (stripos($persetujuanKetua, 'Sudah') !== false || stripos($persetujuanKetua, 'sudah') !== false) ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $persetujuanKetua }}
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                {{-- Tampilkan Tujuan Disposisi Utama --}}
                                                @if($tujuanDisposisi)
                                                    <div class="mb-1">
                                                        <strong>{{ $tujuanDisposisi }}</strong>
                                                    </div>
                                                @endif
                                                
                                                {{-- Tampilkan Informasi Lainnya --}}
                                                @if(count($otherParts) > 0)
                                                    <small class="text-muted d-block">
                                                        @foreach($otherParts as $part)
                                                            {{ $part }}
                                                            @if(!$loop->last)<br>@endif
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($surat->status == 'tercatat')
                                            <span class="bg-tercatat">Tercatat</span>
                                        @elseif($surat->status == 'terdisposisi')
                                            <span class="bg-terdisposisi">Terdisposisi</span>
                                        @elseif($surat->status == 'diproses')
                                            <span class="bg-diproses">Diproses</span>
                                        @elseif($surat->status == 'koreksi')
                                            <span class="bg-koreksi">Koreksi</span>
                                        @elseif($surat->status == 'diambil')
                                            <span class="bg-diambil">Diambil</span>
                                        @elseif($surat->status == 'selesai')
                                            <span class="bg-selesai">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" type="button" 
                                                        data-surat-id="{{ $surat->id }}"
                                                        data-persetujuan="{{ isset($persetujuanKetua) && $persetujuanKetua ? $persetujuanKetua : 'Belum' }}"
                                                        data-tujuan="{{ isset($tujuanDisposisi) && $tujuanDisposisi ? htmlspecialchars($tujuanDisposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                        data-sub-disposisi="{{ isset($subDisposisi) && $subDisposisi ? htmlspecialchars($subDisposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                        data-tanggal="{{ isset($tanggalDisposisi) && $tanggalDisposisi ? htmlspecialchars($tanggalDisposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                        data-catatan="{{ isset($catatan) && $catatan ? htmlspecialchars($catatan, ENT_QUOTES, 'UTF-8') : '' }}"
                                                        data-disposisi-full="{{ $surat->disposisi ? htmlspecialchars($surat->disposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                        onclick="openDisposisiModal({{ $surat->id }}, this)">
                                                        <i class="fas fa-sync-alt fa-fw me-2 text-warning"></i>Disposisi
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" type="button" onclick="openStatusModal({{ $surat->id }}, '{{ $surat->status }}')">
                                                        <i class="fas fa-check-circle fa-fw me-2 text-success"></i>Status
                                            </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('surat-masuk.detail', $surat->id) }}">
                                                        <i class="fas fa-eye fa-fw me-2 text-primary"></i>Detail
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $surat->id }})">
                                                    <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <form id="delete-form-{{ $surat->id }}" action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" style="display: none;">
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
                    {{ $suratMasuk->links('pagination::bootstrap-4') }}
                </div>
                <div class="d-flex justify-content-center">
                    <span class="surat-badge surat-badge-sm mt-2 d-inline-block">
                        <i class="fas fa-envelope"></i> Jumlah Surat Masuk: {{ $suratMasuk->total() }}
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
                        <!-- STATUS PERSETUJUAN KETUA BIRO HUKUM -->
                        <div class="mb-3">
<label class="form-label"><strong>Status Persetujuan Ketua Biro Hukum:</strong></label>
@if(auth()->user() && auth()->user()->role === 'admin')
    <div id="radioPersetujuanGroup" class="mb-2">
        <input class="form-check-input" type="radio" name="persetujuan_ketua" id="radioDisetujui" value="Sudah">
        <label class="form-check-label me-3" for="radioDisetujui">Sudah Disetujui</label>
        <input class="form-check-input" type="radio" name="persetujuan_ketua" id="radioBelum" value="Belum">
        <label class="form-check-label" for="radioBelum">Belum Disetujui</label>
    </div>
@else
    <span id="statusPersetujuan" class="badge bg-secondary"></span>
@endif
                        <input type="hidden" id="disposisiSuratId" name="disposisiSuratId" />
                        <input type="hidden" id="currentDisposisi" name="currentDisposisi" />
                        </div>
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

        .bg-tercatat     {
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

        .btn-info:hover {
            background-color: #3c5aa8;
        }
    </style>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function showSubpoints(select) {
            let selectedValue = select.value;
            let subpointSelect = select.parentElement.querySelector('.subpoint');
            
            let subpoints = {
                'kabag': ['Analisis Hukum ahli muda wilayah 1', 'Analisis Hukum ahli muda wilayah 2','Analisis Hukum ahli muda wilayah 3'],
                'bankum': ['Litigasi', 'Non-litigasi', 'Kasubag Tata Usaha'],
                'madya': ['Subker Penetapan', 'Subker Pengaturan']
            };

            subpointSelect.innerHTML = '<option value="">Pilih Subpoint</option>';

            if (selectedValue && subpoints[selectedValue]) {
                subpoints[selectedValue].forEach(sp => {
                    let option = document.createElement("option");
                    option.value = sp.toLowerCase().replace(/\s+/g, '_');
                    option.textContent = sp;
                    subpointSelect.appendChild(option);
                });
            }

            subpointSelect.style.display = selectedValue ? 'block' : 'none';
        }

        function openStatusModal(id, currentStatus) {
            document.getElementById('statusForm').action = `/surat-masuk/${id}/update-status`;
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
            form.action = `/surat-masuk/${id}/update-status`;
            
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
         
        // Fungsi untuk konfirmasi hapus dengan SweetAlert2
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

        function editCatatan(suratId, currentCatatan) {
            const container = document.querySelector(`[data-surat-id="${suratId}"]`);
            const textarea = container.querySelector('.catatan-textarea');
            
            // Toggle readonly state
            textarea.readOnly = !textarea.readOnly;
            
            if (!textarea.readOnly) {
                // Enter edit mode
                textarea.focus();
                container.querySelector('.btn-success i').classList.remove('fa-sync-alt');
                container.querySelector('.btn-success i').classList.add('fa-save');
            } else {
                // Save mode
                container.querySelector('.btn-success i').classList.remove('fa-save');
                container.querySelector('.btn-success i').classList.add('fa-sync-alt');
                
                // Send AJAX request to update catatan
                fetch(`/surat-masuk/${suratId}/update-catatan`, {
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
                        showSuccess('Catatan berhasil diperbarui');
                    } else {
                        showError('Gagal memperbarui catatan');
                        textarea.value = currentCatatan; // Revert to original value
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan sistem');
                    textarea.value = currentCatatan; // Revert to original value
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
            ]
        };

        document.getElementById('disposisi').addEventListener('change', function() {
            const subDisposisiContainer = document.getElementById('subDisposisiContainer');
            const subDisposisiSelect = document.getElementById('sub_disposisi');
            const selectedDisposisi = this.value;

            // Reset sub disposisi
            subDisposisiSelect.innerHTML = '<option value="">Pilih Tujuan</option>';

            if (subDisposisiOptions[selectedDisposisi]) {
                // Tampilkan dan isi opsi sub disposisi
                subDisposisiContainer.style.display = 'block';
                subDisposisiOptions[selectedDisposisi].forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    subDisposisiSelect.appendChild(optionElement);
                });
                // Set default value ke "Belum/Tidak diteruskan"
                subDisposisiSelect.value = 'Belum/Tidak diteruskan';
                subDisposisiSelect.required = true;
            } else {
                // Sembunyikan sub disposisi jika tidak ada opsi
                subDisposisiContainer.style.display = 'none';
                subDisposisiSelect.required = false;
            }
        });

        // Tambahkan fungsi untuk set tanggal otomatis
        function setDefaultDate() {
            const tanggalInput = document.getElementById('tanggal_disposisi');
            if (tanggalInput) {
                const today = new Date().toISOString().split('T')[0];
                tanggalInput.value = today;
                console.log('Default date set:', today);
            }
        }

        // Panggil fungsi saat modal disposisi dibuka
        function openDisposisiModal(id, buttonElement) {
            const form = document.getElementById('disposisiForm');
            form.action = `/surat-masuk/${id}/disposisi`;
            document.getElementById('disposisiSuratId').value = id;
            
            // Reset form terlebih dahulu
            form.reset();
            document.getElementById('subDisposisiContainer').style.display = 'none';
            
            // Ambil data dari data attribute button (tanpa API)
            const persetujuan = buttonElement && buttonElement.getAttribute('data-persetujuan') ? buttonElement.getAttribute('data-persetujuan') : 'Belum';
            const tujuan = buttonElement && buttonElement.getAttribute('data-tujuan') ? buttonElement.getAttribute('data-tujuan') : '';
            const subDisposisi = buttonElement && buttonElement.getAttribute('data-sub-disposisi') ? buttonElement.getAttribute('data-sub-disposisi') : '';
            const tanggal = buttonElement && buttonElement.getAttribute('data-tanggal') ? buttonElement.getAttribute('data-tanggal') : '';
            const catatan = buttonElement && buttonElement.getAttribute('data-catatan') ? buttonElement.getAttribute('data-catatan') : '';
            
            console.log('Data from button attributes:', {
                persetujuan,
                tujuan,
                subDisposisi,
                tanggal,
                catatan
            });
            
            // Simpan disposisi lengkap untuk radio button listener
            const currentDisposisiInput = document.getElementById('currentDisposisi');
            if (currentDisposisiInput && buttonElement) {
                // Ambil disposisi lengkap dari data attribute
                const disposisiFull = buttonElement.getAttribute('data-disposisi-full');
                if (disposisiFull) {
                    currentDisposisiInput.value = disposisiFull;
                } else {
                    // Fallback: ambil dari API jika tidak ada di data attribute
                    fetch(`/api/surat-masuk/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.disposisi) {
                                currentDisposisiInput.value = data.disposisi;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching disposisi:', error);
                        });
                }
            }
            
            let isAdmin = false;
            @if(auth()->user() && auth()->user()->role === 'admin')
                isAdmin = true;
            @endif
            
            // 1. Set Status Persetujuan Ketua Biro Hukum
            // Normalisasi persetujuan (bisa "Sudah", "Belum", atau "Sudah di Setujui Ketua Biro Hukum", dll)
            let persetujuanValue = 'Belum';
            if (persetujuan) {
                if (persetujuan.toLowerCase().includes('sudah')) {
                    persetujuanValue = 'Sudah';
                } else if (persetujuan.toLowerCase().includes('belum')) {
                    persetujuanValue = 'Belum';
                } else {
                    persetujuanValue = persetujuan; // Ambil apa adanya jika tidak match
                }
            }
            
            let status = persetujuanValue === 'Sudah' ? 'Sudah Disetujui' : 'Belum Disetujui';
            console.log('Setting persetujuan:', persetujuanValue, '->', status);
            
            if (isAdmin) {
                const radioDisetujui = document.getElementById('radioDisetujui');
                const radioBelum = document.getElementById('radioBelum');
                if (radioDisetujui && radioBelum) {
                    if (persetujuanValue === 'Sudah') {
                        radioDisetujui.checked = true;
                        radioBelum.checked = false;
                        console.log('Radio Sudah checked');
                    } else {
                        radioDisetujui.checked = false;
                        radioBelum.checked = true;
                        console.log('Radio Belum checked');
                    }
                }
            } else {
                const statusEl = document.getElementById('statusPersetujuan');
                if (statusEl) {
                    statusEl.innerText = status;
                }
            }
            
            // 2. Set Tujuan Disposisi
            if (tujuan) {
                const disposisiSelect = document.getElementById('disposisi');
                if (disposisiSelect) {
                    const tujuanValue = tujuan.trim();
                    console.log('Setting tujuan disposisi:', tujuanValue);
                    
                    // Cari option yang cocok
                    let found = false;
                    for (let i = 0; i < disposisiSelect.options.length; i++) {
                        const option = disposisiSelect.options[i];
                        if (option.value === tujuanValue || option.textContent.trim() === tujuanValue) {
                            disposisiSelect.value = option.value;
                            found = true;
                            console.log('Found matching option:', option.value);
                            break;
                        }
                    }
                    
                    // Jika tidak ditemukan exact match, coba partial match
                    if (!found) {
                        for (let i = 0; i < disposisiSelect.options.length; i++) {
                            const option = disposisiSelect.options[i];
                            if (option.value.includes(tujuanValue) || tujuanValue.includes(option.value) ||
                                option.textContent.includes(tujuanValue) || tujuanValue.includes(option.textContent.trim())) {
                                disposisiSelect.value = option.value;
                                found = true;
                                console.log('Found partial matching option:', option.value);
                                break;
                            }
                        }
                    }
                    
                    // Trigger change event untuk menampilkan sub disposisi jika ada
                    setTimeout(() => {
                        const changeEvent = new Event('change', { bubbles: true });
                        disposisiSelect.dispatchEvent(changeEvent);
                        
                        // 3. Set Diteruskan Kepada (sub_disposisi) setelah change event
                        if (subDisposisi) {
                            setTimeout(() => {
                                const subDisposisiContainer = document.getElementById('subDisposisiContainer');
                                const subDisposisiSelect = document.getElementById('sub_disposisi');
                                
                                if (subDisposisiContainer && subDisposisiSelect) {
                                    subDisposisiContainer.style.display = 'block';
                                    
                                    // Cari option yang cocok
                                    let found = false;
                                    for (let i = 0; i < subDisposisiSelect.options.length; i++) {
                                        if (subDisposisiSelect.options[i].value === subDisposisi || 
                                            subDisposisiSelect.options[i].textContent.trim() === subDisposisi) {
                                            subDisposisiSelect.value = subDisposisiSelect.options[i].value;
                                            found = true;
                                            break;
                                        }
                                    }
                                    
                                    if (!found && subDisposisi) {
                                        // Tambahkan option baru jika tidak ada
                                        const newOption = document.createElement('option');
                                        newOption.value = subDisposisi;
                                        newOption.textContent = subDisposisi;
                                        subDisposisiSelect.appendChild(newOption);
                                        subDisposisiSelect.value = subDisposisi;
                                    }
                                    
                                    // Jika tidak ada sub_disposisi yang ada, set default ke "Belum/Tidak diteruskan"
                                    if (!subDisposisi && subDisposisiSelect.options.length > 1) {
                                        subDisposisiSelect.value = 'Belum/Tidak diteruskan';
                                    }
                                }
                            }, 100);
                        } else {
                            // Jika tidak ada sub_disposisi yang ada, set default setelah change event
                            setTimeout(() => {
                                const subDisposisiContainer = document.getElementById('subDisposisiContainer');
                                const subDisposisiSelect = document.getElementById('sub_disposisi');
                                if (subDisposisiContainer && subDisposisiSelect && subDisposisiContainer.style.display === 'block') {
                                    subDisposisiSelect.value = 'Belum/Tidak diteruskan';
                                }
                            }, 150);
                        }
                    }, 50);
                }
            }
            
            // 4. Set Tanggal Disposisi (PENTING: gunakan data dari database jika ada)
            const tanggalInput = document.getElementById('tanggal_disposisi');
            if (tanggalInput) {
                if (tanggal && tanggal.trim()) {
                    let tanggalValue = tanggal.trim();
                    console.log('Setting tanggal disposisi from data:', tanggalValue);
                    
                    // Jika format YYYY-MM-DD sudah benar, gunakan langsung
                    if (tanggalValue.match(/^\d{4}-\d{2}-\d{2}$/)) {
                        tanggalInput.value = tanggalValue;
                        console.log('Tanggal set to (YYYY-MM-DD):', tanggalValue);
                    } else {
                        // Coba parse format lain (DD/MM/YYYY, DD-MM-YYYY, dll)
                        const dateParts = tanggalValue.split(/[-\/]/);
                        if (dateParts.length === 3) {
                            let day, month, year;
                            
                            // Cek panjang tahun untuk menentukan format
                            if (dateParts[2].length === 4) {
                                // Format DD/MM/YYYY atau MM/DD/YYYY
                                if (parseInt(dateParts[0]) > 12) {
                                    // Format DD/MM/YYYY atau DD-MM-YYYY
                                    day = dateParts[0].padStart(2, '0');
                                    month = dateParts[1].padStart(2, '0');
                                    year = dateParts[2];
                                } else {
                                    // Format MM/DD/YYYY atau MM-DD-YYYY
                                    month = dateParts[0].padStart(2, '0');
                                    day = dateParts[1].padStart(2, '0');
                                    year = dateParts[2];
                                }
                            } else if (dateParts[0].length === 4) {
                                // Format YYYY-MM-DD atau YYYY/MM/DD
                                year = dateParts[0];
                                month = dateParts[1].padStart(2, '0');
                                day = dateParts[2].padStart(2, '0');
                            } else {
                                // Default: asumsikan DD/MM/YYYY
                                day = dateParts[0].padStart(2, '0');
                                month = dateParts[1].padStart(2, '0');
                                year = dateParts[2];
                            }
                            
                            tanggalInput.value = `${year}-${month}-${day}`;
                            console.log('Converted tanggal:', tanggalInput.value);
                        } else {
                            // Fallback: gunakan tanggal hari ini jika format tidak valid
                            console.log('Invalid tanggal format, using default');
                            setDefaultDate();
                        }
                    }
                } else {
                    // Set tanggal default jika tidak ada data
                    console.log('No tanggal data, using default');
                    setDefaultDate();
                }
            }
            
            // 5. Set Catatan
            if (catatan) {
                const catatanInput = document.getElementById('catatan');
                if (catatanInput) {
                    catatanInput.value = catatan;
                    console.log('Setting catatan:', catatan);
                }
            }
            
            // Tampilkan modal
            setTimeout(() => {
                const modalElement = document.getElementById('disposisiModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                    
                    // Log final values setelah modal ditampilkan
                    setTimeout(() => {
                        console.log('Modal shown, final values:');
                        console.log('Disposisi:', document.getElementById('disposisi')?.value);
                        console.log('Tanggal:', document.getElementById('tanggal_disposisi')?.value);
                        console.log('Catatan:', document.getElementById('catatan')?.value);
                    }, 100);
                }
            }, 100);
        }

        // Radio button listener (ADMIN)
        if (document.getElementById('radioPersetujuanGroup')) {
            document.getElementById('radioPersetujuanGroup').addEventListener('change', function(e) {
                const id = document.getElementById('disposisiSuratId').value;
                const currentDisposisiInput = document.getElementById('currentDisposisi');
                let disposisiBaru = currentDisposisiInput ? currentDisposisiInput.value : '';
                let statusSetuju = false;
                
                // Jika tidak ada disposisi di hidden input, ambil dari API (fallback)
                if (!disposisiBaru) {
                    fetch(`/api/surat-masuk/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            disposisiBaru = data.disposisi || '';
                            updatePersetujuan(id, disposisiBaru);
                        })
                        .catch(error => {
                            console.error('Error fetching disposisi:', error);
                        });
                    return;
                }
                
                updatePersetujuan(id, disposisiBaru);
            });
        }
        
        function updatePersetujuan(id, disposisiBaru) {
            let statusSetuju = false;
            
            if(document.getElementById('radioDisetujui').checked) {
                statusSetuju = true;
                // Format baru: "Sudah di Setujui Ketua Biro Hukum"
                const newFormat = 'Sudah di Setujui Ketua Biro Hukum';
                if (disposisiBaru.match(/(Sudah|Belum|sudah|belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i)) {
                    disposisiBaru = disposisiBaru.replace(/(Sudah|Belum|sudah|belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i, newFormat);
                } else if (disposisiBaru.includes('Persetujuan Ketua Biro Hukum:')) {
                    // Fallback format lama
                    disposisiBaru = disposisiBaru.replace(/Persetujuan Ketua Biro Hukum:[^|]*/g, newFormat);
                } else {
                    disposisiBaru = newFormat + (disposisiBaru ? ' | ' + disposisiBaru : '');
                }
            } else {
                // Format baru: "Belum di Setujui Ketua Biro Hukum"
                const newFormat = 'Belum di Setujui Ketua Biro Hukum';
                if (disposisiBaru.match(/(Sudah|Belum|sudah|belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i)) {
                    disposisiBaru = disposisiBaru.replace(/(Sudah|Belum|sudah|belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i, newFormat);
                } else if (disposisiBaru.includes('Persetujuan Ketua Biro Hukum:')) {
                    // Fallback format lama
                    disposisiBaru = disposisiBaru.replace(/Persetujuan Ketua Biro Hukum:[^|]*/g, newFormat);
                } else {
                    disposisiBaru = newFormat + (disposisiBaru ? ' | ' + disposisiBaru : '');
                }
            }
            
            // Update hidden input dengan disposisi baru
            const currentDisposisiInput = document.getElementById('currentDisposisi');
            if (currentDisposisiInput) {
                currentDisposisiInput.value = disposisiBaru;
            }
            
            sendUpdatePersetujuan(id, disposisiBaru, statusSetuju);
        }

        function sendUpdatePersetujuan(id, disposisiBaru, statusSetuju) {
            fetch(`/surat-masuk/${id}/update-disposisi`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ disposisi: disposisiBaru })
            })
            .then((response) => response.json())
            .then(data => {
                if(data.success) {
                    // Update tampilan status (jika ada untuk non-admin)
                    const statusPersetujuanEl = document.getElementById('statusPersetujuan');
                    if (statusPersetujuanEl) {
                        statusPersetujuanEl.innerText = statusSetuju ? 'Sudah Disetujui' : 'Belum Disetujui';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating persetujuan:', error);
            });
        }

        document.getElementById('disposisiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
            
            // Validasi form
            const disposisi = document.getElementById('disposisi').value;
            const subDisposisi = document.getElementById('sub_disposisi').value;
            const subDisposisiContainer = document.getElementById('subDisposisiContainer');
            const tanggalDisposisi = document.getElementById('tanggal_disposisi').value;
            
            // Validasi sub disposisi jika container terlihat
            if (subDisposisiContainer && subDisposisiContainer.style.display !== 'none' && !subDisposisi) {
                Swal.fire({
                    title: 'Validasi Gagal',
                    text: 'Silakan pilih sub disposisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Validasi tanggal
            if (!tanggalDisposisi) {
                Swal.fire({
                    title: 'Validasi Gagal',
                    text: 'Silakan isi tanggal disposisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Disable button dan tampilkan loading
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            }
            
            // Submit form
            form.submit();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        const bsAlert = new bootstrap.Alert(alert);
                        if (bsAlert) {
                            bsAlert.close();
                        }
                    });
                }, 5000); // 5 seconds
            }

            const disposisiSelect = document.getElementById('disposisi');
        });
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // This script block is no longer needed for DataTables initialization
            // as the table is now rendered directly.
        });
    </script>

    <style>
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
    </style>
    
@endsection