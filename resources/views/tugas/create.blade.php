<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.tugas.index', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Buat Tugas</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Detail Tugas</h3>
                <p class="text-sm text-slate-400 mt-0.5">Kelas: {{ $kelas->nama_kelas }}</p>
            </div>
            <form method="POST" action="{{ route('kelas.tugas.store', $kelas->id) }}" class="px-8 py-6 space-y-5">
                @csrf
                <div>
                    <label for="judul" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Latihan Soal Bab 1"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('judul') border-red-400 @enderror">
                    @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi / Instruksi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Tuliskan instruksi tugas secara detail..."
                              class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deadline <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="deadline" name="deadline" value="{{ old('deadline') }}"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('deadline') border-red-400 @enderror">
                        @error('deadline')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nilai_maksimum" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nilai Maksimum <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_maksimum" name="nilai_maksimum" value="{{ old('nilai_maksimum', 100) }}" min="1" max="100"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="pertemuan_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pertemuan (opsional)</label>
                    <select id="pertemuan_id" name="pertemuan_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">-- Tidak terkait pertemuan --</option>
                        @foreach($kelas->pertemuan as $p)
                            <option value="{{ $p->id }}" {{ old('pertemuan_id') == $p->id ? 'selected' : '' }}>
                                Pertemuan {{ $p->urutan }}: {{ $p->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Buat Tugas
                    </button>
                    <a href="{{ route('kelas.tugas.index', $kelas->id) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
