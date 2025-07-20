@extends('layouts.app')



@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-envelope-open-text me-2"></i>Daftar Data Persetujuan</h1>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" class="d-flex">
                <select name="status" id="statusFilter" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <input type="text" name="search" id="search" placeholder="Cari data surat..." class="form-control form-control-sm mr-2" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="surat-badge surat-badge-sm mt-2 d-inline-block">
                            <i class="fas fa-envelope"></i> Jumlah Surat: {{ $totalFiltered < $totalAll ? $totalFiltered . ' (Total: ' . $totalAll . ')' : $totalAll }}
                        </span>
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
                                    <th class="text-center">Dinas</th>
                                    <th class="text-center">Tanggal Permintaan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Fisik</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvalRequests as $index => $request)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
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
                                        <td class="text-center">{{ $request->user->dinas ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($request->created_at)
                                                {{ $request->created_at instanceof \Illuminate\Support\Carbon ? $request->created_at->format('d M Y') : \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($request->status === 'pending')
                                                <span class="badge bg-warning">Menunggu Review</span>
                                            @elseif($request->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($request->status === 'rejected')
                                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($request->status === 'approved')
                                                <span id="fisik-badge-{{ $request->id }}" style="cursor:pointer" onclick="toggleFisik({{ $request->id }})">
                                                    @if($request->fisik_diterima)
                                                        <span class="badge bg-success"><i class="fas fa-check"></i> Sudah</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum</span>
                                                    @endif
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#detailRequestModal{{ $request->id }}" title="Detail">
                                                <i class="fas fa-eye me-2"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada permintaan persetujuan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(isset($approvalRequests) && method_exists($approvalRequests, 'links'))
                    <div class="d-flex justify-content-center my-4">
                        <nav aria-label="Page navigation">
                            {{ $approvalRequests->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detail Request Modal -->
@foreach($approvalRequests as $request)
<div class="modal fade" id="detailRequestModal{{ $request->id }}" tabindex="-1" aria-labelledby="detailRequestModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailRequestModalLabel{{ $request->id }}">Detail Permintaan Tambah Surat</h5>
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
                            <label class="form-label fw-bold">Tanggal Permintaan</label>
                            <p>
                                @if($request->created_at)
                                    {{ $request->created_at instanceof \Illuminate\Support\Carbon ? $request->created_at->format('d M Y') : \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                         <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Surat</label>
                            <p>
                                @if($request->tanggal_surat)
                                    {{ $request->tanggal_surat instanceof \Illuminate\Support\Carbon ? $request->tanggal_surat->format('d M Y') : \Carbon\Carbon::parse($request->tanggal_surat)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Fisik</label>
                            @if($request->status === 'approved')
                                <span id="fisik-status-{{ $request->id }}">
                                    @if($request->fisik_diterima)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Sudah diterima</span>
                                        <div class="text-muted small">
                                            Diterima pada:
                                            @if($request->fisik_diterima_at)
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
                            <label class="form-label fw-bold">Deskripsi (Catatan User)</label>
                            <p>{{ $request->notes ?: '-' }}</p>
                        </div>
                         <div class="mb-3">
                            <label class="form-label fw-bold">Status Saat Ini</label>
                            <p>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Menunggu Persetujuan</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                 @if($request->status === 'pending')
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}" data-bs-dismiss="modal">
                       <i class="fas fa-check me-1"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}" data-bs-dismiss="modal">
                       <i class="fas fa-times me-1"></i> Tolak
                    </button>
                 @else
                     <span class="text-muted me-2">Permintaan sudah diproses.</span>
                 @endif
                
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Approve Modal -->
@foreach($approvalRequests as $request)
<div class="modal fade " id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog bg-white shadow-lg shadow-lg rounded-2">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">Setujui Permintaan Data</h5>
            </div>
            <form action="{{ route('admin.approval-requests.approve', $request->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Permintaan dari: <strong>{{ $request->user->name }}</strong> ({{ $request->letter_type }})</p>
                    <div class="mb-3">
                        <label for="no_agenda{{ $request->id }}" class="form-label">No. Agenda</label>
                        <input type="text" class="form-control" id="no_agenda{{ $request->id }}" name="no_agenda" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_diterima{{ $request->id }}" class="form-label">Tanggal Diterima</label>
                        <input type="date" class="form-control" id="tanggal_diterima{{ $request->id }}" name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}" required>
                    </div>
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
@foreach($approvalRequests as $request)
<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">Tolak Permintaan Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.approval-requests.reject', $request->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                     <p>Permintaan dari: <strong>{{ $request->user->name }}</strong> ({{ $request->letter_type }})</p>
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
        if(data.status === 'success') {
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
        let badge = '';
        if(data.fisik_diterima) {
            badge = `<span class='badge bg-success'><i class='fas fa-check'></i> Sudah</span>`;
        } else {
            badge = `<span class='badge bg-secondary'>Belum</span>`;
        }
        document.getElementById('fisik-badge-' + id).innerHTML = badge;
        document.getElementById('fisik-status-' + id).innerHTML = badge + (data.fisik_diterima_at ? `<div class='text-muted small'>Diterima pada: ${data.fisik_diterima_at}</div>` : '');
    });
}
</script>
@endpush