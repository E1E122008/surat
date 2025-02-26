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
        // Jika tidak ada parameter tab, redirect ke tab default
        if (!$request->has('tab')) {
            return redirect()->route('buku-agenda.kategori-keluar.index', ['tab' => 'surat-keluar']);
        }

        $activeTab = $request->input('tab', 'surat-keluar');

        // Ambil informasi filter
        $filterInfo = null;
        if ($request->has('filterType')) {
            switch ($request->filterType) {
                case 'minggu':
                    $bulan = Carbon::create(null, $request->input('bulan', now()->month))->format('F');
                    $tahun = $request->input('tahun', now()->year);
                    $filterInfo = "Minggu ke-{$request->mingguKe} {$bulan} {$tahun}";
                    break;
                case 'bulan':
                    $bulan = Carbon::create(null, $request->bulan)->format('F');
                    $tahun = $request->input('tahun', now()->year);
                    $filterInfo = "Bulan {$bulan} {$tahun}";
                    break;
                case 'tahun':
                    $filterInfo = "Tahun {$request->tahun}";
                    break;
            }
        }

        // Inisialisasi query
        $querySuratKeluar = SuratKeluar::query();
        $querySppdDalamDaerah = SppdDalamDaerah::query();
        $querySppdLuarDaerah = SppdLuarDaerah::query();
        $querySptDalamDaerah = SptDalamDaerah::query();
        $querySptLuarDaerah = SptLuarDaerah::query();

        // Handle filter dari modal
        if ($request->has('filterType')) {
            switch ($request->filterType) {
                case 'minggu':
                    $weekNumber = $request->mingguKe;
                    $currentMonth = now()->startOfMonth();

                    switch($weekNumber) {
                        case 1:
                            $startDate = $currentMonth->copy(); // Tanggal 1-7
                            $endDate = $currentMonth->copy()->addDays(6);
                            break;
                        case 2:
                            $startDate = $currentMonth->copy()->addDays(7); // Tanggal 8-14
                            $endDate = $currentMonth->copy()->addDays(13);
                            break;
                        case 3:
                            $startDate = $currentMonth->copy()->addDays(14); // Tanggal 15-21
                            $endDate = $currentMonth->copy()->addDays(20);
                            break;
                        case 4:
                            $startDate = $currentMonth->copy()->addDays(21); // Tanggal 22-akhir bulan
                            $endDate = $currentMonth->copy()->endOfMonth();
                            break;
                    }

                    $querySuratKeluar->whereBetween('tanggal', [$startDate, $endDate]);
                    $querySppdDalamDaerah->whereBetween('tanggal', [$startDate, $endDate]);
                    $querySppdLuarDaerah->whereBetween('tanggal', [$startDate, $endDate]);
                    $querySptDalamDaerah->whereBetween('tanggal', [$startDate, $endDate]);
                    $querySptLuarDaerah->whereBetween('tanggal', [$startDate, $endDate]);
                    break;

                case 'bulan':
                    $month = $request->bulan;
                    $querySuratKeluar->whereMonth('tanggal', $month)
                                    ->whereYear('tanggal', now()->year);
                    $querySppdDalamDaerah->whereMonth('tanggal', $month)
                                    ->whereYear('tanggal', now()->year);
                    $querySppdLuarDaerah->whereMonth('tanggal', $month)
                                    ->whereYear('tanggal', now()->year);
                    $querySptDalamDaerah->whereMonth('tanggal', $month)
                                    ->whereYear('tanggal', now()->year);
                    $querySptLuarDaerah->whereMonth('tanggal', $month)
                                    ->whereYear('tanggal', now()->year);
                    break;

                case 'tahun':
                    $year = $request->tahun;
                    $querySuratKeluar->whereYear('tanggal', $year);
                    $querySppdDalamDaerah->whereYear('tanggal', $year);
                    $querySppdLuarDaerah->whereYear('tanggal', $year); 
                    $querySptDalamDaerah->whereYear('tanggal', $year);
                    $querySptLuarDaerah->whereYear('tanggal', $year);
                    break;

                default:
                    // Jika tidak ada filter atau "Tampilkan Semua" dipilih
                    break;
            }
        }

        // Eksekusi query
        $suratKeluar = $querySuratKeluar->get();
        $sppdDalamDaerah = $querySppdDalamDaerah->get();
        $sppdLuarDaerah = $querySppdLuarDaerah->get();
        $sptDalamDaerah = $querySptDalamDaerah->get();
        $sptLuarDaerah = $querySptLuarDaerah->get();

        // Kirim data ke view
        return view('layouts.buku-agenda.kategori-keluar.index', compact(
            'suratKeluar',
            'sppdDalamDaerah',
            'sppdLuarDaerah',
            'sptDalamDaerah',
            'sptLuarDaerah',
            'activeTab',
            'filterInfo'
        ));
    }
}
