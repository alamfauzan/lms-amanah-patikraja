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

    <div class="max-w-4xl mx-auto"
         x-data="{
             questions: {{ json_encode($kuis->soal->sortBy('urutan')->values()->map(fn($s) => [
                 'id'           => $s->id,
                 'pertanyaan'   => $s->pertanyaan,
                 'tipe'         => $s->tipe,
                 'kunci_jawaban'=> $s->kunci_jawaban,
                 'poin'         => $s->poin,
                 'pilihan'      => $s->pilihan_jawaban ?? ['a'=>'','b'=>'','c'=>'','d'=>''],
                 'existingGambar' => $s->gambar ? asset('storage/'.$s->gambar) : null,
             ])) }},
             addQuestion() {
                 this.questions.push({
                     id: null,
                     pertanyaan: '',
                     tipe: 'pilihan_ganda',
                     kunci_jawaban: 'a',
                     poin: 10,
                     pilihan: { a: '', b: '', c: '', d: '' },
                     existingGambar: null,
                 });
             },
             removeQuestion(index) {
                 if (this.questions.length > 1) {
                     this.questions.splice(index, 1);
                 } else {
                     alert('Minimal harus ada 1 soal kuis!');
                 }
             },
             changeType(index, type) {
                 this.questions[index].tipe = type;
                 if (type === 'benar_salah') {
                     this.questions[index].kunci_jawaban = 'benar';
                 } else if (type === 'isian_singkat') {
                     this.questions[index].kunci_jawaban = '';
                 } else {
                     this.questions[index].kunci_jawaban = 'a';
                 }
             }
         }">

        <form action="{{ route('kelas.kuis.update', [$kelas->id, $kuis->id]) }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Pengaturan Umum --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">Pengaturan Umum Kuis</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Ubah konfigurasi utama kuis.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Mata Pelajaran (read-only) --}}
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Mata Pelajaran</span>
                        <div class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-medium text-sm">
                            {{ $kuis->mataPelajaran->nama_mapel }} ({{ $kuis->mataPelajaran->kode_mapel }})
                        </div>
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $kuis->mata_pelajaran_id }}">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Judul --}}
                        <div class="md:col-span-2">
                            <label for="judul" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Judul Kuis <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" id="judul" required value="{{ old('judul', $kuis->judul) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                            @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kuis</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                      placeholder="Tuliskan petunjuk pengerjaan di sini...">{{ old('deskripsi', $kuis->deskripsi) }}</textarea>
                        </div>

                        {{-- Durasi --}}
                        <div>
                            <label for="durasi_menit" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Durasi (Menit) <span class="text-red-500">*</span></label>
                            <input type="number" name="durasi_menit" id="durasi_menit" required min="1" value="{{ old('durasi_menit', $kuis->durasi_menit) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        {{-- Batas Percobaan --}}
                        <div>
                            <label for="batas_pengerjaan" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Maksimal Percobaan <span class="text-red-500">*</span></label>
                            <input type="number" name="batas_pengerjaan" id="batas_pengerjaan" required min="1" value="{{ old('batas_pengerjaan', $kuis->batas_pengerjaan) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        {{-- Nilai Diambil Dari --}}
                        <div>
                            <label for="nilai_diambil_dari" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Nilai Diambil Dari <span class="text-red-500">*</span></label>
                            <select name="nilai_diambil_dari" id="nilai_diambil_dari" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                <option value="terakhir" @selected(old('nilai_diambil_dari', $kuis->nilai_diambil_dari) === 'terakhir')>Percobaan terakhir</option>
                                <option value="tertinggi" @selected(old('nilai_diambil_dari', $kuis->nilai_diambil_dari) === 'tertinggi')>Nilai tertinggi</option>
                                <option value="rata_rata" @selected(old('nilai_diambil_dari', $kuis->nilai_diambil_dari) === 'rata_rata')>Rata-rata semua percobaan</option>
                            </select>
                        </div>

                        {{-- Bobot Nilai --}}
                        <div>
                            <label for="bobot_nilai" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Bobot Nilai (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="bobot_nilai" id="bobot_nilai" required min="0" max="100" value="{{ old('bobot_nilai', $kuis->bobot_nilai) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        {{-- Jadwal Mulai --}}
                        <div>
                            <label for="mulai_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Jadwal Mulai</label>
                            <input type="datetime-local" name="mulai_at" id="mulai_at" value="{{ old('mulai_at', $kuis->mulai_at ? $kuis->mulai_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        {{-- Jadwal Selesai --}}
                        <div>
                            <label for="selesai_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 uppercase tracking-wider mb-2">Jadwal Selesai</label>
                            <input type="datetime-local" name="selesai_at" id="selesai_at" value="{{ old('selesai_at', $kuis->selesai_at ? $kuis->selesai_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ $kuis->is_aktif ? 'checked' : '' }}
                               class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="is_aktif" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Aktifkan kuis ini agar dapat diakses oleh siswa</label>
                    </div>
                </div>
            </div>

            {{-- Pertanyaan Kuis --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-500/10 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">Pertanyaan Kuis</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Edit pertanyaan, pilihan jawaban, dan kunci jawaban.</p>
                        </div>
                    </div>
                    <button type="button" @click="addQuestion()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Soal
                    </button>
                </div>

                <div class="p-6 space-y-6 divide-y divide-slate-100 dark:divide-slate-800/80">
                    <template x-for="(q, index) in questions" :key="index">
                        <div class="pt-6 first:pt-0 space-y-4">
                            {{-- Hidden soal ID (for existing soal) --}}
                            <input type="hidden" :name="`soal[${index}][id]`" :value="q.id || ''">
                            <input type="hidden" :name="`soal[${index}][tipe]`" :value="q.tipe">

                            <div class="flex items-center justify-between gap-4">
                                <span class="w-8 h-8 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0" x-text="index + 1"></span>
                                <div class="flex items-center gap-2">
                                    {{-- Tipe Soal --}}
                                    <div class="inline-flex rounded-lg p-0.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                        <button type="button" @click="changeType(index, 'pilihan_ganda')"
                                                :class="q.tipe === 'pilihan_ganda' ? 'bg-white dark:bg-slate-800 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-400 hover:text-slate-700'"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded transition-all">Pilihan Ganda</button>
                                        <button type="button" @click="changeType(index, 'benar_salah')"
                                                :class="q.tipe === 'benar_salah' ? 'bg-white dark:bg-slate-800 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-400 hover:text-slate-700'"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded transition-all">Benar/Salah</button>
                                        <button type="button" @click="changeType(index, 'isian_singkat')"
                                                :class="q.tipe === 'isian_singkat' ? 'bg-white dark:bg-slate-800 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-400 hover:text-slate-700'"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded transition-all">Isian Singkat</button>
                                    </div>
                                    {{-- Hapus Soal --}}
                                    <button type="button" @click="removeQuestion(index)"
                                            class="p-1 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Pertanyaan --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                                <textarea :name="`soal[${index}][pertanyaan]`" x-model="q.pertanyaan" required rows="2"
                                          class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                          placeholder="Tulis pertanyaan di sini..."></textarea>
                            </div>

                            {{-- Gambar Soal --}}
                            <div x-data="{ previewUrl: q.existingGambar || null, fileName: '' }">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1.5">
                                    Gambar Soal <span class="normal-case font-normal text-slate-400 ml-1">(opsional • maks 2MB)</span>
                                </label>
                                <div class="flex items-start gap-3">
                                    <template x-if="previewUrl">
                                        <div class="relative shrink-0">
                                            <img :src="previewUrl" alt="Preview" class="w-28 h-20 object-cover rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                                            <button type="button" @click="previewUrl = null; fileName = ''; $refs.fileInput.value = '';"
                                                    class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow hover:bg-red-600 transition">✕</button>
                                        </div>
                                    </template>
                                    <label :for="`gambar-input-${index}`"
                                           class="flex-1 flex items-center gap-3 px-4 py-3 border-2 border-dashed rounded-xl cursor-pointer transition-colors border-slate-200 dark:border-slate-800 hover:border-indigo-400">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400" x-text="fileName || (previewUrl ? 'Ganti gambar...' : 'Klik untuk unggah gambar')"></p>
                                        <input type="file"
                                               :id="`gambar-input-${index}`"
                                               :name="`gambar[${index}]`"
                                               accept="image/jpeg,image/png,image/gif,image/webp"
                                               class="hidden"
                                               x-ref="fileInput"
                                               @change="
                                                   const file = $event.target.files[0];
                                                   if (file) {
                                                       fileName = file.name;
                                                       const reader = new FileReader();
                                                       reader.onload = e => previewUrl = e.target.result;
                                                       reader.readAsDataURL(file);
                                                   }
                                               ">
                                    </label>
                                </div>
                            </div>

                            {{-- Pilihan Ganda --}}
                            <div x-show="q.tipe === 'pilihan_ganda'" class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-4 border-l-2 border-indigo-500/30">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan A</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][a]`" x-model="q.pilihan.a"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan B</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][b]`" x-model="q.pilihan.b"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan C</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][c]`" x-model="q.pilihan.c"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan D</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][d]`" x-model="q.pilihan.d"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                            </div>

                            {{-- Kunci Jawaban + Poin --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1.5">Kunci Jawaban <span class="text-red-500">*</span></label>
                                    {{-- PG --}}
                                    <select :name="`soal[${index}][kunci_jawaban]`" x-show="q.tipe === 'pilihan_ganda'" x-model="q.kunci_jawaban"
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                        <option value="a">Pilihan A</option>
                                        <option value="b">Pilihan B</option>
                                        <option value="c">Pilihan C</option>
                                        <option value="d">Pilihan D</option>
                                    </select>
                                    {{-- Benar/Salah --}}
                                    <select :name="`soal[${index}][kunci_jawaban]`" x-show="q.tipe === 'benar_salah'" x-model="q.kunci_jawaban"
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                        <option value="benar">Benar</option>
                                        <option value="salah">Salah</option>
                                    </select>
                                    {{-- Isian --}}
                                    <input type="text" :name="`soal[${index}][kunci_jawaban]`" x-show="q.tipe === 'isian_singkat'" x-model="q.kunci_jawaban"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                           placeholder="Kunci jawaban eksak (tidak case-sensitive)">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1.5">Poin Soal <span class="text-red-500">*</span></label>
                                    <input type="number" :name="`soal[${index}][poin]`" x-model="q.poin" required min="1"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Poin total dihitung dari jumlah poin per soal.</span>
                    <button type="button" @click="addQuestion()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Soal
                    </button>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('kuis.show', [$kelas->id, $kuis->id]) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 text-sm font-semibold transition duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-600/10 transition duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
