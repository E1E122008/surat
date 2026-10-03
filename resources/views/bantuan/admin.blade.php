@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-inbox me-1"></i> Pelaporan</span>
@endsection

@section('content')
    <style>
        .surat-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%);
            color: #fff;
            font-weight: 500;
            border-radius: 2rem;
            padding: 0.3rem 1rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(91, 126, 241, 0.08);
            gap: 0.5rem;
        }

        .surat-badge-sm {
            font-size: 0.95rem;
            padding: 0.2rem 0.8rem;
        }

        .surat-badge i {
            font-size: 1em;
        }
    </style>
    <div class="mb-4">
        <h2 class="header h2"><i class="fas fa-inbox text-primary me-2"></i> <strong>Pelaporan & Bantuan Aplikasi</strong>
        </h2>
        <p class="text-muted mt-2" style="font-size: 0.95rem;">Daftar keluhan pengguna internal dan kendala akses tamu luar.
        </p>
    </div>

    <style>
        @media (min-width: 768px) {
            .fixed-action-width {
                width: 160px !important;
                flex: 0 0 160px !important;
            }
        }
    </style>

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
        <!-- STANDARDIZED ACTION BAR -->
        <div class="card-header py-3 bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3"
            style="border-bottom: 1px solid rgba(0,0,0,0.05);">
            <div class="d-flex flex-column flex-md-row w-100 align-items-stretch align-items-md-center flex-grow-1"
                style="gap: 12px;">
                <form method="GET" action="{{ route('bantuan.index') }}"
                    class="d-flex flex-column flex-md-row m-0 flex-grow-1" style="gap: 12px;">
                    <!-- SEARCH -->
                    <div class="position-relative flex-grow-1">
                        <i class="fas fa-search position-absolute text-muted"
                            style="top: 50%; left: 15px; transform: translateY(-50%); font-size: 1rem;"></i>
                        <input type="text" name="search" placeholder="Cari topik atau pengirim..."
                            class="form-control shadow-sm w-100 custom-search-input"
                            style="padding-left: 45px; border-radius: 30px; height: 42px; font-size: 0.95rem; font-weight: 500;"
                            value="{{ request('search') }}">
                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                    </div>
                    <!-- STATUS -->
                    <div class="d-flex justify-content-stretch fixed-action-width">
                        <select name="status" id="statusFilter" class="form-select shadow-sm w-100 custom-search-input"
                            style="border-radius: 30px; height: 42px; font-weight: 500;" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                                Respons
                            </option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Ditindak
                            </option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </form>

                <!-- SORT URUTKAN -->
                <div class="dropdown d-flex justify-content-stretch fixed-action-width">
                    <button class="btn btn-outline-secondary dropdown-toggle shadow-sm w-100 m-0 text-nowrap" type="button"
                        id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Urutkan"
                        style="border-radius: 30px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 500;">
                        <i class="fas fa-sort-amount-{{ $sortOrder == 'desc' ? 'down' : 'up' }} me-2"></i>
                        Urutkan
                    </button>
                    <ul class="dropdown-menu shadow" aria-labelledby="sortDropdown">
                        <li>
                            <a class="dropdown-item {{ $sortOrder == 'desc' ? 'active bg-primary text-white' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}">
                                Terbaru ke Terlama
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $sortOrder == 'asc' ? 'active bg-primary text-white' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}">
                                Terlama ke Terbaru
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-responsive px-4 pb-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.95rem; min-width: 800px;">
                <thead style="background-color: var(--navy-utama); color: white; white-space: nowrap;">
                    <tr>
                        <th class="py-3 px-4" style="width: 15%; white-space: nowrap;">TANGGAL DITERIMA</th>
                        <th class="py-3 px-4" style="min-width: 220px;">IDENTITAS PENGIRIM</th>
                        <th class="py-3 px-4" style="width: 1%; white-space: nowrap;">TOPIK KENDALA</th>
                        <th class="py-3 px-4" style="width: auto;">DESKRIPSI KELUHAN</th>
                        <th class="py-3 px-4 text-center" style="width: 10%; white-space: nowrap;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td class="py-3 px-4">
                                <div style="font-weight: 500;">{{ $req->created_at->format('d M Y') }}</div>
                                <div class="text-muted" style="font-size: 0.85rem;">{{ $req->created_at->format('H:i') }}
                                    WITA</div>
                            </td>
                            <td class="py-3 px-4">
                                <div style="font-weight: 700; color: var(--navy-utama);">{{ $req->nama_pengirim }}</div>
                                <div class="text-muted" style="font-size: 0.85rem;"><i class="fas fa-envelope me-1"></i>
                                    {{ $req->kontak }}</div>
                                <div class="mt-2">
                                    @if ($req->user_id)
                                        <span class="badge"
                                            style="background-color: rgba(56, 189, 248, 0.1); color: #0284c7; border: 1px solid #7dd3fc;">Internal
                                            User</span>
                                    @else
                                        <span class="badge"
                                            style="background-color: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid #fcd34d;">Tamu
                                            Luar (Terkunci)</span>
                                    @endif
                                    @if ($req->instansi)
                                        <span class="badge bg-secondary ms-1">{{ $req->instansi }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge bg-dark">{{ $req->kendala }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <p class="mb-0 text-truncate"
                                    style="max-width: 250px; cursor: pointer; color: var(--slate-700);"
                                    title="{{ $req->deskripsi }}">
                                    {{ $req->deskripsi }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button type="button"
                                    class="btn btn-sm shadow-sm font-weight-bold dropdown-toggle {{ $req->status == 'menunggu' ? 'text-danger' : ($req->status == 'diproses' ? 'text-warning text-dark' : 'text-success') }}"
                                    style="background-color: {{ $req->status == 'menunggu' ? 'rgba(239, 68, 68, 0.1)' : ($req->status == 'diproses' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(34, 197, 94, 0.1)') }};"
                                    data-bs-toggle="modal" data-bs-target="#updateModal{{ $req->id }}">
                                    @if ($req->status == 'menunggu')
                                        Menunggu Respons
                                    @elseif($req->status == 'diproses')
                                        Sedang Ditindak
                                    @else
                                        Selesai
                                    @endif
                                </button>


                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: #cbd5e1;"></i>
                                <p class="mb-0">Belum ada pelaporan masuk saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 mb-2 d-flex justify-content-center">
            {{ $requests->links('pagination::bootstrap-4') }}
        </div>
        <div class="mb-4 d-flex justify-content-center">
            <span class="surat-badge surat-badge-sm d-inline-flex">
                <i class="fas fa-file-alt me-2"></i> Jumlah Laporan:
                {{ method_exists($requests, 'total') ? $requests->total() : $requests->count() }}
            </span>
        </div>
    </div>

    <!-- Modals are placed here entirely outside the table-responsive to prevent graphical cut-offs and z-index issues -->
    @foreach ($requests as $req)
        <div class="modal fade text-start" id="updateModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" style="font-weight: 700; color: var(--navy-utama);">
                            Detail Keluhan & Update Status</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('bantuan.status.update', $req->id) }}" method="POST">
                        @csrf
                        <div class="modal-body pt-0">
                            <div class="mb-4 p-3 bg-light rounded" style="border: 1px solid #e2e8f0;">
                                <label
                                    style="font-size: 0.85rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase;">Deskripsi
                                    Keluhan Lengkap</label>
                                <p class="mb-0 mt-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
                                    {{ $req->deskripsi }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: var(--slate-700);">Ubah
                                    Status</label>
                                <select name="status" class="form-select shadow-none">
                                    <option value="menunggu" {{ $req->status == 'menunggu' ? 'selected' : '' }}>Menunggu
                                        Respons</option>
                                    <option value="diproses" {{ $req->status == 'diproses' ? 'selected' : '' }}>Sedang
                                        Ditindak</option>
                                    <option value="selesai" {{ $req->status == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: var(--slate-700);">Pesan
                                    Balasan Pribadi (Opsional)</label>
                                <textarea name="balasan_admin" class="form-control" rows="3"
                                    placeholder="Tuliskan respon Admin di sini. User akan bisa membacanya di Riwayat Bantuan mereka..."
                                    style="background-color: #f8fafc;">{{ $req->balasan_admin }}</textarea>
                                <small class="text-muted">Gunakan balasan jika topik butuh
                                    pencerahan teknis spesifik ke user.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary shadow-sm"
                                style="background-color: var(--navy-utama); border-color: var(--navy-utama);">Simpan
                                & Beri Beritahu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

<style>
    .surat-badge {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(90deg, #5b7ef1 0%, #6ea8fe 100%);
        color: #fff;
        font-weight: 500;
        border-radius: 2rem;
        padding: 0.3rem 1rem;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(91, 126, 241, 0.08);
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
