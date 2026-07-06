# 📦 Sistem Koperasi Kelas 6G (Panduan Khusus Agent Frontend)

> **Misi Anda sebagai AI (Agent)**: Repositori ini adalah proyek **Frontend** berbasis **Laravel Blade, Alpine.js, dan Tailwind CSS 4**. Tugas Anda adalah mengintegrasikan data dari Backend API ke dalam UI yang sudah ada.
>
> 🛑 **ATURAN MUTLAK (UI FREEZE): DESAIN ANTARMUKA SAAT INI SUDAH FINAL!** Dilarang keras merombak, menghapus, atau mengubah layout HTML/CSS secara drastis. Pekerjaan Anda **100% HANYA FOKUS PADA INTEGRASI DATA** (AJAX via Proxy, State Management Alpine.js `x-data`, binding data ke UI, dan WebSocket). Jangan ubah estetika visual yang sudah ada.

---

## 🛠️ Stack Teknologi Frontend
| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 13 (View-only, bukan API) |
| Styling | Tailwind CSS v4 via `@tailwindcss/vite` |
| Font | Instrument Sans (via Bunny Fonts di `vite.config.js`) |
| Reactivity | Alpine.js v3 (`x-data`, `x-bind`, `x-on`, `x-text`, `x-for`) |
| Alerts/Toasts | SweetAlert2 |
| Real-Time Client | Laravel Echo + `pusher-js` (mendengarkan Reverb di backend) |
| Build Tool | Vite 8 |

---

## 📁 Struktur Direktori Aktual (Hasil Scan)

```
front-end-api-merger/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ApiProxyController.php  ← 🔑 BFF Proxy (JANGAN DIUBAH)
│   │   │   ├── AuthController.php      ← Login/Register/Logout → Hit Backend API
│   │   │   ├── BarangController.php    ← (Jika ada logika view khusus barang)
│   │   │   ├── ProductController.php   ← Menampilkan halaman produk
│   │   │   └── VoucherController.php   ← (Jika ada logika view khusus voucher)
│   │   ├── Middleware/
│   │   │   └── CheckRole.php           ← Middleware cek role user dari session
│   │   └── Requests/                   ← Form Request (validasi sisi frontend)
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── ClaimVoucherRequest.php
│   │       ├── StoreVoucherRequest.php
│   │       ├── UpdateVoucherRequest.php
│   │       └── UseVoucherRequest.php
│   ├── Models/                         ← Model Eloquent (dibaca saja, jangan ditulis)
│   │   ├── User.php, Barang.php, Kategori.php, Voucher.php
│   │   ├── Transaction.php, TransactionDetail.php
│   │   ├── Customer.php, Wallet.php, WalletHistory.php
│   │   └── ... (dll)
│   └── Providers/
│       └── AppServiceProvider.php
│
├── resources/
│   ├── css/
│   │   └── app.css                     ← Entry Tailwind CSS (@import tailwindcss)
│   ├── js/
│   │   └── app.js                      ← Entry JS (inisialisasi Alpine.js & Echo di sini)
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           ← Master Layout utama situs
│       ├── templates/
│       │   ├── navbar.blade.php        ← Komponen Navbar
│       │   ├── sidebar.blade.php       ← Komponen Sidebar
│       │   └── user/
│       │       └── display_kategori_produk.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── lupa_password.blade.php
│       │   ├── verifikasi_otp.blade.php
│       │   ├── buat_sandi_baru.blade.php
│       │   └── verifikasi_sandi_baru.blade.php
│       ├── dashboard_user.blade.php    ← Halaman dashboard customer
│       └── welcome.blade.php           ← Halaman landing / beranda
│
├── routes/
│   ├── web.php                         ← Semua route Web + Proxy Route
│   └── console.php
│
├── .env                                ← Konfigurasi (termasuk API_BASE_URL)
├── vite.config.js
└── package.json
```

### Konvensi Penambahan File Baru
- **Halaman baru per fitur**: Tambahkan di `resources/views/` dengan folder sesuai domain (contoh: `views/checkout/index.blade.php`, `views/produk/detail.blade.php`).
- **Komponen reusable**: Tambahkan di `resources/views/templates/` (sudah ada `navbar.blade.php` dan `sidebar.blade.php` sebagai referensi gaya).
- **Controller baru**: Taruh di `app/Http/Controllers/`. Untuk logika yang memakai API, gunakan `ApiProxyController` sebagai referensi pola.

---

## 🔑 Arsitektur BFF Proxy (Sangat Penting!)

Karena token keamanan disimpan di **HttpOnly Cookie** (tidak bisa dibaca JS), semua AJAX dari browser **DILARANG** langsung memanggil `http://localhost:8000/api`.

```
Browser (Alpine.js)
        │ fetch('/api-proxy/barang')
        ▼
[ Frontend Laravel - ApiProxyController ]
        │ Ambil cookie api_token → Pasang Authorization: Bearer <token>
        │ Http::withToken($token)->get('http://localhost:8000/api/barang')
        ▼
[ Backend Laravel - API koperasi6G:8000 ]
        │ Validasi Sanctum token → Proses → Return JSON
        ▼
[ ApiProxyController ] → return response ke Browser
```

**Cara memanggilnya dari Alpine.js/Javascript:**
```javascript
// ✅ BENAR — Gunakan path /api-proxy/
fetch('/api-proxy/barang', {
    headers: { 'Accept': 'application/json' }
})

// ❌ SALAH — Dilarang keras
fetch('http://localhost:8000/api/barang', {
    headers: { 'Authorization': 'Bearer ...' }
})
```

---

## 🔗 Panduan Integrasi API

### 1. AJAX vs WebSocket
| Kapan? | Teknologi | Contoh |
|--------|-----------|--------|
| User melakukan aksi (klik tombol, submit form) | **AJAX via `/api-proxy`** | Login, Checkout, Ambil list barang |
| Server mengirim update otomatis tanpa aksi user | **WebSocket (Echo)** | Notif stok habis, update status order |

### 2. Standar Pemanggilan AJAX (Alpine.js)
```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('checkoutProcess', () => ({
        isLoading: false,
        error: null,

        async submit() {
            this.isLoading = true;
            this.error = null;
            try {
                const uuid = crypto.randomUUID(); // Anti double-submit
                const res = await fetch('/api-proxy/transaction/checkout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Idempotency-Key': uuid  // WAJIB untuk transaksi
                    },
                    body: JSON.stringify({ /* payload */ })
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire('Gagal', data.message, 'error');
                    return;
                }
                Swal.fire('Berhasil!', data.message, 'success');
            } catch (e) {
                Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
            } finally {
                this.isLoading = false;
            }
        }
    }))
})
```

### 3. Wajib: Idempotency Key (Anti Double Submit)
Untuk endpoint transaksi (Checkout & Topup Wallet), selalu sertakan:
```
'X-Idempotency-Key': crypto.randomUUID()
```
Key ini **dibuat baru setiap kali tombol diklik**, bukan satu kali saat halaman load.

### 4. Format Response API (Standar)
```json
{ "success": true, "message": "Berhasil", "data": { ... } }
{ "success": false, "message": "Pesan error", "data": { "field": ["error msg"] } }
```
- Kode `200/201` → sukses
- Kode `401` → token expired/tidak ada → redirect ke `/login`
- Kode `422` → validasi form gagal → tampilkan error per field
- Kode `409` → idempotency conflict (request ganda) → jangan kirim ulang

### 5. Konfigurasi Real-Time (Laravel Echo)
Inisialisasi di `resources/js/app.js`:
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Dengarkan notifikasi global dari Backend
window.Echo.channel('tugas-channel').listen('DataUpdated', (e) => {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'info',
        title: 'Notifikasi Baru', text: JSON.stringify(e.payload),
        showConfirmButton: false, timer: 3000
    });
});
```

---

## 🎨 Panduan Desain & UI

- 🛑 **UI FREEZE**: Desain sudah final. Dilarang mengubah `class` Tailwind, warna, padding, flex/grid yang sudah ada.
- **Hanya Data Binding**: Anda hanya boleh menambahkan atribut Alpine.js (`x-text`, `x-bind:class`, `x-show`, `x-for`) ke HTML yang sudah ada.
- **Alert/Error**: Gunakan **SweetAlert2** untuk feedback. Jangan ubah struktur DOM secara ekstrem.

---

## 🏁 Alur Kerja Agent AI

1. Baca file view yang ada sebelum menulis kode — pahami strukturnya.
2. Integrasikan data dengan menambahkan `x-data`, `x-text`, `x-for` tanpa mengubah layout.
3. Semua AJAX wajib ke `/api-proxy/` (bukan `localhost:8000`).
4. Gunakan `X-Idempotency-Key` untuk semua endpoint transaksi/topup.
5. Tampilkan loading/error state menggunakan SweetAlert2.
6. Tambahkan `X-CSRF-TOKEN` header untuk semua POST/PUT/PATCH/DELETE request.

---

## 🚀 Cara Menjalankan Ekosistem Penuh

| Terminal | Direktori | Perintah | Keterangan |
|----------|-----------|----------|------------|
| 1 | `d:\koperasi6G` | `composer run-api` | Backend API (port 8000), Queue, Reverb WebSocket (port 8080) |
| 2 | `d:\front-end-api-merger` | `php artisan serve --port=8001` | Frontend Laravel (port 8001) |
| 3 | `d:\front-end-api-merger` | `npm run dev` | Vite (HMR Tailwind CSS & JS) |
| 4 (opsional) | `d:\python-starter` | `python main.py` | FastAPI ML Service (port 5610) |

> [!IMPORTANT]
> Frontend berjalan di **port 8001**, Backend di **port 8000**. Pastikan `API_BASE_URL=http://localhost:8000/api` sudah ada di file `.env` Frontend.
