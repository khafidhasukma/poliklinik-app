<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ObatExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Obat::all();
    }

    public function headings(): array
    {
        return ['No', 'Nama Obat', 'Kemasan', 'Harga', 'Stok'];
    }

    public function map($obat): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $obat->nama_obat,
            $obat->kemasan,
            $obat->harga,
            $obat->stok,
        ];
    }
}
