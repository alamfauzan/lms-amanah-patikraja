<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg text-slate-800 dark:text-slate-205 leading-tight flex items-center gap-2">
            <span>{{ __('Dashboard') }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-extrabold uppercase tracking-wide">
                {{ auth()->user()->roles->first()?->name ?? 'User' }}
            </span>
        </h2>
    </x-slot>

    @php
        $role = auth()->user()->roles->first()?->name ?? 'siswa';
    @endphp

    <div class="py-4">
        @if($role === 'admin')
            @include('dashboards.admin')
        @elseif($role === 'guru')
            @include('dashboards.guru')
        @else
            @include('dashboards.siswa')
        @endif
    </div>
</x-app-layout>
