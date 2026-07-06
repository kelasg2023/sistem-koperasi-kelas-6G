@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto" x-data="checkoutPage()">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Checkout</h1>
            <p class="text-gray-500 text-sm">Selesaikan pesanan Anda menggunakan Saldo Koperasi Pay.</p>
        </div>
        <a href="{{ route('keranjang.index') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#2D7A42] transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        {{-- KIRI: FORM PENGIRIMAN --}}
        <div class="w-full lg:w-2/3 space-y-6">
            
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">
                    <i class="fa-solid fa-location-dot text-[#2D7A42] mr-2"></i> Alamat Pengiriman
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea x-model="form.alamat_pengiriman" rows="3" placeholder="Contoh: Jl. Merdeka No. 10, RT 01/RW 02, Kec. Sukamaju, Kota Jakarta" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-[#2D7A42] focus:border-[#2D7A42] transition-colors outline-none resize-none bg-gray-50 hover:bg-white"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">
                    <i class="fa-solid fa-truck text-[#2D7A42] mr-2"></i> Jasa Pengiriman
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#2D7A42] transition-all" :class="{'border-[#2D7A42] bg-[#E8F5EC]/30': form.jasa_kurir === 'JNE'}">
                        <input type="radio" name="kurir" value="JNE" x-model="form.jasa_kurir" class="mt-1 text-[#2D7A42] focus:ring-[#2D7A42]">
                        <div>
                            <span class="block font-bold text-gray-800 text-sm">JNE Reguler</span>
                            <span class="text-xs text-gray-500">Estimasi 2-3 hari kerja</span>
                        </div>
                    </label>
                    
                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#2D7A42] transition-all" :class="{'border-[#2D7A42] bg-[#E8F5EC]/30': form.jasa_kurir === 'JNT'}">
                        <input type="radio" name="kurir" value="JNT" x-model="form.jasa_kurir" class="mt-1 text-[#2D7A42] focus:ring-[#2D7A42]">
                        <div>
                            <span class="block font-bold text-gray-800 text-sm">J&T Express</span>
                            <span class="text-xs text-gray-500">Estimasi 1-2 hari kerja</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#2D7A42] transition-all" :class="{'border-[#2D7A42] bg-[#E8F5EC]/30': form.jasa_kurir === 'Kurir Koperasi'}">
                        <input type="radio" name="kurir" value="Kurir Koperasi" x-model="form.jasa_kurir" class="mt-1 text-[#2D7A42] focus:ring-[#2D7A42]">
                        <div>
                            <span class="block font-bold text-gray-800 text-sm">Kurir Koperasi 6G</span>
                            <span class="text-xs text-gray-500">Dikirim hari ini (Khusus dalam kota)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
                <i class="fa-solid fa-wallet absolute -right-6 -top-6 text-8xl opacity-10"></i>
                <div class="relative z-10 w-full md:w-auto text-center md:text-left">
                    <p class="text-sm font-medium text-green-100 mb-1">Metode Pembayaran</p>
                    <h2 class="text-xl font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-400"></i> Koperasi Pay (Wallet)
                    </h2>
                </div>
                <div class="relative z-10 text-right">
                    <p class="text-xs text-green-100 mb-0.5">Saldo Anda Saat Ini</p>
                    <div x-show="isLoadingWallet" class="animate-pulse h-6 bg-white/20 rounded w-24"></div>
                    <p x-show="!isLoadingWallet" class="text-xl font-bold" x-text="formatRupiah(walletBalance)" x-cloak></p>
                </div>
            </div>
        </div>

        {{-- KANAN: RINGKASAN PESANAN --}}
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-900">Ringkasan Pesanan</h2>
                </div>
                
                <div class="p-5 max-h-[300px] overflow-y-auto space-y-4">
                    <template x-for="item in items" :key="item.id">
                        <div class="flex items-start gap-3">
                            <img :src="item.image || 'https://placehold.co/100x100/e2e8f0/475569?text=Produk'" class="w-12 h-12 rounded-lg object-cover border border-gray-100 shrink-0">
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-800 line-clamp-1" x-text="item.name"></h4>
                                <p class="text-xs text-gray-500" x-text="item.qty + ' x ' + formatRupiah(item.price)"></p>
                            </div>
                            <span class="text-sm font-extrabold text-[#2D7A42] shrink-0" x-text="formatRupiah(item.qty * item.price)"></span>
                        </div>
                    </template>
                </div>

                <div class="p-5 border-t border-gray-100 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Total Harga</span>
                        <span class="text-sm font-semibold text-gray-800" x-text="formatRupiah(totalPrice)"></span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-600">Ongkos Kirim</span>
                        <span class="text-sm font-semibold text-gray-800">Gratis</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-3 mb-6">
                        <span class="text-base font-bold text-gray-800">Total Tagihan</span>
                        <span class="text-xl font-extrabold text-[#2D7A42]" x-text="formatRupiah(totalPrice)"></span>
                    </div>

                    <button type="button" @click="submitCheckout" 
                            class="w-full bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold py-3.5 px-4 rounded-xl transition-colors disabled:opacity-50 flex items-center justify-center gap-2"
                            :disabled="isLoading || !isFormValid">
                        <i x-show="isLoading" class="fa-solid fa-spinner fa-spin"></i>
                        <span x-text="isLoading ? 'Memproses...' : 'Bayar Sekarang'"></span>
                    </button>
                    <p x-show="walletBalance < totalPrice && !isLoadingWallet" class="text-xs text-red-500 font-semibold text-center mt-3" x-cloak>
                        Saldo Koperasi Pay tidak mencukupi.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkoutPage', () => ({
        items: [],
        walletBalance: 0,
        isLoadingWallet: true,
        isLoading: false,
        form: {
            alamat_pengiriman: '',
            jasa_kurir: '',
            payment_method: 'wallet'
        },

        init() {
            // Load items dari localStorage checkout_items
            const stored = localStorage.getItem('checkout_items');
            if (stored) {
                try {
                    this.items = JSON.parse(stored);
                } catch (e) {
                    this.items = [];
                }
            }
            if (this.items.length === 0) {
                Swal.fire('Oops!', 'Tidak ada barang untuk di-checkout.', 'warning').then(() => {
                    window.location.href = "{{ route('keranjang.index') }}";
                });
                return;
            }

            this.loadWallet();
        },

        async loadWallet() {
            try {
                const res = await fetch('/api-proxy/dashboard');
                const json = await res.json();
                if (json.success) {
                    this.walletBalance = json.data.dashboard_metrics?.wallet_balance || 0;
                }
            } catch (e) {
                console.error('Failed to load wallet', e);
            } finally {
                this.isLoadingWallet = false;
            }
        },

        get totalPrice() {
            return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        get isFormValid() {
            return this.form.alamat_pengiriman.trim() !== '' && 
                   this.form.jasa_kurir !== '' &&
                   this.walletBalance >= this.totalPrice;
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka || 0);
        },

        async submitCheckout() {
            if (!this.isFormValid) return;
            
            this.isLoading = true;
            
            // Format item sesuai payload backend
            const payloadItems = this.items.map(item => ({
                barang_id: item.id,
                jumlah: item.qty
            }));

            const payload = {
                alamat_pengiriman: this.form.alamat_pengiriman,
                jasa_kurir: this.form.jasa_kurir,
                payment_method: this.form.payment_method,
                items: payloadItems
            };

            const idempotencyKey = crypto.randomUUID();

            try {
                const res = await fetch('/api-proxy/transaction/checkout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Idempotency-Key': idempotencyKey
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat checkout.', 'error');
                    return;
                }

                // Hapus barang yang berhasil di-checkout dari global cart
                const cartIds = this.items.map(i => i.id);
                if (window.Alpine && window.Alpine.store('cart')) {
                    let currentCart = window.Alpine.store('cart').items;
                    currentCart = currentCart.filter(cartItem => !cartIds.includes(cartItem.id));
                    window.Alpine.store('cart').items = currentCart;
                    window.Alpine.store('cart').save();
                }

                // Hapus checkout items
                localStorage.removeItem('checkout_items');

                Swal.fire('Berhasil!', 'Pesanan Anda telah dibuat.', 'success').then(() => {
                    window.location.href = "{{ route('transaksi.index') }}"; // Arahkan ke history transaksi
                });

            } catch (e) {
                Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
</script>
@endpush
@endsection
