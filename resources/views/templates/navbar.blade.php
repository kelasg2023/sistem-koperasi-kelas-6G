{{-- resources/views/templates/navbar.blade.php --}}
<nav class="flex items-center justify-between h-16 px-4 sm:px-6">

    {{-- Kiri: Hamburger (mobile) + Brand --}}
    <div class="flex items-center gap-3">
        {{-- Tombol hamburger — hanya muncul di mobile --}}
        <button type="button"
                id="sidebar-toggle"
                onclick="toggleSidebar()"
                class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg
                       text-gray-500 hover:text-gray-700 hover:bg-gray-100
                       dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800
                       transition-colors duration-150"
                aria-label="Toggle sidebar">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Brand / Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-gray-900 dark:text-white">
            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-600 text-white text-xs font-bold">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </span>
            <span class="hidden sm:inline">{{ config('app.name', 'App') }}</span>
        </a>
    </div>

    {{-- Tengah: Breadcrumb / Page Title --}}
    <div class="hidden md:flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
        @yield('breadcrumb')
    </div>

    {{-- Kanan: Actions --}}
    <div class="flex items-center gap-2">

        {{-- Notifikasi --}}
        <button type="button"
                class="relative inline-flex items-center justify-center p-2 rounded-lg
                       text-gray-500 hover:text-gray-700 hover:bg-gray-100
                       dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800
                       transition-colors duration-150"
                aria-label="Notifikasi">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            {{-- Badge --}}
            <span class="absolute top-1.5 right-1.5 block w-2 h-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"></span>
        </button>

        {{-- User Avatar / Dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150"
                    aria-label="User menu">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-sm font-semibold">
                    {{-- Ganti dengan Auth::user()->name[0] jika sudah ada auth --}}
                    U
                </span>
                <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                 style="display:none">
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil
                </a>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pengaturan
                </a>
                <hr class="my-1 border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>
