@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-envelope-open-text me-2"></i>Permintaan Tambah Surat</h1>
        
        
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="surat-badge surat-badge-sm d-inline-block align-middle">
                            <i class="fas fa-envelope"></i> Jumlah Surat: {{ $totalFiltered < $totalAll ? $totalFiltered . ' (Total: ' . $totalAll . ')' : $totalAll }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('data-requests.index') }}" method="GET" class="d-flex align-items-center gap-2 mb-0">
                            <select name="status" 
                                    id="statusFilter" 
                                    class="form-control form-control-sm"
                                    style="height: 38px; min-width: 130px; max-width: 150px;">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   placeholder="Cari data surat..." 
                                   class="form-control form-control-sm"
                                   style="height: 38px; min-width: 120px; max-width: 160px;"
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0;">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center justify-content-center ms-2" style="height: 38px; min-width: 180px; font-size: 0.95em; padding: 0 28px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#requestModal">
                                <i class="fas fa-plus fa-sm text-white me-2"></i>Tambah Data Surat
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
                                    <th class="text-center">Tanggal Surat</th>
                                    <th class="text-center">Status</th>
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
                                        <td class="text-center">{{ $request->tanggal_surat ? $request->tanggal_surat->format('d M Y') : '-' }}</td>
                                        <td class="text-center">
                                            @if($request->status === 'pending')
                                                <span class="badge bg-warning">
                                                    Menunggu Review
                                                </span>
                                            @elseif($request->status === 'approved')
                                                @if(!$request->fisik_diterima)
                                                    <span class="badge bg-info">
                                                        Menunggu Fisik Surat
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        Selesai
                                                    </span>
                                                @endif
                                            @elseif($request->status === 'rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#detailModal{{ $request->id }}" title="Detail">
                                                <i class="fas fa-eye me-2"></i> Detail
                                            </button>
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
                @if(isset($approvalRequests) && method_exists($approvalRequests, 'links'))
                    <div class="d-flex justify-content-center my-4">
                        <nav aria-label="Page navigation">
                            {{ $approvalRequests->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                @endif
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
                            <label class="form-label fw-bold">No. HP</label>
                            <p>{{ $request->no_hp }}</p>
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
                @if($request->status === 'approved' && !$request->fisik_diterima)
                    <div class="alert alert-success">
                        Surat Anda telah disetujui. Silakan bawa dokumen fisik ke kantor untuk proses lebih lanjut.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                @if($request->status === 'pending')
                    <form action="{{ route('data-requests.cancel', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" onclick="confirmCancelRequest(this)" title="Batal Kirim">
                            <i class="fas fa-times me-2"></i> Batal Kirim
                        </button>
                    </form>
                @endif
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
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="letter_type" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                            <select class="form-select" id="letter_type" name="letter_type" required>
                                <option value="">Pilih Jenis Surat</option>
                                <option value="surat_masuk">Surat Masuk</option>
                                <option value="sk">SK</option>
                                <option value="perda">PERDA</option>
                                <option value="pergub">PERGUB</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="no_surat" class="form-label">No. Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="Masukkan nomor surat" required>
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" required>
                            <small class="text-muted">Masukkan No.HP yang bisa di hubungi untuk info selanjutnya.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_surat" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                        </div>
                        <div class="col-md-12">
                            <label for="lampiran" class="form-label">Lampiran <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="lampiran" name="lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            <small class="text-muted">File Lampiran wajib di isi, max 2GB.</small>
                        </div>
                        <div class="col-12">
                            <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" rows="2" placeholder="Tuliskan perihal surat" required></textarea>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Data surat akan direview oleh admin, surat akan disimpan jika disetujui.
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

        // Debounce untuk search
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

        searchInput.addEventListener('input', debounce(function() {
            form.submit();
        }, 500));

        // Auto-submit saat status diubah
        statusFilter.addEventListener('change', function() {
            form.submit();
        });
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
    box-shadow: 0 2px 8px rgba(91,126,241,0.08);
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
@endsection