@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-headset me-1"></i> Desk Bantuan & Panduan</span>
@endsection

@section('content')
    <style>
        .bantuan-header {
            margin-bottom: 2rem;
        }

        .bantuan-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--navy-utama);
            margin-bottom: 0.5rem;
        }

        .bantuan-subtitle {
            font-size: 0.95rem;
            color: var(--slate-500);
        }

        .search-box {
            width: 100%;
            max-width: 100%;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            border: 1px solid var(--border-sangat-tipis);
            background-color: var(--putih-kartu);
            color: var(--slate-700);
            margin-bottom: 1.5rem;
        }

        .search-box:focus {
            border-color: var(--gold-utama);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
            outline: none;
        }

        .panduan-card {
            background-color: var(--putih-kartu);
            border: 1px solid var(--border-sangat-tipis);
            border-radius: 10px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .panduan-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .panduan-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--slate-700);
            text-transform: uppercase;
            margin-bottom: 0.3rem;
            letter-spacing: 0.5px;
        }

        .panduan-count {
            font-size: 0.85rem;
            color: var(--slate-500);
        }

        .action-card {
            background-color: var(--putih-kartu);
            border: 1px solid var(--border-sangat-tipis);
            border-radius: 12px;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .action-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .icon-blue {
            background-color: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
        }

        .icon-green {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .action-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--navy-utama);
            margin: 0;
        }

        .action-desc {
            color: var(--slate-500);
            font-size: 0.9rem;
            margin-top: 0.8rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-bantuan {
            display: block;
            width: 100%;
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-bantuan-yellow {
            background-color: #f59e0b;
            /* Mustard Yellow */
            color: #fff;
            border: none;
        }

        .btn-bantuan-yellow:hover {
            background-color: #d97706;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-bantuan-green {
            background-color: #10b981;
            color: #fff;
            border: none;
        }

        .btn-bantuan-green:hover {
            background-color: #059669;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-sangat-tipis);
            padding: 1.5rem 2rem 1rem;
        }

        .modal-title {
            font-weight: 700;
            color: var(--navy-utama);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .modal-footer {
            border-top: 1px solid var(--border-sangat-tipis);
            padding: 1rem 2rem 1.5rem;
        }

        .info-footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            margin-top: 3rem;
            background-color: var(--putih-kartu);
            border: 1px solid var(--border-sangat-tipis);
            border-radius: 12px;
        }

        .info-jam {
            color: #64748b;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
        }
    </style>

    <div class="bantuan-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="bantuan-title">Bantuan & Kontak</h1>
                <p class="bantuan-subtitle">kirim permintaan ke Admin bila butuh bantuan atau hubungi via WhatsApp langsung
                </p>
            </div>
            <a href="{{ route('bantuan.riwayat') }}"
                style="color: #d97706; font-weight: 600; font-size: 0.95rem; text-decoration: none; transition: color 0.3s; margin-bottom: 2rem;">
                Permintaan Bantuan Saya &rarr;
            </a>
        </div>

        <!-- Kumpulan Panduan Berdasarkan Role -->
        <div class="row g-3 mb-4">
            @if (Auth::user()->role === 'user')
                <!-- Tampilan khusus Role User dengan 2 Menu -->
                <div class="col-md-6">
                    <div class="panduan-card">
                        <h3 class="panduan-title">Arsip & Riwayat Surat</h3>
                        <ul class="mt-3 text-muted" style="font-size: 0.9rem; padding-left: 1.2rem; line-height: 1.6;">
                            <li>Melihat daftar seluruh surat (Masuk, Keputusan, Peraturan Daerah, Peraturan Gubernur).</li>
                            <li>Memantau status surat (tercatat, ditolak, direvisi, disetujui).</li>
                            <li>Mengecek posisi atau riwayat disposisi surat.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panduan-card">
                        <h3 class="panduan-title">Pengajuan Berkas Baru</h3>
                        <ul class="mt-3 text-muted" style="font-size: 0.9rem; padding-left: 1.2rem; line-height: 1.6;">
                            <li>Membuat dan mengirimkan draf Surat ke Admin Biro Hukum.</li>
                            <li>Mengunggah lampiran salinan fisik/digital.</li>
                            <li>Memperbaiki/mengirim ulang surat jika ditolak.</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <!-- Area Action Utama -->
        <div class="row g-4 mt-2">
            <!-- Card Formulir Bantuan -->
            <div class="col-md-6">
                <div class="action-card">
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="action-icon icon-blue me-3">
                                <i class="fas fa-upload"></i>
                            </div>
                            <h3 class="action-title">Belum terjawab? Kirim Permintaan Bantuan</h3>
                        </div>
                        <p class="action-desc">Isi kendala Anda melalui form singkat — permintaan tercatat di sistem dan
                            Admin
                            akan merespons melalui platform ini.</p>
                    </div>
                    <button type="button" class="btn-bantuan btn-bantuan-yellow" data-bs-toggle="modal"
                        data-bs-target="#bantuanModal">
                        Buka Form Permintaan Bantuan
                    </button>
                </div>
            </div>

            <!-- Card Chat WhatsApp Admin -->
            <div class="col-md-6">
                <div class="action-card">
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="action-icon icon-green me-3">
                                <i class="fas fa-comment-dots"></i>
                            </div>
                            <h3 class="action-title">Admin Sistem</h3>
                        </div>
                        <p class="action-desc">Untuk kendala yang butuh respons sangat cepat atau keluhan teknis di luar
                            kendali
                            sistem.</p>
                    </div>
                    <a href="https://wa.me/6282118591143" target="_blank" class="btn-bantuan btn-bantuan-green">
                        <i class="fab fa-whatsapp me-1"></i> Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal Form Bantuan Internal -->
        <div class="modal fade" id="bantuanModal" tabindex="-1" aria-labelledby="bantuanModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bantuanModalLabel">Kirim Permintaan Bantuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('bantuan.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: var(--slate-700);">Topik Kendala
                                    <span class="text-danger">*</span></label>
                                <select name="kendala" class="form-select" required>
                                    <option value="" disabled selected>Pilih salah satu masalah...</option>
                                    <option value="Error Sistem/Bug">Error Sistem / Fitur Tidak Berjalan</option>
                                    <option value="Kendala Upload Lampiran">Kendala Upload Lampiran Surat</option>
                                    <option value="Permasalahan Akun (Ubah Identitas)">Permasalahan Akun
                                    </option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" style="font-weight: 600; color: var(--slate-700);">Pesan Detail
                                    Keluhan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control" rows="4"
                                    placeholder="Tuliskan keluhan atau hal yang ingin ditanyakan secara spesifik..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer" style="justify-content: flex-end;">
                            <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal"
                                style="border-radius: 8px;">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 py-2"
                                style="background-color: var(--navy-utama); border: none; border-radius: 8px;">Kirim
                                Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Info Layanan Jam -->
        <div class="info-footer-container flex-column flex-md-row">
            <div class="info-jam mb-2 mb-md-0">
                <i class="far fa-clock me-2"></i> [Jam layanan operasional — Senin-Jumat, 08.00-16.00 WITA]
            </div>
            <div class="info-jam text-md-end" style="color: #94a3b8;">
                Di luar jam layanan, permintaan tetap tersimpan dan direspons hari kerja berikutnya.
            </div>
        </div>
    @endsection
