@extends('layouts.app') {{-- Sesuaikan dengan nama layout utamamu yang memuat Sidebar & Navbar --}}

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h1>
        
        {{-- Form Pencarian Transaksi --}}
        <div class="relative w-64 hidden sm:block">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Cari invoice atau produk..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42]">
        </div>
    </div>

    {{-- Tabs Filter Status --}}
    <div class="flex overflow-x-auto scrollbar-hide gap-2 mb-6 pb-2">
        <button class="px-5 py-2 rounded-full text-sm font-semibold bg-[#2D7A42] text-white whitespace-nowrap">Semua</button>
        <button class="px-5 py-2 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 whitespace-nowrap transition-colors">Belum Bayar</button>
        <button class="px-5 py-2 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 whitespace-nowrap transition-colors">Diproses</button>
        <button class="px-5 py-2 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 whitespace-nowrap transition-colors">Dikirim</button>
        <button class="px-5 py-2 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 whitespace-nowrap transition-colors">Selesai</button>
        <button class="px-5 py-2 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 whitespace-nowrap transition-colors">Dibatalkan</button>
    </div>

    {{-- List Transaksi --}}
    <div class="space-y-4">
        
        {{-- Card Transaksi 1 (Status: Selesai) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i>
                    <span class="text-sm font-bold text-gray-800">Belanja • 12 Mei 2026</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-green-100 text-green-700">Selesai</span>
                </div>
                <span class="text-xs text-gray-500 font-medium hidden sm:block">INV/20260512/0001</span>
            </div>

            <div class="flex flex-col sm:flex-row items-start gap-4">
                <img src="https://placehold.co/100x100/e2e8f0/475569?text=Beras" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
                <div class="flex-1">
                    <h3 class="text-base font-bold text-gray-800">Beras Pandan Wangi 5 Kg</h3>
                    <p class="text-sm text-gray-500">1 barang x Rp 68.500</p>
                    <p class="text-xs text-gray-400 mt-1">+ 2 produk lainnya</p>
                </div>
                <div class="sm:text-right sm:border-l border-t sm:border-t-0 border-gray-100 sm:pl-4 pt-3 sm:pt-0 w-full sm:w-auto flex justify-between sm:block mt-2 sm:mt-0">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="text-lg font-extrabold text-gray-900">Rp 120.500</p>
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 mt-4 pt-4 border-t border-gray-50">
                <button class="px-4 py-2 text-sm font-bold text-[#2D7A42] bg-white border border-[#2D7A42] rounded-xl hover:bg-[#E8F5EC] transition">Lihat Detail</button>
                <button class="px-4 py-2 text-sm font-bold text-white bg-[#2D7A42] rounded-xl hover:bg-[#1E5C2F] transition">Beli Lagi</button>
            </div>
        </div>

        {{-- Card Transaksi 2 (Status: Diproses) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i>
                    <span class="text-sm font-bold text-gray-800">Belanja • 15 Mei 2026</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-yellow-100 text-yellow-700">Diproses</span>
                </div>
                <span class="text-xs text-gray-500 font-medium hidden sm:block">INV/20260515/0042</span>
            </div>

            <div class="flex flex-col sm:flex-row items-start gap-4">
                <img src="https://placehold.co/100x100/e2e8f0/475569?text=Minyak" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
                <div class="flex-1">
                    <h3 class="text-base font-bold text-gray-800">Minyak Goreng 2 Liter</h3>
                    <p class="text-sm text-gray-500">2 barang x Rp 34.000</p>
                </div>
                <div class="sm:text-right sm:border-l border-t sm:border-t-0 border-gray-100 sm:pl-4 pt-3 sm:pt-0 w-full sm:w-auto flex justify-between sm:block mt-2 sm:mt-0">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="text-lg font-extrabold text-gray-900">Rp 68.000</p>
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 mt-4 pt-4 border-t border-gray-50">
                <button class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">Hubungi Koperasi</button>
                <button class="px-4 py-2 text-sm font-bold text-[#2D7A42] bg-white border border-[#2D7A42] rounded-xl hover:bg-[#E8F5EC] transition">Lihat Detail</button>
            </div>
        </div>

    </div>
</div>
@endsection