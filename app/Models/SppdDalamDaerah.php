<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppdDalamDaerah extends Model
{
    use HasFactory;

    protected $table = 'sppd_dalam_daerah';
    
    protected $fillable = [
        'no_surat',
        'no_agenda',
        'tanggal',
        'tujuan',
        'perihal',
        'nama_petugas',
        'lampiran',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    // Method untuk generate nomor surat otomatis
    public static function generateNomorSurat()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count() + 1;
            
        return "100.3.5.4/{$count}/DD/BH/{$bulan}/{$tahun}";
    }
} 