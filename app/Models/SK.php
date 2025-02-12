<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SK extends Model
{
    use HasFactory;

    protected $table = 'sks';
    
    protected $fillable = [
        'no_surat',
        'pengirim',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'lampiran'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];
} 