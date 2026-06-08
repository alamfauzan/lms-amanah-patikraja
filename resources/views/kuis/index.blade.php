<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @isset($kelas)
            <a href="{{ route('kelas.show', $kelas->id) }}"
               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 dark:hover:text-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            @endisset
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">
                    Daftar Kuis
                    @isset($kelas)
                    &mdash; {{ $kelas->nama_kelas }}
                    @isset($mapel) &bull; {{ $mapel->nama_mapel }} @endisset
                    @endisset
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $kuis->count() }} kuis ditemukan</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5">
        {{-- Page Actions Row --}}
        @if(auth()->user()->hasAnyRole(['admin','guru']) && isset($kelas))
            <div class="flex justify-end mb-2">
                <a href="{{ route('kelas.kuis.create', [$kelas->id, 'mapel_id' => isset($mapel) ? $mapel->id : null]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Kuis
                </a>
            </div>
        @endif
        @if(session('success'))
        <div class="flex items-center gap-2.5 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-450 rounded-xl text-sm font-semibold shadow-sm">
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
            $baseUrl       = isset($kelas) ? route('kelas.kuis.index', $kelas->id) : route('kuis.index');
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
        @if($kuis->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 mb-1">
                @if($currentFilter === 'belum') Tidak ada kuis yang belum dikerjakan
                @elseif($currentFilter === 'selesai') Belum ada kuis yang selesai
                @elseif($currentFilter === 'overdue') Tidak ada kuis yang terlambat
                @else Belum ada kuis
                @endif
            </h3>
            <p class="text-sm text-slate-400 max-w-xs">
                @if($currentFilter !== 'semua') Coba pilih filter lain untuk melihat kuis lainnya.
                @else Kuis dari guru akan muncul di sini.
                @endif
            </p>
        </div>

        @else

        {{-- Kuis Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($kuis as $k)
            @php
                $kelasId   = isset($kelas) ? $kelas->id : $k->kelas_id;
                $isPast    = $k->selesai_at ? now()->gt($k->selesai_at) : false;
                $attempt   = $isSiswa ? $k->hasilBySiswa(auth()->id()) : null;
                $nilaiKuis = $isSiswa ? $k->nilaiAkhirBySiswa(auth()->id()) : null;

                if ($isSiswa) {
                    $status = ($attempt && $attempt->is_submitted) ? 'selesai' : ($isPast ? 'overdue' : 'belum');
                } else {
                    $status = $k->is_aktif ? 'aktif' : 'draft';
                }
            @endphp

            <div class="group relative bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm hover:shadow-lg hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 overflow-hidden flex flex-col">

                {{-- Whole Card Clickable Link --}}
                @php
                    if ($isSiswa) {
                        $cardUrl = ($attempt && $attempt->is_submitted) 
                            ? route('kuis.hasil', [$kelasId, $k->id]) 
                            : ($k->is_aktif ? route('kuis.show', [$kelasId, $k->id]) : '#');
                    } else {
                        $cardUrl = route('kuis.show', [$kelasId, $k->id]);
                    }
                @endphp
                @if($cardUrl !== '#')
                <a href="{{ $cardUrl }}" class="absolute inset-0 z-10" aria-label="Buka Kuis"></a>
                @endif

                {{-- Top Accent Bar --}}
                @if($status === 'selesai')
                <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                @elseif($status === 'belum')
                <div class="h-1 w-full bg-gradient-to-r from-amber-400 to-orange-500"></div>
                @elseif($status === 'overdue')
                <div class="h-1 w-full bg-gradient-to-r from-red-400 to-rose-600"></div>
                @elseif($status === 'aktif')
                <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                @else
                <div class="h-1 w-full bg-gradient-to-r from-slate-400 to-slate-500"></div>
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
                            @else
                            <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            @endif

                            {{-- Mapel + Judul --}}
                            <div class="min-w-0">
                                @if($k->mataPelajaran)
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-100 text-violet-750 dark:bg-violet-900/30 dark:text-violet-400 uppercase tracking-wide mb-1">
                                    {{ $k->mataPelajaran->nama_mapel }}
                                </span>
                                @elseif(isset($mapel))
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-100 text-violet-750 dark:bg-violet-900/30 dark:text-violet-400 uppercase tracking-wide mb-1">
                                    {{ $mapel->nama_mapel }}
                                </span>
                                @endif
                                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 leading-snug line-clamp-2">
                                    {{ $k->pertemuan ? 'Kuis Pertemuan ' . $k->pertemuan->urutan : $k->judul }}
                                </h3>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        @if($isSiswa)
                            @if($status === 'selesai')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-450 dark:ring-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Selesai
                            </span>
                            @elseif($status === 'belum')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-amber-50 text-amber-750 ring-1 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Belum
                            </span>
                            @elseif($status === 'overdue')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-red-50 text-red-600 ring-1 ring-red-200 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Terlambat
                            </span>
                            @endif
                        @else
                            @if($status === 'aktif')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-emerald-50 text-emerald-705 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 bg-slate-50 text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Draft
                            </span>
                            @endif
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($k->deskripsi)
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed -mt-1">
                        {{ $k->deskripsi }}
                    </p>
                    @endif

                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-100 dark:border-slate-800">
                        @if(!isset($kelas) && $k->kelas)
                        <div class="flex items-center gap-1">
                            <span class="font-medium text-slate-700 dark:text-slate-400">{{ $k->kelas->nama_kelas }}</span>
                        </div>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        @endif

                        <div class="flex items-center gap-1">
                            <span>⏱️ {{ $k->durasi_menit }} Menit</span>
                        </div>
                        <span class="text-slate-300 dark:text-slate-700">•</span>

                        <div class="flex items-center gap-1">
                            <span>📝 {{ $k->jumlah_soal }} Soal</span>
                        </div>

                        @if($k->pertemuan)
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <div class="flex items-center gap-1">
                            <span>Pertemuan {{ $k->pertemuan->urutan }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Deadline / Info --}}
                    <div class="text-[11px] flex items-center justify-between text-slate-400 dark:text-slate-500 mt-0.5">
                        @if($k->selesai_at)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Selesai pada: {{ $k->selesai_at->translatedFormat('d M Y, H:i') }}
                        </span>
                        @else
                        <span>Tanpa batas pengerjaan</span>
                        @endif

                        @if($isSiswa && $attempt && $attempt->is_submitted)
                        <span class="text-emerald-600 dark:text-emerald-450 font-medium">Sudah dikerjakan</span>
                        @endif
                    </div>
                </div>

                {{-- Footer CTA --}}
                <div class="flex items-center justify-between px-5 py-3.5 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 transition-all duration-200 mt-auto">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">
                        @if($isSiswa)
                            @if($attempt && $attempt->is_submitted)
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Lihat Hasil &amp; Nilai</span>
                            @elseif($k->is_aktif)
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">{{ $attempt ? 'Lanjutkan Kuis' : 'Mulai Kuis' }}</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-600">Belum Aktif</span>
                            @endif
                        @else
                            <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Lihat Detail / Hasil</span>
                        @endif
                    </span>

                    <div class="flex items-center gap-3 relative z-20">
                        @if(!$isSiswa)
                        <a href="{{ route('kelas.kuis.edit', [$kelasId, $k->id]) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white hover:bg-indigo-50 dark:bg-slate-900 dark:hover:bg-indigo-950/20 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-800 transition-all duration-200">
                            Edit
                        </a>
                        @endif

                        @if($cardUrl !== '#')
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:translate-x-1 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        @endif
                    </div>
                </div>
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
