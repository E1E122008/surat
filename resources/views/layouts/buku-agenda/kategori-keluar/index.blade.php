@extends('layouts.app')

@section('content')
    <style>
        .alert-info {
            border-left: 5px solid #0dcaf0;
        }

        .filter-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .filter-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-badge {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stats-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }

        .clear-filter {
            color: #dc3545;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .clear-filter:hover {
            background-color: #dc3545;
            color: white;
        }

        .filter-icon {
            color: #0dcaf0;
            margin-right: 0.5rem;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4"><strong>📂 Buku Agenda</strong> / <span style="color: gray;"> Kategori Surat Keluar</span></h2>
            </div>
        </div>

        <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'surat-keluar') == 'surat-keluar' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'surat-keluar']) }}">
                                Surat Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-dalam-daerah' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sppd-dalam-daerah']) }}">
                                SPPD Dalam Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-luar-daerah' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sppd-luar-daerah']) }}">
                                SPPD Luar Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-dalam-daerah' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'spt-dalam-daerah']) }}">
                                SPT Dalam Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-luar-daerah' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'spt-luar-daerah']) }}">
                                SPT Luar Daerah
                            </a>
                        </li>
                    </ul>
                </div>

                @if($filterInfo)
                <div class="alert alert-info mt-3">
                    <i class="fas fa-filter"></i> Filter Aktif: {{ $filterInfo }}
                    <a href="{{ request()->url() }}?tab={{ request('tab', 'surat-keluar') }}" class="float-end text-decoration-none">
                        <i class="fas fa-times"></i> Hapus Filter
                    </a>
                </div>
                @endif

                <div class="tab-content">
                    <!-- Tab Surat Masuk -->
                    <div class="tab-pane fade {{ request('tab', 'surat-keluar') == 'surat-keluar' ? 'show active' : '' }}" id="surat-keluar">
                        <h4>📤  Surat Keluar</h4>
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <span class="stats-badge">
                                <i class="fas fa-envelope me-1"></i>
                                Jumlah Surat: {{ $totalSurat['surat_keluar'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($suratKeluar as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Sppd Dalam Daerah -->
                    <div class="tab-pane fade {{ request('tab') == 'sppd-dalam-daerah' ? 'show active' : '' }}" id="sppd-dalam-daerah">
                        <h4>📤 SPPD Dalam Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPPD: {{ $totalSurat['sppd_dalam'] }}
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tujuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama yang di Tugaskan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sppdDalamDaerah as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tujuan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->nama_petugas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <div class="tab-pane fade {{ request('tab') == 'sppd-luar-daerah' ? 'show active' : '' }}" id="sppd-luar-daerah">
                        <h4>📤 SPPD Luar Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPPD: {{ $totalSurat['sppd_luar'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tujuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama yang di Tugaskan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sppdLuarDaerah as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tujuan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->nama_petugas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ request('tab') == 'spt-dalam-daerah' ? 'show active' : '' }}" id="spt-dalam-daerah">
                        <h4>📤 SPT Dalam Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPT: {{ $totalSurat['spt_dalam'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tujuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama yang di Tugaskan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                            </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sptDalamDaerah as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tujuan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->nama_petugas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="tab-pane fade {{ request('tab') == 'spt-luar-daerah' ? 'show active' : '' }}" id="spt-luar-daerah">
                        <h4>📤 SPT Luar Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>  
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPT: {{ $totalSurat['spt_luar'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tujuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama yang di Tugaskan</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sptLuarDaerah as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tujuan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->nama_petugas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- End tab-content -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="filterForm" method="GET">
                    <input type="hidden" name="tab" value="{{ request('tab', 'surat-keluar') }}">
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="filterType" class="form-label">Filter Berdasarkan</label>
                            <select class="form-select" id="filterType" name="filterType" required>
                                <option value="minggu">Minggu</option>
                                <option value="bulan">Bulan</option>
                                <option value="tahun">Tahun</option>
                            </select>
                        </div>

                        <div class="mb-3" id="mingguSubpoint">
                            <label for="mingguKe" class="form-label">Pilih Minggu</label>
                            <select class="form-select mb-2" name="mingguKe">
                                <option value="1">Minggu ke 1</option>
                                <option value="2">Minggu ke 2</option>
                                <option value="3">Minggu ke 3</option>
                                <option value="4">Minggu ke 4</option>
                            </select>
                        </div>
                        <div class="mb-3" id="bulanSubpoint" style="display: none;">
                            <label for="bulan" class="form-label">Pilih Bulan</label>
                            <select class="form-select mb-2" name="bulan">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                            
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="number" class="form-control" name="tahun" 
                                   min="2000" max="2099" value="{{ date('Y') }}">
                        </div>

                        <!-- Subpoint Tahun -->
                        <div class="mb-3" id="tahunSubpoint" style="display: none;">
                            <label for="tahun" class="form-label">Masukkan Tahun</label>
                            <input type="number" class="form-control" name="tahun" min="2000" max="2099" 
                                   value="{{ date('Y') }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => request('tab')]) }}" 
                           class="btn btn-warning">Tampilkan Semua</a>
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterType = document.getElementById('filterType');
        const mingguSubpoint = document.getElementById('mingguSubpoint');
        const bulanSubpoint = document.getElementById('bulanSubpoint');
        const tahunSubpoint = document.getElementById('tahunSubpoint');

        function showDefaultSubpoint() {
            mingguSubpoint.style.display = 'none';
            bulanSubpoint.style.display = 'none';
            tahunSubpoint.style.display = 'none';

            switch(filterType.value) {
                case 'minggu':
                    mingguSubpoint.style.display = 'block';
                    break;
                case 'bulan':
                    bulanSubpoint.style.display = 'block';
                    break;
                case 'tahun':
                    tahunSubpoint.style.display = 'block';
                    break;
            }
        }

        showDefaultSubpoint();
        filterType.addEventListener('change', showDefaultSubpoint);
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('filterType')) {
            filterType.value = urlParams.get('filterType');
            showDefaultSubpoint();

            if (urlParams.has('mingguKe')) {
                document.querySelector('[name="mingguKe"]').value = urlParams.get('mingguKe');
            }

            if (urlParams.has('bulan')) {
                document.querySelector('[name="bulan"]').value = urlParams.get('bulan');
            }   

            if (urlParams.has('tahun')) {
                document.querySelector('[name="tahun"]').value = urlParams.get('tahun');
            }
        }
    });
    </script>
@endsection
