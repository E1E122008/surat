<?php

namespace App\Exports;

use App\Models\SppdLuarDaerah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SppdLuarDaerahExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SppdLuarDaerah::latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Agenda',
            'Nomor Surat',
            'Tanggal',
            'Tujuan',
            'Perihal',
            'Nama Petugas',
            'Lampiran',
        ];
    }

    public function map($sppd): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $sppd->no_agenda,
            $sppd->no_surat,
            $sppd->tanggal->format('d/m/Y'),
            $sppd->tujuan,
            $sppd->perihal,
            $sppd->nama_petugas,
            $sppd->lampiran,
        ];
    }
} 