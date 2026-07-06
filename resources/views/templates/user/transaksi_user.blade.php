@extends('layouts.app') {{-- Sesuaikan dengan nama layout utamamu yang memuat Sidebar & Navbar --}}

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl" x-data="transaksiPage()">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h1>
        
        {{-- Form Pencarian Transaksi --}}
        <div class="relative w-64 hidden sm:block">
            <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" x-model="searchQuery" placeholder="Cari invoice atau produk..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42]">
        </div>
    </div>

    {{-- Tabs Filter Status --}}
    <div class="flex overflow-x-auto scrollbar-hide gap-2 mb-6 pb-2">
        <template x-for="tab in ['Semua', 'proses', 'berhasil', 'gagal', 'refund']">
            <button @click="filter = tab" 
                    :class="filter === tab ? 'bg-[#2D7A42] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                    class="px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors" x-text="tab === 'berhasil' ? 'Selesai' : (tab === 'proses' ? 'Diproses' : (tab === 'gagal' ? 'Dibatalkan' : tab))"></button>
        </template>
    </div>

    {{-- Loading state --}}
    <div x-show="isLoading" class="flex justify-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2D7A42]"></div>
    </div>

    {{-- List Transaksi --}}
    <div class="space-y-4" x-show="!isLoading" x-cloak>
        <template x-for="trx in filteredTransactions" :key="trx.transaction_id">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#2D7A42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="text-sm font-bold text-gray-800" x-text="'Belanja • ' + new Date(trx.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></span>
                        
                        <span x-show="trx.status === 'berhasil'" class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-green-100 text-green-700">Selesai</span>
                        <span x-show="trx.status === 'proses'" class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-yellow-100 text-yellow-700">Diproses</span>
                        <span x-show="trx.status === 'gagal' || trx.status === 'refund'" class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-100 text-red-700" x-text="trx.status"></span>
                    </div>
                    <span class="text-xs text-gray-500 font-medium hidden sm:block" x-text="'INV/6G/' + trx.transaction_id"></span>
                </div>

                <div class="flex flex-col gap-4">
                    <template x-for="item in trx.transaction_details" :key="item.detail_id">
                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            <div class="w-16 h-16 rounded-xl border border-gray-100 flex items-center justify-center bg-gray-50 text-2xl shrink-0">
                                <i class="fa-solid fa-box text-gray-400 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-800" x-text="item.barang ? item.barang.nama : 'Produk Koperasi'"></h3>
                                <p class="text-sm text-gray-500">
                                    <span x-text="item.jumlah + ' x Rp ' + new Intl.NumberFormat('id-ID').format(item.harga_satuan)"></span>
                                </p>
                            </div>
                            <div class="sm:text-right sm:border-l border-t sm:border-t-0 border-gray-100 sm:pl-4 pt-3 sm:pt-0 w-full sm:w-auto flex justify-between sm:block mt-2 sm:mt-0 shrink-0">
                                <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                                <p class="text-sm font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.jumlah * item.harga_satuan)"></p>
                            </div>
                        </div>
                    </template>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-700">Total Belanja:</span>
                    <span class="text-lg font-extrabold text-[#2D7A42]" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(trx.total_harga)"></span>
                </div>

                <div class="flex justify-end items-center gap-3 mt-4 pt-4 border-t border-gray-50">
                    <button x-show="trx.status === 'proses' || trx.status === 'pending'" @click="cancelTransaction(trx.transaction_id)" class="px-4 py-2 text-sm font-bold text-red-600 bg-white border border-red-600 rounded-xl hover:bg-red-50 transition">Batalkan Pesanan</button>
                    <a :href="'/pesanan/' + trx.transaction_id" class="px-4 py-2 text-sm font-bold text-[#2D7A42] bg-white border border-[#2D7A42] rounded-xl hover:bg-[#E8F5EC] transition">Lihat Detail</a>
                    <button x-show="trx.status === 'berhasil'" class="px-4 py-2 text-sm font-bold text-white bg-[#2D7A42] rounded-xl hover:bg-[#1E5C2F] transition">Beli Lagi</button>
                </div>
            </div>
        </template>
        
        <div x-show="filteredTransactions.length === 0" class="text-center py-12 text-gray-500 bg-white rounded-2xl border border-gray-100">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p>Tidak ada transaksi yang ditemukan.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('transaksiPage', () => ({
        transactions: [],
        isLoading: true,
        filter: 'Semua',
        searchQuery: '',
        
        async init() {
            this.fetchTransactions();
        },
        
        async fetchTransactions() {
            this.isLoading = true;
            try {
                const res = await fetch('/api-proxy/transaction/history');
                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    this.transactions = json.data;
                }
            } catch (e) {
                console.error("Gagal memuat transaksi", e);
            } finally {
                this.isLoading = false;
            }
        },
        
        get filteredTransactions() {
            let filtered = this.transactions;
            if (this.filter !== 'Semua') {
                filtered = filtered.filter(t => t.status === this.filter);
            }
            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                filtered = filtered.filter(t => {
                    const idMatch = t.transaction_id.toString().includes(q);
                    const prodMatch = t.transaction_details && t.transaction_details.some(d => d.barang && d.barang.nama.toLowerCase().includes(q));
                    return idMatch || prodMatch;
                });
            }
            return filtered;
        },

        async cancelTransaction(id) {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: "Saldo akan dikembalikan ke dompet Anda, namun pesanan tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Membatalkan pesanan Anda.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    try {
                        const res = await fetch(`/api-proxy/transaction/${id}/cancel`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        });
                        const data = await res.json();
                        
                        if (res.ok && data.success) {
                            Swal.fire('Dibatalkan!', data.message, 'success');
                            this.fetchTransactions();
                        } else {
                            Swal.fire('Gagal', data.message || 'Tidak dapat membatalkan pesanan', 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Kesalahan koneksi ke server', 'error');
                    }
                }
            });
        }
    }));
});
</script>
@endsection