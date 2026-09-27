<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Menggunakan updateOrCreate agar bisa update jika user sudah ada
        \App\Models\User::updateOrCreate(
            ['email' => 'intership25@gmail.com'],
            [
                'name' => 'Intership',
                'password' => \Illuminate\Support\Facades\Hash::make('1nt3rsh1p25'),
                'role' => 'superadmin',
                'dinas' => 'Magang',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'birohukum@gmail.com'],
            [
                'name' => 'Biro Hukum',
                'password' => \Illuminate\Support\Facades\Hash::make('b1r0hukum!'),
                'role' => 'admin',
                'dinas' => 'Biro Hukum',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'kepalabirohukum@gmail.com'],
            [
                'name' => 'Kepala Biro Hukum',
                'password' => \Illuminate\Support\Facades\Hash::make('k3p4lb1r0hukum!'),
                'role' => 'monitor',
                'dinas' => 'Biro Hukum',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'ktu@gmail.com'],
            [
                'name' => 'Ketua Tata Usaha',
                'password' => \Illuminate\Support\Facades\Hash::make('k3tu4tn!'),
                'role' => 'monitor',
                'dinas' => 'Biro Hukum',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'staffbirohukum@gmail.com'],
            [
                'name' => 'Staff Biro Hukum',
                'password' => \Illuminate\Support\Facades\Hash::make('st4ffb1r0hukum!'),
                'role' => 'user',
                'dinas' => 'Biro Hukum',
            ]
        );

        $opdData = [
            ['name' => 'Inspektorat Daerah Prov. Sultra', 'email' => 'inspektorat@sultra.id', 'password' => '#Inspek26', 'role' => 'user'],
            ['name' => 'Badan Pengelola Keuangan dan Aset Daerah Prov. Sultra', 'email' => 'bpkad@sultra.id', 'password' => '#Bpkad26', 'role' => 'user'],
            ['name' => 'Badan Perencanaan Pembangunan Daerah Prov. Sultra', 'email' => 'bappeda@sultra.id', 'password' => '#Bappeda', 'role' => 'user'],
            ['name' => 'Badan Pendapatan Daerah Prov. Sultra', 'email' => 'bapenda@sultra.id', 'password' => '#Bapenda', 'role' => 'user'],
            ['name' => 'Badan Kepegawaian Daerah Prov. Sultra', 'email' => 'bkd@sultra.id', 'password' => '#Bkd26!N', 'role' => 'user'],
            ['name' => 'Badan Pengembangan Sumber Daya Manusia Prov. Sultra', 'email' => 'bpsdm@sultra.id', 'password' => '#Bpsdm26', 'role' => 'user'],
            ['name' => 'Badan Riset dan Inovasi Daerah Prov. Sultra', 'email' => 'brida@sultra.id', 'password' => '#Brida26', 'role' => 'user'],
            ['name' => 'Badan Penanggulangan Bencana Daerah Prov. Sultra', 'email' => 'bpbd@sultra.id', 'password' => '#Bpbd26!', 'role' => 'user'],
            ['name' => 'Badan Kesatuan Bangsa dan Politik Prov. Sultra', 'email' => 'kesbangpol@sultra.id', 'password' => '#Ksbp26!', 'role' => 'user'],
            ['name' => 'Dinas Pendidikan dan Kebudayaan Prov. Sultra', 'email' => 'disdikbud@sultra.id', 'password' => '#Dikbud26', 'role' => 'user'],
            ['name' => 'Dinas Kesehatan Prov. Sultra', 'email' => 'dinkes@sultra.id', 'password' => '#Dinkes26', 'role' => 'user'],
            ['name' => 'Dinas Sumber Daya Air dan Bina Marga Prov. Sultra', 'email' => 'sdabm@sultra.id', 'password' => '#Sdabm26', 'role' => 'user'],
            ['name' => 'Dinas Cipta Karya, Bina Konstruksi dan Tata Ruang Prov. Sultra', 'email' => 'ciptakarya@sultra.id', 'password' => '#Cipta26', 'role' => 'user'],
            ['name' => 'Dinas Perumahan Rakyat, Kawasan Permukiman dan Pertanahan Prov. Sultra', 'email' => 'perumahan@sultra.id', 'password' => '#Perkim26', 'role' => 'user'],
            ['name' => 'Dinas Sosial Prov. Sultra', 'email' => 'dinsos@sultra.id', 'password' => '#Dinsos26', 'role' => 'user'],
            ['name' => 'Dinas Transmigrasi dan Tenaga Kerja Prov. Sultra', 'email' => 'transnaker@sultra.id', 'password' => '#Trans26', 'role' => 'user'],
            ['name' => 'Dinas Pemberdayaan Perempuan, Perlindungan Anak, Pengendalian Penduduk dan KB Prov. Sultra', 'email' => 'dp3a@sultra.id', 'password' => '#Dp3a26!', 'role' => 'user'],
            ['name' => 'Dinas Ketahanan Pangan Prov. Sultra', 'email' => 'dkp@sultra.id', 'password' => '#disKP26', 'role' => 'user'],
            ['name' => 'Dinas Lingkungan Hidup Prov. Sultra', 'email' => 'dlh@sultra.id', 'password' => '#Dlh26!G', 'role' => 'user'],
            ['name' => 'Dinas Kependudukan dan Pencatatan Sipil Prov. Sultra', 'email' => 'disdukcapil@sultra.id', 'password' => '#Dukcapil', 'role' => 'user'],
            ['name' => 'Dinas Pemberdayaan Masyarakat Desa Prov. Sultra', 'email' => 'dpmd@sultra.id', 'password' => '#Dpmd26!', 'role' => 'user'],
            ['name' => 'Dinas Perhubungan Prov. Sultra', 'email' => 'dishub@sultra.id', 'password' => '#Dishub26', 'role' => 'user'],
            ['name' => 'Dinas Komunikasi dan Informatika Prov. Sultra', 'email' => 'diskominfo@sultra.id', 'password' => '#Kominfo', 'role' => 'user'],
            ['name' => 'Dinas Koperasi Usaha Mikro Kecil dan Menengah Prov. Sultra', 'email' => 'kopumkm@sultra.id', 'password' => '#disUmkm', 'role' => 'user'],
            ['name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Prov. Sultra', 'email' => 'dpmptsp@sultra.id', 'password' => '#Dpmptsp', 'role' => 'user'],
            ['name' => 'Dinas Kepemudaan dan Olahraga Prov. Sultra', 'email' => 'dispora@sultra.id', 'password' => '#Dispora', 'role' => 'user'],
            ['name' => 'Dinas Perpustakaan dan Kearsipan Prov. Sultra', 'email' => 'perpustakaan@sultra.id', 'password' => '#perArsp', 'role' => 'user'],
            ['name' => 'Dinas Kelautan dan Perikanan Prov. Sultra', 'email' => 'diskelautan@sultra.id', 'password' => '#Dkp26!L', 'role' => 'user'],
            ['name' => 'Dinas Pariwisata Prov. Sultra', 'email' => 'dispariwisata@sultra.id', 'password' => '#Dispar2', 'role' => 'user'],
            ['name' => 'Dinas Tanaman Pangan dan Peternakan Prov. Sultra', 'email' => 'dtpp@sultra.id', 'password' => '#Tpn26!R', 'role' => 'user'],
            ['name' => 'Dinas Perkebunan dan Hortikultura Prov. Sultra', 'email' => 'disperkebunan@sultra.id', 'password' => '#Dispera', 'role' => 'user'],
            ['name' => 'Dinas Kehutanan Prov. Sultra', 'email' => 'dishut@sultra.id', 'password' => '#Dishut2', 'role' => 'user'],
            ['name' => 'Dinas Energi dan Sumber Daya Mineral Prov. Sultra', 'email' => 'esdm@sultra.id', 'password' => '#Esdm26!', 'role' => 'user'],
            ['name' => 'Dinas Perindustrian dan Perdagangan Prov. Sultra', 'email' => 'disperinda@sultra.id', 'password' => '#Dispend', 'role' => 'user'],
            ['name' => 'Sekretariat DPRD Prov. Sultra', 'email' => 'setdprd@sultra.id', 'password' => '#Setdprd', 'role' => 'user'],
            ['name' => 'Satuan Polisi Pamong Praja Prov. Sultra', 'email' => 'satpolpp@sultra.id', 'password' => '#Satpol2', 'role' => 'user'],
            ['name' => 'Rumah Sakit Umum Daerah Bahteramas Prov. Sultra', 'email' => 'rsudbahteramas@sultra.id', 'password' => '#Rsud26!', 'role' => 'user'],
            ['name' => 'Biro Administrasi Pembangunan Setda Prov. Sultra', 'email' => 'biroadministran@sultra.id', 'password' => '#Admistran', 'role' => 'user'],
            ['name' => 'Biro Umum Setda Prov. Sultra', 'email' => 'biroumum@sultra.id', 'password' => '#Birum26', 'role' => 'user'],
            ['name' => 'Biro Kesejahteraan Rakyat Setda Prov. Sultra', 'email' => 'birokesra@sultra.id', 'password' => '#Kesra26', 'role' => 'user'],
            ['name' => 'Biro Pemerintahan dan Otonomi Daerah Setda Prov. Sultra', 'email' => 'biropemda@sultra.id', 'password' => '#Pemda26', 'role' => 'user'],
            ['name' => 'Biro Organisasi Setda Prov. Sultra', 'email' => 'biroorganisasi@sultra.id', 'password' => '#Org26!Q', 'role' => 'user'],
            ['name' => 'Biro Perekonomian Setda Prov. Sultra', 'email' => 'biroekonomi@sultra.id', 'password' => '#Ekonomi', 'role' => 'user'],
            ['name' => 'Biro Administrasi Pimpinan Setda Prov. Sultra', 'email' => 'biropimpinan@sultra.id', 'password' => '#Adpim26', 'role' => 'user'],
            ['name' => 'Rumah Sakit Jiwa Prov. Sultra', 'email' => 'rsj@sultra.id', 'password' => '#Rsj26!M', 'role' => 'user'],
            ['name' => 'Rumah Sakit Jantung dan Pembuluh Darah Oputa Yi Koo Prov. Sultra', 'email' => 'rsjantung@sultra.id', 'password' => '#Jantung', 'role' => 'user'],
            ['name' => 'Badan Penghubung Pemerintah Daerah Prov. Sultra', 'email' => 'penghubung@sultra.id', 'password' => '#Bppd26!', 'role' => 'user'],
        ];

        foreach ($opdData as $opd) {
            \App\Models\User::firstOrCreate(
                ['email' => $opd['email']],
                [
                    'name' => $opd['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make($opd['password']),
                    'role' => strtolower($opd['role']),
                    'dinas' => $opd['name'],
                ]
            );
        }
    }
}
