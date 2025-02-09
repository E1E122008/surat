<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
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

        // Data untuk grafik bulanan
        $months = collect(range(0, 11))->map(function($month) {
            return Carbon::now()->subMonths($month)->format('M Y');
        })->reverse()->values();

        $monthlyData = [
            'labels' => $months,
            'sppdDalam' => $this->getMonthlyCount(SppdDalamDaerah::class, $months),
            'sppdLuar' => $this->getMonthlyCount(SppdLuarDaerah::class, $months),
            'sptDalam' => $this->getMonthlyCount(SptDalamDaerah::class, $months),
            'sptLuar' => $this->getMonthlyCount(SptLuarDaerah::class, $months),
        ];

        return view('dashboard', compact(
            'sppdDalamCount',
            'sppdLuarCount',
            'sptDalamCount',
            'sptLuarCount',
            'monthlyData'
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
} 