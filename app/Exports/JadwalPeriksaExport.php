<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JadwalPeriksaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return JadwalPeriksa::where('id_dokter', Auth::id())->get();
    }

    public function headings(): array
    {
        return ['No', 'Hari', 'Jam Mulai', 'Jam Selesai'];
    }

    public function map($jadwal): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $jadwal->hari,
            $jadwal->jam_mulai,
            $jadwal->jam_selesai,
        ];
    }
}
