<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pengirim',
        'instansi',
        'kontak',
        'kendala',
        'deskripsi',
        'status',
        'balasan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
