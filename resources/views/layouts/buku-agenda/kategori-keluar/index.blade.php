@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f3f4f6 !important;
            margin: 0;
            padding: 0;
        }

        .text-truncate-custom {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .perihal-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .disposisi-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .text-truncate-custom:hover, 
        .perihal-truncate:hover, 
        .disposisi-truncate:hover {
            white-space: normal;
            overflow: visible;
            position: relative;
            z-index: 1;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 4px 8px;
            border-radius: 4px;
            max-width: 400px;
        }

        .nav-tabs .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border: none;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #4a69bd;
            border-bottom: 2px solid #4a69bd;
            background: none;
        }

        .table thead tr {
            background-color: #4a69bd !important;
            color: white;
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
            color: #333;
        }

        .table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:last-child {
            border-bottom: 2px solid #000;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }

        .stats-badge {
            background: linear-gradient(45deg, #4a69bd, #6c8aee);
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

        .alert {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

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
            <div class="col-md-9">
                <h2 class="mb-4"><strong>📂 Arsip</strong> / <span style="color: gray;"> Arsip Surat Keluar</span></h2>
            </div>
            <div class="col-md-3">
                <form action="{{ route('buku-agenda.kategori-keluar.index') }}" method="GET" class="d-flex">
                    <input type="text" 
                           name="search" 
                           placeholder="Cari arsip..." 
                           class="form-control me-2"
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <!-- Menyimpan tab yang aktif -->
                    <input type="hidden" name="tab" value="{{ request('tab', 'surat-keluar') }}">
                </form>
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
                            <a class="nav-link {{ request('tab') == 'sppd-dalam' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sppd-dalam']) }}">
                                SPPD Dalam Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-luar' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sppd-luar']) }}">
                                SPPD Luar Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-dalam' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'spt-dalam']) }}">
                                SPT Dalam Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-luar' ? 'active' : '' }}"
                               href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'spt-luar']) }}">
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
                            <a href="{{ route('buku-agenda.kategori-keluar.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => request('tab', 'surat-keluar')
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.kategori-keluar.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => request('tab', 'surat-keluar')
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-envelope me-1"></i>
                                Jumlah Surat: {{ $totalSurat['surat_keluar'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-bordered mt-4">
                                <thead class="thead-dark">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="perihal-truncate" title="{{ $surat->perihal }}">
                                                    {{ $surat->perihal }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab SPPD Dalam Daerah -->
                    <div class="tab-pane fade {{ request('tab') == 'sppd-dalam' ? 'show active' : '' }}" id="sppd-dalam">
                        <h4>📄 SPPD Dalam Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.kategori-keluar.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'sppd-dalam'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.kategori-keluar.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'sppd-dalam'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPPD: {{ $totalSurat['sppd_dalam'] ?? 0 }}
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-bordered mt-4">
                                <thead class="thead-dark">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="perihal-truncate" title="{{ $surat->perihal }}">
                                                    {{ $surat->perihal }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <div class="tab-pane fade {{ request('tab') == 'sppd-luar' ? 'show active' : '' }}" id="sppd-luar">
                        <h4>📄 SPPD Luar Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.kategori-keluar.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'sppd-luar'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.kategori-keluar.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'sppd-luar'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPPD: {{ $totalSurat['sppd_luar'] ?? 0 }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-bordered mt-4">
                                <thead class="thead-dark">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="perihal-truncate" title="{{ $surat->perihal }}">
                                                    {{ $surat->perihal }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ request('tab') == 'spt-dalam' ? 'show active' : '' }}" id="spt-dalam">
                        <h4>📤 SPT Dalam Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('buku-agenda.kategori-keluar.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'spt-dalam'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.kategori-keluar.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'spt-dalam'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPT: {{ $totalSurat['spt_dalam'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-bordered mt-4">
                                <thead class="thead-dark">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="perihal-truncate" title="{{ $surat->perihal }}">
                                                    {{ $surat->perihal }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 text-center">
                                                <a href="{{ asset('storage/' . $surat->lampiran) }}" class="text-blue-500 hover:underline">{{ $surat->lampiran }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="tab-pane fade {{ request('tab') == 'spt-luar' ? 'show active' : '' }}" id="spt-luar">
                        <h4>📤 SPT Luar Daerah</h4>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i> Filter
                            </button>  
                            <a href="{{ route('buku-agenda.kategori-keluar.export', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'spt-luar'
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('buku-agenda.kategori-keluar.export-pdf', [
                                'filterType' => request('filterType'),
                                'mingguKe' => request('mingguKe'),
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                                'tab' => 'spt-luar'
                            ]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <span class="stats-badge">
                                <i class="fas fa-file-alt me-1"></i>
                                Jumlah SPT: {{ $totalSurat['spt_luar'] }}
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-bordered mt-4">
                                <thead class="thead-dark">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <span class="perihal-truncate" title="{{ $surat->perihal }}">
                                                    {{ $surat->perihal }}
                                                </span>
                                            </td>
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
