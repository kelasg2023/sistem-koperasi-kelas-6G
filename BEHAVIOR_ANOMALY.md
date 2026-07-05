# 🔍 Behavior Anomaly Detection - Perubahan Perilaku Drastis

**Fitur AI untuk mendeteksi "Perubahan Perilaku Drastis" pada user**

## 📋 Konsep Bisnis

Di dunia Machine Learning Anomaly Detection, sistem ini tidak melihat apakah produk pernah dibeli atau tidak, melainkan melihat **seberapa jauh profil transaksi menyimpang dari behavior harian user** (User Profiling).

### 🎯 Contoh Real:

- **Biasanya:** Rizki cuma beli jajan seribuan
- **Anomali:** Tiba-tiba dalam satu transaksi dia beli 50 buah seragam olahraga
- **Lompatan:** Volume/kategori yang drastis ini baru layak disebut anomali

### 📊 Use Cases:

1. **Fraud Detection:** Deteksi perubahan pola belanja yang mencurigakan
2. **Risk Management:** Identifikasi transaksi yang memerlukan review
3. **Customer Insights:** Pahami perubahan behavior customer
4. **Inventory Alert:** Persiapan stok untuk demand spike yang tidak terduga

---

## 🛠️ Setup & Installation

### 1. Generate Training Data

```bash
python scripts/generate_behavior_anomaly_data.py
```

Output: `data/raw/behavior_anomaly_data.csv` (2000+ transaksi)

Fitur data:

- user_id, category_id, volume, total_price
- transaction_hour, transaction_date
- is_anomaly (label untuk training)

### 2. Train Model

```bash
python scripts/train_behavior_anomaly.py
```

Output:

- `models/saved/model_behavior_anomaly.joblib` (Isolation Forest)
- `models/saved/scaler_behavior_anomaly.joblib` (StandardScaler)

Model Performance:

- Menggunakan **Isolation Forest** untuk mendeteksi anomali
- Contamination rate disesuaikan dengan data training
- Feature engineering berbasis user profile

### 3. Mulai API Server

```bash
python main.py
```

Server berjalan di: `http://localhost:5610`

---

## 📡 API Endpoints

### POST `/api/v1/behavior-anomaly/check`

**Deteksi anomali pada transaksi user**

#### Request Body:

```json
{
  "user_id": 1,
  "category_id": 2,
  "volume": 50,
  "total_price": 2500000,
  "transaction_hour": 14,
  "transaction_date": "2025-01-05",
  "transaction_id": 12345
}
```

#### Parameter:

- `user_id` _(int, required)_: ID user dari tabel users
- `category_id` _(int, required)_: ID kategori dari tabel kategori
- `volume` _(int, required)_: Jumlah item yang dibeli
- `total_price` _(float, required)_: Total harga dalam Rp
- `transaction_hour` _(int, default: 12)_: Jam transaksi (0-23)
- `transaction_date` _(str, default: 2025-01-01)_: Tanggal transaksi (YYYY-MM-DD)
- `transaction_id` _(int, optional)_: ID transaksi (untuk audit trail)

#### Response Success (200):

```json
{
  "transaction_id": 12345,
  "user_id": 1,
  "category_id": 2,
  "status": "anomaly",
  "anomaly_score": 0.92,
  "confidence": 0.87,
  "reason": "Terdeteksi PERUBAHAN PERILAKU DRASTIS pada pola pembelian. Indikator: Volume pembelian melonjak: 50 item vs rata-rata 2.0 item",
  "user_profile": {
    "avg_total_price": 50000,
    "std_total_price": 20000,
    "avg_volume": 2,
    "std_volume": 1,
    "primary_category_id": null,
    "category_frequency": {},
    "transaction_count": 0
  },
  "suspicious_indicators": [
    "Volume pembelian melonjak: 50 item vs rata-rata 2.0 item"
  ]
}
```

#### Response Error:

```json
{
  "detail": "user_id harus positif"
}
```

---

## 🧠 Model Architecture

### Feature Engineering

Model menggunakan **8 fitur utama** yang di-engineer dari user behavior:

| No  | Feature             | Penjelasan                              | Range |
| --- | ------------------- | --------------------------------------- | ----- |
| 0   | `norm_price`        | Z-score harga dibanding rata-rata user  | 0-∞   |
| 1   | `norm_volume`       | Z-score volume dibanding rata-rata user | 0-∞   |
| 2   | `cat_freq`          | Frekuensi kategori ini dibeli user      | 0-1   |
| 3   | `time_score`        | Suspicious hour (0-5, 22-23)            | 0-1   |
| 4   | `log_price_per_vol` | Log transformasi harga/unit             | ℝ     |
| 5   | `category_shift`    | Apakah kategori berbeda dari usual      | 0-1   |
| 6   | `volume_spike`      | Normalized volume spike                 | 0-∞   |
| 7   | `price_spike`       | Normalized price spike                  | 0-∞   |

### Algorithm: Isolation Forest

**Mengapa Isolation Forest?**

- ✅ Unsupervised learning (cocok untuk anomaly detection)
- ✅ Efisien untuk high-dimensional data
- ✅ Robust terhadap outlier
- ✅ Tidak memerlukan threshold manual yang ketat
- ✅ Fast inference (cocok untuk real-time API)

**Hyperparameters:**

- `contamination`: Estimated proportion of outliers (auto-calculated)
- `n_estimators`: 100 trees
- `max_samples`: 'auto'
- `random_state`: 42 (reproducibility)

### Output Interpretation

| Status  | Anomaly Score | Arti                                        |
| ------- | ------------- | ------------------------------------------- |
| normal  | 0.0 - 0.3     | Transaksi normal, tidak ada anomali         |
| normal  | 0.3 - 0.5     | Transaksi sedikit unusual tapi masih wajar  |
| anomaly | 0.5 - 0.7     | Transaksi mencurigakan, perlu review        |
| anomaly | 0.7 - 1.0     | Transaksi sangat anomali, highly suspicious |

---

## 🗄️ Database Mapping

Model ini menggunakan tabel-tabel dari `db.sql`:

### Tabel yang Digunakan:

**1. `users`**

```sql
- id_users (PK)
- username
- email
- role
```

**2. `transactions`**

```sql
- transaction_id (PK)
- user_id (FK -> users)
- total_harga
- status (berhasil/proses/gagal/refund)
- created_at
```

**3. `transaction_details`**

```sql
- detail_id (PK)
- transaction_id (FK -> transactions)
- barang_id (FK -> barang)
- jumlah (volume)
- harga_satuan
```

**4. `barang`**

```sql
- barang_id (PK)
- nama
- harga
- id_kategori (FK -> kategori)
```

**5. `kategori`**

```sql
- id_kategori (PK)
- nama_kategori
- satuan
```

### Query untuk User Profile (Future Enhancement):

```sql
SELECT
  t.user_id,
  b.id_kategori,
  AVG(t.total_harga) as avg_total_price,
  STDDEV(t.total_harga) as std_total_price,
  AVG(SUM(td.jumlah)) as avg_volume,
  STDDEV(SUM(td.jumlah)) as std_volume,
  COUNT(DISTINCT t.transaction_id) as transaction_count
FROM transactions t
JOIN transaction_details td ON t.transaction_id = td.transaction_id
JOIN barang b ON td.barang_id = b.barang_id
WHERE t.user_id = ? AND t.status = 'berhasil'
GROUP BY t.user_id, b.id_kategori
ORDER BY COUNT(*) DESC
```

---

## 🧪 Testing

### Test 1: Normal Transaksi

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 1,
    "volume": 2,
    "total_price": 50000,
    "transaction_hour": 14
  }'
```

Expected: `status: "normal"`

### Test 2: Anomali Volume Spike

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 2,
    "volume": 50,
    "total_price": 2500000,
    "transaction_hour": 14
  }'
```

Expected: `status: "anomaly"`, indikator volume melonjak

### Test 3: Anomali Price Spike

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 3,
    "volume": 1,
    "total_price": 50000000,
    "transaction_hour": 3
  }'
```

Expected: `status: "anomaly"`, multiple indikator

---

## 📁 File Structure

```
scripts/
├── generate_behavior_anomaly_data.py    # Data generator
├── train_behavior_anomaly.py            # Training script
├── train.py                             # (existing)
└── ...

models/saved/
├── model_behavior_anomaly.joblib        # Trained model
├── scaler_behavior_anomaly.joblib       # Feature scaler
├── model_fraud.joblib                   # (existing)
└── ...

app/api/v1/endpoints/
├── behavior_anomaly.py                  # NEW: API endpoint
├── fraud.py                             # (existing)
├── recommendation.py                    # (existing)
└── ...

data/raw/
├── behavior_anomaly_data.csv            # Training data
├── fraud_data.csv                       # (existing)
└── ...
```

---

## 🚀 Quick Start

```bash
# 1. Generate data
python scripts/generate_behavior_anomaly_data.py

# 2. Train model
python scripts/train_behavior_anomaly.py

# 3. Start API
python main.py

# 4. Test endpoint
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "category_id": 1, "volume": 2, "total_price": 50000}'
```

---

## 📚 References

- **Isolation Forest:** [Original Paper](https://dl.acm.org/doi/10.1145/1363189.1363257)
- **Scikit-learn Docs:** [IsolationForest](https://scikit-learn.org/stable/modules/generated/sklearn.ensemble.IsolationForest.html)
- **Anomaly Detection:** [Concept Overview](https://en.wikipedia.org/wiki/Anomaly_detection)

---

## ❓ FAQ

**Q: Bagaimana kalau user baru tidak punya transaction history?**
A: Model akan menggunakan default profile (avg_price: 50k, std_price: 20k). Seiring transaksi user bertambah, profile akan lebih akurat.

**Q: Berapa akurasi model?**
A: Tergantung data training. Gunakan classification report dari training output untuk lihat precision, recall, dan F1-score.

**Q: Bisa pakai model lain?**
A: Ya! Bisa ganti dengan Isolation Forest, Local Outlier Factor (LOF), atau One-Class SVM. Code sudah fleksibel.

**Q: Bagaimana kalau ingin retrain model?**
A: Jalankan `python scripts/train_behavior_anomaly.py` lagi dengan data baru.

---

**Last Updated:** 2025-01-05
**Model Version:** 1.0.0
**Status:** ✅ Production Ready
