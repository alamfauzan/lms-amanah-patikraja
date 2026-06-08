<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-['Outfit'] antialiased bg-slate-50/50 text-slate-800 dark:bg-slate-900 dark:text-slate-100" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }">
        <div class="min-h-screen flex flex-col md:flex-row bg-slate-50 dark:bg-slate-950">
            <!-- Sidebar Navigation -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div :class="sidebarCollapsed ? 'md:pl-28' : 'md:pl-72'"
                 class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300 ease-in-out">
                <!-- Top Header -->
                <header :class="sidebarCollapsed ? 'md:left-28' : 'md:left-72'"
                        class="fixed top-0 left-0 right-0 h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/85 dark:border-slate-800/85 md:top-4 md:right-4 md:rounded-2xl md:border md:border-slate-200/80 dark:md:border-slate-800/80 md:shadow-sm z-40 transition-all duration-300 ease-in-out">
                    <div class="flex items-center justify-between h-full px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            <!-- Toggle Sidebar Mobile -->
                            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 md:hidden transition duration-200 shrink-0">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            @if (isset($header))
                                <div class="min-w-0 flex-1">
                                    {{ $header }}
                                </div>
                            @endif
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-4 shrink-0">
                            <!-- User Settings Dropdown -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-3.5 focus:outline-none transition duration-150 py-1.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                        <!-- User Info Text (Left of Avatar, right-aligned) -->
                                        <div class="hidden sm:flex flex-col text-right">
                                            <span class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ Auth::user()->name }}</span>
                                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 mt-1 leading-none">
                                                @php
                                                    $role = Auth::user()->roles->first()?->name ?? 'User';
                                                    if ($role === 'siswa') {
                                                        $kelas = Auth::user()->siswaKelas()->first();
                                                        $subtitle = $kelas ? $kelas->nama_kelas : 'Siswa';
                                                    } elseif ($role === 'guru') {
                                                        $subtitle = 'Guru';
                                                    } else {
                                                        $subtitle = 'Admin';
                                                    }
                                                @endphp
                                                {{ $subtitle }}
                                            </span>
                                        </div>

                                        <!-- Avatar with Initials -->
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-zinc-800 to-zinc-950 border border-zinc-700/40 flex items-center justify-center shadow-sm shrink-0">
                                            <span class="font-bold text-xs text-zinc-200 uppercase tracking-wider">
                                                @php
                                                    $words = explode(' ', Auth::user()->name);
                                                    $initials = '';
                                                    if (count($words) >= 2) {
                                                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                                    } else {
                                                        $initials = strtoupper(substr(Auth::user()->name, 0, 2));
                                                    }
                                                @endphp
                                                {{ $initials }}
                                            </span>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-800">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</div>
                                        <div class="mt-2">
                                            @php
                                                if ($role === 'siswa') {
                                                    $badgeText = $kelas ? $kelas->nama_kelas : 'Siswa';
                                                    $badgeClass = 'bg-blue-500/10 text-blue-500 dark:bg-blue-500/20 dark:text-blue-400 border border-blue-500/20';
                                                } elseif ($role === 'guru') {
                                                    $badgeText = 'Guru';
                                                    $badgeClass = 'bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 border border-indigo-500/20';
                                                } else {
                                                    $badgeText = 'Admin';
                                                    $badgeClass = 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/20';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide {{ $badgeClass }}">
                                                {{ $badgeText }}
                                            </span>
                                        </div>
                                    </div>
                                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-350 border-t border-slate-100 dark:border-slate-800">
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto px-4 pt-24 pb-6 md:pl-0 md:pr-4 bg-slate-50/60 dark:bg-slate-905">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
