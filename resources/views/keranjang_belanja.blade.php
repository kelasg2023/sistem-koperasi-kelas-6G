<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Koperasi 6G</title>
    {{-- Pastikan kamu memanggil Tailwind sesuai konfigurasi proyekmu, contoh menggunakan Vite: --}}
    @vite('resources/css/app.css') 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

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
        <form id="form-keranjang" action="#" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-6">
                
                {{-- BAGIAN KIRI: DAFTAR PRODUK --}}
                <div class="w-full lg:w-2/3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    {{-- Header Aksi --}}
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="check-all" class="w-5 h-5 rounded border-gray-300 text-[#2D7A42] focus:ring-[#2D7A42]">
                            <span class="text-sm font-semibold text-gray-700">Pilih Semua</span>
                        </label>
                        <button type="button" id="btn-hapus-terpilih" class="text-sm font-semibold text-red-500 hover:text-red-700 hidden">
                            Hapus Terpilih
                        </button>
                    </div>

                    {{-- List Item Keranjang --}}
                    <div id="keranjang-container" class="p-4 space-y-4">
                        
                        {{-- Item 1 --}}
                        <div class="cart-item flex items-start gap-4 py-4 border-b border-gray-50 last:border-0" data-id="1" data-harga="68500">
                            <input type="checkbox" name="selected_items[]" value="1" class="item-check w-5 h-5 rounded border-gray-300 text-[#2D7A42] focus:ring-[#2D7A42] mt-2 cursor-pointer">
                            <img src="https://placehold.co/100x100/e2e8f0/475569?text=Beras" class="w-20 h-20 rounded-xl object-cover border border-gray-100">
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-800">Beras Pandan Wangi 5 Kg</h3>
                                <p class="text-sm text-gray-500 mb-2">Sembako</p>
                                <p class="text-[15px] font-extrabold text-[#2D7A42]">Rp 68.500</p>
                            </div>
                            <div class="flex flex-col items-end gap-3">
                                <button type="button" class="text-gray-400 hover:text-red-500 transition-colors btn-hapus-satuan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <div class="flex items-center border border-gray-200 rounded-lg">
                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-l-lg btn-minus">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" name="qty[1]" value="1" class="w-12 h-8 text-center text-sm font-semibold border-x-0 border-y-0 p-0 focus:ring-0 qty-input appearance-none">
                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-r-lg btn-plus">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Item 2 --}}
                        <div class="cart-item flex items-start gap-4 py-4 border-b border-gray-50 last:border-0" data-id="2" data-harga="34000">
                            <input type="checkbox" name="selected_items[]" value="2" class="item-check w-5 h-5 rounded border-gray-300 text-[#2D7A42] focus:ring-[#2D7A42] mt-2 cursor-pointer">
                            <img src="https://placehold.co/100x100/e2e8f0/475569?text=Minyak" class="w-20 h-20 rounded-xl object-cover border border-gray-100">
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-800">Minyak Goreng 2 Liter</h3>
                                <p class="text-sm text-gray-500 mb-2">Minyak</p>
                                <p class="text-[15px] font-extrabold text-[#2D7A42]">Rp 34.000</p>
                            </div>
                            <div class="flex flex-col items-end gap-3">
                                <button type="button" class="text-gray-400 hover:text-red-500 transition-colors btn-hapus-satuan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <div class="flex items-center border border-gray-200 rounded-lg">
                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-l-lg btn-minus">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" name="qty[2]" value="2" class="w-12 h-8 text-center text-sm font-semibold border-x-0 border-y-0 p-0 focus:ring-0 qty-input appearance-none">
                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-r-lg btn-plus">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
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
                                <span class="text-sm font-semibold" id="summary-total-item">0 Barang</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600 border-t border-gray-100 pt-3">
                                <span class="text-base font-bold text-gray-800">Total Harga</span>
                                <span class="text-lg font-extrabold text-[#2D7A42]" id="summary-total-harga">Rp 0</span>
                            </div>
                        </div>

                        <button type="button" id="btn-checkout" class="w-full bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold py-3.5 px-4 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Beli Sekarang
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- LOGIKA JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check-all');
            const btnHapusTerpilih = document.getElementById('btn-hapus-terpilih');
            const keranjangContainer = document.getElementById('keranjang-container');

            const formatRupiah = (angka) => {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            };

            const hitungTotal = () => {
                let totalBarang = 0;
                let totalHarga = 0;
                let adaYangDiceklis = false;
                
                const items = document.querySelectorAll('.cart-item');

                items.forEach(item => {
                    const checkbox = item.querySelector('.item-check');
                    const harga = parseFloat(item.dataset.harga);
                    const qty = parseInt(item.querySelector('.qty-input').value);

                    if (checkbox.checked) {
                        totalBarang += qty;
                        totalHarga += (harga * qty);
                        adaYangDiceklis = true;
                    }
                });

                document.getElementById('summary-total-item').innerText = `${totalBarang} Barang`;
                document.getElementById('summary-total-harga').innerText = formatRupiah(totalHarga);
                document.getElementById('btn-checkout').disabled = !adaYangDiceklis;
                btnHapusTerpilih.style.display = adaYangDiceklis ? 'block' : 'none';

                // Jika keranjang kosong
                if (items.length === 0) {
                    keranjangContainer.innerHTML = `<div class="text-center py-10 text-gray-500">Keranjang belanja kamu masih kosong.</div>`;
                    checkAll.checked = false;
                    checkAll.disabled = true;
                }
            };

            // Inisialisasi event untuk satu item (Digunakan saat loop dan saat add listener)
            const attachItemEvents = (item) => {
                const check = item.querySelector('.item-check');
                const btnMinus = item.querySelector('.btn-minus');
                const btnPlus = item.querySelector('.btn-plus');
                const inputQty = item.querySelector('.qty-input');
                const btnHapus = item.querySelector('.btn-hapus-satuan');

                check.addEventListener('change', () => {
                    const allChecks = document.querySelectorAll('.item-check');
                    const allChecked = Array.from(allChecks).every(c => c.checked);
                    checkAll.checked = allChecked;
                    hitungTotal();
                });

                btnPlus.addEventListener('click', () => {
                    inputQty.value = parseInt(inputQty.value) + 1;
                    hitungTotal();
                });

                btnMinus.addEventListener('click', () => {
                    let currentVal = parseInt(inputQty.value);
                    if (currentVal > 1) {
                        inputQty.value = currentVal - 1;
                        hitungTotal();
                    } else if (currentVal === 1) {
                        // LOGIKA BARU: Konfirmasi hapus jika minus ditekan saat qty = 1
                        if(confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
                            item.remove(); // Hapus elemen dari tampilan HTML
                            hitungTotal(); // Hitung ulang total
                        }
                    }
                });

                inputQty.addEventListener('change', () => {
                    if (inputQty.value < 1 || isNaN(inputQty.value)) inputQty.value = 1;
                    hitungTotal();
                });

                // Hapus satuan lewat icon tong sampah
                btnHapus.addEventListener('click', () => {
                    if(confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
                        item.remove();
                        hitungTotal();
                    }
                });
            };

            // Pasang event ke semua item yang ada
            document.querySelectorAll('.cart-item').forEach(item => attachItemEvents(item));

            // Pilih Semua
            checkAll.addEventListener('change', function () {
                document.querySelectorAll('.item-check').forEach(check => {
                    check.checked = this.checked;
                });
                hitungTotal();
            });

            // Hapus Terpilih
            btnHapusTerpilih.addEventListener('click', () => {
                if(confirm('Yakin ingin menghapus semua produk yang terpilih?')) {
                    document.querySelectorAll('.cart-item').forEach(item => {
                        if (item.querySelector('.item-check').checked) {
                            item.remove();
                        }
                    });
                    checkAll.checked = false;
                    hitungTotal();
                }
            });

            hitungTotal();
        });
    </script>
</body>
</html>