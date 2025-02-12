<?php

namespace App\Exports;

use App\Models\SK;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SKExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SK::latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor SK',
            'Pengirim',
            'Tanggal SK',
            'Tanggal Terima',
            'Perihal',
        ];
    }

    public function map($sk): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $sk->no_surat,
            $sk->pengirim,
            $sk->tanggal_surat->format('d/m/Y'),
            $sk->tanggal_terima->format('d/m/Y'),
            $sk->perihal,
        ];
    }
}
