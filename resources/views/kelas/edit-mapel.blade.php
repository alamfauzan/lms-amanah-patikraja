<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.show', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Edit Pengampu Mata Pelajaran</h2>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-850 dark:text-slate-100">Edit Pengampu — {{ $kelas->nama_kelas }}</h3>
            </div>
            
            <form method="POST" action="{{ route('kelas.mapel.update', [$kelas->id, $link->id]) }}" class="px-8 py-6 space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Mata Pelajaran</label>
                    <input type="text" disabled value="{{ $link->mataPelajaran->nama_mapel }} ({{ $link->mataPelajaran->kode_mapel }})" 
                           class="w-full px-4 py-2.5 bg-slate-105 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl text-sm font-medium cursor-not-allowed">
                </div>
                
                <div>
                    <label for="guru_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Guru Pengampu <span class="text-red-500">*</span></label>
                    <select id="guru_id" name="guru_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-805 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Pilih Guru Pengampu</option>
                        @foreach($allTeachers as $guru)
                            <option value="{{ $guru->id }}" {{ (old('guru_id') ?? $link->guru_id) == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('kelas.show', $kelas->id) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-350 text-sm font-semibold rounded-xl transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
