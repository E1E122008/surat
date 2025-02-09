<?php

namespace App\Exports;

use App\Models\SptLuarDaerah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SptLuarDaerahExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SptLuarDaerah::latest()->get();
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

    public function map($spt): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $spt->no_surat,
            $spt->tanggal->format('d/m/Y'),
            $spt->perihal,
            $spt->nama_petugas,
        ];
    }
} 