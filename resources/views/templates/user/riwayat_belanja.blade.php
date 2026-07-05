@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto">
    
    {{-- Header Halaman --}}
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] hover:border-[#2D7A42]/30 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Riwayat Belanja</h1>
            <p class="text-gray-500 text-sm">Pantau status pesanan dan daftar transaksi Anda sebelumnya.</p>
        </div>
    </div>

    {{-- Pencarian & Filter Tab --}}
    <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 mb-6 sticky top-20 z-40">
        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Tab Status --}}
            <div class="flex overflow-x-auto scrollbar-hide gap-1 flex-1">
                <button class="px-5 py-2.5 text-sm font-bold text-white bg-[#2D7A42] rounded-xl whitespace-nowrap shadow-sm">Semua Transaksi</button>
                <button class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-[#2D7A42] hover:bg-gray-50 rounded-xl whitespace-nowrap transition-colors">Berlangsung</button>
                <button class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-[#2D7A42] hover:bg-gray-50 rounded-xl whitespace-nowrap transition-colors">Selesai</button>
                <button class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-[#2D7A42] hover:bg-gray-50 rounded-xl whitespace-nowrap transition-colors">Dibatalkan</button>
            </div>
            
            {{-- Search Bar --}}
            <div class="relative sm:w-64 shrink-0">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari nama produk..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] bg-gray-50 focus:bg-white transition-all">
            </div>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="space-y-5">
        
        {{-- Item Transaksi 1: Selesai (Bulan Juli) --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:border-[#2D7A42]/50 transition-colors shadow-sm">
            {{-- Header Card --}}
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i>
                    <span class="text-xs font-bold text-gray-600">05 Jul 2026</span>
                    <span class="text-gray-300 text-xs">•</span>
                    <span class="text-xs font-medium text-gray-500">INV/20260705/001</span>
                </div>
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">
                    Selesai
                </span>
            </div>
            
            {{-- Body Card --}}
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-3xl shrink-0">
                    🌾
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm sm:text-base font-bold text-gray-900 truncate">Beras Pandan Wangi 5 Kg</h4>
                    <p class="text-xs text-gray-500 mt-1">1 barang x Rp 68.500</p>
                    <p class="text-xs text-gray-400 mt-1">+ 7 produk lainnya (Sembako)</p>
                </div>
                <div class="text-right shrink-0 border-l border-gray-100 pl-4 hidden sm:block">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="text-base font-extrabold text-gray-900">Rp 420.000</p>
                </div>
            </div>

            {{-- Footer Card --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="sm:hidden w-full flex justify-between items-center">
                    <p class="text-xs text-gray-500">Total Belanja</p>
                    <p class="text-sm font-extrabold text-gray-900">Rp 420.000</p>
                </div>
                <button class="text-xs font-bold text-gray-500 hover:text-[#2D7A42] transition-colors">
                    Lihat Detail Transaksi
                </button>
                <button class="w-full sm:w-auto px-6 py-2 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                    Beli Lagi
                </button>
            </div>
        </div>

        {{-- Item Transaksi 2: Diproses --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:border-orange-300 transition-colors shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i>
                    <span class="text-xs font-bold text-gray-600">Hari ini</span>
                    <span class="text-gray-300 text-xs">•</span>
                    <span class="text-xs font-medium text-gray-500">INV/20260705/045</span>
                </div>
                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">
                    Sedang Diproses
                </span>
            </div>
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-3xl shrink-0">
                    🥦
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm sm:text-base font-bold text-gray-900 truncate">Sayur & Buah Segar Mix</h4>
                    <p class="text-xs text-gray-500 mt-1">4 barang</p>
                </div>
                <div class="text-right shrink-0 border-l border-gray-100 pl-4 hidden sm:block">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="text-base font-extrabold text-gray-900">Rp 85.000</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="sm:hidden w-full flex justify-between items-center">
                    <p class="text-xs text-gray-500">Total Belanja</p>
                    <p class="text-sm font-extrabold text-gray-900">Rp 85.000</p>
                </div>
                <button class="text-xs font-bold text-gray-500 hover:text-[#2D7A42] transition-colors">
                    Lihat Detail Transaksi
                </button>
                <button class="w-full sm:w-auto px-6 py-2 bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200 text-xs font-bold rounded-xl transition-colors">
                    Lacak Pesanan
                </button>
            </div>
        </div>

        {{-- Item Transaksi 3: Selesai (Bulan Desember 2025) --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:border-[#2D7A42]/50 transition-colors shadow-sm opacity-80 hover:opacity-100">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i>
                    <span class="text-xs font-bold text-gray-600">01 Des 2025</span>
                    <span class="text-gray-300 text-xs">•</span>
                    <span class="text-xs font-medium text-gray-500">INV/20251201/012</span>
                </div>
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">
                    Selesai
                </span>
            </div>
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl shrink-0">
                    🧹
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm sm:text-base font-bold text-gray-900 truncate">Paket Kebersihan Rumah</h4>
                    <p class="text-xs text-gray-500 mt-1">3 barang</p>
                </div>
                <div class="text-right shrink-0 border-l border-gray-100 pl-4 hidden sm:block">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="text-base font-extrabold text-gray-900">Rp 112.500</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="sm:hidden w-full flex justify-between items-center">
                    <p class="text-xs text-gray-500">Total Belanja</p>
                    <p class="text-sm font-extrabold text-gray-900">Rp 112.500</p>
                </div>
                <button class="text-xs font-bold text-gray-500 hover:text-[#2D7A42] transition-colors">
                    Lihat Detail Transaksi
                </button>
                <button class="w-full sm:w-auto px-6 py-2 bg-white border border-[#2D7A42] text-[#2D7A42] hover:bg-[#E8F5EC] text-xs font-bold rounded-xl transition-colors">
                    Beli Lagi
                </button>
            </div>
        </div>

    </div>
</div>
@endsection