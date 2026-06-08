<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Rekap Nilai</h2>
    </x-slot>

    <div class="space-y-6 animate-fade-in">

        @forelse($rekapKelas as $item)
            @php
                $kelas     = $item['kelas'];
                $mapelList = $item['mapel_list'];
            @endphp

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <!-- Kelas Header -->
                <div class="px-6 py-5 bg-slate-50/50 dark:bg-slate-950/20 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 dark:text-slate-100">{{ $kelas->nama_kelas }}</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            {{ $mapelList->count() }} Mata Pelajaran Terdaftar
                        </p>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    @foreach($mapelList as $m)
                        <details class="group border border-slate-200 dark:border-slate-800/80 rounded-2xl overflow-hidden bg-slate-50/25 dark:bg-slate-900/30" @if($loop->first) open @endif>
                            <summary class="list-none cursor-pointer px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4 flex-wrap bg-slate-50/50 dark:bg-slate-900/50 [&::-webkit-details-marker]:hidden">
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-800 dark:text-slate-200">{{ $m['mata_pelajaran']->nama_mapel }}</h4>
                                    @if($m['guru'])
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Pengampu: {{ $m['guru']->name }}</p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 ml-auto">
                                    @if(!is_null($m['nilai_akhir'] ?? null))
                                        <div class="text-right">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-wider font-bold">Nilai Akhir:</span>
                                            <div class="text-xl font-black {{ ($m['nilai_akhir'] ?? 0) >= 80 ? 'text-emerald-600 dark:text-emerald-400' : (($m['nilai_akhir'] ?? 0) >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                                                {{ isset($m['nilai_akhir']) ? (int) round($m['nilai_akhir']) : '-' }}
                                            </div>
                                        </div>
                                    @elseif(!is_null($m['rata_rata'] ?? null))
                                        <div class="text-right">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-wider font-bold">Rata-rata Mapel</span>
                                            <div class="text-xl font-black {{ ($m['rata_rata'] ?? 0) >= 80 ? 'text-emerald-600 dark:text-emerald-400' : (($m['rata_rata'] ?? 0) >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                                                {{ $m['rata_rata'] ?? '-' }}
                                            </div>
                                        </div>
                                    @endif
                                    <span class="hidden sm:inline-flex text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Lihat detail</span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </summary>

                            <div class="p-4 space-y-3">
                                @if($m['tugas']->isEmpty() && $m['kuis']->isEmpty())
                                    <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-2">Belum ada tugas atau kuis untuk mata pelajaran ini.</p>
                                @else
                                    @foreach($m['tugas'] as $t)
                                        <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-xs">
                                            <div class="min-w-0 flex-1 flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-450 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate">{{ $t['tugas']->judul }}</p>
                                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Tugas</p>
                                                </div>
                                            </div>

                                            <div class="shrink-0 text-right">
                                                @if(!is_null($t['nilai']))
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-extrabold {{ $t['nilai'] >= 80 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : ($t['nilai'] >= 60 ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'bg-red-500/10 text-red-700 dark:text-red-400') }}">
                                                        {{ rtrim(rtrim(number_format((float) $t['nilai'], 2, '.', ''), '0'), '.') }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">/{{ $t['tugas']->nilai_maksimum }}</span>
                                                @elseif($t['status'] === 'terkumpul' || $t['status'] === 'diserahkan')
                                                    <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400">Menunggu Penilaian</span>
                                                @else
                                                    @if(
                                                        \Carbon\Carbon::parse($t['tugas']->deadline)->isPast()
                                                    )
                                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-500/10 text-red-600 dark:text-red-400">Terlambat</span>
                                                    @else
                                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-500/10 text-slate-500 dark:text-slate-400">Belum Dikumpul</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach($m['kuis'] as $k)
                                        <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-xs">
                                            <div class="min-w-0 flex-1 flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate">{{ $k['kuis']->judul }}</p>
                                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Kuis</p>
                                                </div>
                                            </div>

                                            <div class="shrink-0 text-right">
                                                @if(!is_null($k['nilai']))
                                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-extrabold {{ $k['nilai'] >= 80 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : ($k['nilai'] >= 60 ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'bg-red-500/10 text-red-700 dark:text-red-400') }}">{{ rtrim(rtrim(number_format((float) $k['nilai'], 2, '.', ''), '0'), '.') }}</span>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">/{{ $k['kuis']->bobot_nilai }}</span>
                                                @else
                                                    <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-500/10 text-slate-500 dark:text-slate-400">Belum Dikerjakan</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-20 text-center text-slate-400 dark:text-slate-500 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl">
                <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
                <p class="text-sm font-medium">Kamu belum terdaftar di kelas manapun.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
