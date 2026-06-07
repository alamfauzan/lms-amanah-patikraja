<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.guru.index') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Tambah Guru Baru</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-slate-100">Informasi Guru</h3>
                <p class="text-sm text-slate-400 dark:text-slate-500 mt-0.5">Isi data akun untuk guru baru.</p>
            </div>

            <form method="POST" action="{{ route('admin.guru.store') }}" class="px-8 py-6 space-y-6">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Ahmad Fauzan, S.Pd."
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="Contoh: ahmad@lms.com"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required
                           placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi password"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200">
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Guru
                    </button>
                    <a href="{{ route('admin.guru.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
