@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i>
    <span style="color: white; font-weight: 600;">
        <i class="fas fa-history me-1"></i> Riwayat Aktivitas
    </span>
@endsection

@section('content')
    <div class="container-fluid px-4 pb-4 mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1 fw-bold" style="color: #0f1b3d;">Riwayat Aktivitas</h3>
                <p class="text-muted mb-0 small">
                    @if (auth()->user()->role === 'superadmin')
                        Rekam jejak seluruh aktivitas pengguna pada sistem
                    @else
                        Jejak aktivitas akun Anda
                    @endif
                </p>
            </div>
        </div>

        <!-- Toolbar: Search & Filter (Admin Only) -->
        <div
            class="bg-white p-3 rounded-lg shadow-sm border mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
                <i class="fas fa-history text-muted"></i>
                <span class="text-muted fw-bold">Total: <span
                        class="badge bg-primary rounded-pill">{{ $logs->total() }}</span> Log Aktivitas</span>
            </div>

            @if (auth()->user()->role === 'superadmin')
                <form action="{{ route('profile.activity') }}" method="GET"
                    class="d-flex flex-row gap-2 justify-content-end" style="max-width: 400px; flex: 1;">
                    <a href="{{ route('profile.activity') }}"
                        class="btn btn-light shadow-sm flex-shrink-0 d-flex justify-content-center align-items-center"
                        style="border-radius: 50%; width: 42px; height: 42px; background: white; border: 1px solid #dee2e6;"
                        title="Reset Filter">
                        <i class="fas fa-sync-alt text-muted"></i>
                    </a>

                    <select name="user_id" class="form-select shadow-sm flex-grow-1" onchange="this.form.submit()"
                        style="border-radius: 30px; height: 42px; min-width: 150px;">
                        <option value="">Semua Pengguna</option>
                        @foreach ($allUsers as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ ucfirst($u->role) }})
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        <!-- Timeline Content (Responsive) -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border mb-4">
            <div class="card-header py-3" style="background-color: #0f1b3d; color: white; border-bottom: none;">
                <div class="d-flex w-100 justify-content-between align-items-center px-2">
                    <h6 class="m-0 font-weight-bold" style="font-size: 1.05rem;"><i class="fas fa-list-ul me-2"></i>Daftar
                        Aktivitas</h6>
                </div>
            </div>

            <div class="list-group list-group-flush border-bottom scrollarea">
                @forelse($logs as $log)
                    @php
                        // Color styling based on log type
                        $color = 'gray';
                        $icon = 'info-circle';
                        $iconBg = 'rgba(100, 116, 139, 0.1)';
                        $iconColor = '#64748b';

                        if (str_contains($log->action, 'login') || str_contains($log->action, 'logout')) {
                            $color = 'blue';
                            $icon = 'sign-in-alt';
                            $iconBg = 'rgba(56, 189, 248, 0.1)';
                            $iconColor = '#38bdf8';
                        } elseif (str_contains($log->action, 'create') || str_contains($log->action, 'accept')) {
                            $color = 'green';
                            $icon = 'plus-circle';
                            $iconBg = 'rgba(16, 185, 129, 0.1)';
                            $iconColor = '#10b981';
                        } elseif (
                            str_contains($log->action, 'update') ||
                            str_contains($log->action, 'change') ||
                            str_contains($log->action, 'reset')
                        ) {
                            $color = 'yellow';
                            $icon = 'edit';
                            $iconBg = 'rgba(245, 158, 11, 0.1)';
                            $iconColor = '#f59e0b';
                        } elseif (str_contains($log->action, 'delete') || str_contains($log->action, 'reject')) {
                            $color = 'red';
                            $icon = 'trash-alt';
                            $iconBg = 'rgba(239, 68, 68, 0.1)';
                            $iconColor = '#ef4444';
                        } elseif (str_contains($log->action, 'export')) {
                            $color = 'purple';
                            $icon = 'download';
                            $iconBg = 'rgba(168, 85, 247, 0.1)';
                            $iconColor = '#a855f7';
                        }
                    @endphp

                    <div class="list-group-item py-3 px-4 lh-sm"
                        style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease;">
                        <div class="d-flex w-100 align-items-center">

                            <!-- Area Ikon -->
                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 45px; height: 45px; background: {!! $iconBg !!}; color: {!! $iconColor !!}; flex-shrink: 0;">
                                <i class="fas fa-{{ $icon }}" style="font-size: 1.1rem;"></i>
                            </div>

                            <!-- Area Konten Teks -->
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 me-2 text-dark"
                                        style="font-weight: 600; font-size: 0.95rem; line-height: 1.4;">
                                        {{ $log->description ?? '-' }}
                                    </h6>

                                    <!-- Tanggal terselip di bahu atas (Mobile Friendly) -->
                                    <small class="text-nowrap text-muted" style="font-size: 0.75rem; padding-top: 2px;">
                                        {{ $log->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                                    </small>
                                </div>

                                <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-pill bg-gray-100 text-gray-800 border"
                                        style="font-size: 0.7rem;">
                                        {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                    </span>

                                    @if (auth()->user()->role === 'superadmin')
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.8rem;">
                                            <i class="fas fa-user-circle me-1"></i>
                                            <span class="fw-medium">{{ optional($log->user)->name ?? 'System' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px; background: rgba(226, 232, 240, 0.5);">
                            <i class="fas fa-history fa-2x text-slate-400" style="color: #94a3b8;"></i>
                        </div>
                        <h5 style="color: #475569; font-weight: 600;">Belum Ada Riwayat</h5>
                        <p class="text-muted" style="font-size: 0.9rem;">Data riwayat aktivitas sistem akan muncul di sini.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if ($logs->hasPages())
            <div class="mt-4 mb-2 d-flex justify-content-center">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection

