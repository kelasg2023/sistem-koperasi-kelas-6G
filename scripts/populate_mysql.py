import os
import pandas as pd
import numpy as np
from sqlalchemy import create_engine, text
from loguru import logger
from dotenv import load_dotenv

# Load env variables
load_dotenv()

DATABASE_URL = os.getenv("DATABASE_URL")

# Ganti mysql:// dengan mysql+pymysql:// jika belum ada
if DATABASE_URL.startswith("mysql://"):
    DATABASE_URL = DATABASE_URL.replace("mysql://", "mysql+pymysql://")

def populate_database():
    logger.info("Mengkoneksikan ke MySQL database...")
    engine = create_engine(DATABASE_URL)
    
    # ── 1. Cek Koneksi ────────────────────────────────────────────────────────
    try:
        with engine.connect() as conn:
            conn.execute(text("SELECT 1"))
        logger.success("Koneksi ke MySQL berhasil!")
    except Exception as e:
        logger.error(f"Gagal terhubung ke MySQL: {e}")
        logger.error("Pastikan server MySQL (XAMPP/Laragon) menyala dan database 'db_koperasi' sudah dibuat.")
        return

    # ── 2. Load Data CSV Simulasi ─────────────────────────────────────────────
    recommendation_csv = "data/raw/recommendation_data.csv"
    fraud_csv = "data/raw/fraud_data.csv"
    
    if not os.path.exists(recommendation_csv) or not os.path.exists(fraud_csv):
        logger.error("File CSV data simulasi belum lengkap. Jalankan script generator data terlebih dahulu.")
        return
        
    df_rec = pd.read_csv(recommendation_csv)
    df_fraud = pd.read_csv(fraud_csv)

    # ── 3. Sinkronisasi Data Master (kategori, barang, users) ──────────────────
    # Karena ada Foreign Key Constraints di db.sql, kita harus mengisi data master dulu
    
    with engine.begin() as conn:
        logger.info("Mengisi tabel master: 'kategori'...")
        conn.execute(text("SET FOREIGN_KEY_CHECKS = 0;")) # Matikan FK sementara untuk membersihkan data lama
        conn.execute(text("TRUNCATE TABLE transaction_details;"))
        conn.execute(text("TRUNCATE TABLE transactions;"))
        conn.execute(text("TRUNCATE TABLE customers;"))
        conn.execute(text("TRUNCATE TABLE barang;"))
        conn.execute(text("TRUNCATE TABLE kategori;"))
        conn.execute(text("TRUNCATE TABLE users;"))
        conn.execute(text("SET FOREIGN_KEY_CHECKS = 1;"))

        # A. Kategori
        conn.execute(text("INSERT INTO kategori (id_kategori, nama_kategori, satuan) VALUES "
                          "(1, 'Makanan & Minuman', 'pcs'), "
                          "(2, 'Alat Tulis', 'pcs'), "
                          "(3, 'Keperluan Umum', 'pcs');"))

        # B. Barang
        logger.info("Mengisi tabel master: 'barang'...")
        barang_list = [
            (1, "Buku Tulis", 100, 5000.00, 2),
            (2, "Pulpen", 150, 3000.00, 2),
            (3, "Penggaris", 50, 2500.00, 2),
            (4, "Kopi Sachet", 300, 1500.00, 1),
            (5, "Teh Celup", 200, 2000.00, 1),
            (6, "Roti Tawar", 40, 12000.00, 1),
            (7, "Susu UHT", 80, 6000.00, 1),
            (8, "Mie Instan", 500, 3500.00, 1),
            (9, "Sabun Mandi", 60, 4000.00, 3),
            (10, "Detergen", 70, 15000.00, 3),
        ]
        for b in barang_list:
            conn.execute(text("INSERT INTO barang (barang_id, nama, stok, harga, id_kategori) VALUES "
                              "(:id, :nama, :stok, :harga, :id_kat);"), 
                         {"id": b[0], "nama": b[1], "stok": b[2], "harga": b[3], "id_kat": b[4]})

        # C. Users
        logger.info("Mengisi tabel master: 'users' (100 anggota)...")
        for u_id in range(1, 101):
            conn.execute(text("INSERT INTO users (id_users, username, email, password, role) VALUES "
                              "(:id, :user, :email, 'password123', 'customer');"),
                         {"id": u_id, "user": f"anggota_{u_id:03d}", "email": f"anggota_{u_id:03d}@koperasi6g.test"})
        
        # D. Customers records untuk setiap anggota
        logger.info("Mengisi tabel 'customers' untuk setiap anggota...")
        for u_id in range(1, 101):
            conn.execute(text("INSERT INTO customers (user_id, point, is_member) VALUES "
                              "(:u_id, 0, 0);"),
                         {"u_id": u_id})

    # ── 4. Sinkronisasi Data Transaksi (transactions) ─────────────────────────
    # Ambil data transaksi unik dari df_fraud untuk dimasukkan ke tabel `transactions`
    logger.info("Mengisi tabel 'transactions' dari data simulasi...")
    
    # Ambil data header transaksi unik
    tx_headers = df_fraud[['transaction_id', 'user_id', 'total_harga', 'status', 'payment_method', 'created_at']].copy()
    
    # Kita juga butuh header transaksi untuk recommendation_data
    # Karena di recommendation_data transaction_id-nya dummy, mari kita gabungkan datanya
    rec_headers = df_rec[['transaction_id', 'user_id', 'created_at']].copy()
    rec_headers['total_harga'] = 0.0 # dummy
    rec_headers['status'] = 'berhasil'
    rec_headers['payment_method'] = 'cash'
    
    # Gabung semua header transaksi, buang duplikat id-nya
    all_headers = pd.concat([tx_headers, rec_headers]).drop_duplicates(subset=['transaction_id'])
    
    with engine.begin() as conn:
        for idx, row in all_headers.iterrows():
            conn.execute(
                text("INSERT INTO transactions (transaction_id, user_id, total_harga, status, payment_method, created_at) "
                     "VALUES (:tx_id, :u_id, :total, :stat, :pay, :created);"),
                {
                    "tx_id": int(row['transaction_id']),
                    "u_id": int(row['user_id']),
                    "total": float(row['total_harga']),
                    "stat": str(row['status']),
                    "pay": str(row['payment_method']),
                    "created": str(row['created_at'])
                }
            )

    # ── 5. Sinkronisasi Detail Transaksi (transaction_details) ────────────────
    logger.info("Mengisi tabel 'transaction_details'...")
    
    # Detail transaksi didapat dari df_rec
    with engine.begin() as conn:
        for idx, row in df_rec.iterrows():
            # Cari harga satuan dari barang
            barang_id = int(row['barang_id'])
            harga_satuan = next(b[3] for b in barang_list if b[0] == barang_id)
            
            # Hitung total harga per item
            conn.execute(
                text("INSERT INTO transaction_details (detail_id, transaction_id, barang_id, jumlah, harga_satuan) "
                     "VALUES (:det_id, :tx_id, :b_id, :qty, :price);"),
                {
                    "det_id": int(row['detail_id']),
                    "tx_id": int(row['transaction_id']),
                    "b_id": barang_id,
                    "qty": int(row['jumlah']),
                    "price": float(harga_satuan)
                }
            )
            
            # Update total_harga di tabel transactions agar sesuai dengan jumlah * harga_satuan
            conn.execute(
                text("UPDATE transactions SET total_harga = total_harga + :subtotal WHERE transaction_id = :tx_id;"),
                {
                    "subtotal": float(int(row['jumlah']) * harga_satuan),
                    "tx_id": int(row['transaction_id'])
                }
            )

    logger.success("Seluruh data simulasi berhasil di-populate ke MySQL database lokal Anda!")

if __name__ == "__main__":
    populate_database()
