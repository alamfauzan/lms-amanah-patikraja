<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $preselectedMapelId ?? '']) }}"
               class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Buat Tugas</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">{{ $kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('kelas.tugas.store', $kelas->id) }}" enctype="multipart/form-data"
              class="space-y-5"
              x-data="{
                  selectedMapel: '{{ old('mata_pelajaran_id', $preselectedMapelId) }}',
                  meetings: {{ json_encode($kelas->pertemuan->map(fn($p) => ['id' => $p->id, 'urutan' => $p->urutan, 'judul' => $p->judul, 'mapel_id' => $p->mata_pelajaran_id])) }}
              }">
            @csrf

            {{-- Detail Tugas --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">Detail Tugas</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Isi informasi tugas yang akan diberikan kepada siswa.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Mata Pelajaran --}}
                    @if($preselectedMapelId)
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $preselectedMapelId }}">
                        <div>
                            <span class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Mata Pelajaran</span>
                            @php $selectedMapelObj = $mapels->firstWhere('id', $preselectedMapelId); @endphp
                            <div class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 font-medium text-sm">
                                {{ $selectedMapelObj ? $selectedMapelObj->nama_mapel . ' (' . $selectedMapelObj->kode_mapel . ')' : 'Mata Pelajaran' }}
                            </div>
                        </div>
                    @else
                        <div>
                            <label for="mata_pelajaran_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select id="mata_pelajaran_id" name="mata_pelajaran_id" x-model="selectedMapel" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition @error('mata_pelajaran_id') border-red-400 @enderror">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}">
                                        {{ $mapel->nama_mapel }} ({{ $mapel->kode_mapel }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    @endif

                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                            Judul Tugas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                               placeholder="Contoh: Latihan Soal Bab 1 — Persamaan Linear"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition @error('judul') border-red-400 @enderror">
                        @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="deskripsi" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                            Instruksi / Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  placeholder="Tuliskan instruksi tugas secara detail..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition resize-none">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Deadline & Nilai Max --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="deadline" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                                Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="deadline" name="deadline" value="{{ old('deadline') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition @error('deadline') border-red-400 @enderror">
                            @error('deadline')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="nilai_maksimum" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                                Nilai Maksimum <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="nilai_maksimum" name="nilai_maksimum" value="{{ old('nilai_maksimum', 100) }}" min="1" max="100"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                        </div>
                    </div>

                    {{-- Tautkan Pertemuan --}}
                    @if($preselectedPertemuanId)
                        <input type="hidden" name="pertemuan_id" value="{{ $preselectedPertemuanId }}">
                        <div>
                            <span class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Ditautkan ke Pertemuan</span>
                            @php $selectedPertemuanObj = $pertemuan->firstWhere('id', $preselectedPertemuanId); @endphp
                            <div class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 font-medium text-sm">
                                {{ $selectedPertemuanObj ? 'Pertemuan ' . $selectedPertemuanObj->urutan . ': ' . $selectedPertemuanObj->judul : 'Pertemuan' }}
                            </div>
                        </div>
                    @else
                        <div>
                            <label for="pertemuan_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">
                                Tautkan ke Pertemuan <span class="text-xs normal-case font-normal text-slate-400">(opsional)</span>
                            </label>
                            <select id="pertemuan_id" name="pertemuan_id"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                                <option value="">-- Tidak ditautkan ke pertemuan --</option>
                                <template x-for="p in meetings.filter(m => m.mapel_id == selectedMapel)" :key="p.id">
                                    <option :value="p.id" x-text="`Pertemuan ${p.urutan}: ${p.judul}`"
                                            :selected="p.id == '{{ old('pertemuan_id') }}'"></option>
                                </template>
                            </select>
                    @endif
                </div>
            </div>

            {{-- Lampiran --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden"
                 x-data="{ fileName: '' }">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">Lampiran Berkas</h3>
                            <p class="text-xs text-slate-400 mt-0.5">PDF, Word, PPT, atau ZIP • Maks. 10MB</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <label for="file"
                           class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-orange-400 dark:hover:border-orange-500/50 rounded-xl p-8 text-center cursor-pointer transition-colors">
                        <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"
                                  x-text="fileName ? fileName : 'Klik untuk pilih berkas'"></span>
                            <p class="text-xs text-slate-400 mt-1">PDF, DOC, DOCX, PPT, PPTX, ZIP</p>
                        </div>
                        <input type="file" id="file" name="file" class="hidden"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    </label>
                    @error('file')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $preselectedMapelId ?? '']) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-orange-500/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Buat Tugas
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
