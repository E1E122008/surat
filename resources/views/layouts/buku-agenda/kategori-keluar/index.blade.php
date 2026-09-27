@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-paper-plane me-1"></i> Arsip Surat Keluar</span>
@endsection

@section('content')
    <style>
        .arsip-search-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15) !important;
            outline: none;
        }

        .arsip-tabs-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .arsip-tabs-wrap::-webkit-scrollbar {
            display: none;
        }

        .arsip-tabs-wrap ul {
            border-bottom: 1px solid #dee2e6;
            white-space: nowrap;
        }

        .nav-tabs .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: .75rem 1.1rem;
            border: none;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            background: none;
        }

        .arsip-table {
            min-width: 580px;
        }

        .arsip-table thead th {
            background: #0d1b4b;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding: 12px 14px;
            white-space: nowrap;
            border: none !important;
        }

        .arsip-table tbody td {
            padding: 12px 14px;
            font-size: .875rem;
            vertical-align: middle;
            border: none !important;
        }

        .arsip-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .arsip-table tbody tr:last-child {
            border-bottom: 2px solid #e2e8f0;
        }

        .arsip-table tbody tr:hover {
            background: #f9fafb;
        }

        .perihal-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .surat-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%);
            color: #fff;
            font-weight: 500;
            border-radius: 2rem;
            padding: .25rem .9rem;
            font-size: .88rem;
            box-shadow: 0 2px 8px rgba(91, 126, 241, .1);
        }

        @media (max-width: 767.98px) {
            .arsip-count-badge {
                width: 100%;
                justify-content: center;
            }
        }

        /* ── Detail Modal ── */
        .detail-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1055;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .detail-modal-backdrop.active {
            display: flex;
        }

        .detail-modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: modalFadeIn .2s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 767.98px) {
            .detail-modal-backdrop {
                align-items: flex-end;
            }

            .detail-modal-box {
                border-radius: 20px 20px 0 0;
                max-width: 100%;
                max-height: 88vh;
                padding: 24px 20px;
                animation: slideUp .25s ease;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                    opacity: .7;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        }

        .detail-close-btn {
            position: absolute;
            top: 14px;
            right: 16px;
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748b;
            transition: background .15s;
        }

        .detail-close-btn:hover {
            background: #e2e8f0;
        }

        .detail-field-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: .7px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .detail-field-value {
            font-size: .95rem;
            color: #1e293b;
            margin-bottom: 14px;
        }
    </style>

    <div style="max-width: 1400px; margin: auto; padding: 20px;">

        {{-- Page Header --}}
        <div class="mb-3">
            <h2
                style="display:flex; align-items:center; gap:10px; font-size:1.5rem; font-weight:700; color:#0d1b4b; margin:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                    stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <line x1="22" y1="2" x2="11" y2="13" />
                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                </svg>
                <strong>Arsip Surat Keluar</strong>
            </h2>
        </div>

        <div class="bg-white shadow-sm" style="border-radius:12px;">
            <div class="p-4">

                {{-- ─── TOOLBAR ─── --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3 pb-3"
                    style="border-bottom:1px solid rgba(0,0,0,.06);">

                    <div class="d-flex flex-column flex-md-row gap-2 flex-grow-1 align-items-md-center">
                        <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                            style="font-size:11px; color:#94a3b8; letter-spacing:1px;">ARSIP KELUAR</span>
                        <form method="GET" action="{{ route('buku-agenda.kategori-keluar.index') }}"
                            class="d-flex flex-grow-1 m-0" style="max-width:480px;">
                            <div class="position-relative w-100">
                                <i class="fas fa-search position-absolute text-muted"
                                    style="top:50%; left:15px; transform:translateY(-50%); font-size:.9rem; z-index:2;"></i>
                                <input type="text" name="search" placeholder="Cari di semua arsip keluar..."
                                    class="form-control w-100 arsip-search-input"
                                    style="padding-left:42px; border-radius:30px; height:42px; font-size:.93rem; font-weight:500; border:1px solid #cbd5e1;"
                                    value="{{ request('search') }}">
                            </div>
                            <input type="hidden" name="tab" value="{{ request('tab', 'surat-keluar') }}">
                            @if (request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </form>
                    </div>

                    @php $sortOrder = request('sort', 'asc'); @endphp
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        {{-- Sort --}}
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle text-nowrap" type="button"
                                data-bs-toggle="dropdown"
                                style="border-radius:30px; height:42px; padding:0 18px; font-weight:500; border-color:#cbd5e1;">
                                <i class="fas fa-sort-amount-{{ $sortOrder == 'desc' ? 'down' : 'up' }} me-1"></i> Urutkan
                            </button>
                            <ul class="dropdown-menu shadow">
                                <li><a class="dropdown-item {{ $sortOrder == 'desc' ? 'active bg-primary text-white' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}">Terbaru ke Terlama</a>
                                </li>
                                <li><a class="dropdown-item {{ $sortOrder == 'asc' ? 'active bg-primary text-white' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}">Terlama ke Terbaru</a>
                                </li>
                            </ul>
                        </div>

                        {{-- Filter --}}
                        <button type="button" class="btn btn-outline-primary text-nowrap" data-bs-toggle="modal"
                            data-bs-target="#filterModal"
                            style="border-radius:30px; height:42px; padding:0 18px; font-weight:500; border-color:#cbd5e1;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>

                        {{-- Export --}}
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle text-nowrap" type="button"
                                data-bs-toggle="dropdown"
                                style="border-radius:30px; height:42px; padding:0 18px; font-weight:600;">
                                <i class="fas fa-download me-1"></i>
                                <span class="d-none d-sm-inline">Ekspor</span>
                            </button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('buku-agenda.kategori-keluar.export', ['filterType' => request('filterType'), 'mingguKe' => request('mingguKe'), 'bulan' => request('bulan'), 'tahun' => request('tahun'), 'tab' => request('tab', 'surat-keluar')]) }}">
                                        <i class="fas fa-file-excel me-2 text-success"></i>Export Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('buku-agenda.kategori-keluar.export-pdf', ['filterType' => request('filterType'), 'mingguKe' => request('mingguKe'), 'bulan' => request('bulan'), 'tahun' => request('tahun'), 'tab' => request('tab', 'surat-keluar')]) }}">
                                        <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Badge --}}
                        @php
                            $activeTab = request('tab', 'surat-keluar');
                            $jumlahLabel = match ($activeTab) {
                                'sppd-dalam' => 'Jumlah SPPD: ' . ($totalSurat['sppd_dalam'] ?? 0),
                                'sppd-luar' => 'Jumlah SPPD: ' . ($totalSurat['sppd_luar'] ?? 0),
                                'spt-dalam' => 'Jumlah SPT: ' . ($totalSurat['spt_dalam'] ?? 0),
                                'spt-luar' => 'Jumlah SPT: ' . ($totalSurat['spt_luar'] ?? 0),
                                'sk-karo' => 'Jumlah SK: ' . ($totalSurat['sk_karo'] ?? 0),
                                default => 'Jumlah Surat: ' . ($totalSurat['surat_keluar'] ?? 0),
                            };
                        @endphp
                        <span class="surat-badge d-inline-flex arsip-count-badge">
                            <i class="fas fa-layer-group"></i> {{ $jumlahLabel }}
                        </span>
                    </div>
                </div>

                {{-- Filter banner --}}
                @if ($filterInfo)
                    <div class="alert alert-info d-flex align-items-center justify-content-between mb-3"
                        style="border-radius:10px;">
                        <span><i class="fas fa-filter me-2"></i>Filter Aktif: {{ $filterInfo }}</span>
                        <a href="{{ request()->url() }}?tab={{ request('tab', 'surat-keluar') }}"
                            class="text-decoration-none text-danger">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                @endif

                {{-- ─── TABS ─── --}}
                <div class="arsip-tabs-wrap mb-3">
                    <ul class="nav nav-tabs flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'surat-keluar') == 'surat-keluar' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'surat-keluar', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-paper-plane me-1"></i> Surat Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-dalam' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sppd-dalam', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-map-marker-alt me-1"></i> SPPD Dalam Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-luar' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sppd-luar', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-globe me-1"></i> SPPD Luar Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-dalam' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'spt-dalam', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-clipboard-list me-1"></i> SPT Dalam Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-luar' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'spt-luar', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-clipboard me-1"></i> SPT Luar Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sk-karo' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => 'sk-karo', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-file-signature me-1"></i> SK KARO
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- ─── TAB CONTENT ─── --}}
                <div class="tab-content">

                    {{-- ══ SURAT KELUAR ══ --}}
                    @php
                        function buildLmpData($surat)
                        {
                            $raw = is_array($surat->lampiran) ? $surat->lampiran : json_decode($surat->lampiran, true);
                            if (!$raw && is_string($surat->lampiran) && $surat->lampiran) {
                                $raw = [['path' => $surat->lampiran, 'name' => basename($surat->lampiran)]];
                            }
                            if (!$raw) {
                                return [];
                            }
                            $out = [];
                            foreach ($raw as $f) {
                                if (is_string($f)) {
                                    $f = ['path' => $f, 'name' => basename($f)];
                                }
                                $parts = explode('/', $f['path'] ?? '');
                                $out[] = [
                                    'name' => $f['name'],
                                    'url' => asset('storage/' . implode('/', array_map('rawurlencode', $parts))),
                                ];
                            }
                            return $out;
                        }
                    @endphp

                    <div class="tab-pane fade {{ request('tab', 'surat-keluar') == 'surat-keluar' ? 'show active' : '' }}"
                        id="surat-keluar">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th class="text-center" style="width:130px;">TANGGAL</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suratKeluar as $index => $surat)
                                        @php
                                            $lmpData = buildLmpData($surat);
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => '-',
                                                    'tanggal' => $surat->tanggal
                                                        ? $surat->tanggal->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => '-',
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td><span class="perihal-cell"
                                                    title="{{ $surat->perihal }}">{{ $surat->perihal }}</span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    style="border-radius:20px;font-size:.8rem;padding:5px 14px;border:1px solid #e2e8f0;"
                                                    onclick="openDetail({{ $det }})">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data surat keluar</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $suratKeluar->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>

                    {{-- ══ SPPD DALAM DAERAH ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'sppd-dalam' ? 'show active' : '' }}" id="sppd-dalam">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th class="text-center" style="width:130px;">TANGGAL</th>
                                        <th>TUJUAN</th>
                                        <th>NAMA PETUGAS</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sppdDalamDaerah as $index => $surat)
                                        @php
                                            $lmpData = buildLmpData($surat);
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => '-',
                                                    'tanggal' => $surat->tanggal
                                                        ? $surat->tanggal->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->tujuan ?? '-',
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->nama_petugas ?? '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $surat->tujuan ?? '-' }}</td>
                                            <td>{{ $surat->nama_petugas ?? '-' }}</td>
                                            <td><span class="perihal-cell"
                                                    title="{{ $surat->perihal }}">{{ $surat->perihal }}</span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    style="border-radius:20px;font-size:.8rem;padding:5px 14px;border:1px solid #e2e8f0;"
                                                    onclick="openDetail({{ $det }})">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data SPPD Dalam Daerah</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $sppdDalamDaerah->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>

                    {{-- ══ SPPD LUAR DAERAH ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'sppd-luar' ? 'show active' : '' }}" id="sppd-luar">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th class="text-center" style="width:130px;">TANGGAL</th>
                                        <th>TUJUAN</th>
                                        <th>NAMA PETUGAS</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sppdLuarDaerah as $index => $surat)
                                        @php
                                            $lmpData = buildLmpData($surat);
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => '-',
                                                    'tanggal' => $surat->tanggal
                                                        ? $surat->tanggal->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->tujuan ?? '-',
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->nama_petugas ?? '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $surat->tujuan ?? '-' }}</td>
                                            <td>{{ $surat->nama_petugas ?? '-' }}</td>
                                            <td><span class="perihal-cell"
                                                    title="{{ $surat->perihal }}">{{ $surat->perihal }}</span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    style="border-radius:20px;font-size:.8rem;padding:5px 14px;border:1px solid #e2e8f0;"
                                                    onclick="openDetail({{ $det }})">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data SPPD Luar Daerah</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $sppdLuarDaerah->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>

                    {{-- ══ SPT DALAM DAERAH ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'spt-dalam' ? 'show active' : '' }}" id="spt-dalam">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th class="text-center" style="width:130px;">TANGGAL</th>
                                        <th>TUJUAN</th>
                                        <th>NAMA PETUGAS</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sptDalamDaerah as $index => $surat)
                                        @php
                                            $lmpData = buildLmpData($surat);
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => '-',
                                                    'tanggal' => $surat->tanggal
                                                        ? $surat->tanggal->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->tujuan ?? '-',
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->nama_petugas ?? '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $surat->tujuan ?? '-' }}</td>
                                            <td>{{ $surat->nama_petugas ?? '-' }}</td>
                                            <td><span class="perihal-cell"
                                                    title="{{ $surat->perihal }}">{{ $surat->perihal }}</span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    style="border-radius:20px;font-size:.8rem;padding:5px 14px;border:1px solid #e2e8f0;"
                                                    onclick="openDetail({{ $det }})">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data SPT Dalam Daerah</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $sptDalamDaerah->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>

                    {{-- ══ SPT LUAR DAERAH ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'spt-luar' ? 'show active' : '' }}" id="spt-luar">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th class="text-center" style="width:130px;">TANGGAL</th>
                                        <th>TUJUAN</th>
                                        <th>NAMA PETUGAS</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sptLuarDaerah as $index => $surat)
                                        @php
                                            $lmpData = buildLmpData($surat);
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => '-',
                                                    'tanggal' => $surat->tanggal
                                                        ? $surat->tanggal->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->tujuan ?? '-',
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->nama_petugas ?? '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $surat->tujuan ?? '-' }}</td>
                                            <td>{{ $surat->nama_petugas ?? '-' }}</td>
                                            <td><span class="perihal-cell"
                                                    title="{{ $surat->perihal }}">{{ $surat->perihal }}</span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    style="border-radius:20px;font-size:.8rem;padding:5px 14px;border:1px solid #e2e8f0;"
                                                    onclick="openDetail({{ $det }})">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data SPT Luar Daerah</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $sptLuarDaerah->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>

                    {{-- ══ SK KARO ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'sk-karo' ? 'show active' : '' }}" id="sk-karo">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th class="text-center" style="width:130px;">TANGGAL</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($skKaro as $index => $surat)
                                        @php
                                            $lmpData = buildLmpData($surat);
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => '-',
                                                    'tanggal' => $surat->tanggal
                                                        ? $surat->tanggal->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => '-',
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td><span class="perihal-cell"
                                                    title="{{ $surat->perihal }}">{{ $surat->perihal }}</span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    style="border-radius:20px;font-size:.8rem;padding:5px 14px;border:1px solid #e2e8f0;"
                                                    onclick="openDetail({{ $det }})">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data SK KARO</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $skKaro->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>

                </div>{{-- end tab-content --}}
            </div>
        </div>
    </div>

    {{-- ─── FILTER MODAL ─── --}}
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:12px;">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" style="font-weight:700; color:#0d1b4b;">
                        <i class="fas fa-filter me-2 text-primary"></i>Filter Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="filterForm" method="GET">
                    <input type="hidden" name="tab" value="{{ request('tab', 'surat-keluar') }}">
                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;">Filter Berdasarkan</label>
                            <select class="form-select shadow-none" id="filterType" name="filterType">
                                <option value="minggu">Minggu</option>
                                <option value="bulan">Bulan</option>
                                <option value="tahun">Tahun</option>
                            </select>
                        </div>
                        <div class="mb-3" id="mingguSubpoint" style="display:none;">
                            <label class="form-label" style="font-weight:600;">Pilih Minggu</label>
                            <select class="form-select mb-2" name="mingguKe">
                                <option value="1">Minggu ke 1</option>
                                <option value="2">Minggu ke 2</option>
                                <option value="3">Minggu ke 3</option>
                                <option value="4">Minggu ke 4</option>
                            </select>
                        </div>
                        <div class="mb-3" id="bulanSubpoint" style="display:none;">
                            <label class="form-label" style="font-weight:600;">Pilih Bulan</label>
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
                            <label class="form-label mt-2" style="font-weight:600;">Tahun</label>
                            <input type="number" class="form-control" name="tahun" min="2000" max="2099"
                                value="{{ date('Y') }}">
                        </div>
                        <div class="mb-3" id="tahunSubpoint" style="display:none;">
                            <label class="form-label" style="font-weight:600;">Masukkan Tahun</label>
                            <input type="number" class="form-control" name="tahun" min="2000" max="2099"
                                value="{{ date('Y') }}">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('buku-agenda.kategori-keluar.index', ['tab' => request('tab')]) }}"
                            class="btn btn-warning shadow-sm">Tampilkan Semua</a>
                        <button type="submit" class="btn btn-primary shadow-sm">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── DETAIL PANEL ─── --}}
    <div class="detail-modal-backdrop" id="arsip-detail-backdrop">
        <div class="detail-modal-box">
            <button class="detail-close-btn" onclick="closeDetail()" aria-label="Tutup"><i
                    class="fas fa-times"></i></button>
            <p class="detail-field-label mb-1">Detail Surat</p>
            <h5 id="det-header" class="fw-bold mb-4" style="color:#0d1b4b; font-size:1.05rem; padding-right:36px;"></h5>

            <div id="det-tujuan-wrap" style="display:none;">
                <p class="detail-field-label">Tujuan</p>
                <p id="det-tujuan" class="detail-field-value"></p>
            </div>
            <div id="det-petugas-wrap" style="display:none;">
                <p class="detail-field-label">Nama Yang Ditugaskan</p>
                <p id="det-petugas" class="detail-field-value"></p>
            </div>

            <p class="detail-field-label">Perihal</p>
            <p id="det-perihal" class="detail-field-value"></p>

            <p class="detail-field-label">Lampiran</p>
            <div id="det-lampiran-wrap"></div>
        </div>
    </div>

    <script>
        function openDetail(data) {
            document.getElementById('det-header').textContent = (data.no_surat || '-') + ' • ' + (data.tanggal || '-');
            document.getElementById('det-perihal').textContent = data.perihal || '-';

            // Tujuan (mapped to 'pengirim' field for SPPD/SPT)
            const tujuanWrap = document.getElementById('det-tujuan-wrap');
            if (data.pengirim && data.pengirim !== '-') {
                document.getElementById('det-tujuan').textContent = data.pengirim;
                tujuanWrap.style.display = 'block';
            } else {
                tujuanWrap.style.display = 'none';
            }

            // Nama Petugas (mapped to 'disposisi' field for SPPD/SPT)
            const petugasWrap = document.getElementById('det-petugas-wrap');
            if (data.disposisi && data.disposisi !== '-') {
                document.getElementById('det-petugas').textContent = data.disposisi;
                petugasWrap.style.display = 'block';
            } else {
                petugasWrap.style.display = 'none';
            }

            const lampWrap = document.getElementById('det-lampiran-wrap');
            lampWrap.innerHTML = '';
            if (data.lampiran && data.lampiran.length > 0) {
                data.lampiran.forEach(function(f) {
                    const ext = f.name.split('.').pop().toLowerCase();
                    const iconMap = {
                        pdf: 'fa-file-pdf text-danger',
                        doc: 'fa-file-word text-primary',
                        docx: 'fa-file-word text-primary',
                        jpg: 'fa-file-image text-info',
                        jpeg: 'fa-file-image text-info',
                        png: 'fa-file-image text-info'
                    };
                    const icon = iconMap[ext] || 'fa-file-alt text-secondary';
                    lampWrap.innerHTML += `<div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:.875rem;"><i class="fas ${icon} me-2"></i>${f.name}</span>
                    <a href="${f.url}" target="_blank" class="btn btn-sm btn-outline-primary ms-3" style="border-radius:20px;font-size:.8rem;">
                        <i class="fas fa-download me-1"></i>Unduh
                    </a>
                </div>`;
                });
            } else {
                lampWrap.innerHTML = '<span class="text-muted" style="font-size:.875rem;">Tidak ada lampiran</span>';
            }

            document.getElementById('arsip-detail-backdrop').classList.add('active');
        }

        function closeDetail() {
            document.getElementById('arsip-detail-backdrop').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const bd = document.getElementById('arsip-detail-backdrop');
            if (bd) bd.addEventListener('click', function(e) {
                if (e.target === bd) closeDetail();
            });

            const filterType = document.getElementById('filterType');
            const mingguSub = document.getElementById('mingguSubpoint');
            const bulanSub = document.getElementById('bulanSubpoint');
            const tahunSub = document.getElementById('tahunSubpoint');

            function showFilterSub() {
                mingguSub.style.display = 'none';
                bulanSub.style.display = 'none';
                tahunSub.style.display = 'none';
                if (filterType.value === 'minggu') mingguSub.style.display = 'block';
                if (filterType.value === 'bulan') bulanSub.style.display = 'block';
                if (filterType.value === 'tahun') tahunSub.style.display = 'block';
            }
            showFilterSub();
            filterType.addEventListener('change', showFilterSub);

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('filterType')) {
                filterType.value = urlParams.get('filterType');
                showFilterSub();
                if (urlParams.has('mingguKe')) document.querySelector('select[name="mingguKe"]').value = urlParams
                    .get('mingguKe');
                if (urlParams.has('bulan')) document.querySelector('#bulanSubpoint select[name="bulan"]').value =
                    urlParams.get('bulan');
                if (urlParams.has('tahun')) document.querySelectorAll('input[name="tahun"]').forEach(el => el
                    .value = urlParams.get('tahun'));
            }
        });
    </script>
@endsection
