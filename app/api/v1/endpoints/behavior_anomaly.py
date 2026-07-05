"""
Endpoint untuk Behavior Anomaly Detection API.

Fitur: Deteksi "Perubahan Perilaku Drastis"
- User Profiling: analisis pattern pembelian user sebelumnya
- Anomaly Detection: apakah transaksi ini menyimpang drastis dari normal behavior
- Contoh: User biasanya beli jajan ribuan → tiba-tiba beli 50 seragam

Database Mapping:
- user_id: dari users table
- category_id: dari kategori table
- transaction_id (optional): untuk audit trail
"""

from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from datetime import datetime
from typing import Optional
from app.services.model_service import model_service
import numpy as np
import pandas as pd
from sqlalchemy import text
import logging

logger = logging.getLogger(__name__)

router = APIRouter()


# ── Pydantic Models ──────────────────────────────────────────────────────────

class BehaviorAnomalyRequest(BaseModel):
    """Request untuk behavior anomaly check."""
    user_id: int
    category_id: int
    volume: int
    total_price: float
    transaction_hour: int = 12
    transaction_date: str = "2025-01-01"
    transaction_id: Optional[int] = None


class BehaviorAnomalyResponse(BaseModel):
    """Response untuk behavior anomaly check."""
    transaction_id: Optional[int] = None
    user_id: int
    category_id: int
    status: str                    # "normal" atau "anomaly"
    anomaly_score: float           # Semakin tinggi = semakin anomali (-1 to 1)
    confidence: float              # Confidence level (0-1)
    reason: str                    # Penjelasan deteksi
    user_profile: dict             # User behavior profile
    suspicious_indicators: list    # List of detected anomalies


# ── Helper Functions ────────────────────────────────────────────────────────

def get_user_profile(user_id: int):
    """
    Ambil user behavior profile dari database MySQL.

    Query dari transactions + transaction_details + barang + kategori
    untuk menghitung:
    - Average transaction value
    - Category preference
    - Typical volume
    - Typical price range
    """
    try:
        from app.core.database import engine

        # Query 1: Overall statistics
        query_overall = text("""
        SELECT 
            AVG(t.total_harga) as avg_total_price,
            STDDEV_POP(t.total_harga) as std_total_price,
            COUNT(DISTINCT t.transaction_id) as transaction_count
        FROM transactions t
        WHERE t.user_id = :user_id AND t.status = 'berhasil'
        """)

        # Query 2: Per-category statistics
        query_category = text("""
        SELECT 
            b.id_kategori,
            COUNT(DISTINCT t.transaction_id) as category_count,
            AVG(td.jumlah) as avg_volume
        FROM transactions t
        JOIN transaction_details td ON t.transaction_id = td.transaction_id
        JOIN barang b ON td.barang_id = b.barang_id
        WHERE t.user_id = :user_id AND t.status = 'berhasil'
        GROUP BY b.id_kategori
        ORDER BY category_count DESC
        """)

        with engine.connect() as conn:
            # Get overall stats
            result_overall = conn.execute(
                query_overall, {"user_id": user_id}).fetchone()

            # Get category stats
            result_categories = conn.execute(
                query_category, {"user_id": user_id}).fetchall()

        # Process results
        avg_price = float(
            result_overall[0]) if result_overall and result_overall[0] else 50000
        std_price = float(
            result_overall[1]) if result_overall and result_overall[1] else 20000
        transaction_count = int(
            result_overall[2]) if result_overall and result_overall[2] else 0

        # Jika std_price adalah 0, set ke default
        if std_price == 0 or std_price is None:
            std_price = 20000

        # Calculate category frequency
        total_transactions = sum(
            [row[1] for row in result_categories]) if result_categories else 1
        category_frequency = {}
        primary_category_id = None
        primary_count = 0

        for row in result_categories:
            cat_id, cat_count, avg_vol = int(row[0]), int(
                row[1]), float(row[2])  # Convert to proper types
            category_frequency[str(cat_id)] = cat_count / \
                total_transactions if total_transactions > 0 else 0
            if cat_count > primary_count:
                primary_count = cat_count
                primary_category_id = cat_id

        # Calculate average volume
        avg_volume = 2  # default
        std_volume = 1  # default
        if result_categories:
            # Convert Decimal to float
            volumes = [float(row[2]) for row in result_categories]
            avg_volume = sum(volumes) / len(volumes) if volumes else 2
            if volumes:
                variance = sum(
                    [(v - avg_volume) ** 2 for v in volumes]) / len(volumes)
                std_volume = variance ** 0.5
            else:
                std_volume = 1

        profile = {
            'avg_total_price': float(avg_price),
            'std_total_price': float(std_price),
            'avg_volume': float(avg_volume),
            'std_volume': float(std_volume),
            'primary_category_id': primary_category_id,
            'category_frequency': category_frequency,
            'transaction_count': transaction_count,
        }

        logger.info(
            f"User {user_id} profile: {transaction_count} transaksi, kategori utama: {primary_category_id}")
        return profile

    except Exception as e:
        logger.error(
            f"Failed to get user profile for user {user_id}: {str(e)}", exc_info=True)
        # Default fallback profile
        return {
            'avg_total_price': 50000,
            'std_total_price': 20000,
            'avg_volume': 2,
            'std_volume': 1,
            'primary_category_id': None,
            'category_frequency': {},
            'transaction_count': 0,
            'error': str(e),
        }


def engineer_features_for_prediction(
    user_id: int,
    category_id: int,
    volume: int,
    total_price: float,
    transaction_hour: int,
    user_profile: dict
) -> np.ndarray:
    """
    Engineer features untuk prediksi.

    Features (sesuai training):
    0. norm_price: (total_price - user_avg) / user_std
    1. norm_volume: (volume - user_avg_vol) / user_std_vol
    2. cat_freq: category frequency (0-1)
    3. time_score: suspicious hour (0-1)
    4. log_price_per_vol: log(total_price / volume)
    5. category_shift: apakah kategori berbeda dari usual
    6. volume_spike: normalized volume
    7. price_spike: normalized price
    """

    # 0. Normalized price
    avg_price = user_profile.get('avg_total_price', 50000)
    std_price = user_profile.get('std_total_price', 20000)

    if std_price > 0:
        norm_price = abs((total_price - avg_price) / std_price)
    else:
        norm_price = 0

    # 1. Normalized volume
    avg_volume = user_profile.get('avg_volume', 2)
    std_volume = user_profile.get('std_volume', 1)

    if std_volume > 0:
        norm_volume = abs((volume - avg_volume) / std_volume)
    else:
        norm_volume = 0

    # 2. Category frequency
    cat_freq = user_profile.get(
        'category_frequency', {}).get(str(category_id), 0.1)

    # 3. Time score (jam mencurigakan)
    time_score = 1.0 if (
        transaction_hour < 5 or transaction_hour > 22) else 0.0

    # 4. Log price per volume
    try:
        price_per_vol = total_price / volume if volume > 0 else total_price
        log_price_per_vol = np.log1p(price_per_vol)
    except:
        log_price_per_vol = 0

    # 5. Category shift (simplified: assume dari user profile)
    primary_category = user_profile.get('primary_category_id')
    category_shift = 0.0 if category_id == primary_category else 1.0

    # 6-7. Volume spike & Price spike (same as norm values)
    volume_spike = norm_volume
    price_spike = norm_price

    features = np.array([
        norm_price,           # 0
        norm_volume,          # 1
        cat_freq,             # 2
        time_score,           # 3
        log_price_per_vol,    # 4
        category_shift,       # 5
        volume_spike,         # 6
        price_spike,          # 7
    ]).reshape(1, -1)

    return features


def analyze_anomaly_indicators(
    user_profile: dict,
    category_id: int,
    volume: int,
    total_price: float,
    transaction_hour: int,
) -> list:
    """Analisis indikator anomali spesifik."""
    indicators = []

    # 1. Price spike
    avg_price = user_profile.get('avg_total_price', 50000)
    std_price = user_profile.get('std_total_price', 20000)
    if std_price > 0 and total_price > avg_price + (3 * std_price):
        indicators.append(
            f"Lompatan harga drastis: Rp{total_price:,.0f} vs rata-rata Rp{avg_price:,.0f}")

    # 2. Volume spike
    avg_volume = user_profile.get('avg_volume', 2)
    std_volume = user_profile.get('std_volume', 1)
    if std_volume > 0 and volume > avg_volume + (5 * std_volume):
        indicators.append(
            f"Volume pembelian melonjak: {volume} item vs rata-rata {avg_volume:.1f} item")

    # 3. Unusual hour
    if transaction_hour < 5 or transaction_hour > 22:
        indicators.append(
            f"Transaksi pada jam mencurigakan: {transaction_hour:02d}:00")

    # 4. Category shift
    primary_category = user_profile.get('primary_category_id')
    if primary_category and category_id != primary_category:
        indicators.append(
            f"Kategori tidak sesuai usual pattern (Category ID: {category_id})")

    # 5. Extreme price per volume
    try:
        price_per_unit = total_price / volume if volume > 0 else 0
        if price_per_unit > 1000000:
            indicators.append(
                f"Harga per unit ekstrem: Rp{price_per_unit:,.0f}")
    except:
        pass

    return indicators


@router.post("/check", response_model=BehaviorAnomalyResponse)
async def check_behavior_anomaly(request: BehaviorAnomalyRequest):
    """
    Check apakah ada perubahan perilaku drastis pada transaksi user.

    Request body:
    {
      "user_id": 1,
      "category_id": 2,
      "volume": 50,
      "total_price": 2500000,
      "transaction_hour": 14,
      "transaction_date": "2025-01-05"
    }

    Response:
    {
      "status": "anomaly",
      "anomaly_score": 0.92,
      "confidence": 0.87,
      "reason": "Terdeteksi lompatan drastis di volume pembelian",
      "suspicious_indicators": [...]
    }
    """

    try:
        # 1. Validasi input
        if request.user_id <= 0:
            raise HTTPException(
                status_code=400, detail="user_id harus positif")

        if request.category_id <= 0:
            raise HTTPException(
                status_code=400, detail="category_id harus positif")

        if request.volume <= 0:
            raise HTTPException(status_code=400, detail="volume harus positif")

        if request.total_price <= 0:
            raise HTTPException(
                status_code=400, detail="total_price harus positif")

        if not (0 <= request.transaction_hour < 24):
            raise HTTPException(
                status_code=400, detail="transaction_hour harus 0-23")

        # 2. Get user profile dari database
        user_profile = get_user_profile(request.user_id)

        # 3. Engineer features
        features = engineer_features_for_prediction(
            user_id=request.user_id,
            category_id=request.category_id,
            volume=request.volume,
            total_price=request.total_price,
            transaction_hour=request.transaction_hour,
            user_profile=user_profile,
        )

        # 4. Scale features
        scaler = model_service.load_model("scaler_behavior_anomaly.joblib")
        if scaler is None:
            raise HTTPException(
                status_code=500,
                detail="Scaler model tidak ditemukan. Jalankan training terlebih dahulu."
            )

        try:
            features_scaled = scaler.transform(features)
        except Exception as e:
            logger.error(f"Scaling error: {e}")
            features_scaled = features

        # 5. Predict dengan Isolation Forest
        model = model_service.load_model("model_behavior_anomaly.joblib")
        if model is None:
            raise HTTPException(
                status_code=500,
                detail="Model tidak ditemukan. Jalankan training terlebih dahulu."
            )

        prediction = model.predict(features_scaled)[0]
        anomaly_score = model.score_samples(features_scaled)[0]

        # Isolation Forest: -1 = anomali, 1 = normal
        is_anomaly = prediction == -1

        # Normalize anomaly score ke 0-1 (biasanya -1 to 1, map ke 0-1)
        normalized_score = (1 - anomaly_score) / 2  # Rough normalization
        normalized_score = max(0, min(1, normalized_score))

        # 6. Analyze indicators
        indicators = analyze_anomaly_indicators(
            user_profile=user_profile,
            category_id=request.category_id,
            volume=request.volume,
            total_price=request.total_price,
            transaction_hour=request.transaction_hour,
        )

        # 7. Generate response
        if is_anomaly:
            status = "anomaly"
            reason = "Terdeteksi PERUBAHAN PERILAKU DRASTIS pada pola pembelian."
            if indicators:
                reason += " Indikator: " + " | ".join(indicators)
            confidence = normalized_score
        else:
            status = "normal"
            reason = "Pola pembelian normal dan sesuai dengan behavior user."
            confidence = 1 - normalized_score

        return BehaviorAnomalyResponse(
            transaction_id=request.transaction_id,
            user_id=request.user_id,
            category_id=request.category_id,
            status=status,
            anomaly_score=float(normalized_score),
            confidence=float(confidence),
            reason=reason,
            user_profile=user_profile,
            suspicious_indicators=indicators,
        )

    except HTTPException as he:
        raise he

    except Exception as e:
        logger.error(f"Prediction error: {e}", exc_info=True)
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan pada server AI: {str(e)}"
        )


@router.get("/profile/{user_id}")
async def get_user_behavior_profile(user_id: int):
    """Get user behavior profile from database."""
    try:
        profile = get_user_profile(user_id)
        return {
            "user_id": user_id,
            "profile": profile,
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
