@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto">
    
    {{-- Header Halaman dengan Tombol Kembali --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-[#F5820A] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Voucher Saya</h1>
            <p class="text-gray-500 text-sm">Klaim dan gunakan voucher untuk belanja lebih hemat di Koperasi 6G.</p>
        </div>
    </div>

    {{-- Form Input Kode Voucher --}}
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-sm mb-6 lg:mb-8 flex flex-col sm:flex-row gap-3">
        <input type="text" placeholder="Masukkan kode voucher di sini" class="w-full sm:flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#F5820A] focus:ring-1 focus:ring-[#F5820A] uppercase placeholder:normal-case">
        <button class="px-6 py-3 bg-[#F5820A] hover:bg-[#d67208] text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
            Klaim Voucher
        </button>
    </div>

    {{-- Filter Tab --}}
    <div class="flex overflow-x-auto scrollbar-hide gap-2 mb-6 pb-2 border-b border-gray-100">
        <button class="px-5 py-2.5 text-sm font-bold text-[#F5820A] border-b-2 border-[#F5820A] whitespace-nowrap">Semua (5)</button>
        <button class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-[#F5820A] whitespace-nowrap transition-colors">Gratis Ongkir</button>
        <button class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-[#F5820A] whitespace-nowrap transition-colors">Diskon & Cashback</button>
    </div>

    {{-- Grid List Voucher --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
        
        {{-- Voucher 1: Diskon --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex shadow-sm hover:shadow-md transition-shadow relative">
            <div class="w-24 sm:w-32 bg-gradient-to-br from-[#F5820A] to-[#E06E00] flex flex-col items-center justify-center text-white shrink-0 p-3 border-r-[3px] border-dashed border-white">
                <i class="fa-solid fa-percent text-2xl sm:text-3xl mb-2"></i>
                <span class="text-[10px] sm:text-xs font-bold text-center leading-tight">Diskon<br>Koperasi</span>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">Diskon 10% s.d Rp 20.000</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Min. belanja Rp 100.000. Berlaku untuk Sembako.</p>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] sm:text-[11px] font-bold text-red-500 flex items-center gap-1"><i class="fa-regular fa-clock"></i> Berakhir besok!</span>
                    <button class="text-xs font-bold text-[#F5820A] border border-[#F5820A] bg-orange-50 hover:bg-[#F5820A] hover:text-white px-4 py-1.5 rounded-lg transition-colors">Pakai</button>
                </div>
            </div>
        </div>

        {{-- Voucher 2: Gratis Ongkir --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex shadow-sm hover:shadow-md transition-shadow relative">
            <div class="w-24 sm:w-32 bg-gradient-to-br from-[#2D7A42] to-[#1E5C2F] flex flex-col items-center justify-center text-white shrink-0 p-3 border-r-[3px] border-dashed border-white">
                <i class="fa-solid fa-truck-fast text-2xl sm:text-3xl mb-2"></i>
                <span class="text-[10px] sm:text-xs font-bold text-center leading-tight">Gratis<br>Ongkir</span>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">Gratis Ongkir s.d Rp 15.000</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Min. belanja Rp 50.000. Semua kategori.</p>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] sm:text-[11px] font-medium text-gray-400">Berakhir 15 Jul 2026</span>
                    <button class="text-xs font-bold text-[#F5820A] border border-[#F5820A] bg-orange-50 hover:bg-[#F5820A] hover:text-white px-4 py-1.5 rounded-lg transition-colors">Pakai</button>
                </div>
            </div>
        </div>

        {{-- Voucher 3: Diskon Member --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex shadow-sm hover:shadow-md transition-shadow relative">
            <div class="w-24 sm:w-32 bg-gradient-to-br from-[#F5820A] to-[#E06E00] flex flex-col items-center justify-center text-white shrink-0 p-3 border-r-[3px] border-dashed border-white">
                <i class="fa-solid fa-star text-2xl sm:text-3xl mb-2"></i>
                <span class="text-[10px] sm:text-xs font-bold text-center leading-tight">Khusus<br>Member</span>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">Potongan Langsung Rp 10.000</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Tanpa min. belanja. Khusus member Koperasi.</p>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] sm:text-[11px] font-medium text-gray-400">Berakhir 30 Jul 2026</span>
                    <button class="text-xs font-bold text-[#F5820A] border border-[#F5820A] bg-orange-50 hover:bg-[#F5820A] hover:text-white px-4 py-1.5 rounded-lg transition-colors">Pakai</button>
                </div>
            </div>
        </div>

        {{-- Voucher 4: Cashback --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex shadow-sm hover:shadow-md transition-shadow relative">
            <div class="w-24 sm:w-32 bg-gradient-to-br from-blue-500 to-blue-600 flex flex-col items-center justify-center text-white shrink-0 p-3 border-r-[3px] border-dashed border-white">
                <i class="fa-solid fa-coins text-2xl sm:text-3xl mb-2"></i>
                <span class="text-[10px] sm:text-xs font-bold text-center leading-tight">Cashback<br>Poin</span>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">Cashback 5% s.d 10.000 Poin</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Min. belanja Rp 150.000. Poin langsung masuk.</p>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] sm:text-[11px] font-medium text-gray-400">Berakhir 20 Jul 2026</span>
                    <button class="text-xs font-bold text-[#F5820A] border border-[#F5820A] bg-orange-50 hover:bg-[#F5820A] hover:text-white px-4 py-1.5 rounded-lg transition-colors">Pakai</button>
                </div>
            </div>
        </div>

        {{-- Voucher 5: Gratis Ongkir (Masa Berlaku Habis / Disabled) --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden flex shadow-sm relative opacity-60">
            <div class="w-24 sm:w-32 bg-gray-400 flex flex-col items-center justify-center text-white shrink-0 p-3 border-r-[3px] border-dashed border-white">
                <i class="fa-solid fa-truck-fast text-2xl sm:text-3xl mb-2"></i>
                <span class="text-[10px] sm:text-xs font-bold text-center leading-tight">Gratis<br>Ongkir</span>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between relative">
                {{-- Watermark Habis --}}
                <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                    <span class="border-2 border-red-500 text-red-500 text-lg font-black uppercase px-3 py-1 rounded-lg transform -rotate-12 opacity-80">KADALUARSA</span>
                </div>
                
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-700">Gratis Ongkir s.d Rp 20.000</h3>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Min. belanja Rp 100.000.</p>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-[10px] sm:text-[11px] font-medium text-gray-500">Berakhir 01 Jul 2026</span>
                    <button disabled class="text-xs font-bold text-gray-400 bg-gray-200 px-4 py-1.5 rounded-lg cursor-not-allowed">Pakai</button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection