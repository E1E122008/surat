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

        // Data untuk grafik - mengambil data 6 bulan terakhir
        $months = collect(range(5, 0))->map(function($i) {
            return now()->startOfMonth()->subMonths($i);
        });

        $suratMasukData = $months->map(function($month) {
            return SuratMasuk::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        });

        $skData = $months->map(function($month) {
            return SK::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        });

        $perdaData = $months->map(function($month) {
            return Perda::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        });

        $pergubData = $months->map(function($month) {
            return Pergub::whereYear('tanggal_terima', $month->year)
                ->whereMonth('tanggal_terima', $month->month)
                ->count();
        });

        // Data untuk grafik surat keluar
        $suratKeluarData = $months->map(function($month) {
            return SuratKeluar::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        });

        $sppdDalamData = $months->map(function($month) {
            return SppdDalamDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        });

        $sppdLuarData = $months->map(function($month) {
            return SppdLuarDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        });

        $sptDalamData = $months->map(function($month) {
            return SptDalamDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        });

        $sptLuarData = $months->map(function($month) {
            return SptLuarDaerah::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();
        });

        $labels = $months->map(function($month) {
            return $month->format('M Y');
        });

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
        
        // Tentukan rentang waktu berdasarkan filter
        if ($period === 'minggu') {
            $startDate = now()->subWeeks(7);
            $months = collect(range(0, 7))->map(function($i) {
                return now()->subWeeks($i);
            })->reverse();
            $labels = $months->map(function($date) {
                return $date->format('d M');
            });
        } elseif ($period === 'tahun') {
            $startDate = now()->subYears(1);
            $months = collect(range(0, 11))->map(function($i) {
                return now()->subMonths($i);
            })->reverse();
            $labels = $months->map(function($date) {
                return $date->format('M Y');
            });
        } else { // bulan
            $startDate = now()->subMonths(6);
            $months = collect(range(0, 6))->map(function($i) {
                return now()->subMonths($i);
            })->reverse();
            $labels = $months->map(function($date) {
                return $date->format('M Y');
            });
        }

        // Query data sesuai periode
        $suratMasukData = $months->map(function($date) use ($period) {
            $query = SuratMasuk::query();
            if ($period === 'minggu') {
                return $query->whereDate('tanggal_terima', $date->format('Y-m-d'))->count();
            } else {
                return $query->whereYear('tanggal_terima', $date->year)
                            ->whereMonth('tanggal_terima', $date->month)
                            ->count();
            }
        });

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
        });

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
        });

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
        });

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
        });

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
        });

        return response()->json([
            'labels' => $labels,
            'suratMasukData' => $suratMasukData,
            'skData' => $skData ?? [],
            'perdaData' => $perdaData ?? [],
            'pergubData' => $pergubData ?? [],
            'suratKeluarData' => $suratKeluarData,
            'sppdDalamData' => $sppdDalamData,
            'sppdLuarData' => $sppdLuarData,
            'sptDalamData' => $sptDalamData,
            'sptLuarData' => $sptLuarData,
        ]);
    }
} 