@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto" x-data="voucherPage()">
    
    {{-- Header Halaman dengan Tombol Kembali --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-[#F5820A] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Voucher Saya</h1>
            <p class="text-gray-500 text-sm">Klaim dan gunakan voucher untuk belanja lebih hemat di Koperasi 6G.</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div x-show="message.text" :class="message.type === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'" class="mb-6 border px-4 py-3 rounded-xl flex items-center gap-3" x-cloak>
        <i class="fa-solid fa-circle-info text-lg"></i>
        <span class="text-sm font-medium" x-text="message.text"></span>
    </div>

    {{-- Form Input Kode Voucher --}}
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-sm mb-6 lg:mb-8 flex flex-col sm:flex-row gap-3">
        <input type="text" x-model="claimCode" placeholder="Masukkan kode voucher di sini" class="w-full sm:flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#F5820A] focus:ring-1 focus:ring-[#F5820A] uppercase placeholder:normal-case">
        <button @click="claimVoucher()" :disabled="isClaiming || !claimCode" class="px-6 py-3 bg-[#F5820A] hover:bg-[#d67208] disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
            <span x-show="!isClaiming">Klaim Voucher</span>
            <span x-show="isClaiming">Mengkalim...</span>
        </button>
    </div>

    {{-- Loading --}}
    <div x-show="isLoading" class="flex justify-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#F5820A]"></div>
    </div>

    {{-- Grid List Voucher --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6" x-show="!isLoading" x-cloak>
        <template x-for="voucher in vouchers" :key="voucher.id_voucher">
            <div :class="new Date(voucher.expired_at) < new Date() || voucher.kuota <= 0 ? 'bg-gray-50 opacity-60' : 'bg-white hover:shadow-md'" class="border border-gray-200 rounded-2xl overflow-hidden flex shadow-sm transition-shadow relative">
                
                <div :class="new Date(voucher.expired_at) < new Date() || voucher.kuota <= 0 ? 'bg-gray-400' : 'bg-gradient-to-br from-[#F5820A] to-[#E06E00]'" class="w-24 sm:w-32 flex flex-col items-center justify-center text-white shrink-0 p-3 border-r-[3px] border-dashed border-white">
                    <i class="fa-solid fa-ticket text-2xl sm:text-3xl mb-2"></i>
                    <span class="text-[10px] sm:text-xs font-bold text-center leading-tight">Diskon<br><span x-text="parseFloat(voucher.potongan_persen) + '%'"></span></span>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between relative">
                    {{-- Watermark Habis --}}
                    <div x-show="new Date(voucher.expired_at) < new Date() || voucher.kuota <= 0" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                        <span class="border-2 border-red-500 text-red-500 text-lg font-black uppercase px-3 py-1 rounded-lg transform -rotate-12 opacity-80" x-text="voucher.kuota <= 0 ? 'HABIS' : 'KADALUARSA'"></span>
                    </div>
                    
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-gray-900" x-text="'Diskon ' + parseFloat(voucher.potongan_persen) + '%'"></h3>
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-1" x-text="'Kode: ' + voucher.kode_voucher"></p>
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-1" x-show="voucher.barang">Khusus: <span x-text="voucher.barang.nama"></span></p>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-[10px] sm:text-[11px] font-medium text-gray-500" x-text="'Berakhir ' + new Date(voucher.expired_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></span>
                        
                        <button x-show="new Date(voucher.expired_at) >= new Date() && voucher.kuota > 0" class="text-xs font-bold text-[#F5820A] border border-[#F5820A] bg-orange-50 hover:bg-[#F5820A] hover:text-white px-4 py-1.5 rounded-lg transition-colors">Pakai</button>
                        
                        <button x-show="new Date(voucher.expired_at) < new Date() || voucher.kuota <= 0" disabled class="text-xs font-bold text-gray-400 bg-gray-200 px-4 py-1.5 rounded-lg cursor-not-allowed">Pakai</button>
                    </div>
                </div>
            </div>
        </template>
        
        <div x-show="vouchers.length === 0" class="col-span-1 lg:col-span-2 text-center py-12 text-gray-500 bg-white rounded-2xl border border-gray-100">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <p>Belum ada voucher yang tersedia.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('voucherPage', () => ({
        vouchers: [],
        isLoading: true,
        claimCode: '',
        isClaiming: false,
        message: { text: '', type: '' },

        async init() {
            this.fetchVouchers();
        },

        showMessage(text, type = 'success') {
            this.message = { text, type };
            setTimeout(() => this.message.text = '', 5000);
        },

        async fetchVouchers() {
            this.isLoading = true;
            try {
                const res = await fetch('/api-proxy/voucher');
                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    this.vouchers = json.data;
                }
            } catch (e) {
                console.error("Gagal memuat voucher", e);
            } finally {
                this.isLoading = false;
            }
        },

        async claimVoucher() {
            if (!this.claimCode) return;
            this.isClaiming = true;
            try {
                const res = await fetch('/api-proxy/voucher/claim', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ kode_voucher: this.claimCode.toUpperCase() })
                });
                const json = await res.json();
                
                if (res.ok && json.success) {
                    this.showMessage('Voucher berhasil diklaim!');
                    this.claimCode = '';
                    this.fetchVouchers(); // Refresh list
                } else {
                    this.showMessage(json.message || 'Gagal klaim voucher', 'error');
                }
            } catch (e) {
                this.showMessage('Terjadi kesalahan koneksi', 'error');
            } finally {
                this.isClaiming = false;
            }
        }
    }));
});
</script>
@endsection