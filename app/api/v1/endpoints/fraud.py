from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from datetime import datetime
from typing import Optional
from app.services.model_service import model_service

router = APIRouter()

# Schema Input Request
class FraudCheckRequest(BaseModel):
    transaction_id: int
    user_id: int
    total_harga: float
    payment_method: str
    created_at: str

# Schema Output Response
class FraudCheckResponse(BaseModel):
    transaction_id: int
    status: str            # "normal" atau "suspicious"
    fraud_score: float     # Nilai tingkat keanehan (anomaly score)
    reason: str            # Alasan deteksi

# Mapping metode pembayaran ke angka
PAYMENT_MAPPING = {'cash': 0, 'qris': 1, 'transfer': 2, 'wallet': 3}

@router.post("/check", response_model=FraudCheckResponse)
async def check_fraud(request: FraudCheckRequest):
    try:
        # 1. Parsing waktu transaksi
        try:
            dt = datetime.strptime(request.created_at, "%Y-%m-%d %H:%M:%S")
        except ValueError:
            raise HTTPException(
                status_code=400, 
                detail="Format created_at salah. Gunakan format 'YYYY-MM-DD HH:MM:SS'"
            )
        
        # 2. Ekstraksi Fitur
        jam = dt.hour
        hari = dt.weekday()

        
        # 3. Label Encoding metode pembayaran
        pay_method = request.payment_method.lower()
        if pay_method not in PAYMENT_MAPPING:
            raise HTTPException(
                status_code=400, 
                detail=f"payment_method tidak valid. Harus salah satu dari: {list(PAYMENT_MAPPING.keys())}"
            )
        pay_encoded = PAYMENT_MAPPING[pay_method]
        
        # 4. Gabungkan fitur ke array X
        # Sesuai urutan saat training: [total_harga, jam_transaksi, hari_transaksi, payment_method_encoded]
        features = [request.total_harga, jam, hari, pay_encoded]
        
        # 5. Load model dan jalankan prediksi
        # model_service.predict akan memuat 'model_fraud.joblib' secara otomatis
        prediction = model_service.predict(features, model_name="model_fraud.joblib")
        
        # Isolation Forest:
        # -1 = Anomali (suspicious)
        #  1 = Normal (normal)
        if prediction == -1:
            status = "suspicious"
            
            # Cari tahu alasan deteksi anomali
            reasons = []
            if request.total_harga > 1000000:
                reasons.append("nominal transaksi ekstrem (> Rp 1.000.000)")
            if jam < 5 or jam > 22:
                reasons.append(f"transaksi terjadi pada jam mencurigakan ({jam:02d}:00)")
            
            reason = "Terdeteksi anomali: " + ", ".join(reasons) if reasons else "Pola transaksi tidak wajar (deteksi otomatis AI)"
            fraud_score = 0.85  # Default threshold representasi untuk anomali
        else:
            status = "normal"
            reason = "Transaksi normal dan aman"
            fraud_score = 0.15
            
        return FraudCheckResponse(
            transaction_id=request.transaction_id,
            status=status,
            fraud_score=fraud_score,
            reason=reason
        )
        
    except HTTPException as he:
        raise he
    except Exception as e:
        raise HTTPException(
            status_code=500, 
            detail=f"Terjadi kesalahan pada server AI: {str(e)}"
        )
