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
                            @if(auth()->user()->role !== 'monitor')
                                <a href="{{ route('draft-phd.sk.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah SK
                                </a>
                                <a href="{{ route('draft-phd.sk.export') }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            @endif
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
                                            $disposisiParts = explode('|', $item->disposisi);
                                            $persetujuanKetua = null;
                                            $tujuanDisposisi = null;
                                            $subDisposisi = null;
                                            $tanggalDisposisi = null;
                                            $catatan = null;
                                            $otherParts = [];

                                            // Pisahkan status persetujuan dan bagian lainnya
                                            foreach($disposisiParts as $index => $part) {
                                                $trimmedPart = trim($part);
                                                if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i', $trimmedPart)) {
                                                    $persetujuanKetua = $trimmedPart;
                                                } elseif (strpos($trimmedPart, 'Persetujuan Ketua Biro Hukum:') !== false) {
                                                    // Fallback format lama
                                                    $persetujuanKetua = $trimmedPart;
                                                } elseif (strpos($trimmedPart, 'Diteruskan ke:') !== false) {
                                                    // Extract sub disposisi
                                                    $subDisposisi = trim(str_replace('Diteruskan ke:', '', $trimmedPart));
                                                } elseif (strpos($trimmedPart, 'Tanggal:') !== false) {
                                                    // Extract tanggal disposisi
                                                    $tanggalDisposisi = trim(str_replace('Tanggal:', '', $trimmedPart));
                                                } elseif (strpos($trimmedPart, 'Catatan:') !== false) {
                                                    // Extract catatan
                                                    $catatan = trim(str_replace('Catatan:', '', $trimmedPart));
                                                } elseif ($index === 0 && !$tujuanDisposisi) {
                                                    $tujuanDisposisi = $trimmedPart;
                                                } else {
                                                    $otherParts[] = $trimmedPart;
                                                }
                                            }

                                            // Jika tujuan belum terisi, ambil dari otherParts
                                            if (!$tujuanDisposisi && count($otherParts) > 0) {
                                                $tujuanDisposisi = $otherParts[0];
                                                $otherParts = array_slice($otherParts, 1);
                                            }
                                        @endphp
                                        <div class="text-center">
                                            {{-- Status Persetujuan --}}
                                            @if($persetujuanKetua)
                                                <div class="mb-2">
                                                    <span class="badge {{ (stripos($persetujuanKetua, 'Sudah') !== false) ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $persetujuanKetua }}
                                                    </span>
                                                </div>
                                            @endif

                                            {{-- Tujuan Disposisi Utama --}}
                                            @if($tujuanDisposisi)
                                                <div class="mb-1">
                                                    <strong>{{ $tujuanDisposisi }}</strong>
                                                </div>
                                            @endif

                                            {{-- Tampilkan Diteruskan ke --}}
                                            @if($subDisposisi)
                                                <div class="mb-1 text-sm">
                                                    <strong>Diteruskan ke:</strong> {{ $subDisposisi }}
                                                </div>
                                            @endif

                                            {{-- Tampilkan Tanggal --}}
                                            @if($tanggalDisposisi)
                                                <div class="mb-1 text-sm">
                                                    <strong>Tanggal:</strong> {{ $tanggalDisposisi }}
                                                </div>
                                            @endif

                                            {{-- Tampilkan Catatan --}}
                                            @if($catatan)
                                                <div class="mb-1 text-sm">
                                                    <strong>Catatan:</strong> {{ $catatan }}
                                                </div>
                                            @endif

                                            {{-- Informasi Lainnya (fallback untuk data lama) --}}
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
                                                @if(auth()->user()->role !== 'monitor')
                                                    <li>
                                                        <button 
                                                            class="dropdown-item" 
                                                            type="button" 
                                                            data-surat-id="{{ $item->id }}"
                                                            data-persetujuan="{{ isset($persetujuanKetua) && $persetujuanKetua ? $persetujuanKetua : 'Belum' }}"
                                                            data-tujuan="{{ isset($tujuanDisposisi) && $tujuanDisposisi ? htmlspecialchars($tujuanDisposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                            data-sub-disposisi="{{ isset($subDisposisi) && $subDisposisi ? htmlspecialchars($subDisposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                            data-tanggal="{{ isset($tanggalDisposisi) && $tanggalDisposisi ? htmlspecialchars($tanggalDisposisi, ENT_QUOTES, 'UTF-8') : '' }}"
                                                            data-catatan="{{ isset($catatan) && $catatan ? htmlspecialchars($catatan, ENT_QUOTES, 'UTF-8') : '' }}"
                                                            onclick="openDisposisiModal({{ $item->id }}, this)">
                                                            <i class="fas fa-sync-alt fa-fw me-2 text-warning"></i>Disposisi
                                                        </button>
                                                    </li>
                                                    <li><button class="dropdown-item" type="button" onclick="openStatusModal({{ $item->id }}, '{{ $item->status }}')"><i class="fas fa-check-circle fa-fw me-2 text-success"></i>Status</button></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('draft-phd.sk.detail', $item->id) }}"><i class="fas fa-eye fa-fw me-2 text-primary"></i>Detail</a></li>
                                                @if(auth()->user()->role !== 'monitor')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $item->id }})">
                                                            <i class="fas fa-trash-alt fa-fw me-2"></i>Hapus
                                                        </button>
                                                    </li>
                                                @endif
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
                        <!-- STATUS PERSETUJUAN KETUA BIRO HUKUM -->
                        <div class="mb-3">
                            <label class="form-label"><strong>Status Persetujuan Ketua Biro Hukum:</strong></label>
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <div id="radioPersetujuanGroupSk" class="mb-2">
                                    <input class="form-check-input" type="radio" name="persetujuan_ketua" id="radioSkDisetujui" value="Sudah">
                                    <label class="form-check-label me-3" for="radioSkDisetujui">Sudah Disetujui</label>
                                    <input class="form-check-input" type="radio" name="persetujuan_ketua" id="radioSkBelum" value="Belum" checked>
                                    <label class="form-check-label" for="radioSkBelum">Belum Disetujui</label>
                                </div>
                            @else
                                <span id="statusPersetujuanSk" class="badge bg-secondary"></span>
                            @endif
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
                        <input type="hidden" id="disposisiSuratIdSk" name="disposisiSuratId" />
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
        ]
        // Catatan: Kasubag Tata Usaha tidak memiliki sub disposisi
    };

    // Tambahkan fungsi untuk set tanggal otomatis
    function setDefaultDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_disposisi').value = today;
    }

    function openDisposisiModal(id, buttonElement) {
        // Validasi parameter
        if (!id) {
            console.error('SK - ID tidak valid');
            return;
        }
        
        const form = document.getElementById('disposisiForm');
        if (!form) {
            console.error('SK - Form tidak ditemukan');
            return;
        }
        
        form.action = `/draft-phd/sk/${id}/disposisi`;
        const suratIdInput = document.getElementById('disposisiSuratIdSk');
        if (suratIdInput) {
            suratIdInput.value = id;
        }
        
        // Reset form terlebih dahulu
        form.reset();
        const subDisposisiContainer = document.getElementById('subDisposisiContainer');
        if (subDisposisiContainer) {
            subDisposisiContainer.style.display = 'none';
        }
        
        // Ambil data dari data attribute button (tanpa API)
        const persetujuan = buttonElement && buttonElement.getAttribute ? buttonElement.getAttribute('data-persetujuan') : 'Belum';
        const tujuan = buttonElement && buttonElement.getAttribute ? buttonElement.getAttribute('data-tujuan') : '';
        const subDisposisi = buttonElement && buttonElement.getAttribute ? buttonElement.getAttribute('data-sub-disposisi') : '';
        const tanggal = buttonElement && buttonElement.getAttribute ? buttonElement.getAttribute('data-tanggal') : '';
        const catatan = buttonElement && buttonElement.getAttribute ? buttonElement.getAttribute('data-catatan') : '';
        
        console.log('SK - Data from button attributes:', {
            persetujuan,
            tujuan,
            subDisposisi,
            tanggal,
            catatan
        });
        
        let isAdmin = false;
        @if(auth()->user() && auth()->user()->role === 'admin')
            isAdmin = true;
        @endif
        
        // 1. Set Status Persetujuan Ketua Biro Hukum
        // Normalisasi persetujuan
        let persetujuanValue = 'Belum';
        if (persetujuan) {
            if (persetujuan.toLowerCase().includes('sudah')) {
                persetujuanValue = 'Sudah';
            } else if (persetujuan.toLowerCase().includes('belum')) {
                persetujuanValue = 'Belum';
            } else {
                persetujuanValue = persetujuan;
            }
        }
        
        let status = persetujuanValue === 'Sudah' ? 'Sudah Disetujui' : 'Belum Disetujui';
        console.log('SK - Setting persetujuan:', persetujuanValue, '->', status);
        
        if (isAdmin) {
            const radioDisetujui = document.getElementById('radioSkDisetujui');
            const radioBelum = document.getElementById('radioSkBelum');
            if (radioDisetujui && radioBelum) {
                if (persetujuanValue === 'Sudah') {
                    radioDisetujui.checked = true;
                    radioBelum.checked = false;
                    console.log('SK - Radio Sudah checked');
                } else {
                    radioDisetujui.checked = false;
                    radioBelum.checked = true;
                    console.log('SK - Radio Belum checked');
                }
            }
        } else {
            const statusEl = document.getElementById('statusPersetujuanSk');
            if (statusEl) {
                statusEl.innerText = status;
            }
        }
        
        // 2. Set Tujuan Disposisi
        if (tujuan) {
            const disposisiSelect = document.getElementById('disposisi');
            if (disposisiSelect) {
                const tujuanValue = tujuan.trim();
                console.log('SK - Setting tujuan disposisi:', tujuanValue);
                
                // Cari option yang cocok
                let found = false;
                for (let i = 0; i < disposisiSelect.options.length; i++) {
                    const option = disposisiSelect.options[i];
                    if (option.value === tujuanValue || option.textContent.trim() === tujuanValue) {
                        disposisiSelect.value = option.value;
                        found = true;
                        console.log('SK - Found matching option:', option.value);
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
                            console.log('SK - Found partial matching option:', option.value);
                            break;
                        }
                    }
                }
                
                // Trigger change event untuk menampilkan sub disposisi jika ada
                setTimeout(() => {
                    const changeEvent = new Event('change', { bubbles: true });
                    disposisiSelect.dispatchEvent(changeEvent);
                    
                    // 3. Set Diteruskan Kepada (sub_disposisi) setelah change event
                    // Jangan tampilkan sub disposisi jika tujuan adalah "Kasubag Tata Usaha"
                    const selectedTujuan = disposisiSelect.value;
                    if (selectedTujuan !== 'Kasubag Tata Usaha' && subDisposisi && subDisposisi.trim()) {
                        setTimeout(() => {
                            const subDisposisiContainer = document.getElementById('subDisposisiContainer');
                            const subDisposisiSelect = document.getElementById('sub_disposisi');
                            
                            console.log('SK - Setting sub_disposisi:', subDisposisi);
                            
                            if (subDisposisiContainer && subDisposisiSelect) {
                                subDisposisiContainer.style.display = 'block';
                                
                                // Cari option yang cocok
                                let found = false;
                                const subDisposisiValue = subDisposisi.trim();
                                
                                for (let i = 0; i < subDisposisiSelect.options.length; i++) {
                                    const option = subDisposisiSelect.options[i];
                                    if (option.value === subDisposisiValue || 
                                        option.textContent.trim() === subDisposisiValue ||
                                        option.value.includes(subDisposisiValue) ||
                                        option.textContent.includes(subDisposisiValue)) {
                                        subDisposisiSelect.value = option.value;
                                        found = true;
                                        console.log('SK - Found matching sub_disposisi option:', option.value);
                                        break;
                                    }
                                }
                                
                                if (!found && subDisposisiValue) {
                                    // Tambahkan option baru jika tidak ada
                                    const newOption = document.createElement('option');
                                    newOption.value = subDisposisiValue;
                                    newOption.textContent = subDisposisiValue;
                                    subDisposisiSelect.appendChild(newOption);
                                    subDisposisiSelect.value = subDisposisiValue;
                                    console.log('SK - Added new sub_disposisi option:', subDisposisiValue);
                                }
                                
                                // Jika tidak ada sub_disposisi yang ada, set default ke "Belum/Tidak diteruskan"
                                if (!subDisposisiValue && subDisposisiSelect.options.length > 1) {
                                    subDisposisiSelect.value = 'Belum/Tidak diteruskan';
                                }
                            }
                        }, 200);
                    } else if (selectedTujuan !== 'Kasubag Tata Usaha') {
                        // Jika tidak ada sub_disposisi yang ada, set default setelah change event
                        setTimeout(() => {
                            const subDisposisiContainer = document.getElementById('subDisposisiContainer');
                            const subDisposisiSelect = document.getElementById('sub_disposisi');
                            if (subDisposisiContainer && subDisposisiSelect && subDisposisiContainer.style.display === 'block') {
                                subDisposisiSelect.value = 'Belum/Tidak diteruskan';
                            }
                        }, 250);
                    } else if (selectedTujuan === 'Kasubag Tata Usaha') {
                        // Pastikan sub disposisi disembunyikan jika tujuan adalah Kasubag Tata Usaha
                        const subDisposisiContainer = document.getElementById('subDisposisiContainer');
                        if (subDisposisiContainer) {
                            subDisposisiContainer.style.display = 'none';
                        }
                    }
                }, 100);
            }
        }
        
        // 4. Set Tanggal Disposisi
        const tanggalInput = document.getElementById('tanggal_disposisi');
        if (tanggalInput) {
            if (tanggal && tanggal.trim()) {
                let tanggalValue = tanggal.trim();
                console.log('SK - Setting tanggal disposisi from data:', tanggalValue);
                
                // Jika format YYYY-MM-DD sudah benar, gunakan langsung
                if (tanggalValue.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    tanggalInput.value = tanggalValue;
                    console.log('SK - Tanggal set to (YYYY-MM-DD):', tanggalValue);
                } else {
                    // Coba parse format lain
                    const dateParts = tanggalValue.split(/[-\/]/);
                    if (dateParts.length === 3) {
                        let day, month, year;
                        
                        if (dateParts[2].length === 4) {
                            // Format DD/MM/YYYY atau MM/DD/YYYY
                            if (parseInt(dateParts[0]) > 12) {
                                day = dateParts[0].padStart(2, '0');
                                month = dateParts[1].padStart(2, '0');
                                year = dateParts[2];
                            } else {
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
                            day = dateParts[0].padStart(2, '0');
                            month = dateParts[1].padStart(2, '0');
                            year = dateParts[2];
                        }
                        
                        tanggalInput.value = `${year}-${month}-${day}`;
                        console.log('SK - Converted tanggal:', tanggalInput.value);
                    } else {
                        console.log('SK - Invalid tanggal format, using default');
                        setDefaultDate();
                    }
                }
            } else {
                console.log('SK - No tanggal data, using default');
                setDefaultDate();
            }
        }
        
        // 5. Set Catatan (setelah semua field lain di-set, termasuk setelah change event disposisi)
        setTimeout(() => {
            const catatanInput = document.getElementById('catatan');
            if (catatanInput) {
                if (catatan && catatan.trim()) {
                    catatanInput.value = catatan.trim();
                    console.log('SK - Setting catatan:', catatan.trim());
                } else {
                    catatanInput.value = '';
                    console.log('SK - No catatan data, clearing field');
                }
            } else {
                console.error('SK - Catatan input element not found');
            }
        }, 250);
        
        // Tampilkan modal
        setTimeout(() => {
            const modalElement = document.getElementById('disposisiModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                
                // Log final values setelah modal ditampilkan
                setTimeout(() => {
                    console.log('SK - Modal shown, final values:');
                    console.log('SK - Disposisi:', document.getElementById('disposisi')?.value);
                    console.log('SK - Tanggal:', document.getElementById('tanggal_disposisi')?.value);
                    console.log('SK - Catatan:', document.getElementById('catatan')?.value);
                }, 100);
            }
        }, 100);
    }

    // Event listener untuk perubahan disposisi
    document.getElementById('disposisi').addEventListener('change', function() {
        const selectedDisposisi = this.value;
        const subDisposisiContainer = document.getElementById('subDisposisiContainer');
        const subDisposisiSelect = document.getElementById('sub_disposisi');
        
        // Reset sub disposisi
        subDisposisiSelect.innerHTML = '<option value="">Pilih Tujuan</option>';
        
        // Kasubag Tata Usaha tidak memiliki sub disposisi
        if (selectedDisposisi === 'Kasubag Tata Usaha') {
            subDisposisiContainer.style.display = 'none';
            subDisposisiSelect.required = false;
        } else if (selectedDisposisi && subDisposisiOptions[selectedDisposisi]) {
            // Tampilkan container dan tambahkan opsi
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
            subDisposisiContainer.style.display = 'none';
            subDisposisiSelect.required = false;
        }
    });

    // Handle form submission
    document.getElementById('disposisiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
        
        const disposisi = document.getElementById('disposisi').value;
        const subDisposisi = document.getElementById('sub_disposisi').value;
        const subDisposisiContainer = document.getElementById('subDisposisiContainer');
        const tanggalDisposisi = document.getElementById('tanggal_disposisi').value;
        
        // Validasi sub disposisi jika container terlihat
        if (disposisi !== 'Kasubag Tata Usaha' && subDisposisiContainer && subDisposisiContainer.style.display !== 'none' && !subDisposisi) {
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