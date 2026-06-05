<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Notifikasi</h2>
            @if($notifikasi->isNotEmpty())
                <form method="POST" action="{{ route('notifikasi.readAll') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 rounded-lg transition">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-3">
        @if($notifikasi->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">Tidak ada notifikasi</h3>
                <p class="text-sm text-slate-400">Semua notifikasi akan muncul di sini.</p>
            </div>
        @else
            @foreach($notifikasi as $notif)
                @php
                    $icons = [
                        'tugas_baru'    => ['bg' => 'bg-orange-500/10', 'color' => 'text-orange-500', 'emoji' => '📝'],
                        'deadline'      => ['bg' => 'bg-red-500/10',    'color' => 'text-red-500',    'emoji' => '⏰'],
                        'materi_baru'   => ['bg' => 'bg-blue-500/10',   'color' => 'text-blue-500',   'emoji' => '📚'],
                        'kuis_baru'     => ['bg' => 'bg-violet-500/10', 'color' => 'text-violet-500', 'emoji' => '🎯'],
                        'nilai_tersedia'=> ['bg' => 'bg-emerald-500/10','color' => 'text-emerald-500','emoji' => '⭐'],
                        'pengumpulan'   => ['bg' => 'bg-indigo-500/10', 'color' => 'text-indigo-500', 'emoji' => '📬'],
                    ];
                    $icon = $icons[$notif->tipe] ?? ['bg' => 'bg-slate-100', 'color' => 'text-slate-400', 'emoji' => '🔔'];
                @endphp
                <div class="group bg-white dark:bg-slate-900 border {{ $notif->dibaca_at ? 'border-slate-200/80 dark:border-slate-800/80' : 'border-indigo-200 dark:border-indigo-500/30' }} rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="flex items-start gap-4 p-5">
                        <div class="w-10 h-10 rounded-xl {{ $icon['bg'] }} flex items-center justify-center text-lg shrink-0">
                            {{ $icon['emoji'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ $notif->judul }}</h3>
                                @if(!$notif->dibaca_at)
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $notif->pesan }}</p>
                            <p class="text-xs text-slate-400 mt-1.5">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            @if($notif->link)
                                <a href="{{ $notif->link }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors" title="Buka">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('notifikasi.destroy', $notif->id) }}" onsubmit="return confirm('Hapus notifikasi?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="pt-2">{{ $notifikasi->links() }}</div>
        @endif
    </div>
</x-app-layout>
