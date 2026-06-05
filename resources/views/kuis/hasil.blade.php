<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kuis.show', [$kelas->id, $kuis->id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-850 dark:text-slate-100">Review Hasil Kuis</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">{{ $kuis->judul }} • {{ $kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(!$hasil)
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 text-center shadow-sm">
                <p class="text-sm text-slate-400">Tidak ada riwayat pengerjaan yang ditemukan.</p>
            </div>
        @else
            <!-- Summary Scorecard Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <p class="text-xs text-indigo-555 dark:text-indigo-400 font-bold uppercase tracking-wider">Hasil Evaluasi Kuis</p>
                    <h3 class="font-bold text-slate-850 dark:text-slate-100 text-lg sm:text-xl">Percobaan #{{ $hasil->attempt }}</h3>
                    <p class="text-xs text-slate-400">Dikerjakan pada {{ $hasil->selesai_at ? $hasil->selesai_at->format('d M Y, H:i') : '-' }}</p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-3 text-xs font-semibold text-slate-500">
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500 block"></span> {{ $hasil->benar }} Benar</span>
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500 block"></span> {{ $hasil->salah }} Salah</span>
                    </div>
                </div>

                <div class="w-32 h-32 bg-indigo-50/50 dark:bg-indigo-950/10 border-2 border-indigo-100/50 dark:border-indigo-950/20 rounded-full flex flex-col items-center justify-center shrink-0">
                    <span class="text-3xl font-black text-indigo-650 dark:text-indigo-400 leading-none">{{ $hasil->nilai_akhir }}</span>
                    <span class="text-[10px] text-slate-400 uppercase font-bold mt-1 tracking-wider">Nilai Akhir</span>
                </div>
            </div>

            <!-- Detailed Questions Review -->
            <div class="space-y-4">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-base">Detail Jawaban Anda:</h3>

                @foreach($kuis->soal as $i => $soal)
                    @php
                        $jaw = $jawaban->get($soal->id);
                        $isBenar = $jaw ? $jaw->is_benar : false;
                        $poinSiswa = $jaw ? $jaw->poin_diperoleh : 0;
                    @endphp
                    <div class="bg-white dark:bg-slate-900 border rounded-2xl p-6 shadow-sm space-y-4
                        {{ $isBenar ? 'border-emerald-500/20 dark:border-emerald-500/10' : 'border-red-500/20 dark:border-red-500/10' }}">

                        <!-- Question header -->
                        <div class="flex items-center justify-between gap-4">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                                {{ $isBenar ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-550' }}">
                                {{ $i + 1 }}
                            </span>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider
                                    {{ $isBenar ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-550' }}">
                                    {{ $isBenar ? 'Benar' : 'Salah / Kosong' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">Poin: {{ $poinSiswa }} / {{ $soal->poin }}</span>
                            </div>
                        </div>

                        <!-- Question Text -->
                        <div class="text-slate-800 dark:text-slate-200 font-semibold text-sm leading-relaxed">
                            {!! nl2br(e($soal->pertanyaan)) !!}
                        </div>

                        <!-- Options Display -->
                        @if($soal->tipe === 'pilihan_ganda')
                            <div class="grid grid-cols-1 gap-2.5 pl-4 border-l-2 border-slate-100 dark:border-slate-800">
                                @foreach(['a', 'b', 'c', 'd'] as $optKey)
                                    @if(isset($soal->pilihan_jawaban[$optKey]) && $soal->pilihan_jawaban[$optKey] !== '')
                                        @php
                                            $isCorrectOption = strtolower(trim($soal->kunci_jawaban)) === $optKey;
                                            $isSiswaOption = $jaw && strtolower(trim($jaw->jawaban)) === $optKey;
                                        @endphp
                                        <div class="flex items-center gap-3 p-3 rounded-xl border text-xs leading-snug
                                            {{ $isCorrectOption ? 'border-emerald-500 bg-emerald-500/5 text-emerald-700 dark:text-emerald-400' : ($isSiswaOption ? 'border-red-500 bg-red-500/5 text-red-700 dark:text-red-400' : 'border-slate-150 dark:border-slate-850 text-slate-600 dark:text-slate-450') }}">

                                            <div class="shrink-0 flex items-center justify-center w-4 h-4 rounded-full border
                                                {{ $isCorrectOption ? 'border-emerald-500 bg-emerald-500 text-white' : ($isSiswaOption ? 'border-red-500 bg-red-500 text-white' : 'border-slate-300 dark:border-slate-700') }}">
                                                @if($isCorrectOption)
                                                    ✓
                                                @elseif($isSiswaOption)
                                                    ✗
                                                @endif
                                            </div>

                                            <span class="font-bold uppercase">{{ $optKey }}.</span>
                                            <span class="font-medium">{{ $soal->pilihan_jawaban[$optKey] }}</span>

                                            @if($isCorrectOption)
                                                <span class="text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 px-1.5 py-0.5 rounded ml-auto">Kunci</span>
                                            @endif
                                            @if($isSiswaOption && !$isCorrectOption)
                                                <span class="text-[9px] font-bold uppercase tracking-wider bg-red-500/10 text-red-500 px-1.5 py-0.5 rounded ml-auto">Pilihan Anda</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        @elseif($soal->tipe === 'benar_salah')
                            <div class="grid grid-cols-2 gap-3 pl-4 border-l-2 border-slate-100 dark:border-slate-800">
                                @foreach(['benar', 'salah'] as $optVal)
                                    @php
                                        $isCorrectOption = strtolower(trim($soal->kunci_jawaban)) === $optVal;
                                        $isSiswaOption = $jaw && strtolower(trim($jaw->jawaban)) === $optVal;
                                    @endphp
                                    <div class="flex items-center gap-3 p-3 rounded-xl border text-xs leading-snug
                                        {{ $isCorrectOption ? 'border-emerald-500 bg-emerald-500/5 text-emerald-700 dark:text-emerald-400' : ($isSiswaOption ? 'border-red-500 bg-red-500/5 text-red-700 dark:text-red-400' : 'border-slate-150 dark:border-slate-850 text-slate-650 dark:text-slate-450') }}">

                                        <div class="shrink-0 flex items-center justify-center w-4 h-4 rounded-full border
                                            {{ $isCorrectOption ? 'border-emerald-500 bg-emerald-500 text-white' : ($isSiswaOption ? 'border-red-500 bg-red-500 text-white' : 'border-slate-300 dark:border-slate-700') }}">
                                            @if($isCorrectOption)
                                                ✓
                                            @elseif($isSiswaOption)
                                                ✗
                                            @endif
                                        </div>

                                        <span class="font-bold capitalize">{{ $optVal }}</span>

                                        @if($isCorrectOption)
                                            <span class="text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 px-1.5 py-0.5 rounded ml-auto">Kunci</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        @elseif($soal->tipe === 'isian_singkat')
                            <div class="pl-4 border-l-2 border-slate-100 dark:border-slate-800 space-y-2 text-xs">
                                <div class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-150 dark:border-slate-850 rounded-xl space-y-1">
                                    <p class="text-slate-500 font-semibold">Jawaban Anda:</p>
                                    <p class="font-bold {{ $isBenar ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">
                                        {{ $jaw ? $jaw->jawaban : '(Tidak diisi)' }}
                                    </p>
                                </div>
                                <div class="p-3 bg-emerald-500/5 border border-emerald-500/10 rounded-xl space-y-1">
                                    <p class="text-slate-500 font-semibold">Kunci Jawaban:</p>
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400 capitalize">
                                        {{ $soal->kunci_jawaban }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
