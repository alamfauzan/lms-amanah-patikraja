<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.kuis.index', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Buat Kuis Baru</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">{{ $kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('kelas.kuis.store', $kelas->id) }}" method="POST" class="space-y-6"
              x-data="{
                  questions: [
                      { pertanyaan: '', tipe: 'pilihan_ganda', kunci_jawaban: 'a', poin: 10, pilihan: { a: '', b: '', c: '', d: '' } }
                  ],
                  addQuestion() {
                      this.questions.push({
                          pertanyaan: '',
                          tipe: 'pilihan_ganda',
                          kunci_jawaban: 'a',
                          poin: 10,
                          pilihan: { a: '', b: '', c: '', d: '' }
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
            @csrf

            <!-- Pengaturan Umum -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">⚙️ Pengaturan Umum Kuis</h3>
                    <p class="text-xs text-slate-400 mt-1">Konfigurasi pengaturan utama untuk kuis.</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Judul -->
                        <div class="md:col-span-2">
                            <label for="judul" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Judul Kuis <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                   placeholder="Contoh: Evaluasi Tengah Bab 1">
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Deskripsi Kuis</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                      class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                      placeholder="Tuliskan petunjuk pengerjaan di sini...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Durasi Menit -->
                        <div>
                            <label for="durasi_menit" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Durasi (Menit) <span class="text-red-500">*</span></label>
                            <input type="number" name="durasi_menit" id="durasi_menit" required min="1" value="{{ old('durasi_menit', 60) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        <!-- Batas Pengerjaan (Attempt) -->
                        <div>
                            <label for="batas_pengerjaan" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Maksimal Percobaan <span class="text-red-500">*</span></label>
                            <input type="number" name="batas_pengerjaan" id="batas_pengerjaan" required min="1" value="{{ old('batas_pengerjaan', 1) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        <!-- Bobot Nilai -->
                        <div>
                            <label for="bobot_nilai" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Bobot Nilai (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="bobot_nilai" id="bobot_nilai" required min="0" max="100" value="{{ old('bobot_nilai', 100) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        <!-- Hubungkan ke Pertemuan -->
                        <div>
                            <label for="pertemuan_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Tautkan Pertemuan</label>
                            <select name="pertemuan_id" id="pertemuan_id"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                <option value="">-- Umum (Tidak ditautkan) --</option>
                                @foreach($kelas->pertemuan as $p)
                                    <option value="{{ $p->id }}" {{ old('pertemuan_id') == $p->id ? 'selected' : '' }}>Pertemuan ke-{{ $p->urutan }}: {{ $p->judul }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jadwal Mulai -->
                        <div>
                            <label for="mulai_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Jadwal Mulai</label>
                            <input type="datetime-local" name="mulai_at" id="mulai_at" value="{{ old('mulai_at') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>

                        <!-- Jadwal Selesai -->
                        <div>
                            <label for="selesai_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-350 uppercase tracking-wider mb-2">Jadwal Selesai</label>
                            <input type="datetime-local" name="selesai_at" id="selesai_at" value="{{ old('selesai_at') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                        </div>
                    </div>

                    <!-- Aktifkan kuis langsung -->
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_aktif" id="is_aktif" value="1" checked
                               class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="is_aktif" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Aktifkan kuis ini langsung agar siswa dapat mengerjakannya</label>
                    </div>
                </div>
            </div>

            <!-- Pembuat Soal -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">✏️ Pertanyaan Kuis</h3>
                        <p class="text-xs text-slate-400 mt-1">Buat daftar pertanyaan, pilihan jawaban, kunci jawaban, dan alokasi poin.</p>
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
                            <div class="flex items-center justify-between gap-4">
                                <span class="w-8 h-8 rounded-full bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0" x-text="index + 1"></span>

                                <div class="flex items-center gap-2">
                                    <!-- Pilihan Tipe Soal -->
                                    <div class="inline-flex rounded-lg p-0.5 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-850">
                                        <button type="button" @click="changeType(index, 'pilihan_ganda')"
                                                :class="q.tipe === 'pilihan_ganda' ? 'bg-white dark:bg-slate-800 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-400 hover:text-slate-750 dark:hover:text-slate-300'"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded transition-all">
                                            Pilihan Ganda
                                        </button>
                                        <button type="button" @click="changeType(index, 'benar_salah')"
                                                :class="q.tipe === 'benar_salah' ? 'bg-white dark:bg-slate-800 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-400 hover:text-slate-750 dark:hover:text-slate-300'"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded transition-all">
                                            Benar / Salah
                                        </button>
                                        <button type="button" @click="changeType(index, 'isian_singkat')"
                                                :class="q.tipe === 'isian_singkat' ? 'bg-white dark:bg-slate-800 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-400 hover:text-slate-750 dark:hover:text-slate-300'"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded transition-all">
                                            Isian Singkat
                                        </button>
                                    </div>

                                    <!-- Hapus Soal -->
                                    <button type="button" @click="removeQuestion(index)"
                                            class="p-1 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" :name="`soal[${index}][tipe]`" :value="q.tipe">

                            <!-- Pertanyaan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350 mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                                <textarea :name="`soal[${index}][pertanyaan]`" x-model="q.pertanyaan" required rows="2"
                                          class="w-full px-4 py-2 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                          placeholder="Contoh: Apa ibukota negara Indonesia?"></textarea>
                            </div>

                            <!-- Opsi Pilihan Ganda -->
                            <div x-show="q.tipe === 'pilihan_ganda'" class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-4 border-l-2 border-indigo-500/30">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan A</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][a]`" x-model="q.pilihan.a" :required="q.tipe === 'pilihan_ganda'"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan B</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][b]`" x-model="q.pilihan.b" :required="q.tipe === 'pilihan_ganda'"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan C</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][c]`" x-model="q.pilihan.c" :required="q.tipe === 'pilihan_ganda'"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Pilihan D</label>
                                    <input type="text" :name="`soal[${index}][pilihan_jawaban][d]`" x-model="q.pilihan.d" :required="q.tipe === 'pilihan_ganda'"
                                           class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                            </div>

                            <!-- Kunci Jawaban & Poin -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Kunci Jawaban (Dynamic input based on Type) -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350 mb-1.5">Kunci Jawaban <span class="text-red-500">*</span></label>

                                    <!-- Kunci PG -->
                                    <select :name="`soal[${index}][kunci_jawaban]`" x-show="q.tipe === 'pilihan_ganda'" x-model="q.kunci_jawaban"
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                        <option value="a">Pilihan A</option>
                                        <option value="b">Pilihan B</option>
                                        <option value="c">Pilihan C</option>
                                        <option value="d">Pilihan D</option>
                                    </select>

                                    <!-- Kunci Benar / Salah -->
                                    <select :name="`soal[${index}][kunci_jawaban]`" x-show="q.tipe === 'benar_salah'" x-model="q.kunci_jawaban"
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                        <option value="benar">Benar</option>
                                        <option value="salah">Salah</option>
                                    </select>

                                    <!-- Kunci Isian Singkat -->
                                    <input type="text" :name="`soal[${index}][kunci_jawaban]`" x-show="q.tipe === 'isian_singkat'" x-model="q.kunci_jawaban" :required="q.tipe === 'isian_singkat'"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                           placeholder="Kunci jawaban persis (Huruf kecil/besar tidak sensitif)">
                                </div>

                                <!-- Alokasi Poin -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350 mb-1.5">Alokasi Poin Soal <span class="text-red-500">*</span></label>
                                    <input type="number" :name="`soal[${index}][poin]`" x-model="q.poin" required min="1"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer add button -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Poin total akan dihitung dari jumlah poin per soal.</span>
                    <button type="button" @click="addQuestion()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Soal
                    </button>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('kelas.kuis.index', $kelas->id) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 text-slate-650 dark:text-slate-450 text-sm font-semibold transition duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-indigo-650 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-650/10 transition duration-200">
                    Simpan Kuis
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
