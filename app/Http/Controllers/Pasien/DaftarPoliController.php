<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarPoliController extends Controller
{
    public function create()
    {
        $pasien = Auth::user();

        // Check if patient already has active registration (not yet examined)
        $activeRegistration = DaftarPoli::where('id_pasien', $pasien->id)
            ->whereDoesntHave('periksas')
            ->first();

        if ($activeRegistration) {
            return redirect()->route('pasien.dashboard')
                ->with('error', 'Anda sudah memiliki antrian aktif. Silakan tunggu hingga pemeriksaan selesai.');
        }

        $polis = Poli::all();
        $no_rm = $pasien->no_rm ?? date('Ym') . '-' . str_pad($pasien->id, 3, '0', STR_PAD_LEFT);

        return view('pasien.daftar-poli.create', compact('polis', 'no_rm'));
    }

    public function getJadwal($poliId)
    {
        $jadwals = JadwalPeriksa::whereHas('dokter', function ($q) use ($poliId) {
            $q->where('id_poli', $poliId);
        })->with('dokter')->get();

        return response()->json($jadwals);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        $pasien = Auth::user();

        // Double-check: prevent duplicate active registration
        $activeRegistration = DaftarPoli::where('id_pasien', $pasien->id)
            ->whereDoesntHave('periksas')
            ->first();

        if ($activeRegistration) {
            return redirect()->route('pasien.dashboard')
                ->with('error', 'Anda sudah memiliki antrian aktif.');
        }

        // Calculate queue number
        $lastAntrian = DaftarPoli::where('id_jadwal', $request->id_jadwal)
            ->max('no_antrian');

        DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => ($lastAntrian ?? 0) + 1,
        ]);

        return redirect()->route('pasien.dashboard')
            ->with('success', 'Berhasil mendaftar poli. Nomor antrian Anda: ' . (($lastAntrian ?? 0) + 1));
    }
}
