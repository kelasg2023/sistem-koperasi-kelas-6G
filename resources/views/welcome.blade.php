@extends('layouts.app')

@section('title', 'Koperasi 6G - Beranda')

@section('no-chrome', true)

@section('content')
<div class="min-h-screen bg-[#F6F8F6] dark:bg-gray-950 font-sans flex flex-col justify-between">

    <!-- 1. HEADER / NAVBAR -->
   <header class="sticky top-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shadow-sm px-4 md:px-12 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-[#1A622A] dark:text-emerald-500 text-2xl font-extrabold tracking-tight">Koperasi 6G</span>
        </div>

        <nav id="main-nav" class="hidden lg:flex items-center gap-7 text-[13px] font-semibold text-gray-800 dark:text-gray-300">
            <a href="#" class="nav-link text-[#1A622A] dark:text-emerald-400 border-b-2 border-[#1A622A] dark:border-emerald-400 pb-1">Beranda</a>
            <a href="#produk-kategori" class="nav-link hover:text-[#1A622A] dark:hover:text-emerald-400 transition">Produk</a>
            <a href="#promo-hari-ini" class="nav-link hover:text-[#1A622A] dark:hover:text-emerald-400 transition">Promo</a>
            <a href="#tentang-kami" class="nav-link hover:text-[#1A622A] dark:hover:text-emerald-400 transition">Tentang Kami</a>
            <a href="#lokasi-toko" class="nav-link hover:text-[#1A622A] dark:hover:text-emerald-400 transition">Lokasi Toko</a>
            <a href="#kontak" class="nav-link hover:text-[#1A622A] dark:hover:text-emerald-400 transition">Kontak</a>
        </nav>

        <div class="relative hidden lg:block max-w-[260px] w-full">
            <span class="absolute left-3.5 top-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" 
                   placeholder="Cari sembako..." 
                   class="w-full bg-gray-100 dark:bg-gray-800 pl-10 pr-4 py-2 rounded-full text-xs font-medium outline-none focus:ring-1 focus:ring-[#1A622A] dark:text-white transition" />
        </div>

        <div class="flex items-center gap-4">
            <button class="relative text-gray-700 dark:text-gray-300 hover:text-[#1A622A] focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </button>

            <button class="relative text-gray-700 dark:text-gray-300 hover:text-[#1A622A] focus:outline-none mr-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="absolute -top-1.5 -right-2 bg-[#1A622A] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white">
                    3
                </span>
            </button>

            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="border border-gray-800 dark:border-gray-600 px-5 py-1.5 rounded-full text-xs font-semibold text-gray-900 dark:text-gray-300 hover:bg-gray-50 transition">
                    Masuk
                </a>
            @else
                <a href="#" class="border border-gray-800 dark:border-gray-600 px-5 py-1.5 rounded-full text-xs font-semibold text-gray-900 dark:text-gray-300 hover:bg-gray-50 transition">
                    Masuk
                </a>
            @endif

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="bg-[#1A622A] hover:bg-[#13491f] px-5 py-1.5 rounded-full text-xs font-semibold text-white shadow-sm transition">
                    Daftar
                </a>
            @else
                <a href="#" class="bg-[#1A622A] hover:bg-[#13491f] px-5 py-1.5 rounded-full text-xs font-semibold text-white shadow-sm transition">
                    Daftar
                </a>
            @endif
        </div>
    </header>

    <main class="flex-grow px-4 md:px-12 py-6">
        <div class="bg-[#2D7A42] rounded-[32px] p-8 md:p-12 flex flex-col lg:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <div class="w-full lg:w-1/2 text-white z-10 flex flex-col justify-center">
                <h1 class="text-3xl md:text-[42px] font-bold leading-[1.1] mb-5 tracking-tight">
                    Belanja Bahan Pokok Lebih Hemat Bersama Koperasi 6G
                </h1>
                <p class="text-sm md:text-sm text-white/90 leading-relaxed mb-8 max-w-md font-medium">
                    Nikmati kemudahan belanja sembako kualitas premium dengan harga yang lebih terjangkau bagi seluruh masyarakat.
                </p>
                <div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-[#F5820A] hover:bg-orange-600 active:scale-95 text-white font-semibold px-6 py-2.5 rounded-full text-[13px] transition duration-150">
                        Belanja Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-[45%] flex justify-center lg:justify-end z-10">
               <img src="https://placehold.co/600x400/e2e8f0/1a622a?text=Gambar+Sembako" 
     alt="Bahan Pokok Segar" 
     class="rounded-l-sm rounded-r-[24px] w-full h-[280px] lg:h-[340px] object-cover" />
            </div>

            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-1.5 z-10">
                <span class="w-2 h-2 rounded-full bg-[#13491f]"></span>
                <span class="w-2 h-2 rounded-full bg-white"></span>
                <span class="w-2 h-2 rounded-full bg-white"></span>
            </div>
        </div>

        <section id="produk-kategori" class="mt-12 scroll-mt-24">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-6">
                Belanja per Kategori
            </h2>
            
            <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
                
                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#E8F5EC] dark:bg-emerald-950/40 transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-[#1A622A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Minyak & Lemak</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#FCECDD] dark:bg-amber-950/20 transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Beras & Tepung</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#EDEDED] dark:bg-gray-800 transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Makanan Kaleng</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#8df08d] transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-[#1A622A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Sabun & Kebersihan</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#D4E0D9] transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Minuman</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#FCD8B8] transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-[#cc6a12]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Bumbu Dapur</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#E5E5E5] transition group-hover:scale-105">
                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Mie & Pasta</span>
                </div>

                <div class="flex flex-col items-center text-center cursor-pointer group">
                    <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full flex items-center justify-center bg-[#65d065] transition group-hover:scale-105">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="5" r="2"></circle>
                            <circle cx="12" cy="5" r="2"></circle>
                            <circle cx="19" cy="5" r="2"></circle>
                            <circle cx="5" cy="12" r="2"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                            <circle cx="19" cy="12" r="2"></circle>
                            <circle cx="5" cy="19" r="2"></circle>
                            <circle cx="12" cy="19" r="2"></circle>
                            <circle cx="19" cy="19" r="2"></circle>
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-300 mt-2.5">Sembako Lainnya</span>
                </div>

            </div>
        </section>

         <!-- ==================== 1. PROMO SPESIAL HARI INI ==================== -->
        <section id="promo-hari-ini" class="mt-12 scroll-mt-24">
            <!-- Header Promo -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Promo Spesial Hari Ini</h3>
                    <!-- Countdown Badge -->
                    <div class="bg-[#F5820A] text-white text-xs font-bold px-3 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" />
                        </svg>
                        <span id="promo-timer">08:24:57</span>
                    </div>
                </div>
                <a href="#" class="text-xs md:text-sm font-semibold text-[#2D7A42] hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Grid Promo (4 Kolom) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                
                <!-- Card 1: Minyak Goreng -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <div>
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md z-10">
                            Hemat 20%
                        </span>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3">
                            <img src="https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain" alt="Minyak Goreng">
                        </div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 mb-1.5 line-clamp-1">Minyak Goreng Premium 2L</h4>
                        <div class="flex items-baseline gap-1.5 mb-2">
                            <span class="text-sm font-extrabold text-red-600">Rp 32.500</span>
                            <span class="text-[10px] text-gray-400 line-through">Rp 41.000</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                            <span>Sisa 15 item</span>
                            <span>Terjual 65%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden mb-4">
                            <div class="bg-red-600 h-full rounded-full" style="width: 65%"></div>
                        </div>
                        <button class="w-full py-2 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-xs font-bold rounded-lg transition">
                            Beli Sekarang
                        </button>
                    </div>
                </div>

                <!-- Card 2: Beras -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <div>
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md z-10">
                            Hemat 15%
                        </span>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3">
                            <img src="https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain" alt="Beras">
                        </div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 mb-1.5 line-clamp-1">Beras Pandan Wangi 5kg</h4>
                        <div class="flex items-baseline gap-1.5 mb-2">
                            <span class="text-sm font-extrabold text-red-600">Rp 64.000</span>
                            <span class="text-[10px] text-gray-400 line-through">Rp 75.000</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                            <span>Sisa 8 item</span>
                            <span>Terjual 82%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden mb-4">
                            <div class="bg-red-600 h-full rounded-full" style="width: 82%"></div>
                        </div>
                        <button class="w-full py-2 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-xs font-bold rounded-lg transition">
                            Beli Sekarang
                        </button>
                    </div>
                </div>

                <!-- Card 3: Mie Instan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <div>
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md z-10">
                            Hemat 10%
                        </span>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3">
                            <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain" alt="Mie Instan">
                        </div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 mb-1.5 line-clamp-1">Paket Mie Instan isi 5</h4>
                        <div class="flex items-baseline gap-1.5 mb-2">
                            <span class="text-sm font-extrabold text-red-600">Rp 13.500</span>
                            <span class="text-[10px] text-gray-400 line-through">Rp 15.000</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                            <span>Sisa 24 item</span>
                            <span>Terjual 70%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden mb-4">
                            <div class="bg-red-600 h-full rounded-full" style="width: 70%"></div>
                        </div>
                        <button class="w-full py-2 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-xs font-bold rounded-lg transition">
                            Beli Sekarang
                        </button>
                    </div>
                </div>

                <!-- Card 4: Sarden -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <div>
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md z-10">
                            Hemat 20%
                        </span>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3">
                            <img src="https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain" alt="Sarden">
                        </div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 mb-1.5 line-clamp-1">Sarden Tomat Kaleng</h4>
                        <div class="flex items-baseline gap-1.5 mb-2">
                            <span class="text-sm font-extrabold text-red-600">Rp 8.200</span>
                            <span class="text-[10px] text-gray-400 line-through">Rp 9.500</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                            <span>Sisa 50 item</span>
                            <span>Terjual 30%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden mb-4">
                            <div class="bg-red-600 h-full rounded-full" style="width: 30%"></div>
                        </div>
                        <button class="w-full py-2 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-xs font-bold rounded-lg transition">
                            Beli Sekarang
                        </button>
                    </div>
                </div>

            </div>
        </section>
    </main> <!-- Penutup tag main (jika ada) -->

    <!-- ==================== 2. PRODUK TERLARIS MINGGU INI (Dark Section) ==================== -->
    <section class="bg-[#1A1A1A] dark:bg-black py-12 px-4 sm:px-6 md:px-12 -mx-4 sm:-mx-6 md:-mx-12 mt-12 transition-colors">
        <div class="max-w-7xl mx-auto">
            <!-- Header Kategori Dark -->
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg md:text-xl font-extrabold text-white tracking-tight">Produk Terlaris Minggu Ini</h3>
                <a href="#" class="text-xs md:text-sm font-semibold text-emerald-400 hover:underline flex items-center gap-1">
                    Semua Terlaris
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Grid Terlaris (4 Kolom) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                
                <!-- Card 1: Sayur Segar -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 relative flex flex-col justify-between shadow-md hover:shadow-xl transition group">
                    <!-- Tombol Favorit (Heart) -->
                    <button class="absolute top-3.5 right-3.5 text-gray-400 hover:text-red-500 transition z-10">
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <div>
                        <!-- Container Gambar & Tag -->
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3.5 relative">
                            <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain transition group-hover:scale-105" alt="Sayur Segar">
                            <!-- Floating Tag Badge -->
                            <div class="absolute bottom-2 left-2 flex gap-1 z-10">
                                <span class="bg-[#E8F5EC] dark:bg-emerald-950/50 text-[#2D7A42] dark:text-emerald-400 text-[9px] font-extrabold px-1.5 py-0.5 rounded">Sembako</span>
                                <span class="bg-[#F5820A] text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded">Harga Anggota</span>
                            </div>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-1">Paket Sayur Segar</h4>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 block mb-3">per paket (1-2kg)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-base font-extrabold text-[#2D7A42] dark:text-emerald-400">Rp 45.000</div>
                            <div class="text-[9px] text-gray-400 dark:text-gray-500">Rp 42.500 Member</div>
                        </div>
                        <button class="w-8 h-8 rounded-full bg-[#F5820A] hover:bg-orange-600 text-white flex items-center justify-center shadow transition active:scale-90 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Card 2: Tepung Terigu -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 relative flex flex-col justify-between shadow-md hover:shadow-xl transition group">
                    <button class="absolute top-3.5 right-3.5 text-gray-400 hover:text-red-500 transition z-10">
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <div>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3.5 relative">
                            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain transition group-hover:scale-105" alt="Tepung Terigu">
                            <div class="absolute bottom-2 left-2 flex gap-1 z-10">
                                <span class="bg-[#E8F5EC] dark:bg-emerald-950/50 text-[#2D7A42] dark:text-emerald-400 text-[9px] font-extrabold px-1.5 py-0.5 rounded">Sembako</span>
                                <span class="bg-[#F5820A] text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded">Harga Anggota</span>
                            </div>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-1">Tepung Terigu Protein</h4>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 block mb-3">per kg</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-base font-extrabold text-[#2D7A42] dark:text-emerald-400">Rp 15.500</div>
                            <div class="text-[9px] text-gray-400 dark:text-gray-500">Rp 14.500 Member</div>
                        </div>
                        <button class="w-8 h-8 rounded-full bg-[#F5820A] hover:bg-orange-600 text-white flex items-center justify-center shadow transition active:scale-90 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Telur Ayam -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 relative flex flex-col justify-between shadow-md hover:shadow-xl transition group">
                    <button class="absolute top-3.5 right-3.5 text-gray-400 hover:text-red-500 transition z-10">
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <div>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3.5 relative">
                            <img src="https://images.unsplash.com/photo-1516448620398-c5f44bf9f441?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain transition group-hover:scale-105" alt="Telur Ayam">
                            <div class="absolute bottom-2 left-2 flex gap-1 z-10">
                                <span class="bg-[#E8F5EC] dark:bg-emerald-950/50 text-[#2D7A42] dark:text-emerald-400 text-[9px] font-extrabold px-1.5 py-0.5 rounded">Sembako</span>
                                <span class="bg-[#F5820A] text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded">Harga Anggota</span>
                            </div>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-1">Telur Ayam Kampung</h4>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 block mb-3">per 10 butir</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-base font-extrabold text-[#2D7A42] dark:text-emerald-400">Rp 28.000</div>
                            <div class="text-[9px] text-gray-400 dark:text-gray-500">Rp 26.500 Member</div>
                        </div>
                        <button class="w-8 h-8 rounded-full bg-[#F5820A] hover:bg-orange-600 text-white flex items-center justify-center shadow transition active:scale-90 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Card 4: Gula Pasir -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 relative flex flex-col justify-between shadow-md hover:shadow-xl transition group">
                    <button class="absolute top-3.5 right-3.5 text-gray-400 hover:text-red-500 transition z-10">
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <div>
                        <div class="w-full h-36 flex items-center justify-center bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden mb-3.5 relative">
                            <img src="https://images.unsplash.com/photo-1581781870027-04210a40ca7a?auto=format&fit=crop&q=80&w=300" class="h-28 object-contain transition group-hover:scale-105" alt="Gula Pasir">
                            <div class="absolute bottom-2 left-2 flex gap-1 z-10">
                                <span class="bg-[#E8F5EC] dark:bg-emerald-950/50 text-[#2D7A42] dark:text-emerald-400 text-[9px] font-extrabold px-1.5 py-0.5 rounded">Sembako</span>
                                <span class="bg-[#F5820A] text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded">Harga Anggota</span>
                            </div>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-1">Gula Pasir Putih Premium</h4>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 block mb-3">per kg</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-base font-extrabold text-[#2D7A42] dark:text-emerald-400">Rp 17.000</div>
                            <div class="text-[9px] text-gray-400 dark:text-gray-500">Rp 16.000 Member</div>
                        </div>
                        <button class="w-8 h-8 rounded-full bg-[#F5820A] hover:bg-orange-600 text-white flex items-center justify-center shadow transition active:scale-90 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

   <!-- ================================================================= -->
<!-- SECTION 1: Banner Daftar Jadi Anggota Koperasi 6G -->
<!-- ================================================================= -->
<!-- Bagian yang diubah: Ditambahkan id="tentang-kami" dan scroll-mt-24 -->
<section id="tentang-kami" class="max-w-7xl mx-auto px-4 md:px-6 my-12 scroll-mt-24">
    <div class="bg-gradient-to-r from-[#0D5C34] to-[#14532D] rounded-[2rem] p-8 md:p-12 lg:p-16 flex flex-col lg:flex-row items-center justify-between gap-8 relative overflow-hidden shadow-lg">
        
        <!-- Elemen Dekoratif Latar Belakang -->
        <div class="absolute -right-10 -bottom-10 w-80 h-80 bg-[#166534] rounded-full blur-3xl opacity-30 pointer-events-none"></div>
        
        <!-- Sisi Kiri: Text & List Keuntungan -->
        <div class="w-full lg:w-1/2 text-white relative z-10">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                Daftar Jadi Anggota Koperasi 6G
            </h2>
            <p class="text-emerald-100/90 text-sm md:text-base mb-8 max-w-lg leading-relaxed">
                Nikmati keuntungan lebih dalam setiap transaksi belanja Anda. Bergabunglah dengan komunitas kami hari ini.
            </p>
            
            <!-- List Benefit -->
            <ul class="space-y-4">
                <li class="flex items-center text-sm md:text-base font-medium">
                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#0D5C34]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    Harga khusus anggota lebih hemat s.d 10%
                </li>
                <li class="flex items-center text-sm md:text-base font-medium">
                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#0D5C34]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    Poin reward belanja yang bisa ditukar produk
                </li>
                <li class="flex items-center text-sm md:text-base font-medium">
                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#0D5C34]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    Promo eksklusif di hari ulang tahun & hari besar
                </li>
                <li class="flex items-center text-sm md:text-base font-medium">
                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#0D5C34]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    Mendapatkan Sisa Hasil Usaha (SHU) tahunan
                </li>
            </ul>
            
            <!-- Tombol Daftar -->
            <button class="mt-8 px-8 py-3.5 bg-white text-[#0D5C34] hover:bg-emerald-50 active:scale-95 transition font-extrabold rounded-full shadow-md text-sm md:text-base">
                Daftar Sekarang
            </button>
        </div>
        
        <!-- Sisi Kanan: Ilustrasi Keluarga -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end relative z-10">
            <img src="https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&q=80&w=600" 
                 alt="Keluarga Koperasi 6G" 
                 class="w-full max-w-[450px] object-cover rounded-2xl h-64 md:h-80 shadow-inner" />
        </div>
    </div>
</section>
    <!-- ================================================================= -->
    <!-- SECTION 2: Cara Belanja di Koperasi 6G -->
    <!-- ================================================================= -->
    <section class="bg-[#F5F5F5] dark:bg-gray-900/60 py-16 px-4 md:px-6 transition-colors duration-250">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-center text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white mb-16">
                Cara Belanja di Koperasi 6G
            </h3>
            
            <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
                <!-- Garis Penghubung (Hanya muncul di layar lebar lg) -->
                <div class="hidden lg:block absolute top-6 left-[12%] right-[12%] h-0.5 border-t-2 border-dashed border-gray-300 dark:border-gray-700 z-0"></div>
                
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center text-center group z-10">
                    <div class="w-12 h-12 rounded-xl bg-[#0D5C34] text-white flex items-center justify-center font-bold text-lg mb-4 shadow transition-transform group-hover:scale-105">
                        1
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Daftar/Masuk</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-[200px] leading-relaxed">
                        Masuk ke akun member Anda untuk dapatkan harga spesial.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center text-center group z-10">
                    <div class="w-12 h-12 rounded-xl bg-[#0D5C34] text-white flex items-center justify-center font-bold text-lg mb-4 shadow transition-transform group-hover:scale-105">
                        2
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Pilih Produk</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-[200px] leading-relaxed">
                        Pilih sembako segar dan berkualitas dari katalog kami.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center text-center group z-10">
                    <div class="w-12 h-12 rounded-xl bg-[#0D5C34] text-white flex items-center justify-center font-bold text-lg mb-4 shadow transition-transform group-hover:scale-105">
                        3
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Checkout</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-[200px] leading-relaxed">
                        Pilih metode pembayaran dan pengiriman yang tersedia.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="relative flex flex-col items-center text-center group z-10">
                    <div class="w-12 h-12 rounded-xl bg-[#0D5C34] text-white flex items-center justify-center font-bold text-lg mb-4 shadow transition-transform group-hover:scale-105">
                        4
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Ambil/Kirim</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-[200px] leading-relaxed">
                        Ambil di toko terdekat atau barang dikirim ke rumah Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- SECTION 3: Produk UMKM Lokal Pilihan -->
    <!-- ================================================================= -->
    <section class="bg-[#1C1C1C] py-16 px-4 md:px-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-white tracking-wide">
                        Produk UMKM Lokal Pilihan
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Mendukung pertumbuhan produsen lokal melalui jaringan koperasi.
                    </p>
                </div>
                <!-- Tombol Mitra -->
                <a href="#" class="inline-block border border-emerald-700 hover:border-emerald-600 text-emerald-400 hover:bg-emerald-950/20 px-4 py-2 rounded-full text-xs font-bold tracking-wide transition active:scale-95 text-center self-start sm:self-auto">
                    Daftar Jadi Mitra
                </a>
            </div>
            
            <!-- Grid 5 Kolom (Responsif) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 md:gap-5">
                
                <!-- Card 1: Madu Murni Desa -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 flex flex-col items-center justify-between text-center shadow transition hover:shadow-lg">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden mb-4 bg-gray-50 shadow-inner flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&q=80&w=150" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300" 
                                 alt="Madu Murni Desa">
                        </div>
                        <span class="bg-[#E8F5EC] text-[#2D7A42] text-[8px] md:text-[9px] font-black px-2.5 py-1 rounded-full mb-3 uppercase tracking-wider">
                            Produk Mitra Lokal
                        </span>
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
                            Madu Murni Desa
                        </h4>
                    </div>
                </div>

                <!-- Card 2: Gula Aren -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 flex flex-col items-center justify-between text-center shadow transition hover:shadow-lg">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden mb-4 bg-gray-50 shadow-inner flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1621932953986-15fcf084da0f?auto=format&fit=crop&q=80&w=150" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300" 
                                 alt="Gula Aren">
                        </div>
                        <span class="bg-[#E8F5EC] text-[#2D7A42] text-[8px] md:text-[9px] font-black px-2.5 py-1 rounded-full mb-3 uppercase tracking-wider">
                            Produk Mitra Lokal
                        </span>
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
                            Gula Aren
                        </h4>
                    </div>
                </div>

                <!-- Card 3: Kopi Robusta Lereng -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 flex flex-col items-center justify-between text-center shadow transition hover:shadow-lg">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden mb-4 bg-gray-50 shadow-inner flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1607687311177-0100785c2e9a?auto=format&fit=crop&q=80&w=150" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300" 
                                 alt="Kopi Robusta Lereng">
                        </div>
                        <span class="bg-[#E8F5EC] text-[#2D7A42] text-[8px] md:text-[9px] font-black px-2.5 py-1 rounded-full mb-3 uppercase tracking-wider">
                            Produk Mitra Lokal
                        </span>
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
                            Kopi Robusta Lereng
                        </h4>
                    </div>
                </div>

                <!-- Card 4: Sambal Rumahan Bu Sri -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 flex flex-col items-center justify-between text-center shadow transition hover:shadow-lg">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden mb-4 bg-gray-50 shadow-inner flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1599940824399-b87987ceb72a?auto=format&fit=crop&q=80&w=150" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300" 
                                 alt="Sambal Rumahan Bu Sri">
                        </div>
                        <span class="bg-[#E8F5EC] text-[#2D7A42] text-[8px] md:text-[9px] font-black px-2.5 py-1 rounded-full mb-3 uppercase tracking-wider">
                            Produk Mitra Lokal
                        </span>
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
                            Sambal Rumahan Bu Sri
                        </h4>
                    </div>
                </div>

                <!-- Card 5: Kerajinan Bambu Desa -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 flex flex-col items-center justify-between text-center shadow transition hover:shadow-lg col-span-2 sm:col-span-1">
                    <div class="flex flex-col items-center w-full">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden mb-4 bg-gray-50 shadow-inner flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1549490349-8643362247b5?auto=format&fit=crop&q=80&w=150" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300" 
                                 alt="Kerajinan Bambu Desa">
                        </div>
                        <span class="bg-[#E8F5EC] text-[#2D7A42] text-[8px] md:text-[9px] font-black px-2.5 py-1 rounded-full mb-3 uppercase tracking-wider">
                            Produk Mitra Lokal
                        </span>
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
                            Kerajinan Bambu Desa
                        </h4>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- SECTION 4: Temukan Toko Koperasi 6G Terdekat -->
    <!-- ================================================================= -->
    <section id="lokasi-toko" class="max-w-7xl mx-auto px-4 md:px-6 py-16 scroll-mt-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Sisi Kiri: Daftar Cabang -->
            <div class="lg:col-span-4 flex flex-col justify-between h-full">
                <div>
                    <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white mb-6">
                        Temukan Toko Koperasi 6G Terdekat
                    </h3>
                    
                    <!-- Container Cabang -->
                    <div class="space-y-4">
                        <!-- Cabang Kartasura -->
                        <div class="bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl p-4 transition duration-200 hover:translate-x-1 border border-transparent dark:border-gray-700/50">
                            <h4 class="font-extrabold text-sm md:text-base text-gray-900 dark:text-white">Cabang Kartasura</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jl. Mawar Melati No.2</p>
                            <div class="flex items-center text-[#2D7A42] dark:text-emerald-400 text-xs font-bold mt-3">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Buka: 08:00 - 21:00
                            </div>
                        </div>

                        <!-- Cabang Mangkunegaran -->
                        <div class="bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl p-4 transition duration-200 hover:translate-x-1 border border-transparent dark:border-gray-700/50">
                            <h4 class="font-extrabold text-sm md:text-base text-gray-900 dark:text-white">Cabang Mangkunegaran</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ruko Kenanga No.4</p>
                            <div class="flex items-center text-[#2D7A42] dark:text-emerald-400 text-xs font-bold mt-3">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Buka: 07:00 - 22:00
                            </div>
                        </div>

                        <!-- Cabang Manahan -->
                        <div class="bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl p-4 transition duration-200 hover:translate-x-1 border border-transparent dark:border-gray-700/50">
                            <h4 class="font-extrabold text-sm md:text-base text-gray-900 dark:text-white">Cabang Manahan</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jl. Depan Sepatu Rodaku</p>
                            <div class="flex items-center text-[#2D7A42] dark:text-emerald-400 text-xs font-bold mt-3">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Buka: 08:00 - 20:00
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Lihat Semua Lokasi -->
                <a href="#" class="inline-flex items-center text-[#2D7A42] dark:text-emerald-400 font-extrabold text-sm mt-6 hover:underline">
                    Lihat Semua Lokasi
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </a>
            </div>

            <!-- Sisi Kanan: Peta (Mock Map Visual) -->
            <div class="lg:col-span-8 relative rounded-3xl overflow-hidden shadow-md border border-gray-100 dark:border-gray-800 h-[320px] md:h-[420px] w-full">
                <!-- Gambar Latar Belakang Peta (Gunakan ilustrasi map atau map satelit ringan) -->
                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=1000" 
                     class="w-full h-full object-cover filter brightness-95 contrast-90 dark:opacity-80" 
                     alt="Peta Lokasi Toko">
                
                <!-- Overlay Elemen Pin Lokasi di Peta -->
                <div class="absolute inset-0 bg-emerald-950/5 dark:bg-black/10 mix-blend-multiply"></div>

                <!-- Pin Cabang 1 (Kartasura) -->
                <div class="absolute top-[28%] left-[45%] group cursor-pointer">
                    <span class="absolute inline-flex h-6 w-6 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                    <svg class="w-8 h-8 text-red-600 relative z-10 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Pin Cabang 2 (Mangkunegaran) -->
                <div class="absolute top-[65%] left-[62%] group cursor-pointer">
                    <span class="absolute inline-flex h-6 w-6 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                    <svg class="w-8 h-8 text-red-600 relative z-10 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Pin Cabang 3 (Manahan) -->
                <div class="absolute bottom-[10%] left-[35%] group cursor-pointer">
                    <span class="absolute inline-flex h-6 w-6 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                    <svg class="w-8 h-8 text-red-600 relative z-10 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- SECTION 5: Testimoni (Apa Kata Anggota Kami?) -->
    <!-- ================================================================= -->
    <section class="bg-[#EBEBEB] dark:bg-gray-900/40 py-16 px-4 md:px-6 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            
            <!-- Judul Section -->
            <h3 class="text-center text-xl md:text-2xl lg:text-3xl font-extrabold text-gray-950 dark:text-white mb-16">
                Apa Kata Anggota Kami?
            </h3>
            
            <!-- Grid Card Testimoni -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Testimoni 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 relative flex flex-col justify-between shadow-sm border border-gray-100/30 dark:border-gray-700/40">
                    <!-- Icon Quote Hijau -->
                    <div class="absolute -top-5 left-6 w-10 h-10 rounded-full bg-[#0D5C34] flex items-center justify-center text-white font-black text-sm select-none shadow">
                        99
                    </div>
                    
                    <!-- Bintang Rating -->
                    <div class="flex gap-1 text-orange-500 mt-2 mb-4">
                        <!-- 5 Star Icons -->
                        <span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span>
                    </div>

                    <!-- Isi Review -->
                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 italic leading-relaxed mb-6">
                        "Belanja di Koperasi 6G sangat membantu keuangan keluarga kami. Harganya bersaing dan akhir tahun masih dapet SHU. Benar-benar untung bersama!"
                    </p>

                    <!-- Profil Pengguna -->
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=100" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700" 
                             alt="Ibu Lisa">
                        <div>
                            <h4 class="font-bold text-xs md:text-sm text-gray-900 dark:text-white">Ibu Lisa</h4>
                            <span class="text-[10px] md:text-xs text-[#2D7A42] dark:text-emerald-400 font-semibold block">Anggota Aktif (Sejak 2021)</span>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 relative flex flex-col justify-between shadow-sm border border-gray-100/30 dark:border-gray-700/40">
                    <div class="absolute -top-5 left-6 w-10 h-10 rounded-full bg-[#0D5C34] flex items-center justify-center text-white font-black text-sm select-none shadow">
                        99
                    </div>
                    
                    <div class="flex gap-1 text-orange-500 mt-2 mb-4">
                        <span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span>
                    </div>

                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 italic leading-relaxed mb-6">
                        "Kualitas sayur dan buahnya juara. Segar sekali, kayak baru dipetik dari kebun. Pengirimannya juga cepat dan rapi."
                    </p>

                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700" 
                             alt="Ibu Jennie">
                        <div>
                            <h4 class="font-bold text-xs md:text-sm text-gray-900 dark:text-white">Ibu Jennie</h4>
                            <span class="text-[10px] md:text-xs text-[#2D7A42] dark:text-emerald-400 font-semibold block">Anggota Aktif (Sejak 2022)</span>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 relative flex flex-col justify-between shadow-sm border border-gray-100/30 dark:border-gray-700/40">
                    <div class="absolute -top-5 left-6 w-10 h-10 rounded-full bg-[#0D5C34] flex items-center justify-center text-white font-black text-sm select-none shadow">
                        99
                    </div>
                    
                    <div class="flex gap-1 text-orange-500 mt-2 mb-4">
                        <span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span><span class="text-base">★</span>
                    </div>

                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 italic leading-relaxed mb-6">
                        "Aplikasinya mudah digunakan, pembayaran lengkap. Fitur koperasinya juga transparan. Rejeki banget kenal Koperasi 6G."
                    </p>

                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=100" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700" 
                             alt="Ibu Jisoo">
                        <div>
                            <h4 class="font-bold text-xs md:text-sm text-gray-900 dark:text-white">Ibu Jisoo</h4>
                            <span class="text-[10px] md:text-xs text-[#2D7A42] dark:text-emerald-400 font-semibold block">Anggota Aktif (Sejak 2020)</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- FOOTER SECTION (Koperasi 6G) -->
    <!-- ================================================================= -->
  <footer id="kontak" class="bg-[#EAEAEA] dark:bg-gray-950 border-t border-gray-300 dark:border-gray-800 pt-16 pb-8 transition-colors duration-200 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            
            <!-- Grid Atas: 4 Kolom -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 pb-12 border-b border-gray-300 dark:border-gray-800">
                
                <!-- Kolom 1: Brand & Slogan (4/12 Grid) -->
                <div class="lg:col-span-4 space-y-4">
                    <h4 class="text-[#2D7A42] dark:text-emerald-400 text-lg md:text-xl font-extrabold tracking-tight">
                        Koperasi 6G
                    </h4>
                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed max-w-xs">
                        Belanja Mudah, Untung Bersama. Solusi sembako modern untuk masyarakat mandiri.
                    </p>
                    
                    <!-- Sosial Media Buttons -->
                    <div class="flex items-center gap-3 pt-2">
                        <!-- Facebook / Share -->
                        <a href="#" class="w-8 h-8 rounded-full bg-[#0D5C34] hover:bg-emerald-800 text-white flex items-center justify-center transition active:scale-90" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8H7v3h2v9h4v-9h3.6l.4-3H13V6c0-.5.5-1 1-1h2V1H13a4 4 0 00-4 4v3z"/>
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="#" class="w-8 h-8 rounded-full bg-[#0D5C34] hover:bg-emerald-800 text-white flex items-center justify-center transition active:scale-90" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                        <!-- WhatsApp / Telegram -->
                        <a href="#" class="w-8 h-8 rounded-full bg-[#0D5C34] hover:bg-emerald-800 text-white flex items-center justify-center transition active:scale-90" aria-label="WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.734-1.456L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.115-2.903-6.99C16.262 1.876 13.788.847 11.15.845 5.717.845 1.3 5.257 1.296 10.69c-.001 1.708.45 3.374 1.304 4.83l-.999 3.644 3.73-.978c1.455.795 3.033 1.213 4.621 1.213-.001 0-.001 0 0 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Produk Links (2/12 Grid) -->
                <div class="lg:col-span-2 space-y-4">
                    <h5 class="text-xs md:text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                        Produk
                    </h5>
                    <ul class="space-y-2.5 text-xs md:text-sm text-gray-600 dark:text-gray-400">
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Sembako Utama</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Sayur & Buah</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Daging & Ikan</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Produk UMKM</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Katalog Promo</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Koperasi Links (2/12 Grid) -->
                <div class="lg:col-span-2 space-y-4">
                    <h5 class="text-xs md:text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                        Koperasi
                    </h5>
                    <ul class="space-y-2.5 text-xs md:text-sm text-gray-600 dark:text-gray-400">
                        <li><a href="#tentang-kami" class="hover:text-[#2D7A42] transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Keanggotaan</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Laporan SHU</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-[#2D7A42] transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Download Aplikasi (4/12 Grid) -->
                <div class="lg:col-span-4 space-y-4">
                    <h5 class="text-xs md:text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                        Download Aplikasi
                    </h5>
                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed max-w-xs">
                        Dapatkan kemudahan dalam genggaman Anda.
                    </p>
                    
                    <!-- Tombol Download Stores -->
                    <div class="flex flex-col gap-3 max-w-[180px]">
                        <!-- Google Play Button -->
                        <a href="#" class="flex items-center gap-3 bg-[#1e1e1e] hover:bg-black text-white px-4 py-2.5 rounded-xl transition active:scale-95 shadow-sm">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 5.277L14.721 17L3 17V5.277 M17.487 12L3 21.115V21h14.487 C18.322 21 19 20.322 19 19.487V4.513C19 3.678,18.322 3 17.487 3H3v0.115L17.487 12" />
                            </svg>
                            <div class="text-left">
                                <span class="block text-[8px] uppercase text-gray-400 font-medium">Download on</span>
                                <span class="block text-xs font-bold -mt-0.5">Google Play</span>
                            </div>
                        </a>
                        
                        <!-- App Store Button -->
                        <a href="#" class="flex items-center gap-3 bg-[#1e1e1e] hover:bg-black text-white px-4 py-2.5 rounded-xl transition active:scale-95 shadow-sm">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.71 19.5C17.88 20.74 17 21.95 15.66 21.97C14.32 22 13.89 21.18 12.37 21.18C10.84 21.18 10.37 21.95 9.1 22C7.79 22.05 6.8 20.68 5.96 19.47C4.25 17 2.94 12.45 4.7 9.39C5.57 7.87 7.13 6.91 8.82 6.88C10.1 6.86 11.32 7.75 12.11 7.75C12.89 7.75 14.37 6.68 15.92 6.84C16.57 6.87 18.39 7.1 19.56 8.82C19.47 8.88 17.39 10.1 17.41 12.63C17.44 15.65 20.06 16.66 20.1 16.67C20.08 16.74 19.67 18.11 18.71 19.5M15.97 4.17C16.63 3.37 17.07 2.28 16.95 1C16 1.04 14.9 1.6 14.24 2.38C13.68 3.04 13.19 4.14 13.34 5.39C14.39 5.47 15.4 4.88 15.97 4.17Z"/>
                            </svg>
                            <div class="text-left">
                                <span class="block text-[8px] uppercase text-gray-400 font-medium">Download on the</span>
                                <span class="block text-xs font-bold -mt-0.5">App Store</span>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Grid Bawah: Hak Cipta & Icon Pembayaran -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
                <!-- Sisi Kiri: Teks Copyright -->
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center sm:text-left">
                    &copy; 2026 Koperasi 6G - Untung Bersama. Seluruh hak cipta dilindungi undang-undang.
                </p>
                
                <!-- Sisi Kanan: Ikon Fitur Pembayaran / QR / Bank (Minimalis) -->
                <div class="flex items-center gap-4 text-gray-400 dark:text-gray-500">
                    <!-- Icon 1: Tunai / Cash -->
                    <svg class="w-6 h-6 hover:text-[#2D7A42] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-label="Tunai">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <!-- Icon 2: Bank / Transfer -->
                    <svg class="w-6 h-6 hover:text-[#2D7A42] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-label="Transfer Bank">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <!-- Icon 3: QR Code / QRIS -->
                    <svg class="w-6 h-6 hover:text-[#2D7A42] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-label="QRIS">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
            </div>

        </div>
    </footer>

    
    </main>

    
   <script>
// Matikan scroll restoration bawaan browser supaya tidak auto-scroll ke posisi terakhir saat refresh
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

window.scrollTo(0, 0);

document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('#main-nav .nav-link');

    const activeClasses = [
        'text-[#2D7A42]', 
        'dark:text-emerald-400', 
        'border-b-2', 
        'border-[#2D7A42]', 
        'dark:border-emerald-400', 
        'pb-1'
    ];

    const inactiveClasses = [
        'hover:text-[#2D7A42]', 
        'dark:hover:text-emerald-400', 
        'transition'
    ];

    let isClicking = false;

    const cameFromExternalPage = document.referrer && !document.referrer.includes(window.location.origin + window.location.pathname);

    if (!cameFromExternalPage && window.location.hash) {
        history.replaceState(null, '', window.location.pathname + window.location.search);
    }

    // updateUrl: default true. Set false kalau tidak ingin mengubah address bar (misal saat inisialisasi awal dari referrer)
    function setActiveLink(hash, updateUrl = true) {
        const currentHash = hash ? hash.replace('#', '') : '';
        let matched = false;

        navLinks.forEach(link => {
            link.classList.remove(...activeClasses);
            link.classList.add(...inactiveClasses);

            const linkHash = link.getAttribute('href').split('#')[1] || '';

            if (linkHash === currentHash) {
                link.classList.remove(...inactiveClasses);
                link.classList.add(...activeClasses);
                matched = true;
            }
        });

        if (!matched && navLinks.length > 0) {
            navLinks[0].classList.remove(...inactiveClasses);
            navLinks[0].classList.add(...activeClasses);
        }

        // Update address bar mengikuti section aktif, tanpa reload/scroll jump
        if (updateUrl) {
            const newUrl = currentHash 
                ? window.location.pathname + window.location.search + '#' + currentHash
                : window.location.pathname + window.location.search;
            history.replaceState(null, '', newUrl);
        }
    }

    if (cameFromExternalPage && window.location.hash) {
        setActiveLink(window.location.hash, false); // jangan ubah URL, memang sudah sesuai tujuan
    } else {
        window.scrollTo(0, 0);
        setActiveLink('', false); // sudah bersih dari awal, tidak perlu replaceState lagi
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            const hash = this.getAttribute('href').split('#')[1];
            isClicking = true;
            setActiveLink(hash ? '#' + hash : '');

            clearTimeout(window._clickTimeout);
            window._clickTimeout = setTimeout(() => {
                isClicking = false;
            }, 800);
        });
    });

    window.addEventListener('hashchange', function () {
        setActiveLink(window.location.hash, false); // hash sudah berubah sendiri, tidak perlu replaceState lagi
    });

    const sectionIds = Array.from(navLinks)
        .map(link => link.getAttribute('href').split('#')[1])
        .filter(id => id);

    const sections = sectionIds
        .map(id => document.getElementById(id))
        .filter(section => section);

    const lastHash = sectionIds[sectionIds.length - 1];

    if (sections.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            if (isClicking) return;

            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setActiveLink('#' + entry.target.id); // update URL mengikuti section yang terlihat
                }
            });
        }, {
            root: null,
            rootMargin: '-100px 0px -60% 0px',
            threshold: 0
        });

        sections.forEach(section => observer.observe(section));

        window.addEventListener('scroll', function () {
            if (isClicking) return;

            const scrollPosition = window.scrollY + window.innerHeight;
            const pageHeight = document.documentElement.scrollHeight;

            if (window.scrollY < 150) {
                setActiveLink(''); // balik ke Beranda, URL juga dibersihkan dari hash
                return;
            }

            if (scrollPosition >= pageHeight - 50) {
                setActiveLink('#' + lastHash); // URL jadi #kontak saat di footer
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    let seconds = 57;
    let minutes = 24;
    let hours = 8;

    const timerElement = document.getElementById('promo-timer');

    if (timerElement) {
        const interval = setInterval(() => {
            seconds--;
            if (seconds < 0) {
                seconds = 59;
                minutes--;
                if (minutes < 0) {
                    minutes = 59;
                    hours--;
                    if (hours < 0) {
                        clearInterval(interval);
                        timerElement.innerText = "PROMO SELESAI";
                        return;
                    }
                }
            }

            const formatNum = (num) => String(num).padStart(2, '0');
            timerElement.innerText = `${formatNum(hours)}:${formatNum(minutes)}:${formatNum(seconds)}`;
        }, 1000);
    }
});
</script>

</div>
@endsection