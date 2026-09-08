@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Surat</h6>
                <a href="{{ auth()->user()->role == 'admin' ? route('admin.approval-requests.index') : route('data-requests.index') }}"
                    class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">Jenis Surat</label>
                            <p class="fs-6 fw-medium text-dark">
                                @php
                                    $letterTypes = [
                                        'surat_masuk' => 'Surat Masuk',
                                        'sk' => 'SK',
                                        'perda' => 'PERDA',
                                        'pergub' => 'PERGUB',
                                    ];
                                @endphp
                                {{ $letterTypes[$dataRequest->letter_type] ?? $dataRequest->letter_type }}
                            </p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">No. Surat</label>
                            <p class="fs-6 fw-medium text-dark">{{ $dataRequest->no_surat ?? '-' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">No. HP</label>
                            <p class="fs-6 fw-medium text-dark">{{ $dataRequest->no_hp ?? '-' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">Tanggal Surat</label>
                            <p class="fs-6 fw-medium text-dark">
                                {{ $dataRequest->tanggal_surat ? $dataRequest->tanggal_surat->format('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">Perihal</label>
                            <p class="fs-6 fw-medium text-dark">{{ $dataRequest->perihal ?? '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">Lampiran</label>
                            @php
                                // Handle null, string kosong, dan format lama
                                $lampiran = $dataRequest->lampiran;
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
                                <div class="row g-2 mt-1">
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
                                        <div class="col-12 d-flex align-items-center gap-2">
                                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                class="fs-4 me-2 text-decoration-none" title="Lihat file">
                                                <i class="fas {{ $iconClass }}"></i>
                                            </a>
                                            <span class="fw-bold small text-truncate" style="max-width:300px;"
                                                title="{{ $file['name'] }}">
                                                {{ $file['name'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="fs-6 text-muted">-</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">Catatan</label>
                            <p class="fs-6 fw-medium text-dark">{{ $dataRequest->notes ?: '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted text-uppercase small mb-1">Status</label>
                            <div>
                                @if ($dataRequest->status === 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                        <i class="fas fa-clock me-1"></i> Menunggu Review
                                    </span>
                                @elseif($dataRequest->status === 'approved')
                                    <span class="badge bg-success px-3 py-2 fs-6">
                                        <i class="fas fa-check me-1"></i> Disetujui
                                    </span>
                                @elseif($dataRequest->status === 'rejected')
                                    <span class="badge bg-danger px-3 py-2 fs-6">
                                        <i class="fas fa-times me-1"></i> Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($dataRequest->admin_notes && $dataRequest->status != 'pending')
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted text-uppercase small mb-1">Catatan
                                    Admin/Pelepas</label>
                                <p class="fs-6 fw-medium text-danger">{{ $dataRequest->admin_notes }}</p>
                            </div>
                        @endif

                    </div>
                </div>

                @if ($dataRequest->status === 'approved' && !$dataRequest->fisik_diterima && auth()->user()->id == $dataRequest->user_id)
                    <div class="alert alert-success mt-4">
                        <i class="fas fa-info-circle me-1"></i> Surat Anda telah disetujui. Silakan bawa dokumen fisik ke
                        kantor untuk proses lebih lanjut.
                    </div>
                @endif

                <hr>

                @if (auth()->user()->role == 'admin' && $dataRequest->status == 'pending')
                    <div class="mt-4 bg-light p-4 rounded border d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-check-circle text-primary"></i> Tindak Lanjut Persetujuan</h5>
                        <div>
                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                data-bs-target="#approveModal{{ $dataRequest->id }}">
                                <i class="fas fa-check me-1"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal{{ $dataRequest->id }}">
                                <i class="fas fa-times me-1"></i> Tolak
                            </button>
                        </div>
                    </div>
                @endif

            </div> <!-- /.card-body -->
        </div> <!-- /.card -->
    </div> <!-- /.container-fluid -->

    @if (auth()->user()->role == 'admin' && $dataRequest->status == 'pending')
        <!-- Approve Modal -->
        <div class="modal fade " id="approveModal{{ $dataRequest->id }}" tabindex="-1"
            aria-labelledby="approveModalLabel{{ $dataRequest->id }}" aria-hidden="true">
            <div class="modal-dialog bg-white shadow-lg rounded-2">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel{{ $dataRequest->id }}">Setujui
                            Permintaan Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.approval-requests.approve', $dataRequest->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Permintaan dari: <strong>{{ $dataRequest->user->name }}</strong>
                                ({{ $dataRequest->letter_type }})</p>
                            <div class="mb-3">
                                <label for="no_agenda{{ $dataRequest->id }}" class="form-label">No.
                                    Agenda</label>
                                <input type="text" class="form-control" id="no_agenda{{ $dataRequest->id }}"
                                    name="no_agenda" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_diterima{{ $dataRequest->id }}" class="form-label">Tanggal
                                    Diterima</label>
                                <input type="date" class="form-control" id="tanggal_diterima{{ $dataRequest->id }}"
                                    name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}" required>
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
                            <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>
                                Simpan & Arsipkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal{{ $dataRequest->id }}" tabindex="-1"
            aria-labelledby="rejectModalLabel{{ $dataRequest->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel{{ $dataRequest->id }}">Tolak Permintaan
                            Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.approval-requests.reject', $dataRequest->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Permintaan dari: <strong>{{ $dataRequest->user->name }}</strong>
                                ({{ $dataRequest->letter_type }})</p>
                            <div class="mb-3">
                                <label for="admin_notes{{ $dataRequest->id }}" class="form-label">Alasan
                                    Penolakan</label>
                                <textarea class="form-control" id="admin_notes{{ $dataRequest->id }}" name="admin_notes" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-times me-1"></i>
                                Tolak Permintaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
