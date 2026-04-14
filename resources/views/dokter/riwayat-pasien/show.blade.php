<x-layouts.app title="Detail Riwayat Pasien">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.riwayat-pasien.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Detail Riwayat Pasien</h2>
    </div>

    {{-- Patient Info --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border mb-6">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Pasien</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-500">Nama Pasien:</span>
                    <span class="font-semibold ml-2">{{ $periksa->daftarPoli->pasien->nama }}</span>
                </div>
                <div>
                    <span class="text-slate-500">Tanggal Periksa:</span>
                    <span class="font-semibold ml-2">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="text-slate-500">Keluhan:</span>
                    <span class="font-semibold ml-2">{{ $periksa->daftarPoli->keluhan }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Catatan --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border mb-6">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-3">Catatan Pemeriksaan</h3>
            <p class="text-sm text-slate-700">{{ $periksa->catatan ?: '-' }}</p>
        </div>
    </div>

    {{-- Obat --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border mb-6">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Obat Diberikan</h3>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-sm text-slate-500">
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Kemasan</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periksa->detailPeriksas as $i => $detail)
                            <tr class="text-sm">
                                <td>{{ $i + 1 }}</td>
                                <td class="font-medium">{{ $detail->obat->nama_obat }}</td>
                                <td>{{ $detail->obat->kemasan }}</td>
                                <td>Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-slate-400">Tidak ada obat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Total --}}
    <div class="card bg-primary/5 border border-primary/20 rounded-2xl">
        <div class="card-body p-6">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-slate-800">Total Biaya</span>
                <span class="text-2xl font-bold text-primary">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

</x-layouts.app>
