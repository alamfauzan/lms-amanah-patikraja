<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 min-w-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <a href="{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $kuis->mata_pelajaran_id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="min-w-0">
                    <h2 class="font-bold text-sm sm:text-base md:text-lg text-slate-800 dark:text-slate-100 truncate max-w-[150px] xs:max-w-[200px] sm:max-w-md md:max-w-xl" title="{{ $kuis->judul }}">{{ $kuis->judul }}</h2>
                </div>
            </div>

            @if(auth()->user()->hasAnyRole(['admin','guru']))
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('kelas.kuis.edit', [$kelas->id, $kuis->id]) }}"
                       class="px-3.5 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-slate-600 dark:text-slate-350 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition duration-200">
                        Edit
                    </a>
                    <form action="{{ route('kelas.kuis.destroy', [$kelas->id, $kuis->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/40 text-red-650 dark:text-red-400 text-xs font-semibold rounded-xl border border-red-200/40 dark:border-red-900/40 transition duration-200">
                            Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-455 rounded-xl text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-455 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Section 1: Header Card --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400">
                    {{ $kuis->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $kuis->is_aktif ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }}">
                    {{ $kuis->is_aktif ? 'Aktif' : 'Draft' }}
                </span>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                {{ $kuis->judul }}
            </h1>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                {{-- Durasi --}}
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Durasi</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $kuis->durasi_menit }} Menit</p>
                    </div>
                </div>
                {{-- Soal --}}
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Soal</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $kuis->jumlah_soal }} Butir</p>
                    </div>
                </div>
                {{-- Percobaan --}}
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Percobaan</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $kuis->batas_pengerjaan }}x</p>
                    </div>
                </div>
                {{-- Bobot Nilai --}}
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Bobot Nilai</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $kuis->bobot_nilai }}%</p>
                    </div>
                </div>
                {{-- Dibuka --}}
                @if($kuis->mulai_at)
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Dibuka</p>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $kuis->mulai_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
                {{-- Ditutup --}}
                @if($kuis->selesai_at)
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg {{ now()->gt($kuis->selesai_at) ? 'bg-red-50 dark:bg-red-950/30 text-red-500' : 'bg-slate-50 dark:bg-slate-800 text-slate-400' }} shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Ditutup</p>
                            <p class="text-xs font-semibold {{ now()->gt($kuis->selesai_at) ? 'text-red-500' : 'text-slate-700 dark:text-slate-200' }}">{{ $kuis->selesai_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>


        {{-- Section 2: Instruksi Kuis --}}
        @if($kuis->deskripsi)
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">Instruksi Kuis</h2>
                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-350 leading-relaxed text-sm">
                    {!! nl2br(e($kuis->deskripsi)) !!}
                </div>
            </div>
        @endif

        {{-- Section 3: For Student --}}
        @if(auth()->user()->hasRole('siswa'))
            @php
                $attemptsCount = \App\Models\HasilKuis::where(['kuis_id' => $kuis->id, 'siswa_id' => auth()->id()])->count();
                $canAttempt = $attemptsCount < $kuis->batas_pengerjaan;
                $isOpen = $kuis->is_aktif
                    && (!$kuis->mulai_at || now()->gte($kuis->mulai_at))
                    && (!$kuis->selesai_at || now()->lte($kuis->selesai_at));
            @endphp

            {{-- Sedang Berlangsung --}}
            @if($hasilSiswa && !$hasilSiswa->is_submitted)
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">Status Pengerjaan</h2>
                    <div class="p-4 rounded-xl bg-amber-50/60 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 flex items-center justify-between gap-4 flex-wrap">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Percobaan #{{ $hasilSiswa->attempt }} — Sedang Berlangsung</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Anda memiliki sesi kuis yang belum diselesaikan.</p>
                        </div>
                        <a href="{{ route('kuis.kerjakan', [$kelas->id, $kuis->id]) }}"
                           class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200 shrink-0">
                            Lanjutkan Kuis
                        </a>
                    </div>
                </div>

            {{-- Sudah Submit — tampilkan hasil --}}
            @elseif($hasilSiswa && $hasilSiswa->is_submitted)
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hasil Kuis Anda</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Selesai Dinilai
                        </span>
                    </div>
                    {{-- Score --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl flex flex-col items-center justify-center text-center sm:w-36 shrink-0">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nilai Akhir</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $hasilSiswa->nilai_akhir }}</span>
                                <span class="text-sm text-slate-400 font-semibold">/ 100</span>
                            </div>
                        </div>

                        {{-- Info rows --}}
                        <div class="flex-1 p-5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-2.5">
                            <div class="flex items-center justify-between text-xs gap-4">
                                <span class="text-slate-500 font-medium">Percobaan ke</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-200">#{{ $hasilSiswa->attempt }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs gap-4">
                                <span class="text-slate-500 font-medium">Selesai dikerjakan</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-200 whitespace-nowrap">{{ $hasilSiswa->selesai_at?->format('d M Y, H:i') ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs gap-4">
                                <span class="text-slate-500 font-medium">Total percobaan</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $attemptsCount }} / {{ $kuis->batas_pengerjaan }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex items-center justify-end gap-3 flex-wrap pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('kuis.hasil', [$kelas->id, $kuis->id]) }}"
                           class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition duration-200">
                            Lihat Review Jawaban
                        </a>
                        @if($canAttempt && $isOpen)
                            <a href="{{ route('kuis.kerjakan', [$kelas->id, $kuis->id]) }}"
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition duration-200">
                                Mulai Percobaan Baru
                            </a>
                        @endif
                    </div>
                </div>


            {{-- Belum Mulai --}}
            @else
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">Mulai Kuis</h2>
                    <div class="space-y-3">
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl grid grid-cols-2 gap-3 text-xs">
                            <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                <span class="text-slate-500 font-medium">Batas percobaan</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $kuis->batas_pengerjaan }} kali</span>
                            </div>
                            <div class="flex items-center justify-between col-span-2 sm:col-span-1">
                                <span class="text-slate-500 font-medium">Sudah dilakukan</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $attemptsCount }} kali</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-end pt-2 border-t border-slate-100 dark:border-slate-800">
                            @if(!$isOpen)
                                <span class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                    Kuis Belum Dibuka / Sudah Ditutup
                                </span>
                            @elseif($canAttempt)
                                <a href="{{ route('kuis.kerjakan', [$kelas->id, $kuis->id]) }}"
                                   class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200">
                                    Mulai Kuis
                                </a>
                            @else
                                <span class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                    Batas Percobaan Habis
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Section 4: For Teacher / Admin --}}
        @if(auth()->user()->hasAnyRole(['admin','guru']))
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">Hasil Pengerjaan Siswa</h3>
                        <p class="text-xs text-slate-400 mt-1">Daftar percobaan kuis oleh siswa kelas ini.</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-900/50">
                        {{ $semuaHasil->where('is_submitted', true)->count() }} Selesai
                    </span>
                </div>

                @if($semuaHasil->isEmpty())
                    <div class="p-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada siswa yang mengerjakan kuis ini.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 dark:bg-slate-905 border-b border-slate-100 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="px-6 py-3.5">Nama Siswa</th>
                                    <th class="px-6 py-3.5 text-center">Percobaan Ke</th>
                                    <th class="px-6 py-3.5">Mulai Pada</th>
                                    <th class="px-6 py-3.5">Selesai Pada</th>
                                    <th class="px-6 py-3.5 text-right">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-xs">
                                @foreach($semuaHasil as $hasil)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/20 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[10px] shrink-0">
                                                    {{ strtoupper(substr($hasil->siswa->name, 0, 1)) }}
                                                </div>
                                                <span class="font-semibold text-slate-750 dark:text-slate-200">{{ $hasil->siswa->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500">#{{ $hasil->attempt }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-400">{{ $hasil->mulai_at ? $hasil->mulai_at->format('d M Y, H:i') : '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if($hasil->is_submitted)
                                                <span class="text-slate-400">{{ $hasil->selesai_at ? $hasil->selesai_at->format('d M Y, H:i') : '-' }}</span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-600 uppercase">Berlangsung</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($hasil->is_submitted)
                                                <span class="font-bold text-sm text-indigo-600 dark:text-indigo-400">{{ $hasil->nilai_akhir }}</span>
                                            @else
                                                <span class="text-slate-400 font-semibold italic text-[11px]">Dalam proses</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
