@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row gap-6">
        
        <!-- Gambar Produk di kiri -->
        <div class="md:w-1/3">
            <img src="{{ asset('images/'.$product['gambar']) }}" 
                 alt="{{ $product['nama'] }}" 
                 class="w-full h-auto object-contain rounded">
        </div>

        <!-- Info Produk di kanan -->
        <div class="md:w-2/3 space-y-3">
            <h2 class="text-2xl font-bold">{{ $product['nama'] }}</h2>

            <!-- Badge kategori -->
            <span class="inline-block px-2 py-1 text-xs rounded
                @if($product['kategori'] == 'Sayuran') bg-green-100 text-green-700 
                @elseif($product['kategori'] == 'Buah') bg-orange-100 text-orange-700 
                @elseif($product['kategori'] == 'Minuman') bg-blue-100 text-blue-700 
                @elseif($product['kategori'] == 'Minyak') bg-yellow-100 text-yellow-700 
                @else bg-gray-100 text-gray-700 @endif">
                {{ $product['icon'] }} {{ $product['kategori'] }}
            </span>

            <p><strong>Berat:</strong> {{ $product['berat'] }}</p>
            <p><strong>Stok:</strong> {{ $product['stok'] }}</p>
            <p class="text-gray-600">{{ $product['deskripsi'] }}</p>

            <h4 class="text-xl font-bold text-green-600">
                Rp {{ number_format($product['harga'], 0, ',', '.') }}
            </h4>

            <!-- Tombol Aksi -->
            <div class="flex gap-3 mt-4">
                <a href="{{ route('produk.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                   Kembali
                </a>
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Tambah ke Keranjang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
