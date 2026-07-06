@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto" x-data="riwayatPage()">
    
    {{-- Header Halaman --}}
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] hover:border-[#2D7A42]/30 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Riwayat Belanja</h1>
            <p class="text-gray-500 text-sm">Pantau status pesanan dan daftar transaksi Anda sebelumnya.</p>
        </div>
    </div>

    {{-- Pencarian & Filter Tab --}}
    <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 mb-6 sticky top-20 z-40">
        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Tab Status --}}
            <div class="flex overflow-x-auto scrollbar-hide gap-1 flex-1">
                <template x-for="tab in ['Semua', 'proses', 'berhasil', 'gagal', 'refund']">
                    <button @click="filter = tab" 
                            :class="filter === tab ? 'bg-[#2D7A42] text-white' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-[#2D7A42]'"
                            class="px-5 py-2.5 text-sm font-semibold rounded-xl whitespace-nowrap shadow-sm transition-colors" x-text="tab === 'berhasil' ? 'Selesai' : (tab === 'proses' ? 'Berlangsung' : (tab === 'gagal' ? 'Dibatalkan' : (tab === 'Semua' ? 'Semua Transaksi' : tab)))"></button>
                </template>
            </div>
            
            {{-- Search Bar --}}
            <div class="relative sm:w-64 shrink-0">
                <svg class="w-4 h-4 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari nama produk..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] bg-gray-50 focus:bg-white transition-all">
            </div>
        </div>
    </div>

    {{-- Loading --}}
    <div x-show="isLoading" class="flex justify-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2D7A42]"></div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="space-y-5" x-show="!isLoading" x-cloak>
        <template x-for="trx in filteredTransactions" :key="trx.transaction_id">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:border-[#2D7A42]/50 transition-colors shadow-sm">
                {{-- Header Card --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#2D7A42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="text-xs font-bold text-gray-600" x-text="new Date(trx.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})"></span>
                        <span class="text-gray-300 text-xs">•</span>
                        <span class="text-xs font-medium text-gray-500" x-text="'INV/6G/' + trx.transaction_id"></span>
                    </div>
                    
                    <span x-show="trx.status === 'berhasil'" class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                    <span x-show="trx.status === 'proses'" class="bg-orange-100 text-orange-600 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">Sedang Diproses</span>
                    <span x-show="trx.status === 'gagal' || trx.status === 'refund'" class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider" x-text="trx.status"></span>
                </div>
                
                {{-- Body Card --}}
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-3xl shrink-0">
                        🛒
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm sm:text-base font-bold text-gray-900 truncate" x-text="trx.transaction_details && trx.transaction_details.length > 0 ? trx.transaction_details[0].barang.nama : 'Produk'"></h4>
                        <p class="text-xs text-gray-500 mt-1" x-show="trx.transaction_details && trx.transaction_details.length > 0">
                            <span x-text="trx.transaction_details[0].jumlah + ' barang x Rp ' + new Intl.NumberFormat('id-ID').format(trx.transaction_details[0].harga_satuan)"></span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1" x-show="trx.transaction_details && trx.transaction_details.length > 1" x-text="'+ ' + (trx.transaction_details.length - 1) + ' produk lainnya'"></p>
                    </div>
                    <div class="text-right shrink-0 border-l border-gray-100 pl-4 hidden sm:block">
                        <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                        <p class="text-base font-extrabold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(trx.total_harga)"></p>
                    </div>
                </div>

                {{-- Footer Card --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                    <div class="sm:hidden w-full flex justify-between items-center">
                        <p class="text-xs text-gray-500">Total Belanja</p>
                        <p class="text-sm font-extrabold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(trx.total_harga)"></p>
                    </div>
                    <a :href="'/pesanan/' + trx.transaction_id" class="text-xs font-bold text-gray-500 hover:text-[#2D7A42] transition-colors">
                        Lihat Detail Transaksi
                    </a>
                    <button x-show="trx.status === 'berhasil'" class="w-full sm:w-auto px-6 py-2 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                        Beli Lagi
                    </button>
                </div>
            </div>
        </template>
        
        <div x-show="filteredTransactions.length === 0" class="text-center py-12 text-gray-500 bg-white rounded-2xl border border-gray-100">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p>Belum ada riwayat transaksi.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('riwayatPage', () => ({
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
        }
    }));
});
</script>
@endsection