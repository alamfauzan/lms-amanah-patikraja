<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Pengaturan Sistem</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl text-sm font-medium text-emerald-700 dark:text-emerald-400 mb-6">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Branding Sekolah</h3>
                <p class="text-sm text-slate-400 dark:text-slate-500 mt-0.5">Atur nama instansi dan judul platform LMS.</p>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="px-8 py-6 space-y-6">
                @csrf

                {{-- Nama Instansi (School Name) --}}
                <div>
                    <label for="school_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Sekolah / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" id="school_name" name="school_name" value="{{ old('school_name', $school_name) }}" required
                           placeholder="Contoh: Madrasah Al-Ilm"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('school_name') border-red-400 @enderror">
                    @error('school_name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul LMS (LMS Name) --}}
                <div>
                    <label for="lms_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Platform LMS <span class="text-red-500">*</span></label>
                    <input type="text" id="lms_name" name="lms_name" value="{{ old('lms_name', $lms_name) }}" required
                           placeholder="Contoh: Al-Ilm Learning System"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('lms_name') border-red-400 @enderror">
                    @error('lms_name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
