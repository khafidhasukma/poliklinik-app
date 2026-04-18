<x-layouts.app title="Periksa Pasien">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Periksa Pasien</h2>

    {{-- Filter Form --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border mb-5">
        <div class="card-body p-5">
            <form method="GET" action="{{ route('dokter.periksa-pasien.index') }}" class="flex flex-wrap items-end gap-4">

                <div class="flex flex-col gap-1 min-w-[160px]">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</label>
                    <select name="status" class="select select-bordered select-sm rounded-lg text-sm">
                        <option value="">Semua Status</option>
                        <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Diperiksa</option>
                        <option value="sudah" {{ request('status') === 'sudah' ? 'selected' : '' }}>Sudah Diperiksa</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1 min-w-[180px]">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal Daftar</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="input input-bordered input-sm rounded-lg text-sm">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-semibold rounded-lg transition">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    @if(request('status') || request('tanggal'))
                    <a href="{{ route('dokter.periksa-pasien.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition">
                        <i class="fas fa-xmark text-xs"></i> Reset
                    </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Tanggal Daftar</th>
                            <th class="px-6 py-4">Hari / Jadwal</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($daftarPolis as $dpIdx => $dp)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4">{{ $dpIdx + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $dp->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($dp->created_at)->format('d M Y') }}<br>
                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($dp->created_at)->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold">{{ $dp->jadwalPeriksa->hari ?? '-' }}</span><br>
                                <span class="text-xs text-slate-400">
                                    {{ substr($dp->jadwalPeriksa->jam_mulai ?? '', 0, 5) }}
                                    – {{ substr($dp->jadwalPeriksa->jam_selesai ?? '', 0, 5) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge badge-outline badge-primary font-bold">{{ $dp->no_antrian }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ Str::limit($dp->keluhan, 45) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($dp->periksas->count() > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-check-circle text-xs"></i> Sudah Diperiksa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                        <i class="fas fa-clock text-xs"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($dp->periksas->count() === 0)
                                    <a href="{{ route('dokter.periksa-pasien.show', $dp->id) }}"
                                        class="inline-flex items-center gap-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                                        <i class="fas fa-stethoscope text-xs"></i> Periksa
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block mx-auto"></i>
                                Tidak ada data pasien
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>

