import os
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import random

# Tentukan folder output
OUTPUT_DIR = "data/raw"
OUTPUT_FILE = os.path.join(OUTPUT_DIR, "fraud_data.csv")

def generate_data(num_records=1000):
    np.random.seed(42)
    random.seed(42)
    
    data = []
    
    # Menyesuaikan dengan database schema:
    # - user_id (int)
    # - transaction_id (int)
    # - total_harga (decimal)
    # - payment_method: 'cash', 'qris', 'transfer', 'wallet'
    # - status: 'berhasil', 'proses', 'gagal', 'refund'
    # - created_at (timestamp)
    
    user_ids = list(range(1, 101))  # 100 user (id_users)
    payment_methods = ['cash', 'qris', 'transfer', 'wallet']
    
    # Mulai tanggal transaksi
    start_date = datetime(2026, 6, 1, 8, 0, 0)
    
    for i in range(1, num_records + 1):
        # Kebanyakan transaksi terjadi jam 08:00 - 17:00
        hour = random.randint(8, 16)
        minute = random.randint(0, 59)
        second = random.randint(0, 59)
        
        # Hari acak dalam bulan Juni 2026
        days_to_add = random.randint(0, 25)
        tx_time = start_date + timedelta(days=days_to_add, hours=hour - 8, minutes=minute, seconds=second)
        
        user_id = random.choice(user_ids)
        payment_method = random.choice(payment_methods)
        
        # Nominal transaksi normal berkisar Rp 5.000 s/d Rp 150.000
        total_harga = float(round(random.uniform(5000, 150000), -3)) # Dibulatkan ke ribuan terdekat
        
        data.append({
            "transaction_id": i,
            "user_id": user_id,
            "total_harga": total_harga,
            "status": "berhasil",
            "payment_method": payment_method,
            "created_at": tx_time.strftime("%Y-%m-%d %H:%M:%S"),
            "is_anomaly": 0  # Label normal
        })

    # ── SUNTIK ANOMALI (FRAUD) ──
    
    # 1. Anomali Jenis 1: Nominal Ekstrem (total_harga sangat besar)
    for j in range(15):
        idx = random.randint(0, num_records - 1)
        data[idx]["total_harga"] = float(round(random.uniform(1500000, 5000000), -3))  # Rp 1,5 jt s/d 5 jt
        data[idx]["is_anomaly"] = 1
        
    # 2. Anomali Jenis 2: Transaksi di luar jam kerja (tengah malam)
    for j in range(15):
        idx = random.randint(0, num_records - 1)
        dt = datetime.strptime(data[idx]["created_at"], "%Y-%m-%d %H:%M:%S")
        suspicious_dt = dt.replace(hour=random.randint(1, 4))
        data[idx]["created_at"] = suspicious_dt.strftime("%Y-%m-%d %H:%M:%S")
        data[idx]["is_anomaly"] = 1
        
    # 3. Anomali Jenis 3: Transaksi Duplikat Cepat (User sama, total_harga sama, waktu < 1 menit)
    next_tx_id = num_records + 1
    for j in range(10):
        idx = random.randint(0, num_records - 1)
        original_tx = data[idx]
        
        dt = datetime.strptime(original_tx["created_at"], "%Y-%m-%d %H:%M:%S")
        duplicate_dt = dt + timedelta(seconds=random.randint(5, 30))
        
        duplicate_tx = {
            "transaction_id": next_tx_id,
            "user_id": original_tx["user_id"],
            "total_harga": original_tx["total_harga"],
            "status": "berhasil",
            "payment_method": original_tx["payment_method"],
            "created_at": duplicate_dt.strftime("%Y-%m-%d %H:%M:%S"),
            "is_anomaly": 1
        }
        data.append(duplicate_tx)
        next_tx_id += 1

    # Simpan ke CSV
    df = pd.DataFrame(data)
    # Sort berdasarkan transaction_id
    df = df.sort_values(by="transaction_id").reset_index(drop=True)
    
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    df.to_csv(OUTPUT_FILE, index=False)
    print(f"Berhasil memperbarui data transaksi sesuai skema db.sql!")
    print(f"File disimpan di: {OUTPUT_FILE}")
    print(f"Total data: {len(df)} baris. Jumlah anomali: {df['is_anomaly'].sum()} transaksi.")

if __name__ == "__main__":
    generate_data()
