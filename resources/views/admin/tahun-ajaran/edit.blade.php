<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Edit Tahun Ajaran</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Ubah Informasi Tahun Ajaran</h3>
                <p class="text-sm text-slate-400 dark:text-slate-500 mt-0.5">Ubah detail periode tahun ajaran.</p>
            </div>

            <form method="POST" action="{{ route('admin.tahun-ajaran.update', $year->id) }}" class="px-8 py-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $year->tahun_ajaran) }}" required
                           placeholder="Contoh: 2026/2027 Ganjil"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('tahun_ajaran') border-red-400 @enderror">
                    @error('tahun_ajaran')
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
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.tahun-ajaran.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
