<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2.5 min-w-0">
            <a href="{{ route('kelas.pertemuan.index', [$kelas->id, 'mapel_id' => $tugas->mata_pelajaran_id]) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0">
                <h2 class="font-bold text-sm sm:text-base md:text-lg text-slate-800 dark:text-slate-100 truncate max-w-[150px] xs:max-w-[200px] sm:max-w-md md:max-w-xl" title="{{ $tugas->judul }}">{{ $tugas->judul }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Page Actions Row --}}
        @if(auth()->user()->hasAnyRole(['admin','guru']))
            <div class="flex justify-end gap-2 mb-2">
                <a href="{{ route('kelas.tugas.edit', [$kelas->id, $tugas->id]) }}"
                   class="px-3.5 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition duration-200 shadow-sm">
                    Edit
                </a>
                <form method="POST" action="{{ route('kelas.tugas.destroy', [$kelas->id, $tugas->id]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-semibold rounded-xl border border-red-200/40 dark:border-red-900/40 transition duration-200 shadow-sm">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-455 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-455 rounded-xl text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <!-- Section 1: Assignment Header Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400">
                        {{ $tugas->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold 
                        {{ now()->gt($tugas->deadline) ? 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400' : 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' }}">
                        {{ now()->gt($tugas->deadline) ? 'Tutup' : 'Aktif' }}
                    </span>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                {{ $tugas->judul }}
            </h1>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 dark:border-slate-800/60 text-sm">
                <!-- Deadline -->
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Batas Pengumpulan</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 {{ now()->gt($tugas->deadline) ? 'text-red-550' : '' }}">
                            {{ $tugas->deadline->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <!-- Maximum Score -->
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nilai Maksimum</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                            {{ $tugas->nilai_maksimum }} Poin
                        </p>
                    </div>
                </div>

                <!-- Teacher -->
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Guru Pengampu</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                            {{ $tugas->guru->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Assignment Instructions Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">
                Instruksi Tugas
            </h2>
            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-400 leading-relaxed text-sm">
                @if($tugas->deskripsi)
                    {!! nl2br(e($tugas->deskripsi)) !!}
                @else
                    <p class="text-xs text-slate-400 italic">Tidak ada instruksi khusus yang diberikan.</p>
                @endif
            </div>

            @if($tugas->file_path)
                <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ basename($tugas->file_path) }}</p>
                            <p class="text-[10px] text-slate-400">Berkas instruksi dari guru</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $tugas->file_path) }}" download class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition shrink-0">
                        Unduh
                    </a>
                </div>
            @endif
        </div>

        <!-- Section 3: Grading Result Card -->
        @if(auth()->user()->hasRole('siswa') && $pengumpulan && $pengumpulan->status === 'dinilai')
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        Hasil Penilaian
                    </h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                        Selesai Dinilai
                    </span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Score Block -->
                    <div class="sm:col-span-1 p-5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl flex flex-col justify-center items-center text-center">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nilai Akhir</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ (float) $pengumpulan->nilai }}</span>
                            <span class="text-xs text-slate-400 font-semibold">/ {{ $tugas->nilai_maksimum }}</span>
                        </div>
                    </div>
                    
                    <!-- Feedback Block -->
                    <div class="sm:col-span-2 p-5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl flex flex-col justify-between gap-4">
                        <div class="prose prose-slate dark:prose-invert max-w-none space-y-2">
                            <p class="text-xs text-slate-600 dark:text-slate-300 italic leading-relaxed">
                                "{{ $pengumpulan->feedback ?? 'Tidak ada catatan tambahan.' }}"
                            </p>
                            @if($pengumpulan->file_jawaban)
                                <div class="pt-2 flex items-center gap-2 border-t border-slate-100 dark:border-slate-800/60">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Berkas Jawaban Anda:</span>
                                    <a href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" download class="inline-flex items-center gap-1.5 text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        {{ basename($pengumpulan->file_jawaban) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-900 flex items-center justify-center font-bold text-[10px] text-indigo-600 dark:text-indigo-400">
                                    {{ strtoupper(substr($tugas->guru->name, 0, 1)) }}
                                </div>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">{{ $tugas->guru->name }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400">
                                Dikumpulkan: {{ $pengumpulan->dikumpulkan_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submission Status Banner for Submitted Tasks -->
        @if(auth()->user()->hasRole('siswa') && $pengumpulan && $pengumpulan->status === 'terkumpul')
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">
                    Status Pengumpulan
                </h2>
                <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap leading-none text-xs">
                            <span class="font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                                Terkirim
                            </span>
                            <span class="text-slate-300 dark:text-slate-700">•</span>
                            <span class="text-slate-400">
                                Dikumpulkan: {{ $pengumpulan->dikumpulkan_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Tugas telah terkirim dan sedang menunggu penilaian dari guru pengampu.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- For Student: Submission Area & Form -->
        @if(auth()->user()->hasRole('siswa') && !now()->gt($tugas->deadline) && (!$pengumpulan || $pengumpulan->status !== 'dinilai'))
            <form method="POST" action="{{ route('tugas.submit', [$kelas->id, $tugas->id]) }}" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm p-6 space-y-6">
                @csrf
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">
                    Unggah Jawaban
                </h2>

                <!-- Section 4: Submission Area (Drag & Drop Component) -->
                <div x-data="{ 
                    files: null, 
                    dragover: false, 
                    hasExistingFile: {{ ($pengumpulan && $pengumpulan->file_jawaban) ? 'true' : 'false' }}, 
                    isDeleted: false 
                }" class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">File Jawaban</label>
                    
                    <!-- Hidden input to tell backend to delete file -->
                    <input type="hidden" name="hapus_file_jawaban" :value="isDeleted ? '1' : '0'">

                    <!-- Drag and Drop Box: hidden if there is a selected file or an active existing file -->
                    <div 
                        x-show="(!files || files.length === 0) && (!hasExistingFile || isDeleted)"
                        @dragover.prevent="dragover = true"
                        @dragleave.prevent="dragover = false"
                        @drop.prevent="dragover = false; files = $event.dataTransfer.files; $refs.fileInput.files = files; $refs.fileInput.dispatchEvent(new Event('change'))"
                        :class="dragover ? 'border-indigo-500 bg-indigo-50/30 dark:bg-indigo-900/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50'"
                        class="border-2 border-dashed rounded-xl p-6 transition-all duration-200 flex flex-col items-center justify-center text-center cursor-pointer relative group"
                    >
                        <input type="file" name="file_jawaban" id="file_jawaban" ref="fileInput" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                               @change="files = $event.target.files"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                               
                        <div class="p-3 bg-white dark:bg-slate-800 rounded-full shadow-sm border border-slate-100 dark:border-slate-700/50 text-slate-400 group-hover:text-indigo-500 transition-colors duration-200 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">
                            <span class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">Pilih berkas</span> atau seret ke sini
                        </p>
                        <p class="text-xs text-slate-400">Mendukung berkas PDF, Word, PPT, atau ZIP. Maksimal 10MB.</p>
                    </div>

                    <!-- Selected File Preview Card (shows when files are picked) -->
                    <div x-show="files && files.length > 0" class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate" x-text="files ? files[0].name : ''"></p>
                                <p class="text-[10px] text-slate-400" x-text="files ? Math.round(files[0].size / 1024) + ' KB' : ''"></p>
                            </div>
                        </div>
                        <button type="button" @click="files = null; $refs.fileInput.value = ''; if (hasExistingFile) isDeleted = true" class="p-1 rounded-md text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Existing Uploaded File Card (shows if page loaded with file and it hasn't been deleted yet) -->
                    @if($pengumpulan && $pengumpulan->file_jawaban)
                        <div x-show="hasExistingFile && !isDeleted && (!files || files.length === 0)" class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ basename($pengumpulan->file_jawaban) }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">Berkas Terunggah</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <a href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" download class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline shrink-0">
                                    Unduh
                                </a>
                                <button type="button" @click="isDeleted = true; if($refs.fileInput) { $refs.fileInput.value = ''; files = null; }" class="text-xs text-red-500 font-bold hover:underline shrink-0">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Section 5: Student Message -->
                <div class="space-y-2">
                    <label for="catatan" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Pesan untuk Guru (Opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Tulis catatan atau pesan Anda di sini..."
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none shadow-sm">{{ $pengumpulan?->catatan }}</textarea>
                </div>

                <!-- Section 6: Action Area -->
                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" name="action" value="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition duration-200 shadow-sm">
                        {{ $pengumpulan ? 'Perbarui Jawaban' : 'Kirim Jawaban' }}
                    </button>
                    <button type="submit" name="action" value="draft" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition duration-200 shadow-sm border border-slate-200/50 dark:border-slate-700/50">
                        Simpan Draft
                    </button>
                </div>
            </form>
        @else
            @if(auth()->user()->hasRole('siswa') && !$pengumpulan)
                <div class="p-4 bg-red-50 border border-red-200 text-xs text-red-600 dark:bg-red-900/20 dark:border-red-900/30 dark:text-red-400 font-medium rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Batas waktu pengumpulan telah lewat. Pengumpulan tugas ditutup.
                </div>
            @endif
        @endif

        <!-- For Teacher / Admin: Submissions & Grading -->
        @if(auth()->user()->hasAnyRole(['admin','guru']))
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">Daftar Pengumpulan</h3>
                        <p class="text-xs text-slate-400 mt-1">Daftar siswa yang telah mengumpulkan tugas ini.</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-900/50">
                        {{ $tugas->pengumpulan->where('status','!=','belum')->count() }} Terkumpul
                    </span>
                </div>

                @if($tugas->pengumpulan->isEmpty())
                    <div class="p-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada siswa yang mengumpulkan tugas ini.</div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @foreach($tugas->pengumpulan as $p)
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200/50 dark:border-slate-700/50">
                                            {{ strtoupper(substr($p->siswa->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 leading-snug">{{ $p->siswa->name }}</p>
                                            <p class="text-[10px] text-slate-400">Dikumpulkan {{ $p->dikumpulkan_at->format('d M Y, H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $p->status === 'dinilai' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' }}">
                                        {{ $p->status === 'dinilai' ? 'Sudah Dinilai' : 'Perlu Dinilai' }}
                                    </span>
                                </div>

                                @if($p->catatan || $p->file_jawaban)
                                    <div class="p-4 bg-slate-50/50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl space-y-3">
                                        @if($p->file_jawaban)
                                            <div class="flex items-center justify-between gap-2 text-xs">
                                                <span class="font-medium text-slate-500">Berkas Jawaban:</span>
                                                <a href="{{ asset('storage/' . $p->file_jawaban) }}" download class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Unduh Berkas
                                                </a>
                                            </div>
                                        @endif
                                        @if($p->catatan)
                                            <div class="text-xs">
                                                <span class="font-medium text-slate-500">Catatan Siswa:</span>
                                                <p class="text-slate-700 dark:text-slate-300 mt-1 italic leading-relaxed">"{{ $p->catatan }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($p->status !== 'dinilai')
                                    <form method="POST" action="{{ route('tugas.grade', [$kelas->id, $tugas->id, $p->id]) }}" class="flex flex-col sm:flex-row gap-3 pt-2">
                                        @csrf
                                        <div class="w-full sm:w-28 shrink-0">
                                            <input type="number" name="nilai" min="0" max="{{ $tugas->nilai_maksimum }}" placeholder="Nilai" required
                                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" name="feedback" placeholder="Feedback/komentar..."
                                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition duration-200 shrink-0 shadow-sm">
                                            Simpan Nilai
                                        </button>
                                    </form>
                                @else
                                    <div class="p-4 bg-emerald-500/5 border border-emerald-500/10 rounded-xl flex items-center justify-between gap-4">
                                        <div class="text-xs min-w-0">
                                            <span class="font-medium text-slate-550 block">Feedback/Komentar Guru:</span>
                                            <p class="text-slate-700 dark:text-slate-300 mt-1 font-semibold truncate">{{ $p->feedback ?? 'Tidak ada feedback.' }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $p->nilai }} <span class="text-xs font-normal text-slate-400">/ {{ $tugas->nilai_maksimum }}</span></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
