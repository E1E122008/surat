<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SK;
use App\Models\Perda;
use App\Models\Pergub;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total data
        $sppdDalamCount = SppdDalamDaerah::count();
        $sppdLuarCount = SppdLuarDaerah::count();
        $sptDalamCount = SptDalamDaerah::count();
        $sptLuarCount = SptLuarDaerah::count();
        $skCount = SK::count();
        $perdaCount = Perda::count();
        $pergubCount = Pergub::count();
        $jumlahSuratMasuk = SuratMasuk::count();
        $jumlahSuratKeluar = SuratKeluar::count();

        $draftphd = $skCount + $perdaCount + $pergubCount;
        $sppdCount = $sppdDalamCount + $sppdLuarCount;
        $sptCount = $sptDalamCount + $sptLuarCount;

        // Data untuk grafik - mulai dari tahun 2026, menampilkan 5 bulan ke depan dari bulan sekarang
        // Jika sekarang Jan 2026 → tampilkan: Jan, Feb, Mar, Apr, Mei 2026 (5 bulan)
        // Jika sekarang Maret 2026 → tampilkan: Mar, Apr, Mei, Jun, Jul 2026 (5 bulan)
        $now = Carbon::now();
        $startYear = 2026;
        
        // Tentukan bulan mulai (bulan sekarang)
        $currentMonth = $now->month;
        $currentYear = $now->year;
        
        // Jika tahun sekarang < 2026, mulai dari Jan 2026
        if ($currentYear < $startYear) {
            $currentYear = $startYear;
            $currentMonth = 1;
        }
        
        // Mulai dari bulan sekarang, tampilkan 5 bulan ke depan
        $startMonth = $currentMonth;
        $endMonth = min(12, $currentMonth + 4); // Maksimal sampai Desember
        
        // Generate array bulan dari startMonth sampai endMonth (5 bulan)
        $months = collect();
        for ($month = $startMonth; $month <= $endMonth; $month++) {
            $months->push(Carbon::create($currentYear, $month, 1)->startOfMonth());
        }

        $suratMasukData = $months->map(function($month) {
            return SuratMasuk::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        })->values();

        $skData = $months->map(function($month) {
            return SK::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        })->values();

        $perdaData = $months->map(function($month) {
            return Perda::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        })->values();

        $pergubData = $months->map(function($month) {
            return Pergub::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        })->values();

        // Data untuk grafik surat keluar
        $suratKeluarData = $months->map(function($month) {
            return SuratKeluar::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        })->values();

        $sppdDalamData = $months->map(function($month) {
            return SppdDalamDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        })->values();

        $sppdLuarData = $months->map(function($month) {
            return SppdLuarDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        })->values();

        $sptDalamData = $months->map(function($month) {
            return SptDalamDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        })->values();

        $sptLuarData = $months->map(function($month) {
            return SptLuarDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        })->values();

        $labels = $months->map(function($month) {
            return $month->format('M Y');
        })->values();

        return view('dashboard', compact(
            'sppdDalamCount',
            'sppdLuarCount',
            'sptDalamCount',
            'sptLuarCount',
            'jumlahSuratMasuk',
            'jumlahSuratKeluar',
            'draftphd',
            'sppdCount',
            'sptCount',
            'labels',
            'suratMasukData',
            'skData',
            'perdaData',
            'pergubData',
            'suratKeluarData',
            'sppdDalamData',
            'sppdLuarData',
            'sptDalamData',
            'sptLuarData'
        ));
    }

    private function getMonthlyCount($model, $months)
    {
        return $months->map(function($month) use ($model) {
            $date = Carbon::createFromFormat('M Y', $month);
            return $model::whereYear('tanggal', $date->year)
                        ->whereMonth('tanggal', $date->month)
                        ->count();
        })->values();
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'bulan');
        
        // Tentukan rentang waktu berdasarkan filter - selalu menggunakan Carbon::now() untuk data terbaru
        if ($period === 'minggu') {
            $startDate = Carbon::now()->subWeeks(7);
            $months = collect(range(0, 7))->map(function($i) {
                return Carbon::now()->subWeeks($i);
            })->reverse();
            $labels = $months->map(function($date) {
                return $date->format('d M');
            });
        } elseif ($period === 'tahun') {
            $startDate = Carbon::now()->subYears(1);
            $months = collect(range(0, 11))->map(function($i) {
                return Carbon::now()->subMonths($i);
            })->reverse();
            $labels = $months->map(function($date) {
                return $date->format('M Y');
            });
        } else { // bulan
            // Mulai dari tahun 2026, menampilkan 5 bulan ke depan dari bulan sekarang
            // Jika sekarang Jan 2026 → tampilkan: Jan, Feb, Mar, Apr, Mei 2026 (5 bulan)
            // Jika sekarang Maret 2026 → tampilkan: Mar, Apr, Mei, Jun, Jul 2026 (5 bulan)
            $now = Carbon::now();
            $startYear = 2026;
            
            // Tentukan bulan mulai (bulan sekarang)
            $currentMonth = $now->month;
            $currentYear = $now->year;
            
            // Jika tahun sekarang < 2026, mulai dari Jan 2026
            if ($currentYear < $startYear) {
                $currentYear = $startYear;
                $currentMonth = 1;
            }
            
            // Mulai dari bulan sekarang, tampilkan 5 bulan ke depan
            $startMonth = $currentMonth;
            $endMonth = min(12, $currentMonth + 4); // Maksimal sampai Desember
            
            // Generate array bulan dari startMonth sampai endMonth (5 bulan)
            $months = collect();
            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $months->push(Carbon::create($currentYear, $month, 1)->startOfMonth());
            }
            
            $labels = $months->map(function($date) {
                return $date->format('M Y');
            })->values();
        }

        // Query data sesuai periode - selalu menggunakan data terbaru
        $suratMasukData = $months->map(function($date) use ($period) {
            $query = SuratMasuk::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal_terima', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal_terima', $date->year)
                            ->whereMonth('tanggal_terima', $date->month)
                            ->count();
            }
        })->values();
        
        // Query untuk SK, Perda, Pergub (hanya untuk periode bulan)
        $skData = [];
        $perdaData = [];
        $pergubData = [];
        
        if ($period === 'bulan') {
            $skData = $months->map(function($date) {
                return SK::whereYear('tanggal_terima', $date->year)
                        ->whereMonth('tanggal_terima', $date->month)
                        ->count();
            })->values();
            
            $perdaData = $months->map(function($date) {
                return Perda::whereYear('tanggal_terima', $date->year)
                        ->whereMonth('tanggal_terima', $date->month)
                        ->count();
            })->values();
            
            $pergubData = $months->map(function($date) {
                return Pergub::whereYear('tanggal_terima', $date->year)
                        ->whereMonth('tanggal_terima', $date->month)
                        ->count();
            })->values();
        }

        // Lakukan hal yang sama untuk data lainnya
        $suratKeluarData = $months->map(function($date) use ($period) {
            $query = SuratKeluar::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal', $date->year)
                            ->whereMonth('tanggal', $date->month)
                            ->count();
            }
        })->values();

        // Query untuk SPPD Dalam
        $sppdDalamData = $months->map(function($date) use ($period) {
            $query = SppdDalamDaerah::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal', $date->year)
                            ->whereMonth('tanggal', $date->month)
                            ->count();
            }
        })->values();

        // Query untuk SPPD Luar
        $sppdLuarData = $months->map(function($date) use ($period) {
            $query = SppdLuarDaerah::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal', $date->year)
                            ->whereMonth('tanggal', $date->month)
                            ->count();
            }
        })->values();

        // Query untuk SPT Dalam
        $sptDalamData = $months->map(function($date) use ($period) {
            $query = SptDalamDaerah::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal', $date->year)
                            ->whereMonth('tanggal', $date->month)
                            ->count();
            }
        })->values();

        // Query untuk SPT Luar
        $sptLuarData = $months->map(function($date) use ($period) {
            $query = SptLuarDaerah::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal', $date->year)
                            ->whereMonth('tanggal', $date->month)
                            ->count();
            }
        })->values();

        return response()->json([
            'labels' => $labels,
            'suratMasukData' => $suratMasukData,
            'skData' => $skData,
            'perdaData' => $perdaData,
            'pergubData' => $pergubData,
            'suratKeluarData' => $suratKeluarData,
            'sppdDalamData' => $sppdDalamData,
            'sppdLuarData' => $sppdLuarData,
            'sptDalamData' => $sptDalamData,
            'sptLuarData' => $sptLuarData,
        ]);
    }
} 