<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::where('id_pasien', Auth::id())
            ->with(['periksa.daftarPoli.jadwalPeriksa.dokter.poli', 'periksa.detailPeriksas.obat'])
            ->orderByDesc('created_at')
            ->get();

        return view('pasien.pembayaran.index', compact('pembayarans'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pembayaran = Pembayaran::where('id_pasien', Auth::id())->findOrFail($id);

        if ($pembayaran->status === 'lunas') {
            return back()->with('error', 'Pembayaran sudah lunas.');
        }

        $path = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        $pembayaran->update([
            'bukti_pembayaran' => $path,
            'status' => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}
