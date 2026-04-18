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

        // Check if patient already has active registration today (not yet examined)
        $activeRegistration = DaftarPoli::where('id_pasien', $pasien->id)
            ->whereDate('created_at', today())
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
        $hariMap = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis',  5 => 'Jumat', 6 => 'Sabtu',
        ];

        $hariIni  = $hariMap[now()->dayOfWeek];
        $sekarang = now()->format('H:i:s');

        $jadwals = JadwalPeriksa::whereHas('dokter', function ($q) use ($poliId) {
            $q->where('id_poli', $poliId);
        })->with('dokter')->get();

        return response()->json(
            $jadwals->map(function ($jadwal) use ($hariIni, $sekarang) {
                $isToday     = $jadwal->hari === $hariIni;
                $inTimeRange = $sekarang >= $jadwal->jam_mulai && $sekarang <= $jadwal->jam_selesai;

                return array_merge($jadwal->toArray(), [
                    'is_available' => $isToday && $inTimeRange,
                    'hari_ini'     => $isToday,
                ]);
            })
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        $pasien = Auth::user();

        // Double-check: prevent duplicate active registration today
        $activeRegistration = DaftarPoli::where('id_pasien', $pasien->id)
            ->whereDate('created_at', today())
            ->whereDoesntHave('periksas')
            ->first();

        if ($activeRegistration) {
            return redirect()->route('pasien.dashboard')
                ->with('error', 'Anda sudah memiliki antrian aktif.');
        }

        // Validate that registration is within the schedule's day & time window
        $jadwal = JadwalPeriksa::findOrFail($request->id_jadwal);

        $hariMap = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis',  5 => 'Jumat', 6 => 'Sabtu',
        ];
        $hariIni  = $hariMap[now()->dayOfWeek];
        $sekarang = now()->format('H:i:s');

        if ($jadwal->hari !== $hariIni) {
            return back()->withInput()
                ->with('error', "Pendaftaran untuk jadwal ini hanya bisa dilakukan pada hari {$jadwal->hari}. Hari ini adalah {$hariIni}.");
        }

        if ($sekarang < $jadwal->jam_mulai || $sekarang > $jadwal->jam_selesai) {
            $mulai   = substr($jadwal->jam_mulai, 0, 5);
            $selesai = substr($jadwal->jam_selesai, 0, 5);
            return back()->withInput()
                ->with('error', "Pendaftaran hanya dibuka pada pukul {$mulai}–{$selesai}. Sekarang pukul " . now()->format('H:i') . '.');
        }

        // Calculate queue number — reset every day by only counting today's registrations
        $lastAntrian = DaftarPoli::where('id_jadwal', $request->id_jadwal)
            ->whereDate('created_at', today())
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
