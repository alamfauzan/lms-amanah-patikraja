<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="font-bold text-lg text-slate-850 dark:text-slate-100">{{ $kuis->judul }}</h2>
            </div>
        </div>
    </x-slot>

    <!-- Offline Banner -->
    <div id="offline-banner" class="hidden mb-4 px-4 py-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-xl flex items-center gap-3 text-xs font-semibold text-amber-700 dark:text-amber-400">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <span>Koneksi terputus — jawaban tersimpan di perangkat, akan otomatis tersinkron saat online kembali.</span>
    </div>

    <!-- Quiz Engine Workspace Container -->
    <div class="max-w-6xl mx-auto"
         x-data="{
             activeQuestionIndex: 0,
             questionsCount: {{ $kuis->soal->count() }},
             timeLeftSeconds: {{ max(0, $kuis->durasi_menit * 60 - now()->diffInSeconds($attempt->mulai_at)) }},
             storageKey: 'kuis_{{ $kuis->id }}_attempt_{{ $attempt->attempt }}_{{ auth()->id() }}',
             saveQueue: {},
             isSyncing: false,
             isSubmitting: false,
             showSubmitModal: false,
             showTimeupModal: false,
             answers: {
                 @foreach($kuis->soal as $soal)
                     @php
                         $answ = \App\Models\JawabanSiswa::where(['soal_id' => $soal->id, 'siswa_id' => auth()->id(), 'attempt' => $attempt->attempt])->first();
                     @endphp
                     '{{ $soal->id }}': '{{ $answ ? e($answ->jawaban) : '' }}',
                 @endforeach
             },
             loadFromLocalStorage() {
                 try {
                     const saved = localStorage.getItem(this.storageKey);
                     if (saved) {
                         const parsed = JSON.parse(saved);
                         // Merge: server data takes priority if not empty
                         Object.keys(parsed).forEach(k => {
                             if (!this.answers[k]) this.answers[k] = parsed[k];
                         });
                     }
                 } catch(e) {}
             },
             saveToLocalStorage() {
                 try {
                     localStorage.setItem(this.storageKey, JSON.stringify(this.answers));
                 } catch(e) {}
             },
             saveAnswer(soalId) {
                 // 1. Simpan ke localStorage dulu (instant, offline-safe)
                 this.saveToLocalStorage();

                 // 2. Tambahkan ke antrian kirim ke server
                 this.saveQueue[soalId] = this.answers[soalId];
                 this.flushQueue();
             },
             flushQueue() {
                 if (this.isSyncing || Object.keys(this.saveQueue).length === 0) return;
                 if (!navigator.onLine) return; // tunggu sampai online

                 this.isSyncing = true;
                 const batch = { ...this.saveQueue };

                 const sendPromises = Object.entries(batch).map(([soalId, jawaban]) => {
                     return fetch('{{ route('kuis.jawab', [$kelas->id, $kuis->id]) }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         },
                         body: JSON.stringify({
                             soal_id: soalId,
                             jawaban: jawaban,
                             attempt: {{ $attempt->attempt }}
                         })
                     })
                     .then(r => r.ok ? soalId : null)
                     .catch(() => null);
                 });

                 Promise.all(sendPromises).then(results => {
                     results.forEach(soalId => {
                         if (soalId) delete this.saveQueue[soalId]; // hapus dari queue jika sukses
                     });
                     this.isSyncing = false;
                     // Ada yang gagal? Retry setelah 3 detik
                     if (Object.keys(this.saveQueue).length > 0) {
                         setTimeout(() => this.flushQueue(), 3000);
                     }
                 });
             },
             async submitSekarang() {
                 if (this.isSubmitting) return;
                 this.isSubmitting = true;

                 // Kirim SEMUA jawaban yang ada (bukan cuma yang di queue) ke server
                 const sends = Object.entries(this.answers).map(([soalId, jawaban]) => {
                     return fetch('{{ route('kuis.jawab', [$kelas->id, $kuis->id]) }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         },
                         body: JSON.stringify({
                             soal_id: soalId,
                             jawaban: jawaban,
                             attempt: {{ $attempt->attempt }}
                         })
                     }).catch(() => null);
                 });

                 await Promise.all(sends);

                 try { localStorage.removeItem(this.storageKey); } catch(e) {}
                 document.getElementById('submit-kuis-form').submit();
             },
             formatTime() {
                 let m = Math.floor(this.timeLeftSeconds / 60);
                 let s = this.timeLeftSeconds % 60;
                 return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
             },
             init() {
                 // Load backup dari localStorage
                 this.loadFromLocalStorage();

                 // Timer countdown
                 let interval = setInterval(() => {
                     if (this.timeLeftSeconds <= 0) {
                         clearInterval(interval);
                         this.showTimeupModal = true;
                     } else {
                         this.timeLeftSeconds--;
                     }
                 }, 1000);

                 // Monitor online/offline
                 const banner = document.getElementById('offline-banner');
                 window.addEventListener('offline', () => {
                     banner.classList.remove('hidden');
                 });
                 window.addEventListener('online', () => {
                     banner.classList.add('hidden');
                     this.flushQueue(); // langsung sync saat kembali online
                 });

                 // Retry queue setiap 5 detik jika ada yang gagal
                 setInterval(() => this.flushQueue(), 5000);

                 // Bersihkan localStorage saat submit
                 document.getElementById('submit-kuis-form').addEventListener('submit', () => {
                     try { localStorage.removeItem(this.storageKey); } catch(e) {}
                 });
             }
         }">

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            <!-- Left Panel: Main Question View -->
            <div class="lg:col-span-3 space-y-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-650 dark:text-indigo-400 uppercase tracking-wide">
                            Pertanyaan <span x-text="activeQuestionIndex + 1"></span> dari <span x-text="questionsCount"></span>
                        </span>
                        <span class="text-[10px] text-slate-400">Poin Soal: <span x-text="document.querySelector('[data-soal-index=\'' + activeQuestionIndex + '\']')?.dataset.poin || '0'"></span> Poin</span>
                    </div>

                    <div class="p-6">
                        @foreach($kuis->soal as $i => $soal)
                            <div x-show="activeQuestionIndex === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-150"
                                 data-soal-index="{{ $i }}"
                                 data-poin="{{ $soal->poin }}"
                                 class="space-y-6">

                                <!-- Pertanyaan Text -->
                                <div class="text-slate-800 dark:text-slate-100 font-semibold text-base leading-relaxed select-none">
                                    {!! nl2br(e($soal->pertanyaan)) !!}
                                </div>

                                <!-- Answers Choices -->
                                @if($soal->tipe === 'pilihan_ganda')
                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach(['a', 'b', 'c', 'd'] as $optKey)
                                            @if(isset($soal->pilihan_jawaban[$optKey]) && $soal->pilihan_jawaban[$optKey] !== '')
                                                <label class="flex items-center gap-3.5 p-4 rounded-xl border cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-850/50 transition duration-150"
                                                       :class="answers['{{ $soal->id }}'] === '{{ $optKey }}' ? 'border-indigo-650 dark:border-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/10' : 'border-slate-200 dark:border-slate-800/80'">
                                                    <input type="radio" name="jawaban_{{ $soal->id }}" value="{{ $optKey }}"
                                                           x-model="answers['{{ $soal->id }}']" @change="saveAnswer('{{ $soal->id }}')"
                                                           class="text-indigo-600 border-slate-350 dark:border-slate-800 focus:ring-indigo-500 shrink-0">
                                                    <span class="text-xs font-bold text-slate-400 shrink-0 uppercase">{{ $optKey }}.</span>
                                                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300 leading-snug">{{ $soal->pilihan_jawaban[$optKey] }}</span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>

                                @elseif($soal->tipe === 'benar_salah')
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach(['benar', 'salah'] as $optVal)
                                            <label class="flex items-center gap-3.5 p-4 rounded-xl border cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-850/50 transition duration-150"
                                                   :class="answers['{{ $soal->id }}'] === '{{ $optVal }}' ? 'border-indigo-650 dark:border-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/10' : 'border-slate-200 dark:border-slate-800/80'">
                                                <input type="radio" name="jawaban_{{ $soal->id }}" value="{{ $optVal }}"
                                                       x-model="answers['{{ $soal->id }}']" @change="saveAnswer('{{ $soal->id }}')"
                                                       class="text-indigo-600 border-slate-350 dark:border-slate-800 focus:ring-indigo-500 shrink-0">
                                                <span class="text-xs font-bold text-slate-750 dark:text-slate-300 capitalize">{{ $optVal }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($soal->tipe === 'isian_singkat')
                                    <div class="space-y-2">
                                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Jawaban Singkat Anda:</label>
                                        <input type="text" x-model="answers['{{ $soal->id }}']" @change="saveAnswer('{{ $soal->id }}')"
                                               class="w-full px-4 py-3 rounded-xl border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200"
                                               placeholder="Ketikkan jawaban Anda disini...">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Left panel actions -->
                    <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800/50 flex items-center justify-between gap-4">
                        <button type="button" @click="if (activeQuestionIndex > 0) activeQuestionIndex--"
                                :disabled="activeQuestionIndex === 0"
                                class="px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200">
                            Sebelumnya
                        </button>

                        <button type="button" @click="if (activeQuestionIndex < questionsCount - 1) activeQuestionIndex++"
                                x-show="activeQuestionIndex < questionsCount - 1"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition duration-200">
                            Berikutnya
                        </button>

                        <button type="button" x-show="activeQuestionIndex === questionsCount - 1"
                                @click="showSubmitModal = true"
                                class="px-5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/10 transition duration-200">
                            Selesaikan Kuis
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Timer and Navigation Grid -->
            <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-20">
                <!-- Timer Widget -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm text-center"
                     :class="timeLeftSeconds < 300 ? 'border-red-500/30 bg-red-50/10 dark:bg-red-950/5' : ''">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sisa Waktu Pengerjaan</span>
                    <span class="text-3xl font-black font-mono tracking-tight block mt-1"
                          :class="timeLeftSeconds < 300 ? 'text-red-500' : 'text-slate-850 dark:text-slate-100'"
                          x-text="formatTime()">
                        --:--
                    </span>
                    <div class="w-full bg-slate-100 dark:bg-slate-950 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="h-full transition-all duration-1000"
                             :class="timeLeftSeconds < 300 ? 'bg-red-500' : 'bg-indigo-600'"
                             :style="`width: ${(timeLeftSeconds / ({{ $kuis->durasi_menit }} * 60)) * 100}%`"></div>
                    </div>
                </div>

                <!-- Navigator Grid Card -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm">
                    <h3 class="font-bold text-slate-850 dark:text-slate-100 text-xs uppercase tracking-wider mb-4">Navigasi Soal</h3>

                    <div class="grid grid-cols-5 gap-2">
                        @foreach($kuis->soal as $i => $soal)
                            <button type="button" @click="activeQuestionIndex = {{ $i }}"
                                    class="w-10 h-10 text-xs font-bold rounded-xl transition duration-150 focus:outline-none"
                                    :class="[
                                        activeQuestionIndex === {{ $i }} ? 'ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-slate-900' : '',
                                        answers['{{ $soal->id }}'] !== '' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-750'
                                    ]">
                                {{ $i + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-150 dark:border-slate-850 flex items-center justify-between text-[10px] text-slate-400 font-semibold">
                        <div class="flex items-center gap-1">
                            <span class="w-3.5 h-3.5 bg-indigo-600 rounded-md block"></span>
                            <span>Sudah Diisi</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-3.5 h-3.5 bg-slate-100 dark:bg-slate-800 rounded-md block border border-slate-200 dark:border-slate-700"></span>
                            <span>Belum Diisi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden submit form -->
        <form id="submit-kuis-form" action="{{ route('kuis.submit', [$kelas->id, $kuis->id]) }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="attempt" value="{{ $attempt->attempt }}">
        </form>

        {{-- ===================== MODAL: Konfirmasi Submit ===================== --}}
        <div x-show="showSubmitModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display:none">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showSubmitModal = false"></div>

            {{-- Modal Box --}}
            <div x-show="showSubmitModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200/80 dark:border-slate-700/50 w-full max-w-sm p-6 text-center">

                {{-- Icon --}}
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center mx-auto mb-4 ring-4 ring-emerald-100 dark:ring-emerald-900/30">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-1">Kumpulkan Kuis?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">
                    Semua jawaban telah tersimpan secara otomatis.
                </p>
                <p class="text-xs text-slate-400 mb-5">
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400" x-text="Object.values(answers).filter(v => v !== '').length"></span>
                    dari <span class="font-semibold">{{ $kuis->soal->count() }}</span> soal terjawab.
                </p>

                <div class="flex gap-3">
                    <button type="button"
                            @click="showSubmitModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-200">
                        Kembali
                    </button>
                    <button type="button"
                            @click="submitSekarang()"
                            :disabled="isSubmitting"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-70 disabled:cursor-wait text-white text-xs font-bold shadow-sm shadow-emerald-500/20 transition duration-200 flex items-center justify-center gap-1.5">
                        <svg x-show="isSubmitting" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Kumpulkan'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===================== MODAL: Waktu Habis ===================== --}}
        <div x-show="showTimeupModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display:none"
             x-init="$watch('showTimeupModal', val => {
                 if (val) {
                     let secs = 3;
                     const el = document.getElementById('timeup-countdown');
                     if (el) el.textContent = secs;
                     const t = setInterval(() => {
                         secs--;
                         if (el) el.textContent = secs;
                         if (secs <= 0) {
                             clearInterval(t);
                             try { localStorage.removeItem(storageKey); } catch(e) {}
                             document.getElementById('submit-kuis-form').submit();
                         }
                     }, 1000);
                 }
             })">

            <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>

            <div x-show="showTimeupModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-red-200/60 dark:border-red-900/40 w-full max-w-sm p-6 text-center">

                {{-- Icon --}}
                <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center mx-auto mb-4 ring-4 ring-red-100 dark:ring-red-900/30">
                    <svg class="w-8 h-8 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h3 class="text-base font-bold text-red-600 dark:text-red-400 mb-1">Waktu Habis!</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Kuis akan dikumpulkan otomatis dalam
                    <span id="timeup-countdown" class="font-black text-red-500 text-sm">3</span>
                    detik...
                </p>

                <div class="w-full bg-red-100 dark:bg-red-950/30 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full bg-red-500 rounded-full animate-[shrink_3s_linear_forwards]"
                         style="animation: shrinkbar 3s linear forwards; width: 100%"></div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<style>
@keyframes shrinkbar {
    from { width: 100%; }
    to   { width: 0%; }
}
</style>
