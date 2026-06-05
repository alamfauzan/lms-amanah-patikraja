<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kuis.show', [$kelas->id, $kuis->id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Edit Kuis</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">{{ $kuis->judul }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">⚙️ Pengaturan Kuis</h3>
                <p class="text-xs text-slate-400 mt-1">Ubah konfigurasi utama kuis. Pertanyaan kuis tidak dapat diedit secara langsung demi menjaga konsistensi nilai siswa.</p>
            </div>

            <form action="{{ route('kuis.update', [$kelas->id, $kuis->id]) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Judul Kuis <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" required value="{{ old('judul', $kuis->judul) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Deskripsi Kuis</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                              placeholder="Tuliskan petunjuk pengerjaan di sini...">{{ old('deskripsi', $kuis->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Durasi Menit -->
                    <div>
                        <label for="durasi_menit" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Durasi (Menit) <span class="text-red-500">*</span></label>
                        <input type="number" name="durasi_menit" id="durasi_menit" required min="1" value="{{ old('durasi_menit', $kuis->durasi_menit) }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        @error('durasi_menit')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Batas Pengerjaan (Attempt) -->
                    <div>
                        <label for="batas_pengerjaan" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Maksimal Percobaan <span class="text-red-500">*</span></label>
                        <input type="number" name="batas_pengerjaan" id="batas_pengerjaan" required min="1" value="{{ old('batas_pengerjaan', $kuis->batas_pengerjaan) }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        @error('batas_pengerjaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bobot Nilai -->
                    <div>
                        <label for="bobot_nilai" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Bobot Nilai (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="bobot_nilai" id="bobot_nilai" required min="0" max="100" value="{{ old('bobot_nilai', $kuis->bobot_nilai) }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        @error('bobot_nilai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hubungkan ke Pertemuan (Read-only info) -->
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Ditautkan Ke</span>
                        <div class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-650 dark:text-slate-350 text-sm font-semibold">
                            {{ $kuis->pertemuan ? 'Pertemuan ke-' . $kuis->pertemuan->urutan : 'Umum (Tidak ditautkan)' }}
                        </div>
                    </div>

                    <!-- Jadwal Mulai -->
                    <div>
                        <label for="mulai_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Jadwal Mulai</label>
                        <input type="datetime-local" name="mulai_at" id="mulai_at" value="{{ old('mulai_at', $kuis->mulai_at ? $kuis->mulai_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        @error('mulai_at')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jadwal Selesai -->
                    <div>
                        <label for="selesai_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Jadwal Selesai</label>
                        <input type="datetime-local" name="selesai_at" id="selesai_at" value="{{ old('selesai_at', $kuis->selesai_at ? $kuis->selesai_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        @error('selesai_at')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ $kuis->is_aktif ? 'checked' : '' }}
                           class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="is_aktif" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Aktifkan kuis ini agar dapat diakses oleh siswa</label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('kuis.show', [$kelas->id, $kuis->id]) }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 text-slate-600 dark:text-slate-400 text-sm font-semibold transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-indigo-650 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-650/10 transition duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
