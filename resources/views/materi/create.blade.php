<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $pertemuan->mata_pelajaran_id]) }}"
               class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Tambah Materi</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">
                    {{ $kelas->nama_kelas }} &bull; Pertemuan {{ $pertemuan->urutan }}: {{ $pertemuan->judul }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('kelas.pertemuan.materi.store', [$kelas->id, $pertemuan->id]) }}"
              method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Informasi Materi --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">Informasi Materi</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Judul dan isi penjelasan materi pembelajaran.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                               placeholder="Contoh: Pengenalan Aljabar Linear"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                        @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Konten / Penjelasan --}}
                    <div>
                        <label for="konten" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                            Isi Materi / Penjelasan
                            <span class="normal-case font-normal text-slate-400">(opsional, mendukung Markdown)</span>
                        </label>
                        <textarea name="konten" id="konten" rows="8"
                                  placeholder="Tuliskan isi penjelasan materi di sini... (mendukung **tebal**, *miring*, # Judul, - list, dll.)"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition font-mono text-sm">{{ old('konten') }}</textarea>
                        @error('konten')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Lampiran Berkas / Video --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden"
                 x-data="{ fileName: '' }">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">Lampiran Berkas / Video</h3>
                            <p class="text-xs text-slate-400 mt-0.5">PDF, Word, PPT, atau Video • Maks. 100MB • Opsional</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <label for="file"
                           class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-emerald-400 dark:hover:border-emerald-500/50 rounded-xl p-8 text-center cursor-pointer transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"
                                  x-text="fileName ? fileName : 'Klik untuk pilih berkas'"></span>
                            <p class="text-xs text-slate-400 mt-1">PDF, DOC, DOCX, PPT, PPTX, MP4, AVI, MKV</p>
                        </div>
                        <input type="file" name="file" id="file" class="hidden"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,video/*"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    </label>
                    @error('file')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $pertemuan->mata_pelajaran_id]) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-600/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Materi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
