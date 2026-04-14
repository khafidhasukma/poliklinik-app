<x-layouts.app title="Daftar Poli">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.dashboard') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Daftar Poli</h2>
    </div>

    @if(session('error'))
        <div class="alert alert-error mb-6 rounded-xl">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-6">
            <form action="{{ route('pasien.daftar.store') }}" method="POST">
                @csrf

                {{-- No Rekam Medis --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. Rekam Medis</label>
                    <input type="text" value="{{ $no_rm }}" disabled class="w-full px-4 py-2 border-2 rounded-lg bg-slate-50 text-slate-500">
                </div>

                {{-- Pilih Poli --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Poli <span class="text-red-500">*</span></label>
                    <select id="poliSelect" class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none" required>
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Jadwal --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Jadwal <span class="text-red-500">*</span></label>
                    <select name="id_jadwal" id="jadwalSelect" class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none" required disabled>
                        <option value="">-- Pilih Poli Terlebih Dahulu --</option>
                    </select>
                    @error('id_jadwal') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Keluhan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Keluhan <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" rows="4" placeholder="Tuliskan keluhan Anda..." class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none" required>{{ old('keluhan') }}</textarea>
                    @error('keluhan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition">
                        <i class="fas fa-paper-plane mr-1"></i> Daftar
                    </button>
                    <a href="{{ route('pasien.dashboard') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('poliSelect').addEventListener('change', function() {
            const poliId = this.value;
            const jadwalSelect = document.getElementById('jadwalSelect');

            if (!poliId) {
                jadwalSelect.disabled = true;
                jadwalSelect.innerHTML = '<option value="">-- Pilih Poli Terlebih Dahulu --</option>';
                return;
            }

            fetch(`{{ url('pasien/daftar/jadwal') }}/${poliId}`)
                .then(res => res.json())
                .then(data => {
                    jadwalSelect.disabled = false;
                    jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
                    data.forEach(jadwal => {
                        const opt = document.createElement('option');
                        opt.value = jadwal.id;
                        opt.textContent = `${jadwal.dokter.nama} — ${jadwal.hari}, ${jadwal.jam_mulai} - ${jadwal.jam_selesai}`;
                        jadwalSelect.appendChild(opt);
                    });

                    if (data.length === 0) {
                        jadwalSelect.innerHTML = '<option value="">Tidak ada jadwal tersedia</option>';
                    }
                })
                .catch(() => {
                    jadwalSelect.innerHTML = '<option value="">Gagal memuat jadwal</option>';
                });
        });
    </script>
    @endpush

</x-layouts.app>
