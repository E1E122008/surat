@extends('layouts.app')

@section('content')
    <style>
        .stats-badge {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-left: 10px;
            display: inline-block;
        }

        .stats-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h2 class="mb-4"><strong>📂 Arsip</strong> / <span style="color: gray;"> Arsip Surat Masuk</span></h2>
            </div>
            <div class="col-md-3">
                <form action="{{ route('buku-agenda.index') }}" method="GET" class="d-flex">
                    <input type="text" 
                           name="search" 
                           placeholder="Cari arsip..." 
                           class="form-control me-2"
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <!-- Menyimpan tab yang aktif -->
                    <input type="hidden" name="tab" value="{{ request('tab', 'surat-masuk') }}">
                </form>
            </div>
        </div>

        <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.index', ['tab' => 'surat-masuk']) }}">
                                Surat Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'surat-keputusan' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.index', ['tab' => 'surat-keputusan']) }}">
                                Surat Keputusan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'perda' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.index', ['tab' => 'perda']) }}">
                                Peraturan Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'pergub' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.index', ['tab' => 'pergub']) }}">
                                Peraturan Gubernur
                            </a>
                        </li>
                        
                    </ul>
                </div>

                @if($filterInfo)
                <div class="alert alert-info mt-3">
                    <i class="fas fa-filter"></i> Filter Aktif: {{ $filterInfo }}
                    <a href="{{ request()->url() }}?tab={{ request('tab', 'surat-masuk') }}" class="float-end text-decoration-none">
                        <i class="fas fa-times"></i> Hapus Filter
                    </a>
                </div>
                @endif

                <div class="tab-content mt-3">
                    <!-- Tab Surat Masuk -->
                    <div class="tab-pane fade {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'show active' : '' }}" id="surat-masuk">
                        <h4>📥 Surat Masuk</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'surat-masuk'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'surat-masuk'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-envelope me-1"></i>
                                Jumlah Surat: {{ $totalSurat['surat_masuk'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-bordered mt-4">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No.Agenda</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal Terima</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Disposisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($suratMasuk as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_agenda }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->pengirim }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->disposisi }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Surat Keputusan -->
                    <div class="tab-pane fade {{ request('tab') == 'surat-keputusan' ? 'show active' : '' }}" id="surat-keputusan">
                        <h4>📄 Surat Keputusan</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'surat-keputusan'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'surat-keputusan'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SK: {{ $totalSurat['sk'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Agenda</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sk as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_agenda }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->pengirim }}</td>
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

                    <!-- Tab Perda -->
                    <div class="tab-pane fade {{ request('tab') == 'perda' ? 'show active' : '' }}" id="perda">
                        <h4>📋 Peraturan Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'perda'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'perda'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-book me-1"></i>
                                Jumlah Perda: {{ $totalSurat['perda'] }}
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200"> 
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Agenda</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>  
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($perda as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_agenda }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->pengirim }}</td>
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

                    <!-- Tab Pergub -->
                    <div class="tab-pane fade {{ request('tab') == 'pergub' ? 'show active' : '' }}" id="pergub">
                        <h4>📋 Peraturan Gubernur</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'pergub'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'pergub'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-scroll me-1"></i>
                                Jumlah Pergub: {{ $totalSurat['pergub'] }}
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="table-bordered">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Agenda</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No. Surat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>  
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pergub as $index => $surat)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_agenda }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->pengirim }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap break-words text-sm text-gray-500 text-center">{{ $surat->perihal }}</td>
                                            
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

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="filterForm" method="GET">
                    <!-- Hidden input untuk menyimpan tab yang sedang aktif -->
                    <input type="hidden" name="tab" value="{{ request('tab', 'surat-masuk') }}">
                    
                    <div class="modal-body">
                        <!-- Main Filter -->
                        <div class="mb-3">
                            <label for="filterType" class="form-label">Filter Berdasarkan</label>
                            <select class="form-select" id="filterType" name="filterType" required>
                                <option value="minggu">Minggu</option>
                                <option value="bulan">Bulan</option>
                                <option value="tahun">Tahun</option>
                            </select>
                        </div>

                        <!-- Subpoint Minggu -->
                        <div class="mb-3" id="mingguSubpoint" style="display: none;">
                            <label for="mingguKe" class="form-label">Pilih Minggu</label>
                            <select class="form-select mb-2" name="mingguKe">
                                <option value="1">Minggu ke 1</option>
                                <option value="2">Minggu ke 2</option>
                                <option value="3">Minggu ke 3</option>
                                <option value="4">Minggu ke 4</option>
                            </select>

                            
                        </div>

                        <!-- Subpoint Bulan -->
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
                        <a href="{{ route('buku-agenda.index', ['tab' => request('tab')]) }}" 
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

        const showDefaultSubpoint = () => {
            // Sembunyikan semua subpoint terlebih dahulu
            mingguSubpoint.style.display = 'none';
            bulanSubpoint.style.display = 'none';
            tahunSubpoint.style.display = 'none';

            // Tampilkan subpoint yang dipilih
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
        };

        // Tampilkan subpoint default saat halaman dimuat
        showDefaultSubpoint();

        // Handler untuk perubahan filter
        filterType.addEventListener('change', showDefaultSubpoint);

        // Set nilai awal dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('filterType')) {
            filterType.value = urlParams.get('filterType');
            showDefaultSubpoint();
            
            // Set nilai subpoint
            if (urlParams.has('mingguKe')) {
                document.querySelector('select[name="mingguKe"]').value = urlParams.get('mingguKe');
            }
            if (urlParams.has('bulan')) {
                document.querySelector('select[name="bulan"]').value = urlParams.get('bulan');
            }
            if (urlParams.has('tahun')) {
                document.querySelector('input[name="tahun"]').value = urlParams.get('tahun');
            }
        }
    });
    </script>
@endsection
