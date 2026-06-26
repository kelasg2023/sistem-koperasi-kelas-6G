{{-- resources/views/templates/sidebar.blade.php --}}
<div class="flex flex-col h-full">

    {{-- Brand (hanya muncul di sidebar mobile karena di desktop brand ada di navbar) --}}
    <div class="flex items-center gap-3 h-16 px-5 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white text-sm font-bold">
            {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
        </span>
        <span class="font-semibold text-gray-900 dark:text-white">{{ config('app.name', 'App') }}</span>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        {{-- Helper macro: active class --}}
        @php
            $active = fn(string $route) =>
                request()->routeIs($route)
                    ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-medium'
                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100';
        @endphp

        {{-- Group: Utama --}}
        <p class="px-3 pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Utama
        </p>

        <a href="{{ url('/') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors duration-150 {{ $active('beranda') }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Beranda</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors duration-150 {{ $active('dashboard') }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Group: Manajemen --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Manajemen
        </p>

        <a href="#"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors duration-150 {{ $active('users.*') }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Pengguna</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors duration-150 {{ $active('reports.*') }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Laporan</span>
        </a>

        {{-- Group: Sistem --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Sistem
        </p>

        <a href="#"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors duration-150 {{ $active('settings.*') }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Pengaturan</span>
        </a>

    </nav>

    {{-- Footer sidebar --}}
    <div class="flex-shrink-0 px-4 py-3 border-t border-gray-200 dark:border-gray-800">
        <p class="text-xs text-gray-400 dark:text-gray-600 text-center">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>

</div>
