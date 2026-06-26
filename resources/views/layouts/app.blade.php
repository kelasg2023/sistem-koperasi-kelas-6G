<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <title>@hasSection('title')@yield('title') — {{ config('app.name', 'App') }}@else{{ config('app.name', 'App') }}@endif</title>
    <meta name="description" content="@yield('meta_description', config('app.name', 'App'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Vite: CSS & JS (otomatis HMR saat dev, manifest saat production) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Slot untuk head tambahan per-halaman --}}
    @stack('head')
</head>

{{--
    Logika "no-chrome":
    Jika @section('no-chrome') didefinisikan di child view → halaman full-page (login, register, landing, dll.)
    Jika tidak → tampilkan layout lengkap dengan navbar + sidebar
--}}
@hasSection('no-chrome')
<body class="h-full antialiased">
    {{-- ===== FULL-PAGE / NO-CHROME MODE ===== --}}
    @yield('content')

    @stack('scripts')
</body>
@else
<body class="h-full antialiased bg-gray-50 dark:bg-gray-950">

    {{-- ===== APP CHROME MODE (Navbar + Sidebar + Content) ===== --}}

    {{-- Sidebar overlay untuk mobile --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden hidden"
         onclick="toggleSidebar()">
    </div>

    <div class="flex h-full">

        {{-- ===== SIDEBAR ===== --}}
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out
                      lg:relative lg:translate-x-0 lg:flex lg:flex-shrink-0
                      bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg lg:shadow-none">
            @include('templates.sidebar')
        </aside>

        {{-- ===== MAIN WRAPPER (Navbar + Content) ===== --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- ===== NAVBAR ===== --}}
            <header class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-sm">
                @include('templates.navbar')
            </header>

            {{-- ===== PAGE CONTENT ===== --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>{{-- end main wrapper --}}

    </div>{{-- end flex container --}}

    {{-- Sidebar toggle script (mobile) --}}
    <script>
        function toggleSidebar() {
            const sidebar  = document.getElementById('sidebar');
            const overlay  = document.getElementById('sidebar-overlay');
            const isOpen   = !sidebar.classList.contains('-translate-x-full');

            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        }

        // Tutup sidebar saat resize ke desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar-overlay').classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
@endif
</html>
