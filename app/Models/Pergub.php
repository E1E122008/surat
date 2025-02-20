<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pergub extends Model
{
    use HasFactory;

    protected $table = 'pergub';

    protected $fillable = [
        'no_agenda',
        'no_surat',
        'pengirim',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'lampiran',
        'catatan',
    ];  

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];
    
}