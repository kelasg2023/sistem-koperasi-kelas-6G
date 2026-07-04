@php
// Mock data untuk simulasi data dinamis dari Controller Laravel
// Anda dapat menghapus blok php ini setelah menghubungkannya dengan database Anda

$categories = [
    ['id' => 1, 'name' => 'Semua Produk', 'checked' => true],
    ['id' => 2, 'name' => 'Beras', 'checked' => false],
    ['id' => 3, 'name' => 'Minyak Goreng', 'checked' => false],
    ['id' => 4, 'name' => 'Gula & Garam', 'checked' => false],
    ['id' => 5, 'name' => 'Tepung', 'checked' => false],
];

$brands = [
    ['id' => 1, 'name' => 'Rose Brand', 'checked' => true],
    ['id' => 2, 'name' => 'Bimoli', 'checked' => false],
    ['id' => 3, 'name' => 'Anak Raja', 'checked' => false],
    ['id' => 4, 'name' => 'Gulaku', 'checked' => false],
    ['id' => 5, 'name' => 'Topi Koki', 'checked' => false],
];

$products = [
    [
        'id' => 1,
        'name' => 'Beras Premium 5kg',
        'category' => 'Beras',
        'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Beras+Premium', // Ganti dengan URL gambar asli
        'original_price' => 85000,
        'member_price' => 79500,
        'promo' => 'PROMO'
    ],
    [
        'id' => 2,
        'name' => 'Minyak Goreng 2L',
        'category' => 'Minyak',
        'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Minyak+Goreng',
        'original_price' => 38000,
        'member_price' => 34200,
        'promo' => null
    ],
    [
        'id' => 3,
        'name' => 'Gula Pasir Lokal 1kg',
        'category' => 'Gula',
        'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Gula+Pasir',
        'original_price' => 16500,
        'member_price' => 15000,
        'promo' => null
    ],
    [
        'id' => 4,
        'name' => 'Telur Ayam Negeri 1kg',
        'category' => 'Telur',
        'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Telur+Ayam',
        'original_price' => 28000,
        'member_price' => 26500,
        'promo' => null
    ],
    [
        'id' => 5,
        'name' => 'Garam Meja 500g',
        'category' => 'Bumbu',
        'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Garam+Meja',
        'original_price' => 5500,
        'member_price' => 4800,
        'promo' => null
    ],
    [
        'id' => 6,
        'name' => 'Tepung Terigu 1kg',
        'category' => 'Tepung',
        'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Tepung+Terigu',
        'original_price' => 14000,
        'member_price' => 12900,
        'promo' => null
    ],
];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Produk</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Breadcrumbs -->
        <nav class="text-sm mb-6 text-gray-500">
            <span class="hover:text-green-800 cursor-pointer">Beranda</span> 
            <span class="mx-1">&gt;</span> 
            <span class="text-green-700 font-semibold">Produk</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar Filter -->
            <aside class="lg:col-span-1 bg-white p-5 rounded-xl border border-gray-100 shadow-sm self-start">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Filter</h2>
                    <button class="text-xs text-green-700 font-semibold hover:underline">Reset</button>
                </div>

                <!-- Kategori -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Kategori</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <label class="flex items-center space-x-3 cursor-pointer text-sm text-gray-600">
                                <input type="checkbox" {{ $category['checked'] ? 'checked' : '' }} class="w-4 h-4 rounded text-green-700 focus:ring-green-700 border-gray-300 accent-[#146e37]">
                                <span class="{{ $category['checked'] ? 'text-gray-900 font-medium' : '' }}">{{ $category['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Urutkan Berdasarkan -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Urutkan Berdasarkan</h3>
                    <div class="relative">
                        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent appearance-none">
                            <option>Paling Relevan</option>
                            <option>Harga Terendah</option>
                            <option>Harga Tertinggi</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Harga</h3>
                    <div class="space-y-3">
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-xs text-gray-400">Rp</span>
                            <input type="text" placeholder="Min" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-1.5 pl-8 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-xs text-gray-400">Rp</span>
                            <input type="text" placeholder="Max" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-1.5 pl-8 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Merek -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Merek</h3>
                    <div class="space-y-2">
                        @foreach($brands as $brand)
                            <label class="flex items-center space-x-3 cursor-pointer text-sm text-gray-600">
                                <input type="checkbox" {{ $brand['checked'] ? 'checked' : '' }} class="w-4 h-4 rounded text-green-700 focus:ring-green-700 border-gray-300 accent-[#146e37]">
                                <span class="{{ $brand['checked'] ? 'text-gray-900 font-medium' : '' }}">{{ $brand['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Rating -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Rating</h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer">
                        <div class="flex text-amber-500">
                            <!-- 4 Bintang Emas -->
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <!-- 1 Bintang Abu-abu -->
                            <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="text-xs text-gray-500">Ke atas</span>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="lg:col-span-3">
                
                <!-- Information Header -->
                <p class="text-sm text-gray-500 mb-4">Menampilkan 24 produk untuk <span class="font-semibold text-gray-700">"Produk"</span></p>

                <!-- Product Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <div class="bg-white border border-gray-100 rounded-xl p-3 flex flex-col justify-between hover:shadow-md transition-shadow duration-200 relative">
                            
                            <!-- Badges (Promo, dll) -->
                            @if($product['promo'])
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="bg-[#bf5a2a] text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                        {{ $product['promo'] }}
                                    </span>
                                </div>
                            @endif

                            <!-- Gambar Produk -->
                            <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center mb-3">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="object-cover w-full h-full">
                            </div>

                            <!-- Detail Deskripsi Produk -->
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <!-- Kategori -->
                                    <span class="text-[11px] text-gray-400 block mb-1 uppercase tracking-wider font-semibold">
                                        {{ $product['category'] }}
                                    </span>
                                    <!-- Nama Produk -->
                                    <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 min-h-[40px]">
                                        {{ $product['name'] }}
                                    </h4>
                                </div>

                                <!-- Informasi Harga -->
                                <div class="mt-2">
                                    <span class="text-xs text-gray-400 line-through">
                                        Rp {{ number_format($product['original_price'], 0, ',', '.') }}
                                    </span>
                                    <p class="text-[10px] font-semibold text-green-700 mt-1">Harga Member</p>
                                    <p class="text-base font-bold text-green-700">
                                        Rp {{ number_format($product['member_price'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Tombol Tambah ke Keranjang -->
                            <button class="w-full bg-[#146e37] hover:bg-[#0e5229] text-white text-xs font-semibold py-2 px-3 rounded-lg flex items-center justify-center gap-1.5 mt-4 transition-colors duration-200">
                                <!-- Ikon Keranjang -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Tambah
                            </button>
                        </div>
                    @endforeach
                </div>
            </main>

        </div>
    </div>

</body>
</html>