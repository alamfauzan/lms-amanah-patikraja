<div class="space-y-8 animate-fade-in">
    <!-- Header Page -->
    <div class="flex flex-col gap-1.5">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Dashboard Pengajar</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Selamat datang kembali, {{ auth()->user()->name }}. Berikut ringkasan kelas dan tugas yang Anda ampu hari ini.</p>
    </div>

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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Daily Schedule -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Jadwal Mengajar Hari Ini</h3>
            </div>
            <div class="p-6 flex-1 divide-y divide-slate-100 dark:divide-slate-800/50">
                @forelse($jadwalHariIni as $j)
                    <div class="py-4 first:pt-0 last:pb-0 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 flex flex-col items-center justify-center font-bold shrink-0 p-1 text-center">
                            <span class="text-[10px] leading-tight">{{ $j->kelas->nama_kelas }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-slate-805 dark:text-slate-200 truncate">{{ $j->mataPelajaran->nama_mapel }}</h4>
                            <div class="flex items-center gap-1.5 mt-1 text-xs text-slate-400 dark:text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $j->jam_mulai ? \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '' }} - {{ $j->jam_selesai ? \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') : '' }} WIB</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada jadwal mengajar hari ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Student Submission Logs -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm lg:col-span-2 overflow-hidden flex flex-col justify-between">
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
