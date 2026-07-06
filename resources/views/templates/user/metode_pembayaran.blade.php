@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto" x-data="pembayaranPage()">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Metode Pembayaran</h1>
            <p class="text-gray-500 text-sm">Kelola daftar rekening bank dan e-wallet untuk kemudahan bertransaksi.</p>
        </div>
    </div>

    <div class="space-y-6">
        
        {{-- ========================================= --}}
        {{-- 1. SALDO KOPERASI (METODE UTAMA)            --}}
        {{-- ========================================= --}}
        <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 lg:p-8 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
            <i class="fa-solid fa-wallet absolute -right-6 -top-6 text-8xl opacity-10"></i>
            
            <div class="relative z-10 w-full md:w-auto text-center md:text-left">
                <p class="text-sm font-medium text-green-100 mb-1">Saldo Koperasi Pay</p>
                <div x-show="isLoading" class="animate-pulse h-9 bg-white/20 rounded w-48 mt-1"></div>
                <h2 x-show="!isLoading" class="text-3xl font-extrabold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(walletBalance)" x-cloak>Rp 0</h2>
                <div class="inline-flex items-center gap-1.5 text-xs text-green-200 mt-3 bg-black/10 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-circle-check text-green-400"></i> Metode pembayaran utama
                </div>
            </div>
            
            <div class="relative z-10 w-full md:w-auto flex gap-3">
                <button @click="topUp()" class="w-full md:w-auto px-6 py-3 bg-white text-[#2D7A42] font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center justify-center gap-2 disabled:opacity-50" :disabled="isToppingUp">
                    <i x-show="isToppingUp" class="fa-solid fa-spinner fa-spin"></i>
                    <i x-show="!isToppingUp" class="fa-solid fa-arrow-up-right-dots"></i>
                    <span x-text="isToppingUp ? 'Memproses...' : 'Top Up Saldo'"></span>
                </button>
            </div>
        </div>

        @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-dummy') }}"></script>
        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pembayaranPage', () => ({
                walletBalance: 0,
                isLoading: true,
                isToppingUp: false,
                async init() {
                    await this.loadWallet();
                },
                async loadWallet() {
                    this.isLoading = true;
                    try {
                        const res = await fetch('/api-proxy/dashboard');
                        const json = await res.json();
                        if (json.success) {
                            this.walletBalance = json.data.dashboard_metrics?.wallet_balance || 0;
                        }
                    } catch (e) {
                        console.error('Failed to load wallet', e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async topUp() {
                    const { value: amount } = await Swal.fire({
                        title: 'Top Up Saldo',
                        input: 'number',
                        inputLabel: 'Masukkan nominal top up (Min. Rp 10.000)',
                        inputPlaceholder: '10000',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#2D7A42',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Nominal tidak boleh kosong!';
                            }
                            if (parseInt(value) < 10000) {
                                return 'Minimal top up adalah Rp 10.000';
                            }
                        }
                    });

                    if (amount) {
                        this.processTopUp(amount);
                    }
                },
                async processTopUp(amount) {
                    this.isToppingUp = true;
                    try {
                        const idempotencyKey = typeof crypto !== 'undefined' && crypto.randomUUID ? crypto.randomUUID() : 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => { const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8); return v.toString(16); });
                        const res = await fetch('/api-proxy/wallet/topup', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Idempotency-Key': idempotencyKey
                            },
                            body: JSON.stringify({ gross_amount: amount })
                        });
                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat memproses top up.', 'error');
                            this.isToppingUp = false;
                            return;
                        }

                        // Panggil Snap Midtrans
                        const snapToken = data.data.snap_token;
                        const orderId = data.data.order_id;
                        
                        window.snap.pay(snapToken, {
                            onSuccess: async (result) => {
                                // Manual verifikasi status karena webhook localhost mungkin tidak jalan
                                try {
                                    await fetch('/api-proxy/wallet/check-status', {
                                        method: 'POST',
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({ order_id: orderId })
                                    });
                                } catch (err) {}
                                
                                Swal.fire('Berhasil!', 'Top up berhasil dilakukan.', 'success').then(() => {
                                    this.loadWallet();
                                });
                            },
                            onPending: (result) => {
                                Swal.fire('Menunggu Pembayaran', 'Silakan selesaikan pembayaran Anda.', 'info').then(() => {
                                    this.loadWallet();
                                });
                            },
                            onError: (result) => {
                                Swal.fire('Gagal', 'Pembayaran gagal atau kadaluarsa.', 'error');
                            },
                            onClose: () => {
                                this.isToppingUp = false;
                            }
                        });

                    } catch (e) {
                        console.error(e);
                        Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
                        this.isToppingUp = false;
                    }
                }
            }));
        });
        </script>
        @endpush

        {{-- ========================================= --}}
        {{-- 2. REKENING BANK                            --}}
        {{-- ========================================= --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">Transfer Bank</h3>
            
            <div class="space-y-4">
                {{-- Bank Item 1 (Tersimpan / Default) --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-2 border-[#2D7A42]/20 bg-[#E8F5EC]/30 rounded-xl transition-colors group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-14 h-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100 shrink-0">
                            <span class="text-blue-700 font-extrabold text-sm italic">BCA</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Bank Central Asia</h4>
                            <p class="text-xs text-gray-500 font-medium">**** **** 1234 a/n Ardian Putra</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0 mt-2 sm:mt-0">
                        <button class="text-xs font-semibold text-gray-400 hover:text-red-500 transition-colors px-2">Hapus</button>
                        <span class="px-3 py-1.5 bg-[#2D7A42] text-white text-[10px] font-bold uppercase rounded-md flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i> Tersimpan
                        </span>
                    </div>
                </div>

                {{-- Bank Item 2 --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-gray-200 rounded-xl hover:border-[#2D7A42] transition-colors group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-14 h-10 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shrink-0">
                            <span class="text-orange-600 font-extrabold text-sm">BNI</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Bank Negara Indonesia</h4>
                            <p class="text-xs text-gray-500 font-medium">**** **** 5678 a/n Ardian Putra</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0 mt-2 sm:mt-0">
                        <button class="text-xs font-semibold text-gray-500 hover:text-red-500 transition-colors px-2">Hapus</button>
                        <button class="px-3 py-1.5 bg-white border border-[#2D7A42] text-[#2D7A42] hover:bg-[#E8F5EC] text-[10px] font-bold uppercase rounded-md transition-colors">
                            Jadikan Utama
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- 3. E-WALLET                               --}}
        {{-- ========================================= --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">E-Wallet</h3>
            
            <div class="space-y-4">
                {{-- GoPay --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-gray-200 rounded-xl hover:border-[#2D7A42] transition-colors group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-14 h-10 bg-[#00AED6] rounded-lg flex items-center justify-center border border-[#00AED6] shrink-0">
                            <span class="text-white font-extrabold text-xs tracking-wider">gopay</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">GoPay</h4>
                            <p class="text-xs text-gray-500 font-medium">0812-****-7890</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <button class="w-full sm:w-auto px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                            Putuskan Koneksi
                        </button>
                    </div>
                </div>
                
                {{-- OVO (Belum terkoneksi) --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-dashed border-gray-300 bg-gray-50/50 rounded-xl group cursor-pointer hover:bg-[#E8F5EC] hover:border-[#2D7A42] transition-all">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0 opacity-60 group-hover:opacity-100 transition-opacity">
                        <div class="w-14 h-10 bg-[#4C3494] rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-white font-extrabold text-xs tracking-wider">OVO</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">OVO</h4>
                            <p class="text-xs text-gray-400 font-medium">Belum terhubung</p>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto flex justify-end">
                        <span class="text-xs font-bold text-[#2D7A42]">Hubungkan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- E-WALLET SECTION MOCKED OUT --}}
    </div>
</div>
@endsection