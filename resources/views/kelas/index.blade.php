<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Daftar Kelas</h2>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('kelas.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kelas
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Empty State --}}
        @if($classes->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">Belum ada kelas</h3>
                <p class="text-sm text-slate-400 dark:text-slate-500">
                    @if(auth()->user()->hasRole('admin'))
                        Klik tombol "Tambah Kelas" untuk membuat kelas baru.
                    @elseif(auth()->user()->hasRole('guru'))
                        Anda belum ditugaskan mengampu kelas manapun.
                    @else
                        Anda belum terdaftar di kelas manapun.
                    @endif
                </p>
            </div>
        @else
            {{-- Classes Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($classes as $kelas)
                    @php
                        $progress = 100;
                        if (auth()->user()->hasRole('siswa')) {
                            $totalTugas = \App\Models\Tugas::where('kelas_id', $kelas->id)->count();
                            $totalKuis = \App\Models\Kuis::where('kelas_id', $kelas->id)->count();
                            $totalActivity = $totalTugas + $totalKuis;

                            if ($totalActivity > 0) {
                                $completedTugas = \App\Models\PengumpulanTugas::where('siswa_id', auth()->id())
                                    ->whereIn('tugas_id', \App\Models\Tugas::where('kelas_id', $kelas->id)->pluck('id'))
                                    ->count();
                                $completedKuis = \App\Models\HasilKuis::where('siswa_id', auth()->id())
                                    ->where('is_submitted', true)
                                    ->whereIn('kuis_id', \App\Models\Kuis::where('kelas_id', $kelas->id)->pluck('id'))
                                    ->count();
                                $completedActivity = $completedTugas + $completedKuis;
                                $progress = round(($completedActivity / $totalActivity) * 100);
                            }
                        }
                    @endphp
                    <div class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                        <!-- Top accent color bar -->
                        <div class="h-2.5 w-full bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                        <div class="p-6">
                            @if(auth()->user()->hasRole('admin'))
                                <div class="flex justify-end gap-1 mb-3">
                                    <a href="{{ route('kelas.edit', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('kelas.destroy', $kelas->id) }}" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <h4 class="text-base font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $kelas->nama_kelas }}
                            </h4>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">Wali: {{ $kelas->waliKelas->name ?? '-' }}</p>
                            
                            @if(auth()->user()->hasRole('siswa'))
                                <div class="mt-5 space-y-2">
                                    <div class="flex justify-between text-xxs font-bold text-slate-400">
                                        <span>Progress Belajar</span>
                                        <span>{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-805 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-5 space-y-1.5">
                                    <div class="flex justify-between text-xxs font-bold text-slate-400">
                                        <span>Tahun Ajaran</span>
                                        <span>{{ $kelas->tahun_ajaran }}</span>
                                    </div>
                                    <div class="flex justify-between text-xxs font-bold text-slate-400">
                                        <span>Jumlah Siswa</span>
                                        <span>{{ $kelas->siswa_count }} Siswa</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('kelas.show', $kelas->id) }}" class="px-6 py-3.5 bg-slate-50 dark:bg-slate-900/50 hover:bg-blue-500/5 text-center text-xs font-semibold text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 border-t border-slate-200 dark:border-slate-800/80 transition-all duration-200">
                            Masuk Kelas
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
