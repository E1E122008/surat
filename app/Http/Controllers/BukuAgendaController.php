<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\Sk;
use App\Models\Perda;
use App\Models\Pergub;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgendaMasukExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuAgendaController extends Controller
{
    public function index(Request $request)
    {
        // Jika tidak ada parameter tab, redirect ke tab default
        if (!$request->has('tab')) {
            return redirect()->route('buku-agenda.index', ['tab' => 'surat-masuk']);
        }

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
        $query = SuratMasuk::query();
        $querySK = Sk::query();
        $queryPerda = Perda::query();
        $queryPergub = Pergub::query();

        // Logika pencarian
        if ($request->has('search')) {
            $search = $request->search;
            
            // Pencarian untuk setiap jenis surat
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('disposisi', 'LIKE', "%{$search}%");
            });

            $querySK->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('disposisi', 'LIKE', "%{$search}%");
            });

            $queryPerda->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('disposisi', 'LIKE', "%{$search}%");
            });

            $queryPergub->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('disposisi', 'LIKE', "%{$search}%");
            });
        }

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
                    
                    $query->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $querySK->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $queryPerda->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $queryPergub->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    break;

                case 'bulan':
                    $month = $request->bulan;
                    $query->whereMonth('tanggal_terima', $month)
                          ->whereYear('tanggal_terima', now()->year);
                    $querySK->whereMonth('tanggal_terima', $month)
                            ->whereYear('tanggal_terima', now()->year);
                    $queryPerda->whereMonth('tanggal_terima', $month)
                              ->whereYear('tanggal_terima', now()->year);
                    $queryPergub->whereMonth('tanggal_terima', $month)
                              ->whereYear('tanggal_terima', now()->year);
                    break;

                case 'tahun':
                    $year = $request->tahun;
                    $query->whereYear('tanggal_terima', $year);
                    $querySK->whereYear('tanggal_terima', $year);
                    $queryPerda->whereYear('tanggal_terima', $year);
                    $queryPergub->whereYear('tanggal_terima', $year);
                    break;

                default:
                    // Jika tidak ada filter atau "Tampilkan Semua" dipilih
                    break;
            }
        }

        // Eksekusi query
        $suratMasuk = $query->orderBy('created_at', 'desc')->get();
        $sk = $querySK->orderBy('created_at', 'desc')->get();
        $perda = $queryPerda->orderBy('created_at', 'desc')->get();
        $pergub = $queryPergub->orderBy('created_at', 'desc')->get();

        // Tambahkan perhitungan total surat
        $totalSurat = [
            'surat_masuk' => $suratMasuk->count(),
            'sk' => $sk->count(),
            'perda' => $perda->count(),
            'pergub' => $pergub->count()
        ];

        // Kirim data ke view
        return view('layouts.buku-agenda.index', compact(
            'suratMasuk', 
            'activeTab', 
            'sk', 
            'perda', 
            'pergub',
            'filterInfo',
            'totalSurat'
        ));
    }
    
    public function export(Request $request)
    {
        $filterType = $request->filterType;
        $tab = $request->tab ?? 'surat-masuk';
        
        // Tentukan nama file berdasarkan tab dan filter
        $prefix = match($tab) {
            'surat-masuk' => 'surat-masuk',
            'surat-keputusan' => 'sk',
            'perda' => 'perda',
            'pergub' => 'pergub',
            default => 'surat-masuk'
        };
        
        // Tambahkan info filter ke nama file jika ada
        $filterInfo = '';
        if ($filterType) {
            $filterInfo = match($filterType) {
                'minggu' => "-minggu-{$request->mingguKe}-bulan-{$request->bulan}",
                'bulan' => "-bulan-{$request->bulan}",
                'tahun' => "-tahun-{$request->tahun}",
                default => ''
            };
        }
        
        // Generate nama file
        $fileName = $prefix . $filterInfo . '-' . date('Y-m-d-His') . '.xlsx';

        return Excel::download(new AgendaMasukExport(
            $filterType,
            $request->mingguKe,
            $request->bulan,
            $request->tahun,
            $tab
        ), $fileName);
    }

    public function exportPDF(Request $request)
    {
        $tab = $request->tab ?? 'surat-masuk';
        
        // Inisialisasi query berdasarkan tab
        switch($tab) {
            case 'surat-masuk':
                $query = SuratMasuk::query();
                $title = 'Arsip Surat Masuk';
                break;
            case 'surat-keputusan':
                $query = Sk::query();
                $title = 'Arsip Surat Keputusan';
                break;
            case 'perda':
                $query = Perda::query();
                $title = 'Arsip Peraturan Daerah';
                break;
            case 'pergub':
                $query = Pergub::query();
                $title = 'Arsip Peraturan Gubernur';
                break;
            default:
                $query = SuratMasuk::query();
                $title = 'Arsip Surat Masuk';
        }

        // Terapkan filter jika ada
        if ($request->has('filterType')) {
            switch ($request->filterType) {
                case 'minggu':
                    $weekNumber = $request->mingguKe;
                    $currentMonth = Carbon::create(null, $request->bulan ?? now()->month);
                    
                    switch($weekNumber) {
                        case 1:
                            $startDate = $currentMonth->copy()->startOfMonth();
                            $endDate = $currentMonth->copy()->startOfMonth()->addDays(6);
                            break;
                        case 2:
                            $startDate = $currentMonth->copy()->startOfMonth()->addDays(7);
                            $endDate = $currentMonth->copy()->startOfMonth()->addDays(13);
                            break;
                        case 3:
                            $startDate = $currentMonth->copy()->startOfMonth()->addDays(14);
                            $endDate = $currentMonth->copy()->startOfMonth()->addDays(20);
                            break;
                        case 4:
                            $startDate = $currentMonth->copy()->startOfMonth()->addDays(21);
                            $endDate = $currentMonth->copy()->endOfMonth();
                            break;
                        default:
                            $startDate = $currentMonth->copy()->startOfMonth();
                            $endDate = $currentMonth->copy()->endOfMonth();
                    }
                    
                    $query->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    $filterInfo = "Minggu ke-{$weekNumber} Bulan " . $currentMonth->format('F Y');
                    break;

                case 'bulan':
                    $month = $request->bulan;
                    $year = $request->tahun ?? now()->year;
                    $query->whereMonth('tanggal_terima', $month)
                          ->whereYear('tanggal_terima', $year);
                    $filterInfo = "Bulan " . Carbon::create(null, $month, 1)->format('F') . " {$year}";
                    break;

                case 'tahun':
                    $year = $request->tahun;
                    $query->whereYear('tanggal_terima', $year);
                    $filterInfo = "Tahun {$year}";
                    break;

                default:
                    $filterInfo = "Semua Data";
            }
        } else {
            $filterInfo = "Semua Data";
        }

        // Ambil data
        $data = $query->orderBy('tanggal_terima', 'desc')->get();

        // Generate PDF
        $pdf = PDF::loadView('layouts.buku-agenda.pdf', [
            'title' => $title,
            'filterInfo' => $filterInfo,
            'data' => $data
        ]);

        // Set paper ke landscape untuk data yang banyak
        $pdf->setPaper('a4', 'landscape');

        // Download PDF
        return $pdf->download($title . ' - ' . $filterInfo . '.pdf');
    }
}
