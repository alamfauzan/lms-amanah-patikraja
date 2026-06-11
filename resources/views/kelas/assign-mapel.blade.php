<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kelas.show', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Tugaskan Mata Pelajaran</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-850 dark:text-slate-100">Assign Mapel & Guru — {{ $kelas->nama_kelas }}</h3>
            </div>
            
            <form method="POST" action="{{ route('kelas.mapel.assign', $kelas->id) }}" class="px-8 py-6 space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="mata_pelajaran_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select id="mata_pelajaran_id" name="mata_pelajaran_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($allSubjects as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="guru_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Guru Pengampu <span class="text-red-500">*</span></label>
                        <select id="guru_id" name="guru_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Pilih Guru Pengampu</option>
                            @foreach($allTeachers as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                        @error('guru_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 mt-2">
                    <h5 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">Atur Jadwal Pelajaran (Opsional)</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label for="hari" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Hari</label>
                            <select id="hari" name="hari" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">Pilih Hari</option>
                                <option value="1" {{ old('hari') == 1 ? 'selected' : '' }}>Senin</option>
                                <option value="2" {{ old('hari') == 2 ? 'selected' : '' }}>Selasa</option>
                                <option value="3" {{ old('hari') == 3 ? 'selected' : '' }}>Rabu</option>
                                <option value="4" {{ old('hari') == 4 ? 'selected' : '' }}>Kamis</option>
                                <option value="5" {{ old('hari') == 5 ? 'selected' : '' }}>Jumat</option>
                                <option value="6" {{ old('hari') == 6 ? 'selected' : '' }}>Sabtu</option>
                                <option value="7" {{ old('hari') == 7 ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('hari')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="jam_mulai" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jam Selesai</label>
                            <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div class="col-span-2">
                            <label for="ruangan" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Ruangan</label>
                            <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}" placeholder="Lab IPA, Kelas 7A, dll"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            @error('ruangan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                        Tugaskan & Simpan
                    </button>
                    <a href="{{ route('kelas.show', $kelas->id) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
