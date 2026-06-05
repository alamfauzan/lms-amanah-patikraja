<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Jadwal Pelajaran</h2>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('jadwal.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Jadwal
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @forelse($hariList as $hariNum => $hariNama)
            @php $jadwalHari = $jadwalByHari->get($hariNum, collect()); @endphp
            @if($jadwalHari->isNotEmpty())
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-extrabold text-xs">
                            {{ substr($hariNama, 0, 3) }}
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $hariNama }}</h3>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-500/10 px-2 py-0.5 rounded-full">{{ $jadwalHari->count() }} jadwal</span>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($jadwalHari as $j)
                            <div class="px-6 py-4 flex items-center gap-4">
                                {{-- Time --}}
                                <div class="text-center w-16 shrink-0">
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</p>
                                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</p>
                                </div>

                                <div class="w-px h-10 bg-slate-200 dark:bg-slate-700 shrink-0"></div>

                                {{-- Subject --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">
                                        {{ $j->mataPelajaran->nama_mapel }}
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $j->kelas->nama_kelas }}
                                        @if(!auth()->user()->hasRole('guru')) • {{ $j->guru->name }} @endif
                                        @if($j->ruangan) • {{ $j->ruangan }} @endif
                                    </p>
                                </div>

                                @if(auth()->user()->hasRole('admin'))
                                    <form method="POST" action="{{ route('jadwal.destroy', $j->id) }}" onsubmit="return confirm('Hapus jadwal ini?')" class="shrink-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">Belum ada jadwal</h3>
                <p class="text-sm text-slate-400">Jadwal yang ditambahkan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
