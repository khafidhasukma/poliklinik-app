<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        $riwayats = DaftarPoli::where('id_pasien', Auth::id())
            ->with(['jadwalPeriksa.dokter.poli', 'periksas.detailPeriksas.obat'])
            ->orderByDesc('created_at')
            ->get();

        return view('pasien.riwayat.index', compact('riwayats'));
    }

    public function show($id)
    {
        $daftarPoli = DaftarPoli::where('id_pasien', Auth::id())
            ->with(['jadwalPeriksa.dokter.poli', 'periksas.detailPeriksas.obat'])
            ->findOrFail($id);

        $periksa = $daftarPoli->periksas->first();

        if (!$periksa) {
            return back()->with('error', 'Pemeriksaan belum dilakukan.');
        }

        return view('pasien.riwayat.show', compact('daftarPoli', 'periksa'));
    }
}
