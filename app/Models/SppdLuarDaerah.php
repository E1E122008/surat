<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppdLuarDaerah extends Model
{
    use HasFactory;

    protected $table = 'sppd_luar_daerah';
    
    protected $fillable = [
        'no_surat',
        'tanggal',
        'tujuan',
        'perihal',
        'nama_petugas',
        'lampiran',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Method untuk generate nomor surat otomatis
    public static function generateNomorSurat()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count() + 1;
            
        return "000.1.2.3/{$count}/LD/BH/{$bulan}/{$tahun}";
    }
} 