<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">
            Dashboard
        </h2>
    </x-slot>

<div class="space-y-8 animate-fade-in">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-red-500/5 group-hover:bg-red-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-650 dark:text-red-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tugas Belum Selesai</span>
                    <h3 class="text-2xl font-extrabold text-slate-805 dark:text-slate-100 leading-none mt-1">{{ $jumlahTugasBelumSelesai }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/5 group-hover:bg-indigo-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas Aktif</span>
                    <h3 class="text-2xl font-extrabold text-slate-855 dark:text-slate-100 leading-none mt-1">{{ $kelasAktif }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 group-hover:bg-emerald-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Rata-rata</span>
                    <h3 class="text-2xl font-extrabold text-slate-805 dark:text-slate-100 leading-none mt-1">{{ $nilaiRataRata }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 group-hover:bg-amber-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kehadiran</span>
                    <h3 class="text-2xl font-extrabold text-slate-805 dark:text-slate-100 leading-none mt-1">{{ $kehadiran }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Daily Schedule -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800">
                <h3 class="font-bold text-slate-855 dark:text-slate-100">Jadwal</h3>
            </div>
            <div class="p-6 space-y-4 max-h-[350px] overflow-y-auto custom-scrollbar">
                @forelse($jadwalHariIni as $j)
                    @php
                        $nowTime = now()->format('H:i');
                        $isOngoing = false;
                        if ($j->jam_mulai && $j->jam_selesai) {
                            $start = \Carbon\Carbon::parse($j->jam_mulai)->format('H:i');
                            $end = \Carbon\Carbon::parse($j->jam_selesai)->format('H:i');
                            $isOngoing = ($nowTime >= $start && $nowTime <= $end);
                        }
                        
                        // Deterministic colors & icons based on mapel ID
                        $mapelId = $j->mata_pelajaran_id;
                        if ($mapelId % 4 === 0) {
                            $iconColor = 'bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6l-6 6h6M9 17h10" /></svg>';
                        } elseif ($mapelId % 4 === 1) {
                            $iconColor = 'bg-amber-500/10 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>';
                        } elseif ($mapelId % 4 === 2) {
                            $iconColor = 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>';
                        } else {
                            $iconColor = 'bg-indigo-500/10 text-indigo-650 dark:bg-indigo-500/20 dark:text-indigo-400';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>';
                        }
                    @endphp
                    <div class="flex items-center justify-between gap-4 p-4 rounded-xl border transition-all duration-300 {{ $isOngoing ? 'bg-blue-50/30 dark:bg-blue-500/5 border-blue-200 dark:border-blue-800/40 shadow-sm' : 'bg-slate-50/50 dark:bg-slate-900/50 border-slate-150 dark:border-slate-800/80' }}">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $iconColor }}">
                                {!! $iconSvg !!}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate leading-snug">
                                    {{ $j->mataPelajaran->nama_mapel }}
                                </h4>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                    {{ $j->guru->name ?? '-' }} • {{ $j->ruangan ?? 'Online' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 flex flex-col items-end">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-350 leading-none">
                                {{ $j->jam_mulai ? \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '' }} - {{ $j->jam_selesai ? \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') : '' }}
                            </span>
                            @if($isOngoing)
                                <span class="bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-full mt-1.5">
                                    Sedang Berlangsung
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada jadwal hari ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Task Deadlines -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-855 dark:text-slate-100">Deadline Tugas</h3>
                <span class="text-xxs font-bold text-red-650 bg-red-500/10 px-2.5 py-1 rounded-full uppercase">Penting</span>
            </div>
            <div class="p-6 overflow-y-auto max-h-[350px] custom-scrollbar">
                <div class="space-y-6">
                    @forelse($tugasMendatang as $t)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-xl border {{ $t['is_urgent'] ? 'border-red-200/30 dark:border-red-500/10 bg-red-50/20 dark:bg-red-500/5' : 'border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50' }}">
                            <div class="flex items-start gap-3">
                                <span class="w-2.5 h-2.5 rounded-full {{ $t['is_urgent'] ? 'bg-red-500' : 'bg-amber-500' }} mt-1 shrink-0"></span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-805 dark:text-slate-200">{{ $t['judul'] }}</h4>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $t['mapel'] }} • Batas: {{ $t['diff'] }}</p>
                                </div>
                            </div>
                            <a href="{{ route('tugas.show', [$t['kelas_id'], $t['id']]) }}" class="px-3.5 py-1.5 {{ $t['is_urgent'] ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-650 hover:bg-indigo-700' }} text-white rounded-lg text-xs font-semibold shadow transition-colors">
                                Kerjakan
                            </a>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Tidak ada tugas mendatang. Semua tugas selesai!</div>
                    @endforelse
                </div>
            </div>
        </div>
</div>
</x-app-layout>
