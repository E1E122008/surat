<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SK;
use App\Models\Perda;
use App\Models\Pergub;


class DisposisiController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::all();
        $sk = SK::all();
        $pergub = Pergub::all();
        $perda = Perda::all();
        return view('disposisi.index', compact('suratMasuk', 'sk', 'pergub', 'perda'));
    }
    public function detail($id)
    {
        $suratMasuk = SuratMasuk::find($id);
        return view('disposisi.detail', compact('suratMasuk'));
    }
}