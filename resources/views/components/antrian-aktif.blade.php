@props(['activeRegistration', 'sedangDilayani' => 0])

<div class="relative overflow-hidden rounded-2xl mb-8 shadow-lg"
    style="background: linear-gradient(135deg, #3d5aed 0%, #5b73f5 50%, #6c82fb 100%);">

    {{-- Decorative circles --}}
    <div class="absolute -top-10 -right-10 w-48 h-48 rounded-full opacity-10"
        style="background: radial-gradient(circle, #fff, transparent);"></div>
    <div class="absolute -bottom-12 -left-10 w-56 h-56 rounded-full opacity-10"
        style="background: radial-gradient(circle, #fff, transparent);"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        {{-- Info --}}
        <div class="flex-1">
            <p class="text-base font-bold uppercase tracking-[0.15em] text-white mb-4">
                Antrian Aktif Anda
            </p>

            <div class="space-y-5">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-white/50 mb-0.5">Poliklinik</p>
                    <p class="text-lg font-bold text-white leading-tight">
                        {{ $activeRegistration->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-white/50 mb-0.5">Dokter</p>
                    <p class="text-lg font-bold text-white leading-tight">
                        {{ $activeRegistration->jadwalPeriksa->dokter->nama }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-white/50 mb-0.5">Jadwal Periksa</p>
                    <p class="text-base font-semibold text-white/90 leading-tight">
                        {{ $activeRegistration->jadwalPeriksa->hari }}
                        ({{ \Carbon\Carbon::parse($activeRegistration->jadwalPeriksa->jam_mulai)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($activeRegistration->jadwalPeriksa->jam_selesai)->format('H:i') }})
                    </p>
                </div>
            </div>
        </div>

        {{-- Queue Numbers --}}
        <div class="flex items-stretch gap-3 shrink-0">

            {{-- Nomor Anda --}}
            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-5 text-center flex flex-col justify-center">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-white/70 mb-1">Nomor Anda</p>
                <p class="text-5xl font-bold text-white leading-none" id="nomorAntrian">
                    {{ $activeRegistration->no_antrian }}
                </p>
            </div>

            {{-- Sedang Dilayani --}}
            <div class="bg-white rounded-2xl px-6 py-5 text-center shadow-md flex flex-col justify-center">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Sedang Dilayani</p>
                <p class="text-5xl font-bold text-slate-800 leading-none" id="sedangDilayani">
                    {{ $sedangDilayani }}
                </p>
                <p class="text-[10px] text-green-500 font-semibold mt-2 flex items-center justify-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                    Live Update
                </p>
            </div>

        </div>

    </div>
</div>

@push('scripts')
@vite(['resources/js/echo.js'])
<script>
    (function () {
        const myJadwalId = {{ $activeRegistration->id_jadwal }};

        function subscribeAntrian() {
            if (!window.Echo) {
                setTimeout(subscribeAntrian, 100);
                return;
            }
            window.Echo.channel('antrian')
                .listen('.antrian.updated', function (e) {
                    if (parseInt(e.jadwalId) === myJadwalId) {
                        const el = document.getElementById('sedangDilayani');
                        if (el) el.textContent = e.nomorAntrian;
                    }
                });
        }

        subscribeAntrian();
    })();
</script>
@endpush
