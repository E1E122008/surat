<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Bantuan Akses - SIAP BROH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        body,
        html {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bantuan-container {
            width: 100%;
            max-width: 480px;
            background: rgba(15, 23, 42, 0.9);
            padding: 2.5rem 2rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            margin: auto;
            position: relative;
            z-index: 10;
        }

        .title {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        .form-label {
            color: #cbd5e1;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(15, 23, 42, 0.6);
            color: #f8fafc;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: rgba(15, 23, 42, 0.95);
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
            color: #ffffff;
            outline: none;
        }

        .form-control::placeholder {
            color: #475569;
        }

        .btn-submit {
            background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            padding: 0.85rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(56, 189, 248, 0.3);
            color: #ffffff;
        }

        .btn-wa {
            background: transparent;
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-wa:hover {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .divider:not(:empty)::before {
            margin-right: 1rem;
        }

        .divider:not(:empty)::after {
            margin-left: 1rem;
        }

        .btn-kembali {
            background: transparent;
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s;
            margin-top: 0.8rem;
        }

        .btn-kembali:hover {
            background: rgba(148, 163, 184, 0.1);
            color: #f8fafc;
        }
    </style>
</head>

<body>

    <div class="position-relative w-100 d-flex justify-content-center align-items-center p-3">
        <div class="bantuan-container m-0">
            <h2 class="title" style="text-align: center;">Ajukan Bantuan Akses</h2>
            <p class="subtitle" style="text-align: center;">Isi data berikut, Admin Sistem akan menghubungi Anda melalui
                email/nomor yang Anda cantumkan.</p>

            <form action="{{ route('bantuan.akses.kirim') }}" method="POST">
                @csrf
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama sesuai identitas dinas"
                    required>

                <label class="form-label">Instansi / OPD</label>
                <input type="text" name="instansi" class="form-control" placeholder="Contoh: Dinas Kominfo Provinsi"
                    required>

                <label class="form-label">Email / Nomor HP</label>
                <input type="text" name="kontak" class="form-control" placeholder="nama.anda@gmail.com" required>

                <label class="form-label">Jenis Kendala</label>
                <select name="kendala" class="form-select" required>
                    <option value="" disabled selected>Pilih salah satu...</option>
                    <option value="Lupa Kata Sandi">Lupa Kata Sandi</option>
                    <option value="Belum Memiliki Akun">Belum Memiliki Akun</option>
                    <option value="Lainnya">Lainnya</option>
                </select>

                <label class="form-label">Deskripsi Tambahan (opsional)</label>
                <textarea name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan singkat kendala Anda..."></textarea>

                <button type="submit" class="btn-submit">Kirim Permintaan Bantuan</button>
            </form>

            <div class="divider">ATAU</div>

            <a href="https://wa.me/6282118591143" target="_blank" class="btn-wa">
                <i class="fab fa-whatsapp me-2" style="font-size: 1.1rem;"></i> Butuh respon cepat? Hubungi via WhatsApp
            </a>

            <a href="{{ route('login') }}" class="btn-kembali">
                Kembali ke Halaman Login
            </a>

        </div>
    </div>

</body>

</html>
