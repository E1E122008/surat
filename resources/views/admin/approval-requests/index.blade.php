@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-clipboard-check"></i> Permintaan Persetujuan</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($approvalRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pengirim</th>
                                <th class="text-center">Jenis Surat</th>
                                <th class="text-center">Tanggal Permintaan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvalRequests as $index => $request)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $request->sender }}</td>
                                    <td class="text-center">{{ $request->letter_type }}</td>
                                    <td class="text-center">{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Menunggu Persetujuan</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Disetujui</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($request->status === 'pending')
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailRequestModal{{ $request->id }}" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted text-center fw-bold" data-bs-toggle="modal" data-bs-target="#detailRequestModal{{ $request->id }}" title="Lihat Detail">Sudah diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                    <p class="text-muted">Tidak ada permintaan persetujuan</p>
                </div>
            @endif
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
                            <p>{{ $request->created_at->format('d M Y') }}</p>
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
<div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">Setujui Permintaan Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <input type="date" class="form-control" id="tanggal_diterima{{ $request->id }}" name="tanggal_diterima" required>
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