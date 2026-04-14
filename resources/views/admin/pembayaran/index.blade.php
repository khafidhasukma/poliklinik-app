<x-layouts.app title="Verifikasi Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Verifikasi Pembayaran</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Biaya</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($pembayarans as $index => $pembayaran)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $pembayaran->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold">Rp {{ number_format($pembayaran->periksa->biaya_periksa ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($pembayaran->status === 'lunas')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Lunas</span>
                                @elseif($pembayaran->status === 'menunggu_verifikasi')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu Verifikasi</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}"
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                                    <i class="fas fa-eye text-xs"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox mx-auto text-3xl mb-3 block"></i>
                                Belum ada data pembayaran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
