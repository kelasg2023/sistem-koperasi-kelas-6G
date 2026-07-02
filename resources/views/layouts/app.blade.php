<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') — {{ config('app.name', 'FreshMarket') }}@else{{ config('app.name', 'FreshMarket') }}@endif</title>
    <meta name="description" content="@yield('meta_description', config('app.name', 'FreshMarket'))">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

@hasSection('no-chrome')
<body class="font-['Plus_Jakarta_Sans'] bg-[#F6F8F6] text-[#1A1A1A] antialiased">
    @yield('content')
    @stack('scripts')
</body>
@else
<body class="font-['Plus_Jakarta_Sans'] bg-[#F6F8F6] text-[#1A1A1A] flex min-h-screen overflow-x-hidden antialiased">

    {{-- Sidebar Overlay untuk Mobile (satu-satunya, dikelola di sini) --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-[90] hidden lg:hidden transition-opacity" onclick="toggleSidebar(false)"></div>

    {{-- SIDEBAR --}}
    @include('templates.sidebar')

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="flex-1 flex flex-col min-h-screen w-full lg:ml-[220px] transition-all duration-300">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-50 bg-white border-b border-gray-200 h-16 px-4 lg:px-7 flex items-center gap-4">
            @include('templates.navbar')
        </header>

        {{-- KONTEN HALAMAN --}}
        <main class="flex-1">
            @yield('content')
        </main>

    </div>

    <script>
        function toggleSidebar(forceState) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;

            // forceState: true = buka paksa, false = tutup paksa, undefined = toggle otomatis
            const shouldOpen = typeof forceState === 'boolean'
                ? forceState
                : sidebar.classList.contains('-translate-x-full');

            if (shouldOpen) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        window.addEventListener('resize', () => {
            const overlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth >= 1024) {
                if (overlay) overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
@endif
</html>