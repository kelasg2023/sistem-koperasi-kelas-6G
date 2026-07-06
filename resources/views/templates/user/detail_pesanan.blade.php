@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-4xl mx-auto" x-data="detailPesanan('{{ $transactionId }}')">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('transaksi.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] hover:border-[#2D7A42]/30 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Detail Pesanan</h1>
                <p class="text-xs sm:text-sm text-gray-500">
                    No. Transaksi: <span class="font-bold text-gray-800" x-text="'TRX-' + (transaction ? transaction.transaction_id : '...')"></span>
                </p>
            </div>
        </div>
        <button class="hidden sm:flex px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-lg hover:bg-gray-50 shadow-sm gap-2 items-center">
            <i class="fa-solid fa-download"></i> Unduh Invoice
        </button>
    </div>

    {{-- Loading State --}}
    <div x-show="isLoading" class="flex justify-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2D7A42]"></div>
    </div>

    <div x-show="!isLoading && transaction" x-cloak>
        {{-- Alert Status --}}
        <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-5 mb-6 text-white shadow-md flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center shrink-0 backdrop-blur-sm">
                <i class="fa-solid fa-truck-fast text-2xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold mb-1 uppercase" x-text="'Status: ' + transaction.status"></h2>
                <p class="text-xs sm:text-sm text-green-100">
                    <span x-show="transaction.status === 'proses'">Pesanan Anda sedang diproses dan akan segera dikirim.</span>
                    <span x-show="transaction.status === 'berhasil' || transaction.status === 'selesai'">Pesanan telah selesai. Terima kasih telah berbelanja!</span>
                    <span x-show="transaction.status !== 'proses' && transaction.status !== 'berhasil' && transaction.status !== 'selesai'">Pesanan saat ini dalam status <span x-text="transaction.status"></span>.</span>
                </p>
            </div>
        </div>

        {{-- Lacak Pengiriman (Timeline) --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6 mb-6">
            <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">Status Pengiriman</h3>
            
            <div class="relative border-l-2 border-gray-100 ml-3 md:ml-4 space-y-6">
                <template x-for="(track, index) in tracking" :key="index">
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white"
                            :class="index === 0 ? 'bg-[#2D7A42] ring-4 ring-[#E8F5EC]' : 'bg-gray-300'"></div>
                        <h4 class="text-sm font-bold capitalize" :class="index === 0 ? 'text-[#2D7A42]' : 'text-gray-700'" x-text="track.status_pengiriman"></h4>
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-1" x-text="track.keterangan || 'Update status pengiriman.'"></p>
                        <span class="text-[10px] font-semibold text-gray-400 mt-1.5 block" x-text="formatDate(track.created_at)"></span>
                    </div>
                </template>
                <div x-show="tracking.length === 0" class="pl-6 text-sm text-gray-500">Belum ada riwayat pengiriman.</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Kiri: Detail Produk & Pengiriman --}}
            <div class="md:col-span-2 space-y-6">
                
                {{-- Alamat Pengiriman --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Alamat Pengiriman</h3>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800" x-text="userProfile?.name || 'Customer'"></h4>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed" x-text="transaction.alamat_pengiriman || 'Alamat tidak tersedia.'"></p>
                        </div>
                    </div>
                </div>

                {{-- Daftar Produk --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Daftar Produk</h3>
                    
                    <div class="space-y-4">
                        <template x-for="item in transaction.details" :key="item.detail_id">
                            <div class="flex gap-4">
                                <div class="w-16 h-16 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-2xl shrink-0">
                                    <i class="fa-solid fa-box text-gray-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-800" x-text="item.barang ? item.barang.nama : 'Produk tidak diketahui'"></h4>
                                    <p class="text-xs text-gray-500 mt-1" x-text="item.jumlah + ' x ' + formatRupiah(item.harga_satuan)"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900" x-text="formatRupiah(item.jumlah * item.harga_satuan)"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="!transaction.details || transaction.details.length === 0" class="text-sm text-gray-500">
                            Tidak ada detail produk untuk pesanan ini.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kanan: Rincian Pembayaran --}}
            <div class="md:col-span-1">
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6 sticky top-24">
                    <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Rincian Pembayaran</h3>
                    
                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between items-center text-xs sm:text-sm">
                            <span class="text-gray-500">Metode Pembayaran</span>
                            <span class="font-bold text-gray-800 uppercase" x-text="transaction.payment_method"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs sm:text-sm">
                            <span class="text-gray-500">Subtotal Produk</span>
                            <span class="font-medium text-gray-800" x-text="formatRupiah(calculateSubtotal())"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs sm:text-sm">
                            <span class="text-gray-500">Diskon (Voucher)</span>
                            <span class="font-bold text-[#F5820A]" x-text="'- ' + formatRupiah(calculateDiscount())"></span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-dashed border-gray-200 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900 text-sm">Total Belanja</span>
                            <span class="font-extrabold text-xl text-[#2D7A42]" x-text="formatRupiah(transaction.total_harga)"></span>
                        </div>
                    </div>

                    <button class="w-full px-6 py-3 bg-white border border-[#2D7A42] text-[#2D7A42] hover:bg-[#E8F5EC] font-bold text-sm rounded-xl transition-colors shadow-sm mb-3">
                        Hubungi Bantuan
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="block text-center w-full px-6 py-3 bg-gray-50 text-gray-600 hover:bg-gray-100 font-bold text-sm rounded-xl transition-colors">
                        Kembali
                    </a>
                </div>
            </div>

        </div>
    </div>
    
    <div x-show="!isLoading && !transaction" class="text-center py-10" x-cloak>
        <p class="text-gray-500">Transaksi tidak ditemukan.</p>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('detailPesanan', (id) => ({
        trxId: id,
        transaction: null,
        tracking: [],
        isLoading: true,
        userProfile: null,

        async init() {
            await Promise.all([
                this.fetchTransactionHistory(),
                this.fetchProfile()
            ]);
            if (this.transaction) {
                await this.fetchTracking();
            }
            this.isLoading = false;
        },

        async fetchTransactionHistory() {
            try {
                const res = await fetch('/api-proxy/transaction/history');
                const json = await res.json();
                if (json.success && json.data) {
                    this.transaction = json.data.find(t => t.transaction_id == this.trxId) || null;
                }
            } catch (e) {
                console.error("Gagal mengambil history transaksi", e);
            }
        },

        async fetchTracking() {
            try {
                const res = await fetch(`/api-proxy/transaction/${this.trxId}/track`);
                const json = await res.json();
                if (json.success && json.data) {
                    // Sorting desc biar yang terbaru di atas
                    this.tracking = json.data.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));
                }
            } catch (e) {
                console.error("Gagal mengambil tracking", e);
            }
        },

        async fetchProfile() {
            try {
                const res = await fetch('/api-proxy/profile');
                const json = await res.json();
                if (json.success && json.data) {
                    this.userProfile = json.data;
                }
            } catch (e) {
                console.error("Gagal mengambil profile", e);
            }
        },

        calculateSubtotal() {
            if (!this.transaction || !this.transaction.details) return 0;
            return this.transaction.details.reduce((sum, item) => sum + (parseFloat(item.harga_satuan) * item.jumlah), 0);
        },

        calculateDiscount() {
            const sub = this.calculateSubtotal();
            const total = parseFloat(this.transaction?.total_harga || 0);
            const diff = sub - total;
            return diff > 0 ? diff : 0;
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka || 0);
        },

        formatDate(dateString) {
            const opts = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('id-ID', opts) + ' WIB';
        }
    }));
});
</script>
@endsection