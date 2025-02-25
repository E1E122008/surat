<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Perda extends Model
{
    use HasFactory;

    protected $table = 'perda';
    
    protected $fillable = [
        'no_agenda',
        'no_surat',
        'pengirim',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'lampiran',
        'status',
        'disposisi'
    ];

    protected $casts = [    
        'tanggal_surat' => 'date',      
        'tanggal_terima' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];   
}
