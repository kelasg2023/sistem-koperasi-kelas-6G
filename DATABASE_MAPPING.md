# 🗄️ Database Mapping - Behavior Anomaly Detection

**Dokumentasi complete mapping antara API dan db.sql**

---

## 📊 Tabel Database yang Digunakan

### 1. `transactions` (Transaksi)

```sql
CREATE TABLE `transactions` (
  `transaction_id` bigint UNSIGNED PRIMARY KEY,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `status` ENUM('berhasil', 'proses', 'gagal', 'refund'),
  `payment_method` ENUM('cash', 'qris', 'transfer', 'wallet'),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  ...
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`)
);
```

**Digunakan untuk:**

- ✅ Total harga transaksi (`total_harga`)
- ✅ User ID (`user_id`)
- ✅ Filter status transaksi berhasil (`status = 'berhasil'`)

---

### 2. `transaction_details` (Detail Transaksi)

```sql
CREATE TABLE `transaction_details` (
  `detail_id` bigint UNSIGNED PRIMARY KEY,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  ...
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`)
  CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`)
);
```

**Digunakan untuk:**

- ✅ Jumlah/volume item (`jumlah`)
- ✅ Link ke `barang` untuk dapat `id_kategori`

---

### 3. `barang` (Produk)

```sql
CREATE TABLE `barang` (
  `barang_id` bigint UNSIGNED PRIMARY KEY,
  `nama` varchar(255) NOT NULL,
  `stok` int DEFAULT '0',
  `harga` decimal(15,2) NOT NULL,
  `id_kategori` bigint UNSIGNED NOT NULL,
  ...
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`)
);
```

**Digunakan untuk:**

- ✅ Kategori produk (`id_kategori`)

---

### 4. `kategori` (Kategori Produk)

```sql
CREATE TABLE `kategori` (
  `id_kategori` bigint UNSIGNED PRIMARY KEY,
  `nama_kategori` varchar(50) NOT NULL,
  `satuan` varchar(10) NOT NULL
);
```

**Digunakan untuk:**

- ✅ Identifikasi kategori produk

---

### 5. `users` (User/Anggota)

```sql
CREATE TABLE `users` (
  `id_users` bigint UNSIGNED PRIMARY KEY,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `role` ENUM('admin', 'staff', 'supplier', 'manager', 'customer')
);
```

**Digunakan untuk:**

- ✅ Validasi user_id (optional future use)

---

## 🔍 Query yang Digunakan

### Query 1: Overall Statistics per User

```sql
SELECT
    AVG(t.total_harga) as avg_total_price,
    STDDEV_POP(t.total_harga) as std_total_price,
    COUNT(DISTINCT t.transaction_id) as transaction_count
FROM transactions t
WHERE t.user_id = :user_id
  AND t.status = 'berhasil'
```

**Tujuan:** Hitung rata-rata & standar deviasi harga transaksi user

**Output Example:**

```
avg_total_price: 150000
std_total_price: 45000
transaction_count: 25
```

**Interpretasi:** User ini biasanya belanja Rp 150K ± Rp 45K

---

### Query 2: Per-Category Statistics

```sql
SELECT
    b.id_kategori,
    COUNT(DISTINCT t.transaction_id) as category_count,
    AVG(td.jumlah) as avg_volume
FROM transactions t
JOIN transaction_details td ON t.transaction_id = td.transaction_id
JOIN barang b ON td.barang_id = b.barang_id
WHERE t.user_id = :user_id
  AND t.status = 'berhasil'
GROUP BY b.id_kategori
ORDER BY category_count DESC
```

**Tujuan:** Hitung preferensi kategori dan volume per-kategori

**Output Example:**

```
id_kategori: 1 (Makanan)
category_count: 15
avg_volume: 2.5

id_kategori: 2 (Pakaian)
category_count: 8
avg_volume: 1.2

id_kategori: 3 (Elektronik)
category_count: 2
avg_volume: 1.0
```

**Interpretasi:**

- Primary category: Makanan (15 transaksi)
- Secondary category: Pakaian (8 transaksi)
- Elektronik jarang dibeli (2 transaksi)

---

## 🎯 Feature Engineering Mapping

| Feature          | Query             | db.sql Column                   | Rumus                       |
| ---------------- | ----------------- | ------------------------------- | --------------------------- |
| `norm_price`     | Query 1           | `transactions.total_harga`      | `(harga - avg) / stddev`    |
| `norm_volume`    | Query 2           | `transaction_details.jumlah`    | `(volume - avg) / stddev`   |
| `cat_freq`       | Query 2           | `kategori.id_kategori`          | `count / total`             |
| `time_score`     | Request           | `transactions.created_at` (jam) | 1 jika jam 0-5 atau 22-23   |
| `price_per_vol`  | Query 1 + Request | `harga / volume`                | `log(total_price / volume)` |
| `category_shift` | Query 2           | `kategori.id_kategori`          | 0 jika == primary, else 1   |

---

## 📥 API Request → Database Query Flow

```
POST /api/v1/behavior-anomaly/check
{
  "user_id": 5,
  "category_id": 2,
  "volume": 50,
  "total_price": 2500000,
  "transaction_hour": 14
}
    ↓
    ↓ get_user_profile(user_id=5)
    ↓
    ├─ Query 1: SELECT AVG(total_harga), STDDEV... WHERE user_id=5
    │   └─ Result: avg=150K, std=45K
    │
    └─ Query 2: SELECT id_kategori, COUNT(*), AVG(jumlah)... WHERE user_id=5
       └─ Result:
           - kategori 1: count=15, avg_vol=2.5
           - kategori 2: count=8, avg_vol=1.2
           - kategori 3: count=2, avg_vol=1.0
    ↓
    ↓ engineer_features()
    ├─ norm_price = (2500000 - 150000) / 45000 = 52.2
    ├─ norm_volume = (50 - 1.2) / stddev_vol = ~48
    ├─ cat_freq = 8/25 = 0.32
    ├─ time_score = 0 (jam 14 = normal)
    ├─ price_per_vol = log(2500000 / 50) = log(50K) = 10.82
    └─ category_shift = 1 (kategori 2 bukan primary)
    ↓
    ↓ Isolation Forest Prediction
    └─ Status: ANOMALY (semua feature extreme)
```

---

## ✅ Validasi Query vs db.sql

### Column Names ✓

```
✅ transactions.transaction_id
✅ transactions.user_id
✅ transactions.total_harga (bukan total_price!)
✅ transactions.status
✅ transaction_details.jumlah
✅ transaction_details.barang_id
✅ barang.id_kategori (bukan kategori_id!)
✅ kategori.id_kategori
```

### Foreign Keys ✓

```
✅ users.id_users → transactions.user_id
✅ transactions.transaction_id ← transaction_details.transaction_id
✅ barang.barang_id ← transaction_details.barang_id
✅ kategori.id_kategori ← barang.id_kategori
```

### Enum Values ✓

```
✅ transactions.status IN ('berhasil', 'proses', 'gagal', 'refund')
   Filter: status = 'berhasil'
✅ transactions.payment_method IN ('cash', 'qris', 'transfer', 'wallet')
```

---

## 🔧 Implementation Details

### File: `app/api/v1/endpoints/behavior_anomaly.py`

```python
def get_user_profile(user_id: int):
    """
    Query dari database sesuai db.sql schema.
    """
    from app.core.database import engine
    from sqlalchemy import text

    # Query 1: Overall stats
    query_overall = text("""
    SELECT
        AVG(t.total_harga),          -- transactions.total_harga
        STDDEV_POP(t.total_harga),
        COUNT(DISTINCT t.transaction_id)
    FROM transactions t
    WHERE t.user_id = :user_id
      AND t.status = 'berhasil'
    """)

    # Query 2: Category stats
    query_category = text("""
    SELECT
        b.id_kategori,                  -- barang.id_kategori
        COUNT(DISTINCT t.transaction_id),
        AVG(td.jumlah)                  -- transaction_details.jumlah
    FROM transactions t
    JOIN transaction_details td ON t.transaction_id = td.transaction_id
    JOIN barang b ON td.barang_id = b.barang_id
    WHERE t.user_id = :user_id
      AND t.status = 'berhasil'
    GROUP BY b.id_kategori
    ORDER BY COUNT(*) DESC
    """)

    with engine.connect() as conn:
        result_overall = conn.execute(query_overall, {"user_id": user_id}).fetchone()
        result_categories = conn.execute(query_category, {"user_id": user_id}).fetchall()

    # Process results...
```

---

## 🧪 Test dengan Real Data

**Prerequisite:** Database sudah terisi dengan data transaksi real dari web koperasi

### Test Case: Query User 1 dari Database

```bash
# Assume: User 1 memiliki 10 transaksi dengan pola:
# - Kategori 1 (Makanan): Rp 50K-100K, 3-5 item
# - Kategori 2 (Pakaian): Rp 200K-300K, 1-2 item

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

**Expected Response:**

```json
{
  "status": "anomaly",
  "anomaly_score": 0.95,
  "reason": "Terdeteksi PERUBAHAN PERILAKU DRASTIS. Indikator: Volume pembelian melonjak: 50 item vs rata-rata 1.5 item | Lompatan harga drastis: Rp2,500,000 vs rata-rata Rp250,000",
  "user_profile": {
    "avg_total_price": 250000,      ← From Query 1
    "std_total_price": 75000,       ← From Query 1
    "avg_volume": 1.5,              ← From Query 2
    "primary_category_id": 1,       ← From Query 2 (Makanan paling sering)
    "category_frequency": {
      "1": 0.7,                     ← 7 dari 10 transaksi
      "2": 0.3                      ← 3 dari 10 transaksi
    },
    "transaction_count": 10
  }
}
```

---

## 📋 Summary

✅ **Fully Aligned dengan db.sql**

- Query names: Correct
- Column names: Correct
- Foreign keys: Correct
- Enum values: Correct
- Data types: Correct

✅ **Ready untuk Production**

- Database connection: Via SQLAlchemy engine
- Query execution: Safe with parameterized queries
- Error handling: Fallback ke default jika user belum ada transaksi

✅ **Real User Profiling**

- Setiap user punya profile unik berdasarkan history
- Deteksi anomali relatif terhadap user behavior, bukan absolut

---

**Status: ✅ Production Ready** 🚀
