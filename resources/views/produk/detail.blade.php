@extends('layouts.app')

@section('content')
@extends('layouts.app')

@section('no-sidebar', true)

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-6 sm:py-10 max-w-5xl">
    <div class="bg-white shadow-xl rounded-2xl sm:rounded-3xl p-5 sm:p-8 lg:p-10 flex flex-col md:flex-row gap-6 lg:gap-10 border border-gray-100">
        
        <!-- Gambar Produk di kiri -->
        <div class="md:w-5/12 lg:w-1/2 flex justify-center items-start">
            <img src="https://picsum.photos/seed/{{ $product['id'] ?? rand() }}/600/600" 
                 alt="{{ $product['nama'] }}" 
                 class="w-full max-w-[400px] md:max-w-full aspect-square object-cover rounded-2xl shadow-md border border-gray-100">
        </div>

        <!-- Info Produk di kanan -->
        <div class="md:w-7/12 lg:w-1/2 flex flex-col">
            <div class="flex-grow space-y-4">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $product['nama'] }}
                </h2>

                <!-- Badge kategori -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-3 py-1.5 text-xs sm:text-sm font-bold rounded-full
                        @if($product['kategori'] == 'Sayuran') bg-green-100 text-green-700 
                        @elseif($product['kategori'] == 'Buah') bg-orange-100 text-orange-700 
                        @elseif($product['kategori'] == 'Minuman') bg-blue-100 text-blue-700 
                        @elseif($product['kategori'] == 'Minyak') bg-yellow-100 text-yellow-700 
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ $product['icon'] }} {{ $product['kategori'] }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 text-xs sm:text-sm font-bold rounded-full">
                        ⭐ 4.8
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100 mt-4">
                    <div>
                        <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Berat</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800">{{ $product['berat'] }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Stok</p>
                        <p class="text-sm sm:text-base font-semibold {{ ($product['stok'] > 0) ? 'text-green-600' : 'text-red-500' }}">
                            {{ $product['stok'] }} tersedia
                        </p>
                    </div>
                </div>

                <div>
                    <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Produk</p>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                        {{ $product['deskripsi'] }}
                    </p>
                </div>
            </div>

            <!-- Harga & Aksi -->
            <div class="mt-6 sm:mt-8 pt-6 border-t border-gray-100">
                <p class="text-[11px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Harga Khusus Anggota</p>
                <h4 class="text-3xl sm:text-4xl font-extrabold text-[#2D7A42] mb-6">
                    Rp {{ number_format($product['harga'], 0, ',', '.') }}
                </h4>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="bg-[#2D7A42] text-white font-bold px-6 py-3.5 rounded-xl hover:bg-[#1E5C2F] transition-colors w-full sm:flex-1 shadow-lg shadow-green-900/20 text-sm sm:text-base flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                    <a href="{{ route('produk.index') }}" 
                       class="bg-white border-2 border-gray-200 text-gray-700 font-bold px-6 py-3.5 rounded-xl hover:bg-gray-50 transition-colors w-full sm:w-auto text-center text-sm sm:text-base">
                       Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
