<x-layouts.app title="Riwayat Pendaftaran">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Riwayat Pendaftaran</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-sm text-slate-500">
                            <th>No</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Hari</th>
                            <th>No. Antrian</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $i => $riwayat)
                            <tr class="hover:bg-slate-50 text-sm">
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</td>
                                <td class="font-medium">{{ $riwayat->jadwalPeriksa->dokter->nama }}</td>
                                <td>{{ $riwayat->jadwalPeriksa->hari }}</td>
                                <td>
                                    <span class="badge badge-primary badge-outline">{{ $riwayat->no_antrian }}</span>
                                </td>
                                <td>{{ Str::limit($riwayat->keluhan, 30) }}</td>
                                <td>
                                    @if($riwayat->periksas->count() > 0)
                                        <span class="badge badge-success text-xs">Sudah Diperiksa</span>
                                    @else
                                        <span class="badge badge-warning text-xs">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($riwayat->periksas->count() > 0)
                                        <a href="{{ route('pasien.riwayat.show', $riwayat->id) }}" class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary font-medium text-xs hover:bg-primary/20 transition">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">Belum ada riwayat pendaftaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
