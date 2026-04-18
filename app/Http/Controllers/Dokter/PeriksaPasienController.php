<?php

namespace App\Http\Controllers\Dokter;

use App\Events\AntrianUpdated;
use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index(Request $request)
    {
        $jadwalIds = Auth::user()->jadwalPeriksa()->pluck('id');

        $query = DaftarPoli::whereIn('id_jadwal', $jadwalIds)
            ->with(['pasien', 'jadwalPeriksa', 'periksas']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'belum') {
                $query->whereDoesntHave('periksas');
            } elseif ($request->status === 'sudah') {
                $query->whereHas('periksas');
            }
        }

        // Filter by tanggal daftar
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $daftarPolis = $query->orderBy('created_at', 'desc')
            ->orderBy('no_antrian', 'asc')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPolis'));
    }

    public function show($id)
    {
        $daftarPoli = DaftarPoli::with(['pasien', 'jadwalPeriksa'])->findOrFail($id);
        $obats = Obat::all();

        return view('dokter.periksa-pasien.show', compact('daftarPoli', 'obats'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'obat' => 'required|array|min:1',
            'obat.*' => 'exists:obat,id',
            'catatan' => 'nullable|string',
        ]);

        $daftarPoli = DaftarPoli::findOrFail($id);

        // Check if already examined
        if ($daftarPoli->periksas()->exists()) {
            return back()->with('error', 'Pasien ini sudah diperiksa.');
        }

        DB::beginTransaction();
        try {
            // Check stock availability for all medicines
            $obatIds = $request->obat;
            $obatCounts = array_count_values($obatIds);

            foreach ($obatCounts as $obatId => $qty) {
                $obat = Obat::lockForUpdate()->find($obatId);
                if (!$obat || $obat->stok < $qty) {
                    DB::rollBack();
                    return back()->with('error', "Stok obat \"{$obat->nama_obat}\" tidak mencukupi (sisa: {$obat->stok}). Seluruh proses dibatalkan.");
                }
            }

            // Calculate total cost (150000 consultation + medicine costs)
            $biaya = 150000;
            foreach ($obatIds as $obatId) {
                $obat = Obat::find($obatId);
                $biaya += $obat->harga;
            }

            // Create periksa record
            $periksa = Periksa::create([
                'id_daftar_poli' => $daftarPoli->id,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $biaya,
            ]);

            // Create detail_periksa records and reduce stock
            foreach ($obatCounts as $obatId => $qty) {
                for ($i = 0; $i < $qty; $i++) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $obatId,
                    ]);
                }

                Obat::where('id', $obatId)->decrement('stok', $qty);
            }

            // Create pembayaran record
            Pembayaran::create([
                'id_periksa' => $periksa->id,
                'id_pasien' => $daftarPoli->id_pasien,
                'status' => 'belum_bayar',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // Broadcast AFTER commit so a missing Reverb server never rolls back real data
        try {
            event(new AntrianUpdated($daftarPoli->id_jadwal, $daftarPoli->no_antrian));
        } catch (\Exception $e) {
            // Reverb may be offline; log but do not fail the request
            logger()->warning('AntrianUpdated broadcast failed: ' . $e->getMessage());
        }

        return redirect()->route('dokter.periksa-pasien.index')
            ->with('success', 'Hasil pemeriksaan berhasil disimpan.');
    }
}
