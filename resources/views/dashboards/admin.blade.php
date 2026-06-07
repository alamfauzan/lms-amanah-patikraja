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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Guru</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $totalGuru }}</h3>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Kelas</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $totalKelas }}</h3>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="group relative bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-violet-500/5 group-hover:bg-violet-500/10 rounded-full transition-colors duration-300"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/10 text-violet-650 dark:text-violet-405 flex items-center justify-center font-semibold shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mata Pelajaran</span>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 leading-none mt-1">{{ $totalMapel }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- System Activity Log -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Log Aktivitas Terbaru</h3>
                <span class="text-xxs font-bold text-indigo-650 bg-indigo-500/10 px-2.5 py-1 rounded-full uppercase">Realtime</span>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($systemLogs as $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-800" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-lg {{ $log['icon_bg'] }} flex items-center justify-center">
                                                {!! $log['icon_svg'] !!}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-slate-650 dark:text-slate-350">
                                                    {!! $log['text'] !!}
                                                </p>
                                            </div>
                                            <div class="text-right text-xs whitespace-nowrap text-slate-400 dark:text-slate-500">
                                                <time>{{ \Carbon\Carbon::parse($log['time'])->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada aktivitas baru.</div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- System Usage Stat -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Status & Kapasitas</h3>
            </div>
            <div class="p-6 flex-1 flex flex-col gap-6">
                <!-- Status Row 1 -->
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 dark:text-slate-450">
                        <span>Penggunaan Storage</span>
                        <span>4.2 GB / 10 GB</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full rounded-full" style="width: 42%"></div>
                    </div>
                </div>

                <!-- Status Row 2 -->
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 dark:text-slate-450">
                        <span>Beban Server CPU</span>
                        <span>24%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: 24%"></div>
                    </div>
                </div>

                <!-- Status Row 3 -->
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 dark:text-slate-450">
                        <span>Penggunaan Memory RAM</span>
                        <span>68%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full rounded-full" style="width: 68%"></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4.5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-150 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>Versi Laravel: 10.x</span>
                <span>PHP: 8.x</span>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

