@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4"><strong>📂 Buku Agenda</strong> / <span style="color: gray;"> Kategori Surat Masuk</span></h2>
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

                <div class="tab-content">
                    <!-- Tab Surat Masuk -->
                    <div class="tab-pane fade {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'show active' : '' }}" id="surat-masuk">
                        <h4>📩 Surat Masuk</h4>
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="surat-masuk">
                                <label for="waktuSuratMasuk" class="me-2">Filter Waktu:</label>
                                <select name="waktuSuratMasuk" id="waktuSuratMasuk" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSuratMasuk') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                    <option value="minggu" {{ request('waktuSuratMasuk') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                                    <option value="tahun" {{ request('waktuSuratMasuk') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                </select>
                                <button class="btn btn-primary me-2" type="submit" style="width: 120px;">Terapkan</button>
                                
                            </form>
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
                        <h4>📤 Surat Keputusan</h4>
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="surat-keputusan">
                                <label for="waktuSuratKeputusan" class="me-2">Filter Waktu:</label>
                                <select name="waktuSuratKeputusan" id="waktuSuratKeputusan" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuSuratKeputusan') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                    <option value="minggu" {{ request('waktuSuratKeputusan') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                                    <option value="tahun" {{ request('waktuSuratKeputusan') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
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
                        <h4>📝 Peraturan Daerah</h4>
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="perda">
                                <label for="waktuPerda" class="me-2">Filter Waktu:</label>
                                <select name="waktuPerda" id="waktuPerda" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuPerda') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                    <option value="minggu" {{ request('waktuPerda') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                                    <option value="tahun" {{ request('waktuPerda') == 'tahun' ? 'selected' : '' }}>Tahun</option>   
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                        <h4>📤 Peraturan Gubernur</h4>
                        <div class="mb-3 d-flex align-items-center">
                            <form action="{{ route('buku-agenda.index') }}" method="GET" class="col-md-3 d-flex align-items-center">
                                <input type="hidden" name="tab" value="pergub">
                                <label for="waktuPergub" class="me-2">Filter Waktu:</label>
                                <select name="waktuPergub" id="waktuPergub" class="form-select me-3" style="width: auto;">
                                    <option value="bulan" {{ request('waktuPergub') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                                    <option value="minggu" {{ request('waktuPergub') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                                    <option value="tahun" {{ request('waktuPergub') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </form>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
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
@endsection
