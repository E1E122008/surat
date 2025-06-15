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
        'no_agenda',
        'pengirim',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'lampiran',
        'catatan',
        'disposisi',
        'status',
        'submitted_by',
        'admin_notes'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    protected $attributes = [
        'status' => 'pending_review'
    ];
} 