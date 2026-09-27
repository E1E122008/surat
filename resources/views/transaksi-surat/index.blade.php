@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-exchange-alt me-1"></i> Arsip Surat</span>
@endsection

@section('content')
    <div style="max-width: 1400px; margin: auto; padding: 20px;">

        {{-- Page Header --}}
        <div class="mb-3">
            <h2 class="header h2 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="me-2 text-primary" style="vertical-align: middle;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <strong>Arsip &amp; Riwayat Surat</strong>
            </h2>
        </div>

        <div class="bg-white shadow-sm" style="border-radius: 12px;">
            <div class="p-4">

                {{-- ─── TOOLBAR ─── --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3 pb-3"
                    style="border-bottom: 1px solid rgba(0,0,0,0.06);">

                    {{-- Label (desktop only) --}}
                    <span class="d-none d-md-block text-uppercase fw-bold flex-shrink-0"
                        style="font-size: 11px; color: #94a3b8; letter-spacing: 1px;">ARSIP SURAT</span>

                    {{-- Search (cross-tab) --}}
                    <form method="GET" action="{{ route('transaksi-surat.index') }}" class="d-flex flex-grow-1 m-0"
                        style="max-width: 480px;">
                        <div class="position-relative w-100">
                            <i class="fas fa-search position-absolute text-muted"
                                style="top:50%; left:15px; transform:translateY(-50%); font-size:.9rem; z-index:2;"></i>
                            <input type="text" name="search" placeholder="Cari di semua arsip..."
                                class="form-control w-100 arsip-search-input"
                                style="padding-left:42px; border-radius:30px; height:42px; font-size:.93rem; font-weight:500; border:1px solid #cbd5e1;"
                                value="{{ request('search') }}">
                        </div>
                        <input type="hidden" name="tab" value="{{ request('tab', 'surat-masuk') }}">
                    </form>

                    {{-- Right-side controls --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        {{-- Urutkan --}}
                        @php $sortOrder = request('sort', 'desc'); @endphp
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle text-nowrap" type="button"
                                data-bs-toggle="dropdown"
                                style="border-radius:30px; height:42px; padding:0 18px; font-weight:500; border-color:#cbd5e1;">
                                <i class="fas fa-sort-amount-{{ $sortOrder == 'desc' ? 'down' : 'up' }} me-1"></i>
                                Urutkan
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

                        {{-- Badge jumlah – desktop inline, mobile full-width row via CSS --}}
                        @php
                            $activeTab = request('tab', 'surat-masuk');
                            $jumlahLabel = match ($activeTab) {
                                'sk' => 'Jumlah SK: ' . ($totalSK ?? 0),
                                'perda' => 'Jumlah PERDA: ' . ($totalPerda ?? 0),
                                'pergub' => 'Jumlah PERGUB: ' . ($totalPergub ?? 0),
                                default => 'Jumlah Surat: ' . ($totalSuratMasuk ?? 0),
                            };
                        @endphp
                        <span class="surat-badge surat-badge-sm d-inline-flex align-items-center arsip-count-badge">
                            <i class="fas fa-layer-group me-1"></i> {{ $jumlahLabel }}
                        </span>
                    </div>
                </div>

                {{-- ─── TABS (scrollable on mobile) ─── --}}
                <div class="arsip-tabs-wrap mb-3">
                    <ul class="nav nav-tabs flex-nowrap" style="border-bottom:1px solid #dee2e6; white-space:nowrap;">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'active' : '' }}"
                                href="{{ route('transaksi-surat.index', ['tab' => 'surat-masuk', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-envelope-open-text me-1"></i> Surat Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sk' ? 'active' : '' }}"
                                href="{{ route('transaksi-surat.index', ['tab' => 'sk', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-file-alt me-1"></i> Surat Keputusan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'perda' ? 'active' : '' }}"
                                href="{{ route('transaksi-surat.index', ['tab' => 'perda', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-scroll me-1"></i> Peraturan Daerah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'pergub' ? 'active' : '' }}"
                                href="{{ route('transaksi-surat.index', ['tab' => 'pergub', 'sort' => request('sort'), 'search' => request('search')]) }}">
                                <i class="fas fa-landmark me-1"></i> Peraturan Gubernur
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- ─── TAB CONTENT ─── --}}
                <div class="tab-content">
                    @include('transaksi-surat.partials.surat-masuk')
                    @include('transaksi-surat.partials.sk')
                    @include('transaksi-surat.partials.perda')
                    @include('transaksi-surat.partials.pergub')
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Search focus state */
        .arsip-search-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
            outline: none;
        }

        /* Scrollable tabs on mobile */
        .arsip-tabs-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .arsip-tabs-wrap::-webkit-scrollbar {
            display: none;
        }

        /* Badge responsive: full-width on mobile */
        @media (max-width: 767.98px) {
            .arsip-count-badge {
                width: 100%;
                justify-content: center;
            }
        }

        /* Surat-badge style (shared) */
        .surat-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%);
            color: #fff;
            font-weight: 500;
            border-radius: 2rem;
            padding: .3rem 1rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(91, 126, 241, .08);
            gap: .5rem;
        }

        .surat-badge-sm {
            font-size: .9rem;
            padding: .2rem .85rem;
        }

        /* Table improvements */
        .arsip-table {
            min-width: 650px;
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
        }

        .arsip-table tbody td {
            padding: 12px 14px;
            font-size: .875rem;
            vertical-align: middle;
        }

        .arsip-table .perihal-cell {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Detail Modal */
        .detail-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1050;
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
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile: full-screen slide-up */
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

    <script>
        // Detail modal logic (shared across all tabs)
        function openDetail(data) {
            const bd = document.getElementById('arsip-detail-backdrop');
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
                        lampWrap.innerHTML += `<div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <span style="font-size:.875rem;"><i class="fas fa-file me-2 text-primary"></i>${f.name}</span>
                            <a href="${f.url}" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius:20px;font-size:.8rem;">
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
            bd.classList.add('active');
        }

        function closeDetail() {
            document.getElementById('arsip-detail-backdrop').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var bd = document.getElementById('arsip-detail-backdrop');
            if (bd) {
                bd.addEventListener('click', function(e) {
                    if (e.target === bd) closeDetail();
                });
            }
        });
    </script>

    {{-- ── SHARED DETAIL MODAL (single DOM element, data injected via JS) ── --}}
    <div class="detail-modal-backdrop" id="arsip-detail-backdrop">
        <div class="detail-modal-box">
            <button class="detail-close-btn" onclick="closeDetail()" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
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
@endsection
