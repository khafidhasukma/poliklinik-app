<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
        $jadwalIds = $dokter->jadwalPeriksa()->pluck('id');

        $totalJadwal = $dokter->jadwalPeriksa()->count();

        $pasienMenunggu = DaftarPoli::whereIn('id_jadwal', $jadwalIds)
            ->whereDoesntHave('periksas')
            ->count();

        $totalRiwayat = Periksa::whereHas('daftarPoli', function ($q) use ($jadwalIds) {
            $q->whereIn('id_jadwal', $jadwalIds);
        })->count();

        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('dokter.dashboard', compact('totalJadwal', 'pasienMenunggu', 'totalRiwayat', 'jadwals'));
    }
}
