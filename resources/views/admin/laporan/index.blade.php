<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Laporan Pembelajaran</h2>
            <a href="{{ route('admin.laporan.export') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor Laporan (CSV)
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 animate-fade-in">
            {{-- Guru --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                <p class="text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Total Guru</p>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalTeachers }}</h3>
            </div>
            {{-- Siswa --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                <p class="text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Total Siswa</p>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalStudents }}</h3>
            </div>
            {{-- Kelas --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                <p class="text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Total Kelas</p>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalClasses }}</h3>
            </div>
            {{-- Mapel --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                <p class="text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Total Mapel</p>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalSubjects }}</h3>
            </div>
            {{-- Tugas --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                <p class="text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Total Tugas</p>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalAssignments }}</h3>
            </div>
            {{-- Kuis --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                <p class="text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Total Kuis</p>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalQuizzes }}</h3>
            </div>
        </div>

        {{-- Class Performance Table --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">Kinerja per Kelas</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Statistik jumlah siswa dan rata-rata pencapaian akademik tiap kelas.</p>
            </div>

            @if($classesData->isEmpty())
                <div class="text-center py-12 text-slate-400">Belum ada data kelas untuk dievaluasi.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200/80 dark:border-slate-700/80 text-slate-500 dark:text-slate-400">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Tahun Ajaran</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Wali Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Jumlah Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Rata-rata Tugas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Rata-rata Kuis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-400">
                            @foreach($classesData as $class)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $class['nama_kelas'] }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                        {{ $class['tahun_ajaran'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $class['wali_kelas'] }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold">
                                        {{ $class['siswa_count'] }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(is_null($class['avg_tugas']))
                                            <span class="text-xs text-slate-300 dark:text-slate-650">—</span>
                                        @else
                                            <span class="inline-block px-2.5 py-1 font-bold text-amber-600 dark:text-amber-400">
                                                {{ $class['avg_tugas'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(is_null($class['avg_kuis']))
                                            <span class="text-xs text-slate-300 dark:text-slate-650">—</span>
                                        @else
                                            <span class="inline-block px-2.5 py-1 font-bold text-violet-600 dark:text-violet-400">
                                                {{ $class['avg_kuis'] }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
