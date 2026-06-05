<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.pertemuan.show', [$kelas->id, $pertemuan->id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Tambah Materi</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">Pertemuan ke-{{ $pertemuan->urutan }} • {{ $kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Formulir Materi Baru</h3>
                <p class="text-xs text-slate-400 mt-1">Silakan pilih tipe materi (Teks, File, atau Video) dan unggah konten materi pembelajaran.</p>
            </div>

            <form action="{{ route('kelas.pertemuan.materi.store', [$kelas->id, $pertemuan->id]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" x-data="{ tipe: 'teks' }">
                @csrf

                <!-- Judul Materi -->
                <div>
                    <label for="judul" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-2">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                           placeholder="Contoh: Pengenalan Aljabar Linear">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Materi (Tabs) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-2">Tipe Materi</label>
                    <input type="hidden" name="tipe" :value="tipe">
                    <div class="grid grid-cols-3 gap-3 p-1 bg-slate-100 dark:bg-slate-950 rounded-xl">
                        <button type="button" @click="tipe = 'teks'"
                                :class="tipe === 'teks' ? 'bg-white dark:bg-slate-900 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-550 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="py-2.5 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-2">
                            📚 Teks
                        </button>
                        <button type="button" @click="tipe = 'file'"
                                :class="tipe === 'file' ? 'bg-white dark:bg-slate-900 shadow text-amber-600 dark:text-amber-400' : 'text-slate-550 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="py-2.5 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-2">
                            📄 Dokumen
                        </button>
                        <button type="button" @click="tipe = 'video'"
                                :class="tipe === 'video' ? 'bg-white dark:bg-slate-900 shadow text-red-650 dark:text-red-400' : 'text-slate-550 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="py-2.5 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-2">
                            🎥 Video
                        </button>
                    </div>
                </div>

                <!-- Konten Teks -->
                <div x-show="tipe === 'teks'" x-transition:enter="transition ease-out duration-200" class="space-y-2">
                    <label for="konten" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">Isi Materi Teks <span class="text-red-500">*</span></label>
                    <textarea name="konten" id="konten" rows="8"
                              class="w-full px-4 py-3 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                              placeholder="Tuliskan isi materi di sini...">{{ old('konten') }}</textarea>
                    @error('konten')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konten File / Dokumen -->
                <div x-show="tipe === 'file'" x-transition:enter="transition ease-out duration-200" class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350">Unggah Dokumen <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 dark:hover:border-indigo-500/50 rounded-2xl p-6 text-center transition duration-200">
                        <input type="file" name="file" id="file" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx">
                        <label for="file" class="cursor-pointer flex flex-col items-center">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-500 mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-750 dark:text-slate-300">Pilih berkas dari komputer Anda</span>
                            <span class="text-xs text-slate-400 mt-1">Mendukung file PDF, DOC, DOCX, PPT, PPTX (Maksimal 20MB)</span>
                        </label>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konten Video -->
                <div x-show="tipe === 'video'" x-transition:enter="transition ease-out duration-200" class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350">Unggah Video <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 dark:hover:border-indigo-500/50 rounded-2xl p-6 text-center transition duration-200">
                        <input type="file" name="file_video" id="file_video" class="hidden" accept="video/mp4,video/x-msvideo,video/x-matroska">
                        <label for="file_video" class="cursor-pointer flex flex-col items-center">
                            <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center text-red-550 mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-750 dark:text-slate-300">Pilih video dari komputer Anda</span>
                            <span class="text-xs text-slate-400 mt-1">Mendukung file MP4, AVI, MKV (Maksimal 100MB)</span>
                        </label>
                    </div>
                    @error('file_video')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('kelas.pertemuan.show', [$kelas->id, $pertemuan->id]) }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 text-slate-600 dark:text-slate-400 text-sm font-semibold transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-indigo-650 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-650/10 transition duration-200">
                        Simpan Materi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
