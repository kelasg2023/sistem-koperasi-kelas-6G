<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') — {{ config('app.name', 'FreshMarket') }}@else{{ config('app.name', 'FreshMarket') }}@endif</title>
    <meta name="description" content="@yield('meta_description', config('app.name', 'FreshMarket'))">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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
    @if(!View::hasSection('no-sidebar'))
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-[90] hidden lg:hidden transition-opacity" onclick="toggleSidebar(false)"></div>
        {{-- SIDEBAR --}}
        @include('templates.sidebar')
    @endif

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="flex-1 flex flex-col min-h-screen w-full {{ !View::hasSection('no-sidebar') ? 'lg:ml-[220px]' : '' }} transition-all duration-300">

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

        // ==========================================
        // Set User ID for Broadcasting
        // ==========================================
        @if(session()->has('user'))
            window.userId = {{ session('user.id_users') ?? 'null' }};
        @endif

        // ==========================================
        // Trait/Helper Frontend (Global Function)
        // Konversi Waktu dari GMT (UTC) ke Local Time
        // ==========================================
        window.formatDateFromGMT = function(dateString) {
            if (!dateString) return '-';
            
            // Tambahkan 'Z' agar Javascript mendeteksi sebagai UTC (GMT)
            // Jika backend (Laravel) mengembalikan "2026-07-06 10:00:00" tanpa Z
            let utcString = dateString;
            if (!utcString.endsWith('Z') && !utcString.includes('+')) {
                utcString = utcString.replace(' ', 'T') + 'Z';
            }
            
            const date = new Date(utcString);
            
            // Konversi ke format lokal (contoh: Waktu Indonesia)
            let formatted = date.toLocaleDateString('id-ID', {
                day: 'numeric', 
                month: 'short', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit',
                timeZoneName: 'shortOffset'
            });
            
            // Ganti offset standar dengan singkatan zona waktu Indonesia
            formatted = formatted.replace('GMT+7', 'WIB')
                                 .replace('GMT+8', 'WITA')
                                 .replace('GMT+9', 'WIT');
                                 
            return formatted;
        };
    </script>
    @stack('scripts')
</body>
@endif

    @include('templates.toast')
</html>