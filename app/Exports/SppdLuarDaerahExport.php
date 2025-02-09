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
            'Nomor Surat',
            'Tanggal',
            'Perihal',
            'Nama Petugas',
        ];
    }

    public function map($sppd): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $sppd->no_surat,
            $sppd->tanggal->format('d/m/Y'),
            $sppd->perihal,
            $sppd->nama_petugas,
        ];
    }
} 