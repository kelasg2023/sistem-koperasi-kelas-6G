# 🧪 Testing API - Fraud Detection vs Behavior Anomaly Detection

Ada **2 AI API Endpoints** yang bisa di-test:

---

## 🚀 Start API Server

```bash
python main.py
```

Server berjalan di: `http://localhost:5610`

---

## 1️⃣ API #1: Fraud Detection

**Endpoint:** `POST /api/v1/fraud/check`

**Purpose:** Deteksi transaksi mencurigakan berdasarkan:

- Nominal transaksi ekstrem
- Jam transaksi aneh
- Metode pembayaran tidak biasa

### Test Case 1.1: Normal Transaction ✅

```bash
curl -X POST "http://localhost:5610/api/v1/fraud/check" \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": 1,
    "user_id": 1,
    "total_harga": 150000,
    "payment_method": "cash",
    "created_at": "2025-01-05 14:30:00"
  }'
```

**Expected Response:**

```json
{
  "transaction_id": 1,
  "status": "normal",
  "fraud_score": 0.15,
  "reason": "Transaksi normal dan aman"
}
```

---

### Test Case 1.2: Suspicious Transaction 🚨

```bash
curl -X POST "http://localhost:5610/api/v1/fraud/check" \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": 2,
    "user_id": 5,
    "total_harga": 5000000,
    "payment_method": "qris",
    "created_at": "2025-01-05 03:15:00"
  }'
```

**Expected Response:**

```json
{
  "transaction_id": 2,
  "status": "suspicious",
  "fraud_score": 0.85,
  "reason": "Terdeteksi anomali: nominal transaksi ekstrem (> Rp 1.000.000), transaksi terjadi pada jam mencurigakan (03:00)"
}
```

---

### Test Case 1.3: Invalid Payment Method ❌

```bash
curl -X POST "http://localhost:5610/api/v1/fraud/check" \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": 3,
    "user_id": 1,
    "total_harga": 100000,
    "payment_method": "bitcoin",
    "created_at": "2025-01-05 14:00:00"
  }'
```

**Expected Response (Error):**

```json
{
  "detail": "payment_method tidak valid. Harus salah satu dari: ['cash', 'qris', 'transfer', 'wallet']"
}
```

---

## 2️⃣ API #2: Behavior Anomaly Detection

**Endpoint:** `POST /api/v1/behavior-anomaly/check`

**Purpose:** Deteksi PERUBAHAN PERILAKU DRASTIS pada user:

- Volume pembelian melonjak
- Kategori produk shift drastis
- Harga per unit ekstrem
- User profiling based detection

### Test Case 2.1: Normal Behavior ✅

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 1,
    "volume": 2,
    "total_price": 50000,
    "transaction_hour": 14,
    "transaction_date": "2025-01-05",
    "transaction_id": 100
  }'
```

**Expected Response:**

```json
{
  "transaction_id": 100,
  "user_id": 1,
  "category_id": 1,
  "status": "normal",
  "anomaly_score": 0.25,
  "confidence": 0.75,
  "reason": "Pola pembelian normal dan sesuai dengan behavior user.",
  "user_profile": {
    "avg_total_price": 50000,
    "std_total_price": 20000,
    "avg_volume": 2,
    "std_volume": 1,
    "primary_category_id": null,
    "category_frequency": {},
    "transaction_count": 0
  },
  "suspicious_indicators": []
}
```

---

### Test Case 2.2: Volume Spike (Anomaly) 🚨

**Skenario:** Biasanya beli 2 item → tiba-tiba beli 50 item

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 2,
    "volume": 50,
    "total_price": 2500000,
    "transaction_hour": 14,
    "transaction_date": "2025-01-05",
    "transaction_id": 101
  }'
```

**Expected Response:**

```json
{
  "transaction_id": 101,
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

---

### Test Case 2.3: Price Spike + Unusual Hour 🚨

**Skenario:** Beli 1 item tetapi harganya 50 juta pada jam 2 pagi

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 3,
    "volume": 1,
    "total_price": 50000000,
    "transaction_hour": 2,
    "transaction_date": "2025-01-05",
    "transaction_id": 102
  }'
```

**Expected Response:**

```json
{
  "transaction_id": 102,
  "user_id": 1,
  "category_id": 3,
  "status": "anomaly",
  "anomaly_score": 0.98,
  "confidence": 0.95,
  "reason": "Terdeteksi PERUBAHAN PERILAKU DRASTIS pada pola pembelian. Indikator: Lompatan harga drastis: Rp50,000,000.00 vs rata-rata Rp50,000.00 | Transaksi pada jam mencurigakan: 02:00 | Harga per unit ekstrem: Rp50,000,000.00",
  "user_profile": {...},
  "suspicious_indicators": [
    "Lompatan harga drastis: Rp50,000,000.00 vs rata-rata Rp50,000.00",
    "Transaksi pada jam mencurigakan: 02:00",
    "Harga per unit ekstrem: Rp50,000,000.00"
  ]
}
```

---

### Test Case 2.4: Category Shift 🚨

**Skenario:** User biasanya beli makanan (cat 1), tiba-tiba beli elektronik besar-besaran (cat 3)

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "category_id": 3,
    "volume": 10,
    "total_price": 5000000,
    "transaction_hour": 14,
    "transaction_date": "2025-01-05",
    "transaction_id": 103
  }'
```

**Expected Response:**

```json
{
  "transaction_id": 103,
  "user_id": 1,
  "category_id": 3,
  "status": "anomaly",
  "anomaly_score": 0.88,
  "confidence": 0.82,
  "reason": "Terdeteksi PERUBAHAN PERILAKU DRASTIS pada pola pembelian. Indikator: Kategori tidak sesuai usual pattern (Category ID: 3)",
  "user_profile": {...},
  "suspicious_indicators": [
    "Kategori tidak sesuai usual pattern (Category ID: 3)"
  ]
}
```

---

### Test Case 2.5: Invalid Input ❌

```bash
curl -X POST "http://localhost:5610/api/v1/behavior-anomaly/check" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 0,
    "category_id": 1,
    "volume": 5,
    "total_price": 100000,
    "transaction_hour": 14
  }'
```

**Expected Response (Error):**

```json
{
  "detail": "user_id harus positif"
}
```

---

## 📊 Comparison: API Fraud vs Behavior Anomaly

| Aspek            | Fraud Detection                | Behavior Anomaly                                                   |
| ---------------- | ------------------------------ | ------------------------------------------------------------------ |
| **Endpoint**     | `/fraud/check`                 | `/behavior-anomaly/check`                                          |
| **Algorithm**    | Isolation Forest               | Isolation Forest                                                   |
| **Features**     | Amount, Hour, Payment Method   | Price z-score, Volume z-score, Category freq, Time, Price per unit |
| **Focus**        | Transaksi mencurigakan per-se  | Penyimpangan dari user pattern                                     |
| **Output Score** | 0.15 (normal) / 0.85 (anomaly) | 0-1 (normalized)                                                   |
| **Use Case**     | Deteksi fraud umum             | Deteksi perubahan behavior drastis                                 |

---

## 🧪 Recommended Test Sequence

1. **Start API:**

   ```bash
   python main.py
   ```

2. **Test Fraud API (normal):** Test Case 1.1
3. **Test Fraud API (anomaly):** Test Case 1.2
4. **Test Behavior Anomaly API (normal):** Test Case 2.1
5. **Test Behavior Anomaly API (volume spike):** Test Case 2.2
6. **Test Behavior Anomaly API (price spike):** Test Case 2.3
7. **Test error handling:** Test Case 2.5

---

## 📝 Using Postman (Alternative)

1. **Import Collection** atau buat manual requests
2. **URL:** `http://localhost:5610/api/v1/{endpoint}`
3. **Method:** POST
4. **Headers:** `Content-Type: application/json`
5. **Body:** Copy dari test cases di atas

---

## ✅ Success Indicators

- ✅ Endpoint respond dengan status 200
- ✅ Response JSON valid dan sesuai schema
- ✅ Status field = "normal" atau "anomaly"
- ✅ anomaly_score / fraud_score dalam range 0-1
- ✅ reason field berisi penjelasan logis

---

**Happy Testing! 🎉**
