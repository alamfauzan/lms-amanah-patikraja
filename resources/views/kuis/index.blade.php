<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                @isset($kelas)
                    <a href="{{ route('kelas.show', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endisset
                <div>
                    <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">
                        Daftar Kuis @isset($mapel)— {{ $mapel->nama_mapel }} @endisset
                    </h2>
                    @isset($kelas)
                        <p class="text-xs text-slate-400 leading-none mt-0.5">
                            {{ $kelas->nama_kelas }} @isset($mapel) • {{ $mapel->kode_mapel }} @endisset
                        </p>
                    @endisset
                </div>
            </div>

            @if(auth()->user()->hasAnyRole(['admin','guru']) && isset($kelas))
                <a href="{{ route('kelas.kuis.create', [$kelas->id, 'mapel_id' => isset($mapel) ? $mapel->id : null]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-650/10 transition duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Buat Kuis
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-450 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($kuis->isEmpty())
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-500 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-lg">Belum ada kuis</h3>
                <p class="text-sm text-slate-400 mt-1">Saat ini belum ada kuis yang ditambahkan untuk kelas ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($kuis as $k)
                    @php
                        $attempt = auth()->user()->hasRole('siswa') ? $k->hasilBySiswa(auth()->id()) : null;
                        $kelasId = isset($kelas) ? $kelas->id : $k->kelas_id;
                    @endphp
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between gap-4">
                         <div class="space-y-2">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div class="flex flex-wrap gap-1.5">
                                    @if(!isset($kelas) && $k->kelas)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-500/10 text-indigo-650 dark:text-indigo-400">
                                            {{ $k->kelas->nama_kelas }}
                                        </span>
                                    @endif
                                    @if($k->mataPelajaran)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-100 text-violet-750 dark:bg-violet-900/30 dark:text-violet-400">
                                            {{ $k->mataPelajaran->nama_mapel }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                        {{ $k->pertemuan ? 'Pertemuan ke-' . $k->pertemuan->urutan : 'Umum' }}
                                    </span>
                                </div>

                                @if(auth()->user()->hasAnyRole(['admin','guru']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold
                                        {{ $k->is_aktif ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-550' }}">
                                        {{ $k->is_aktif ? 'Aktif' : 'Draft' }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base leading-snug">{{ $k->judul }}</h3>
                            <p class="text-xs text-slate-400 line-clamp-2">{{ $k->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800/50 flex items-center justify-between gap-2">
                            <div class="text-[11px] text-slate-400 space-y-0.5">
                                <p>⏱️ Durasi: <b>{{ $k->durasi_menit }} Menit</b></p>
                                <p>📝 Soal: <b>{{ $k->jumlah_soal }} Butir</b></p>
                            </div>

                            <div>
                                @if(auth()->user()->hasRole('siswa'))
                                    @if($attempt && $attempt->is_submitted)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Selesai dikerjakan</span>
                                            <a href="{{ route('kuis.hasil', [$kelasId, $k->id]) }}"
                                               class="px-3.5 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition duration-200">
                                                Nilai: {{ $attempt->nilai_akhir }}
                                            </a>
                                        </div>
                                    @else
                                        @if(!$k->is_aktif)
                                            <span class="text-xs font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-lg">Belum Aktif</span>
                                        @else
                                            <a href="{{ route('kuis.show', [$kelasId, $k->id]) }}"
                                               class="px-3.5 py-1.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition duration-200">
                                                {{ $attempt ? 'Lanjutkan' : 'Mulai Kuis' }}
                                            </a>
                                        @endif
                                    @endif
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('kuis.show', [$kelasId, $k->id]) }}"
                                           class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition duration-200">
                                            Detail / Hasil
                                        </a>
                                        <a href="{{ route('kelas.kuis.edit', [$kelasId, $k->id]) }}"
                                           class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-950/20 text-slate-600 dark:text-slate-350 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-lg transition duration-200">
                                            Edit
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
