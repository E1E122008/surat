@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-shield-alt me-1"></i> Data Persetujuan</span>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="header h2"><i class="fas fa-shield-alt text-primary me-2"></i> <strong>Daftar Data Persetujuan</strong>
            </h2>
        </div>
        <div class="row">
            <div class="col-12">
                <style>
                    @media (min-width: 768px) {
                        .fixed-action-width {
                            width: 160px !important;
                            flex: 0 0 160px !important;
                        }
                    }
                </style>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3"
                        style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <div class="d-flex flex-column flex-md-row w-100 align-items-stretch align-items-md-center flex-grow-1"
                            style="gap: 12px;">
                            <form method="GET" class="d-flex flex-column flex-md-row m-0 flex-grow-1" style="gap: 12px;">
                                <!-- PENCARIAN -->
                                <div class="position-relative flex-grow-1">
                                    <button type="submit" class="position-absolute border-0 bg-transparent text-muted"
                                        style="top: 50%; left: 15px; transform: translateY(-50%); z-index: 4;">
                                        <i class="fas fa-search" style="font-size: 1rem;"></i>
                                    </button>
                                    <input type="text" name="search" placeholder="Cari data surat..."
                                        class="form-control shadow-sm w-100 custom-search-input"
                                        style="padding-left: 45px; border-radius: 30px; height: 42px; font-size: 0.95rem; font-weight: 500;"
                                        value="{{ request('search') }}">
                                    @if (request('sort'))
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    @endif
                                </div>
                                <!-- STATUS -->
                                <div class="d-flex justify-content-stretch fixed-action-width">
                                    <select name="status" id="statusFilter"
                                        class="form-select shadow-sm w-100 custom-search-input"
                                        style="border-radius: 30px; height: 42px; font-weight: 500;"
                                        onchange="this.form.submit()">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua
                                            Status</option>
                                        <option value="pending_review"
                                            {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Review
                                        </option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                            Ditolak</option>
                                    </select>
                                </div>
                            </form>
                            <!-- SORTING -->
                            <div class="dropdown d-flex justify-content-stretch fixed-action-width">
                                <button class="btn btn-outline-secondary dropdown-toggle shadow-sm w-100 m-0 text-nowrap"
                                    type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    title="Urutkan"
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
                        <div class="d-none d-md-flex justify-content-center flex-shrink-0 fit-content-action">
                            <span
                                class="surat-badge surat-badge-sm w-100 d-inline-flex align-items-center justify-content-center m-0 px-3">
                                <i class="fas fa-envelope me-1"></i> Jumlah Permintaan:
                                {{ $totalFiltered < $totalAll ? $totalFiltered . ' (Total: ' . $totalAll . ')' : $totalAll }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            No</th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Jenis Surat</th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Pengirim</th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Dinas</th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Tanggal Permintaan</th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Status</th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Fisik
                                        </th>
                                        <th translate="no"
                                            class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvalRequests as $index => $request)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                {{ $index + 1 + ($approvalRequests->currentPage() - 1) * $approvalRequests->perPage() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                @php
                                                    $letterTypes = [
                                                        'surat_masuk' => 'Surat Masuk',
                                                        'sk' => 'SK',
                                                        'perda' => 'PERDA',
                                                        'pergub' => 'PERGUB',
                                                    ];
                                                @endphp
                                                {{ $letterTypes[$request->letter_type] ?? $request->letter_type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                {{ $request->sender }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                {{ $request->user->dinas ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                @if ($request->created_at)
                                                    {{ $request->created_at instanceof \Illuminate\Support\Carbon ? $request->created_at->format('d/m/Y') : \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                @if ($request->status === 'pending')
                                                    <span class="badge bg-warning">Menunggu Review</span>
                                                @elseif($request->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($request->status === 'rejected')
                                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>
                                                        Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                @if ($request->status === 'approved')
                                                    @if (in_array(Auth::user()->role, ['admin', 'superadmin']))
                                                        <span id="fisik-badge-{{ $request->id }}" style="cursor:pointer"
                                                            onclick="toggleFisik({{ $request->id }})"
                                                            title="Klik untuk toggle status penerimaan fisik">
                                                            @if ($request->fisik_diterima)
                                                                <span class="badge bg-success"><i class="fas fa-check"></i>
                                                                    Sudah</span>
                                                            @else
                                                                <span class="badge bg-secondary fisik-pulse">Belum</span>
                                                            @endif
                                                            <div class="fisik-hint-text">klik untuk ubah</div>
                                                        </span>
                                                    @else
                                                        <span id="fisik-badge-{{ $request->id }}"
                                                            title="Status penerimaan fisik">
                                                            @if ($request->fisik_diterima)
                                                                <span class="badge bg-success"><i
                                                                        class="fas fa-check"></i>
                                                                    Sudah</span>
                                                            @else
                                                                <span class="badge bg-secondary">Belum</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailRequestModal{{ $request->id }}"
                                                    title="Detail">
                                                    <i class="fas fa-eye me-2"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">Tidak ada permintaan persetujuan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (isset($approvalRequests) && method_exists($approvalRequests, 'links'))
                        <div class="d-flex flex-column justify-content-center w-100 px-4 py-3 border-top gap-3">
                            <div class="d-flex flex-wrap w-100 justify-content-between align-items-center">
                                <!-- DESKTOP PAGINATION -->
                                <nav aria-label="Page navigation" class="d-none d-md-flex ms-auto">
                                    <ul class="pagination mb-0">
                                        @if ($approvalRequests->onFirstPage())
                                            <li class="page-item disabled"><span class="page-link"><i
                                                        class="fas fa-chevron-left"></i> Sebelumnya</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $approvalRequests->previousPageUrl() }}" rel="prev"><i
                                                        class="fas fa-chevron-left"></i> Sebelumnya</a></li>
                                        @endif

                                        @php
                                            $currentPage = $approvalRequests->currentPage();
                                            $lastPage = $approvalRequests->lastPage();
                                            $startPage = max(1, $currentPage - 2);
                                            $endPage = min($lastPage, $currentPage + 2);
                                        @endphp

                                        @if ($startPage > 1)
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $approvalRequests->url(1) }}">1</a></li>
                                            @if ($startPage > 2)
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            @endif
                                        @endif

                                        @for ($page = $startPage; $page <= $endPage; $page++)
                                            @if ($page == $currentPage)
                                                <li class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $approvalRequests->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endfor

                                        @if ($endPage < $lastPage)
                                            @if ($endPage < $lastPage - 1)
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            @endif
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $approvalRequests->url($lastPage) }}">{{ $lastPage }}</a>
                                            </li>
                                        @endif

                                        @if ($approvalRequests->hasMorePages())
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $approvalRequests->nextPageUrl() }}"
                                                    rel="next">Selanjutnya <i class="fas fa-chevron-right"></i></a>
                                            </li>
                                        @else
                                            <li class="page-item disabled"><span class="page-link">Selanjutnya <i
                                                        class="fas fa-chevron-right"></i></span></li>
                                        @endif
                                    </ul>
                                </nav>

                                <!-- MOBILE PAGINATION -->
                                <div class="d-flex d-md-none justify-content-between align-items-center w-100 gap-2">
                                    @if ($approvalRequests->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm disabled"
                                            style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i
                                                class="fas fa-chevron-left"></i></button>
                                    @else
                                        <a href="{{ $approvalRequests->previousPageUrl() }}"
                                            class="btn btn-outline-secondary btn-sm"
                                            style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i
                                                class="fas fa-chevron-left"></i></a>
                                    @endif
                                    <span class="text-muted" style="font-size: 14px; font-weight: 500;">Halaman
                                        {{ $approvalRequests->currentPage() }} dari
                                        {{ $approvalRequests->lastPage() }}</span>
                                    @if ($approvalRequests->hasMorePages())
                                        <a href="{{ $approvalRequests->nextPageUrl() }}"
                                            class="btn btn-outline-secondary btn-sm"
                                            style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i
                                                class="fas fa-chevron-right"></i></a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm disabled"
                                            style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i
                                                class="fas fa-chevron-right"></i></button>
                                    @endif
                                </div>
                            </div>
                            <div class="w-100 text-center text-md-start mt-2 mt-md-0"
                                style="color: #94a3b8; font-size: 12px; font-weight: 500;">
                                Menampilkan {{ $approvalRequests->firstItem() ?? 0 }} sampai
                                {{ $approvalRequests->lastItem() ?? 0 }} dari {{ $approvalRequests->total() }} data
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Request Modal -->
    @foreach ($approvalRequests as $request)
        <div class="modal fade" id="detailRequestModal{{ $request->id }}" tabindex="-1"
            aria-labelledby="detailRequestModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailRequestModalLabel{{ $request->id }}">Detail Permintaan Tambah
                            Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama User</label>
                                    <p>{{ $request->user->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pengirim</label>
                                    <p>{{ $request->sender }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jenis Surat</label>
                                    @php
                                        $letterTypes = [
                                            'surat_masuk' => 'Surat Masuk',
                                            'sk' => 'SK',
                                            'perda' => 'PERDA',
                                            'pergub' => 'PERGUB',
                                        ];
                                    @endphp
                                    <p>{{ $letterTypes[$request->letter_type] ?? $request->letter_type }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Surat</label>
                                    <p>{{ $request->no_surat }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. HP</label>
                                    <p>{{ $request->no_hp }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Permintaan</label>
                                    <p>
                                        @if ($request->created_at)
                                            {{ $request->created_at instanceof \Illuminate\Support\Carbon ? $request->created_at->format('d M Y') : \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Surat</label>
                                    <p>
                                        @if ($request->tanggal_surat)
                                            {{ $request->tanggal_surat instanceof \Illuminate\Support\Carbon ? $request->tanggal_surat->format('d M Y') : \Carbon\Carbon::parse($request->tanggal_surat)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Perihal</label>
                                    <p>{{ $request->perihal }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Lampiran</label>
                                    @php
                                        $lampiran = $request->lampiran;
                                        if (is_string($lampiran)) {
                                            $lampiran = trim($lampiran);
                                            if ($lampiran === '' || $lampiran === 'null') {
                                                $lampiran = [];
                                            } else {
                                                $decoded = json_decode($lampiran, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $lampiran = $decoded;
                                                } else {
                                                    $lampiran = [['path' => $lampiran, 'name' => basename($lampiran)]];
                                                }
                                            }
                                        }
                                    @endphp
                                    @if ($lampiran && count($lampiran))
                                        <div class="row">
                                            @foreach ($lampiran as $file)
                                                @php
                                                    if (is_string($file)) {
                                                        $file = ['path' => $file, 'name' => basename($file)];
                                                    }
                                                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                                    $iconClass = 'fa-file-alt text-secondary';
                                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        $iconClass = 'fa-file-image text-info';
                                                    } elseif ($ext === 'pdf') {
                                                        $iconClass = 'fa-file-pdf text-danger';
                                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                                        $iconClass = 'fa-file-word text-primary';
                                                    }
                                                @endphp
                                                <div class="col-12 mb-2 d-flex align-items-center gap-2">
                                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                        class="fs-4 me-2" title="Lihat file">
                                                        <i class="fas {{ $iconClass }}"></i>
                                                    </a>
                                                    <span class="fw-bold small lampiran-filename"
                                                        title="{{ $file['name'] }}">
                                                        {{ $file['name'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Deskripsi (Catatan User)</label>
                                    <p>{{ $request->notes ?: '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Saat Ini</label>
                                    <p>
                                        @if ($request->status === 'pending')
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Menunggu
                                                Persetujuan</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>
                                                Disetujui</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ditolak</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Fisik</label>
                                    @if ($request->status === 'approved')
                                        <span id="fisik-status-{{ $request->id }}">
                                            @if ($request->fisik_diterima)
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Sudah
                                                    diterima</span>
                                                <div class="text-muted small">
                                                    Diterima pada:
                                                    @if ($request->fisik_diterima_at)
                                                        {{ $request->fisik_diterima_at instanceof \Illuminate\Support\Carbon ? $request->fisik_diterima_at->format('d M Y H:i') : \Carbon\Carbon::parse($request->fisik_diterima_at)->format('d M Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">Belum</span>
                                            @endif
                                        </span>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($request->status === 'pending')
                            @if (in_array(Auth::user()->role, ['admin', 'superadmin']))
                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                    data-bs-target="#approveModal{{ $request->id }}" data-bs-dismiss="modal">
                                    <i class="fas fa-check me-1"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $request->id }}" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Tolak
                                </button>
                            @else
                                <span class="text-muted me-2">Menunggu keputusan Admin.</span>
                            @endif
                        @else
                            <span class="text-muted me-2">Permintaan sudah diproses.</span>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Approve Modal -->
    @foreach ($approvalRequests as $request)
        <div class="modal fade " id="approveModal{{ $request->id }}" tabindex="-1"
            aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog bg-white shadow-lg shadow-lg rounded-2">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">Setujui Permintaan Data</h5>
                    </div>
                    <form action="{{ route('admin.approval-requests.approve', $request->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Permintaan dari: <strong>{{ $request->user->name }}</strong> ({{ $request->letter_type }})
                            </p>
                            <div class="mb-3">
                                <label for="no_agenda{{ $request->id }}" class="form-label">No. Agenda</label>
                                <input type="text" class="form-control" id="no_agenda{{ $request->id }}"
                                    name="no_agenda" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_diterima{{ $request->id }}" class="form-label">Tanggal
                                    Diterima</label>
                                <input type="date" class="form-control" id="tanggal_diterima{{ $request->id }}"
                                    name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}"
                                    required>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Reject Modal -->
    @foreach ($approvalRequests as $request)
        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1"
            aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">Tolak Permintaan Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.approval-requests.reject', $request->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Permintaan dari: <strong>{{ $request->user->name }}</strong> ({{ $request->letter_type }})
                            </p>
                            <div class="mb-3">
                                <label for="admin_notes{{ $request->id }}" class="form-label">Alasan Penolakan</label>
                                <textarea class="form-control" id="admin_notes{{ $request->id }}" name="admin_notes" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

<style>
    /* Fisik Badge Interaktif */
    @keyframes fisik-pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.55;
        }
    }

    .fisik-pulse {
        animation: fisik-pulse 2s ease-in-out infinite;
    }

    .fisik-hint-text {
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 2px;
        line-height: 1;
    }

    [id^="fisik-badge-"]:hover .badge {
        filter: brightness(1.18);
        transform: scale(1.08);
        transition: all 0.15s ease;
    }

    [id^="fisik-badge-"] .badge {
        transition: all 0.15s ease;
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

    .lampiran-filename {
        max-width: 250px;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    /* Table Styling */
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
        padding: 0.75rem;
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

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.25rem;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        color: #374151;
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .pagination .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
        color: #111827;
    }

    .pagination .page-item.active .page-link {
        background-color: #4a69bd;
        border-color: #4a69bd;
        color: white;
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        color: #9ca3af;
        background-color: #f9fafb;
        border-color: #e5e7eb;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-link i {
        font-size: 0.75rem;
    }
</style>

@push('scripts')
    <script>
        function konfirmasiFisik(id) {
            fetch('/admin/approval-requests/' + id + '/fisik-ajax', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('fisik-status-' + id).innerHTML =
                            `<span class='badge bg-success'><i class='fas fa-check'></i> Sudah diterima</span>
                <div class='text-muted small'>Diterima pada: ${data.fisik_diterima_at}</div>`;
                    }
                });
        }

        function toggleFisik(id) {
            fetch('/admin/approval-requests/' + id + '/toggle-fisik', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const wrapper = document.getElementById('fisik-badge-' + id);
                    let badgeHtml = '';
                    if (data.fisik_diterima) {
                        badgeHtml = `<span class='badge bg-success'><i class='fas fa-check'></i> Sudah</span>`;
                    } else {
                        badgeHtml = `<span class='badge bg-secondary fisik-pulse'>Belum</span>`;
                    }
                    wrapper.innerHTML = badgeHtml + `<div class='fisik-hint-text'>klik untuk ubah</div>`;

                    if (document.getElementById('fisik-status-' + id)) {
                        document.getElementById('fisik-status-' + id).innerHTML = badgeHtml +
                            (data.fisik_diterima_at ?
                                `<div class='text-muted small'>Diterima pada: ${data.fisik_diterima_at}</div>` : '');
                    }
                });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.auto-dismiss-alert').forEach(function(alert) {
                    // Bootstrap 5 way to close alert
                    if (window.bootstrap && bootstrap.Alert) {
                        var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    } else {
                        alert.style.display = 'none';
                    }
                });
            }, 5000);
        });
    </script>
@endpush
