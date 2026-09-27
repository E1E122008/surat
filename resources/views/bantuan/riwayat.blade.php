@extends('layouts.app')

@section('content')
    <style>
        .header-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--navy-utama);
            margin-bottom: 0.5rem;
        }

        .header-desc {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .breadcrumb-custom {
            font-size: 0.9rem;
            margin-bottom: 1rem;
            color: #94a3b8;
        }

        .breadcrumb-custom a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-custom a:hover {
            color: var(--navy-utama);
        }

        .ticket-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ticket-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .ticket-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy-utama);
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ticket-meta {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .ticket-body {
            background-color: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            color: var(--slate-700);
            font-size: 0.95rem;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .admin-reply-box {
            margin-top: 1rem;
            padding: 1rem;
            border-left: 3px solid #fbbf24;
            background-color: rgba(251, 191, 36, 0.05);
            border-radius: 0 8px 8px 0;
            font-size: 0.95rem;
        }
    </style>

    <div class="px-3 py-2">
    @section('breadcrumb')
        <i class="fas fa-chevron-right separator"></i> <a href="{{ route('bantuan.index') }}">Bantuan & Kontak</a>
        <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
                class="fas fa-history me-1"></i> Riwayat</span>
    @endsection

    <h1 class="header-title">Riwayat Permintaan Bantuan Saya</h1>
    <p class="header-desc">Pantau status permintaan bantuan yang pernah Anda kirimkan ke Admin.</p>

    <div class="mt-4" id="riwayat">
        @forelse($riwayat as $req)
            <div class="ticket-card">
                <div class="ticket-header">
                    <h4 class="ticket-title">
                        {{ $req->kendala }}

                        @if ($req->status == 'menunggu')
                            <span class="badge rounded-pill bg-danger px-3 py-1"
                                style="font-size: 0.75rem; font-weight: 600;">Menunggu Respons</span>
                        @elseif($req->status == 'diproses')
                            <span class="badge rounded-pill text-primary px-3 py-1"
                                style="background-color: rgba(14, 165, 233, 0.1); font-size: 0.75rem; font-weight: 600;">Diproses</span>
                        @else
                            <span class="badge rounded-pill text-success px-3 py-1"
                                style="background-color: rgba(34, 197, 94, 0.1); font-size: 0.75rem; font-weight: 600;">Selesai</span>
                        @endif
                    </h4>
                </div>
                <div class="ticket-meta">
                    Tiket #REQ-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }} — Dikirim
                    {{ $req->created_at->format('d M Y, H:i') }} WITA
                </div>
                <div class="ticket-body">
                    "{{ $req->deskripsi }}"
                </div>

                @if ($req->balasan_admin)
                    <div class="admin-reply-box">
                        <span style="font-weight: 700; color: #d97706; margin-right: 8px;">Admin:</span>
                        <span style="color: var(--slate-700);">{{ $req->balasan_admin }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-5" style="background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <i class="fas fa-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                <h5 style="color: var(--slate-600); font-weight: 600;">Belum ada riwayat laporan</h5>
                <p class="text-muted" style="font-size: 0.95rem;">Anda belum pernah mengirimkan permohonan bantuan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection



