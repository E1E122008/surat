<?php

namespace App\Exports;

use App\Models\Perda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerdaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Perda::latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Agenda',
            'No Surat',
            'Pengirim',
            'Tanggal Surat',
            'Tanggal Terima',
            'Perihal',
            'Lampiran',
            'Catatan',
            
        ];
    }

    public function map($perda): array
    {
        return [
            $perda->id,
            $perda->no_agenda,
            $perda->no_surat,
            $perda->pengirim,
            $perda->tanggal_surat->format('d/m/Y'),
            $perda->tanggal_terima->format('d/m/Y'),
            $perda->perihal,
            $perda->lampiran,
            $perda->catatan,
        ];
    }
    
}
