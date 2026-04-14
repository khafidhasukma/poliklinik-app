<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DokterExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return User::where('role', 'dokter')->with('poli')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Dokter', 'Email', 'No. KTP', 'No. HP', 'Alamat', 'Poli'];
    }

    public function map($dokter): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $dokter->nama,
            $dokter->email,
            $dokter->no_ktp,
            $dokter->no_hp,
            $dokter->alamat,
            $dokter->poli->nama_poli ?? '-',
        ];
    }
}
