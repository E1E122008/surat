<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkKaro extends Model
{
    protected $fillable = [
        'no_sk',
        'tanggal_sk',
        'perihal',
        'pejabat_ttd',
        'file_surat',
    ];

    public static function generateNomorSk()
    {
        $tahunLengkap = date('Y');
        
        $count = self::whereYear('created_at', $tahunLengkap)->count() + 1;
            
        return sprintf("100.3.5.4/%03d Tahun %s", $count, $tahunLengkap);
    }
}
