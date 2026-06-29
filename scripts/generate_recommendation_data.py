import os
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import random

# Tentukan folder output
OUTPUT_DIR = "data/raw"
OUTPUT_FILE = os.path.join(OUTPUT_DIR, "recommendation_data.csv")

def generate_recommendation_data(num_records=2000):
    np.random.seed(42)
    random.seed(42)
    
    data = []
    
    # ── Master Data Sesuai db.sql ──────────────────────────────────────────
    # User ID: 1 s/d 100
    # Barang ID: 1 s/d 10 (Susu, Roti, Kopi, Teh, Buku Tulis, Pulpen, dll.)
    user_ids = list(range(1, 101))
    
    # Kategori Barang:
    # 1: Makanan & Minuman (Susu, Roti, Kopi, Teh, Mie Instan)
    # 2: Alat Tulis (Buku Tulis, Pulpen, Penggaris, Penghapus)
    # 3: Keperluan Umum (Sabun, Detergen)
    barang_list = [
        {"barang_id": 1, "nama": "Buku Tulis", "id_kategori": 2},
        {"barang_id": 2, "nama": "Pulpen", "id_kategori": 2},
        {"barang_id": 3, "nama": "Penggaris", "id_kategori": 2},
        {"barang_id": 4, "nama": "Kopi Sachet", "id_kategori": 1},
        {"barang_id": 5, "nama": "Teh Celup", "id_kategori": 1},
        {"barang_id": 6, "nama": "Roti Tawar", "id_kategori": 1},
        {"barang_id": 7, "nama": "Susu UHT", "id_kategori": 1},
        {"barang_id": 8, "nama": "Mie Instan", "id_kategori": 1},
        {"barang_id": 9, "nama": "Sabun Mandi", "id_kategori": 3},
        {"barang_id": 10, "nama": "Detergen", "id_kategori": 3},
    ]
    
    # ── Simulasi Klaster Selera Belanja (Agar AI dapat mempelajari pola) ──────
    # Klaster 1: Tipe Pelajar/Mahasiswa (Sering beli Alat Tulis)
    # Klaster 2: Tipe Pekerja/Staff (Sering beli Kopi, Teh, Roti)
    # Klaster 3: Tipe Rumah Tangga (Sering beli Susu, Mie, Sabun, Detergen)
    
    start_date = datetime(2026, 6, 1, 8, 0, 0)
    
    for i in range(1, num_records + 1):
        # Pilih user secara acak
        user_id = random.choice(user_ids)
        
        # Tentukan klaster user berdasarkan ID-nya
        if user_id <= 40:
            # Klaster Pelajar -> Prioritas Alat Tulis (Barang ID: 1, 2, 3)
            weights = [0.35, 0.35, 0.15, 0.03, 0.03, 0.03, 0.02, 0.02, 0.01, 0.01]
        elif user_id <= 70:
            # Klaster Pekerja -> Prioritas Kopi/Teh/Roti (Barang ID: 4, 5, 6)
            weights = [0.03, 0.03, 0.01, 0.35, 0.30, 0.20, 0.04, 0.02, 0.01, 0.01]
        else:
            # Klaster Rumah Tangga -> Prioritas Susu/Mie/Sabun (Barang ID: 7, 8, 9, 10)
            weights = [0.02, 0.02, 0.01, 0.05, 0.05, 0.10, 0.30, 0.25, 0.10, 0.10]
            
        # Pilih barang berdasarkan bobot klaster
        barang = random.choices(barang_list, weights=weights, k=1)[0]
        
        # Jumlah beli acak (1 s/d 5)
        jumlah = random.choices([1, 2, 3, 4, 5], weights=[0.60, 0.25, 0.10, 0.03, 0.02], k=1)[0]
        
        # Waktu transaksi acak selama bulan Juni 2026
        days_to_add = random.randint(0, 25)
        hour = random.randint(8, 16)
        minute = random.randint(0, 59)
        second = random.randint(0, 59)
        tx_time = start_date + timedelta(days=days_to_add, hours=hour - 8, minutes=minute, seconds=second)
        
        data.append({
            "detail_id": i,
            "transaction_id": random.randint(1000, 5000), # dummy tx id
            "user_id": user_id,
            "barang_id": barang["barang_id"],
            "nama_barang": barang["nama"],
            "id_kategori": barang["id_kategori"],
            "jumlah": jumlah,
            "created_at": tx_time.strftime("%Y-%m-%d %H:%M:%S")
        })
        
    df = pd.DataFrame(data)
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    df.to_csv(OUTPUT_FILE, index=False)
    
    print(f"Berhasil membuat dataset rekomendasi belanja di: {OUTPUT_FILE}")
    print(f"Total data: {len(df)} baris transaksi detail.")
    print("Contoh klaster pembelian:")
    print(df.groupby('user_id')['nama_barang'].agg(lambda x: x.mode().iloc[0]).head(10))

if __name__ == "__main__":
    generate_recommendation_data()
