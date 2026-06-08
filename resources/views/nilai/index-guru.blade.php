<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Rekap Nilai</h2>
    </x-slot>

    <div class="space-y-6 animate-fade-in">

        @if($kelasList->isEmpty())
            <div class="py-20 text-center text-slate-400 dark:text-slate-500 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl">
                <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
                <p class="text-sm font-medium">Tidak ada kelas yang diampu.</p>
            </div>
        @else
            {{-- Intro text --}}
            <p class="text-sm text-slate-500 dark:text-slate-400">Pilih kelas untuk melihat dan mengekspor rekap nilai siswa.</p>

            {{-- Kelas Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kelasList as $kelas)
                    <div class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-700/50 transition-all duration-300">
                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $kelas->nama_kelas }}
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $kelas->siswa_count }} Siswa
                                        </span>
                                        @if($kelas->tahun_ajaran)
                                            <span class="inline-flex items-center text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                                                &bull; TA {{ $kelas->tahun_ajaran }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex border-t border-slate-100 dark:border-slate-800/80">
                            <a href="{{ route('nilai.rekap', $kelas->id) }}"
                               class="flex-1 py-3 text-center text-xs font-semibold text-slate-500 hover:text-indigo-600 hover:bg-indigo-500/5 dark:text-slate-400 dark:hover:text-indigo-400 transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Lihat Rekap
                            </a>
                            <div class="w-px bg-slate-100 dark:bg-slate-800/80"></div>
                            <a href="{{ route('nilai.export-csv', $kelas->id) }}"
                               class="flex-1 py-3 text-center text-xs font-semibold text-slate-500 hover:text-emerald-600 hover:bg-emerald-500/5 dark:text-slate-400 dark:hover:text-emerald-400 transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export CSV
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
