<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\sptDalamDaerah;
use App\Models\sptLuarDaerah;
use Carbon\Carbon;
use App\Exports\AgendaKeluarExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $querySptDalamDaerah = sptDalamDaerah::query();
        $querySptLuarDaerah = sptLuarDaerah::query();

        // Logika pencarian
        if ($request->has('search')) {
            $search = $request->search;
            
            // Pencarian untuk Surat Keluar
            $querySuratKeluar->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });

            // Pencarian untuk SPPD Dalam Daerah
            $querySppdDalamDaerah->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });

            // Pencarian untuk SPPD Luar Daerah
            $querySppdLuarDaerah->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });

            // Pencarian untuk SPT Dalam Daerah
            $querySptDalamDaerah->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });

            // Pencarian untuk SPT Luar Daerah
            $querySptLuarDaerah->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
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

        // Hitung total surat
        $totalSurat = [
            'surat_keluar' => $suratKeluar->count(),
            'sppd_dalam' => $sppdDalamDaerah->count(),
            'sppd_luar' => $sppdLuarDaerah->count(),
            'spt_dalam' => $sptDalamDaerah->count(),
            'spt_luar' => $sptLuarDaerah->count()
        ];

        // Kirim data ke view
        return view('layouts.buku-agenda.kategori-keluar.index', compact(
            'suratKeluar',
            'sppdDalamDaerah',
            'sppdLuarDaerah',
            'sptDalamDaerah',
            'sptLuarDaerah',
            'activeTab',
            'filterInfo',
            'totalSurat'
        ));
    }

    public function export(Request $request)
    {
        $filterType = $request->filterType;
        $tab = $request->tab ?? 'surat-keluar';
        
        // Debug untuk melihat parameter yang diterima
        \Log::info('Export Parameters:', [
            'tab' => $tab,
            'filterType' => $filterType,
            'mingguKe' => $request->mingguKe,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun
        ]);

        // Validasi tab yang valid
        if (!in_array($tab, ['surat-keluar', 'sppd-dalam', 'sppd-luar', 'spt-dalam', 'spt-luar'])) {
            $tab = 'surat-keluar';
        }
        
        $prefix = match($tab) {
            'surat-keluar' => 'surat-keluar',
            'sppd-dalam' => 'sppd-dalam-daerah',
            'sppd-luar' => 'sppd-luar-daerah',
            'spt-dalam' => 'spt-dalam-daerah',
            'spt-luar' => 'spt-luar-daerah',
            default => 'surat-keluar'
        };
        
        $filterInfo = '';
        if ($filterType) {
            $filterInfo = match($filterType) {
                'minggu' => "-minggu-{$request->mingguKe}-bulan-{$request->bulan}",
                'bulan' => "-bulan-{$request->bulan}",
                'tahun' => "-tahun-{$request->tahun}",
                default => ''
            };
        }
        
        $fileName = $prefix . $filterInfo . '-' . date('Y-m-d-His') . '.xlsx';

        return Excel::download(new AgendaKeluarExport(
            $filterType,
            $request->mingguKe,
            $request->bulan,
            $request->tahun,
            $tab
        ), $fileName);
    }
}
