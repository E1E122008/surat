<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuratKeluarExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SuratKeluar::latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Surat',
            'Tanggal',
            'Perihal',
        ];
    }

    public function map($suratKeluar): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $suratKeluar->no_surat,
            $suratKeluar->tanggal->format('d/m/Y'),
            $suratKeluar->perihal,
        ];
    }
} 