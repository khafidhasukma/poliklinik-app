<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PasienExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return User::where('role', 'pasien')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Pasien', 'Email', 'No. KTP', 'No. HP', 'Alamat', 'No. RM'];
    }

    public function map($pasien): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $pasien->nama,
            $pasien->email,
            $pasien->no_ktp,
            $pasien->no_hp,
            $pasien->alamat,
            $pasien->no_rm ?? '-',
        ];
    }
}
