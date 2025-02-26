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
        $querySuratMasuk = SuratMasuk::query();
        $querySk = Sk::query();
        $queryPerda = Perda::query();
        $queryPergub = Pergub::query();

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
                    
                    $querySuratMasuk->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $querySk->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $queryPerda->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $queryPergub->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    break;

                case 'bulan':
                    $month = $request->bulan;
                    $querySuratMasuk->whereMonth('tanggal_terima', $month)
                                    ->whereYear('tanggal_terima', now()->year);
                    $querySk->whereMonth('tanggal_terima', $month)
                            ->whereYear('tanggal_terima', now()->year);
                    $queryPerda->whereMonth('tanggal_terima', $month)
                              ->whereYear('tanggal_terima', now()->year);
                    $queryPergub->whereMonth('tanggal_terima', $month)
                              ->whereYear('tanggal_terima', now()->year);
                    break;

                case 'tahun':
                    $year = $request->tahun;
                    $querySuratMasuk->whereYear('tanggal_terima', $year);
                    $querySk->whereYear('tanggal_terima', $year);
                    $queryPerda->whereYear('tanggal_terima', $year);
                    $queryPergub->whereYear('tanggal_terima', $year);
                    break;

                default:
                    // Jika tidak ada filter atau "Tampilkan Semua" dipilih
                    break;
            }
        }

        // Eksekusi query
        $suratMasuk = $querySuratMasuk->get();
        $sk = $querySk->get();
        $perda = $queryPerda->get();
        $pergub = $queryPergub->get();

        // Kirim data ke view
        return view('layouts.buku-agenda.index', compact(
            'suratMasuk', 
            'activeTab', 
            'sk', 
            'perda', 
            'pergub',
            'filterInfo'
        ));
    }
    
}
