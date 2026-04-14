<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        $jadwalIds = Auth::user()->jadwalPeriksa()->pluck('id');

        $riwayats = Periksa::whereHas('daftarPoli', function ($q) use ($jadwalIds) {
            $q->whereIn('id_jadwal', $jadwalIds);
        })
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa', 'detailPeriksas.obat'])
            ->orderByDesc('tgl_periksa')
            ->get();

        return view('dokter.riwayat-pasien.index', compact('riwayats'));
    }

    public function show($id)
    {
        $jadwalIds = Auth::user()->jadwalPeriksa()->pluck('id');

        $periksa = Periksa::whereHas('daftarPoli', function ($q) use ($jadwalIds) {
            $q->whereIn('id_jadwal', $jadwalIds);
        })
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter', 'detailPeriksas.obat'])
            ->findOrFail($id);

        return view('dokter.riwayat-pasien.show', compact('periksa'));
    }
}
