<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('jadwal.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Tambah Jadwal</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Detail Jadwal</h3>
            </div>
            <form method="POST" action="{{ route('jadwal.store') }}" class="px-8 py-6 space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="kelas_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kelas <span class="text-red-500">*</span></label>
                        <select id="kelas_id" name="kelas_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ (old('kelas_id') ?? $selectedKelasId ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="mata_pelajaran_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Pilih Mapel</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ (old('mata_pelajaran_id') ?? $selectedMapelId ?? '') == $m->id ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="guru_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Guru Pengampu <span class="text-red-500">*</span></label>
                    <select id="guru_id" name="guru_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Pilih Guru</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}" {{ (old('guru_id') ?? $selectedGuruId ?? '') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="hari" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Hari <span class="text-red-500">*</span></label>
                    <select id="hari" name="hari" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Pilih Hari</option>
                        @foreach($hariList as $num => $nama)
                            <option value="{{ $num }}" {{ old('hari') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('hari')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="ruangan" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Ruangan (opsional)</label>
                    <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}" placeholder="Contoh: Lab IPA, Kelas 7A"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Jadwal
                    </button>
                    <a href="{{ route('jadwal.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
