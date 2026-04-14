<x-layouts.app title="Admin Dashboard">

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">
            Selamat Datang, Admin 👋
        </h1>
        <p class="text-slate-500 mt-1">
            {{ now()->translatedFormat('l, d F Y') }} — Berikut ringkasan data sistem poliklinik.
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="card bg-base-100 shadow-md border-b-4 border-blue-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-hospital text-blue-600 text-lg"></i>
                    </div>
                    <a href="{{ route('polis.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalPoli }}</h3>
                    <p class="text-sm text-slate-500">Total Poli</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-b-4 border-green-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="fas fa-user-doctor text-green-600 text-lg"></i>
                    </div>
                    <a href="{{ route('dokter.index') }}" class="text-green-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalDokter }}</h3>
                    <p class="text-sm text-slate-500">Total Dokter</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-b-4 border-yellow-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-bed-pulse text-yellow-600 text-lg"></i>
                    </div>
                    <a href="{{ route('pasien.index') }}" class="text-yellow-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalPasien }}</h3>
                    <p class="text-sm text-slate-500">Total Pasien</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-b-4 border-pink-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center">
                        <i class="fas fa-pills text-pink-600 text-lg"></i>
                    </div>
                    <a href="{{ route('obat.index') }}" class="text-pink-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalObat }}</h3>
                    <p class="text-sm text-slate-500">Total Obat</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Daftar Poli & Quick Access --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Daftar Poli --}}
        <div class="lg:col-span-2 card bg-base-100 shadow-md rounded-2xl border">
            <div class="card-body p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Poli</h3>
                    <a href="{{ route('polis.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Nama Poli</th>
                                <th class="px-4 py-3">Keterangan</th>
                                <th class="px-4 py-3">Dokter</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($polis as $poli)
                            <tr class="border-t hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold">{{ $poli->nama_poli }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ Str::limit($poli->keterangan, 80) }}</td>
                                <td class="px-4 py-3">
                                    @foreach($poli->dokters as $d)
                                        <span class="badge badge-sm bg-blue-100 text-blue-700 border-none">{{ $d->nama }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Access --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border">
            <div class="card-body p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Akses Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('polis.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Tambah Poli</p>
                            <p class="text-xs text-slate-400">Daftarkan poli baru</p>
                        </div>
                    </a>
                    <a href="{{ route('dokter.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fas fa-user-doctor text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Tambah Dokter</p>
                            <p class="text-xs text-slate-400">Daftarkan dokter baru</p>
                        </div>
                    </a>
                    <a href="{{ route('pasien.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-bed-pulse text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Tambah Pasien</p>
                            <p class="text-xs text-slate-400">Daftarkan pasien baru</p>
                        </div>
                    </a>
                    <a href="{{ route('obat.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                            <i class="fas fa-pills text-pink-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Tambah Obat</p>
                            <p class="text-xs text-slate-400">Tambahkan obat baru</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>