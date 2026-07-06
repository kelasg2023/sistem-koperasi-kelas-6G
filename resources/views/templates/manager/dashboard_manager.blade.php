@extends('layouts.app')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-area, #printable-area * {
            visibility: visible;
        }
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        /* Penyesuaian border untuk tabel saat diprint */
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    }
</style>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="managerDashboard()">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Manager</h1>
            <p class="mt-2 text-sm text-gray-600">Pantau performa bisnis dan laporan analitik koperasi.</p>
        </div>
        
        <!-- Tabs Navigasi -->
        <div class="bg-gray-100 p-1 rounded-xl inline-flex space-x-1">
            <button @click="currentTab = 'ringkasan'" :class="{'bg-white shadow text-gray-900': currentTab === 'ringkasan', 'text-gray-600 hover:text-gray-900': currentTab !== 'ringkasan'}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all">
                Ringkasan
            </button>
            <button @click="currentTab = 'penjualan'" :class="{'bg-white shadow text-gray-900': currentTab === 'penjualan', 'text-gray-600 hover:text-gray-900': currentTab !== 'penjualan'}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all">
                Laporan Penjualan
            </button>
            <button @click="currentTab = 'pelanggan'" :class="{'bg-white shadow text-gray-900': currentTab === 'pelanggan', 'text-gray-600 hover:text-gray-900': currentTab !== 'pelanggan'}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all">
                Laporan Pelanggan
            </button>
        </div>
    </div>

    <div id="printable-area">
        <!-- HEADER PRINT (Hanya muncul saat dicetak) -->
        <div class="hidden print:block mb-6 text-center border-b-2 border-gray-800 pb-4">
            <h1 class="text-2xl font-bold uppercase tracking-widest">Sistem Koperasi Kelas 6G</h1>
            <p class="text-gray-600" x-text="'Laporan Resmi - Dicetak pada: ' + new Date().toLocaleString('id-ID')"></p>
            <h2 class="text-xl font-semibold mt-4" x-show="currentTab === 'penjualan'">Laporan Transaksi Penjualan</h2>
            <h2 class="text-xl font-semibold mt-4" x-show="currentTab === 'pelanggan'">Laporan Data Pelanggan Teratas</h2>
        </div>

        <!-- TAB: Ringkasan Metrik -->
        <div x-show="currentTab === 'ringkasan'" class="no-print">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-chart-line text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pendapatan (Bulan Ini)</p>
                        <p class="text-xl font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(metrics.monthly_revenue || 0)"></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-money-bill-trend-up text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pendapatan (Tahun Ini)</p>
                        <p class="text-xl font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(metrics.yearly_revenue || 0)"></p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-box text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pesanan (Bulan Ini)</p>
                        <p class="text-xl font-bold text-gray-900" x-text="metrics.orders_this_month || 0"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: Laporan Penjualan -->
        <div x-show="currentTab === 'penjualan'" style="display: none;">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center print:hidden">
                    <h3 class="font-bold text-gray-800">Riwayat Penjualan Terakhir</h3>
                    <div class="flex gap-4">
                        <button @click="window.print()" class="text-sm text-red-600 hover:text-red-800 flex items-center gap-1 font-medium bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                        </button>
                        <button @click="fetchSales()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i class="fa-solid fa-rotate-right" :class="{'animate-spin': isFetchingSales}"></i> Segarkan
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white text-gray-500 border-b border-gray-100 print:bg-gray-100 print:text-black">
                            <tr>
                                <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Pelanggan</th>
                                <th class="px-6 py-4 font-semibold">Detail Barang</th>
                                <th class="px-6 py-4 font-semibold text-right">Total Harga</th>
                                <th class="px-6 py-4 font-semibold">Status Pembelian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 print:divide-gray-300">
                            <template x-for="sale in salesReports" :key="sale.transaction_id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">#TRX-<span x-text="sale.transaction_id"></span></td>
                                    <td class="px-6 py-4 text-gray-600" x-text="new Date(sale.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit'})"></td>
                                    <td class="px-6 py-4 text-gray-600 font-medium" x-text="sale.user ? sale.user.username : 'Anonim'"></td>
                                    <td class="px-6 py-4">
                                        <ul class="list-disc list-inside text-gray-600 text-xs">
                                            <template x-for="detail in sale.details" :key="detail.detail_id">
                                                <li>
                                                    <span x-text="detail.barang ? detail.barang.nama : 'Barang Terhapus'"></span>
                                                    <span class="font-semibold" x-text="' (x' + detail.jumlah + ')'"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-green-600 text-right print:text-black" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(sale.total_harga)"></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs uppercase font-bold tracking-wide print:border print:border-gray-300 print:text-black print:bg-transparent"
                                              :class="{
                                                  'bg-green-100 text-green-800': sale.status === 'berhasil',
                                                  'bg-yellow-100 text-yellow-800': sale.status === 'proses',
                                                  'bg-red-100 text-red-800': sale.status === 'gagal' || sale.status === 'refund',
                                                  'bg-gray-100 text-gray-800': !sale.status
                                              }"
                                              x-text="sale.status || 'PROSES'"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="salesReports.length === 0 && !isFetchingSales">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data penjualan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB: Laporan Pelanggan -->
        <div x-show="currentTab === 'pelanggan'" style="display: none;">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center print:hidden">
                    <h3 class="font-bold text-gray-800">Daftar Pelanggan Teratas</h3>
                    <div class="flex gap-4">
                        <button @click="window.print()" class="text-sm text-red-600 hover:text-red-800 flex items-center gap-1 font-medium bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                        </button>
                        <button @click="fetchCustomers()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i class="fa-solid fa-rotate-right" :class="{'animate-spin': isFetchingCustomers}"></i> Segarkan
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white text-gray-500 border-b border-gray-100 print:bg-gray-100 print:text-black">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                                <th class="px-6 py-4 font-semibold">Email</th>
                                <th class="px-6 py-4 font-semibold text-center">Jumlah Pembelian</th>
                                <th class="px-6 py-4 font-semibold text-right">Total Pengeluaran</th>
                                <th class="px-6 py-4 font-semibold text-right">Poin Loyalty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 print:divide-gray-300">
                            <template x-for="cust in customerReports" :key="cust.customers_id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold print:hidden" x-text="(cust.user?.profile?.name || cust.user?.username || 'U')[0].toUpperCase()"></div>
                                            <span x-text="cust.user?.profile?.name || cust.user?.username || 'Anonim'"></span>
                                            <span x-show="cust.is_member" class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded font-bold print:border print:border-yellow-700">MEMBER</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600" x-text="cust.user?.email || '-'"></td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-800" x-text="cust.jumlah_pembelian || 0"></td>
                                    <td class="px-6 py-4 font-bold text-green-600 text-right print:text-black" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(cust.total_pengeluaran)"></td>
                                    <td class="px-6 py-4 text-orange-600 font-bold text-right print:text-black" x-text="cust.point + ' Pts'"></td>
                                </tr>
                            </template>
                            <tr x-show="customerReports.length === 0 && !isFetchingCustomers">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data pelanggan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('managerDashboard', () => ({
        currentTab: 'ringkasan',
        metrics: {
            monthly_revenue: 0,
            yearly_revenue: 0,
            orders_this_month: 0
        },
        salesReports: [],
        customerReports: [],
        isFetchingSales: false,
        isFetchingCustomers: false,
        
        init() {
            this.fetchMetrics();
            
            // Watch tab changes to load data lazy
            this.$watch('currentTab', value => {
                if (value === 'penjualan' && this.salesReports.length === 0) this.fetchSales();
                if (value === 'pelanggan' && this.customerReports.length === 0) this.fetchCustomers();
            });
        },
        async fetchMetrics() {
            try {
                const res = await fetch('/api-proxy/manager/dashboard', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.metrics = data.data;
                }
            } catch (e) {
                console.error("Gagal memuat metrik manager");
            }
        },
        async fetchSales() {
            this.isFetchingSales = true;
            try {
                const res = await fetch('/api-proxy/manager/reports/sales?per_page=15', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.salesReports = data.data.data; // .data (from ApiResponse), .data (from paginator)
                }
            } catch (e) {
                console.error("Gagal memuat laporan penjualan");
            } finally {
                this.isFetchingSales = false;
            }
        },
        async fetchCustomers() {
            this.isFetchingCustomers = true;
            try {
                const res = await fetch('/api-proxy/manager/reports/customers?per_page=15', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.customerReports = data.data.data;
                }
            } catch (e) {
                console.error("Gagal memuat laporan pelanggan");
            } finally {
                this.isFetchingCustomers = false;
            }
        }
    }));
});
</script>
@endsection
