<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\Sk;
use Carbon\Carbon;

class BukuAgendaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tab aktif dari request, default ke 'surat-masuk'
        $activeTab = $request->input('tab', 'surat-masuk');

        // Ambil filter waktu dari request (default: bulan ini)
        $filterWaktuSuratMasuk = $request->input('waktuSuratMasuk', 'bulan');
        $filterWaktuKeputusan = $request->input('waktuSuratKeputusan', 'bulan');

        // Inisialisasi query untuk Surat Masuk
        $querySuratMasuk = SuratMasuk::query();
        $querySk = Sk::query();

        // Filter waktu Surat Masuk
        if ($filterWaktuSuratMasuk == 'minggu') {
            $querySuratMasuk->whereBetween('tanggal_terima', [
                Carbon::now()->startOfWeek()->format('Y-m-d'), 
                Carbon::now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($filterWaktuSuratMasuk == 'bulan') {
            $querySuratMasuk->whereMonth('tanggal_terima', Carbon::now()->format('m'))
                            ->whereYear('tanggal_terima', Carbon::now()->format('Y'));
        } elseif ($filterWaktuSuratMasuk == 'tahun') {
            $querySuratMasuk->whereYear('tanggal_terima', Carbon::now()->format('Y'));
        }

        // Filter waktu untuk Surat Keputusan
        if ($filterWaktuKeputusan == 'minggu') {
            $querySk->whereBetween('tanggal_surat', [
                Carbon::now()->startOfWeek()->format('Y-m-d'), 
                Carbon::now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($filterWaktuKeputusan == 'bulan') {
            $querySk->whereMonth('tanggal_surat', Carbon::now()->format('m'))
                    ->whereYear('tanggal_surat', Carbon::now()->format('Y'));
        } elseif ($filterWaktuKeputusan == 'tahun') {
            $querySk->whereYear('tanggal_surat', Carbon::now()->format('Y'));
        }

        // Eksekusi query
        $suratMasuk = $querySuratMasuk->get();
        $sk = $querySk->get();

        // Kirim data ke view
        return view('layouts.buku-agenda.index', compact('suratMasuk', 'activeTab', 'sk'));
    }
    
}
