@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-700 to-green-500 rounded-3xl p-8 text-white mb-8 shadow-lg">
        <h1 class="text-4xl font-bold mb-2">
            🛒 Produk Koperasi 6G
        </h1>
        <p class="opacity-90">
            Temukan kebutuhan sehari-hari dengan harga terbaik khusus anggota koperasi.
        </p>
    </div>

    {{-- INFO FILTER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Katalog Produk
            </h2>

            <p class="text-gray-500">
                Kategori aktif :
                <span id="kategoriAktif"
                    class="font-bold text-green-600">
                    Semua
                </span>
            </p>
        </div>

        <div class="mt-3 md:mt-0">

            <span
                class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">

                Total Produk :
                <span id="jumlahProduk">
                    {{ count($products) }}
                </span>

            </span>

        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-3xl shadow-lg p-6 mb-8">

        <h3 class="font-bold text-lg mb-5">
            Filter Kategori
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">

            <div onclick="setActiveFilter(this,'all')" class="filter-box active">
                📦
                <span>Semua</span>
            </div>

            <div onclick="setActiveFilter(this,'Sembako')" class="filter-box">
                🌾
                <span>Sembako</span>
            </div>

            <div onclick="setActiveFilter(this,'Minuman')" class="filter-box">
                🥤
                <span>Minuman</span>
            </div>

            <div onclick="setActiveFilter(this,'Sayuran')" class="filter-box">
                🥦
                <span>Sayuran</span>
            </div>

            <div onclick="setActiveFilter(this,'Buah')" class="filter-box">
                🍎
                <span>Buah</span>
            </div>

            <div onclick="setActiveFilter(this,'Minyak')" class="filter-box">
                🛢️
                <span>Minyak</span>
            </div>

        </div>

    </div>

    {{-- GRID PRODUK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7"
        id="produkGrid">

        @foreach($products as $product)

        <div
            class="produk-card bg-white rounded-3xl overflow-hidden shadow hover:shadow-2xl transition duration-300 hover:-translate-y-2"
            data-kategori="{{ $product['kategori'] }}">

            <img
                src="{{ asset('images/'.$product['gambar']) }}"
                class="w-full h-64 object-cover">

            <div class="p-5">

                <div class="flex justify-between items-center">

                    <span
                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

                        {{ $product['icon'] }}
                        {{ $product['kategori'] }}

                    </span>

                    <span class="text-yellow-500">
                        ⭐ 4.8
                    </span>

                </div>

                <h2 class="font-bold text-lg mt-4">

                    {{ $product['nama'] }}

                </h2>

                <p class="text-gray-500 text-sm mt-2">

                    {{ Str::limit($product['deskripsi'],70) }}

                </p>

                <div class="mt-5 flex justify-between items-center">

                    <div>

                        <h3 class="text-green-700 font-bold text-xl">

                            {{ $product['harga'] }}

                        </h3>

                        <small class="text-gray-400">

                            Stok tersedia

                        </small>

                    </div>

                    <a
                        href="{{ route('produk.show',$product['slug']) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition">

                        Detail

                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

<style>

.filter-box{

display:flex;

flex-direction:column;

justify-content:center;

align-items:center;

padding:18px;

cursor:pointer;

border-radius:18px;

border:2px solid #DCFCE7;

background:#F0FDF4;

font-weight:600;

font-size:15px;

transition:.3s;

gap:8px;

}

.filter-box:hover{

transform:translateY(-5px);

background:#DCFCE7;

}

.filter-box.active{

background:#16A34A;

color:white;

border-color:#16A34A;

transform:scale(1.06);

box-shadow:0 10px 25px rgba(22,163,74,.3);

}

.produk-card{

transition:.35s;

}

</style>

<script>

function setActiveFilter(box,kategori){

document.querySelectorAll('.filter-box').forEach(item=>{

item.classList.remove('active');

});

box.classList.add('active');

document.getElementById('kategoriAktif').innerHTML=
kategori=="all" ? "Semua" : kategori;

filterProduk(kategori);

}

function filterProduk(kategori){

const cards=document.querySelectorAll('.produk-card');

let jumlah=0;

cards.forEach(card=>{

if(kategori=="all" || card.dataset.kategori==kategori){

card.style.display="block";

jumlah++;

setTimeout(()=>{

card.style.opacity="1";

card.style.transform="scale(1)";

},100);

}else{

card.style.opacity="0";

card.style.transform="scale(.9)";

setTimeout(()=>{

card.style.display="none";

},200);

}

});

document.getElementById('jumlahProduk').innerHTML=jumlah;

}

</script>

@endsection