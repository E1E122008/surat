@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-exchange-alt me-1"></i> Arsip Surat</span>
@endsection

@section('content')
    <style>
        /* ── Search focus ── */
        .arsip-search-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15) !important;
            outline: none;
        }

        /* ── Tabs scrollable on mobile ── */
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

        /* ── Table ── */
        .arsip-table {
            min-width: 620px;
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

        /* ── Badge ── */
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

        /* ── Mobile badge full-width ── */
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

        .lampiran-lock {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            font-size: 12.5px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
        }
    </style>

    <div style="max-width: 1400px; margin: auto; padding: 20px;">

        {{-- Page Header --}}
        <div class="mb-3">
            <h2
                style="display:flex; align-items:center; gap:10px; font-size:1.5rem; font-weight:700; color:#0d1b4b; margin:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                    stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                </svg>
                <strong>Arsip &amp; Riwayat Surat</strong>
            </h2>
        </div>


        <div class="bg-white shadow-sm" style="border-radius:12px;">
            <div class="p-4">

                {{-- ─── TOOLBAR ─── --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3 pb-3"
                    style="border-bottom:1px solid rgba(0,0,0,.06);">

                    {{-- Left: label (desktop) + search --}}
                    <div class="d-flex flex-column flex-md-row gap-2 flex-grow-1 align-items-md-center">
                        <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                            style="font-size:11px; color:#94a3b8; letter-spacing:1px;">ARSIP SURAT</span>

                        <form method="GET" action="{{ route('buku-agenda.index') }}" class="d-flex flex-grow-1 m-0"
                            style="max-width:480px;">
                            <div class="position-relative w-100">
                                <i class="fas fa-search position-absolute text-muted"
                                    style="top:50%; left:15px; transform:translateY(-50%); font-size:.9rem; z-index:2;"></i>
                                <input type="text" name="search" placeholder="Cari di semua arsip..."
                                    class="form-control w-100 arsip-search-input"
                                    style="padding-left:42px; border-radius:30px; height:42px; font-size:.93rem; font-weight:500; border:1px solid #cbd5e1;"
                                    value="{{ request('search') }}">
                            </div>
                            <input type="hidden" name="tab" value="{{ request('tab', 'surat-masuk') }}">
                            @if (request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </form>
                    </div>

                    {{-- Right: Sort, Filter, Export (dropdown on mobile), Badge --}}
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

                        {{-- Export Dropdown (single button, both formats) --}}
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
                                        href="{{ route('buku-agenda.export', ['filterType' => request('filterType'), 'mingguKe' => request('mingguKe'), 'bulan' => request('bulan'), 'tahun' => request('tahun'), 'tab' => request('tab', 'surat-masuk')]) }}">
                                        <i class="fas fa-file-excel me-2 text-success"></i>Export Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('buku-agenda.export-pdf', ['filterType' => request('filterType'), 'mingguKe' => request('mingguKe'), 'bulan' => request('bulan'), 'tahun' => request('tahun'), 'tab' => request('tab', 'surat-masuk')]) }}">
                                        <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Badge count --}}
                        @php
                            $activeTab = request('tab', 'surat-masuk');
                            $jumlahLabel = match ($activeTab) {
                                'surat-keputusan' => 'Jumlah SK: ' . ($totalSurat['sk'] ?? 0),
                                'perda' => 'Jumlah PERDA: ' . ($totalSurat['perda'] ?? 0),
                                'pergub' => 'Jumlah PERGUB: ' . ($totalSurat['pergub'] ?? 0),
                                default => 'Jumlah Surat: ' . ($totalSurat['surat_masuk'] ?? 0),
                            };
                        @endphp
                        <span class="surat-badge d-inline-flex arsip-count-badge">
                            <i class="fas fa-layer-group"></i> {{ $jumlahLabel }}
                        </span>
                    </div>
                </div>

                {{-- Filter active banner --}}
                @if ($filterInfo)
                    <div class="alert alert-info d-flex align-items-center justify-content-between mb-3"
                        style="border-radius:10px;">
                        <span><i class="fas fa-filter me-2"></i>Filter Aktif: {{ $filterInfo }}</span>
                        <a href="{{ request()->url() }}?tab={{ request('tab', 'surat-masuk') }}"
                            class="text-decoration-none text-danger">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                @endif

                {{-- ─── TABS ─── --}}
                <div class="arsip-tabs-wrap mb-3">
                    <ul class="nav nav-tabs flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.index', ['tab' => 'surat-masuk', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-envelope-open-text me-1"></i> Surat Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'surat-keputusan' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.index', ['tab' => 'surat-keputusan', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-file-alt me-1"></i> Surat Keputusan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'perda' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.index', ['tab' => 'perda', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-scroll me-1"></i> Peraturan Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'pergub' ? 'active' : '' }}"
                                href="{{ route('buku-agenda.index', ['tab' => 'pergub', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-landmark me-1"></i> Peraturan Gubernur
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- ─── TAB CONTENT ─── --}}
                <div class="tab-content">

                    {{-- ══ TAB: SURAT MASUK ══ --}}
                    <div class="tab-pane fade {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'show active' : '' }}"
                        id="surat-masuk">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th>PENGIRIM</th>
                                        <th class="text-center" style="width:130px;">TANGGAL TERIMA</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suratMasuk as $index => $surat)
                                        @php
                                            $lmp = is_array($surat->lampiran)
                                                ? $surat->lampiran
                                                : json_decode($surat->lampiran, true);
                                            $lmpData = [];
                                            if ($lmp && count($lmp) > 0) {
                                                foreach ($lmp as $f) {
                                                    if (is_string($f)) {
                                                        $f = ['path' => $f, 'name' => basename($f)];
                                                    }
                                                    $parts = explode('/', $f['path'] ?? '');
                                                    $encoded = array_map('rawurlencode', $parts);
                                                    $lmpData[] = [
                                                        'name' => $f['name'],
                                                        'url' => asset('storage/' . implode('/', $encoded)),
                                                    ];
                                                }
                                            }
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => $surat->no_agenda,
                                                    'tanggal' => $surat->tanggal_terima
                                                        ? $surat->tanggal_terima->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->pengirim,
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->disposisi ?: '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td>{{ $surat->pengirim }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                                            </td>
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
                                            <td colspan="6" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data surat masuk</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $suratMasuk->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                    {{-- ══ TAB: SURAT KEPUTUSAN ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'surat-keputusan' ? 'show active' : '' }}"
                        id="surat-keputusan">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. SURAT</th>
                                        <th>PENGIRIM</th>
                                        <th class="text-center" style="width:130px;">TANGGAL TERIMA</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sks as $index => $surat)
                                        @php
                                            $lmp = is_array($surat->lampiran)
                                                ? $surat->lampiran
                                                : json_decode($surat->lampiran, true);
                                            $lmpData = [];
                                            if ($lmp && count($lmp) > 0) {
                                                foreach ($lmp as $f) {
                                                    if (is_string($f)) {
                                                        $f = ['path' => $f, 'name' => basename($f)];
                                                    }
                                                    $parts = explode('/', $f['path'] ?? '');
                                                    $encoded = array_map('rawurlencode', $parts);
                                                    $lmpData[] = [
                                                        'name' => $f['name'],
                                                        'url' => asset('storage/' . implode('/', $encoded)),
                                                    ];
                                                }
                                            }
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => $surat->no_agenda,
                                                    'tanggal' => $surat->tanggal_terima
                                                        ? $surat->tanggal_terima->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->pengirim,
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->disposisi ?: '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td>{{ $surat->pengirim }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                                            </td>
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
                                            <td colspan="6" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data Surat Keputusan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $sks->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                    {{-- ══ TAB: PERDA ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'perda' ? 'show active' : '' }}" id="perda">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. PERDA</th>
                                        <th>PENGIRIM</th>
                                        <th class="text-center" style="width:130px;">TANGGAL TERIMA</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($perda as $index => $surat)
                                        @php
                                            $lmp = is_array($surat->lampiran)
                                                ? $surat->lampiran
                                                : json_decode($surat->lampiran, true);
                                            $lmpData = [];
                                            if ($lmp && count($lmp) > 0) {
                                                foreach ($lmp as $f) {
                                                    if (is_string($f)) {
                                                        $f = ['path' => $f, 'name' => basename($f)];
                                                    }
                                                    $parts = explode('/', $f['path'] ?? '');
                                                    $encoded = array_map('rawurlencode', $parts);
                                                    $lmpData[] = [
                                                        'name' => $f['name'],
                                                        'url' => asset('storage/' . implode('/', $encoded)),
                                                    ];
                                                }
                                            }
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => $surat->no_agenda,
                                                    'tanggal' => $surat->tanggal_terima
                                                        ? $surat->tanggal_terima->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->pengirim,
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->disposisi ?: '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td>{{ $surat->pengirim }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                                            </td>
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
                                            <td colspan="6" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data Peraturan Daerah</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $perda->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                    {{-- ══ TAB: PERGUB ══ --}}
                    <div class="tab-pane fade {{ request('tab') == 'pergub' ? 'show active' : '' }}" id="pergub">
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                            <table class="table arsip-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:48px;">NO</th>
                                        <th>NO. PERGUB</th>
                                        <th>PENGIRIM</th>
                                        <th class="text-center" style="width:130px;">TANGGAL TERIMA</th>
                                        <th>PERIHAL</th>
                                        <th class="text-center" style="width:100px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pergub as $index => $surat)
                                        @php
                                            $lmp = is_array($surat->lampiran)
                                                ? $surat->lampiran
                                                : json_decode($surat->lampiran, true);
                                            $lmpData = [];
                                            if ($lmp && count($lmp) > 0) {
                                                foreach ($lmp as $f) {
                                                    if (is_string($f)) {
                                                        $f = ['path' => $f, 'name' => basename($f)];
                                                    }
                                                    $parts = explode('/', $f['path'] ?? '');
                                                    $encoded = array_map('rawurlencode', $parts);
                                                    $lmpData[] = [
                                                        'name' => $f['name'],
                                                        'url' => asset('storage/' . implode('/', $encoded)),
                                                    ];
                                                }
                                            }
                                            $det = json_encode(
                                                [
                                                    'no_surat' => $surat->no_surat,
                                                    'no_agenda' => $surat->no_agenda,
                                                    'tanggal' => $surat->tanggal_terima
                                                        ? $surat->tanggal_terima->format('d/m/Y')
                                                        : '-',
                                                    'pengirim' => $surat->pengirim,
                                                    'perihal' => $surat->perihal,
                                                    'disposisi' => $surat->disposisi ?: '-',
                                                    'isAdmin' => true,
                                                    'lampiran' => $lmpData,
                                                ],
                                                JSON_HEX_QUOT | JSON_HEX_APOS,
                                            );
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                                            <td>{{ $surat->pengirim }}</td>
                                            <td class="text-center" style="white-space:nowrap;">
                                                {{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                                            </td>
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
                                            <td colspan="6" class="text-center py-4 text-muted"><i
                                                    class="fas fa-inbox me-2"></i>Belum ada data Peraturan Gubernur</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $pergub->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
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
                    <input type="hidden" name="tab" value="{{ request('tab', 'surat-masuk') }}">
                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-weight:600;">Filter Berdasarkan</label>
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
                        <a href="{{ route('buku-agenda.index', ['tab' => request('tab')]) }}"
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

            <p class="detail-field-label">No. Agenda</p>
            <p id="det-agenda" class="detail-field-value"></p>

            <p class="detail-field-label">Pengirim</p>
            <p id="det-pengirim" class="detail-field-value"></p>

            <p class="detail-field-label">Perihal</p>
            <p id="det-perihal" class="detail-field-value"></p>

            <p class="detail-field-label">Disposisi</p>
            <p id="det-disposisi" class="detail-field-value"></p>

            <p class="detail-field-label">Lampiran</p>
            <div id="det-lampiran-wrap"></div>
        </div>
    </div>

    <script>
        // ── Detail panel ──
        function openDetail(data) {
            document.getElementById('det-header').textContent = (data.no_surat || '-') + ' • ' + (data.tanggal || '-');
            document.getElementById('det-agenda').textContent = data.no_agenda || '-';
            document.getElementById('det-pengirim').textContent = data.pengirim || '-';
            document.getElementById('det-perihal').textContent = data.perihal || '-';
            document.getElementById('det-disposisi').textContent = data.disposisi || '-';

            const lampWrap = document.getElementById('det-lampiran-wrap');
            lampWrap.innerHTML = '';
            if (data.isAdmin) {
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
            } else {
                lampWrap.innerHTML = `<div class="lampiran-lock">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Lampiran hanya dapat diakses oleh Admin Sistem
            </div>`;
            }
            document.getElementById('arsip-detail-backdrop').classList.add('active');
        }

        function closeDetail() {
            document.getElementById('arsip-detail-backdrop').classList.remove('active');
        }

        // ── Filter modal logic ──
        document.addEventListener('DOMContentLoaded', function() {
            // Close detail on backdrop click
            const bd = document.getElementById('arsip-detail-backdrop');
            if (bd) bd.addEventListener('click', function(e) {
                if (e.target === bd) closeDetail();
            });

            // Filter sub-sections
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

            // Restore from URL
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
