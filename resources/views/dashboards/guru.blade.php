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
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/5 group-hover:bg-indigo-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas Diajar</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $jumlahKelas }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 group-hover:bg-emerald-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Siswa</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $totalSiswa }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 group-hover:bg-amber-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tugas Aktif</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $tugasAktif }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-violet-500/5 group-hover:bg-violet-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/10 text-violet-650 dark:text-violet-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kuis Aktif</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $kuisAktif }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Daily Schedule -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Jadwal Mengajar Hari Ini</h3>
            </div>
            <div class="p-6 flex-1 space-y-4 max-h-[350px] overflow-y-auto custom-scrollbar">
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
                                <h4 class="text-sm font-bold text-slate-805 dark:text-slate-200 truncate leading-snug">
                                    {{ $j->mataPelajaran->nama_mapel }}
                                </h4>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                    {{ $j->kelas->nama_kelas }} • {{ $j->ruangan ?? 'Online' }}
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
                    <div class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada jadwal mengajar hari ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Student Submission Logs -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Pengumpulan & Aktivitas Siswa</h3>
                <span class="text-xxs font-semibold bg-emerald-500/10 text-emerald-650 px-2 py-0.5 rounded-full">{{ $aktivitasSiswa->count() }} Aktivitas Baru</span>
            </div>
            <div class="p-6 flex-1 overflow-y-auto max-h-[350px] custom-scrollbar">
                <div class="space-y-6">
                    @forelse($aktivitasSiswa as $act)
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold text-xs shrink-0">
                                    {{ $act['initials'] }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $act['nama_siswa'] }}</h4>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $act['deskripsi'] }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xxs font-bold px-2.5 py-1 rounded-full uppercase
                                    {{ $act['type'] === 'kuis' ? 'text-violet-600 bg-violet-500/10' : 'text-emerald-600 bg-emerald-500/10' }}">
                                    {{ $act['status'] }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    {{ \Carbon\Carbon::parse($act['timestamp'])->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada pengumpulan atau aktivitas siswa hari ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
