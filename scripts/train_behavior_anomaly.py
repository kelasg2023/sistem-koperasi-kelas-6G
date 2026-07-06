"""
Training script untuk Behavior Anomaly Detection.

Model: Isolation Forest + feature engineering dari user profile
Output: model_behavior_anomaly.joblib

Features:
- normalized_total_price: harga dibanding rata-rata user
- normalized_volume: volume dibanding rata-rata user
- category_frequency: berapa kali kategori ini digunakan oleh user
- time_interval_hours: jam transaksi (unusual hours: 0-6, 22-23 = suspicious)
- price_per_volume: harga per unit
"""

import os
import joblib
import numpy as np
import pandas as pd
from sklearn.ensemble import IsolationForest
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import classification_report, confusion_matrix, roc_auc_score
from loguru import logger

# ── Konfigurasi ──────────────────────────────────────────────────────────────
DATA_INPUT = "data/raw/behavior_anomaly_data.csv"
MODEL_OUTPUT_DIR = "models/saved"
MODEL_NAME = "model_fraud.joblib"
SCALER_NAME = "scaler_fraud.joblib"
RANDOM_STATE = 42


def engineer_features(df):
    """
    Feature engineering untuk behavior anomaly detection.

    Features:
    1. normalized_total_price: z-score harga per user
    2. normalized_volume: z-score volume per user
    3. category_frequency: frekuensi kategori per user (0-1)
    4. time_suspension_score: anomaly di jam transaksi
    5. price_per_volume: harga satuan
    6. category_shift_score: jika kategori berbeda dari usual
    """

    features_list = []

    for user_id in df['user_id'].unique():
        user_data = df[df['user_id'] == user_id].copy()

        # User statistics
        user_avg_price = user_data['total_price'].mean()
        user_std_price = user_data['total_price'].std()
        user_avg_volume = user_data['volume'].mean()
        user_std_volume = user_data['volume'].std()

        # Category preference
        category_counts = user_data['category_id'].value_counts()
        primary_category = category_counts.index[0]
        primary_category_freq = category_counts.iloc[0] / len(user_data)

        # Process each transaction
        for idx, row in user_data.iterrows():
            try:
                # 1. Normalized total price (z-score)
                if user_std_price > 0:
                    norm_price = abs(
                        (row['total_price'] - user_avg_price) / user_std_price)
                else:
                    norm_price = 0

                # 2. Normalized volume (z-score)
                if user_std_volume > 0:
                    norm_volume = abs(
                        (row['volume'] - user_avg_volume) / user_std_volume)
                else:
                    norm_volume = 0

                # 3. Category frequency for this category
                cat_freq = category_counts.get(
                    row['category_id'], 0) / len(user_data)

                # 4. Time suspension score (jam mencurigakan: 0-5, 23)
                hour = row['transaction_hour']
                time_score = 1.0 if (hour < 5 or hour > 22) else 0.0

                # 5. Price per volume
                price_per_vol = row['unit_price']

                # 6. Category shift score (apakah kategori berbeda dari primary)
                category_shift = 0.0 if row['category_id'] == primary_category else 1.0

                # 7. Volume spike ratio
                volume_spike = norm_volume

                # 8. Price spike ratio
                price_spike = norm_price

                feature_vector = [
                    norm_price,           # 0
                    norm_volume,          # 1
                    cat_freq,             # 2
                    time_score,           # 3
                    np.log1p(price_per_vol),  # 4 (log transform price)
                    category_shift,       # 5
                    volume_spike,         # 6
                    price_spike,          # 7
                ]

                feature_vector.append(row['is_anomaly'])
                features_list.append(feature_vector)

            except Exception as e:
                logger.warning(f"Skip transaction {idx}: {e}")
                continue

    # Convert to DataFrame
    feature_columns = [
        'norm_price', 'norm_volume', 'cat_freq', 'time_score',
        'log_price_per_vol', 'category_shift', 'volume_spike', 'price_spike',
        'is_anomaly'
    ]

    features_df = pd.DataFrame(features_list, columns=feature_columns)
    logger.info(f"Features engineered: {features_df.shape}")
    logger.info(f"\nFeature statistics:\n{features_df.describe()}")

    return features_df


def train():
    """Train Isolation Forest model for anomaly detection."""

    logger.info("🚀 Starting Behavior Anomaly Detection training pipeline...")

    # ── 1. Load Data ──────────────────────────────────────────────────────────
    if not os.path.exists(DATA_INPUT):
        logger.error(f"Data file not found: {DATA_INPUT}")
        logger.info(
            "Generate data first using: python scripts/generate_behavior_anomaly_data.py")
        return

    df = pd.read_csv(DATA_INPUT)
    logger.info(
        f"✅ Data loaded: {len(df)} transactions from {df['user_id'].nunique()} users")

    # ── 2. Feature Engineering ────────────────────────────────────────────────
    logger.info("\n🔧 Engineering features...")
    features_df = engineer_features(df)

    X = features_df.drop('is_anomaly', axis=1).values
    y = features_df['is_anomaly'].values

    logger.info(f"Features shape: {X.shape}")
    logger.info(f"Anomaly distribution: {np.bincount(y)}")

    # ── 3. Standardize Features ───────────────────────────────────────────────
    logger.info("\n📊 Standardizing features...")
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)

    # ── 4. Train Isolation Forest ─────────────────────────────────────────────
    logger.info("\n🤖 Training Isolation Forest model...")
    # contamination = expected proportion of anomalies
    contamination = y.sum() / len(y)
    logger.info(f"   Contamination rate: {contamination:.2%}")

    model = IsolationForest(
        contamination=contamination,
        random_state=RANDOM_STATE,
        n_estimators=100,
        max_samples='auto',
        n_jobs=-1,
    )

    model.fit(X_scaled)
    logger.success("Model trained!")

    # ── 5. Predict & Evaluate ─────────────────────────────────────────────────
    logger.info("\n📈 Evaluating model...")
    y_pred = model.predict(X_scaled)
    y_pred_binary = (y_pred == -1).astype(int)

    # Confusion matrix
    cm = confusion_matrix(y, y_pred_binary)
    logger.info(f"\nConfusion Matrix:\n{cm}")

    # Classification report
    report = classification_report(
        y, y_pred_binary,
        target_names=['Normal', 'Anomaly'],
        digits=4
    )
    logger.info(f"\nClassification Report:\n{report}")

    # Anomaly scores
    anomaly_scores = model.score_samples(X_scaled)
    logger.info(f"\nAnomaly Scores:")
    logger.info(f"  Mean: {anomaly_scores.mean():.4f}")
    logger.info(f"  Std: {anomaly_scores.std():.4f}")
    logger.info(f"  Min: {anomaly_scores.min():.4f}")
    logger.info(f"  Max: {anomaly_scores.max():.4f}")

    # ── 6. Save Model & Scaler ───────────────────────────────────────────────
    logger.info("\n💾 Saving model and scaler...")
    os.makedirs(MODEL_OUTPUT_DIR, exist_ok=True)

    model_path = os.path.join(MODEL_OUTPUT_DIR, MODEL_NAME)
    scaler_path = os.path.join(MODEL_OUTPUT_DIR, SCALER_NAME)

    joblib.dump(model, model_path)
    joblib.dump(scaler, scaler_path)

    logger.success(f"✅ Model saved: {model_path}")
    logger.success(f"✅ Scaler saved: {scaler_path}")

    # ── 7. Model Info ────────────────────────────────────────────────────────
    logger.info("\n📋 Model Info:")
    logger.info(f"   Contamination: {model.contamination}")
    logger.info(f"   N Estimators: {model.n_estimators}")
    logger.info(f"   Feature Count: {X.shape[1]}")
    logger.info(f"   Training Samples: {X.shape[0]}")


if __name__ == "__main__":
    train()
