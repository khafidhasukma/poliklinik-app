<x-layouts.app title="Periksa Pasien">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.periksa-pasien.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Periksa Pasien</h2>
    </div>

    {{-- Patient Info --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border mb-6">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Pasien</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-500">Nama Pasien:</span>
                    <span class="font-semibold ml-2">{{ $daftarPoli->pasien->nama }}</span>
                </div>
                <div>
                    <span class="text-slate-500">No. Antrian:</span>
                    <span class="font-semibold ml-2">{{ $daftarPoli->no_antrian }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="text-slate-500">Keluhan:</span>
                    <span class="font-semibold ml-2">{{ $daftarPoli->keluhan }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Periksa --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border">
        <div class="card-body p-6">
            <form action="{{ route('dokter.periksa-pasien.store', $daftarPoli->id) }}" method="POST" id="formPeriksa">
                @csrf

                {{-- Pilih Obat --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Obat <span class="text-red-500">*</span></label>
                    <select id="obatSelect" class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none">
                        <option value="">-- Pilih Obat --</option>
                        @foreach($obats as $obat)
                            <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}" data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok }}">
                                {{ $obat->nama_obat }} — Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                @if($obat->stok <= 0) (HABIS) @elseif($obat->stok <= 5) (Stok: {{ $obat->stok }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('obat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Selected Medicines --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Obat Terpilih</label>
                    <div id="selectedObat" class="space-y-2">
                        <p id="emptyText" class="text-sm text-slate-400 italic">Belum ada obat dipilih</p>
                    </div>
                </div>

                {{-- Total --}}
                <div class="mb-6 p-4 bg-slate-50 rounded-xl">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-slate-700">Total Harga</span>
                        <span id="totalHarga" class="text-lg font-bold text-primary">Rp 150.000</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">* Termasuk biaya konsultasi Rp 150.000</p>
                </div>

                {{-- Catatan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan <span class="text-slate-400">(Opsional)</span></label>
                    <textarea name="catatan" rows="4" placeholder="Masukkan catatan..." class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('dokter.periksa-pasien.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedObats = [];
        const biayaKonsultasi = 150000;

        document.getElementById('obatSelect').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (!option.value) return;

            const id = option.value;
            const nama = option.dataset.nama;
            const harga = parseInt(option.dataset.harga);
            const stok = parseInt(option.dataset.stok);

            if (stok <= 0) {
                alert('Stok obat "' + nama + '" habis!');
                this.value = '';
                return;
            }

            selectedObats.push({ id, nama, harga });
            this.value = '';
            renderObats();
        });

        function removeObat(index) {
            selectedObats.splice(index, 1);
            renderObats();
        }

        function renderObats() {
            const container = document.getElementById('selectedObat');
            const emptyText = document.getElementById('emptyText');
            const form = document.getElementById('formPeriksa');

            // Remove existing hidden inputs and items
            container.querySelectorAll('.obat-item').forEach(el => el.remove());
            form.querySelectorAll('input[name="obat[]"]').forEach(el => el.remove());

            if (selectedObats.length === 0) {
                emptyText.style.display = 'block';
            } else {
                emptyText.style.display = 'none';
                selectedObats.forEach((obat, i) => {
                    // UI item
                    const div = document.createElement('div');
                    div.className = 'obat-item flex items-center justify-between p-3 bg-white border rounded-xl';
                    div.innerHTML = `
                        <span class="text-sm font-medium">${obat.nama} — Rp ${obat.harga.toLocaleString('id-ID')}</span>
                        <button type="button" onclick="removeObat(${i})" class="w-8 h-8 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    `;
                    container.appendChild(div);

                    // Hidden input
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'obat[]';
                    input.value = obat.id;
                    form.appendChild(input);
                });
            }

            // Update total
            const totalObat = selectedObats.reduce((sum, o) => sum + o.harga, 0);
            document.getElementById('totalHarga').textContent = 'Rp ' + (biayaKonsultasi + totalObat).toLocaleString('id-ID');
        }
    </script>
    @endpush

</x-layouts.app>
