@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Detail Simpanan</h1>
            <p class="text-gray-500 text-sm">Pantau saldo dan riwayat setoran simpanan Anda di Koperasi 6G.</p>
        </div>
    </div>

    {{-- Kartu Total Simpanan (Tema Hijau Koperasi) --}}
    <div class="bg-gradient-to-br from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 lg:p-8 text-white shadow-md mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
        <i class="fa-solid fa-piggy-bank absolute -right-4 -bottom-6 text-9xl opacity-10"></i>
        
        <div class="relative z-10 w-full md:w-auto text-left">
            <div class="flex items-center gap-2 mb-2">
                <p class="text-sm font-semibold text-green-100 uppercase tracking-wider">Total Saldo Simpanan</p>
                <span class="bg-white/20 backdrop-blur-md px-2 py-0.5 rounded text-[10px] font-bold">
                    <i class="fa-solid fa-shield-check mr-1"></i>Aman
                </span>
            </div>
            <h2 class="text-4xl lg:text-5xl font-extrabold mb-3">Rp 4.500.000</h2>
            <p class="text-xs text-green-100">
                Terakhir diperbarui pada: 05 Jul 2026, 09:41 WIB
            </p>
        </div>

        <div class="relative z-10 w-full md:w-auto shrink-0 mt-2 md:mt-0">
            <button class="w-full md:w-auto px-6 py-3.5 bg-white text-[#2D7A42] font-bold text-sm rounded-xl hover:bg-gray-50 hover:scale-105 transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus-circle text-lg"></i> Setor Simpanan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        {{-- ========================================= --}}
        {{-- KIRI: Rincian Alokasi Simpanan            --}}
        {{-- ========================================= --}}
        <div class="lg:col-span-1 space-y-5">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Alokasi Dana</h3>
            
            {{-- Simpanan Sukarela --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm relative overflow-hidden group hover:border-[#2D7A42] transition-colors">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#2D7A42]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center border border-[#2D7A42]/20">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>
                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md uppercase">Bisa Ditarik</span>
                </div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Simpanan Sukarela</p>
                <h4 class="text-2xl font-extrabold text-gray-900 mb-2">Rp 2.500.000</h4>
                <p class="text-[11px] text-gray-400 mb-5 leading-relaxed">Dana bebas yang dapat Anda setor dan tarik kapan saja.</p>
                
                <button class="w-full py-2.5 bg-gray-50 border border-gray-200 hover:bg-[#2D7A42] hover:border-[#2D7A42] hover:text-white text-gray-700 text-xs font-bold rounded-xl transition-all">
                    Tarik Dana Sukarela
                </button>
            </div>

            {{-- Simpanan Wajib & Pokok --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <div class="space-y-5">
                    {{-- Wajib --}}
                    <div class="pb-5 border-b border-gray-100 relative">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-xs font-semibold text-gray-500">Simpanan Wajib</p>
                            <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Rutin</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">Rp 1.500.000</h4>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-emerald-400 h-1.5 rounded-full" style="width: 100%"></div>
                            </div>
                            <span class="text-[10px] font-medium text-emerald-600 whitespace-nowrap">Bulan ini Lunas</span>
                        </div>
                    </div>
                    
                    {{-- Pokok --}}
                    <div class="relative">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-xs font-semibold text-gray-500">Simpanan Pokok</p>
                            <span class="text-[10px] font-bold text-[#2D7A42] bg-[#E8F5EC] px-2 py-0.5 rounded"><i class="fa-solid fa-check mr-1"></i>Lunas</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">Rp 500.000</h4>
                        <p class="text-[11px] text-gray-400 mt-1">Dibayarkan satu kali pada saat pendaftaran member.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- KANAN: Riwayat Setoran                    --}}
        {{-- ========================================= --}}
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6 h-fit">
            <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Transaksi</h3>
                <button class="text-xs font-bold text-gray-500 border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    Bulan Ini <i class="fa-solid fa-chevron-down ml-1"></i>
                </button>
            </div>
            
            <div class="space-y-1">
                {{-- Riwayat 1 --}}
                <div class="flex items-center gap-4 p-3 -mx-3 rounded-xl hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                    <div class="w-10 h-10 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0 group-hover:bg-[#d4eadc] transition-colors">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Setoran Simpanan Sukarela</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Transfer via Bank BCA</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-[#2D7A42]">+ Rp 500.000</span>
                        <p class="text-[10px] font-medium text-gray-400 mt-1">05 Jul 2026</p>
                    </div>
                </div>

                {{-- Riwayat 2 --}}
                <div class="flex items-center gap-4 p-3 -mx-3 rounded-xl hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                    <div class="w-10 h-10 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0 group-hover:bg-[#d4eadc] transition-colors">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Setoran Simpanan Wajib (Juli)</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Potong otomatis dari Saldo Koperasi</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-[#2D7A42]">+ Rp 100.000</span>
                        <p class="text-[10px] font-medium text-gray-400 mt-1">01 Jul 2026</p>
                    </div>
                </div>
                
                {{-- Riwayat 3 --}}
                <div class="flex items-center gap-4 p-3 -mx-3 rounded-xl hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center shrink-0 group-hover:bg-red-100 transition-colors">
                        <i class="fa-solid fa-arrow-up"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Penarikan Simpanan Sukarela</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Ditransfer ke Bank BNI</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-gray-900">- Rp 200.000</span>
                        <p class="text-[10px] font-medium text-gray-400 mt-1">15 Jun 2026</p>
                    </div>
                </div>

                {{-- Riwayat 4 --}}
                <div class="flex items-center gap-4 p-3 -mx-3 rounded-xl hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                    <div class="w-10 h-10 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0 group-hover:bg-[#d4eadc] transition-colors">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Bagi Hasil SHU 2025</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Masuk ke Simpanan Sukarela</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-[#2D7A42]">+ Rp 320.000</span>
                        <p class="text-[10px] font-medium text-gray-400 mt-1">20 Jan 2026</p>
                    </div>
                </div>
            </div>

            <button class="w-full mt-4 py-2.5 bg-white border border-gray-200 hover:border-[#2D7A42] hover:text-[#2D7A42] text-gray-600 font-semibold text-sm rounded-xl transition-all shadow-sm">
                Lihat Semua Riwayat Transaksi
            </button>
        </div>

    </div>
</div>
@endsection