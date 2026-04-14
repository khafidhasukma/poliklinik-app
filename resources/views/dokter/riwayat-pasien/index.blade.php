<x-layouts.app title="Riwayat Pasien">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Riwayat Pasien</h2>
        <a href="{{ route('dokter.export.riwayat') }}" class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition">
            <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-sm text-slate-500">
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Tgl Periksa</th>
                            <th>Biaya</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $i => $riwayat)
                            <tr class="hover:bg-slate-50 text-sm">
                                <td>{{ $i + 1 }}</td>
                                <td class="font-medium">{{ $riwayat->daftarPoli->pasien->nama }}</td>
                                <td>{{ Str::limit($riwayat->daftarPoli->keluhan, 40) }}</td>
                                <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y') }}</td>
                                <td>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('dokter.riwayat-pasien.show', $riwayat->id) }}" class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary font-medium text-xs hover:bg-primary/20 transition">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">Belum ada riwayat pasien</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
