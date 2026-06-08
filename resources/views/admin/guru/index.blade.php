<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Data Guru</h2>
            <a href="{{ route('admin.guru.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Guru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Empty State --}}
        @if($gurus->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">Belum ada data guru</h3>
                <p class="text-sm text-slate-400 dark:text-slate-500">
                    Klik tombol "Tambah Guru" untuk mendaftarkan guru baru.
                </p>
            </div>
        @else
            {{-- Table --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200/80 dark:border-slate-700/80 text-slate-500 dark:text-slate-400">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Tanggal Ditambahkan</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-400">
                            @foreach($gurus as $guru)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0 shadow-inner">
                                                {{ strtoupper(substr($guru->name, 0, 2)) }}
                                            </div>
                                            <span>{{ $guru->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $guru->email }}</td>
                                    <td class="px-6 py-4 text-slate-400 dark:text-slate-500">{{ $guru->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                               class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all duration-200"
                                               title="Edit Guru">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.guru.destroy', $guru->id) }}" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200"
                                                        title="Hapus Guru">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($gurus->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $gurus->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
