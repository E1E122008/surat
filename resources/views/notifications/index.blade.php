@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-bell me-1"></i> Riwayat Notifikasi</span>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700; color: #1e293b !important;">
                <i class="far fa-bell me-2" style="color: #475569;"></i>Riwayat Notifikasi
            </h1>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read.all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn shadow-sm text-white" title="Tandai semua telah dibaca"
                        style="background: #2563eb; border-radius: 8px; font-weight: 500; padding: 8px 16px;">
                        <i class="fas fa-check-double fa-sm me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header py-3 bg-white" style="border-bottom: 1px solid #f1f5f9;">
                        <h6 class="m-0 font-weight-bold" style="color: #334155; font-size: 1.05rem;">Kotak Masuk
                            Pemberitahuan</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush border-bottom scrollarea">
                            @forelse($notifications as $notification)
                                @php
                                    $title = $notification->data['title'] ?? 'Pemberitahuan Sistem';
                                    $titleLower = strtolower($title);

                                    // Deteksi Tipe Ikon dan Warna Berdasarkan Judul
                                    $iconClass = 'fas fa-info-circle text-secondary';
                                    $iconBg = 'background: rgba(100, 116, 139, 0.1); color: #64748b;';

                                    if (
                                        str_contains($titleLower, 'surat') ||
                                        str_contains($titleLower, 'masuk') ||
                                        str_contains($titleLower, 'keluar')
                                    ) {
                                        $iconClass = 'fas fa-envelope text-warning';
                                        $iconBg = 'background: rgba(245, 158, 11, 0.1); color: #f59e0b;';
                                    } elseif (
                                        str_contains($titleLower, 'disetujui') ||
                                        str_contains($titleLower, 'selesai') ||
                                        str_contains($titleLower, 'sukses') ||
                                        str_contains($titleLower, 'aktif')
                                    ) {
                                        $iconClass = 'fas fa-check text-success';
                                        $iconBg = 'background: rgba(16, 185, 129, 0.1); color: #10b981;';
                                    } elseif (
                                        str_contains($titleLower, 'sandi') ||
                                        str_contains($titleLower, 'password') ||
                                        str_contains($titleLower, 'kredensial') ||
                                        str_contains($titleLower, 'kunci')
                                    ) {
                                        $iconClass = 'fas fa-lock text-info';
                                        $iconBg = 'background: rgba(56, 189, 248, 0.1); color: #38bdf8;';
                                    } elseif (
                                        str_contains($titleLower, 'ditolak') ||
                                        str_contains($titleLower, 'gagal') ||
                                        str_contains($titleLower, 'error')
                                    ) {
                                        $iconClass = 'fas fa-times text-danger';
                                        $iconBg = 'background: rgba(239, 68, 68, 0.1); color: #ef4444;';
                                    }
                                @endphp
                                <a href="{{ route('notifications.read', $notification->id) }}"
                                    class="list-group-item list-group-item-action py-3 px-4 lh-sm {{ $notification->read_at ? '' : 'unread-item' }}"
                                    aria-current="true"
                                    style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease;">

                                    <div class="d-flex w-100 align-items-center">
                                        <!-- Area Indikator Unread -->
                                        <div style="width: 15px; margin-right: 10px;">
                                            @if (!$notification->read_at)
                                                <span class="rounded-circle"
                                                    style="width: 8px; height: 8px; background-color: #f59e0b; display: inline-block;"></span>
                                            @endif
                                        </div>

                                        <!-- Area Ikon (Kotak Rounded) -->
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                            style="width: 45px; height: 45px; {!! $iconBg !!} flex-shrink: 0;">
                                            <i class="{{ $iconClass }}" style="font-size: 1.1rem;"></i>
                                        </div>

                                        <!-- Area Konten Teks -->
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0 me-2 text-truncate {{ $notification->read_at ? 'text-secondary' : 'text-dark' }}"
                                                    style="font-weight: 600; font-size: 0.95rem;"
                                                    title="{{ $title }}">
                                                    {{ $title }}
                                                </h6>
                                                <small
                                                    class="text-nowrap {{ $notification->read_at ? 'text-muted' : 'text-secondary' }}"
                                                    style="font-size: 0.75rem; padding-top: 2px;">
                                                    {{ $notification->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                                                </small>
                                            </div>
                                            <div class="small {{ $notification->read_at ? 'text-muted' : 'text-secondary' }}"
                                                style="font-weight: {{ $notification->read_at ? '400' : '500' }}; font-size: 0.85rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                                title="{{ $notification->data['message'] ?? 'Ada pembaruan status data pengajuan.' }}">
                                                {{ $notification->data['message'] ?? 'Ada pembaruan status data pengajuan.' }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                        style="width: 80px; height: 80px; background: rgba(226, 232, 240, 0.5);">
                                        <i class="fas fa-box-open fa-2x text-slate-400" style="color: #94a3b8;"></i>
                                    </div>
                                    <h5 style="color: #475569; font-weight: 600;">Kotak Masuk Kosong</h5>
                                    <p class="text-muted" style="font-size: 0.9rem;">Belum ada riwayat pemberitahuan
                                        aktivitas.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if ($notifications->hasPages())
                            <div class="mt-3 d-flex justify-content-center p-4">
                                {{ $notifications->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
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
            background-color: transparent;
        }

        .list-group-item:hover {
            background-color: #f8fafc !important;
        }

        .unread-item {
            background-color: #fffbeb !important;
            /* Warna kuning sangat pudar */
        }

        .unread-item:hover {
            background-color: #fef3c7 !important;
        }
    </style>
@endsection
