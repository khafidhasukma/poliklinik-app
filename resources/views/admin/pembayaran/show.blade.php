<x-layouts.app title="Detail Pembayaran">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Detail Pembayaran</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Info Pembayaran --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border">
            <div class="card-body p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Pembayaran</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pasien</span>
                        <span class="font-semibold">{{ $pembayaran->pasien->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dokter</span>
                        <span class="font-semibold">{{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Poli</span>
                        <span class="font-semibold">{{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tanggal Periksa</span>
                        <span class="font-semibold">{{ $pembayaran->periksa->tgl_periksa }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Biaya</span>
                        <span class="font-bold text-lg text-primary">Rp {{ number_format($pembayaran->periksa->biaya_periksa, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status</span>
                        @if($pembayaran->status === 'lunas')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Lunas</span>
                        @elseif($pembayaran->status === 'menunggu_verifikasi')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu Verifikasi</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">Belum Bayar</span>
                        @endif
                    </div>
                </div>

                {{-- Obat --}}
                <h4 class="text-md font-bold text-slate-800 mt-6 mb-3">Obat yang Diresepkan</h4>
                <table class="table w-full">
                    <thead class="bg-slate-50 text-xs">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Nama Obat</th>
                            <th class="px-3 py-2">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($pembayaran->periksa->detailPeriksas as $i => $detail)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $i + 1 }}</td>
                            <td class="px-3 py-2">{{ $detail->obat->nama_obat }}</td>
                            <td class="px-3 py-2">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border">
            <div class="card-body p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Bukti Pembayaran</h3>

                @if($pembayaran->bukti_pembayaran)
                    <div class="rounded-xl overflow-hidden border mb-4">
                        <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                             alt="Bukti Pembayaran" class="w-full object-contain max-h-96">
                    </div>

                    @if($pembayaran->status === 'menunggu_verifikasi')
                        <form action="{{ route('admin.pembayaran.confirm', $pembayaran->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Konfirmasi pembayaran ini sebagai lunas?')"
                                class="w-full px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition">
                                <i class="fas fa-check-circle mr-2"></i>
                                Konfirmasi Pembayaran (Lunas)
                            </button>
                        </form>
                    @endif
                @else
                    <div class="text-center py-12 text-slate-400">
                        <i class="fas fa-image text-4xl mb-3 block"></i>
                        <p>Pasien belum mengupload bukti pembayaran</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-layouts.app>
