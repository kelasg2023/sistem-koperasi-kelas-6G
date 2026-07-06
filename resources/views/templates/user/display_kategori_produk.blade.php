@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Breadcrumbs -->
        <nav class="text-sm mb-6 text-gray-500">
            <span class="hover:text-green-800 cursor-pointer">Beranda</span> 
            <span class="mx-1">&gt;</span> 
            <span class="text-green-700 font-semibold">Produk</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar Filter -->
            <form action="{{ route('produk.index') }}" method="GET" class="lg:col-span-1 bg-white p-5 rounded-xl border border-gray-100 shadow-sm self-start" id="filterForm" onsubmit="event.preventDefault(); triggerFilter()">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Filter</h2>
                    <a href="{{ route('produk.index') }}" class="text-xs text-green-700 font-semibold hover:underline">Reset</a>
                </div>

                <!-- Kategori -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Kategori</h3>
                    <div class="space-y-2">
                        @php 
                            $reqKategori = request('kategori');
                            if (!is_array($reqKategori)) {
                                $reqKategori = $reqKategori ? explode(',', $reqKategori) : [];
                            }
                        @endphp
                        @foreach($facets['kategori'] ?? [] as $kategori)
                            <label class="flex items-center justify-between cursor-pointer text-sm text-gray-600 w-full group">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="kategori[]" value="{{ $kategori['id_kategori'] }}" onchange="triggerFilter()" {{ in_array($kategori['id_kategori'], $reqKategori) ? 'checked' : '' }} class="w-4 h-4 rounded text-green-700 focus:ring-green-700 border-gray-300 accent-[#146e37]">
                                    <span class="{{ in_array($kategori['id_kategori'], $reqKategori) ? 'text-gray-900 font-medium' : '' }} group-hover:text-green-700 transition">{{ $kategori['nama_kategori'] }}</span>
                                </div>
                                <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $kategori['count'] }}</span>
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
                            <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="{{ $facets['harga']['min'] ?? 'Min' }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-1.5 pl-8 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-xs text-gray-400">Rp</span>
                            <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="{{ $facets['harga']['max'] ?? 'Max' }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-1.5 pl-8 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full py-1.5 mt-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition">Terapkan Harga</button>
                    </div>
                </div>

                <!-- Merek -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Ketersediaan Stok</h3>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer text-sm text-gray-600">
                            <input type="checkbox" name="in_stock" value="true" onchange="triggerFilter()" {{ request('in_stock') == 'true' ? 'checked' : '' }} class="w-4 h-4 rounded text-green-700 focus:ring-green-700 border-gray-300 accent-[#146e37]">
                            <span class="{{ request('in_stock') == 'true' ? 'text-gray-900 font-medium' : '' }}">Tampilkan Stok Tersedia</span>
                        </label>
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
            </form>

            <!-- Main Content Area -->
            <main class="lg:col-span-3">
                
                <!-- Information Header -->
                <p id="product-info-text" class="text-sm text-gray-500 mb-4">Menampilkan {{ $meta['total'] ?? 0 }} produk {{ request('q') ? 'untuk "'.request('q').'"' : '' }}</p>

                <!-- Product Grid -->
                <div id="product-grid-container" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 transition-opacity duration-300">
                    @forelse($products as $product)
                        <div class="bg-white border border-gray-100 rounded-xl p-3 flex flex-col justify-between hover:shadow-md transition-shadow duration-200 relative">
                            
                            <!-- Badges (Diskon, dll) -->
                            @if(isset($product['diskon_persen']) && $product['diskon_persen'] > 0)
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="bg-[#bf5a2a] text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                        {{ $product['diskon_persen'] }}% OFF
                                    </span>
                                </div>
                            @endif

                            <!-- Gambar Produk -->
                            <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center mb-3">
                                <img src="https://placehold.co/300x300/e2e8f0/475569?text={{ urlencode($product['nama']) }}" alt="{{ $product['nama'] }}" class="object-cover w-full h-full">
                            </div>

                            <!-- Detail Deskripsi Produk -->
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <!-- Kategori -->
                                    <span class="text-[11px] text-gray-400 block mb-1 uppercase tracking-wider font-semibold">
                                        {{ $product['kategori']['nama_kategori'] ?? 'Lainnya' }}
                                    </span>
                                    <!-- Nama Produk -->
                                    <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 min-h-[40px]">
                                        {{ $product['nama'] }}
                                    </h4>
                                </div>

                                <!-- Informasi Harga -->
                                <div class="mt-2">
                                    <p class="text-base font-bold text-green-700">
                                        Rp {{ number_format($product['harga'], 0, ',', '.') }}
                                    </p>
                                    @if($product['stok'] > 0)
                                        <p class="text-[10px] font-medium text-gray-500 mt-1">Stok: {{ $product['stok'] }}</p>
                                    @else
                                        <p class="text-[10px] font-bold text-red-500 mt-1">Stok Habis</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol Tambah ke Keranjang -->
                            <button {{ $product['stok'] <= 0 ? 'disabled' : '' }} 
                                @if($product['stok'] > 0)
                                    x-data @click="$store.cart.add({ id: '{{ $product['barang_id'] }}', name: '{{ addslashes($product['nama']) }}', price: {{ $product['harga'] }}, category: '{{ $product['kategori']['nama_kategori'] ?? 'Lainnya' }}' })"
                                @endif
                                class="{{ $product['stok'] <= 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-[#146e37] hover:bg-[#0e5229] text-white' }} w-full text-xs font-semibold py-2 px-3 rounded-lg flex items-center justify-center gap-1.5 mt-4 transition-colors duration-200">
                                <!-- Ikon Keranjang -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $product['stok'] <= 0 ? 'Habis' : 'Tambah' }}
                            </button>
                        </div>
                    @empty
                        <div class="col-span-2 md:col-span-3 xl:col-span-4 text-center py-10 text-gray-500">
                            Tidak ada produk yang cocok dengan pencarian Anda.
                        </div>
                    @endforelse
                </div>
            </main>

        </div>
    </div>

    <script>
        // Trigger filter action manually
        function triggerFilter() {
            const form = document.getElementById('filterForm');
            const params = new URLSearchParams(new FormData(form));
            const url = form.action + '?' + params.toString();
            fetchFilteredProducts(url);
        }

        // Global function called by both Sidebar Filter and Navbar Search
        window.fetchFilteredProducts = function(url) {
            const gridContainer = document.getElementById('product-grid-container');
            if (gridContainer) {
                gridContainer.style.opacity = '0.4'; // fade effect indicating loading
            }
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update Product Grid
                const newGrid = doc.getElementById('product-grid-container');
                if (newGrid && gridContainer) {
                    gridContainer.innerHTML = newGrid.innerHTML;
                    gridContainer.style.opacity = '1';
                }

                // Update Info Text
                const newInfo = doc.getElementById('product-info-text');
                const oldInfo = document.getElementById('product-info-text');
                if (newInfo && oldInfo) {
                    oldInfo.innerHTML = newInfo.innerHTML;
                }

                // Update Sidebar (Facets count)
                const newSidebar = doc.getElementById('filterForm');
                const oldSidebar = document.getElementById('filterForm');
                if (newSidebar && oldSidebar) {
                    // Update inner HTML of the sidebar to refresh counts, but need to be careful
                    // not to lose focus if user is typing, though sidebar is mostly checkboxes.
                    oldSidebar.innerHTML = newSidebar.innerHTML;
                }
                
                // Update URL without refreshing page
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error fetching filtered products:', error);
                if (gridContainer) gridContainer.style.opacity = '1';
            });
        }

        // Handle browser back button to fetch previous state
        window.addEventListener('popstate', function() {
            fetchFilteredProducts(window.location.href);
        });
    </script>
@endsection