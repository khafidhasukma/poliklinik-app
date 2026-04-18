<x-layouts.app title="Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Pembayaran</h2>
    </div>

    <div class="space-y-4">
        @forelse($pembayarans as $pembayaran)
            <div class="card bg-base-100 shadow-md rounded-2xl border">
                <div class="card-body p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-slate-800">
                                {{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }} —
                                {{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Tanggal: {{ \Carbon\Carbon::parse($pembayaran->periksa->tgl_periksa)->format('d M Y') }}
                            </p>
                            <p class="text-lg font-bold text-primary mt-2">
                                Rp {{ number_format($pembayaran->periksa->biaya_periksa, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-3">
                            {{-- Status Badge --}}
                            @if($pembayaran->status === 'lunas')
                                <span class="badge badge-success gap-1 text-xs">
                                    <i class="fas fa-check-circle"></i> Lunas
                                </span>
                            @elseif($pembayaran->status === 'menunggu_verifikasi')
                                <span class="badge badge-warning gap-1 text-xs">
                                    <i class="fas fa-clock"></i> Menunggu Verifikasi
                                </span>
                            @else
                                <span class="badge badge-error gap-1 text-xs">
                                    <i class="fas fa-circle-xmark"></i> Belum Bayar
                                </span>
                            @endif

                            {{-- Upload Form --}}
                            @if($pembayaran->status === 'belum_bayar')
                                <form action="{{ route('pasien.pembayaran.upload', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                    @csrf
                                    <input type="file" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" class="file-input file-input-bordered file-input-sm w-full max-w-xs" required>
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold text-xs transition whitespace-nowrap">
                                        <i class="fas fa-upload mr-1"></i> Upload
                                    </button>
                                </form>
                            @endif

                            {{-- Show uploaded proof --}}
                            @if($pembayaran->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="text-xs text-primary hover:underline">
                                    <i class="fas fa-image mr-1"></i> Lihat Bukti
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card bg-base-100 shadow-md rounded-2xl border">
                <div class="card-body p-8 text-center">
                    <i class="fas fa-receipt text-4xl text-slate-300 mb-3 mx-auto"></i>
                    <p class="text-slate-400">Belum ada tagihan pembayaran</p>
                </div>
            </div>
        @endforelse
    </div>

</x-layouts.app>
