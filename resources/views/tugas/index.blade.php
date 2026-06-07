<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @isset($kelas)
                    <a href="{{ route('kelas.show', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endisset
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">
                    Daftar Tugas @isset($kelas)— {{ $kelas->nama_kelas }} @isset($mapel) • {{ $mapel->nama_mapel }} @endisset @endisset
                </h2>
            </div>
            @if(auth()->user()->hasAnyRole(['admin','guru']) && isset($kelas))
                <a href="{{ route('kelas.tugas.create', [$kelas->id, 'mapel_id' => isset($mapel) ? $mapel->id : null]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Buat Tugas
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($tugas->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">Belum ada tugas</h3>
                <p class="text-sm text-slate-400">Tugas yang dibuat akan muncul di sini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($tugas as $t)
                    @php
                        $isPast    = now()->gt($t->deadline);
                        $kelasId   = isset($kelas) ? $kelas->id : $t->kelas_id;
                    @endphp
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">
                        <div class="p-5 flex-1">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $isPast ? 'bg-red-500/10 text-red-500' : 'bg-emerald-500/10 text-emerald-600' }}">
                                    {{ $isPast ? 'Lewat' : 'Aktif' }}
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-1 flex items-center gap-1.5 flex-wrap">
                                @if($t->mataPelajaran)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-violet-100 text-violet-750 dark:bg-violet-900/30 dark:text-violet-400">
                                        {{ $t->mataPelajaran->nama_mapel }}
                                    </span>
                                @endif
                                {{ $t->judul }}
                            </h3>
                            @if($t->deskripsi)
                                <p class="text-xs text-slate-400 mb-3 line-clamp-2">{{ $t->deskripsi }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $t->deadline->format('d M Y H:i') }}
                                </span>
                                <span>Nilai max: {{ $t->nilai_maksimum }}</span>
                            </div>
                        </div>
                        <a href="{{ route('tugas.show', [$kelasId, $t->id]) }}"
                           class="flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-50 dark:bg-slate-900/50 hover:bg-orange-500/5 text-xs font-semibold text-slate-500 hover:text-orange-600 dark:text-slate-400 border-t border-slate-150 dark:border-slate-800/80 transition-all duration-200">
                            Lihat Detail
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
