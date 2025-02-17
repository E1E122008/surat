<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';
    
    protected $fillable = [
        'no_agenda',
        'no_surat',
        'tanggal_surat',
        'perihal',
        'lampiran'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    // Method untuk generate nomor surat otomatis
    public static function generateNomorSurat()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count() + 1;
            
        return "100.3.2/{$count}/{$bulan}/{$tahun}";
    }
} 