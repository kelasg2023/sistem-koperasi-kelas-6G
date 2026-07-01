from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from sqlalchemy import text
from datetime import datetime, timedelta
import pandas as pd
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from typing import List, Optional
from pydantic import BaseModel

from app.core.database import get_db

router = APIRouter()

# ── Schemas ──────────────────────────────────────────────────────────────────
class ProdukLarisResponse(BaseModel):
    barang_id: int
    nama: str
    nama_kategori: str
    total_terjual: int
    skor_popularitas: float # skala 0.0 - 100.0

class RekomendasiResponse(BaseModel):
    barang_id: int
    nama: str
    id_kategori: int
    keterangan: str

# ── Helper parsing periode ────────────────────────────────────────────────────
def parse_periode(periode: str) -> datetime:
    """Mengubah format '7d', '30d' dll menjadi datetime batas awal."""
    hari = 7 # default 7 hari
    if periode.endswith("d"):
        try:
            hari = int(periode[:-1])
        except ValueError:
            pass
    elif periode.isdigit():
        hari = int(periode)
        
    return datetime.now() - timedelta(days=hari)

# ── Endpoint 1: Estimasi / Rekomendasi Produk Laris ──────────────────────────
@router.get("/produk/laris", response_model=List[ProdukLarisResponse])
async def get_produk_laris(
    periode: str = Query("7d", description="Periode waktu (contoh: '7d', '30d')"),
    limit: int = Query(5, description="Jumlah produk teratas yang ingin diambil"),
    db: Session = Depends(get_db)
):
    """
    Mendapatkan daftar produk terlaris dalam periode waktu tertentu.
    Logika rule-based/query-based real-time dari database.
    """
    try:
        start_date = parse_periode(periode)
        
        # Query SQL gabungan untuk menjumlahkan qty per barang
        query = text("""
            SELECT 
                b.barang_id,
                b.nama,
                k.nama_kategori,
                SUM(td.jumlah) as total_terjual
            FROM transaction_details td
            JOIN transactions t ON td.transaction_id = t.transaction_id
            JOIN barang b ON td.barang_id = b.barang_id
            JOIN kategori k ON b.id_kategori = k.id_kategori
            WHERE t.created_at >= :start_date AND t.status = 'berhasil'
            GROUP BY b.barang_id, b.nama, k.nama_kategori
            ORDER BY total_terjual DESC
            LIMIT :limit
        """)
        
        results = db.execute(query, {"start_date": start_date, "limit": limit}).fetchall()
        
        if not results:
            return []
            
        # Cari penjualan tertinggi untuk normalisasi skor popularitas
        max_sold = float(results[0][3]) if float(results[0][3]) > 0 else 1.0
        
        response = []
        for r in results:
            total_terjual = int(r[3])
            # Skor popularitas dinormalisasi (0 - 100)
            skor = round((total_terjual / max_sold) * 100.0, 1)
            
            response.append(ProdukLarisResponse(
                barang_id=r[0],
                nama=r[1],
                nama_kategori=r[2],
                total_terjual=total_terjual,
                skor_popularitas=skor
            ))
            
        return response
        
    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan saat menghitung produk laris: {str(e)}"
        )

# ── Endpoint 2: Rekomendasi Personal per Anggota ──────────────────────────────
@router.post("/rekomendasi/anggota/{anggota_id}", response_model=List[RekomendasiResponse])
async def get_rekomendasi_anggota(
    anggota_id: int,
    num_recommendations: int = Query(3, description="Jumlah rekomendasi yang diberikan"),
    db: Session = Depends(get_db)
):
    """
    Rekomendasi produk personal menggunakan Collaborative Filtering (Cosine Similarity).
    Dihitung secara dinamis (on-the-fly) dari histori transaksi database.
    """
    try:
        # 1. Tarik semua data detail transaksi dari database untuk bahan perhitungan
        query = text("""
            SELECT 
                t.user_id,
                td.barang_id,
                b.nama as nama_barang,
                b.id_kategori,
                td.jumlah
            FROM transaction_details td
            JOIN transactions t ON td.transaction_id = t.transaction_id
            JOIN barang b ON td.barang_id = b.barang_id
            WHERE t.status = 'berhasil'
        """)
        
        results = db.execute(query).fetchall()
        
        if not results:
            raise HTTPException(
                status_code=404,
                detail="Belum ada data transaksi di database untuk menghitung rekomendasi."
            )
            
        # 2. Convert ke Pandas DataFrame
        df = pd.DataFrame(results, columns=['user_id', 'barang_id', 'nama_barang', 'id_kategori', 'jumlah'])
        
        # 3. Buat pivot table (User-Item Matrix)
        user_item_matrix = df.pivot_table(
            index='user_id',
            columns='barang_id',
            values='jumlah',
            aggfunc='sum'
        ).fillna(0)
        
        # 4. Ambil daftar barang untuk mapping ID ke Nama
        barang_map = df[['barang_id', 'nama_barang', 'id_kategori']].drop_duplicates().set_index('barang_id').to_dict('index')
        
        # ── KONDISI COLD START (Anggota Baru / Belanjar Belum Pernah Ada) ──
        if anggota_id not in user_item_matrix.index:
            # Berikan produk terpopuler secara umum
            popular_items = df.groupby('barang_id')['jumlah'].sum().sort_values(ascending=False).index[:num_recommendations]
            
            response = []
            for b_id in popular_items:
                b_info = barang_map[b_id]
                response.append(RekomendasiResponse(
                    barang_id=b_id,
                    nama=b_info['nama_barang'],
                    id_kategori=b_info['id_kategori'],
                    keterangan="Rekomendasi produk populer (Anggota baru)"
                ))
            return response
            
        # 5. Jalankan Cosine Similarity antar user
        user_similarity = cosine_similarity(user_item_matrix)
        user_similarity_df = pd.DataFrame(
            user_similarity,
            index=user_item_matrix.index,
            columns=user_item_matrix.index
        )
        
        # 6. Cari Top 5 user yang paling mirip selera belanjanya
        similar_users = user_similarity_df[anggota_id].sort_values(ascending=False).index[1:6]
        
        # 7. Ambil transaksi dari user yang mirip tersebut
        similar_users_purchases = user_item_matrix.loc[similar_users]
        
        # 8. Hitung rata-rata ketertarikan (weighted score)
        scores = similar_users_purchases.mean().sort_values(ascending=False)
        
        # 9. Saring produk yang belum pernah dibeli oleh target anggota
        user_already_bought = user_item_matrix.loc[anggota_id]
        items_to_recommend = scores[user_already_bought == 0]
        
        # Ambil sejumlah rekomendasi yang diminta
        final_recommendations = items_to_recommend.index[:num_recommendations]
        
        response = []
        for b_id in final_recommendations:
            b_info = barang_map[b_id]
            response.append(RekomendasiResponse(
                barang_id=b_id,
                nama=b_info['nama_barang'],
                id_kategori=b_info['id_kategori'],
                keterangan="Direkomendasikan berdasarkan kemiripan pola belanja Anda"
            ))
            
        # Jika rekomendasi kurang karena user sudah membeli hampir semua produk, tambahkan produk populer sisanya
        if len(response) < num_recommendations:
            popular_items = df.groupby('barang_id')['jumlah'].sum().sort_values(ascending=False).index
            for b_id in popular_items:
                if len(response) >= num_recommendations:
                    break
                if b_id not in final_recommendations and user_already_bought[b_id] == 0:
                    b_info = barang_map[b_id]
                    response.append(RekomendasiResponse(
                        barang_id=b_id,
                        nama=b_info['nama_barang'],
                        id_kategori=b_info['id_kategori'],
                        keterangan="Rekomendasi produk populer tambahan"
                    ))
                    
        return response
        
    except HTTPException as he:
        raise he
    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan saat menghitung rekomendasi: {str(e)}"
        )
