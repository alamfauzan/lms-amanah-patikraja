<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('materi.show', $materi->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Edit Materi</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">Pertemuan ke-{{ $materi->pertemuan->urutan }} • {{ $materi->kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Ubah Materi</h3>
                <p class="text-xs text-slate-400 mt-1">Ubah judul materi atau konten teks yang sudah ada.</p>
            </div>

            <form action="{{ route('materi.update', $materi->id) }}" method="POST" class="p-6 space-y-6">
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

                <!-- Info Tipe Materi -->
                <div>
                    <span class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1.5">Tipe Materi</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider
                        {{ $materi->tipe === 'video' ? 'bg-red-500/10 text-red-500' : ($materi->tipe === 'file' ? 'bg-amber-500/10 text-amber-555' : 'bg-blue-500/10 text-indigo-500') }}">
                        @if($materi->tipe === 'video')
                            🎥 Video
                        @elseif($materi->tipe === 'file')
                            📄 Dokumen
                        @else
                            📚 Teks
                        @endif
                    </span>
                    <p class="text-[11px] text-slate-400 mt-1.5">Catatan: Tipe materi tidak dapat diubah setelah dibuat. Untuk mengubah berkas/video silakan buat materi baru.</p>
                </div>

                <!-- Konten Teks (Hanya jika tipe teks) -->
                @if($materi->tipe === 'teks')
                    <div class="space-y-2">
                        <label for="konten" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">Isi Materi Teks <span class="text-red-500">*</span></label>
                        <textarea name="konten" id="konten" rows="8" required
                                  class="w-full px-4 py-3 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                  placeholder="Tuliskan isi materi di sini...">{{ old('konten', $materi->konten) }}</textarea>
                        @error('konten')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-150 dark:border-slate-800 rounded-xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
                            {{ $materi->tipe === 'video' ? 'bg-red-500/10 text-red-500' : 'bg-amber-500/10 text-amber-500' }}">
                            @if($materi->tipe === 'video')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">Berkas Terunggah</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ basename($materi->file_path) }}</p>
                        </div>
                    </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('materi.show', $materi->id) }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 text-slate-600 dark:text-slate-400 text-sm font-semibold transition duration-200">
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
