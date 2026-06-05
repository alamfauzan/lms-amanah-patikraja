@php
    $role = auth()->user()->roles->first()?->name ?? 'siswa';
    
    // Define menus based on roles
    $menus = [];
    if ($role === 'admin') {
        $menus = [
            ['name' => 'Dashboard',     'route' => 'dashboard',    'icon' => 'grid'],
            ['name' => 'Data Guru',     'route' => '#',            'icon' => 'users-guru'],
            ['name' => 'Data Siswa',    'route' => '#',            'icon' => 'users-siswa'],
            ['name' => 'Kelas',         'route' => 'kelas.index',  'icon' => 'academic-cap'],
            ['name' => 'Mata Pelajaran','route' => '#',            'icon' => 'book-open'],
            ['name' => 'Jadwal',        'route' => 'jadwal.index', 'icon' => 'calendar'],
            ['name' => 'Tahun Ajaran',  'route' => '#',            'icon' => 'clock'],
            ['name' => 'Laporan',       'route' => '#',            'icon' => 'document-report'],
            ['name' => 'Pengaturan',    'route' => '#',            'icon' => 'cog'],
        ];
    } elseif ($role === 'guru') {
        $menus = [
            ['name' => 'Dashboard',  'route' => 'dashboard',    'icon' => 'grid'],
            ['name' => 'Kelas Saya', 'route' => 'kelas.index',  'icon' => 'academic-cap'],
            ['name' => 'Materi',     'route' => '#',            'icon' => 'book-open'],
            ['name' => 'Tugas',      'route' => '#',            'icon' => 'clipboard-list'],
            ['name' => 'Kuis',       'route' => '#',            'icon' => 'puzzle'],
            ['name' => 'Jadwal',     'route' => 'jadwal.index', 'icon' => 'calendar'],
            ['name' => 'Nilai',      'route' => 'nilai.guru',   'icon' => 'chart-bar'],
            ['name' => 'Profil',     'route' => 'profile.edit', 'icon' => 'user'],
        ];
    } else { // siswa
        $menus = [
            ['name' => 'Dashboard', 'route' => 'dashboard',              'icon' => 'grid'],
            ['name' => 'Kelas',     'route' => 'kelas.index',            'icon' => 'academic-cap'],
            ['name' => 'Tugas',     'route' => '#',                      'icon' => 'clipboard-list'],
            ['name' => 'Kuis',      'route' => '#',                      'icon' => 'puzzle'],
            ['name' => 'Nilai',     'route' => 'nilai.siswa',             'icon' => 'chart-bar'],
            ['name' => 'Jadwal',    'route' => 'jadwal.index',           'icon' => 'calendar'],
            ['name' => 'Profil',    'route' => 'profile.edit',           'icon' => 'user'],
        ];
    }
@endphp

<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm md:hidden"></div>

<!-- Sidebar Wrapper -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed md:static inset-y-0 left-0 z-50 flex flex-col w-64 bg-slate-900 border-r border-slate-800 text-slate-300 transition-transform duration-300 ease-in-out md:translate-x-0 h-screen overflow-hidden">
    
    <!-- Logo & Brand Header -->
    <div class="flex items-center gap-3 px-6 h-16 border-b border-slate-800/80 shrink-0">
        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-lg shadow-lg shadow-indigo-500/20">
            M
        </div>
        <div>
            <h1 class="font-extrabold text-sm text-slate-100 tracking-wider leading-none uppercase">Madrasah</h1>
            <span class="text-xxs text-slate-400 font-medium">Al-Ilm Learning System</span>
        </div>
    </div>

    <!-- User Profile Summary (Desktop) -->
    <div class="px-5 py-4 border-b border-slate-800/50 shrink-0">
        <div class="flex items-center gap-3 p-2 bg-slate-850/40 rounded-2xl border border-slate-800/30">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-base shadow-inner">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-200 truncate leading-tight">{{ Auth::user()->name }}</p>
                <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded bg-indigo-550/20 text-indigo-400 text-[9px] font-bold uppercase tracking-wider">
                    {{ $role }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
        @foreach($menus as $menu)
            @php
                $isActive = $menu['route'] !== '#' && request()->routeIs($menu['route']) 
                    || ($menu['route'] === 'nilai.guru'  && request()->routeIs('nilai.*'))
                    || ($menu['route'] === 'nilai.siswa' && request()->routeIs('nilai.*'));
                $url = $menu['route'] !== '#' ? route($menu['route']) : 'javascript:void(0):';
            @endphp
            <a href="{{ $url }}" 
               class="group flex items-center gap-3.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:text-slate-100 hover:bg-slate-800/50 {{ $isActive ? 'bg-indigo-600/90 text-white shadow-lg shadow-indigo-600/15' : 'text-slate-400' }}">
                
                <!-- Icons -->
                @if($menu['icon'] === 'grid')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                @elseif($menu['icon'] === 'users-guru')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                @elseif($menu['icon'] === 'users-siswa')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @elseif($menu['icon'] === 'academic-cap')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                @elseif($menu['icon'] === 'book-open')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                @elseif($menu['icon'] === 'clipboard-list')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                @elseif($menu['icon'] === 'puzzle')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                @elseif($menu['icon'] === 'calendar')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                    </svg>
                @elseif($menu['icon'] === 'chart-bar')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                @elseif($menu['icon'] === 'clock')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @elseif($menu['icon'] === 'document-report')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                @elseif($menu['icon'] === 'cog')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @else
                    <!-- Default User Icon -->
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200 {{ $isActive ? 'text-white' : 'text-slate-450 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
                
                <span>{{ $menu['name'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- Logout Button (Sidebar Bottom) -->
    <div class="p-4 border-t border-slate-800/80 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 bg-slate-800/40 hover:bg-red-500/10 text-slate-400 hover:text-red-400 border border-slate-800 hover:border-red-500/20 rounded-xl text-sm font-medium transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</aside>
