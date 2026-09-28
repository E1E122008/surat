<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SystemSetting::create([
            'logo' => null,
            'headline' => "Selamat Datang di Sistem Informasi Administrasi Persuratan Biro Hukum\nSekretariat Daerah Provinsi Sulawesi Tenggara",
            'description' => "Biro Hukum mempunyai tugas membantu asisten pemerintahan dan kesejahteraan rakyat dalam penyiapan perumusan kebijakan daerah, pengoordinasian pelaksanaan tugas perangkat daerah, pemantauan dan evaluasi pelaksanaan kebijakan di bidang peraturan perundang-undangan provinsi, peraturan perundang-undangan kabupaten/kota, dan bantuan hukum.",
            'operational_time' => "[Jam layanan operasional — Senin-Jumat, 08.00–16.00 WITA]"
        ]);
    }
}
