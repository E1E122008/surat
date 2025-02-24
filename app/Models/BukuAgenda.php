<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuAgenda extends Model
{
    protected $table = 'buku_agenda'; // Nama tabel di database
    protected $fillable = ['tanggal', 'jenis_dokumen', 'nomor', 'pengirim', 'status']; // Kolom yang dapat diisi
}


