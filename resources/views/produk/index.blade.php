@extends('layouts.app')

@section('no-sidebar', true)

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-700 to-green-500 rounded-3xl p-6 sm:p-8 text-white mb-6 sm:mb-8 shadow-lg">
        <h1 class="text-3xl sm:text-4xl font-bold mb-2">
            🛒 Produk Koperasi 6G
        </h1>
        <p class="opacity-90 text-sm sm:text-base">
            Temukan kebutuhan sehari-hari dengan harga terbaik khusus anggota koperasi.
        </p>
    </div>

    {{-- INFO FILTER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">

        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                Katalog Produk
            </h2>

            <p class="text-gray-500 text-sm sm:text-base">
                Kategori aktif :
                <span id="kategoriAktif" class="font-bold text-green-600">Semua</span>
            </p>
        </div>

        <div>
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold text-sm sm:text-base inline-block">
                Total Produk : <span id="jumlahProduk">{{ count($products) }}</span>
            </span>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-3xl shadow-lg p-5 sm:p-6 mb-6 sm:mb-8 overflow-x-auto scrollbar-hide">

        <h3 class="font-bold text-lg mb-4 sm:mb-5">
            Filter Kategori
        </h3>

        <div class="flex sm:grid sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-5 min-w-max sm:min-w-0">

            <div onclick="setActiveFilter(this,'all')" class="filter-box" data-kategori-value="all">
                📦 <span>Semua</span>
            </div>

            <div onclick="setActiveFilter(this,'Sembako')" class="filter-box" data-kategori-value="Sembako">
                🌾 <span>Sembako</span>
            </div>

            <div onclick="setActiveFilter(this,'Minuman')" class="filter-box" data-kategori-value="Minuman">
                🥤 <span>Minuman</span>
            </div>

            <div onclick="setActiveFilter(this,'Sayuran')" class="filter-box" data-kategori-value="Sayuran">
                🥦 <span>Sayuran</span>
            </div>

            <div onclick="setActiveFilter(this,'Buah')" class="filter-box" data-kategori-value="Buah">
                🍎 <span>Buah</span>
            </div>

            <div onclick="setActiveFilter(this,'Minyak')" class="filter-box" data-kategori-value="Minyak">
                🛢️ <span>Minyak</span>
            </div>

        </div>

    </div>

    {{-- GRID PRODUK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-7" id="produkGrid">

        @foreach($products as $product)
        <div class="produk-card bg-white rounded-3xl overflow-hidden shadow hover:shadow-2xl transition duration-300 hover:-translate-y-2 flex flex-col h-full" data-kategori="{{ $product['kategori'] }}">

            <img src="https://picsum.photos/seed/{{ $product['id'] ?? rand() }}/400/300" class="w-full h-48 sm:h-64 object-cover" alt="{{ $product['nama'] }}">

            <div class="p-4 sm:p-5 flex flex-col flex-grow">

                <div class="flex justify-between items-center mb-3">
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold whitespace-nowrap">
                        {{ $product['icon'] }} {{ $product['kategori'] }}
                    </span>
                    <span class="text-yellow-500 text-xs sm:text-sm font-semibold">
                        ⭐ 4.8
                    </span>
                </div>

                <h2 class="font-bold text-base sm:text-lg text-gray-900 leading-tight">
                    {{ $product['nama'] }}
                </h2>

                <p class="text-gray-500 text-xs sm:text-sm mt-2 flex-grow">
                    {{ Str::limit($product['deskripsi'], 70) }}
                </p>

                <div class="mt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-green-700 font-bold text-lg sm:text-xl">
                            {{ $product['harga'] }}
                        </h3>
                        <small class="text-gray-400 block mt-0.5">Stok tersedia</small>
                    </div>

                    <a href="{{ route('produk.show', $product['slug']) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 sm:py-2.5 rounded-xl transition w-full sm:w-auto text-center font-semibold text-sm">
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

// Aktifkan filter otomatis kalau halaman ini dibuka lewat link kategori
// (misalnya dari dashboard) yang mengirim slug kategori dari server.
document.addEventListener('DOMContentLoaded', function () {
    const activeKategoriName = @json($activeKategoriName ?? null);

    if (activeKategoriName) {
        const targetBox = document.querySelector(
            `.filter-box[data-kategori-value="${activeKategoriName}"]`
        );
        if (targetBox) {
            setActiveFilter(targetBox, activeKategoriName);
            return;
        }
    }

    // Default: tampilkan "Semua" sebagai aktif
    const allBox = document.querySelector('.filter-box[data-kategori-value="all"]');
    if (allBox) {
        setActiveFilter(allBox, 'all');
    }
});

</script>

@endsection