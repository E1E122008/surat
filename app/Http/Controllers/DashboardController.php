<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SK;
use App\Models\SkKaro;
use App\Models\Perda;
use App\Models\Pergub;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Card: jumlah surat pada tahun berjalan, memakai kolom tanggal yang sama dengan grafik
        $tahun = Carbon::now()->year;
        $sppdDalamCount = SppdDalamDaerah::whereYear('tanggal', $tahun)->count();
        $sppdLuarCount = SppdLuarDaerah::whereYear('tanggal', $tahun)->count();
        $sptDalamCount = SptDalamDaerah::whereYear('tanggal', $tahun)->count();
        $sptLuarCount = SptLuarDaerah::whereYear('tanggal', $tahun)->count();
        $skCount = SK::whereYear('tanggal_terima', $tahun)->count();
        $perdaCount = Perda::whereYear('tanggal_terima', $tahun)->count();
        $pergubCount = Pergub::whereYear('tanggal_terima', $tahun)->count();
        $jumlahSuratMasuk = SuratMasuk::whereYear('tanggal_terima', $tahun)->count();
        $jumlahSuratKeluar = SuratKeluar::whereYear('tanggal', $tahun)->count();
        $skKaroCount = SkKaro::whereYear('tanggal_sk', $tahun)->count();

        $draftphd = $skCount + $perdaCount + $pergubCount;
        $sppdCount = $sppdDalamCount + $sppdLuarCount;
        $sptCount = $sptDalamCount + $sptLuarCount;

        // Grafik bulanan (6 bulan terakhir), sama persis dengan data grafik di Pengaturan Sistem
        $grafik = $this->dataGrafik('bulan');

        return view('dashboard', compact(
            'sppdDalamCount',
            'sppdLuarCount',
            'sptDalamCount',
            'sptLuarCount',
            'jumlahSuratMasuk',
            'jumlahSuratKeluar',
            'skKaroCount',
            'tahun',
            'draftphd',
            'sppdCount',
            'sptCount'
        ) + $grafik);
    }

    public function getChartData(Request $request)
    {
        return response()->json($this->dataGrafik(
            $request->get('period', 'bulan'),
            $request->filled('akhir') ? (string) $request->get('akhir') : null
        ));
    }

    // Kolom tanggal tiap jenis surat yang dipakai grafik
    private const KOLOM_GRAFIK = [
        SuratMasuk::class => 'tanggal_terima',
        SK::class => 'tanggal_terima',
        Perda::class => 'tanggal_terima',
        Pergub::class => 'tanggal_terima',
        SuratKeluar::class => 'tanggal',
        SppdDalamDaerah::class => 'tanggal',
        SppdLuarDaerah::class => 'tanggal',
        SptDalamDaerah::class => 'tanggal',
        SptLuarDaerah::class => 'tanggal',
    ];

    /**
     * Bulan terakhir yang ditampilkan grafik bulanan (navigasi geser).
     * Selalu dibatasi: tidak melewati bulan sekarang, dan tidak mundur lebih jauh
     * dari 6 bulan yang memuat surat tertua.
     */
    private function batasGeserBulan(?string $akhir, Carbon $now): array
    {
        $bulanIni = $now->copy()->startOfMonth();

        $tertua = collect(self::KOLOM_GRAFIK)
            ->map(fn ($kolom, $model) => $model::min($kolom))
            ->filter()
            ->min();
        $akhirMin = $tertua
            ? Carbon::parse($tertua)->locale('id')->startOfMonth()->addMonthsNoOverflow(5)->min($bulanIni)
            : $bulanIni->copy();

        $dipilih = null;
        if ($akhir && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $akhir)) {
            $dipilih = Carbon::createFromFormat('!Y-m', $akhir)->locale('id');
        }
        $dipilih = ($dipilih ?? $bulanIni->copy())->max($akhirMin)->min($bulanIni);

        return [$dipilih, $akhirMin, $bulanIni];
    }

    /**
     * Data grafik surat per periode: 8 minggu, 6 bulan (default), atau 5 tahun terakhir.
     * Setiap titik dihitung dari rentang tanggal penuh [awal, akhir] untuk semua jenis surat.
     * Untuk periode bulanan, $akhir (format Y-m) menggeser jendela 6 bulan.
     */
    private function dataGrafik(string $period, ?string $akhir = null): array
    {
        $now = Carbon::now()->locale('id');
        $navigasi = [];

        if ($period === 'minggu') {
            // 8 minggu terakhir, dihitung penuh Senin s/d Minggu
            $rentang = collect(range(7, 0))->map(function ($i) use ($now) {
                $awal = $now->copy()->subWeeks($i)->startOfWeek();
                return [$awal, $awal->copy()->endOfWeek(), $awal->translatedFormat('d M')];
            });
        } elseif ($period === 'tahun') {
            // Total per tahun untuk 5 tahun terakhir
            $rentang = collect(range(4, 0))->map(function ($i) use ($now) {
                $awal = $now->copy()->subYears($i)->startOfYear();
                return [$awal, $awal->copy()->endOfYear(), $awal->format('Y')];
            });
        } else {
            // 6 bulan sampai bulan $akhir (default bulan ini). NoOverflow agar tanggal 29-31 tidak menggeser bulan
            [$bulanAkhir, $akhirMin, $bulanIni] = $this->batasGeserBulan($akhir, $now);
            $rentang = collect(range(5, 0))->map(function ($i) use ($bulanAkhir) {
                $awal = $bulanAkhir->copy()->subMonthsNoOverflow($i)->startOfMonth();
                return [$awal, $awal->copy()->endOfMonth(), $awal->translatedFormat('M Y')];
            });

            $awal = $rentang->first()[0];
            $navigasi = [
                'akhir' => $bulanAkhir->format('Y-m'),
                'rentang' => $awal->year === $bulanAkhir->year
                    ? $awal->translatedFormat('M') . ' – ' . $bulanAkhir->translatedFormat('M Y')
                    : $awal->translatedFormat('M Y') . ' – ' . $bulanAkhir->translatedFormat('M Y'),
                'bisaMundur' => $bulanAkhir->gt($akhirMin),
                'bisaMaju' => $bulanAkhir->lt($bulanIni),
                'bulanIni' => $bulanAkhir->equalTo($bulanIni),
            ];
        }

        $hitung = function (string $model, string $kolom) use ($rentang) {
            return $rentang->map(fn ($r) => $model::whereBetween($kolom, [$r[0]->toDateString(), $r[1]->toDateString()])->count())->values();
        };

        return [
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
        ] + $navigasi;
    }
}
