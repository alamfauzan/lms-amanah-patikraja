<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Nilai Saya</h2>
    </x-slot>

    <div class="space-y-8 animate-fade-in">
        <!-- Page Header -->
        <div class="flex flex-col gap-1.5">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Nilai Saya</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Rekap nilai tugas dan kuis dari semua kelas yang kamu ikuti.</p>
        </div>

        @forelse($rekapKelas as $item)
            @php
                $kelas    = $item['kelas'];
                $tugasList = $item['tugas'];
                $kuisList  = $item['kuis'];
                $rataRata  = $item['rata_rata'];
            @endphp

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <!-- Kelas Header -->
                <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $kelas->nama_kelas }}</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $tugasList->count() }} Tugas &bull; {{ $kuisList->count() }} Kuis
                            </p>
                        </div>
                    </div>
                    @if(!is_null($rataRata))
                        <div class="text-right">
                            <span class="text-xs text-slate-400 dark:text-slate-500 uppercase tracking-wider">Rata-rata</span>
                            <div class="text-2xl font-extrabold
                                {{ $rataRata >= 80 ? 'text-emerald-600 dark:text-emerald-400' :
                                   ($rataRata >= 60 ? 'text-amber-600 dark:text-amber-400' :
                                   'text-red-600 dark:text-red-400') }}">
                                {{ $rataRata }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    @if($tugasList->count() > 0)
                        <!-- Tugas Section -->
                        <div class="mb-6">
                            <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Tugas
                            </h4>
                            <div class="space-y-2">
                                @foreach($tugasList as $t)
                                    <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-slate-50/80 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 truncate">{{ $t['tugas']->judul }}</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                                Deadline: {{ \Carbon\Carbon::parse($t['tugas']->deadline)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            @if(!is_null($t['nilai']))
                                                <span class="inline-block px-3 py-1 rounded-lg text-sm font-extrabold
                                                    {{ $t['nilai'] >= 80 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' :
                                                       ($t['nilai'] >= 60 ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' :
                                                       'bg-red-500/10 text-red-700 dark:text-red-400') }}">
                                                    {{ $t['nilai'] }}
                                                </span>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">/ {{ $t['tugas']->nilai_maksimum }}</p>
                                            @elseif($t['status'] === 'diserahkan')
                                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                    Menunggu Penilaian
                                                </span>
                                            @elseif($t['status'] === null)
                                                @if(\Carbon\Carbon::parse($t['tugas']->deadline)->isPast())
                                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold bg-red-500/10 text-red-600 dark:text-red-400">
                                                        Terlambat
                                                    </span>
                                                @else
                                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold bg-slate-500/10 text-slate-500 dark:text-slate-400">
                                                        Belum Dikumpul
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($kuisList->count() > 0)
                        <!-- Kuis Section -->
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                </svg>
                                Kuis
                            </h4>
                            <div class="space-y-2">
                                @foreach($kuisList as $k)
                                    <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-slate-50/80 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 truncate">{{ $k['kuis']->judul }}</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                                Durasi: {{ $k['kuis']->durasi_menit }} menit &bull; Bobot: {{ $k['kuis']->bobot_nilai }}
                                            </p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            @if(!is_null($k['nilai']))
                                                <span class="inline-block px-3 py-1 rounded-lg text-sm font-extrabold
                                                    {{ $k['nilai'] >= 80 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' :
                                                       ($k['nilai'] >= 60 ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' :
                                                       'bg-red-500/10 text-red-700 dark:text-red-400') }}">
                                                    {{ $k['nilai'] }}
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold bg-slate-500/10 text-slate-500 dark:text-slate-400">
                                                    Belum Dikerjakan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($tugasList->count() === 0 && $kuisList->count() === 0)
                        <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-6">
                            Belum ada tugas atau kuis di kelas ini.
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-20 text-center text-slate-400 dark:text-slate-500">
                <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
                <p class="text-sm font-medium">Kamu belum terdaftar di kelas manapun.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
