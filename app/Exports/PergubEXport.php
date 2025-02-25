<?php

namespace App\Exports;

use App\Models\Pergub;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PergubExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Pergub::latest()->get();
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
            'Disposisi',
        ];
    }   

    public function map($pergub): array
    {
        return [
            $pergub->id,
            $pergub->no_agenda, 
            $pergub->no_surat,
            $pergub->pengirim,
            $pergub->tanggal_surat->format('d/m/Y'),
            $pergub->tanggal_terima->format('d/m/Y'),
            $pergub->perihal,
            $pergub->lampiran,  
            $pergub->disposisi,
        ];
    }
}
