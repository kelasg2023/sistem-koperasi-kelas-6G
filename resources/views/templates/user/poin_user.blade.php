@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto" x-data="poinPage()">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#FFF3E0] hover:text-[#F5820A] transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Poin Reward</h1>
            <p class="text-gray-500 text-sm">Kumpulkan poin dari setiap transaksi dan tukarkan dengan hadiah menarik.</p>
        </div>
    </div>

    {{-- Kartu Total Poin (Tema Emas/Orange Khusus) --}}
    <div class="bg-gradient-to-br from-[#F5820A] to-[#D96B00] rounded-2xl p-6 lg:p-8 text-white shadow-md mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
        <i class="fa-solid fa-star absolute -right-4 -bottom-6 text-9xl opacity-15"></i>
        
        <div class="relative z-10 w-full md:w-auto text-left">
            <div class="flex items-center gap-2 mb-2">
                <p class="text-sm font-semibold text-white/90 uppercase tracking-wider">Total Poin Aktif</p>
                <span x-show="isMember" class="bg-white/20 backdrop-blur-md px-2 py-0.5 rounded text-[10px] font-bold" x-cloak>
                    <i class="fa-solid fa-crown mr-1 text-[#FFD700]"></i>Member
                </span>
            </div>
            <h2 class="text-4xl lg:text-5xl font-extrabold mb-1 tracking-tight">
                <span x-text="isLoading ? '...' : poin"></span> <span class="text-2xl font-bold opacity-80">Pts</span>
            </h2>
            <p class="text-sm text-white/90 font-medium mt-2" x-show="!isLoading">
                <i class="fa-solid fa-equals text-xs opacity-70 mr-1"></i> Nilai tukar: <span x-text="formatRupiah(poin * 10)"></span>
            </p>
        </div>

        <div class="relative z-10 w-full md:w-auto shrink-0 mt-2 md:mt-0 flex gap-3">
            <button class="w-full md:w-auto px-6 py-3.5 bg-white text-[#F5820A] font-bold text-sm rounded-xl hover:bg-gray-50 hover:scale-105 transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-gift text-lg"></i> Tukar Poin
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        {{-- ========================================= --}}
        {{-- KIRI: Info & Misi Mendapatkan Poin        --}}
        {{-- ========================================= --}}
        <div class="lg:col-span-1 space-y-5">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Cara Mendapatkan Poin</h3>
            
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm space-y-4">
                
                {{-- Misi 1 --}}
                <div class="flex items-start gap-3 pb-4 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Belanja Produk</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Dapatkan 1 Poin untuk setiap pembelanjaan kelipatan Rp 10.000.</p>
                    </div>
                </div>

                {{-- Misi 2 --}}
                <div class="flex items-start gap-3 pb-4 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-full bg-[#E3F2FD] text-[#1E88E5] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-piggy-bank text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Setor Simpanan</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Bonus 50 Poin setiap kali membayar simpanan wajib tepat waktu.</p>
                    </div>
                </div>

                {{-- Misi 3 --}}
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#F3E5F5] text-[#8E24AA] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Ajak Teman</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Dapatkan 100 Poin jika teman mendaftar menggunakan kode referral Anda.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ========================================= --}}
        {{-- KANAN: Riwayat Poin (Static For Now)      --}}
        {{-- ========================================= --}}
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6 h-fit">
            <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Poin</h3>
                <button class="text-xs font-bold text-gray-500 border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    Terbaru <i class="fa-solid fa-chevron-down ml-1"></i>
                </button>
            </div>
            
                        <h4 class="text-sm font-bold text-gray-800">Tukar Voucher Gratis Ongkir</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Klaim reward</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-gray-900">- 200 Pts</span>
                        <p class="text-[10px] font-medium text-gray-400 mt-1">28 Jun 2026</p>
                    </div>
                </div>

                {{-- Riwayat 4 (Dapat Poin) --}}
                <div class="flex items-center gap-4 p-3 -mx-3 rounded-xl hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                    <div class="w-10 h-10 rounded-full bg-[#FFF3E0] text-[#F5820A] flex items-center justify-center shrink-0 group-hover:bg-[#FFE0B2] transition-colors">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Cashback Pembelanjaan</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Transaksi INV-150626-089</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-[#F5820A]">+ 120 Pts</span>
                        <p class="text-[10px] font-medium text-gray-400 mt-1">15 Jun 2026</p>
                    </div>
                </div>
            </div>

            <button class="w-full mt-4 py-2.5 bg-white border border-gray-200 hover:border-[#F5820A] hover:text-[#F5820A] text-gray-600 font-semibold text-sm rounded-xl transition-all shadow-sm">
                Lihat Semua Riwayat
            </button>
        </div>

    </div>
</div>
@endsection