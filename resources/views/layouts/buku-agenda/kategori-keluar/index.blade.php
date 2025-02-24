@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4"><strong>📂 Buku Agenda</strong> / <span style="color: gray;"> Kategori Surat Keluar</span></h2>
            </div>
        </div>

        <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <!-- Nav tabs -->
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

                <div class="tab-content">
                    <!-- Tab Surat Masuk -->
                    <div class="tab-pane fade {{ request('tab', 'surat-keluar') == 'surat-keluar' ? 'show active' : '' }}" id="surat-keluar">
                        <h4>📤  Surat Keluar</h4>
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.kategori-keluar.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="surat-keluar">
                                <label for="waktuSuratKeluar" class="me-2">Filter Waktu:</label>
                                <select name="waktuSuratKeluar" id="waktuSuratKeluar" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSuratKeluar', 'bulan') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="minggu" {{ request('waktuSuratKeluar') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="tahun" {{ request('waktuSuratKeluar') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $surat->tanggal_surat }}
                                            </td>
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
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.kategori-keluar.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="sppd-dalam-daerah">
                                <label for="waktuSppdDalamDaerah" class="me-2">Filter Waktu:</label>
                                <select name="waktuSppdDalamDaerah" id="waktuSppdDalamDaerah" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSppdDalamDaerah') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="minggu" {{ request('waktuSppdDalamDaerah') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="tahun" {{ request('waktuSppdDalamDaerah') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : 'Tanggal tidak tersedia' }}
                                            </td>
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
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.kategori-keluar.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="sppd-luar-daerah">
                                <label for="waktuSppdLuarDaerah" class="me-2">Filter Waktu:</label>
                                <select name="waktuSppdLuarDaerah" id="waktuSppdLuarDaerah" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSppdLuarDaerah') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="minggu" {{ request('waktuSppdLuarDaerah') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="tahun" {{ request('waktuSppdLuarDaerah') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : 'Tanggal tidak tersedia' }}
                                            </td>
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
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.kategori-keluar.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="spt-dalam-daerah">
                                <label for="waktuSptDalamDaerah" class="me-2">Filter Waktu:</label>
                                <select name="waktuSptDalamDaerah" id="waktuSptDalamDaerah" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSptDalamDaerah') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="minggu" {{ request('waktuSptDalamDaerah') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="tahun" {{ request('waktuSptDalamDaerah') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : 'Tanggal tidak tersedia' }}
                                            </td>
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
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.kategori-keluar.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="spt-luar-daerah">
                                <label for="waktuSptLuarDaerah" class="me-2">Filter Waktu:</label>
                                <select name="waktuSptLuarDaerah" id="waktuSptLuarDaerah" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSptLuarDaerah') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="minggu" {{ request('waktuSptLuarDaerah') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="tahun" {{ request('waktuSptLuarDaerah') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : 'Tanggal tidak tersedia' }}
                                            </td>
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
@endsection
