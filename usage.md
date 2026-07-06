# Panduan Integrasi API Koperasi 6G (Frontend)

Dokumen ini berisi panduan dasar bagi tim Frontend (React/Vue/Svelte/Mobile) untuk berinteraksi dengan Backend Laravel & Layanan Machine Learning.

---

## 1. Base URL
Semua request API mengarah ke:
- **Backend Utama (Laravel)**: `http://localhost:8000/api`
*(Machine Learning Service di-handle secara internal oleh Laravel, frontend cukup hit Laravel saja).*

---

## 2. Format Response Standar
Sistem ini menggunakan arsitektur API-first. Semua response (sukses maupun error validasi) akan dibungkus dalam format standar berikut:

**Sukses (2xx):**
```json
{
    "success": true,
    "message": "Transaksi berhasil diproses",
    "data": { ... }
}
```

**Gagal (4xx / 5xx):**
```json
{
    "success": false,
    "message": "Stok tidak mencukupi",
    "data": { ... } 
}
```

---

## 3. Autentikasi (Laravel Sanctum)
Sebagian besar endpoint memerlukan autentikasi. Frontend harus menyertakan **Bearer Token** (didapat saat login) pada header HTTP:

```http
Authorization: Bearer <token_dari_login>
Accept: application/json
```

*Endpoint `/api/auto-login` dapat digunakan saat pertama kali aplikasi dibuka untuk memvalidasi apakah token masih aktif tanpa harus menanyakan password.*

---

## 4. Pencegahan Double Submit (Wajib untuk Transaksi)
Untuk menghindari bug seperti *double-click* atau *network retry* yang menyebabkan saldo terpotong 2x, frontend **WAJIB** mengirimkan header `X-Idempotency-Key` (berupa UUID v4) pada request yang bersifat krusial (Checkout, Topup, Klaim Voucher).

```http
X-Idempotency-Key: 123e4567-e89b-12d3-a456-426614174000
```
*Gunakan library UUID di frontend. UUID yang sama akan diabaikan oleh server jika dikirim berulang dalam waktu singkat.*

---

## 5. Real-Time WebSockets (Laravel Reverb)
Untuk notifikasi *real-time* seperti update stok atau peringatan dari admin, frontend dapat melakukan *subscribe* ke channel publik menggunakan `pusher-js` & `laravel-echo`.

**Instalasi:**
```bash
npm install pusher-js laravel-echo
```

**Konfigurasi Frontend (JS):**
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY, // jktoac5no58fwjrgtgcg
    wsHost: import.meta.env.VITE_REVERB_HOST, // localhost
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

// Dengarkan Broadcast
window.Echo.channel('tugas-channel')
    .listen('DataUpdated', (event) => {
        console.log("Realtime Update Diterima:", event.payload);
        // Lakukan trigger refresh UI atau tampilkan toast notification
    });
```

---

## 6. Daftar Endpoint Penting (Overview)

### A. Autentikasi (Public)
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/login` | Memerlukan `username`/`email` & `password`. Mengembalikan token. |
| POST | `/api/register` | Mendaftarkan akun baru. |

### B. Barang & Katalog (Auth Optional / Auth Required)
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/barang` | Mendukung **Faceted Search**: query `q`, `harga_min`, `harga_max`, `kategori`, `in_stock=true`. Akan mereturn `data`, `facets`, dan `meta` (pagination). |
| GET | `/api/kategori` | Menampilkan seluruh kategori. |
| GET | `/api/rekomendasi` | Menampilkan rekomendasi produk khusus untuk user login (Machine Learning). |

### C. Transaksi & Dompet (Auth Required)
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/wallet/topup` | **[Idempotency Key Req]** Req: `gross_amount`. Mereturn `snap_token` untuk Midtrans. |
| POST | `/api/transaction/checkout` | **[Idempotency Key Req]** Checkout barang, akan otomatis deteksi *fraud* dan memotong stok. |
| GET | `/api/transaction/history` | Riwayat belanja pengguna. |
| GET | `/api/transaction/{id}/track` | Melacak status pengiriman. |

### D. Voucher
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/voucher/check/{kode}`| Cek validasi voucher. |
| POST | `/api/voucher/claim` | **[Idempotency Key Req]** Klaim voucher tipe *claim*. |
| POST | `/api/voucher/use` | **[Idempotency Key Req]** Gunakan voucher. |

### E. Admin
Admin memiliki akses ke `/api/admin/*` untuk memanajemen User, Barang, Kategori, Voucher, dan memantau `/api/admin/stok/alert` (Prediksi Stok).

*Untuk detail struktur JSON request/response masing-masing API, silakan melakukan uji coba endpoint langsung via Postman atau cek controller terkait.*
