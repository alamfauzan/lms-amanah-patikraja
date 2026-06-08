<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @isset($kelas)
            <a href="{{ route('kelas.show', $kelas->id) }}"
               class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            @endisset
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">
                    Daftar Tugas
                    @isset($kelas)
                    &mdash; {{ $kelas->nama_kelas }}
                    @isset($mapel) &bull; {{ $mapel->nama_mapel }} @endisset
                    @endisset
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $tugas->count() }} tugas ditemukan</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5">
        {{-- Page Actions Row --}}
        @if(auth()->user()->hasAnyRole(['admin','guru']) && isset($kelas))
            <div class="flex justify-end mb-2">
                <a href="{{ route('kelas.tugas.create', [$kelas->id, 'mapel_id' => isset($mapel) ? $mapel->id : null]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tugas
                </a>
            </div>
        @endif

        @if(session('success'))
        <div class="flex items-center gap-3 px-5 py-3.5 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @php
            $statusMap     = $statusMap ?? [];
            $isSiswa       = auth()->user()->hasRole('siswa');
            $currentFilter = request('filter', 'semua');
            $countSemua    = count($statusMap);
            $countBelum    = collect($statusMap)->filter(fn($s) => $s === 'belum')->count();
            $countSelesai  = collect($statusMap)->filter(fn($s) => $s === 'selesai')->count();
            $countOverdue  = collect($statusMap)->filter(fn($s) => $s === 'overdue')->count();
            $baseUrl       = isset($kelas) ? route('kelas.tugas.index', $kelas->id) : route('tugas.index');
            $mapelQ        = isset($mapel) ? '&mapel_id=' . $mapel->id : '';
        @endphp

        {{-- Filter Tabs: siswa only --}}
        @if($isSiswa)
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide">

            {{-- Semua --}}
            <a href="{{ $baseUrl }}?filter=semua{{ $mapelQ }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200
               @if($currentFilter === 'semua') bg-slate-100 text-slate-900 border border-slate-300 dark:bg-slate-800 dark:text-white dark:border-slate-700 shadow-sm
               @else bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-slate-300 @endif">
                Semua
                <span class="text-xs px-1.5 py-0.5 rounded-md font-bold
                    @if($currentFilter === 'semua') bg-slate-200 text-slate-800 dark:bg-slate-750 dark:text-slate-300
                    @else bg-slate-100 dark:bg-slate-700 text-slate-500 @endif">
                    {{ $countSemua }}
                </span>
            </a>

            {{-- Belum Dikerjakan --}}
            <a href="{{ $baseUrl }}?filter=belum{{ $mapelQ }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200
               @if($currentFilter === 'belum') bg-amber-500 text-white shadow-sm
               @else bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-amber-300 hover:text-amber-600 @endif">
                Belum Dikerjakan
                <span class="text-xs px-1.5 py-0.5 rounded-md font-bold
                    @if($currentFilter === 'belum') bg-white/20 text-white
                    @else bg-amber-50 text-amber-600 @endif">
                    {{ $countBelum }}
                </span>
            </a>

            {{-- Selesai --}}
            <a href="{{ $baseUrl }}?filter=selesai{{ $mapelQ }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200
               @if($currentFilter === 'selesai') bg-emerald-500 text-white shadow-sm
               @else bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-emerald-300 hover:text-emerald-600 @endif">
                Selesai
                <span class="text-xs px-1.5 py-0.5 rounded-md font-bold
                    @if($currentFilter === 'selesai') bg-white/20 text-white
                    @else bg-emerald-50 text-emerald-600 @endif">
                    {{ $countSelesai }}
                </span>
            </a>

            {{-- Terlambat --}}
            <a href="{{ $baseUrl }}?filter=overdue{{ $mapelQ }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200
               @if($currentFilter === 'overdue') bg-red-500 text-white shadow-sm
               @else bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-red-300 hover:text-red-600 @endif">
                Terlambat
                <span class="text-xs px-1.5 py-0.5 rounded-md font-bold
                    @if($currentFilter === 'overdue') bg-white/20 text-white
                    @else bg-red-50 text-red-500 @endif">
                    {{ $countOverdue }}
                </span>
            </a>

        </div>
        @endif

        {{-- Empty State --}}
        @if($tugas->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 mb-1">
                @if($currentFilter === 'belum') Tidak ada tugas yang belum dikerjakan
                @elseif($currentFilter === 'selesai') Belum ada tugas yang selesai
                @elseif($currentFilter === 'overdue') Tidak ada tugas yang terlambat
                @else Belum ada tugas
                @endif
            </h3>
            <p class="text-sm text-slate-400 max-w-xs">
                @if($currentFilter !== 'semua') Coba pilih filter lain untuk melihat tugas lainnya.
                @else Tugas dari guru akan muncul di sini.
                @endif
            </p>

        </div>

        @else

        {{-- Tugas Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($tugas as $t)
            @php
                $kelasId   = isset($kelas) ? $kelas->id : $t->kelas_id;
                $isPast    = now()->gt($t->deadline);
                $hoursLeft = now()->diffInHours($t->deadline, false);
                $isUrgent  = !$isPast && $hoursLeft <= 24;

                if ($isSiswa) {
                    $status = $statusMap[$t->id] ?? ($isPast ? 'overdue' : 'belum');
                } else {
                    $status = $isPast ? 'overdue' : 'aktif';
                }
            @endphp

            <div class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm hover:shadow-lg hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 overflow-hidden flex flex-col">

                {{-- Top Accent Bar --}}
                @if($status === 'selesai')
                <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                @elseif($status === 'belum')
                <div class="h-1 w-full bg-gradient-to-r from-amber-400 to-orange-500"></div>
                @elseif($status === 'overdue')
                <div class="h-1 w-full bg-gradient-to-r from-red-400 to-rose-600"></div>
                @else
                <div class="h-1 w-full bg-gradient-to-r from-orange-400 to-amber-500"></div>
                @endif

                <div class="p-5 flex-1 flex flex-col gap-3">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">

                            {{-- Icon --}}
                            @if($status === 'selesai')
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @elseif($status === 'overdue')
                            <div class="w-11 h-11 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 dark:text-red-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            @elseif($status === 'belum')
                            <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            @else
                            <div class="w-11 h-11 rounded-xl bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            @endif

                            {{-- Mapel + Judul --}}
                            <div class="min-w-0">
                                @if($t->mataPelajaran)
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 uppercase tracking-wide mb-1">
                                    {{ $t->mataPelajaran->nama_mapel }}
                                </span>
                                @elseif(isset($mapel))
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 uppercase tracking-wide mb-1">
                                    {{ $mapel->nama_mapel }}
                                </span>
                                @endif
                                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 leading-snug line-clamp-2">
                                    {{ $t->judul }}
                                </h3>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        @if($status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Selesai
                        </span>
                        @elseif($status === 'belum')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Belum
                        </span>
                        @elseif($status === 'overdue')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-red-50 text-red-600 ring-1 ring-red-200 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Terlambat
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-sky-50 text-sky-700 ring-1 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-400 dark:ring-sky-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>Aktif
                        </span>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($t->deskripsi)
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed -mt-1">
                        {{ $t->deskripsi }}
                    </p>
                    @endif

                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-100 dark:border-slate-800">

                        {{-- Deadline --}}
                        <div class="flex items-center gap-1.5 @if($status === 'overdue') text-red-500 font-semibold @elseif($isUrgent) text-orange-500 font-semibold @endif">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $t->deadline->format('d M Y, H:i') }}
                            @if($isUrgent) <span class="font-bold text-orange-500">· Segera!</span> @endif
                            @if($status === 'overdue') <span class="font-bold">· Lewat</span> @endif
                        </div>

                        {{-- Nilai Maks --}}
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Nilai maks. <strong class="text-slate-700 dark:text-slate-200 ml-0.5">{{ $t->nilai_maksimum }}</strong>
                        </div>

                        {{-- Kelas (global view only) --}}
                        @if(!isset($kelas) && $t->kelas)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $t->kelas->nama_kelas }}
                        </div>
                        @endif

                        {{-- Pengumpulan (guru/admin) --}}
                        @if(auth()->user()->hasAnyRole(['admin','guru']))
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                            </svg>
                            {{ $t->pengumpulan->count() }} pengumpulan
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Footer CTA --}}
                <a href="{{ route('tugas.show', [$kelasId, $t->id]) }}"
                   class="group/btn flex items-center justify-between px-5 py-3.5 bg-slate-50 dark:bg-slate-800/40 hover:bg-orange-50 dark:hover:bg-orange-500/5 border-t border-slate-100 dark:border-slate-800 transition-all duration-200">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 group-hover/btn:text-orange-600 dark:group-hover/btn:text-orange-400 transition-colors">
                        @if($status === 'selesai') Lihat Nilai &amp; Feedback
                        @elseif($status === 'belum') Kerjakan Sekarang
                        @else Lihat Detail
                        @endif
                    </span>
                    <svg class="w-4 h-4 text-slate-300 group-hover/btn:text-orange-500 group-hover/btn:translate-x-1 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>

        @endif

    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
