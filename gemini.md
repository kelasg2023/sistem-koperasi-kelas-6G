# 📦 Sistem Koperasi Kelas 6G

> Aplikasi manajemen koperasi sekolah berbasis **Laravel 13** (API-first architecture) dengan autentikasi **Sanctum**, real-time broadcasting via **Laravel Reverb**, dan database **MySQL**.

---

## 🛠️ Tech Stack

| Layer         | Teknologi                          |
|---------------|------------------------------------|
| Framework     | Laravel 13.x                       |
| PHP           | ^8.3                               |
| Database      | MySQL                              |
| Auth          | Laravel Sanctum (Token-based)      |
| Real-time     | Laravel Reverb (WebSocket)         |
| Queue         | Laravel Queue (database driver)    |
| Email         | Laravel Mail + Queue (SendEmailJob)|
| Frontend Build| Vite 8 + Tailwind CSS 4            |
| Broadcasting  | Laravel Echo + Pusher JS           |

---

## 🏗️ Arsitektur Aplikasi

Aplikasi ini menggunakan arsitektur **API-first** di mana backend hanya menyediakan JSON API. Seluruh response menggunakan format standar via `ApiResponse` trait:

```json
{
    "success": true|false,
    "message": "Pesan deskriptif",
    "data": { ... }
}
```

### Design Patterns yang Digunakan

- **Trait `ApiResponse`** — Standardisasi format JSON response di semua controller
- **Form Request Validation** — Validasi terpisah untuk Voucher (`StoreVoucherRequest`, `UpdateVoucherRequest`, `ClaimVoucherRequest`, `UseVoucherRequest`)
- **Middleware Role-Based Access** — `CheckRole` middleware untuk pembatasan akses berdasarkan role user
- **Database Transaction + lockForUpdate()** — Mencegah race condition pada checkout dan penggunaan voucher
- **Soft Deletes** — Pada tabel `users`, `barang`, dan `vouchers`
- **Broadcasting Event** — `DataUpdated` event untuk real-time notification via public channel

---

## 👥 Sistem Role & Akses

Terdapat **5 role** dalam sistem:

| Role       | Akses                                                      |
|------------|-------------------------------------------------------------|
| `admin`    | Full CRUD Users, Barang, Kategori, Voucher + Admin Dashboard |
| `staff`    | Staff Dashboard                                             |
| `supplier` | Supplier Dashboard                                          |
| `manager`  | Manager Dashboard                                           |
| `customer` | Browse Barang, Kategori, Voucher, Checkout, Tracking        |

---

## 📊 Database Schema (ERD)

### Tabel Utama

#### `users`
| Kolom       | Tipe                                            | Keterangan              |
|-------------|--------------------------------------------------|-------------------------|
| `id_users`  | BIGINT (PK, AI)                                  | Primary key             |
| `username`  | VARCHAR(255)                                     | Unique                  |
| `email`     | VARCHAR(255)                                     | Unique                  |
| `password`  | VARCHAR(255)                                     | Hashed                  |
| `role`      | ENUM(admin, staff, supplier, manager, customer)  | Role user               |
| `deleted_at`| TIMESTAMP                                        | Soft delete             |
| `timestamps`| TIMESTAMP                                        | created_at, updated_at  |

#### `users_profiles`
| Kolom             | Tipe              | Keterangan                |
|-------------------|--------------------|--------------------------|
| `profiles_id`     | BIGINT (PK, AI)    | Primary key              |
| `user_id`         | FK → users         | One-to-one               |
| `name`            | VARCHAR(255)       | Nama lengkap             |
| `address`         | TEXT (nullable)    | Alamat                   |
| `profile_picture` | VARCHAR (nullable) | URL foto profil          |
| `phone`           | VARCHAR(14)        | Nomor telepon            |
| `is_member`       | BOOLEAN            | Status keanggotaan       |

#### `kategori`
| Kolom           | Tipe          | Keterangan      |
|-----------------|---------------|-----------------|
| `id_kategori`   | BIGINT (PK)   | Primary key     |
| `nama_kategori` | VARCHAR(50)   | Nama kategori   |
| `satuan`        | VARCHAR(10)   | Satuan (pcs, kg)|

#### `barang`
| Kolom           | Tipe             | Keterangan                            |
|-----------------|-------------------|--------------------------------------|
| `barang_id`     | BIGINT (PK, AI)   | Primary key                          |
| `nama`          | VARCHAR(255)      | Nama barang                          |
| `stok`          | INTEGER           | Jumlah stok (default 0)             |
| `harga`         | DECIMAL(15,2)     | Harga satuan                         |
| `diskon_persen` | DECIMAL(5,2)      | Diskon default barang (%)            |
| `deskripsi`     | TEXT (nullable)   | Deskripsi barang                     |
| `id_kategori`   | FK → kategori     | Relasi ke kategori                   |
| `deleted_at`    | TIMESTAMP         | Soft delete                          |

**Indexes (Optimized untuk MySQL):**
- `INDEX` pada `id_kategori`, `harga`, `stok`
- `FULLTEXT INDEX` pada `(nama, deskripsi)` — untuk faceted search

#### `merk`
| Kolom       | Tipe            | Keterangan       |
|-------------|-----------------|------------------|
| `id_merk`   | BIGINT (PK)     | Primary key      |
| `barang_id` | FK → barang     | Relasi ke barang |
| `nama_merk` | VARCHAR         | Nama merk        |

#### `supplier`
| Kolom            | Tipe            | Keterangan          |
|------------------|-----------------|---------------------|
| `id_supplier`    | BIGINT (PK)     | Primary key         |
| `barang_id`      | FK → barang     | Relasi ke barang    |
| `nama_supplier`  | VARCHAR         | Nama supplier       |
| `kontak_supplier`| VARCHAR         | Kontak supplier     |
| `alamat_supplier`| TEXT            | Alamat supplier     |

#### `stok_history`
| Kolom           | Tipe            | Keterangan                     |
|-----------------|-----------------|--------------------------------|
| `id_stok`       | BIGINT (PK)     | Primary key                    |
| `barang_id`     | FK → barang     | Relasi ke barang               |
| `tipe`          | ENUM(masuk/keluar)| Jenis perubahan stok         |
| `jumlah`        | INTEGER         | Jumlah perubahan               |
| `keterangan`    | TEXT            | Catatan perubahan              |
| `created_at`    | TIMESTAMP       | Waktu perubahan                |

#### `customers`
| Kolom          | Tipe            | Keterangan                |
|----------------|-----------------|---------------------------|
| `customers_id` | BIGINT (PK)     | Primary key               |
| `user_id`      | FK → users      | Relasi ke user            |
| `point`        | INTEGER         | Poin pelanggan (default 0)|
| `is_member`    | BOOLEAN         | Status member (via migrasi tambahan) |

#### `vouchers`
| Kolom             | Tipe                     | Keterangan                    |
|-------------------|--------------------------|-------------------------------|
| `id_voucher`      | BIGINT (PK)              | Primary key                   |
| `kode_voucher`    | VARCHAR(50), UNIQUE      | Kode unik voucher             |
| `potongan_persen` | DECIMAL(5,2)             | Persentase diskon             |
| `kuota`           | INTEGER                  | Jumlah pemakaian tersedia     |
| `barang_id`       | FK → barang              | Voucher terikat barang tertentu|
| `tipe_voucher`    | ENUM(langsung, claim)    | Langsung pakai vs harus klaim |
| `expired_at`      | DATETIME                 | Tanggal kadaluarsa            |
| `deleted_at`      | TIMESTAMP                | Soft delete                   |

#### `voucher_claims`
| Kolom        | Tipe            | Keterangan                  |
|--------------|-----------------|-----------------------------|
| `claim_id`   | BIGINT (PK)     | Primary key                 |
| `user_id`    | FK → users      | User yang mengklaim         |
| `id_voucher` | FK → vouchers   | Voucher yang diklaim        |
| `status`     | ENUM            | claimed / used              |
| `claimed_at` | TIMESTAMP       | Waktu klaim                 |
| `used_at`    | TIMESTAMP       | Waktu pemakaian             |

#### `transactions`
| Kolom               | Tipe                                          | Keterangan          |
|----------------------|-----------------------------------------------|---------------------|
| `transaction_id`     | BIGINT (PK)                                    | Primary key         |
| `user_id`            | FK → users                                     | Pembeli             |
| `total_harga`        | DECIMAL(15,2)                                  | Total harga         |
| `status`             | ENUM(berhasil, proses, gagal, refund)          | Status transaksi    |
| `payment_method`     | ENUM(cash, qris, transfer, wallet)             | Metode pembayaran   |
| `alamat_pengiriman`  | VARCHAR                                        | Alamat kirim        |
| `jasa_kurir`         | VARCHAR(50)                                    | Nama kurir          |
| `nomor_resi`         | VARCHAR (nullable)                             | Nomor resi          |
| `status_pengiriman`  | ENUM(pending, shipped, delivered)              | Status pengiriman   |
| `created_at`         | TIMESTAMP                                      | Waktu order         |

#### `transaction_details`
| Kolom          | Tipe            | Keterangan                    |
|----------------|-----------------|-------------------------------|
| `detail_id`    | BIGINT (PK)     | Primary key                   |
| `transaction_id`| FK → transactions | Relasi ke transaksi         |
| `barang_id`    | FK → barang     | Barang yang dibeli            |
| `jumlah`       | INTEGER         | Kuantitas                     |
| `harga_satuan` | DECIMAL(15,2)   | Harga saat pembelian          |
| `id_voucher`   | FK → vouchers   | Voucher yang dipakai (nullable)|

#### `wallet`
| Kolom      | Tipe            | Keterangan        |
|------------|-----------------|--------------------|
| `id_wallet`| BIGINT (PK)     | Primary key        |
| `user_id`  | FK → users      | Pemilik wallet     |
| `saldo`    | DECIMAL         | Saldo wallet       |

#### `wallet_history`
| Kolom             | Tipe            | Keterangan         |
|-------------------|-----------------|--------------------|
| `id_wallet_history`| BIGINT (PK)    | Primary key        |
| `wallet_id`       | FK → wallet     | Relasi ke wallet   |
| `tipe`            | ENUM            | credit / debit     |
| `jumlah`          | DECIMAL         | Nominal            |
| `keterangan`      | TEXT            | Catatan            |

#### `audit`
| Kolom           | Tipe            | Keterangan          |
|-----------------|-----------------|---------------------|
| `id_audit`      | BIGINT (PK)     | Primary key         |
| `transaction_id`| FK → transactions| Transaksi terkait  |
| `user_id`       | FK → users      | Petugas/admin       |
| `action`        | VARCHAR         | Aksi yang dilakukan |
| `created_at`    | TIMESTAMP       | Waktu audit         |

#### `transaction_trackings` (Timeline Status Pengiriman)
| Kolom             | Tipe            | Keterangan                    |
|-------------------|-----------------|-------------------------------|
| `tracking_id`     | BIGINT (PK, AI) | Primary key                   |
| `transaction_id`  | FK → transactions| Relasi ke transaksi           |
| `status_pengiriman`| ENUM            | pending, dikemas, dikirim, selesai|
| `keterangan`      | VARCHAR         | Keterangan spesifik (JNE, dll)|
| `created_at`      | TIMESTAMP       | Waktu perubahan status        |

---

## 🔗 Relasi Antar Model (Eloquent)

```
User
 ├── hasOne → UserProfile
 ├── hasOne → Customer
 ├── hasOne → Wallet
 ├── hasMany → Transaction
 └── hasMany → VoucherClaim

Barang
 ├── belongsTo → Kategori
 ├── hasMany → Merk
 ├── hasMany → Supplier
 ├── hasMany → StokHistory
 ├── hasMany → Voucher
 └── hasMany → TransactionDetail

Kategori
 └── hasMany → Barang

Voucher
 ├── belongsTo → Barang
 ├── hasMany → TransactionDetail
 └── hasMany → VoucherClaim

Transaction
 ├── belongsTo → User
 ├── hasMany → TransactionDetail
 └── hasOne → Audit

TransactionDetail
 ├── belongsTo → Transaction
 ├── belongsTo → Barang
 └── belongsTo → Voucher
```

---

## 🌐 API Endpoints

### Public (Tanpa Auth)

| Method | Endpoint                        | Deskripsi                     |
|--------|----------------------------------|-------------------------------|
| POST   | `/api/register`                  | Registrasi user baru          |
| POST   | `/api/login`                     | Login (username/email + password) |
| POST   | `/api/forgot-password`           | Kirim link reset password via email |
| POST   | `/api/reset-password`            | Reset password dengan token   |

### Authenticated (Bearer Token)

| Method | Endpoint                        | Deskripsi                        |
|--------|----------------------------------|----------------------------------|
| GET    | `/api/auto-login`                | Validasi token & auto login      |
| POST   | `/api/logout`                    | Logout (revoke token)            |
| POST   | `/api/change-password`           | Ubah password                    |
| GET    | `/api/profile`                   | Ambil profil user                |
| PATCH  | `/api/profile`                   | Update profil user               |
| GET    | `/api/dashboard`                 | Dashboard dinamis (sesuai role)  |

### Barang (Read-only untuk authenticated users)

| Method | Endpoint              | Deskripsi                                        |
|--------|-----------------------|--------------------------------------------------|
| GET    | `/api/barang`         | Daftar barang + **Faceted Search** (lihat di bawah)|
| GET    | `/api/barang/{id}`    | Detail barang                                    |

#### Faceted Search Parameters (`GET /api/barang`)

| Parameter   | Tipe     | Deskripsi                              |
|-------------|----------|----------------------------------------|
| `q`         | string   | Full-text search pada nama & deskripsi |
| `harga_min` | numeric  | Filter harga minimum                  |
| `harga_max` | numeric  | Filter harga maksimum                 |
| `in_stock`  | boolean  | `true` = hanya barang yang stoknya > 0|
| `kategori`  | string   | ID kategori, pisahkan koma: `1,2,3`   |
| `per_page`  | integer  | Jumlah item per halaman (default: 15) |
| `page`      | integer  | Nomor halaman                         |

**Response faceted search** menyertakan `facets` (jumlah per kategori + rentang harga) dan `meta` (pagination).

### Kategori (Read-only untuk authenticated users)

| Method | Endpoint               | Deskripsi          |
|--------|-------------------------|--------------------|
| GET    | `/api/kategori`         | Daftar kategori    |
| GET    | `/api/kategori/{id}`    | Detail kategori    |

### Voucher

| Method | Endpoint                    | Deskripsi                          |
|--------|------------------------------|------------------------------------|
| GET    | `/api/voucher`               | Daftar semua voucher               |
| GET    | `/api/voucher/{id}`          | Detail voucher                     |
| GET    | `/api/voucher/check/{kode}`  | Cek validitas kode voucher         |
| POST   | `/api/voucher/claim`         | Klaim voucher (tipe `claim`)       |
| POST   | `/api/voucher/use`           | Gunakan voucher                    |

### Transaksi

| Method | Endpoint                     | Deskripsi                        |
|--------|-------------------------------|----------------------------------|
| POST   | `/api/transaction/checkout`   | Checkout / beli barang           |
| GET    | `/api/transaction/history`    | Riwayat transaksi user           |
| GET    | `/api/transaction/{id}/track` | Tracking status pengiriman       |

### Admin Only (`/api/admin/...`)

| Method | Endpoint                         | Deskripsi                    |
|--------|-----------------------------------|------------------------------|
| POST   | `/api/admin/reset-password`       | Reset password user lain     |
| GET    | `/api/admin/users-legacy`         | Daftar user (username+role)  |
| PATCH  | `/api/admin/users-legacy/{username}` | Update user (role/password)|
| GET    | `/api/admin/users`                | CRUD User (index)            |
| POST   | `/api/admin/users`                | CRUD User (store)            |
| GET    | `/api/admin/users/{id}`           | CRUD User (show)             |
| PUT    | `/api/admin/users/{id}`           | CRUD User (update)           |
| DELETE | `/api/admin/users/{id}`           | CRUD User (destroy)          |
| POST   | `/api/admin/voucher`              | Buat voucher baru            |
| PUT    | `/api/admin/voucher/{id}`         | Update voucher               |
| DELETE | `/api/admin/voucher/{id}`         | Hapus voucher                |
| POST   | `/api/admin/kategori`             | Buat kategori baru           |
| PUT    | `/api/admin/kategori/{id}`        | Update kategori              |
| DELETE | `/api/admin/kategori/{id}`        | Hapus kategori               |
| POST   | `/api/admin/barang`               | Buat barang baru             |
| PUT    | `/api/admin/barang/{id}`          | Update barang                |
| DELETE | `/api/admin/barang/{id}`          | Hapus barang                 |

### Machine Learning & Analitik (Integrasi FastAPI)

| Method | Endpoint                         | Deskripsi                    | Middleware |
|--------|-----------------------------------|------------------------------|------------|
| GET    | `/api/produk/laris`               | Estimasi produk terlaris     | `auth`     |
| GET    | `/api/rekomendasi`                | Rekomendasi personal (CF)    | `auth`     |
| GET    | `/api/admin/stok/prediksi`        | Prediksi stok & Reorder Point| `admin`    |
| GET    | `/api/admin/stok/alert`           | Peringatan stok kritis       | `admin`    |
| POST   | `/api/admin/stok/safety/{id}`     | Detail kalkulasi safety stock| `admin`    |

*Catatan: Deteksi fraud dieksekusi secara otomatis di background pada saat `POST /api/transaction/checkout`. Jika terdeteksi fraud, transaksi akan langsung ditolak (status 400).*

---

## ✨ Fitur-Fitur Utama

### 1. Autentikasi & Otorisasi
- **Register** dengan pembuatan user + profile secara transaksional (DB Transaction)
- **Login** fleksibel: bisa menggunakan username ATAU email
- **Auto Login** untuk validasi token (session check dari frontend)
- **Change Password** untuk user yang sedang login
- **Forgot Password** via email link
- **Reset Password** via token dari email
- **Admin Reset Password** untuk reset manual user lain oleh admin
- **Role-Based Middleware** (`CheckRole`) — mendukung multi-role per route

### 2. Manajemen User (Admin)
- CRUD user lengkap via `apiResource`
- Pembuatan user otomatis membuat profil dan customer record (jika role customer)
- Soft delete pada user

### 3. Manajemen Barang
- CRUD barang dengan relasi ke kategori
- Soft delete pada barang
- **Faceted Search** yang dioptimasi untuk MySQL:
  - Full-text search menggunakan `MATCH() AGAINST()` (bukan `LIKE '%...%'`)
  - Filter harga (min/max), stok, kategori
  - Response menyertakan **facets** (jumlah per kategori + rentang harga)
  - Pagination bawaan

### 4. Manajemen Kategori
- CRUD kategori dengan proteksi delete (tidak bisa dihapus jika masih ada barang terkait)

### 5. Sistem Voucher
- **Dua tipe voucher**:
  - `langsung` — langsung bisa dipakai saat checkout
  - `claim` — harus diklaim dulu oleh user, baru bisa digunakan
- Cek validitas voucher (expired, kuota habis)
- Claim voucher (satu user satu klaim per voucher)
- Use voucher (dengan `lockForUpdate()` untuk mencegah race condition)
- Voucher terikat ke barang tertentu
- Soft delete pada voucher

### 6. Checkout & Transaksi
- Checkout multi-barang dalam satu transaksi
- Penerapan diskon barang otomatis
- Penerapan voucher per item barang
- Pengurangan stok dengan `lockForUpdate()` (anti race condition)
- Validasi member otomatis jika total transaksi ≥ Rp100.000
- Metode pembayaran: cash, QRIS, transfer, wallet
- Tracking pengiriman (status + nomor resi)
- Riwayat transaksi per user

### 7. Real-time Broadcasting (Laravel Reverb)
- Event `DataUpdated` yang broadcast ke channel publik `tugas-channel`
- Menggunakan `ShouldBroadcastNow` (tanpa antrian, langsung broadcast)
- Frontend bisa listen via Laravel Echo + Pusher JS

### 8. Email Queue
- `SendEmailJob` untuk pengiriman email via queue (async)
- `SendEmailMail` Mailable class dengan template Blade

### 9. Idempotency & Concurrency Control (High Performance Layer)
- **Idempotency Key Validation**: Mencegah duplikasi transaksi akibat *double-click* atau *request* berulang dari frontend dengan validasi `X-Idempotency-Key` (UUID) berbasis **Redis Atomic Lock**. Request kembar langsung ditolak di layer awal (`409 Conflict`) tanpa membebani CPU.
- **Double-Lock Protection**: Kombinasi `Cache::lock()` pada Redis (untuk request gateway) dan `lockForUpdate()` pada MySQL (untuk data transaksional stok `barang` dan saldo `wallet`) guna menjamin konsistensi data 100% dan anti *race condition*.
- **Native phpredis Integration**: Menggunakan C extension (`phpredis`) sebagai driver native yang dioptimasi untuk kecepatan tinggi dan efisiensi RAM/CPU yang jauh lebih baik dibanding package php biasa.
- **Persistent Connection & Reuse**: Menggunakan konfigurasi `persistent` dan `persistent_id` khusus pada driver `phpredis` di ekosistem Laravel Octane guna mencegah masalah *connection bleeding*, *socket error*, atau *idle timeout* saat *production*.
- **Pure Redis Queue Driver**: Migrasi penuh dari database driver ke **Redis Queue** via `phpredis` untuk memproses job berat secara asinkronus (seperti `SendEmailJob`) dengan latensi milidetik, membebaskan I/O database MySQL utama.
- **Hybrid Caching Architecture**: 
  - *Octane Cache Store (In-Memory Internal)*: Digunakan khusus untuk asset statis (seperti daftar kategori dan config) demi mencapai *zero network latency*.
  - *Redis Cache Store (Global Memory)*: Digunakan untuk data transaksional dinamis, session, dan *idempotency key* agar data tetap sinkron secara global.
### 10. Integrasi Midtrans & Wallet-only Checkout
- Top-up saldo Wallet difasilitasi oleh Snap API Midtrans.
- Otomatisasi penambahan saldo via notifikasi Webhook Midtrans.
- Sistem Checkout eksklusif via dompet digital (Wallet) untuk menyederhanakan alur tugas.

### 11. Pembaruan Fitur Manager & Supplier
- **Manager Dashboard**: Menampilkan metrik operasional yang komprehensif, performa penjualan barang terperinci, dan status pengiriman secara *real-time*. Mendukung *export* laporan ke PDF melalui CSS `@media print`.
- **Supplier Dashboard**: Desain UI tanpa tab (*tab-less*) untuk efisiensi navigasi. Menggunakan pola **Update Or Create** pada manajemen data pasokan agar riwayat suplai barang dari pemasok yang sama dapat terakumulasi dengan akurat tanpa redundansi data *insert*.
---

## 📁 Struktur Project

```
koperasi6G/
├── app/
│   ├── Events/
│   │   └── DataUpdated.php             # Broadcasting event (Reverb)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Auth: login, register, password, profile
│   │   │   ├── BarangController.php    # CRUD Barang + Faceted Search
│   │   │   ├── KategoriController.php  # CRUD Kategori
│   │   │   ├── TransactionController.php # Checkout, History, Tracking
│   │   │   ├── UserController.php      # CRUD User (Admin)
│   │   │   └── VoucherController.php   # CRUD + Claim + Use Voucher
│   │   ├── Middleware/
│   │   │   └── CheckRole.php           # Role-based access control
│   │   └── Requests/
│   │       ├── ClaimVoucherRequest.php
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── StoreVoucherRequest.php
│   │       ├── UpdateVoucherRequest.php
│   │       └── UseVoucherRequest.php
│   ├── Jobs/
│   │   └── SendEmailJob.php            # Async email via queue
│   ├── Mail/
│   │   └── SendEmailMail.php           # Mailable class
│   ├── Models/
│   │   ├── Audit.php
│   │   ├── Barang.php
│   │   ├── Customer.php
│   │   ├── Kategori.php
│   │   ├── Merk.php
│   │   ├── StokHistory.php
│   │   ├── Supplier.php
│   │   ├── Transaction.php
│   │   ├── TransactionDetail.php
│   │   ├── User.php
│   │   ├── UserProfile.php
│   │   ├── Voucher.php
│   │   ├── VoucherClaim.php
│   │   ├── Wallet.php
│   │   └── WalletHistory.php
│   ├── Providers/
│   └── Traits/
│       └── ApiResponse.php             # Standardized JSON response trait
├── config/
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_07_01_045456_create_users_profiles.php
│   │   ├── 2026_07_03_044405_create_kategori_table.php
│   │   ├── 2026_07_03_044405_z_create_barang_table.php
│   │   ├── 2026_07_03_044406_create_merk_table.php
│   │   ├── 2026_07_03_044407_create_supplier_table.php
│   │   ├── 2026_07_03_044409_create_stok_history_table.php
│   │   ├── 2026_07_03_044410_create_customers_table.php
│   │   ├── 2026_07_03_044411_create_vouchers_table.php
│   │   ├── 2026_07_03_044412_create_voucher_claims_table.php
│   │   ├── 2026_07_03_044413_create_transactions_table.php
│   │   ├── 2026_07_03_044414_create_transaction_details_table.php
│   │   ├── 2026_07_03_044416_create_audit_table.php
│   │   ├── 2026_07_03_044417_create_wallet_table.php
│   │   ├── 2026_07_03_044418_create_wallet_history_table.php
│   │   ├── 2026_07_04_003646_create_personal_access_tokens_table.php
│   │   ├── 2026_07_04_081842_add_deleted_at_to_vouchers_table.php
│   │   ├── 2026_07_04_093540_add_shipping_columns_to_transactions_table.php
│   │   └── 2026_07_04_154709_add_member_status_to_customers_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   ├── api.php                         # Semua API endpoints
│   ├── channels.php                    # Broadcasting channel authorization
│   ├── console.php                     # Artisan console commands
│   └── web.php                         # Web routes (test login, dashboard)
├── .env                                # Environment configuration
├── composer.json                       # PHP dependencies
├── package.json                        # Node.js dependencies
├── vite.config.js                      # Vite build configuration
└── tugas-pak-indra.sql                 # SQL dump awal
```

---

## ⚡ Cara Menjalankan

```bash
# 1. Install dependencies
composer install
npm install

# 2. Copy env dan generate key
cp .env.example .env
php artisan key:generate

# 3. Setup database (pastikan MySQL sudah jalan)
php artisan migrate --seed

# 4. Jalankan server development
composer dev
# Perintah di atas menjalankan secara bersamaan:
#   - php artisan serve (API server)
#   - php artisan queue:listen (Queue worker)
#   - npm run dev (Vite dev server)

# 5. (Opsional) Jalankan Reverb WebSocket server
php artisan reverb:start
```

---

## 🔒 Catatan Keamanan

- Password di-hash menggunakan `bcrypt` via Laravel `Hash` facade
- Token auth menggunakan Sanctum (Personal Access Token)
- Semua endpoint sensitif dilindungi middleware `auth:sanctum` + `role:xxx`
- Race condition pada checkout & voucher dicegah dengan `lockForUpdate()` + DB Transaction
- Input divalidasi menggunakan Laravel Validator atau Form Request
- Soft delete mencegah penghapusan data permanen

---

## 📝 Catatan Pengembangan

- Wallet integration pada checkout masih `TODO`
- Dashboard admin/staff/supplier/manager masih menggunakan data dummy
- Fitur keranjang (cart) belum diimplementasi (saat ini langsung checkout)
- Email template berada di `resources/views/emails/send_email.blade.php`
