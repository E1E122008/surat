<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
use App\Models\SkKaro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AgendaKeluarExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filterType;
    protected $mingguKe;
    protected $bulan;
    protected $tahun;
    protected $tab;
    protected $search;
    protected $sort;

    public function __construct($filterType = null, $mingguKe = null, $bulan = null, $tahun = null, $tab = 'surat-keluar', $search = null, $sort = 'desc')
    {
        $this->filterType = $filterType;
        $this->mingguKe = $mingguKe;
        $this->bulan = $bulan;
        $this->tahun = $tahun ?? now()->year;
        $this->tab = $tab;
        $this->search = $search;
        $this->sort = $sort ?? 'desc';
    }

    public function collection()
    {
        \Log::info('Filter Parameters:', [
            'filterType' => $this->filterType,
            'mingguKe' => $this->mingguKe,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'tab' => $this->tab,
            'search' => $this->search,
            'sort' => $this->sort
        ]);

        $query = $this->getQueryByTab();
        
        // Logika pencarian
        if ($this->search) {
            $search = $this->search;
            if ($this->tab === 'surat-keluar') {
                $query->where(function ($q) use ($search) {
                    $q->where('no_surat', 'LIKE', "%{$search}%")
                      ->orWhere('perihal', 'LIKE', "%{$search}%");
                });
            } else if (in_array($this->tab, ['sppd-dalam', 'sppd-luar', 'spt-dalam', 'spt-luar'])) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_surat', 'LIKE', "%{$search}%")
                      ->orWhere('perihal', 'LIKE', "%{$search}%")
                      ->orWhere('tujuan', 'LIKE', "%{$search}%")
                      ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
                });
            } else if ($this->tab === 'sk-karo') {
                $query->where(function ($q) use ($search) {
                    $q->where('no_sk', 'LIKE', "%{$search}%")
                      ->orWhere('perihal', 'LIKE', "%{$search}%");
                });
            }
        }
        
        if ($this->filterType) {
            switch ($this->filterType) {
                case 'minggu':
                    $currentMonth = Carbon::create(null, $this->bulan, 1);
                    $weekStart = ($this->mingguKe - 1) * 7;
                    $startDate = $currentMonth->copy()->addDays($weekStart)->startOfDay();
                    $endDate = $this->mingguKe == 4 
                        ? $currentMonth->copy()->endOfMonth()->endOfDay()
                        : $currentMonth->copy()->addDays($weekStart + 6)->endOfDay();
                    
                    $query->whereBetween($this->tab == 'sk-karo' ? 'tanggal_sk' : 'tanggal', [$startDate, $endDate])
                          ->whereYear($this->tab == 'sk-karo' ? 'tanggal_sk' : 'tanggal', $this->tahun);
                    break;

                case 'bulan':
                    $query->whereMonth($this->tab == 'sk-karo' ? 'tanggal_sk' : 'tanggal', $this->bulan)
                          ->whereYear($this->tab == 'sk-karo' ? 'tanggal_sk' : 'tanggal', $this->tahun);
                    break;

                case 'tahun':
                    $query->whereYear($this->tab == 'sk-karo' ? 'tanggal_sk' : 'tanggal', $this->tahun);
                    break;
            }
        }

        $dateCol = $this->tab == 'sk-karo' ? 'tanggal_sk' : 'tanggal';
        if ($this->sort === 'asc') {
            return $query->oldest($dateCol)->get();
        } else {
            return $query->latest($dateCol)->get();
        }
    }

    protected function getQueryByTab()
    {
        return match($this->tab) {
            'surat-keluar' => SuratKeluar::query(),
            'sppd-dalam' => SppdDalamDaerah::query(),
            'sppd-luar' => SppdLuarDaerah::query(),
            'spt-dalam' => SptDalamDaerah::query(),
            'spt-luar' => SptLuarDaerah::query(),
            'sk-karo' => SkKaro::query(),
            default => SuratKeluar::query(),
        };
    }

    public function headings(): array
    {
        return match($this->tab) {
            'surat-keluar' => [
                'No',
                'Nomor Surat',
                'Perihal',
                'Tanggal',
                'Lampiran'
            ],
            'sppd-dalam', 'sppd-luar' => [
                'No',
                'Nomor SPPD',
                'Tanggal',
                'Tujuan',
                'Nama Petugas',
                'Perihal',
                'Lampiran'
            ],
            'sk-karo' => [
                'No',
                'Nomor SK',
                'Tanggal',
                'Perihal',
                'Pejabat TTD',
                'Lampiran'
            ],
            'spt-dalam', 'spt-luar' => [
                'No',
                'Nomor SPT',
                'Tanggal',
                'Tujuan',
                'Nama Petugas',
                'Perihal',
                'Lampiran'
            ],
            default => [
                'No',
                'Nomor Surat',
                'Perihal',
                'Tanggal',
                'Lampiran'
            ],
        };
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        
        $lampiran = $row->lampiran ? asset('storage/' . $row->lampiran) : '-';
        
        return match($this->tab) {
            'surat-keluar' => [
                $no,
                $row->no_surat ?? '-',
                $row->perihal ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $lampiran
            ],
            'sppd-dalam', 'sppd-luar' => [
                $no,
                $row->no_surat ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $row->tujuan ?? '-',
                $row->nama_petugas ?? '-',
                $row->perihal ?? '-',
                $lampiran
            ],
            'sk-karo' => [
                $no,
                $row->no_sk ?? '-',
                $row->tanggal_sk ? \Carbon\Carbon::parse($row->tanggal_sk)->format('d/m/Y') : '-',
                $row->perihal ?? '-',
                $row->pejabat_ttd ?? '-',
                $row->file_surat ? asset('storage/' . json_decode($row->file_surat, true)[0]['path']) : '-'
            ],
            'spt-dalam', 'spt-luar' => [
                $no,
                $row->no_surat ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $row->tujuan ?? '-',
                $row->nama_petugas ?? '-',
                $row->perihal ?? '-',
                $lampiran
            ],
            default => [
                $no,
                $row->no_surat ?? '-',
                $row->perihal ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $lampiran
            ],
        };
    }
} 