<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kuis.show', [$kelas->id, $kuis->id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-900 dark:text-slate-100">Review Hasil Kuis</h2>
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
            {{-- Summary Scorecard --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center sm:text-left">
                    <p class="text-[10px] text-indigo-500 dark:text-indigo-400 font-bold uppercase tracking-widest">Hasil Evaluasi Kuis</p>
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xl">Percobaan #{{ $hasil->attempt }}</h3>
                    <p class="text-xs text-slate-400">Dikerjakan pada {{ $hasil->selesai_at ? $hasil->selesai_at->format('d M Y, H:i') : '-' }}</p>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 pt-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                            {{ $hasil->benar }} Benar
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 shrink-0"></span>
                            {{ $hasil->salah }} Salah
                        </span>
                    </div>
                </div>

                <div class="w-28 h-28 bg-indigo-50 dark:bg-indigo-950/20 border-2 border-indigo-100 dark:border-indigo-900/30 rounded-full flex flex-col items-center justify-center shrink-0">
                    <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ $hasil->nilai_akhir }}</span>
                    <span class="text-[9px] text-slate-400 uppercase font-bold mt-1 tracking-widest">Nilai Akhir</span>
                </div>
            </div>

            {{-- Detailed Questions Review --}}
            <div class="space-y-4">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm uppercase tracking-wider">Detail Jawaban Anda</h3>

                @foreach($kuis->soal as $i => $soal)
                    @php
                        $jaw = $jawaban->get($soal->id);
                        $isBenar = $jaw ? $jaw->is_benar : false;
                        $poinSiswa = $jaw ? $jaw->poin_diperoleh : 0;
                    @endphp

                    <div class="bg-white dark:bg-slate-900 border rounded-2xl overflow-hidden shadow-sm
                        {{ $isBenar ? 'border-emerald-200 dark:border-emerald-900/40' : 'border-red-200 dark:border-red-900/40' }}">

                        {{-- Question Header --}}
                        <div class="flex items-center justify-between gap-4 px-6 py-4
                            {{ $isBenar ? 'bg-emerald-50/60 dark:bg-emerald-950/10 border-b border-emerald-100 dark:border-emerald-900/30' : 'bg-red-50/60 dark:bg-red-950/10 border-b border-red-100 dark:border-red-900/30' }}">

                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                                    {{ $isBenar ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                                    {{ $i + 1 }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $isBenar ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                                    {{ $isBenar ? 'Benar' : 'Salah / Kosong' }}
                                </span>
                            </div>

                            <span class="text-xs text-slate-400 font-semibold whitespace-nowrap">
                                Poin: <span class="{{ $isBenar ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }} font-bold">{{ $poinSiswa }}</span> / {{ $soal->poin }}
                            </span>
                        </div>

                        {{-- Question Body --}}
                        <div class="px-6 py-5 space-y-4">
                            {{-- Question Text --}}
                            <div class="text-slate-800 dark:text-slate-200 font-semibold text-sm leading-relaxed">
                                {!! nl2br(e($soal->pertanyaan)) !!}
                            </div>

                            {{-- Options Display --}}
                            @if($soal->tipe === 'pilihan_ganda')
                                <div class="grid grid-cols-1 gap-2">
                                    @foreach(['a', 'b', 'c', 'd'] as $optKey)
                                        @if(isset($soal->pilihan_jawaban[$optKey]) && $soal->pilihan_jawaban[$optKey] !== '')
                                            @php
                                                $isCorrectOption = strtolower(trim($soal->kunci_jawaban)) === $optKey;
                                                $isSiswaOption = $jaw && strtolower(trim($jaw->jawaban)) === $optKey;
                                            @endphp
                                            <div class="flex items-center gap-3 p-3 rounded-xl border text-xs
                                                {{ $isCorrectOption ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400'
                                                    : ($isSiswaOption ? 'border-red-400 bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400'
                                                    : 'border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400') }}">

                                                <div class="shrink-0 w-5 h-5 rounded-full border flex items-center justify-center text-[10px] font-bold
                                                    {{ $isCorrectOption ? 'border-emerald-500 bg-emerald-500 text-white'
                                                        : ($isSiswaOption ? 'border-red-500 bg-red-500 text-white'
                                                        : 'border-slate-300 dark:border-slate-600 text-slate-400') }}">
                                                    @if($isCorrectOption) ✓
                                                    @elseif($isSiswaOption) ✗
                                                    @else {{ strtoupper($optKey) }}
                                                    @endif
                                                </div>

                                                <span class="font-medium flex-1">{{ $soal->pilihan_jawaban[$optKey] }}</span>

                                                @if($isCorrectOption)
                                                    <span class="text-[9px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-full shrink-0">Kunci</span>
                                                @endif
                                                @if($isSiswaOption && !$isCorrectOption)
                                                    <span class="text-[9px] font-bold uppercase tracking-wider bg-red-100 dark:bg-red-900/30 text-red-600 px-2 py-0.5 rounded-full shrink-0">Pilihan Anda</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            @elseif($soal->tipe === 'benar_salah')
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach(['benar', 'salah'] as $optVal)
                                        @php
                                            $isCorrectOption = strtolower(trim($soal->kunci_jawaban)) === $optVal;
                                            $isSiswaOption = $jaw && strtolower(trim($jaw->jawaban)) === $optVal;
                                        @endphp
                                        <div class="flex items-center gap-3 p-3 rounded-xl border text-xs
                                            {{ $isCorrectOption ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400'
                                                : ($isSiswaOption ? 'border-red-400 bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400'
                                                : 'border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400') }}">

                                            <div class="shrink-0 w-5 h-5 rounded-full border flex items-center justify-center text-[10px] font-bold
                                                {{ $isCorrectOption ? 'border-emerald-500 bg-emerald-500 text-white'
                                                    : ($isSiswaOption ? 'border-red-500 bg-red-500 text-white'
                                                    : 'border-slate-300 dark:border-slate-600') }}">
                                                @if($isCorrectOption) ✓
                                                @elseif($isSiswaOption) ✗
                                                @endif
                                            </div>

                                            <span class="font-semibold capitalize flex-1">{{ $optVal }}</span>

                                            @if($isCorrectOption)
                                                <span class="text-[9px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-full shrink-0">Kunci</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($soal->tipe === 'isian_singkat')
                                <div class="space-y-2">
                                    <div class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs space-y-1">
                                        <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Jawaban Anda</p>
                                        <p class="font-bold {{ $isBenar ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">
                                            {{ $jaw ? $jaw->jawaban : '(Tidak diisi)' }}
                                        </p>
                                    </div>
                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl text-xs space-y-1">
                                        <p class="text-emerald-600 dark:text-emerald-400 font-semibold uppercase tracking-wider text-[10px]">Kunci Jawaban</p>
                                        <p class="font-bold text-emerald-700 dark:text-emerald-300 capitalize">{{ $soal->kunci_jawaban }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
