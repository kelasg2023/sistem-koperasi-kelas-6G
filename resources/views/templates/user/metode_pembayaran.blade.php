@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Metode Pembayaran</h1>
            <p class="text-gray-500 text-sm">Kelola daftar rekening bank dan e-wallet untuk kemudahan bertransaksi.</p>
        </div>
        <button class="hidden sm:flex items-center gap-2 px-5 py-2.5 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Baru
        </button>
    </div>

    <div class="space-y-6">
        
        {{-- ========================================= --}}
        {{-- 1. SALDO KOPERASI (METODE UTAMA)            --}}
        {{-- ========================================= --}}
        <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 lg:p-8 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
            <i class="fa-solid fa-wallet absolute -right-6 -top-6 text-8xl opacity-10"></i>
            
            <div class="relative z-10 w-full md:w-auto text-center md:text-left">
                <p class="text-sm font-medium text-green-100 mb-1">Saldo Koperasi Pay</p>
                <h2 class="text-3xl font-extrabold">Rp 1.250.000</h2>
                <div class="inline-flex items-center gap-1.5 text-xs text-green-200 mt-3 bg-black/10 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-circle-check text-green-400"></i> Metode pembayaran utama
                </div>
            </div>
            
            <div class="relative z-10 w-full md:w-auto flex gap-3">
                <button class="w-full md:w-auto px-6 py-3 bg-white text-[#2D7A42] font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-dots mr-1"></i> Top Up Saldo
                </button>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- 2. REKENING BANK                            --}}
        {{-- ========================================= --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">Transfer Bank</h3>
            
            <div class="space-y-4">
                {{-- Bank Item 1 (Tersimpan / Default) --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-2 border-[#2D7A42]/20 bg-[#E8F5EC]/30 rounded-xl transition-colors group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-14 h-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100 shrink-0">
                            <span class="text-blue-700 font-extrabold text-sm italic">BCA</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Bank Central Asia</h4>
                            <p class="text-xs text-gray-500 font-medium">**** **** 1234 a/n Ardian Putra</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0 mt-2 sm:mt-0">
                        <button class="text-xs font-semibold text-gray-400 hover:text-red-500 transition-colors px-2">Hapus</button>
                        <span class="px-3 py-1.5 bg-[#2D7A42] text-white text-[10px] font-bold uppercase rounded-md flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i> Tersimpan
                        </span>
                    </div>
                </div>

                {{-- Bank Item 2 --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-gray-200 rounded-xl hover:border-[#2D7A42] transition-colors group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-14 h-10 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shrink-0">
                            <span class="text-orange-600 font-extrabold text-sm">BNI</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Bank Negara Indonesia</h4>
                            <p class="text-xs text-gray-500 font-medium">**** **** 5678 a/n Ardian Putra</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0 mt-2 sm:mt-0">
                        <button class="text-xs font-semibold text-gray-500 hover:text-red-500 transition-colors px-2">Hapus</button>
                        <button class="px-3 py-1.5 bg-white border border-[#2D7A42] text-[#2D7A42] hover:bg-[#E8F5EC] text-[10px] font-bold uppercase rounded-md transition-colors">
                            Jadikan Utama
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- 3. E-WALLET                               --}}
        {{-- ========================================= --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">E-Wallet</h3>
            
            <div class="space-y-4">
                {{-- GoPay --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-gray-200 rounded-xl hover:border-[#2D7A42] transition-colors group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-14 h-10 bg-[#00AED6] rounded-lg flex items-center justify-center border border-[#00AED6] shrink-0">
                            <span class="text-white font-extrabold text-xs tracking-wider">gopay</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">GoPay</h4>
                            <p class="text-xs text-gray-500 font-medium">0812-****-7890</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <button class="w-full sm:w-auto px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                            Putuskan Koneksi
                        </button>
                    </div>
                </div>
                
                {{-- OVO (Belum terkoneksi) --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-dashed border-gray-300 bg-gray-50/50 rounded-xl group cursor-pointer hover:bg-[#E8F5EC] hover:border-[#2D7A42] transition-all">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0 opacity-60 group-hover:opacity-100 transition-opacity">
                        <div class="w-14 h-10 bg-[#4C3494] rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-white font-extrabold text-xs tracking-wider">OVO</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">OVO</h4>
                            <p class="text-xs text-gray-400 font-medium">Belum terhubung</p>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto flex justify-end">
                        <span class="text-xs font-bold text-[#2D7A42]">Hubungkan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOMBOL TAMBAH UNTUK TAMPILAN MOBILE --}}
        <button class="sm:hidden w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Metode Pembayaran
        </button>

    </div>
</div>
@endsection