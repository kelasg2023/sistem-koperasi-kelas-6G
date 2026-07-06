@extends('layouts.app')

@section('title', 'Membership Koperasi')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-12 max-w-4xl mx-auto">
    
    {{-- Header Halaman --}}
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] hover:border-[#2D7A42]/30 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Membership Koperasi</h1>
            <p class="text-gray-500 text-sm">Cara mudah menjadi anggota Koperasi 6G</p>
        </div>
    </div>

    {{-- Banner Keuntungan (Tema Hijau Koperasi) --}}
    <div class="bg-gradient-to-br from-[#2D7A42] to-[#1A622A] rounded-3xl p-8 lg:p-10 text-white shadow-lg mb-8 relative overflow-hidden flex flex-col items-center text-center">
        {{-- Ornamen Latar --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <i class="fa-solid fa-crown absolute right-10 bottom-10 text-8xl opacity-10 transform -rotate-12 pointer-events-none text-[#FFD700]"></i>
        
        <div class="relative z-10 w-full max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-6 border border-white/20 shadow-inner">
                <i class="fa-solid fa-gem text-4xl text-[#FFD700]"></i>
            </div>
            
            <h2 class="text-3xl font-extrabold mb-4 tracking-tight leading-tight">Selamat Tinggal Birokrasi,<br/>Selamat Datang Kemudahan!</h2>
            <p class="text-white/90 text-sm sm:text-base mb-8 leading-relaxed">
                Di era modern ini, menjadi anggota koperasi tidak perlu lagi mengisi formulir yang tebal atau melampirkan berkas KTP yang merepotkan. Kami menerapkan sistem e-commerce yang cerdas.
            </p>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-8 max-w-lg mx-auto">
                <h3 class="text-xl font-bold mb-2 text-[#FFD700]">Otomatis Menjadi Member</h3>
                <p class="text-sm text-white/90">
                    Lakukan <strong>satu kali transaksi <em>checkout</em></strong> dengan total tagihan minimal <strong class="text-white font-bold text-base">Rp 100.000</strong>, dan akun Anda akan langsung di-upgrade menjadi Member secara otomatis oleh sistem kami!
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-[#2D7A42] hover:bg-gray-50 font-bold rounded-xl transition-all shadow-lg transform hover:-translate-y-1 gap-2">
                <i class="fa-solid fa-cart-shopping"></i> Mulai Belanja Sekarang
            </a>
        </div>
    </div>

    {{-- Grid Keuntungan --}}
    <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Apa yang Anda dapatkan sebagai Member?</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow text-center">
            <div class="w-12 h-12 bg-[#E8F5EC] text-[#2D7A42] rounded-xl flex items-center justify-center text-xl mx-auto mb-4">
                <i class="fa-solid fa-tags"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Akses Voucher Spesial</h4>
            <p class="text-sm text-gray-500">Gunakan kode voucher eksklusif yang hanya bisa diklaim oleh member terdaftar.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow text-center">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-4">
                <i class="fa-solid fa-coins"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Kumpulkan Poin</h4>
            <p class="text-sm text-gray-500">Dapatkan poin (SHU) dari setiap transaksi Anda yang dapat ditukarkan kemudian.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow text-center">
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl mx-auto mb-4">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Prioritas Sistem</h4>
            <p class="text-sm text-gray-500">Mendapatkan prioritas ketersediaan stok barang dan rekomendasi machine learning.</p>
        </div>
    </div>

</div>
@endsection
