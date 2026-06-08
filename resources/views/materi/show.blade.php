<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2.5 min-w-0">
            <a href="{{ route('kelas.pertemuan.index', [$materi->kelas->id, 'mapel_id' => $materi->pertemuan->mata_pelajaran_id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="min-w-0">
                <h2 class="font-bold text-sm sm:text-base md:text-lg text-slate-800 dark:text-slate-100 truncate max-w-[150px] xs:max-w-[200px] sm:max-w-md md:max-w-xl" title="{{ $materi->judul }}">{{ $materi->judul }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Page Actions Row --}}
        @if(auth()->user()->hasAnyRole(['admin','guru']))
            <div class="flex justify-end gap-2 mb-2">
                <a href="{{ route('materi.edit', $materi->id) }}"
                   class="px-3.5 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition duration-200 shadow-sm">
                    Edit
                </a>
                <form action="{{ route('materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold rounded-xl border border-red-200/40 dark:border-red-900/40 transition duration-200 shadow-sm">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <!-- Header Meta -->
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($materi->guru->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $materi->guru->name }}</p>
                        <p class="text-[11px] text-slate-400">Dipublikasikan pada {{ $materi->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 space-y-6">
                {{-- 1. Text Content Section (If present) --}}
                @if(!empty($materi->konten))
                    <div class="prose dark:prose-invert max-w-none text-slate-750 dark:text-slate-300 leading-relaxed font-sans px-2">
                        {!! \Illuminate\Support\Str::markdown($materi->konten) !!}
                    </div>
                @endif

                {{-- Divider if both exist --}}
                @if(!empty($materi->konten) && !empty($materi->file_path))
                    <hr class="border-slate-100 dark:border-slate-800/80 my-6">
                @endif

                {{-- 2. File Attachment Section (If present) --}}
                @if(!empty($materi->file_path))
                    @php
                        $isVideo = $materi->tipe === 'video';
                        $isPdf = \Illuminate\Support\Str::endsWith(strtolower($materi->file_path), '.pdf');
                    @endphp

                    @if($isVideo)
                        <!-- Video Embed Card -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-slate-850 dark:text-slate-200">Video Pembelajaran</h4>
                            <div class="relative rounded-2xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-800 bg-black">
                                <video class="w-full aspect-video focus:outline-none" controls>
                                    <source src="{{ asset('storage/' . $materi->file_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran tag video.
                                </video>
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ basename($materi->file_path) }}</p>
                                    <p class="text-[10px] text-slate-405">Gunakan tombol pemutar video untuk mulai menonton.</p>
                                </div>
                                <a href="{{ asset('storage/' . $materi->file_path) }}" download
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-400 text-xs font-semibold rounded-lg transition duration-200 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh
                                </a>
                            </div>
                        </div>
                    @elseif($isPdf)
                        <!-- Inline PDF Viewer -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-slate-850 dark:text-slate-200">Pratinjau Dokumen PDF</h4>
                            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm bg-white dark:bg-slate-950">
                                <iframe src="{{ asset('storage/' . $materi->file_path) }}" class="w-full h-[600px] border-0" type="application/pdf"></iframe>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ basename($materi->file_path) }}</p>
                                    <p class="text-[10px] text-slate-405 mt-0.5">Jika dokumen tidak tampil, pastikan browser Anda mendukung peninjauan PDF secara langsung atau Anda dapat mengunduh berkas di samping.</p>
                                </div>
                                <a href="{{ asset('storage/' . $materi->file_path) }}" download
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition duration-200 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh PDF
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- General Document Download Card -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-slate-850 dark:text-slate-200">Lampiran Berkas</h4>
                            <div class="max-w-lg mx-auto py-4">
                                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 text-center space-y-4 shadow-inner">
                                    <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-500 mx-auto">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base truncate px-4">{{ basename($materi->file_path) }}</h4>
                                        <p class="text-xs text-slate-400 mt-1">Silakan unduh berkas lampiran di bawah ini untuk mempelajari materi pelajaran.</p>
                                    </div>
                                    <div class="pt-2">
                                        <a href="{{ asset('storage/' . $materi->file_path) }}" download
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-sm shadow-lg shadow-amber-500/10 hover:shadow-amber-500/25 transition duration-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Unduh Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif


            </div>
        </div>
    </div>
</x-app-layout>
