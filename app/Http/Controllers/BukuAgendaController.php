<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\Sk;
use App\Models\Perda;
use App\Models\Pergub;

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
        $filterWaktuPerda = $request->input('waktuPerda', 'bulan');
        $filterWaktuPergub = $request->input('waktuPergub', 'bulan');   

        // Inisialisasi query untuk Surat Masuk
        $querySuratMasuk = SuratMasuk::query();
        $querySk = Sk::query();
        $queryPerda = Perda::query();
        $queryPergub = Pergub::query();

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

        // Filter waktu untuk Perda
        if ($filterWaktuPerda == 'minggu') {
            $queryPerda->whereBetween('tanggal_terima', [
                Carbon::now()->startOfWeek()->format('Y-m-d'), 
                Carbon::now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($filterWaktuPerda == 'bulan') {
            $queryPerda->whereMonth('tanggal_terima', Carbon::now()->format('m'))
                    ->whereYear('tanggal_terima', Carbon::now()->format('Y'));
        } elseif ($filterWaktuPerda == 'tahun') {
            $queryPerda->whereYear('tanggal_terima', Carbon::now()->format('Y'));
        }

        // Filter waktu untuk Pergub
        if ($filterWaktuPergub == 'minggu') {
            $queryPergub->whereBetween('tanggal_terima', [
                Carbon::now()->startOfWeek()->format('Y-m-d'), 
                Carbon::now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($filterWaktuPergub == 'bulan') {
            $queryPergub->whereMonth('tanggal_terima', Carbon::now()->format('m'))
                    ->whereYear('tanggal_terima', Carbon::now()->format('Y'));
        } elseif ($filterWaktuPergub == 'tahun') {
            $queryPergub->whereYear('tanggal_terima', Carbon::now()->format('Y'));
        }
        
        
        
        

        // Eksekusi query
        $suratMasuk = $querySuratMasuk->get();
        $sk = $querySk->get();
        $perda = $queryPerda->get();
        $pergub = $queryPergub->get();

        // Kirim data ke view
        return view('layouts.buku-agenda.index', compact('suratMasuk', 'activeTab', 'sk', 'perda', 'pergub'));
    }
    
}
