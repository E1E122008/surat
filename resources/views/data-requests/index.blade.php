@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Surat</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#requestModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data Surat
        </button>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Data Surat</h6>
                    <div class="dropdown no-arrow">
                        <form action="{{ route('data-requests.index') }}" method="GET" class="d-flex">
                            <select name="status" 
                                    id="statusFilter" 
                                    class="form-control form-control-sm mr-2"
                                    onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   placeholder="Cari data surat..." 
                                   class="form-control form-control-sm mr-2"
                                   value="{{ request('search') }}">
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Jenis Surat</th>
                                    <th class="text-center">Pengirim</th>
                                    <th class="text-center">Tanggal Surat</th>
                                    <th class="text-center">Progres</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvalRequests as $request)
                                    <tr class="request-row" data-status="{{ $request->status }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
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
                                        <td class="text-center">{{ $request->sender }}</td>
                                        <td class="text-center">{{ $request->tanggal_surat ? $request->tanggal_surat->format('d M Y') : '-' }}</td>
                                        <td class="text-center">
                                            @if($request->status === 'pending')
                                                <span class="badge bg-warning">
                                                    Menunggu Review
                                                </span>
                                            @elseif($request->status === 'approved')
                                                <span class="badge bg-success">
                                                    Disetujui
                                                </span>
                                            @elseif($request->status === 'rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="aksiDropdown{{ $request->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="aksiDropdown{{ $request->id }}">
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal{{ $request->id }}" title="Detail">
                                                            <i class="fas fa-eye me-2"></i> Lihat Detail
                                                        </button>
                                                    </li>
                                                    @if($request->status === 'pending')
                                                    <li>
                                                        <form action="{{ route('data-requests.cancel', $request->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger" onclick="confirmCancelRequest(this)" title="Batal Kirim">
                                                                <i class="fas fa-times me-2"></i> Batal Kirim
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data surat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modals -->
@foreach($approvalRequests as $request)
<div class="modal fade" id="detailModal{{ $request->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $request->id }}">Detail Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Surat</label>
                            <p>{{ $letterTypes[$request->letter_type] ?? $request->letter_type }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. Surat</label>
                            <p>{{ $request->no_surat }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pengirim</label>
                            <p>{{ $request->sender }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Surat</label>
                            <p>{{ $request->tanggal_surat ? $request->tanggal_surat->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Perihal</label>
                            <p>{{ $request->perihal }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lampiran</label>
                            @if($request->lampiran)
                                <p>
                                    <a href="{{ asset('storage/' . $request->lampiran) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-paperclip"></i> Lihat Lampiran
                                    </a>
                                </p>
                            @else
                                <p>-</p>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan</label>
                            <p>{{ $request->notes ?: '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i> Menunggu Review
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i> Disetujui
                                    </span>
                                @elseif($request->status === 'rejected')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i> Ditolak
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah Data Surat -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Tambah Data Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('data-requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="letter_type" class="form-label">Jenis Surat</label>
                            <select class="form-select" id="letter_type" name="letter_type" required>
                                <option value="">Pilih Jenis Surat</option>
                                <option value="surat_masuk">Surat Masuk</option>
                                <option value="sk">SK</option>
                                <option value="perda">PERDA</option>
                                <option value="pergub">PERGUB</option>

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_surat" class="form-label">No. Surat</label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sender" class="form-label">Pengirim</label>
                            <input type="text" class="form-control" id="sender" name="sender" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="perihal" class="form-label">Perihal</label>
                        <input type="text" class="form-control" id="perihal" name="perihal" required>
                    </div>
                    <div class="mb-3">
                        <label for="lampiran" class="form-label">Lampiran</label>
                        <input type="file" class="form-control" id="lampiran" name="lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG</small>
                        @error('lampiran')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Data surat akan direview oleh admin. Anda akan mendapatkan notifikasi setelah data disetujui atau ditolak.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('statusFilter');
        const form = searchInput.closest('form');

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Submit form after typing stops for 500ms
        searchInput.addEventListener('input', debounce(function() {
            form.submit();
        }, 500));
    });

    // SweetAlert for cancel request confirmation
    function confirmCancelRequest(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Permintaan yang dibatalkan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Tidak, jangan batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        })
    }
</script>
@endpush
@endsection