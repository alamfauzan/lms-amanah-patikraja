<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.tugas.index', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">{{ $tugas->judul }}</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 px-5 py-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl text-sm font-medium text-red-700 dark:text-red-400">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Detail Tugas --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500/10 text-orange-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">{{ $tugas->judul }}</h3>
                            <p class="text-sm text-slate-400 mt-0.5">Dibuat oleh {{ $tugas->guru->name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-5">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Deadline</p>
                            <p class="text-sm font-bold {{ now()->gt($tugas->deadline) ? 'text-red-500' : 'text-slate-800 dark:text-slate-200' }}">{{ $tugas->deadline->format('d M Y') }}</p>
                            <p class="text-xs text-slate-400">{{ $tugas->deadline->format('H:i') }} WIB</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nilai Maks</p>
                            <p class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">{{ $tugas->nilai_maksimum }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                            @if(now()->gt($tugas->deadline))
                                <span class="inline-block px-2.5 py-1 bg-red-500/10 text-red-500 text-xs font-semibold rounded-full">Lewat Deadline</span>
                            @else
                                <span class="inline-block px-2.5 py-1 bg-emerald-500/10 text-emerald-600 text-xs font-semibold rounded-full">Aktif</span>
                            @endif
                        </div>
                    </div>

                    @if($tugas->deskripsi)
                        <div class="prose prose-sm dark:prose-invert max-w-none border-t border-slate-100 dark:border-slate-800 pt-4">
                            {!! nl2br(e($tugas->deskripsi)) !!}
                        </div>
                    @endif
                </div>

                {{-- Siswa: Submit Form --}}
                @if(auth()->user()->hasRole('siswa'))
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                            <h4 class="font-bold text-slate-800 dark:text-slate-100">Pengumpulan Tugas</h4>
                        </div>
                        <div class="p-6">
                            @if($pengumpulan && $pengumpulan->status === 'dinilai')
                                <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-xl p-5 mb-4">
                                    <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-1">✅ Sudah Dinilai</p>
                                    <p class="text-3xl font-extrabold text-emerald-600">{{ $pengumpulan->nilai }} <span class="text-sm font-normal text-emerald-400">/ {{ $tugas->nilai_maksimum }}</span></p>
                                    @if($pengumpulan->feedback)
                                        <p class="text-sm text-emerald-700 dark:text-emerald-400 mt-2 border-t border-emerald-200 dark:border-emerald-500/20 pt-2">💬 {{ $pengumpulan->feedback }}</p>
                                    @endif
                                </div>
                            @elseif($pengumpulan && $pengumpulan->status === 'terkumpul')
                                <div class="bg-blue-50 dark:bg-blue-500/10 rounded-xl p-4 mb-4 text-sm text-blue-700 dark:text-blue-400 font-medium">
                                    📬 Tugas terkumpul pada {{ $pengumpulan->dikumpulkan_at->format('d M Y H:i') }}. Menunggu penilaian.
                                </div>
                            @endif

                            @if(!now()->gt($tugas->deadline))
                                <form method="POST" action="{{ route('tugas.submit', [$kelas->id, $tugas->id]) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Upload File Jawaban</label>
                                        <input type="file" name="file_jawaban" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                                        <p class="text-xs text-slate-400 mt-1">PDF, DOC, PPT, ZIP. Maks 10MB.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Catatan (opsional)</label>
                                        <textarea name="catatan" rows="2" placeholder="Tambahkan catatan untuk guru..."
                                                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none">{{ $pengumpulan?->catatan }}</textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        {{ $pengumpulan ? 'Update Pengumpulan' : 'Kumpulkan Tugas' }}
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-red-500 font-medium">⏰ Deadline telah lewat. Pengumpulan ditutup.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar: Guru actions & pengumpulan list --}}
            <div class="space-y-4">
                @if(auth()->user()->hasAnyRole(['admin','guru']))
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-5">
                        <h4 class="font-bold text-slate-800 dark:text-slate-100 mb-3 text-sm">Pengaturan</h4>
                        <div class="flex gap-2">
                            <a href="{{ route('kelas.tugas.edit', [$kelas->id, $tugas->id]) }}" class="flex-1 text-center px-3 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-lg transition">Edit</a>
                            <form method="POST" action="{{ route('kelas.tugas.destroy', [$kelas->id, $tugas->id]) }}" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Pengumpulan</h4>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-500/10 px-2 py-0.5 rounded-full">{{ $tugas->pengumpulan->where('status','!=','belum')->count() }}</span>
                        </div>
                        @if($tugas->pengumpulan->isEmpty())
                            <div class="p-5 text-xs text-slate-400 text-center">Belum ada yang mengumpulkan.</div>
                        @else
                            <div class="divide-y divide-slate-100 dark:divide-slate-800/50 max-h-80 overflow-y-auto">
                                @foreach($tugas->pengumpulan as $p)
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $p->siswa->name }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $p->status === 'dinilai' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-blue-500/10 text-blue-600' }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </div>
                                        @if($p->status !== 'dinilai')
                                            <form method="POST" action="{{ route('tugas.grade', [$kelas->id, $tugas->id, $p->id]) }}" class="space-y-2">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <input type="number" name="nilai" min="0" max="{{ $tugas->nilai_maksimum }}" placeholder="Nilai" value="{{ $p->nilai }}"
                                                           class="w-20 px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                    <input type="text" name="feedback" placeholder="Feedback..." value="{{ $p->feedback }}"
                                                           class="flex-1 px-2 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                    <button type="submit" class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                                        ✓
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <p class="text-xs text-emerald-600 font-semibold">Nilai: {{ $p->nilai }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
