@php
    $role = auth()->user()->roles->first()?->name ?? 'siswa';
    
    // Define menus based on roles
    $menus = [];
    if ($role === 'admin') {
        $menus = [
            ['name' => 'Dashboard',     'route' => 'dashboard',        'icon' => 'grid'],
            ['name' => 'Data Guru',     'route' => 'admin.guru.index',  'icon' => 'users-guru'],
            ['name' => 'Data Siswa',    'route' => 'admin.siswa.index', 'icon' => 'users-siswa'],
            ['name' => 'Kelas',         'route' => 'kelas.index',      'icon' => 'book-open'],
            ['name' => 'Mata Pelajaran','route' => 'admin.mapel.index', 'icon' => 'book-open'],
            ['name' => 'Tahun Ajaran',  'route' => 'admin.tahun-ajaran.index', 'icon' => 'clock'],
            ['name' => 'Laporan',       'route' => 'admin.laporan.index',       'icon' => 'document-report'],
            ['name' => 'Pengaturan',    'route' => 'admin.pengaturan.index',    'icon' => 'cog'],
        ];
    } elseif ($role === 'guru') {
        $menus = [
            ['name' => 'Dashboard',  'route' => 'dashboard',    'icon' => 'grid'],
            ['name' => 'Kelas Saya', 'route' => 'kelas.index',  'icon' => 'book-open'],
            ['name' => 'Jadwal',     'route' => 'jadwal.index', 'icon' => 'calendar'],
            ['name' => 'Nilai',      'route' => 'nilai.guru',   'icon' => 'star'],
        ];
    } else { // siswa
        $menus = [
            ['name' => 'Dashboard', 'route' => 'dashboard',              'icon' => 'grid'],
            ['name' => 'Kelas',     'route' => 'kelas.index',            'icon' => 'book-open'],
            ['name' => 'Tugas',     'route' => 'tugas.index',            'icon' => 'clipboard-list'],
            ['name' => 'Kuis',      'route' => 'kuis.index',             'icon' => 'puzzle'],
            ['name' => 'Nilai',     'route' => 'nilai.siswa',            'icon' => 'star'],
            ['name' => 'Jadwal',    'route' => 'jadwal.index',           'icon' => 'calendar'],
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
<aside :class="[
           sidebarOpen ? 'translate-x-0' : '-translate-x-full',
           sidebarCollapsed ? 'md:w-20' : 'md:w-64'
       ]"
       class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-[#1c1c1e] text-slate-400 transition-all duration-300 ease-in-out md:translate-x-0 md:fixed md:top-4 md:left-4 md:bottom-4 md:h-auto md:rounded-2xl border-r border-zinc-800 md:border md:border-zinc-800/60 shadow-2xl overflow-hidden shrink-0">
    
    <!-- Brand Header -->
    <div :class="sidebarCollapsed ? 'md:px-2 md:justify-center' : 'md:px-6 md:justify-between'"
         class="flex items-center gap-3 h-16 border-b border-zinc-800/60 px-6 justify-between shrink-0 transition-all duration-200">
        
        <!-- LMS Logo & Name (Hidden when collapsed on desktop) -->
        <div x-show="!sidebarCollapsed" 
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="flex items-center gap-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-500 to-indigo-500 text-white font-bold text-base shadow-lg shadow-blue-500/20">
                L
            </div>
            <span class="font-extrabold text-base text-white tracking-wider uppercase">LMS</span>
        </div>

        <!-- Desktop Toggle Button -->
        <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                class="hidden md:flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-white hover:bg-zinc-800/60 rounded-lg transition duration-200 focus:outline-none">
            <svg x-show="!sidebarCollapsed" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <svg x-show="sidebarCollapsed" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>



    <!-- Navigation Menu Items -->
    <nav :class="sidebarCollapsed ? 'md:px-2' : 'md:px-4'"
         class="flex-1 py-4 px-4 space-y-1.5 overflow-y-auto custom-scrollbar transition-all duration-200">
        @foreach($menus as $menu)
            @php
                $isActive = $menu['route'] !== '#' && request()->routeIs($menu['route']) 
                    || ($menu['route'] === 'nilai.guru'  && request()->routeIs('nilai.*'))
                    || ($menu['route'] === 'nilai.siswa' && request()->routeIs('nilai.*'));
                $url = $menu['route'] !== '#' ? route($menu['route']) : 'javascript:void(0):';
            @endphp
            <a href="{{ $url }}" 
               :class="sidebarCollapsed ? 'md:justify-center md:px-0 md:w-12 md:h-12 md:mx-auto' : 'md:px-4 md:py-3'"
               class="group flex items-center gap-3.5 rounded-xl text-sm font-medium px-4 py-3 transition-all duration-200 hover:text-white hover:bg-zinc-850/60 {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/15' : 'text-neutral-400' }}">
                
                <!-- Icons -->
                @if($menu['icon'] === 'grid')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="3" width="7" height="7" rx="1.5" />
                        <rect x="3" y="14" width="7" height="7" rx="1.5" />
                        <rect x="14" y="14" width="7" height="7" rx="1.5" />
                    </svg>
                @elseif($menu['icon'] === 'users-guru')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                @elseif($menu['icon'] === 'users-siswa')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @elseif($menu['icon'] === 'book-open')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                @elseif($menu['icon'] === 'clipboard-list')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                @elseif($menu['icon'] === 'puzzle')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <!-- Back sheet -->
                        <path d="M8 7V5a2 2 0 012-2h7.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a2 2 0 01-2 2h-2" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Front sheet -->
                        <path d="M4 9h6.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V20a2 2 0 01-2 2H4a2 2 0 01-2-2V11a2 2 0 012-2z" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Question Mark -->
                        <path d="M6.5 13.5a1.5 1.5 0 013 0c0 1-1.5 1.5-1.5 1.5m0 2.5h.01" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                @elseif($menu['icon'] === 'star')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.246.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.773-.564-.375-1.81.588-1.81h4.906a1 1 0 00.95-.69l1.519-4.674z" />
                    </svg>
                @elseif($menu['icon'] === 'calendar')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                    </svg>
                @elseif($menu['icon'] === 'clock')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @elseif($menu['icon'] === 'document-report')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                @elseif($menu['icon'] === 'cog')
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @else
                    <!-- Default User Icon -->
                    <svg class="w-5 h-5 transition-transform group-hover:scale-105 duration-200 shrink-0 {{ $isActive ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
                
                <span :class="sidebarCollapsed ? 'md:hidden' : 'block'" 
                      class="transition-opacity duration-200">{{ $menu['name'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- Logout Button -->
    <div :class="sidebarCollapsed ? 'md:p-2' : 'md:p-4'"
         class="border-t border-zinc-800/60 shrink-0 p-4 transition-all duration-200">
        <form method="POST" action="{{ route('logout') }}" 
              :class="sidebarCollapsed ? 'md:w-auto md:flex md:justify-center' : 'md:w-full'"
              class="w-full">
            @csrf
            <button type="submit" 
                    :class="sidebarCollapsed ? 'md:w-12 md:h-12 md:p-0 md:justify-center md:mx-auto' : 'md:w-full md:px-4 md:py-2.5'"
                    class="group flex items-center gap-2.5 bg-zinc-900/40 hover:bg-red-500/10 text-neutral-400 hover:text-red-400 border border-zinc-800 hover:border-red-500/20 rounded-xl text-sm font-medium w-full px-4 py-2.5 justify-center transition-all duration-200">
                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105 duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span :class="sidebarCollapsed ? 'md:hidden' : 'block'" 
                      class="transition-opacity duration-200">Logout</span>
            </button>
        </form>
    </div>
</aside>
