<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPoli = Poli::count();
        $totalDokter = User::where('role', 'dokter')->count();
        $totalPasien = User::where('role', 'pasien')->count();
        $totalObat = Obat::count();

        $polis = Poli::with(['dokters'])->get();

        return view('admin.dashboard', compact('totalPoli', 'totalDokter', 'totalPasien', 'totalObat', 'polis'));
    }
}
