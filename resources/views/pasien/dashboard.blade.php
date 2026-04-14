<x-layouts.app title="Pasien Dashboard">

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">
            Selamat Datang, {{ Auth::user()->nama }} 👋
        </h1>
        <p class="text-slate-500 mt-1">
            {{ now()->translatedFormat('l, d F Y') }} — Pantau antrian dan jadwal poli di sini.
        </p>
    </div>

    {{-- Active Queue Banner --}}
    @if($activeRegistration)
        <x-antrian-aktif :activeRegistration="$activeRegistration" :sedangDilayani="$sedangDilayani" />
    @else
        <div class="card bg-base-100 shadow-md rounded-2xl border mb-8">
            <div class="card-body p-6 text-center">
                <i class="fas fa-clipboard-list text-4xl text-slate-300 mb-3 mx-auto"></i>
                <p class="text-slate-500 mb-4">Anda belum memiliki antrian aktif.</p>
                <a href="{{ route('pasien.daftar.create') }}" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition inline-block">
                    <i class="fas fa-plus mr-1"></i> Daftar Poli Sekarang
                </a>
            </div>
        </div>
    @endif

    {{-- Jadwal Periksa Table --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-calendar-check mr-2 text-primary"></i> Jadwal Periksa Tersedia
            </h3>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-sm text-slate-500">
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalData as $item)
                            <tr class="hover:bg-slate-50 text-sm">
                                <td>{{ $item['jadwal']->dokter->poli->nama_poli ?? '-' }}</td>
                                <td class="font-medium">{{ $item['jadwal']->dokter->nama }}</td>
                                <td>{{ $item['jadwal']->hari }}</td>
                                <td>{{ $item['jadwal']->jam_mulai }} - {{ $item['jadwal']->jam_selesai }}</td>
                                <td>
                                    <span class="badge badge-primary badge-outline" data-jadwal-id="{{ $item['jadwal']->id }}">
                                        No. {{ $item['sedang_dilayani'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">Belum ada jadwal periksa</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>