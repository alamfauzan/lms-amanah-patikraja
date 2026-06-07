<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('materi.show', $materi->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Edit Materi</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Ubah Materi</h3>
                <p class="text-xs text-slate-400 mt-1">Ubah judul materi atau konten teks yang sudah ada.</p>
            </div>
            <form action="{{ route('materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Judul Materi -->
                <div>
                    <label for="judul" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-2">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" required value="{{ old('judul', $materi->judul) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                           placeholder="Contoh: Pengenalan Aljabar Linear">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi Materi Teks -->
                <div class="space-y-2">
                    <label for="konten" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">Isi Materi / Penjelasan <span class="text-xs font-normal text-slate-400 dark:text-slate-500">(Opsional, Mendukung Markdown)</span></label>
                    <textarea name="konten" id="konten" rows="8"
                              class="w-full px-4 py-3 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                              placeholder="Tuliskan isi penjelasan materi di sini... (Mendukung pemformatan teks seperti judul, list, tebal, dll.)">{{ old('konten', $materi->konten) }}</textarea>
                    @error('konten')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lampiran Berkas / Video Saat Ini -->
                @if($materi->file_path)
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-150 dark:border-slate-800 rounded-xl space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-indigo-500/10 text-indigo-650 dark:text-indigo-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">Lampiran Saat Ini</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ basename($materi->file_path) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-2 border-t border-slate-200/50 dark:border-slate-800/50">
                            <label class="flex items-center gap-2 text-xs font-semibold text-red-650 dark:text-red-405 cursor-pointer">
                                <input type="checkbox" name="hapus_berkas" value="1" class="rounded text-red-600 focus:ring-red-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900">
                                Hapus Lampiran Saat Ini
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Ganti / Unggah Berkas Baru -->
                <div x-data="{ fileName: '' }" class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-355">
                        {{ $materi->file_path ? 'Ganti Lampiran Berkas / Video' : 'Unggah Lampiran Berkas / Video' }} 
                        <span class="text-xs font-normal text-slate-400 dark:text-slate-500">(Opsional)</span>
                    </label>
                    <div class="border-2 border-dashed border-slate-205 dark:border-slate-800 hover:border-indigo-500/50 dark:hover:border-indigo-500/50 rounded-2xl p-6 text-center transition duration-200 relative bg-slate-50/20 dark:bg-slate-950/20">
                        <input type="file" name="file" id="file" class="hidden" 
                               accept=".pdf,.doc,.docx,.ppt,.pptx,video/*"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        <label for="file" class="cursor-pointer flex flex-col items-center">
                            <div class="w-12 h-12 bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-750 dark:text-slate-300" x-text="fileName ? fileName : 'Pilih berkas baru dari komputer Anda'">Pilih berkas baru dari komputer Anda</span>
                            <span class="text-xs text-slate-405 mt-1">Mendukung Dokumen (PDF, Word, PPT) atau Video (MP4, AVI, MKV) hingga 100MB</span>
                        </label>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('materi.show', $materi->id) }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-855 text-slate-600 dark:text-slate-400 text-sm font-semibold transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-indigo-650 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-650/10 transition duration-200">
                        Perbarui Materi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
