<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

class BukuAgendaController extends Controller
{
    public function index()
    {
        // Mengambil data dari model
        $incomingLetters = SuratMasuk::all(); // Mengambil semua data surat masuk
        $outgoingLetters = SuratKeluar::all(); // Mengambil semua data surat keluar
        $sppdInternal = SppdDalamDaerah::all(); // Mengambil semua data SPPD dalam daerah
        $sppdExternal = SppdLuarDaerah::all(); // Mengambil semua data SPPD luar daerah
        $sptInternal = SptDalamDaerah::all(); // Mengambil semua data SPT dalam daerah
        $sptExternal = SptLuarDaerah::all(); // Mengambil semua data SPT luar daerah

        return view('layouts.Buku Agenda.index', compact(
            'incomingLetters',
            'outgoingLetters',
            'sppdInternal',
            'sppdExternal',
            'sptInternal',
            'sptExternal'
        ));
    }

    // Tambahkan metode lain jika diperlukan
}
