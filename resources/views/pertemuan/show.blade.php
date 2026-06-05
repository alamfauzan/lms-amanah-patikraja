<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.pertemuan.index', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">{{ $pertemuan->judul }}</h2>
                <p class="text-xs text-slate-400 leading-none mt-0.5">Pertemuan ke-{{ $pertemuan->urutan }} • {{ $kelas->nama_kelas }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Materi --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100">📚 Materi</h3>
                    @if(auth()->user()->hasAnyRole(['admin','guru']))
                        <a href="{{ route('kelas.pertemuan.materi.create', [$kelas->id, $pertemuan->id]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Tambah
                        </a>
                    @endif
                </div>

                @if($pertemuan->materi->isEmpty())
                    <div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada materi untuk pertemuan ini.</div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($pertemuan->materi as $m)
                            <div class="px-6 py-4 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-lg {{ $m->tipe === 'video' ? 'bg-red-500/10 text-red-500' : ($m->tipe === 'file' ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-500') }} flex items-center justify-center shrink-0">
                                        @if($m->tipe === 'video')
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                                        @elseif($m->tipe === 'file')
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $m->judul }}</h4>
                                        <p class="text-xs text-slate-400">{{ ucfirst($m->tipe) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('materi.show', $m->id) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-lg hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-500/10 transition-colors shrink-0">
                                    Buka
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Tugas --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100">📝 Tugas</h3>
                    @if(auth()->user()->hasAnyRole(['admin','guru']))
                        <a href="{{ route('kelas.tugas.create', $kelas->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Buat Tugas
                        </a>
                    @endif
                </div>
                @if($pertemuan->tugas->isEmpty())
                    <div class="p-6 text-center text-sm text-slate-400">Belum ada tugas untuk pertemuan ini.</div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($pertemuan->tugas as $t)
                            <div class="px-6 py-4 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $t->judul }}</h4>
                                    <p class="text-xs text-slate-400">Deadline: {{ $t->deadline->format('d M Y H:i') }}</p>
                                </div>
                                <a href="{{ route('tugas.show', [$kelas->id, $t->id]) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-lg hover:bg-orange-50 hover:text-orange-600 transition-colors shrink-0">Lihat</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar info --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6">
                <h4 class="font-bold text-slate-800 dark:text-slate-100 mb-4">Detail Pertemuan</h4>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Urutan</dt>
                        <dd class="text-slate-700 dark:text-slate-300 font-medium mt-1">Pertemuan ke-{{ $pertemuan->urutan }}</dd>
                    </div>
                    @if($pertemuan->tanggal)
                        <div>
                            <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</dt>
                            <dd class="text-slate-700 dark:text-slate-300 font-medium mt-1">{{ $pertemuan->tanggal->format('d M Y') }}</dd>
                        </div>
                    @endif
                    @if($pertemuan->deskripsi)
                        <div>
                            <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Deskripsi</dt>
                            <dd class="text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">{{ $pertemuan->deskripsi }}</dd>
                        </div>
                    @endif
                </dl>

                @if(auth()->user()->hasAnyRole(['admin','guru']))
                    <div class="mt-5 pt-5 border-t border-slate-100 dark:border-slate-800 flex gap-2">
                        <a href="{{ route('kelas.pertemuan.edit', [$kelas->id, $pertemuan->id]) }}" class="flex-1 text-center px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-lg transition-colors">Edit</a>
                        <form method="POST" action="{{ route('kelas.pertemuan.destroy', [$kelas->id, $pertemuan->id]) }}" onsubmit="return confirm('Hapus pertemuan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Kuis --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">🎯 Kuis</h4>
                    @if(auth()->user()->hasAnyRole(['admin','guru']))
                        <a href="{{ route('kelas.kuis.create', $kelas->id) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold">+ Buat</a>
                    @endif
                </div>
                @if($pertemuan->kuis->isEmpty())
                    <div class="px-5 py-4 text-xs text-slate-400">Belum ada kuis.</div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($pertemuan->kuis as $k)
                            <div class="px-5 py-3 flex items-center justify-between gap-2">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $k->judul }}</span>
                                <a href="{{ route('kuis.show', [$kelas->id, $k->id]) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold shrink-0">Lihat</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
