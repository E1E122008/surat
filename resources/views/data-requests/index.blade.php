@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Permintaan Persetujuan</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#requestModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Permintaan Baru
        </button>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Permintaan</h6>
                    <div class="dropdown no-arrow">
                        <form action="{{ route('data-requests.index') }}" method="GET" class="d-flex">
                            <select name="status" 
                                    id="statusFilter" 
                                    class="form-control form-control-sm mr-2"
                                    onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   placeholder="Cari permintaan..." 
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
                                    <th>Jenis Surat</th>
                                    <th>Pengirim</th>
                                    <th>Catatan</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Progres</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvalRequests as $request)
                                    <tr class="request-row" data-status="{{ $request->status }}">
                                        <td>
                                            @php
                                                $letterTypes = [
                                                    'surat_masuk' => 'Surat Masuk',
                                                    'sk' => 'SK',
                                                    'perda' => 'PERDA',
                                                    'pergub' => 'PERGUB',
                                                    'sppd_dalam' => 'SPPD Dalam Daerah',
                                                    'sppd_luar' => 'SPPD Luar Daerah',
                                                    'spt_dalam' => 'SPT Dalam Daerah',
                                                    'spt_luar' => 'SPT Luar Daerah'
                                                ];
                                            @endphp
                                            {{ $letterTypes[$request->letter_type] ?? $request->letter_type }}
                                        </td>
                                        <td>{{ $request->sender }}</td>
                                        <td>{{ $request->notes }}</td>
                                       
                                        <td>{{ $request->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($request->status == 'approved')
                                                @php
                                                    $createRoute = match($request->letter_type) {
                                                        'surat_masuk' => route('surat-masuk.create'),
                                                        'surat_keluar' => route('surat-keluar.create'),
                                                        'sk' => route('draft-phd.sk.create'),
                                                        'perda' => route('draft-phd.perda.create'),
                                                        'pergub' => route('draft-phd.pergub.create'),
                                                        'sppd_dalam' => route('sppd-dalam-daerah.create'),
                                                        'sppd_luar' => route('sppd-luar-daerah.create'),
                                                        'spt_dalam' => route('spt-dalam-daerah.create'),
                                                        'spt_luar' => route('spt-luar-daerah.create'),
                                                        default => route('transaksi-surat.index')
                                                    };
                                                @endphp
                                                <a href="{{ $createRoute }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Tambah Data
                                                </a>
                                            @elseif($request->status == 'rejected')
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-ban"></i> Ditolak
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-warning btn-sm" disabled>
                                                    <i class="fas fa-clock"></i> Menunggu
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada permintaan</td>
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

<!-- Modal Request Approval -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Permintaan Persetujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transaksi-surat.request-approval') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="letter_type" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="letter_type" name="letter_type" required>
                            <option value="">Pilih Jenis Surat</option>
                            <option value="surat_masuk">Surat Masuk</option>
                            <option value="surat_keluar">Surat Keluar</option>
                            <option value="sk">SK</option>
                            <option value="perda">PERDA</option>
                            <option value="pergub">PERGUB</option>
                            <option value="sppd_dalam">SPPD Dalam Daerah</option>
                            <option value="sppd_luar">SPPD Luar Daerah</option>
                            <option value="spt_dalam">SPT Dalam Daerah</option>
                            <option value="spt_luar">SPT Luar Daerah</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sender" class="form-label">Pengirim</label>
                        <input type="text" class="form-control" id="sender" name="sender" 
                            placeholder="Masukkan nama pengirim/instansi pengirim" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                            placeholder="Jelaskan alasan permintaan persetujuan..." required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Permintaan persetujuan akan diproses oleh admin. Anda akan mendapatkan notifikasi setelah permintaan disetujui atau ditolak.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Permintaan
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
</script>
@endpush
@endsection