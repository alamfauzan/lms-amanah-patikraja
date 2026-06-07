<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">{{ $kelas->nama_kelas }}</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500 leading-none mt-0.5">{{ $kelas->tahun_ajaran }}</p>
            </div>
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

        {{-- Class Overview Card --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">{{ $kelas->nama_kelas }}</h3>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-0.5">{{ $kelas->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-650 dark:text-indigo-400 bg-indigo-500/10 px-2.5 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $kelas->tahun_ajaran }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-650 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ $kelas->siswa->count() }} Siswa
                        </span>
                        @if($kelas->waliKelas)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-violet-650 dark:text-violet-400 bg-violet-500/10 px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Wali: {{ $kelas->waliKelas->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('kelas.edit', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit Kelas
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data>
            {{-- Subject & Teacher List --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden {{ auth()->user()->hasRole('siswa') ? 'lg:col-span-3' : 'lg:col-span-2' }}">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100">Mata Pelajaran</h3>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-500/10 px-2.5 py-1 rounded-full">{{ $kelas->kelasMapelGuru->count() }} Mapel</span>
                </div>

                @if($kelas->kelasMapelGuru->isEmpty())
                    <div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada mata pelajaran yang ditambahkan.</div>
                @else
                    <div class="p-6 space-y-4">
                        @foreach($kelas->kelasMapelGuru as $kmg)
                            @php
                                $materiCount = \App\Models\Materi::where('kelas_id', $kelas->id)->where('guru_id', $kmg->guru_id)->count();
                                $tugasCount = $kelas->tugas->where('guru_id', $kmg->guru_id)->count();
                                $kuisCount = $kelas->kuis->where('guru_id', $kmg->guru_id)->count();

                                // Deterministic colors & icons based on mapel ID
                                $mapelId = $kmg->mata_pelajaran_id;
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
                            <div class="flex items-center justify-between gap-4 p-4 rounded-xl border transition-all duration-300 bg-slate-50/50 dark:bg-slate-900/50 border-slate-150 dark:border-slate-800/80 hover:shadow-sm cursor-pointer"
                                 @click="window.location.href = '{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $kmg->mata_pelajaran_id]) }}'">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $iconColor }}">
                                        {!! $iconSvg !!}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate leading-snug">
                                            {{ $kmg->mataPelajaran->nama_mapel }}
                                        </h4>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                            {{ Str::startsWith(strtolower($kmg->guru->name ?? ''), 'guru') ? ($kmg->guru->name ?? '-') : 'Guru ' . ($kmg->guru->name ?? '-') }} • {{ $kmg->mataPelajaran->kode_mapel }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0" @click.stop>
                                    @if(auth()->user()->hasRole('admin'))
                                        <form method="POST" action="{{ route('kelas.mapel.remove', [$kelas->id, $kmg->id]) }}" onsubmit="return confirm('Hapus mapel ini dari kelas?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </form>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-650 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Assign Subject (Admin only) --}}
                @if(auth()->user()->hasRole('admin') && $allSubjects->isNotEmpty())
                    <div class="px-6 py-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Tambah Mata Pelajaran</h4>
                        <form method="POST" action="{{ route('kelas.mapel.assign', $kelas->id) }}" class="flex flex-col sm:flex-row gap-3">
                            @csrf
                            <select name="mata_pelajaran_id" class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($allSubjects as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                            <select name="guru_id" class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Guru Pengampu</option>
                                @foreach($allTeachers as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shrink-0">
                                Tugaskan
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            @if(!auth()->user()->hasRole('siswa'))
            {{-- Student Roster --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100">Daftar Siswa</h3>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-500/10 px-2.5 py-1 rounded-full">{{ $kelas->siswa->count() }} Siswa</span>
                </div>

                @if($kelas->siswa->isEmpty())
                    <div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada siswa terdaftar.</div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/50 max-h-72 overflow-y-auto">
                        @foreach($kelas->siswa as $student)
                            <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $student->name }}</span>
                                </div>
                                @if(auth()->user()->hasRole('admin'))
                                    <form method="POST" action="{{ route('kelas.siswa.remove', [$kelas->id, $student->id]) }}" onsubmit="return confirm('Keluarkan siswa ini dari kelas?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add Student (Admin only) --}}
                @if(auth()->user()->hasRole('admin') && $allStudents->isNotEmpty())
                    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Tambah Siswa</h4>
                        <form method="POST" action="{{ route('kelas.siswa.add', $kelas->id) }}" class="flex gap-2">
                            @csrf
                            <select name="siswa_id" class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Siswa</option>
                                @foreach($allStudents as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
