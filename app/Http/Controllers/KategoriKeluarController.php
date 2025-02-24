<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\sptDalamDaerah;
use App\Models\sptLuarDaerah;
use Carbon\Carbon;

class KategoriKeluarController extends Controller
{
    public function index(Request $request)
    {   
        $activeTab = $request->input('tab', 'surat-keluar');

        // Ambil filter waktu dari request
        $filterWaktuSuratKeluar = $request->input('waktuSuratKeluar', 'bulan');
        $filterWaktuSppdDalamDaerah = $request->input('waktuSppdDalamDaerah', 'bulan');
        $filterWaktuSppdLuarDaerah = $request->input('waktuSppdLuarDaerah', 'bulan');
        $filterWaktuSptDalamDaerah = $request->input('waktuSptDalamDaerah', 'bulan');
        $filterWaktuSptLuarDaerah = $request->input('waktuSptLuarDaerah', 'bulan');


        // Query data
        $querySuratKeluar = SuratKeluar::query();
        $querySppdDalamDaerah = SppdDalamDaerah::query();
        $querySppdLuarDaerah = SppdLuarDaerah::query();
        $querySptDalamDaerah = sptDalamDaerah::query();
        $querySptLuarDaerah = sptLuarDaerah::query();


        // Filter waktu untuk Surat Keluar
        if ($filterWaktuSuratKeluar == 'minggu') {
            $querySuratKeluar->whereBetween('tanggal_surat', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filterWaktuSuratKeluar == 'bulan') {
            $querySuratKeluar->whereMonth('tanggal_surat', Carbon::now()->month)
                             ->whereYear('tanggal_surat', Carbon::now()->year);
        } elseif ($filterWaktuSuratKeluar == 'tahun') {
            $querySuratKeluar->whereYear('tanggal_surat', Carbon::now()->year);
        }

        // Filter waktu untuk SPPD Dalam Daerah
        if ($filterWaktuSppdDalamDaerah == 'minggu') {
            $querySppdDalamDaerah->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filterWaktuSppdDalamDaerah == 'bulan') {
            $querySppdDalamDaerah->whereMonth('tanggal', Carbon::now()->month)
                                 ->whereYear('tanggal', Carbon::now()->year);
        } elseif ($filterWaktuSppdDalamDaerah == 'tahun') {
            $querySppdDalamDaerah->whereYear('tanggal', Carbon::now()->year);
        }

        // Filter waktu untuk SPPD Luar Daerah
        if ($filterWaktuSppdLuarDaerah == 'minggu') {
            $querySppdLuarDaerah->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filterWaktuSppdLuarDaerah == 'bulan') {
            $querySppdLuarDaerah->whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year);
        } elseif ($filterWaktuSppdLuarDaerah == 'tahun') {
            $querySppdLuarDaerah->whereYear('tanggal', Carbon::now()->year);
        }

        // Filter waktu untuk SPT Dalam Daerah
        if ($filterWaktuSptDalamDaerah == 'minggu') {
            $querySptDalamDaerah->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filterWaktuSptDalamDaerah == 'bulan') {
            $querySptDalamDaerah->whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year);
        } elseif ($filterWaktuSptDalamDaerah == 'tahun') {
            $querySptDalamDaerah->whereYear('tanggal', Carbon::now()->year);
        }

        // Filter waktu untuk SPT Luar Daerah
        if ($filterWaktuSptLuarDaerah == 'minggu') {
            $querySptLuarDaerah->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filterWaktuSptLuarDaerah == 'bulan') {
            $querySptLuarDaerah->whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year);
        } elseif ($filterWaktuSptLuarDaerah == 'tahun') {
            $querySptLuarDaerah->whereYear('tanggal', Carbon::now()->year);
        }
        

        // Eksekusi query
        $suratKeluar = $querySuratKeluar->get();
        $sppdDalamDaerah = $querySppdDalamDaerah->get();
        $sppdLuarDaerah = $querySppdLuarDaerah->get();
        $sptDalamDaerah = $querySptDalamDaerah->get();
        $sptLuarDaerah = $querySptLuarDaerah->get();

        // Kirim data ke view
        return view('layouts.buku-agenda.kategori-keluar.index', compact(
            'suratKeluar', 'activeTab', 'sppdDalamDaerah', 'sppdLuarDaerah', 'sptDalamDaerah', 'sptLuarDaerah'
        ));
    }
}
