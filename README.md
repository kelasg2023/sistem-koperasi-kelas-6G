# ML FastAPI Project — Sistem Koperasi Kelas 6G

Machine Learning project dengan FastAPI backend, berjalan di port **5610**.

---

## 📋 Product Requirements Document (PRD)

> Berikut adalah **3 fitur utama** yang harus diimplementasikan oleh tim sebagai bagian dari sistem koperasi berbasis Machine Learning.

---

### 🏆 Fitur 1 — Estimasi / Rekomendasi Produk Laris

**Deskripsi:**  
Sistem memprediksi produk mana yang akan paling laris dalam periode tertentu berdasarkan histori transaksi koperasi, sehingga pengurus dapat menyiapkan stok secara proaktif dan membuat rekomendasi pembelian kepada anggota.

**Tujuan:**
- Meningkatkan penjualan dengan merekomendasikan produk relevan ke anggota
- Membantu pengurus dalam pengambilan keputusan pengadaan barang

**Input yang Dibutuhkan:**
- Data histori transaksi (tanggal, produk, jumlah, anggota)
- Data kategori produk
- Data musiman / periode (hari libur, awal bulan, dll)

**Output yang Diharapkan:**
- Top-N produk yang diprediksi akan laris minggu/bulan ini
- Skor popularitas per produk
- Rekomendasi produk personal per anggota (opsional)

**Teknik ML yang Disarankan:**
- Collaborative Filtering / Content-Based Filtering (untuk rekomendasi)
- Time Series Forecasting (ARIMA, Prophet, LSTM) untuk estimasi volume

**Endpoint API:**
```
GET  /api/v1/produk/laris?periode=7d
POST /api/v1/rekomendasi/anggota/{anggota_id}
```

---

### 📦 Fitur 2 — Otomatisasi Prediksi Stok Aman

**Deskripsi:**  
Sistem secara otomatis memprediksi kapan stok suatu produk akan habis dan menentukan **jumlah stok aman** yang harus dipertahankan, serta memberikan notifikasi/peringatan ketika stok mendekati batas minimum.

**Tujuan:**
- Mencegah kehabisan stok (stockout) yang merugikan anggota
- Menghindari penumpukan stok berlebih (overstock)
- Mengotomatiskan proses pengadaan barang

**Input yang Dibutuhkan:**
- Data stok saat ini per produk
- Data histori penjualan per produk
- Data lead time pemesanan (lama waktu pengadaan)
- Data demand variability (fluktuasi permintaan)

**Output yang Diharapkan:**
- Prediksi tanggal habis stok per produk
- Reorder point (titik pemesanan ulang) yang direkomendasikan
- Jumlah safety stock yang optimal
- Alert/notifikasi produk yang perlu segera dipesan

**Formula Referensi:**
```
Safety Stock = Z × σ_demand × √Lead Time
Reorder Point = (Rata-rata permintaan harian × Lead time) + Safety Stock
```

**Teknik ML yang Disarankan:**
- Regression (prediksi demand harian)
- Time Series (demand forecasting)
- Threshold-based alerting

**Endpoint API:**
```
GET  /api/v1/stok/prediksi
GET  /api/v1/stok/alert
POST /api/v1/stok/safety/{produk_id}
```

---

### 🔍 Fitur 3 — Deteksi Anomali / Fraud Log Transaksi

**Deskripsi:**  
Sistem menganalisis setiap log transaksi koperasi untuk mendeteksi pola yang mencurigakan atau tidak wajar — seperti transaksi duplikat, pembelian di luar jam operasional, nilai transaksi yang jauh di atas rata-rata, atau pola penipuan lainnya.

**Tujuan:**
- Melindungi keuangan koperasi dari kecurangan (fraud)
- Mendeteksi kesalahan input data secara otomatis
- Memberikan laporan audit yang bisa dipertanggungjawabkan

**Input yang Dibutuhkan:**
- Log transaksi (timestamp, anggota_id, produk, jumlah, nilai, kasir)
- Histori transaksi normal sebagai baseline
- Parameter threshold anomali (bisa dikonfigurasi)

**Output yang Diharapkan:**
- Flag setiap transaksi: `NORMAL` / `SUSPICIOUS` / `FRAUD`
- Skor anomali (0.0 – 1.0) per transaksi
- Laporan ringkasan anomali harian/mingguan
- Detail alasan kenapa transaksi dianggap mencurigakan

**Jenis Anomali yang Dideteksi:**
| Jenis | Contoh |
|-------|--------|
| Nilai ekstrem | Transaksi > 3× rata-rata normal |
| Duplikasi | Transaksi sama dalam < 1 menit |
| Waktu mencurigakan | Transaksi di luar jam 07.00–17.00 |
| Frekuensi tinggi | > 20 transaksi dalam 1 jam oleh 1 anggota |
| Produk tidak wajar | Pembelian produk yang tidak pernah dibeli sebelumnya |

**Teknik ML yang Disarankan:**
- Isolation Forest
- Local Outlier Factor (LOF)
- Autoencoder (deep learning)
- Rule-based + ML hybrid

**Endpoint API:**
```
POST /api/v1/fraud/check              # Cek 1 transaksi
GET  /api/v1/fraud/laporan?tanggal=today
GET  /api/v1/fraud/log?status=SUSPICIOUS
```

---

## 📌 Pembagian Tugas Tim

| Fitur | Penanggung Jawab | Deadline |
|-------|-----------------|----------|
| Rekomendasi Produk Laris | _(isi nama)_ | _(isi tanggal)_ |
| Prediksi Stok Aman | _(isi nama)_ | _(isi tanggal)_ |
| Deteksi Fraud Transaksi | _(isi nama)_ | _(isi tanggal)_ |

---


## 📁 Struktur Folder

```
python-starter/
├── main.py                     # Entry point FastAPI (port 5610)
├── requirements.txt            # Semua dependency
├── .env.example                # Template environment variables
├── .gitignore
│
├── app/
│   ├── api/
│   │   └── v1/
│   │       ├── router.py           # API router utama
│   │       └── endpoints/
│   │           ├── health.py       # GET /api/v1/health
│   │           ├── predict.py      # POST /api/v1/predict
│   │           └── models.py       # GET /api/v1/models
│   ├── core/
│   │   └── config.py           # Konfigurasi (port, env vars, dll)
│   ├── services/
│   │   └── model_service.py    # Load & run ML model
│   └── utils/
│       └── preprocessing.py    # Utilitas preprocessing data
│
├── notebooks/                  # Jupyter notebooks eksplorasi
│   └── 01_eda.ipynb
│
├── data/
│   ├── raw/                    # Data mentah (gitignored)
│   ├── processed/              # Data bersih (gitignored)
│   └── db/                     # SQLite database
│
├── models/
│   ├── saved/                  # Model tersimpan (.joblib, .pkl, dll)
│   └── experiments/            # Hasil eksperimen MLflow
│
├── scripts/
│   ├── train.py                # Script pelatihan model
│   └── evaluate.py             # Script evaluasi model
│
├── tests/
│   └── test_api.py             # Unit tests endpoint
│
└── logs/                       # Log aplikasi
```

## 🚀 Quick Start

### 1. Buat virtual environment
```bash
python -m venv venv
venv\Scripts\activate          # Windows
source venv/bin/activate       # Linux/Mac
```

### 2. Install dependencies
```bash
pip install -r requirements.txt
```

### 3. Copy environment variables
```bash
copy .env.example .env
```

### 4. Jalankan server
```bash
python main.py
# atau
uvicorn main:app --host 0.0.0.0 --port 5610 --reload
```

### 5. Buka di browser
- **API Docs (Swagger)**: http://localhost:5610/docs
- **ReDoc**: http://localhost:5610/redoc
- **Health Check**: http://localhost:5610/health

## 🤖 Training Model

```bash
python scripts/train.py     # Train model
python scripts/evaluate.py  # Evaluasi model
```

## 🧪 Testing

```bash
pytest tests/
```

## 📡 Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/` | Root info |
| GET | `/health` | Health check |
| GET | `/api/v1/health` | API health |
| POST | `/api/v1/predict/` | Prediksi ML |
| GET | `/api/v1/models/` | List model |
| GET | `/api/v1/models/{name}` | Info model |
