<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Log;

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
            'Tanggal Surat',
            'Perihal',
            'Lampiran',
        ];
    }

    public function map($suratKeluar): array
    {
        static $no = 0;
        $no++;
        
        Log::info('Surat Keluar:', [$suratKeluar]);

        return [
            $no,
            $suratKeluar->no_surat,
            $suratKeluar->tanggal_surat ? $suratKeluar->tanggal_surat->format('d/m/Y') : 'N/A',
            $suratKeluar->perihal,
            $suratKeluar->lampiran,
        ];
    }
} 