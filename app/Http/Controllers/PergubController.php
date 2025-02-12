<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PergubController extends Controller
{
    public function index()
    {
        // Mengambil data yang diperlukan untuk tampilan
        // Misalnya, data dari model terkait jika ada
        return view('draft-phd.pergub.index'); // Pastikan Anda memiliki tampilan draft-phd/pergub/index.blade.php
    }

    // Tambahkan metode lain jika diperlukan
}