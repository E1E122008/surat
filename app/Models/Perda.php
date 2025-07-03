<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Perda extends Model
{
    use HasFactory;

    protected $table = 'perda';
    
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
        'submitted_by'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    protected $attributes = [
        'status' => 'pending_review'
    ];
}
