<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuratMasukExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SuratMasuk::latest()->get();
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
            'Lampiran',

            
            ];
    }

    public function map($suratMasuk): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $suratMasuk->no_agenda,
            $suratMasuk->no_surat,
            $suratMasuk->pengirim,
            $suratMasuk->tanggal_surat->format('d/m/Y'),
            $suratMasuk->tanggal_terima->format('d/m/Y'),
            $suratMasuk->perihal,
            $suratMasuk->disposisi,
            $suratMasuk->lampiran,
        ];
    }
} 