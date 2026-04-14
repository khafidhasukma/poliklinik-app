<x-layouts.app title="Periksa Pasien">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Periksa Pasien</h2>

    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($daftarPolis as $dp)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4">{{ $dp->id }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $dp->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ Str::limit($dp->keluhan, 50) }}</td>
                            <td class="px-6 py-4">{{ $dp->no_antrian }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($dp->periksas->count() > 0)
                                    <span class="inline-flex items-center gap-1 px-4 py-2 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                        <i class="fas fa-check-circle"></i> Sudah Diperiksa
                                    </span>
                                @else
                                    <a href="{{ route('dokter.periksa-pasien.show', $dp->id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                                        <i class="fas fa-stethoscope text-xs"></i> Periksa
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block mx-auto"></i>
                                Tidak ada pasien menunggu
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
