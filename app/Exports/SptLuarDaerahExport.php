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
            'No Agenda',
            'Nomor Surat',
            'Tanggal',
            'Tujuan',
            'Perihal',
            'Nama Petugas',
            'Lampiran',
        ];
    }

    public function map($spt): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $spt->no_agenda,
            $spt->no_surat,
            $spt->tanggal->format('d/m/Y'),
            $spt->tujuan,
            $spt->perihal,
            $spt->nama_petugas,
            $spt->lampiran,
        ];
    }
} 