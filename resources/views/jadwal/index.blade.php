<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Jadwal Pelajaran</h2>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('jadwal.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Jadwal
                </a>
            @endif
        </div>
    </x-slot>

    <style>
        .jadwal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            min-width: 840px;
        }
        .jadwal-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            min-width: 840px;
        }
    </style>

    <div class="space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">

                {{-- Day Header --}}
                @php
                    $shortDays = [1=>'SEN',2=>'SEL',3=>'RAB',4=>'KAM',5=>'JUM',6=>'SAB',7=>'MIN'];
                    $today = (int) now()->dayOfWeekIso;
                @endphp
                <div class="jadwal-header border-b border-slate-100 dark:border-slate-800">
                    @foreach($hariList as $num => $nama)
                        @php $isToday = ($num === $today); @endphp
                        <div class="py-3 text-center {{ $isToday ? 'border-b-2 border-indigo-500' : '' }}">
                            <span class="text-xs font-extrabold uppercase tracking-widest
                                {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                                {{ $shortDays[$num] ?? substr($nama,0,3) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Schedule Cards --}}
                @php
                    $colors = [
                        0 => ['bg'=>'#EFF6FF','border'=>'#BFDBFE','text'=>'#1e3a5f','time'=>'#3B82F6','icon'=>'#60A5FA'],
                        1 => ['bg'=>'#FDF2F8','border'=>'#F9A8D4','text'=>'#5b1a36','time'=>'#EC4899','icon'=>'#F472B6'],
                        2 => ['bg'=>'#F0FDF4','border'=>'#86EFAC','text'=>'#14532d','time'=>'#16A34A','icon'=>'#4ADE80'],
                        3 => ['bg'=>'#FFFBEB','border'=>'#FDE68A','text'=>'#451a03','time'=>'#D97706','icon'=>'#FBBF24'],
                        4 => ['bg'=>'#F5F3FF','border'=>'#C4B5FD','text'=>'#2e1065','time'=>'#7C3AED','icon'=>'#A78BFA'],
                        5 => ['bg'=>'#ECFEFF','border'=>'#A5F3FC','text'=>'#083344','time'=>'#0891B2','icon'=>'#22D3EE'],
                    ];
                @endphp
                <div class="jadwal-grid divide-x divide-slate-100 dark:divide-slate-800" style="min-height: 280px;">
                    @foreach($hariList as $num => $nama)
                        @php $jadwalHari = $jadwalByHari->get($num, collect()); @endphp
                        <div style="padding: 12px; display: flex; flex-direction: column; gap: 10px;">
                            @forelse($jadwalHari as $j)
                                @php
                                    $c = $colors[$j->mata_pelajaran_id % count($colors)];
                                @endphp
                                <div class="group relative rounded-xl text-xs transition-all hover:shadow-md"
                                     style="background:{{ $c['bg'] }}; border:1px solid {{ $c['border'] }}; padding:10px 10px 8px; color:{{ $c['text'] }};">

                                    @if(auth()->user()->hasRole('admin'))
                                        <form method="POST" action="{{ route('jadwal.destroy', $j->id) }}"
                                              onsubmit="return confirm('Hapus jadwal ini?')"
                                              style="position:absolute;top:6px;right:6px;opacity:0;transition:opacity .15s"
                                              onmouseenter="this.style.opacity='1'" onmouseleave="this.style.opacity='0'">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background:none;border:none;cursor:pointer;padding:2px;color:#94a3b8;">
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <div style="font-weight:700;font-size:12px;line-height:1.3;padding-right:12px;">
                                        {{ $j->mataPelajaran->nama_mapel }}
                                    </div>

                                    <div style="font-size:11px;font-weight:600;color:{{ $c['time'] }};margin-top:4px;">
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                                        @if($j->jam_selesai)
                                            – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                        @endif
                                    </div>

                                    @if($j->guru)
                                        <div style="display:flex;align-items:center;gap:4px;margin-top:6px;font-size:10px;color:{{ $c['icon'] }};font-weight:600;">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {{ \Illuminate\Support\Str::limit($j->guru->name, 14) }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($j->ruangan)
                                        <div style="font-size:9px;opacity:.65;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            🏫 {{ $j->ruangan }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="display:flex;align-items:center;justify-content:center;height:80px;font-size:10px;color:#cbd5e1;font-style:italic;">
                                    Kosong
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
