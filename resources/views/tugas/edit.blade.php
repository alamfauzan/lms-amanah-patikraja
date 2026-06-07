<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.tugas.index', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Edit Tugas</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Edit Tugas</h3>
                <p class="text-sm text-slate-400 mt-0.5">Kelas: {{ $kelas->nama_kelas }}</p>
            </div>
            <form method="POST" action="{{ route('kelas.tugas.update', [$kelas->id, $tugas->id]) }}" enctype="multipart/form-data" class="px-8 py-6 space-y-5"
                  x-data="{ selectedMapel: '{{ old('mata_pelajaran_id', $tugas->mata_pelajaran_id) }}', meetings: {{ json_encode($kelas->pertemuan->map(fn($p) => ['id' => $p->id, 'urutan' => $p->urutan, 'judul' => $p->judul, 'mapel_id' => $p->mata_pelajaran_id])) }} }">
                @csrf @method('PUT')
                <div>
                    <label for="mata_pelajaran_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="mata_pelajaran_id" name="mata_pelajaran_id" x-model="selectedMapel"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('mata_pelajaran_id') border-red-400 @enderror">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">
                                {{ $mapel->nama_mapel }} ({{ $mapel->kode_mapel }})
                            </option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="judul" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $tugas->judul) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi / Instruksi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deadline <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="deadline" name="deadline"
                               value="{{ old('deadline', $tugas->deadline->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label for="nilai_maksimum" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nilai Maksimum <span class="text-red-500">*</span></label>
                        <input type="number" id="nilai_maksimum" name="nilai_maksimum"
                               value="{{ old('nilai_maksimum', $tugas->nilai_maksimum) }}" min="1" max="100"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="file" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Lampiran Berkas Soal / Pendukung <span class="text-xs font-normal text-slate-400 dark:text-slate-500">(opsional)</span></label>
                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                           class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-805 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Mendukung berkas PDF, Word, PPT, atau ZIP. Maksimal 10MB.</p>
                    @if($tugas->file_path)
                        <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-850 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-xs text-slate-650 dark:text-slate-300 font-medium truncate max-w-xs">{{ basename($tugas->file_path) }}</span>
                            </div>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" name="hapus_berkas" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-xs font-semibold text-red-500">Hapus Berkas</span>
                            </label>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="pertemuan_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pertemuan (opsional)</label>
                    <select id="pertemuan_id" name="pertemuan_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-805 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">-- Tidak terkait pertemuan --</option>
                        <template x-for="p in meetings.filter(m => m.mapel_id == selectedMapel)" :key="p.id">
                            <option :value="p.id" x-text="`Pertemuan ${p.urutan}: ${p.judul}`" :selected="p.id == '{{ old('pertemuan_id', $tugas->pertemuan_id) }}'"></option>
                        </template>
                    </select>
                </div>
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Update Tugas
                    </button>
                    <a href="{{ route('kelas.tugas.index', $kelas->id) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
