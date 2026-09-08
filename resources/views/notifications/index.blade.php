@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-bell me-2"></i>Riwayat Notifikasi</h1>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read.all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm" title="Tandai semua telah dibaca">
                        <i class="fas fa-check-double fa-sm text-white-50 me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Kotak Masuk Pemberitahuan</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush border-bottom scrollarea">
                            @forelse($notifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}"
                                    class="list-group-item list-group-item-action py-3 lh-sm {{ $notification->read_at ? '' : 'bg-light' }}"
                                    aria-current="true">
                                    <div class="d-flex w-100 align-items-center justify-content-between mb-1">
                                        <strong class="mb-1 {{ $notification->read_at ? 'text-secondary' : 'text-dark' }}">
                                            @if (!$notification->read_at)
                                                <span class="badge bg-danger rounded-circle p-1 me-1"
                                                    style="width: 10px; height: 10px; display: inline-block;"></span>
                                            @endif
                                            {{ $notification->data['title'] ?? 'Pemberitahuan Sistem' }}
                                        </strong>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div
                                        class="col-10 mb-1 small {{ $notification->read_at ? 'text-muted' : 'text-dark fw-medium' }}">
                                        {{ $notification->data['message'] ?? 'Ada pembaruan status data pengajuan.' }}
                                    </div>
                                    <div class="small mt-2 text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $notification->created_at->format('d M Y, H:i') }}
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i>
                                    <h5>Belum ada notifikasi</h5>
                                    <p>Tidak ada riwayat pemberitahuan untuk akun Anda.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if ($notifications->hasPages())
                            <div class="d-flex justify-content-center p-4">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .list-group-item {
            border-left: 0;
            border-right: 0;
            transition: all 0.2s;
        }

        .list-group-item:hover {
            background-color: #f8f9fa !important;
        }

        .bg-light {
            background-color: #f1f8ff !important;
        }
    </style>
@endsection
