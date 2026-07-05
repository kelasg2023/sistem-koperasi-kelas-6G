# Gemini Project Guide

## Ringkasan
Project ini adalah backend **FastAPI** untuk sistem koperasi berbasis machine learning. Aplikasi berjalan pada port **5610** dan menyediakan API untuk health check, prediksi umum, daftar model, deteksi fraud, rekomendasi produk, dan prediksi stok. Service ini terhubung ke database MySQL yang sama dengan backend Laravel (`koperasi6G`) dan membaca data transaksi secara real-time untuk keperluan analitik ML.

---

## Stack Utama
- **FastAPI** untuk web API
- **Pydantic / pydantic-settings** untuk konfigurasi dan schema
- **SQLAlchemy** untuk akses database (read-only dari DB Laravel)
- **Pandas, NumPy, scikit-learn** untuk pemrosesan data dan ML
- **Uvicorn** sebagai ASGI server
- **PyMySQL** sebagai MySQL driver
- **Pytest** untuk pengujian

---

## Struktur Project
```
python-starter/
├── main.py                         # Entry point aplikasi FastAPI (port 5610)
├── db.sql                          # Referensi schema MySQL (sync dengan migrations Laravel)
├── requirements.txt
├── .env                            # Konfigurasi DATABASE_URL dan lainnya
├── app/
│   ├── api/v1/
│   │   ├── router.py               # Menggabungkan semua endpoint router
│   │   └── endpoints/
│   │       ├── health.py           # GET /api/v1/health
│   │       ├── predict.py          # POST /api/v1/predict/
│   │       ├── models.py           # GET /api/v1/models/, GET /api/v1/models/{name}
│   │       ├── fraud.py            # POST /api/v1/fraud/check
│   │       ├── recommendation.py   # GET /api/v1/produk/laris, POST /api/v1/rekomendasi/anggota/{id}
│   │       └── stock.py            # GET /api/v1/stok/prediksi, /stok/alert, POST /stok/safety/{id}
│   ├── core/
│   │   ├── config.py               # Settings via pydantic-settings (baca dari .env)
│   │   └── database.py             # SQLAlchemy engine + get_db dependency
│   └── services/
│       └── model_service.py        # Load dan infer model ML dari models/saved/
├── models/saved/                   # Artefak model ML (.joblib)
│   └── model_fraud.joblib          # Model Isolation Forest untuk deteksi fraud
├── scripts/
│   ├── populate_mysql.py           # Seed data simulasi ke MySQL (jalankan sekali)
│   ├── generate_fraud_data.py      # Generate CSV data fraud untuk training
│   ├── generate_recommendation_data.py # Generate CSV data rekomendasi
│   ├── train.py                    # Training model fraud (Isolation Forest)
│   └── evaluate.py                 # Evaluasi model
├── data/
│   └── raw/                        # File CSV hasil generate (tidak di-commit)
├── notebooks/                      # Jupyter notebooks untuk eksplorasi
└── tests/                          # Pengujian API dengan pytest + httpx
```

---

## Cara Menjalankan
```bash
pip install -r requirements.txt
python main.py
```

Alternatif:
```bash
uvicorn main:app --host 0.0.0.0 --port 5610 --reload
```

---

## Konfigurasi Database (`.env`)
Service ini **membaca** database MySQL yang sama dengan Laravel. Pastikan `.env` dikonfigurasi ke database yang benar:

```env
DATABASE_URL=mysql://root:@localhost:3306/db_koperasi
# atau
DATABASE_URL=mysql+pymysql://root:@localhost:3306/db_koperasi
```

> Driver `mysql://` akan otomatis diganti ke `mysql+pymysql://` oleh `database.py`.

---

## Endpoint Penting

| Method | Path | Deskripsi |
|--------|------|-----------|
| GET | `/` | Root info |
| GET | `/health` | Health check lokal |
| GET | `/api/v1/health` | Health check API |
| POST | `/api/v1/predict/` | Prediksi ML umum |
| GET | `/api/v1/models/` | Daftar model tersedia |
| GET | `/api/v1/models/{name}` | Detail model |
| POST | `/api/v1/fraud/check` | Deteksi fraud transaksi |
| GET | `/api/v1/produk/laris` | Estimasi produk terlaris (query DB) |
| POST | `/api/v1/rekomendasi/anggota/{anggota_id}` | Rekomendasi produk personal (Cosine Similarity) |
| GET | `/api/v1/stok/prediksi` | Prediksi stok habis + Safety Stock (Linear Regression) |
| GET | `/api/v1/stok/alert` | Daftar barang kritis / perlu dipesan |
| POST | `/api/v1/stok/safety/{produk_id}` | Detail kalkulasi safety stock per produk |

---

## Schema Database (Disesuaikan dengan Laravel Migrations)

Service ini membaca tabel-tabel berikut dari MySQL. Struktur ini **harus cocok** dengan migration di `koperasi6G`:

### Tabel yang Diakses oleh Python Service

#### `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_users` | BIGINT UNSIGNED PK AI | Primary key |
| `username` | VARCHAR(255) | Username unik |
| `email` | VARCHAR(255) UNIQUE | Email (wajib) |
| `password` | VARCHAR(255) | Hashed |
| `role` | ENUM(admin, staff, supplier, manager, **customer**) | Role |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |
| `deleted_at` | TIMESTAMP | Soft delete |

#### `kategori`
| Kolom | Tipe |
|-------|------|
| `id_kategori` | BIGINT UNSIGNED PK AI |
| `nama_kategori` | VARCHAR(50) |
| `satuan` | VARCHAR(10) |

#### `barang`
| Kolom | Tipe |
|-------|------|
| `barang_id` | BIGINT UNSIGNED PK AI |
| `nama` | VARCHAR(255) |
| `stok` | INT DEFAULT 0 |
| `harga` | DECIMAL(15,2) |
| `diskon_persen` | DECIMAL(5,2) DEFAULT 0.00 |
| `deskripsi` | TEXT nullable |
| `id_kategori` | FK → kategori |
| `deleted_at` | TIMESTAMP nullable |

#### `transactions`
| Kolom | Tipe |
|-------|------|
| `transaction_id` | BIGINT UNSIGNED PK AI |
| `user_id` | FK → users |
| `total_harga` | DECIMAL(15,2) |
| `status` | ENUM(berhasil, proses, gagal, refund) |
| `payment_method` | ENUM(cash, qris, transfer, wallet) |
| `created_at` | TIMESTAMP |
| `alamat_pengiriman` | TEXT nullable |
| `jasa_kurir` | VARCHAR nullable |
| `nomor_resi` | VARCHAR nullable |
| `status_pengiriman` | ENUM(pending, dikemas, dikirim, selesai) |

#### `transaction_details`
| Kolom | Tipe |
|-------|------|
| `detail_id` | BIGINT UNSIGNED PK AI |
| `transaction_id` | FK → transactions |
| `barang_id` | FK → barang |
| `jumlah` | INT |
| `harga_satuan` | DECIMAL(15,2) |
| `id_voucher` | FK → vouchers nullable |

#### `vouchers`
| Kolom | Tipe |
|-------|------|
| `id_voucher` | BIGINT UNSIGNED PK AI |
| `kode_voucher` | VARCHAR(50) UNIQUE |
| `potongan_persen` | DECIMAL(5,2) |
| `kuota` | INT |
| `barang_id` | FK → barang |
| `tipe_voucher` | ENUM(langsung, claim) |
| `expired_at` | DATETIME |
| `created_at` | TIMESTAMP |
| `deleted_at` | TIMESTAMP nullable |

#### `voucher_claims`
| Kolom | Tipe |
|-------|------|
| `claim_id` | BIGINT UNSIGNED PK AI |
| `user_id` | FK → users |
| `id_voucher` | FK → vouchers |
| `status` | ENUM(claimed, used, expired) |
| `claimed_at` | TIMESTAMP |
| `used_at` | TIMESTAMP nullable |

#### `customers`
| Kolom | Tipe |
|-------|------|
| `customers_id` | BIGINT UNSIGNED PK AI |
| `user_id` | FK → users |
| `point` | INT DEFAULT 0 |
| `is_member` | BOOLEAN DEFAULT 0 |

#### `wallet`
| Kolom | Tipe |
|-------|------|
| `id_wallet` | BIGINT UNSIGNED PK AI |
| `user_id` | FK → users |
| `balance` | DECIMAL(15,2) DEFAULT 0.00 |

#### `wallet_history`
| Kolom | Tipe |
|-------|------|
| `id_wt_history` | BIGINT UNSIGNED PK AI |
| `id_wallet` | FK → wallet |
| `balance_transaction` | DECIMAL(15,2) |
| `wt_status_history` | ENUM(penambahan, pengembalian, terpakai) |
| `created_at` | TIMESTAMP |

#### `wallet_topups`
| Kolom | Tipe |
|-------|------|
| `id` | BIGINT UNSIGNED PK AI |
| `user_id` | FK → users |
| `order_id` | VARCHAR UNIQUE |
| `gross_amount` | DECIMAL(15,2) |
| `status` | ENUM(pending, success, failed, expired) |
| `snap_token` | VARCHAR nullable |
| `created_at` / `updated_at` | TIMESTAMP |

#### `supplier`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `supplier_id` | BIGINT UNSIGNED PK AI | — |
| `merk_id` | FK → merk | Relasi ke merk barang |
| `barang_id` | FK → barang | Relasi ke barang |
| `harga_beli` | DECIMAL(15,2) | Harga beli dari supplier |
| `jumlah` | INT | Jumlah order |
| `status` | BOOLEAN DEFAULT 1 | Aktif/nonaktif |

#### `stok_history`
| Kolom | Tipe |
|-------|------|
| `stok_history_id` | BIGINT UNSIGNED PK AI |
| `supplier_id` | FK → supplier nullable |
| `barang_id` | FK → barang |
| `jumlah` | INT |
| `stok_awal` | INT |
| `stok_akhir` | INT |
| `keterangan` | VARCHAR nullable |
| `stok_mutasi` | ENUM(keluar, lainnya, masuk) |
| `created_at` | TIMESTAMP |

---

## Pola Implementasi
- Endpoint memakai router FastAPI per fitur di `app/api/v1/endpoints/`.
- Fitur yang butuh DB menggunakan `Depends(get_db)` (SQLAlchemy session).
- SQL ditulis sebagai raw query dengan `text()` dari SQLAlchemy.
- Fraud check memakai model tersimpan melalui `model_service` (singleton).
- Rekomendasi dan stok dihitung real-time dari data transaksi historis.

---

## Workflow Seed Data (Pengembangan Lokal)
Jika database kosong dan ingin menguji endpoint ML:

```bash
# 1. Generate data CSV simulasi
python scripts/generate_fraud_data.py
python scripts/generate_recommendation_data.py

# 2. Populate ke MySQL
python scripts/populate_mysql.py

# 3. Training model fraud
python scripts/train.py

# 4. Jalankan server
python main.py
```

---

## Panduan Untuk Perubahan
- Pertahankan struktur router per fitur di `app/api/v1/endpoints/`.
- Jangan ubah path API yang sudah ada tanpa alasan yang jelas.
- Gunakan schema Pydantic untuk request dan response.
- Jika menambah logika database, gunakan session dependency `Depends(get_db)`.
- Jika menambah model ML, simpan artefak di `models/saved/` dan update `model_service` bila perlu.
- Jika menambah script data atau training, taruh di `scripts/`.
- Jika menambah pengujian, update atau tambah file di `tests/`.
- **Jangan mengubah struktur tabel** — schema selalu mengikuti migration Laravel di `koperasi6G`.

---

## Konteks Implementasi Domain
Project ini berfokus pada tiga area utama:
- **Estimasi produk laris & rekomendasi anggota** — Collaborative Filtering (Cosine Similarity) real-time
- **Prediksi stok aman & alert reorder** — Linear Regression + formula Safety Stock
- **Deteksi anomali / fraud transaksi** — Isolation Forest (model disimpan di `models/saved/model_fraud.joblib`)

Pertahankan output yang mudah dipakai oleh frontend atau sistem lain: response yang konsisten, field yang eksplisit, dan pesan error yang jelas.