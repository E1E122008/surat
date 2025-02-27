<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use App\Models\Sk;
use App\Models\Perda;
use App\Models\Pergub;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AgendaMasukExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filterType;
    protected $mingguKe;
    protected $bulan;
    protected $tahun;
    protected $tab;

    public function __construct($filterType = null, $mingguKe = null, $bulan = null, $tahun = null, $tab = 'surat-masuk')
    {
        $this->filterType = $filterType;
        $this->mingguKe = $mingguKe;
        $this->bulan = $bulan;
        $this->tahun = $tahun ?? now()->year;
        $this->tab = $tab;
    }

    public function collection()
    {
        // Debug untuk melihat nilai parameter
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
                    
                    \Log::info('Date Range:', [
                        'startDate' => $startDate->toDateTimeString(),
                        'endDate' => $endDate->toDateTimeString()
                    ]);

                    $query->whereBetween('tanggal_terima', [$startDate, $endDate]);
                    break;

                case 'bulan':
                    $query->whereMonth('tanggal_terima', $this->bulan)
                          ->whereYear('tanggal_terima', $this->tahun);
                    break;

                case 'tahun':
                    $query->whereYear('tanggal_terima', $this->tahun);
                    break;
            }
        }

        $result = $query->latest()->get();
        
        // Debug untuk melihat jumlah data
        \Log::info('Query Result Count: ' . $result->count());

        return $result;
    }

    protected function getQueryByTab()
    {
        return match($this->tab) {
            'surat-masuk' => SuratMasuk::query(),
            'surat-keputusan' => Sk::query(),
            'perda' => Perda::query(),
            'pergub' => Pergub::query(),
            default => SuratMasuk::query(),
        };
    }

    public function headings(): array
    {
        return [
            'No',
            'No Agenda',
            'Nomor Surat',
            'Pengirim',
            'Tanggal Surat',
            'Tanggal Terima',
            'Perihal',
            'Disposisi',
            'Lampiran'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        
        return match($this->tab) {
            'surat-masuk' => [
                $no,
                $row->no_agenda ?? '-',
                $row->no_surat ?? '-',
                $row->pengirim ?? '-',
                $row->tanggal_surat ? $row->tanggal_surat->format('d/m/Y') : '-',
                $row->tanggal_terima ? $row->tanggal_terima->format('d/m/Y') : '-',
                $row->perihal ?? '-',
                $row->disposisi ?? '-',
                $row->lampiran ?? '-',
            ],
            'surat-keputusan' => [
                $no,
                $row->no_agenda ?? '-',
                $row->no_surat ?? '-',
                $row->pengirim ?? '-',
                $row->tanggal_surat ? $row->tanggal_surat->format('d/m/Y') : '-',
                $row->tanggal_terima ? $row->tanggal_terima->format('d/m/Y') : '-',
                $row->perihal ?? '-',
                $row->disposisi ?? '-',
                $row->lampiran ?? '-',
            ],
            'perda', 'pergub' => [
                $no,
                $row->no_agenda ?? '-',
                $row->no_surat ?? '-',
                $row->pengirim ?? '-',
                $row->tanggal_surat ? $row->tanggal_surat->format('d/m/Y') : '-',
                $row->tanggal_terima ? $row->tanggal_terima->format('d/m/Y') : '-',
                $row->perihal ?? '-',
                $row->disposisi ?? '-',
                $row->lampiran ?? '-',
            ],
            default => [
                $no,
                $row->no_agenda ?? '-',
                $row->no_surat ?? '-',
                $row->pengirim ?? '-',
                $row->tanggal_surat ? $row->tanggal_surat->format('d/m/Y') : '-',
                $row->tanggal_terima ? $row->tanggal_terima->format('d/m/Y') : '-',
                $row->perihal ?? '-',
                $row->disposisi ?? '-',
                $row->lampiran ?? '-',
            ],
        };
    }
} 