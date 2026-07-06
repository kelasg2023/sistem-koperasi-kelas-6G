@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto" x-data="totalBelanjaPage()">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-[#2D7A42] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Rincian Total Belanja</h1>
            <p class="text-gray-500 text-sm">Pantau aktivitas pengeluaran Anda di Koperasi 6G.</p>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div x-show="isLoading" class="flex justify-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2D7A42]"></div>
    </div>

    <div x-show="!isLoading" x-cloak>
        {{-- Kartu Laporan Utama --}}
        <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 lg:p-8 text-white shadow-md mb-6 relative overflow-hidden">
            <i class="fa-solid fa-chart-line absolute -right-4 -bottom-4 text-8xl opacity-10"></i>
            
            <p class="text-sm font-medium text-green-100 mb-2">Total Belanja (Semua Waktu)</p>
            <h2 class="text-4xl lg:text-5xl font-extrabold mb-6" x-text="formatRupiah(totalSemua)"></h2>
            
            <div class="grid grid-cols-2 gap-4 lg:gap-8 border-t border-white/20 pt-6">
                <div>
                    <p class="text-xs text-green-200 mb-1">Bulan Ini</p>
                    <p class="text-lg font-bold" x-text="formatRupiah(totalBulanIni)"></p>
                </div>
                <div>
                    <p class="text-xs text-green-200 mb-1">Bulan Lalu</p>
                    <p class="text-lg font-bold" x-text="formatRupiah(totalBulanLalu)"></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kiri: Kategori Belanja --}}
            <div class="lg:col-span-1 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6 h-fit">
                <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">Pengeluaran per Kategori</h3>
                
                <div class="space-y-4">
                    <template x-for="kat in topKategori" :key="kat.name">
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="font-semibold text-gray-700" x-text="kat.name"></span>
                                <span class="font-bold text-gray-900" x-text="Math.round(kat.percentage) + '%'"></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#2D7A42] h-2 rounded-full" :style="`width: ${kat.percentage}%`"></div>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1" x-text="formatRupiah(kat.total)"></p>
                        </div>
                    </template>
                    <div x-show="topKategori.length === 0" class="text-gray-500 text-sm text-center py-4">Belum ada data kategori belanja.</div>
                </div>
            </div>

            {{-- Kanan: Riwayat Transaksi Terbaru --}}
            <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6">
                <div class="flex justify-between items-center mb-5 border-b border-gray-50 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Aktivitas Belanja Terbaru</h3>
                    <a href="{{ route('transaksi.index') }}" class="text-xs font-bold text-[#2D7A42] hover:underline">Lihat Semua</a>
                </div>
                
                <div class="space-y-0">
                    <template x-for="trx in recentTransactions" :key="trx.transaction_id">
                        <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-800" x-text="trx.status === 'berhasil' ? 'Belanja Selesai' : 'Belanja - ' + trx.status"></h4>
                                <p class="text-[11px] text-gray-500 mt-0.5" x-text="formatDate(trx.created_at) + ' • ' + trx.details_count + ' Item'"></p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-extrabold text-gray-900" x-text="formatRupiah(trx.total_harga)"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="recentTransactions.length === 0" class="text-center text-gray-500 py-8">
                        Belum ada transaksi bulan ini.
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('totalBelanjaPage', () => ({
        transactions: [],
        isLoading: true,
        totalSemua: 0,
        totalBulanIni: 0,
        totalBulanLalu: 0,
        topKategori: [],
        recentTransactions: [],

        async init() {
            this.fetchTransactions();
        },

        async fetchTransactions() {
            try {
                const res = await fetch('/api-proxy/transaction/history');
                const json = await res.json();
                
                if (json.success && json.data) {
                    this.transactions = json.data;
                    this.calculateTotals();
                }
            } catch (e) {
                console.error("Gagal mengambil riwayat transaksi", e);
            } finally {
                this.isLoading = false;
            }
        },

        calculateTotals() {
            let total = 0;
            let ini = 0;
            let lalu = 0;
            const now = new Date();
            const currentMonth = now.getMonth();
            const currentYear = now.getFullYear();
            const lastMonthDate = new Date(now.setMonth(now.getMonth() - 1));
            const lastMonth = lastMonthDate.getMonth();
            const lastMonthYear = lastMonthDate.getFullYear();

            const kategoriMap = {};
            this.recentTransactions = [];

            this.transactions.forEach(trx => {
                // Hanya hitung yang berhasil / sukses
                if (trx.status === 'berhasil' || trx.status === 'selesai' || trx.status === 'proses') {
                    const harga = parseFloat(trx.total_harga);
                    total += harga;

                    const trxDate = new Date(trx.created_at);
                    if (trxDate.getMonth() === currentMonth && trxDate.getFullYear() === currentYear) {
                        ini += harga;
                    } else if (trxDate.getMonth() === lastMonth && trxDate.getFullYear() === lastMonthYear) {
                        lalu += harga;
                    }

                    // Estimasi Kategori dari item pertama (Kategori aslinya mungkin ada di API, tapi untuk demo kita ambil nama)
                    // Jika API mengembalikan items/details
                    if (trx.details && trx.details.length > 0) {
                        trx.details.forEach(detail => {
                            const katName = detail.barang?.kategori?.nama_kategori || 'Lainnya';
                            if(!kategoriMap[katName]) kategoriMap[katName] = 0;
                            kategoriMap[katName] += parseFloat(detail.harga_satuan) * detail.jumlah;
                        });
                    } else {
                        if(!kategoriMap['Umum']) kategoriMap['Umum'] = 0;
                        kategoriMap['Umum'] += harga;
                    }
                }
            });

            this.totalSemua = total;
            this.totalBulanIni = ini;
            this.totalBulanLalu = lalu;

            // Hitung persentase kategori
            const katArray = Object.keys(kategoriMap).map(k => {
                return {
                    name: k,
                    total: kategoriMap[k],
                    percentage: total > 0 ? (kategoriMap[k] / total) * 100 : 0
                }
            });
            katArray.sort((a,b) => b.total - a.total);
            this.topKategori = katArray.slice(0, 5); // Ambil 5 teratas

            // Ambil 5 transaksi terbaru
            this.recentTransactions = [...this.transactions]
                .sort((a,b) => new Date(b.created_at) - new Date(a.created_at))
                .slice(0, 5);
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka || 0);
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric'
            });
        }
    }));
});
</script>
@endsection