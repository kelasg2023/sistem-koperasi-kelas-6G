@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="staffDashboard()">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Staff</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola pesanan dan pantau performa harian koperasi.</p>
        </div>
    </div>

    <!-- Metrik Harian -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-chart-line text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Penjualan Hari Ini</p>
                <p class="text-xl font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(metrics.sales_today)"></p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shopping-bag text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pesanan Hari Ini</p>
                <p class="text-xl font-bold text-gray-900" x-text="metrics.orders_today"></p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-clock text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Menunggu Diproses</p>
                <p class="text-xl font-bold text-gray-900" x-text="metrics.pending_orders"></p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                <p class="text-xl font-bold text-gray-900" x-text="metrics.low_stock_items"></p>
            </div>
        </div>
    </div>

    <!-- Tabel Pesanan (Semua Transaksi) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-[#2D7A42]"></i> Kelola Pesanan
            </h2>
            <div class="flex items-center gap-2">
                <button @click="fetchTransactions" class="p-2 text-gray-400 hover:text-[#2D7A42] transition rounded-lg hover:bg-white border border-transparent hover:border-gray-200">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold border-b border-gray-100 rounded-tl-2xl">ID Pesanan</th>
                        <th class="p-4 font-semibold border-b border-gray-100">Pembeli</th>
                        <th class="p-4 font-semibold border-b border-gray-100">Total Harga</th>
                        <th class="p-4 font-semibold border-b border-gray-100">Status</th>
                        <th class="p-4 font-semibold border-b border-gray-100 text-right rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50 relative">
                    <tr x-show="isLoading" class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10">
                        <td colspan="5" class="py-12 text-center text-[#2D7A42] font-medium flex items-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin"></i> Memuat data...
                        </td>
                    </tr>
                    <tr x-show="!isLoading && transactions.length === 0">
                        <td colspan="5" class="p-8 text-center text-gray-500">Tidak ada pesanan saat ini.</td>
                    </tr>
                    <template x-for="trx in transactions" :key="trx.transaction_id">
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="p-4 font-medium text-gray-900" x-text="'INV/6G/' + trx.transaction_id"></td>
                            <td class="p-4 text-gray-600" x-text="trx.user ? trx.user.username : 'User'"></td>
                            <td class="p-4 font-medium text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(trx.total_harga)"></td>
                            <td class="p-4">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-md capitalize"
                                          :class="{
                                              'bg-yellow-100 text-yellow-700': trx.payment_status === 'pending',
                                              'bg-green-100 text-green-700': trx.payment_status === 'success',
                                              'bg-red-100 text-red-700': trx.payment_status === 'failed' || trx.payment_status === 'expire' || trx.payment_status === 'deny' || trx.payment_status === 'cancel'
                                          }" x-text="trx.payment_status"></span>
                                </div>
                                <div class="mt-2 flex items-center gap-1.5">
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-md capitalize flex items-center gap-1"
                                          :class="{
                                              'bg-orange-100 text-orange-700': trx.status_pengiriman === 'diproses',
                                              'bg-blue-100 text-blue-700': trx.status_pengiriman === 'dikemas',
                                              'bg-indigo-100 text-indigo-700': trx.status_pengiriman === 'dikirim',
                                              'bg-green-100 text-green-700': trx.status_pengiriman === 'selesai'
                                          }">
                                        <i class="fa-solid" :class="{
                                            'fa-clock': trx.status_pengiriman === 'diproses',
                                            'fa-box': trx.status_pengiriman === 'dikemas',
                                            'fa-truck': trx.status_pengiriman === 'dikirim',
                                            'fa-check': trx.status_pengiriman === 'selesai'
                                        }"></i> <span x-text="trx.status_pengiriman"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 align-top w-48">
                                <div x-show="trx.payment_status === 'success' && trx.status_pengiriman !== 'selesai'" class="flex justify-end gap-2">
                                    <button x-show="trx.status_pengiriman === 'diproses'" 
                                            @click="updateStatus(trx.transaction_id, 'dikemas', 'Pesanan sedang disiapkan dan dikemas.')" 
                                            class="px-3 py-1.5 text-[11px] font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition shadow-sm">
                                        <i class="fa-solid fa-box mr-1"></i> Kemas
                                    </button>
                                    <button x-show="trx.status_pengiriman === 'dikemas'" 
                                            @click="updateStatus(trx.transaction_id, 'dikirim', 'Pesanan sedang dalam perjalanan ke pembeli (Resi: ' + trx.nomor_resi + ').')" 
                                            class="px-3 py-1.5 text-[11px] font-bold text-white bg-indigo-500 rounded-lg hover:bg-indigo-600 transition shadow-sm">
                                        <i class="fa-solid fa-truck mr-1"></i> Kirim
                                    </button>
                                    <button x-show="trx.status_pengiriman === 'dikirim'" 
                                            @click="updateStatus(trx.transaction_id, 'selesai', 'Pesanan telah sampai dan diselesaikan.')" 
                                            class="px-3 py-1.5 text-[11px] font-bold text-white bg-green-500 rounded-lg hover:bg-green-600 transition shadow-sm">
                                        <i class="fa-solid fa-check-double mr-1"></i> Selesai
                                    </button>
                                </div>
                                <div x-show="trx.payment_status === 'pending'" class="text-gray-400 text-xs flex justify-end font-medium italic">
                                    Menunggu Pembayaran
                                </div>
                                <span x-show="trx.status_pengiriman === 'selesai'" class="text-green-600 font-bold text-xs flex justify-end"><i class="fa-solid fa-check-double mr-1 mt-0.5"></i> Transaksi Selesai</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('staffDashboard', () => ({
        metrics: { sales_today: 0, orders_today: 0, pending_orders: 0, low_stock_items: 0 },
        transactions: [],
        isLoading: true,

        init() {
            this.fetchMetrics();
            this.fetchTransactions();

            // Real-time listener for Laravel Reverb
            if (window.Echo) {
                window.Echo.channel('tugas-channel').listen('DataUpdated', (e) => {
                    console.log('Realtime event received in Staff Dashboard:', e);
                    // Update data automatically without showing loading spinner
                    this.fetchMetrics();
                    this.fetchTransactions(true); 
                });
            }
        },

        async fetchMetrics() {
            try {
                const res = await fetch('/api-proxy/staff/dashboard', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.metrics = data.data;
                }
            } catch (e) {
                console.error("Gagal memuat metrik staff");
            }
        },

        async fetchTransactions(silent = false) {
            if (!silent) this.isLoading = true;
            try {
                const res = await fetch('/api-proxy/transactions/all', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.transactions = data.data;
                }
            } catch (e) {
                console.error("Gagal memuat transaksi");
            } finally {
                if (!silent) this.isLoading = false;
            }
        },

        async updateStatus(id, newStatus, defaultKeterangan) {
            Swal.fire({
                title: 'Konfirmasi Status',
                text: `Ubah status menjadi ${newStatus.toUpperCase()}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2D7A42',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                    try {
                        const res = await fetch(`/api-proxy/transaction/${id}/status`, {
                            method: 'PUT',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                status_pengiriman: newStatus,
                                keterangan: defaultKeterangan
                            })
                        });
                        const data = await res.json();
                        if (res.ok && data.success) {
                            Swal.fire('Berhasil!', 'Status pesanan berhasil diperbarui', 'success');
                            this.fetchTransactions();
                            this.fetchMetrics();
                        } else {
                            Swal.fire('Gagal', data.message || 'Tidak dapat memperbarui pesanan', 'error');
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
