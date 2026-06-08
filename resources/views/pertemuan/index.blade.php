<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.show', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">
                    {{ $mapel ? $mapel->nama_mapel : 'Pertemuan' }}
                </h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">
                    {{ $kelas->nama_kelas }} {{$mapel ? ' • ' . $mapel->kode_mapel : ''}}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        {{-- Page Actions Row --}}
        @if(auth()->user()->hasAnyRole(['admin','guru']))
            <div class="flex justify-end mb-2">
                <a href="{{ route('kelas.pertemuan.create', [$kelas->id, 'mapel_id' => $mapel ? $mapel->id : null]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pertemuan
                </a>
            </div>
        @endif
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($pertemuan->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-violet-500/10 text-violet-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">Belum ada pertemuan</h3>
                <p class="text-sm text-slate-400">Klik "Tambah Pertemuan" untuk mulai membuat pertemuan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pertemuan as $item)
                    <div x-data="{ open: false }" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden transition-all duration-300">
                        <!-- Accordion Header -->
                        <div @click="open = !open" class="px-6 py-5 flex items-center justify-between cursor-pointer select-none border-b border-slate-100 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-900/20">
                            <div class="flex flex-col gap-1 min-w-0">
                                <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100">
                                    Pertemuan {{ $item->urutan }}
                                </h3>
                                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 truncate">
                                    {{ $item->judul }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 shrink-0" @click.stop>
                                @if($item->tanggal)
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                                        {{ $item->tanggal->format('d M Y') }}
                                    </span>
                                @endif
                                <div class="flex items-center gap-2">
                                    @if(auth()->user()->hasAnyRole(['admin','guru']))
                                        <a href="{{ route('kelas.pertemuan.edit', [$kelas->id, $item->id]) }}"
                                           class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('kelas.pertemuan.destroy', [$kelas->id, $item->id]) }}" onsubmit="return confirm('Hapus pertemuan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <button @click="open = !open" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-650 dark:hover:text-slate-205 transition-colors">
                                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Body -->
                        <div x-show="open" class="p-6 space-y-4">
                            @if($item->deskripsi)
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed border-b border-slate-100 dark:border-slate-800/80 pb-4">
                                    {{ $item->deskripsi }}
                                </p>
                            @endif

                            <!-- Resource List -->
                            <div class="space-y-3">
                                @php
                                    $hasResources = $item->materi->isNotEmpty() || $item->tugas->isNotEmpty() || $item->kuis->isNotEmpty();
                                @endphp

                                @if(!$hasResources)
                                    <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">Belum ada materi, tugas, atau kuis di pertemuan ini.</p>
                                @else
                                    {{-- 1. Materi List --}}
                                    @foreach($item->materi as $m)
                                        @php
                                            if ($m->tipe === 'video') {
                                                $itemIconBg = 'bg-red-500/10 text-red-600 dark:bg-red-500/20 dark:text-red-400';
                                                $itemIconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>';
                                                $labelTipe = 'Video';
                                            } elseif ($m->tipe === 'teks') {
                                                $itemIconBg = 'bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400';
                                                $itemIconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
                                                $labelTipe = 'Teks';
                                            } else {
                                                $itemIconBg = 'bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400';
                                                $itemIconSvg = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
                                                $labelTipe = 'File';
                                            }
                                        @endphp
                                        <a href="{{ route('materi.show', $m->id) }}" class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-xl hover:shadow-sm transition duration-200">
                                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $itemIconBg }}">
                                                {!! $itemIconSvg !!}
                                            </div>
                                            <div class="min-w-0">
                                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block font-sans">Materi • {{ $labelTipe }}</span>
                                                <h4 class="text-sm font-bold text-indigo-655 dark:text-indigo-400 mt-0.5 hover:underline truncate">{{ $m->judul }}</h4>
                                            </div>
                                        </a>
                                    @endforeach

                                    {{-- 2. Tugas List --}}
                                    @foreach($item->tugas as $t)
                                        <a href="{{ route('tugas.show', [$kelas->id, $t->id]) }}" class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-xl hover:shadow-sm transition duration-200">
                                            <div class="w-11 h-11 rounded-xl bg-orange-500/10 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400 flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block font-sans">Tugas</span>
                                                <h4 class="text-sm font-bold text-orange-600 dark:text-orange-400 mt-0.5 hover:underline truncate">{{ $t->judul }}</h4>
                                            </div>
                                        </a>
                                    @endforeach

                                    {{-- 3. Kuis List --}}
                                    @foreach($item->kuis as $k)
                                        <a href="{{ route('kuis.show', [$kelas->id, $k->id]) }}" class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-xl hover:shadow-sm transition duration-200">
                                            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block font-sans">Kuis</span>
                                                <h4 class="text-sm font-bold text-emerald-600 dark:text-emerald-400 mt-0.5 hover:underline truncate">{{ $k->judul }}</h4>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Actions --}}
                            @if(auth()->user()->hasAnyRole(['admin','guru']))
                                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-2">
                                    <a href="{{ route('kelas.pertemuan.materi.create', [$kelas->id, $item->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Materi
                                    </a>
                                    <a href="{{ route('kelas.tugas.create', [$kelas->id, 'mapel_id' => $item->mata_pelajaran_id, 'pertemuan_id' => $item->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 dark:bg-orange-500/10 text-orange-655 dark:text-orange-400 hover:bg-orange-100 text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Buat Tugas
                                    </a>
                                    <a href="{{ route('kelas.kuis.create', [$kelas->id, 'mapel_id' => $item->mata_pelajaran_id, 'pertemuan_id' => $item->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Buat Kuis
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
