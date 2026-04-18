<x-layouts.app title="Dashboard Dokter">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Selamat Datang, Dokter 👋</h1>
        <p class="text-slate-500 mt-1">{{ now()->translatedFormat('l, d F Y') }} — Berikut ringkasan aktivitas praktik Anda hari ini.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="card bg-base-100 shadow-md border-b-4 border-blue-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-calendar-days text-blue-600 text-lg"></i>
                    </div>
                    <a href="{{ route('dokter.jadwal-periksa.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalJadwal }}</h3>
                    <p class="text-sm text-slate-500">Total Jadwal</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-b-4 border-yellow-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-user-clock text-yellow-600 text-lg"></i>
                    </div>
                    <a href="{{ route('dokter.periksa-pasien.index') }}" class="text-yellow-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $pasienMenunggu }}</h3>
                    <p class="text-sm text-slate-500">Pasien Menunggu</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-b-4 border-red-500 rounded-2xl">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="fas fa-file-medical text-red-600 text-lg"></i>
                    </div>
                    <a href="{{ route('dokter.riwayat-pasien.index') }}" class="text-red-600 text-sm font-semibold hover:underline">Lihat</a>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalRiwayat }}</h3>
                    <p class="text-sm text-slate-500">Total Riwayat</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card bg-base-100 shadow-md rounded-2xl border">
            <div class="card-body p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Jadwal Periksa</h3>
                    <a href="{{ route('dokter.jadwal-periksa.index') }}" class="text-sm text-blue-600 hover:underline font-semibold">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">No.</th>
                                <th class="px-4 py-3">Hari</th>
                                <th class="px-4 py-3">Jam Mulai</th>
                                <th class="px-4 py-3">Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($jadwals as $djIdx => $jadwal)
                            <tr class="border-t hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $djIdx + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $jadwal->hari }}</td>
                                <td class="px-4 py-3">{{ substr($jadwal->jam_mulai, 0, 5) }}</td>
                                <td class="px-4 py-3">{{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md rounded-2xl border">
            <div class="card-body p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Akses Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('dokter.jadwal-periksa.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Tambah Jadwal</p>
                            <p class="text-xs text-slate-400">Tambahkan jadwal periksa baru</p>
                        </div>
                    </a>
                    <a href="{{ route('dokter.periksa-pasien.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Periksa Pasien</p>
                            <p class="text-xs text-slate-400">Lihat daftar pasien menunggu</p>
                        </div>
                    </a>
                    <a href="{{ route('dokter.riwayat-pasien.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class="fas fa-file-medical text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Riwayat Pasien</p>
                            <p class="text-xs text-slate-400">Lihat riwayat pemeriksaan</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>