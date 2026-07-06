# 📦 Sistem Koperasi Kelas 6G (Panduan Khusus Agent Claude)

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

## 📁 Struktur Direktori Aktual

```
front-end-api-merger/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ApiProxyController.php  ← 🔑 BFF Proxy (JANGAN DIUBAH)
│   │   │   ├── AuthController.php      ← Login/Register/Logout → Hit Backend API
│   │   │   ├── BarangController.php
│   │   │   ├── ProductController.php   ← Halaman produk/kategori
│   │   │   └── VoucherController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php           ← Cek role user dari session
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── ClaimVoucherRequest.php
│   │       ├── StoreVoucherRequest.php
│   │       ├── UpdateVoucherRequest.php
│   │       └── UseVoucherRequest.php
│   ├── Models/                         ← Eloquent models (referensi saja)
│   │   ├── User.php, Barang.php, Kategori.php
│   │   ├── Voucher.php, VoucherClaim.php
│   │   ├── Transaction.php, TransactionDetail.php
│   │   ├── Customer.php, Wallet.php, WalletHistory.php
│   │   ├── Merk.php, Supplier.php, StokHistory.php
│   │   └── UserProfile.php, Audit.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── resources/
│   ├── css/
│   │   └── app.css                     ← Entry Tailwind CSS
│   ├── js/
│   │   └── app.js                      ← Entry JS (Alpine.js & Echo)
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           ← Master Layout utama
│       ├── templates/
│       │   ├── navbar.blade.php
│       │   ├── sidebar.blade.php
│       │   └── user/
│       │       └── display_kategori_produk.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── lupa_password.blade.php
│       │   ├── verifikasi_otp.blade.php
│       │   ├── buat_sandi_baru.blade.php
│       │   └── verifikasi_sandi_baru.blade.php
│       ├── dashboard_user.blade.php
│       └── welcome.blade.php
│
├── routes/
│   ├── web.php                         ← Route Web + `/api-proxy/{any}` catch-all
│   └── console.php
│
├── .env                                ← Wajib ada: API_BASE_URL=http://localhost:8000/api
├── vite.config.js
└── package.json
```

### Konvensi Penambahan File
- **Halaman baru**: `resources/views/<domain>/nama.blade.php` (contoh: `views/checkout/index.blade.php`)
- **Komponen reusable**: `resources/views/templates/` (lihat `navbar.blade.php` sebagai referensi gaya)
- **Controller baru**: `app/Http/Controllers/` — lihat `ApiProxyController.php` sebagai pola referensi

---

## 🔑 Arsitektur BFF Proxy (Wajib Dipahami)

Token Sanctum disimpan sebagai **HttpOnly Cookie** (`api_token`) — JavaScript tidak dapat membacanya. Maka **semua request AJAX dari browser HARUS melalui proxy internal Frontend**, bukan langsung ke Backend.

```
Browser (Alpine.js)
    │  fetch('/api-proxy/barang')
    ▼
ApiProxyController.php  ←  membaca Cookie api_token dari server
    │  Http::withToken($token)->get('http://localhost:8000/api/barang')
    ▼
Backend koperasi6G (port 8000)
    │  Validasi Sanctum → proses → return JSON
    ▼
ApiProxyController  →  return response ke Browser
```

```javascript
// ✅ BENAR
fetch('/api-proxy/profile')

// ❌ SALAH — dilarang keras
fetch('http://localhost:8000/api/profile', { headers: { Authorization: 'Bearer ...' } })
```

---

## 🔗 Panduan Integrasi API

### Kapan Pakai AJAX vs WebSocket?
| Skenario | Gunakan |
|----------|---------|
| User klik tombol, submit form, buka halaman | **AJAX ke `/api-proxy/`** |
| Data berubah otomatis tanpa aksi user | **WebSocket (Echo)** |

### Pola AJAX Standar (Alpine.js)
```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('namaComponent', () => ({
        isLoading: false,
        data: null,
        error: null,

        async fetchData() {
            this.isLoading = true;
            try {
                const res = await fetch('/api-proxy/barang', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const json = await res.json();

                if (!res.ok || !json.success) {
                    Swal.fire('Gagal', json.message, 'error');
                    if (res.status === 401) window.location.href = '/login';
                    return;
                }
                this.data = json.data;
            } catch (e) {
                Swal.fire('Error', 'Server tidak dapat dijangkau.', 'error');
            } finally {
                this.isLoading = false;
            }
        }
    }))
})
```

### Wajib: Idempotency Key untuk Transaksi
Untuk endpoint **Checkout** dan **Topup Wallet**, selalu sertakan header `X-Idempotency-Key`:
```javascript
const res = await fetch('/api-proxy/transaction/checkout', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Idempotency-Key': crypto.randomUUID() // Dibuat baru setiap klik
    },
    body: JSON.stringify(payload)
});
```

### Format Response API (Standar Backend)
```json
{ "success": true,  "message": "Berhasil",     "data": { ... } }
{ "success": false, "message": "Pesan error",  "data": { "field": ["error"] } }
```
| HTTP Code | Arti | Tindakan |
|-----------|------|----------|
| 200/201 | Sukses | Tampilkan data / redirect |
| 401 | Token expired / tidak ada | Redirect ke `/login` |
| 422 | Validasi gagal | Tampilkan error per field |
| 409 | Idempotency conflict | Jangan kirim ulang |
| 500 | Error server | Tampilkan pesan generik |

### Konfigurasi Real-Time (Laravel Echo)
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

// Dengarkan event global dari Backend
window.Echo.channel('tugas-channel').listen('DataUpdated', (e) => {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'info',
        title: 'Update!', text: JSON.stringify(e.payload),
        showConfirmButton: false, timer: 3000
    });
});
```

---

## 🎨 Panduan Desain & UI

- 🛑 **UI FREEZE**: Dilarang mengubah `class` Tailwind, warna, padding, margin, flex/grid yang sudah ada.
- **Hanya Data Binding**: Tambahkan `x-text`, `x-bind:class`, `x-show`, `x-for` ke elemen HTML yang sudah ada.
- **Feedback User**: Gunakan **SweetAlert2** untuk loading, sukses, dan error. Jangan buat modal atau elemen baru secara manual.
- **Loading State**: Selalu tambahkan `x-bind:disabled="isLoading"` pada tombol submit.

---

## 🏁 Alur Kerja Agent (Claude)

1. **Baca dulu** file view yang diminta — pahami strukturnya sebelum menulis.
2. **Tambahkan** Alpine.js data binding tanpa mengubah layout HTML/CSS.
3. **Gunakan selalu** `/api-proxy/` untuk semua AJAX — jangan langsung ke `localhost:8000`.
4. **Sertakan** `X-CSRF-TOKEN` di semua request POST/PUT/PATCH/DELETE.
5. **Sertakan** `X-Idempotency-Key` untuk endpoint transaksi dan topup wallet.
6. **Handle** semua status error (401 → redirect login, 422 → inline error, 5xx → toast error).

---

## 🚀 Cara Menjalankan Ekosistem Penuh

| Terminal | Direktori | Perintah | Keterangan |
|----------|-----------|----------|------------|
| 1 | `d:\koperasi6G` | `composer run-api` | Backend API (8000) + Queue + Reverb WebSocket (8080) |
| 2 | `d:\front-end-api-merger` | `php artisan serve --port=8001` | Frontend Laravel (8001) |
| 3 | `d:\front-end-api-merger` | `npm run dev` | Vite — HMR Tailwind & JS |
| 4 *(opsional)* | `d:\python-starter` | `python main.py` | FastAPI ML Service (5610) |

> [!IMPORTANT]
> Frontend berjalan di **port 8001**, Backend di **port 8000**.
> Pastikan `.env` Frontend memiliki: `API_BASE_URL=http://localhost:8000/api`
