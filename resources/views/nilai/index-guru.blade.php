<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Rekap Nilai</h2>
    </x-slot>

    <div class="space-y-8 animate-fade-in">
        <!-- Page Header -->
        <div class="flex flex-col gap-1.5">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Rekap Nilai</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pilih kelas untuk melihat dan mengekspor rekap nilai siswa.</p>
        </div>

        <!-- Kelas Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kelasList as $kelas)
                <div class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $kelas->nama_kelas }}
                                </h3>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                    {{ $kelas->siswa_count }} Siswa
                                    @if($kelas->tahun_ajaran)
                                        &bull; TA {{ $kelas->tahun_ajaran }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex border-t border-slate-150 dark:border-slate-800">
                        <a href="{{ route('nilai.rekap', $kelas->id) }}"
                           class="flex-1 py-3 text-center text-xs font-semibold text-slate-500 hover:text-indigo-600 hover:bg-indigo-500/5 dark:text-slate-400 dark:hover:text-indigo-400 transition-all">
                            Lihat Rekap
                        </a>
                        <div class="w-px bg-slate-150 dark:bg-slate-800"></div>
                        <a href="{{ route('nilai.export-csv', $kelas->id) }}"
                           class="flex-1 py-3 text-center text-xs font-semibold text-slate-500 hover:text-emerald-600 hover:bg-emerald-500/5 dark:text-slate-400 dark:hover:text-emerald-400 transition-all">
                            Export CSV
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-slate-400 dark:text-slate-500">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                    <p class="text-sm font-medium">Tidak ada kelas yang diampu</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
