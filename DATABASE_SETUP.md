# 🔧 Setup Database Connection - MySQL

## 🚨 Current Issue

Database config masih pakai **SQLite**, padahal harus **MySQL** untuk align dengan web koperasi!

```python
# ❌ Current (WRONG)
DATABASE_URL: str = "sqlite:///./data/db/app.db"

# ✅ Correct (MySQL)
DATABASE_URL: str = "mysql+pymysql://user:password@host:port/database"
```

---

## 📋 Setup Steps

### 1. Create `.env` file

Di root project: `f:\Tugas\S6\Sistem Cerdas\Projek Koperasi Digital 6G\sistem-koperasi-kelas-6G\.env`

```bash
# Database Configuration
DATABASE_URL=mysql+pymysql://koperasi_user:password123@localhost:3306/koperasi_6g

# Model Configuration
MODEL_DIR=models/saved
MODEL_NAME=model.joblib

# API Configuration
HOST=0.0.0.0
PORT=5610
DEBUG=True
```

---

## 🔑 Database Connection String

Format: `mysql+pymysql://[USERNAME]:[PASSWORD]@[HOST]:[PORT]/[DATABASE]`

### Example:

```
mysql+pymysql://root:@localhost:3306/koperasi_6g
```

### Components:

- `root` = MySQL username
- `` (kosong) = Password (jika tidak ada)
- `localhost` = Host/IP MySQL server
- `3306` = MySQL port (default)
- `koperasi_6g` = Database name

---

## 🔍 Find Your Database Credentials

### Option 1: Laravel .env (jika ada web Laravel)

Cek file `.env` di folder web koperasi Laravel:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi_6g
DB_USERNAME=root
DB_PASSWORD=
```

Convert ke format Python:

```
DATABASE_URL=mysql+pymysql://root:@127.0.0.1:3306/koperasi_6g
```

### Option 2: MySQL Server

```bash
mysql -u root -p
mysql> SHOW DATABASES;
mysql> USE koperasi_6g;
mysql> SHOW TABLES;
```

---

## ✅ After Setup

1. **Create `.env` file** dengan MySQL credentials
2. **Config akan auto-load** dari `.env`
3. **Restart API server** untuk apply changes

```bash
python main.py
```

---

## 🧪 Test Connection

Setelah setup `.env`, test dengan:

```bash
curl http://localhost:5610/api/v1/behavior-anomaly/profile/1
```

Expected response dengan **real data dari database**:

```json
{
  "user_id": 1,
  "profile": {
    "avg_total_price": 150000,
    "std_total_price": 45000,
    "avg_volume": 2.5,
    "primary_category_id": 1,
    "category_frequency": {
      "1": 0.7,
      "2": 0.3
    },
    "transaction_count": 10
  }
}
```

---

## 📝 Notes

- ✅ `pymysql` sudah ada di `requirements.txt`
- ✅ Query sudah sesuai `db.sql` schema
- ✅ Fallback ke default jika user belum punya data
- ✅ Production ready setelah `.env` benar

---

**Yang perlu Anda lakukan:**

1. **Buka `.env` atau create baru** di root project
2. **Set DATABASE_URL** ke MySQL connection string Anda
3. **Restart API server**
4. **Test endpoint** untuk verify data dari database

Mau saya buatin template `.env` file?
