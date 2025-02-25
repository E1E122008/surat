<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';
    
    protected $fillable = [
        'no_surat',
        'no_agenda',
        'pengirim',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'lampiran',
        'catatan',
        'disposisi',
        'status',
        'sub_disposisi',
        'tanggal_disposisi',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    public function disposisi()
    {
        return $this->hasOne(DisposisiSuratMasuk::class);
    }
} 