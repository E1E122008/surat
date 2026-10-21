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
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->startOfMonth());
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
        $now = Carbon::now();

        // Setiap titik grafik adalah satu rentang tanggal [awal, akhir]
        if ($period === 'minggu') {
            // 8 minggu terakhir, dihitung penuh Senin s/d Minggu
            $rentang = collect(range(7, 0))->map(function ($i) use ($now) {
                $awal = $now->copy()->subWeeks($i)->startOfWeek();
                return [$awal, $awal->copy()->endOfWeek(), $awal->format('d M')];
            });
        } elseif ($period === 'tahun') {
            // Total per tahun untuk 5 tahun terakhir
            $rentang = collect(range(4, 0))->map(function ($i) use ($now) {
                $awal = $now->copy()->subYears($i)->startOfYear();
                return [$awal, $awal->copy()->endOfYear(), $awal->format('Y')];
            });
        } else {
            // 6 bulan terakhir
            $rentang = collect(range(5, 0))->map(function ($i) use ($now) {
                $awal = $now->copy()->subMonthsNoOverflow($i)->startOfMonth();
                return [$awal, $awal->copy()->endOfMonth(), $awal->format('M Y')];
            });
        }

        $hitung = function (string $model, string $kolom) use ($rentang) {
            return $rentang->map(fn ($r) => $model::whereBetween($kolom, [$r[0]->toDateString(), $r[1]->toDateString()])->count())->values();
        };

        return response()->json([
            'labels' => $rentang->map(fn ($r) => $r[2])->values(),
            'suratMasukData' => $hitung(SuratMasuk::class, 'tanggal_terima'),
            'skData' => $hitung(SK::class, 'tanggal_terima'),
            'perdaData' => $hitung(Perda::class, 'tanggal_terima'),
            'pergubData' => $hitung(Pergub::class, 'tanggal_terima'),
            'suratKeluarData' => $hitung(SuratKeluar::class, 'tanggal'),
            'sppdDalamData' => $hitung(SppdDalamDaerah::class, 'tanggal'),
            'sppdLuarData' => $hitung(SppdLuarDaerah::class, 'tanggal'),
            'sptDalamData' => $hitung(SptDalamDaerah::class, 'tanggal'),
            'sptLuarData' => $hitung(SptLuarDaerah::class, 'tanggal'),
        ]);
    }
} 