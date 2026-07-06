<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Koperasi 6G</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased" x-data="cartPage()">

    {{-- HEADER KHUSUS KERANJANG (Tanpa Navbar & Sidebar) --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4 max-w-6xl h-16 flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors text-gray-600">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <div class="flex items-center gap-2.5 text-xl font-extrabold text-[#2D7A42] border-l-2 border-gray-300 pl-4">
                <i class="fa-solid fa-seedling"></i>
                Koperasi 6G
            </div>
            <h1 class="text-lg font-bold text-gray-700 ml-4 hidden sm:block">Keranjang Belanja</h1>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-6">
            
            {{-- BAGIAN KIRI: DAFTAR PRODUK --}}
            <div class="w-full lg:w-2/3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- Header Aksi --}}
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50" x-show="cartItems.length > 0">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="checkAll" @change="toggleAll" class="w-5 h-5 rounded border-gray-300 text-[#2D7A42] focus:ring-[#2D7A42]">
                        <span class="text-sm font-semibold text-gray-700">Pilih Semua</span>
                    </label>
                    <button type="button" @click="removeSelected" x-show="hasSelected" class="text-sm font-semibold text-red-500 hover:text-red-700" x-cloak>
                        Hapus Terpilih
                    </button>
                </div>

                {{-- List Item Keranjang --}}
                <div class="p-4 space-y-4">
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex items-start gap-4 py-4 border-b border-gray-50 last:border-0">
                            <input type="checkbox" x-model="item.selected" @change="updateSelection" class="w-5 h-5 rounded border-gray-300 text-[#2D7A42] focus:ring-[#2D7A42] mt-2 cursor-pointer">
                            <img :src="item.image || 'https://placehold.co/100x100/e2e8f0/475569?text=Produk'" class="w-20 h-20 rounded-xl object-cover border border-gray-100">
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-800" x-text="item.name"></h3>
                                <p class="text-sm text-gray-500 mb-2" x-text="item.category || 'Kategori'"></p>
                                <p class="text-[15px] font-extrabold text-[#2D7A42]" x-text="formatRupiah(item.price)"></p>
                            </div>
                            <div class="flex flex-col items-end gap-3">
                                <button @click="removeItem(item.id)" type="button" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <div class="flex items-center border border-gray-200 rounded-lg">
                                    <button @click="updateQty(item.id, -1)" type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-l-lg">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" x-model.number="item.qty" @change="saveCart" class="w-12 h-8 text-center text-sm font-semibold border-x-0 border-y-0 p-0 focus:ring-0 appearance-none text-gray-800">
                                    <button @click="updateQty(item.id, 1)" type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-r-lg">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="cartItems.length === 0" class="text-center py-10 text-gray-500" x-cloak>
                        <i class="fa-solid fa-cart-arrow-down text-4xl mb-4 text-gray-300"></i>
                        <p>Keranjang belanja kamu masih kosong.</p>
                        <a href="{{ route('produk.index') }}" class="inline-block mt-4 text-[#2D7A42] font-bold hover:underline">Mulai Belanja</a>
                    </div>
                </div>
            </div>

            {{-- BAGIAN KANAN: RINGKASAN BELANJA --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Belanja</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center text-gray-600">
                            <span class="text-sm">Total Barang Terpilih</span>
                            <span class="text-sm font-semibold" x-text="totalSelectedQty + ' Barang'"></span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600 border-t border-gray-100 pt-3">
                            <span class="text-base font-bold text-gray-800">Total Harga</span>
                            <span class="text-lg font-extrabold text-[#2D7A42]" x-text="formatRupiah(totalPrice)"></span>
                        </div>
                    </div>

                    <button @click="checkout" type="button" class="w-full bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold py-3.5 px-4 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!hasSelected">
                        Beli Sekarang
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cartPage', () => ({
            cartItems: [],
            checkAll: false,

            init() {
                const stored = localStorage.getItem('koperasi_cart');
                if (stored) {
                    try {
                        this.cartItems = JSON.parse(stored);
                        // Default check all if loaded
                        if (this.cartItems.length > 0 && !this.cartItems.some(i => i.selected === false)) {
                            this.checkAll = true;
                        } else {
                            this.updateSelection();
                        }
                    } catch (e) {
                        this.cartItems = [];
                    }
                }
            },

            saveCart() {
                localStorage.setItem('koperasi_cart', JSON.stringify(this.cartItems));
                this.updateSelection();
            },

            toggleAll() {
                this.cartItems.forEach(item => item.selected = this.checkAll);
                this.saveCart();
            },

            updateSelection() {
                if (this.cartItems.length > 0) {
                    this.checkAll = this.cartItems.every(item => item.selected);
                } else {
                    this.checkAll = false;
                }
                this.saveCart();
            },

            updateQty(id, change) {
                const item = this.cartItems.find(i => i.id === id);
                if (item) {
                    if (item.qty + change > 0) {
                        item.qty += change;
                    } else if (item.qty + change === 0) {
                        if (confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
                            this.cartItems = this.cartItems.filter(i => i.id !== id);
                        }
                    }
                    this.saveCart();
                }
            },

            removeItem(id) {
                if (confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
                    this.cartItems = this.cartItems.filter(i => i.id !== id);
                    this.saveCart();
                }
            },

            removeSelected() {
                if (confirm('Hapus semua produk yang dipilih?')) {
                    this.cartItems = this.cartItems.filter(i => !i.selected);
                    this.saveCart();
                }
            },

            get hasSelected() {
                return this.cartItems.some(item => item.selected);
            },

            get totalSelectedQty() {
                return this.cartItems.filter(i => i.selected).reduce((sum, item) => sum + item.qty, 0);
            },

            get totalPrice() {
                return this.cartItems.filter(i => i.selected).reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka || 0);
            },

            checkout() {
                // Simpan item yang dipilih ke localStorage 'checkout_items'
                const selectedItems = this.cartItems.filter(i => i.selected);
                if (selectedItems.length === 0) return;
                
                localStorage.setItem('checkout_items', JSON.stringify(selectedItems));
                
                // Redirect ke halaman checkout
                window.location.href = "{{ route('checkout.index') }}";
            }
        }));
    });
    </script>
    @include('templates.toast')
</body>
</html>