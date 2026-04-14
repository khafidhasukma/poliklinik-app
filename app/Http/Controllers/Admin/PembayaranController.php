<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with([
            'pasien',
            'periksa.daftarPoli.jadwalPeriksa.dokter',
            'periksa.detailPeriksas.obat',
        ])
            ->orderByRaw("FIELD(status, 'menunggu_verifikasi', 'belum_bayar', 'lunas')")
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'pasien',
            'periksa.daftarPoli.jadwalPeriksa.dokter.poli',
            'periksa.detailPeriksas.obat',
        ])->findOrFail($id);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function confirm($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Pembayaran tidak dalam status menunggu verifikasi.');
        }

        $pembayaran->update(['status' => 'lunas']);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }
}
