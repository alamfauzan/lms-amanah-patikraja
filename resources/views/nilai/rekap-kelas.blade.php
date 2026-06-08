<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('nilai.guru') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">
                    Rekap Nilai — {{ $kelas->nama_kelas }}
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $kelas->tahun_ajaran ?? '' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 animate-fade-in">

        <!-- Grade Table -->
        <form id="rekap-form" action="{{ route('nilai.update', $kelas->id) }}" method="POST">
            @csrf
            <div class="flex justify-end mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('nilai.export-csv', $kelas->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200/80 dark:border-slate-700/80">
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider sticky left-0 bg-slate-50 dark:bg-slate-800/50 min-w-[180px]">
                                Nama Siswa
                            </th>
                            @foreach($tugasList as $tugas)
                                <th class="px-4 py-3.5 text-center text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider min-w-[120px]" title="{{ $tugas->judul }}">
                                    <span class="block truncate max-w-[100px] mx-auto">T: {{ Str::limit($tugas->judul, 15) }}</span>
                                </th>
                            @endforeach
                            @foreach($kuisList as $kuis)
                                <th class="px-4 py-3.5 text-center text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-wider min-w-[120px]" title="{{ $kuis->judul }}">
                                    <span class="block truncate max-w-[100px] mx-auto">K: {{ Str::limit($kuis->judul, 15) }}</span>
                                </th>
                            @endforeach
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider min-w-[100px]">Rata Tugas</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-wider min-w-[100px]">Rata Kuis</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider min-w-[100px]">Nilai Akhir</th>
                        </tr>
                    </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($rekap as $i => $r)
                            <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                                <!-- Nama Siswa -->
                                <td class="px-4 py-3.5 sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/30">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($r['siswa']->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800 dark:text-slate-200 truncate text-sm">{{ $r['siswa']->name }}</p>
                                            <p class="text-xs text-slate-400 truncate">{{ $r['siswa']->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Nilai Tugas per kolom -->
                                @foreach($tugasList as $tugas)
                                    @php
                                        $nt = $r['nilai_tugas'][$tugas->id] ?? null;
                                        $nilai = $nt['nilai'] ?? null;
                                        $status = $nt['status'] ?? null;
                                    @endphp
                                    <td class="px-4 py-3.5 text-center">
                                        @php
                                            $currentVal = old('nilai.'.$tugas->id.'.'.$r['siswa']->id, $nilai !== null ? (string) $nilai : '');
                                            $datalistId = 'nilai-options-'.$tugas->id.'-'.$r['siswa']->id;
                                        @endphp
                                        <input list="{{ $datalistId }}"
                                               type="number"
                                               name="nilai[{{ $tugas->id }}][{{ $r['siswa']->id }}]"
                                               min="0" max="100" step="1"
                                               value="{{ $currentVal }}"
                                               class="w-24 text-center px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <datalist id="{{ $datalistId }}">
                                            @for($n = 0; $n <= 100; $n += 5)
                                                <option value="{{ $n }}"></option>
                                            @endfor
                                        </datalist>
                                    </td>
                                @endforeach

                                <!-- Nilai Kuis per kolom -->
                                @foreach($kuisList as $kuis)
                                    @php
                                        $nk = $r['nilai_kuis'][$kuis->id] ?? null;
                                        $nilai = $nk['nilai'] ?? null;
                                    @endphp
                                    <td class="px-4 py-3.5 text-center">
                                        @if(!is_null($nilai))
                                            <span class="inline-block px-2.5 py-1 rounded-lg text-sm font-bold
                                                {{ $nilai >= 80 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' :
                                                   ($nilai >= 60 ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' :
                                                   'bg-red-500/10 text-red-700 dark:text-red-400') }}">
                                                {{ rtrim(rtrim(number_format((float) $nilai, 2, '.', ''), '0'), '.') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                                        @endif
                                    </td>
                                @endforeach

                                <!-- Rata-rata Tugas -->
                                <td class="px-4 py-3.5 text-center">
                                    @if(!is_null($r['rata_tugas']))
                                        <span class="font-bold text-amber-600 dark:text-amber-400">{{ rtrim(rtrim(number_format((float)$r['rata_tugas'], 2, '.', ''), '0'), '.') }}</span>
                                    @else
                                        <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                                    @endif
                                </td>

                                <!-- Rata-rata Kuis -->
                                <td class="px-4 py-3.5 text-center">
                                    @if(!is_null($r['rata_kuis']))
                                        <span class="font-bold text-violet-600 dark:text-violet-400">{{ rtrim(rtrim(number_format((float)$r['rata_kuis'], 2, '.', ''), '0'), '.') }}</span>
                                    @else
                                        <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                                    @endif
                                </td>

                                <!-- Nilai Akhir -->
                                <td class="px-4 py-3.5 text-center">
                                    @if(!is_null($r['nilai_akhir']))
                                        @php $na = $r['nilai_akhir']; @endphp
                                        <span class="inline-block px-3 py-1.5 rounded-xl text-sm font-extrabold
                                            {{ $na >= 80 ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' :
                                               ($na >= 60 ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800' :
                                               'bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800') }}">
                                            {{ (int) round($na) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 3 + $tugasList->count() + $kuisList->count() }}" class="py-16 text-center text-slate-400 dark:text-slate-500">
                                    Belum ada siswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-900/40 flex items-center gap-3 justify-end">
                @if(session('success'))
                    <span class="text-sm text-emerald-600 font-semibold mr-auto">{{ session('success') }}</span>
                @endif
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('nilai.rekap', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-colors">Batal</a>
            </div>
        </form>
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500/20 border border-emerald-300 dark:border-emerald-700 inline-block"></span> ≥ 80 (Baik)</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500/20 border border-amber-300 dark:border-amber-700 inline-block"></span> 60–79 (Cukup)</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-500/20 border border-red-300 dark:border-red-700 inline-block"></span> &lt; 60 (Kurang)</span>
            <span class="ml-auto font-semibold text-amber-600">T = Tugas &nbsp;&bull;&nbsp;</span>
            <span class="font-semibold text-violet-600">K = Kuis</span>
        </div>
    </div>
</x-app-layout>
