<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AgendaKeluarExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filterType;
    protected $mingguKe;
    protected $bulan;
    protected $tahun;
    protected $tab;

    public function __construct($filterType = null, $mingguKe = null, $bulan = null, $tahun = null, $tab = 'surat-keluar')
    {
        $this->filterType = $filterType;
        $this->mingguKe = $mingguKe;
        $this->bulan = $bulan;
        $this->tahun = $tahun ?? now()->year;
        $this->tab = $tab;
    }

    public function collection()
    {
        \Log::info('Filter Parameters:', [
            'filterType' => $this->filterType,
            'mingguKe' => $this->mingguKe,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'tab' => $this->tab
        ]);

        $query = $this->getQueryByTab();
        
        if ($this->filterType) {
            switch ($this->filterType) {
                case 'minggu':
                    $currentMonth = Carbon::create(null, $this->bulan, 1);
                    $weekStart = ($this->mingguKe - 1) * 7;
                    $startDate = $currentMonth->copy()->addDays($weekStart)->startOfDay();
                    $endDate = $this->mingguKe == 4 
                        ? $currentMonth->copy()->endOfMonth()->endOfDay()
                        : $currentMonth->copy()->addDays($weekStart + 6)->endOfDay();
                    
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                          ->whereYear('tanggal', $this->tahun);
                    break;

                case 'bulan':
                    $query->whereMonth('tanggal', $this->bulan)
                          ->whereYear('tanggal', $this->tahun);
                    break;

                case 'tahun':
                    $query->whereYear('tanggal', $this->tahun);
                    break;
            }
        }

        return $query->latest('tanggal')->get();
    }

    protected function getQueryByTab()
    {
        return match($this->tab) {
            'surat-keluar' => SuratKeluar::query(),
            'sppd-dalam' => SppdDalamDaerah::query(),
            'sppd-luar' => SppdLuarDaerah::query(),
            'spt-dalam' => SptDalamDaerah::query(),
            'spt-luar' => SptLuarDaerah::query(),
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
        
        return match($this->tab) {
            'surat-keluar' => [
                $no,
                $row->no_surat ?? '-',
                $row->perihal ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $row->lampiran ?? '-'
            ],
            'sppd-dalam', 'sppd-luar' => [
                $no,
                $row->no_surat ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $row->tujuan ?? '-',
                $row->nama_petugas ?? '-',
                $row->perihal ?? '-',
                $row->lampiran ?? '-'
            ],
            'spt-dalam', 'spt-luar' => [
                $no,
                $row->no_surat ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $row->tujuan ?? '-',
                $row->nama_petugas ?? '-',
                $row->perihal ?? '-',
                $row->lampiran ?? '-'
            ],
            default => [
                $no,
                $row->no_surat ?? '-',
                $row->perihal ?? '-',
                $row->tanggal ? $row->tanggal->format('d/m/Y') : '-',
                $row->lampiran ?? '-'
            ],
        };
    }
} 