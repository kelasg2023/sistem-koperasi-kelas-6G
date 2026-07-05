@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-4xl mx-auto">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] hover:border-[#2D7A42]/30 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Detail Pesanan</h1>
                <p class="text-xs sm:text-sm text-gray-500">No. Invoice: <span class="font-bold text-gray-800">INV/20260705/TRX-98234</span></p>
            </div>
        </div>
        <button class="hidden sm:flex px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-lg hover:bg-gray-50 shadow-sm gap-2 items-center">
            <i class="fa-solid fa-download"></i> Unduh Invoice
        </button>
    </div>

    {{-- Alert Status --}}
    <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-5 mb-6 text-white shadow-md flex items-center gap-4">
        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center shrink-0 backdrop-blur-sm">
            <i class="fa-solid fa-truck-fast text-2xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold mb-1">Pesanan Sedang Dikirim</h2>
            <p class="text-xs sm:text-sm text-green-100">Kurir Koperasi sedang menuju ke alamat Anda. Estimasi tiba hari ini pukul 14:00 WIB.</p>
        </div>
    </div>

    {{-- Lacak Pengiriman (Timeline) --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6 mb-6">
        <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">Status Pengiriman</h3>
        
        <div class="relative border-l-2 border-gray-100 ml-3 md:ml-4 space-y-6">
            
            {{-- Status 1 (Aktif/Terbaru) --}}
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-[#2D7A42] ring-4 ring-[#E8F5EC]"></div>
                <h4 class="text-sm font-bold text-[#2D7A42]">Pesanan Dibawa Kurir</h4>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Kurir (Bpk. Joko) sedang mengantar pesanan ke alamat tujuan.</p>
                <span class="text-[10px] font-semibold text-gray-400 mt-1.5 block">05 Jul 2026, 09:30 WIB</span>
            </div>

            {{-- Status 2 --}}
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-gray-300 border-2 border-white"></div>
                <h4 class="text-sm font-bold text-gray-700">Pesanan Diproses</h4>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Pesanan Anda sedang dikemas oleh tim Koperasi 6G.</p>
                <span class="text-[10px] font-semibold text-gray-400 mt-1.5 block">05 Jul 2026, 08:15 WIB</span>
            </div>
            
            {{-- Status 3 --}}
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-gray-300 border-2 border-white"></div>
                <h4 class="text-sm font-bold text-gray-700">Pesanan Dibuat</h4>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Pembayaran terverifikasi. Pesanan berhasil dibuat.</p>
                <span class="text-[10px] font-semibold text-gray-400 mt-1.5 block">05 Jul 2026, 08:00 WIB</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Kiri: Detail Produk & Pengiriman --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Alamat Pengiriman --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Alamat Pengiriman</h3>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Ardian Putra <span class="font-normal text-gray-400">(0812-3456-7890)</span></h4>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">Jl. Koperasi No. 123, RT 01/RW 02, Kec. Sukamaju, Kota Sejahtera, Jawa Tengah, 12345.</p>
                    </div>
                </div>
            </div>

            {{-- Daftar Produk --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Daftar Produk</h3>
                
                <div class="space-y-4">
                    {{-- Produk 1 --}}
                    <div class="flex gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-2xl shrink-0">
                            🌾
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800">Beras Pandan Wangi Premium 5 Kg</h4>
                            <p class="text-xs text-gray-500 mt-1">1 x Rp 68.500</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp 68.500</p>
                        </div>
                    </div>

                    {{-- Produk 2 --}}
                    <div class="flex gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-2xl shrink-0">
                            🍾
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800">Minyak Goreng Bimoli 2 Liter</h4>
                            <p class="text-xs text-gray-500 mt-1">2 x Rp 34.000</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp 68.000</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kanan: Rincian Pembayaran --}}
        <div class="md:col-span-1">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6 sticky top-24">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Rincian Pembayaran</h3>
                
                <div class="space-y-3 mb-5">
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Metode Pembayaran</span>
                        <span class="font-bold text-gray-800">Saldo Koperasi</span>
                    </div>
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Subtotal Produk</span>
                        <span class="font-medium text-gray-800">Rp 136.500</span>
                    </div>
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="font-medium text-gray-800">Rp 10.000</span>
                    </div>
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Voucher Diskon</span>
                        <span class="font-bold text-[#F5820A]">- Rp 10.000</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-dashed border-gray-200 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-900 text-sm">Total Belanja</span>
                        <span class="font-extrabold text-xl text-[#2D7A42]">Rp 136.500</span>
                    </div>
                </div>

                <button class="w-full px-6 py-3 bg-white border border-[#2D7A42] text-[#2D7A42] hover:bg-[#E8F5EC] font-bold text-sm rounded-xl transition-colors shadow-sm mb-3">
                    Hubungi Kurir
                </button>
                <button class="w-full px-6 py-3 bg-gray-50 text-gray-600 hover:bg-gray-100 font-bold text-sm rounded-xl transition-colors">
                    Bantuan Pesanan
                </button>
            </div>
        </div>

    </div>
</div>
@endsection