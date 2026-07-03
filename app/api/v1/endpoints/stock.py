from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from sqlalchemy import text
from datetime import datetime, timedelta
import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression
from typing import List, Optional
from pydantic import BaseModel

from app.core.database import get_db

router = APIRouter()

# ── Z-Factor Helper ──────────────────────────────────────────────────────────
def get_z_factor(service_level: float) -> float:
    """Mendapatkan nilai Z-score berdasarkan Service Level."""
    if service_level >= 0.99:
        return 2.33
    elif service_level >= 0.95:
        return 1.65
    elif service_level >= 0.90:
        return 1.28
    else:
        return 1.65  # Default 95%

# ── Schemas ──────────────────────────────────────────────────────────────────
class StockPredictionResponse(BaseModel):
    barang_id: int
    nama: str
    stok_sekarang: int
    prediksi_permintaan_harian: float
    safety_stock: float
    reorder_point: float
    sisa_hari: Optional[float]
    tanggal_habis: Optional[str]
    status: str

class StockAlertResponse(BaseModel):
    barang_id: int
    nama: str
    stok_sekarang: int
    reorder_point: float
    status: str

class SafetyStockDetailResponse(BaseModel):
    barang_id: int
    nama: str
    lead_time: int
    service_level: float
    z_factor: float
    rata_rata_permintaan: float
    standar_deviasi: float
    safety_stock: float
    reorder_point: float

# ── Helper: Kalkulator Stok Core ──────────────────────────────────────────────
def calculate_stock_metrics(
    db: Session,
    lead_time: int = 3,
    service_level: float = 0.95
) -> pd.DataFrame:
    """
    Fungsi internal untuk menarik data dari MySQL dan menghitung metrik stok
    menggunakan Machine Learning Regression & Formula Safety Stock.
    """
    # 1. Ambil data barang & stok saat ini
    query_barang = text("SELECT barang_id, nama, stok FROM barang")
    df_barang = pd.DataFrame(db.execute(query_barang).fetchall(), columns=['barang_id', 'nama', 'stok'])
    
    if df_barang.empty:
        return pd.DataFrame()

    # 2. Ambil data penjualan harian dari histori transaksi sukses
    query_sales = text("""
        SELECT 
            td.barang_id,
            DATE(t.created_at) as tanggal,
            SUM(td.jumlah) as total_qty
        FROM transaction_details td
        JOIN transactions t ON td.transaction_id = t.transaction_id
        WHERE t.status = 'berhasil'
        GROUP BY td.barang_id, DATE(t.created_at)
        ORDER BY td.barang_id, tanggal ASC
    """)
    df_sales = pd.DataFrame(db.execute(query_sales).fetchall(), columns=['barang_id', 'tanggal', 'total_qty'])
    df_sales['total_qty'] = df_sales['total_qty'].astype(float)


    # Siapkan list penampung hasil
    results = []
    z_val = get_z_factor(service_level)

    # 3. Hitung prediksi & stok aman per barang
    for _, row in df_barang.iterrows():
        b_id = int(row['barang_id'])
        nama_barang = str(row['nama'])
        stok_sekarang = int(row['stok'])

        # Ambil histori penjualan produk ini
        df_prod_sales = df_sales[df_sales['barang_id'] == b_id].copy()
        
        # Inisialisasi default jika tidak ada histori penjualan
        daily_demand = 0.0
        std_dev = 0.0
        
        if not df_prod_sales.empty:
            df_prod_sales['tanggal'] = pd.to_datetime(df_prod_sales['tanggal'])
            std_dev = float(df_prod_sales['total_qty'].std())
            if np.isnan(std_dev):
                std_dev = 0.0
                
            # Jika data cukup (minimal 5 hari), gunakan Linear Regression
            if len(df_prod_sales) >= 5:
                # Siapkan fitur X (indeks hari) dan target y (total_qty)
                df_prod_sales = df_prod_sales.sort_values('tanggal')
                X = np.arange(len(df_prod_sales)).reshape(-1, 1)
                y = df_prod_sales['total_qty'].values
                
                # Fit model regresi harian secara real-time
                model = LinearRegression()
                model.fit(X, y)
                
                # Prediksi demand untuk hari berikutnya
                next_day_index = np.array([[len(df_prod_sales)]])
                predicted_demand = float(model.predict(next_day_index)[0])
                
                # Pengaman: jika prediksi negatif (tren menurun tajam), fallback ke nilai rata-rata
                if predicted_demand < 0:
                    daily_demand = float(df_prod_sales['total_qty'].mean())
                else:
                    daily_demand = predicted_demand
            else:
                # Fallback ke rata-rata penjualan riil
                daily_demand = float(df_prod_sales['total_qty'].mean())

        # 4. Kalkulasi Safety Stock & Reorder Point
        safety_stock = round(z_val * std_dev * np.sqrt(lead_time), 2)
        reorder_point = round((daily_demand * lead_time) + safety_stock, 2)
        
        # 5. Hitung sisa hari & tanggal habis stok
        sisa_hari = None
        tanggal_habis = "Aman (Tidak ada penjualan)"
        
        if daily_demand > 0.01:
            sisa_hari = round(stok_sekarang / daily_demand, 1)
            eta_date = datetime.now() + timedelta(days=sisa_hari)
            tanggal_habis = eta_date.strftime("%Y-%m-%d")
        elif stok_sekarang == 0:
            sisa_hari = 0.0
            tanggal_habis = "Stok Habis"
            
        # Tentukan status peringatan
        if stok_sekarang == 0:
            status = "Kritis (Stok Habis)"
        elif stok_sekarang <= reorder_point:
            status = "Peringatan (Perlu Dipesan)"
        else:
            status = "Aman"
            
        results.append({
            "barang_id": b_id,
            "nama": nama_barang,
            "stok_sekarang": stok_sekarang,
            "prediksi_permintaan_harian": round(daily_demand, 2),
            "standar_deviasi": round(std_dev, 2),
            "safety_stock": safety_stock,
            "reorder_point": reorder_point,
            "sisa_hari": sisa_hari,
            "tanggal_habis": tanggal_habis,
            "status": status
        })
        
    return pd.DataFrame(results)

# ── Endpoint 1: Prediksi Stok Habis & ROP ─────────────────────────────────────
@router.get("/stok/prediksi", response_model=List[StockPredictionResponse])
async def get_stok_prediksi(
    lead_time: int = Query(3, description="Lead time pemesanan dalam hari"),
    service_level: float = Query(0.95, description="Service level target (contoh: 0.90, 0.95, 0.99)"),
    db: Session = Depends(get_db)
):
    """
    Memprediksi sisa hari stok barang dan menghitung Safety Stock serta Reorder Point
    secara real-time menggunakan Linear Regression dari histori MySQL.
    """
    try:
        df_result = calculate_stock_metrics(db, lead_time, service_level)
        if df_result.empty:
            return []
            
        response = []
        for _, row in df_result.iterrows():
            response.append(StockPredictionResponse(
                barang_id=int(row['barang_id']),
                nama=str(row['nama']),
                stok_sekarang=int(row['stok_sekarang']),
                prediksi_permintaan_harian=float(row['prediksi_permintaan_harian']),
                safety_stock=float(row['safety_stock']),
                reorder_point=float(row['reorder_point']),
                sisa_hari=row['sisa_hari'] if pd.notna(row['sisa_hari']) else None,
                tanggal_habis=str(row['tanggal_habis']),
                status=str(row['status'])
            ))
        return response
    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=f"Gagal menghitung prediksi stok: {str(e)}"
        )

# ── Endpoint 2: Peringatan Stok Kritis / Perlu Dipesan ─────────────────────────
@router.get("/stok/alert", response_model=List[StockAlertResponse])
async def get_stok_alert(
    lead_time: int = Query(3, description="Lead time pemesanan dalam hari"),
    service_level: float = Query(0.95, description="Service level target (contoh: 0.90, 0.95, 0.99)"),
    db: Session = Depends(get_db)
):
    """
    Mendapatkan daftar barang yang sudah memasuki kondisi kritis (stok habis)
    atau peringatan (stok di bawah Reorder Point) agar segera dipesan kembali.
    """
    try:
        df_result = calculate_stock_metrics(db, lead_time, service_level)
        if df_result.empty:
            return []
            
        # Filter hanya barang yang memerlukan reorder atau sudah habis
        df_alert = df_result[df_result['status'].isin(["Kritis (Stok Habis)", "Peringatan (Perlu Dipesan)"])]
        
        response = []
        for _, row in df_alert.iterrows():
            response.append(StockAlertResponse(
                barang_id=int(row['barang_id']),
                nama=str(row['nama']),
                stok_sekarang=int(row['stok_sekarang']),
                reorder_point=float(row['reorder_point']),
                status=str(row['status'])
            ))
        return response
    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=f"Gagal mengambil alert stok: {str(e)}"
        )

# ── Endpoint 3: Konfigurasi & Kalkulasi Stok Aman per Produk ───────────────────
@router.post("/stok/safety/{produk_id}", response_model=SafetyStockDetailResponse)
async def post_stok_safety(
    produk_id: int,
    lead_time: int = Query(3, description="Lead time pemesanan dalam hari"),
    service_level: float = Query(0.95, description="Service level target (0.90, 0.95, 0.99)"),
    db: Session = Depends(get_db)
):
    """
    Kalkulasi detail Safety Stock dan Reorder Point untuk satu produk tertentu.
    Bisa digunakan admin Laravel untuk mensimulasikan parameter lead time sebelum memesan.
    """
    try:
        # Cari barang
        query_barang = text("SELECT barang_id, nama, stok FROM barang WHERE barang_id = :b_id")
        res_barang = db.execute(query_barang, {"b_id": produk_id}).fetchone()
        
        if not res_barang:
            raise HTTPException(status_code=404, detail=f"Produk dengan ID {produk_id} tidak ditemukan.")
            
        df_all = calculate_stock_metrics(db, lead_time, service_level)
        row_prod = df_all[df_all['barang_id'] == produk_id]
        
        if row_prod.empty:
            raise HTTPException(status_code=404, detail=f"Gagal memproses data untuk produk ID {produk_id}.")
            
        row = row_prod.iloc[0]
        
        return SafetyStockDetailResponse(
            barang_id=int(row['barang_id']),
            nama=str(row['nama']),
            lead_time=lead_time,
            service_level=service_level,
            z_factor=get_z_factor(service_level),
            rata_rata_permintaan=float(row['prediksi_permintaan_harian']),
            standar_deviasi=float(row['standar_deviasi']),
            safety_stock=float(row['safety_stock']),
            reorder_point=float(row['reorder_point'])
        )
    except HTTPException as he:
        raise he
    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=f"Gagal memproses safety stock produk: {str(e)}"
        )
