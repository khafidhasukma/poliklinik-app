<?php

namespace App\Exports;

use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        $jadwalIds = Auth::user()->jadwalPeriksa()->pluck('id');

        return Periksa::whereHas('daftarPoli', function ($q) use ($jadwalIds) {
            $q->whereIn('id_jadwal', $jadwalIds);
        })
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa'])
            ->orderByDesc('tgl_periksa')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'No Antrian', 'Nama Pasien', 'Keluhan', 'Tanggal Periksa', 'Biaya'];
    }

    public function map($periksa): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $periksa->daftarPoli->no_antrian ?? '-',
            $periksa->daftarPoli->pasien->nama ?? '-',
            $periksa->daftarPoli->keluhan ?? '-',
            $periksa->tgl_periksa,
            $periksa->biaya_periksa,
        ];
    }
}
