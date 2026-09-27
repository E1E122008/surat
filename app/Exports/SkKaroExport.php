<?php

namespace App\Exports;

use App\Models\SkKaro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SkKaroExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SkKaro::latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No SK',
            'Tanggal SK',
            'Perihal',
            'Pejabat TTD',
        ];
    }

    public function map($skKaro): array
    {
        static $no = 0;
        $no++;
        
        Log::info('SK KARO Export:', [$skKaro]);

        return [
            $no,
            $skKaro->no_sk,
            $skKaro->tanggal_sk ? Carbon::parse($skKaro->tanggal_sk)->format('d/m/Y') : 'N/A',
            $skKaro->perihal,
            $skKaro->pejabat_ttd,
        ];
    }
}
