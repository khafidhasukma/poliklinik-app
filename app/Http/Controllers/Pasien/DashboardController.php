<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pasien = Auth::user();

        // Get active registration (not yet examined)
        $activeRegistration = DaftarPoli::where('id_pasien', $pasien->id)
            ->whereDoesntHave('periksas')
            ->with(['jadwalPeriksa.dokter.poli'])
            ->first();

        // Get current serving number per jadwal
        $jadwals = JadwalPeriksa::with(['dokter.poli', 'daftarPolis' => function ($q) {
            $q->orderBy('no_antrian');
        }])->get();

        // Calculate "sedang dilayani" per jadwal
        $jadwalData = $jadwals->map(function ($jadwal) {
            $lastExamined = $jadwal->daftarPolis
                ->filter(fn($dp) => $dp->periksas && $dp->periksas->count() > 0)
                ->sortByDesc('no_antrian')
                ->first();

            return [
                'jadwal' => $jadwal,
                'sedang_dilayani' => $lastExamined ? $lastExamined->no_antrian : 0,
            ];
        });

        // Find the "sedang dilayani" for the patient's active registration
        $sedangDilayani = 0;
        if ($activeRegistration) {
            $match = $jadwalData->first(fn($item) => $item['jadwal']->id === $activeRegistration->id_jadwal);
            $sedangDilayani = $match ? $match['sedang_dilayani'] : 0;
        }

        return view('pasien.dashboard', compact('activeRegistration', 'jadwalData', 'sedangDilayani'));
    }
}
