<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="font-bold text-lg text-slate-850 dark:text-slate-100">{{ $kuis->judul }}</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">Percobaan ke-{{ $attempt->attempt }} • {{ $kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <!-- Quiz Engine Workspace Container -->
    <div class="max-w-6xl mx-auto"
         x-data="{
             activeQuestionIndex: 0,
             questionsCount: {{ $kuis->soal->count() }},
             timeLeftSeconds: {{ max(0, $kuis->durasi_menit * 60 - now()->diffInSeconds($attempt->mulai_at)) }},
             answers: {
                 @foreach($kuis->soal as $soal)
                     @php
                         $answ = \App\Models\JawabanSiswa::where(['soal_id' => $soal->id, 'siswa_id' => auth()->id(), 'attempt' => $attempt->attempt])->first();
                     @endphp
                     '{{ $soal->id }}': '{{ $answ ? e($answ->jawaban) : '' }}',
                 @endforeach
             },
             saveAnswer(soalId) {
                 let jawaban = this.answers[soalId];
                 fetch('{{ route('kuis.jawab', [$kelas->id, $kuis->id]) }}', {
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
                 .then(response => response.json())
                 .then(data => {
                     console.log('Saved answer for question ID ' + soalId + ':', data);
                 })
                 .catch(error => console.error('Error saving answer:', error));
             },
             formatTime() {
                 let m = Math.floor(this.timeLeftSeconds / 60);
                 let s = this.timeLeftSeconds % 60;
                 return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
             },
             init() {
                 let interval = setInterval(() => {
                     if (this.timeLeftSeconds <= 0) {
                         clearInterval(interval);
                         alert('Waktu Anda telah habis! Kuis akan dikumpulkan secara otomatis.');
                         document.getElementById('submit-kuis-form').submit();
                     } else {
                         this.timeLeftSeconds--;
                     }
                 }, 1000);
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
                                class="px-5 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition duration-200">
                            Berikutnya
                        </button>

                        <button type="button" x-show="activeQuestionIndex === questionsCount - 1"
                                @click="if(confirm('Apakah Anda yakin ingin menyelesaikan kuis ini? Semua jawaban Anda telah disimpan secara otomatis.')) { document.getElementById('submit-kuis-form').submit(); }"
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
                                        answers['{{ $soal->id }}'] !== '' ? 'bg-indigo-650 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-750'
                                    ]">
                                {{ $i + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-150 dark:border-slate-850 flex items-center justify-between text-[10px] text-slate-400 font-semibold">
                        <div class="flex items-center gap-1">
                            <span class="w-3.5 h-3.5 bg-indigo-650 rounded-md block"></span>
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
    </div>
</x-app-layout>
