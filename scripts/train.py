"""
Training script for Fraud Detection using Isolation Forest.
"""

import os
import joblib
import pandas as pd
from datetime import datetime
from sklearn.ensemble import IsolationForest
from loguru import logger

# Configuration
DATA_FILE = "data/raw/fraud_data.csv"
MODEL_OUTPUT_DIR = "models/saved"
MODEL_NAME = "model_fraud.joblib"
RANDOM_STATE = 42

PAYMENT_MAPPING = {'cash': 0, 'qris': 1, 'transfer': 2, 'wallet': 3}

def train():
    logger.info("Starting fraud detection training pipeline...")

    if not os.path.exists(DATA_FILE):
        logger.error(f"Data file not found: {DATA_FILE}")
        logger.info("Run `python scripts/generate_fraud_data.py` first.")
        return

    # 1. Load Data
    df = pd.read_csv(DATA_FILE)
    logger.info(f"Loaded {len(df)} transactions.")

    # 2. Extract Features
    # Format: [total_harga, jam_transaksi, hari_transaksi, payment_method_encoded]
    features = []
    labels = []
    
    for _, row in df.iterrows():
        try:
            dt = datetime.strptime(row['created_at'], "%Y-%m-%d %H:%M:%S")
            jam = dt.hour
            hari = dt.weekday()
            
            pay_method = str(row['payment_method']).lower()
            pay_encoded = PAYMENT_MAPPING.get(pay_method, 0)
            
            features.append([float(row['total_harga']), jam, hari, pay_encoded])
            labels.append(row.get('is_anomaly', 0))
        except Exception as e:
            continue

    X = pd.DataFrame(features, columns=['total_harga', 'jam', 'hari', 'payment_encoded'])
    logger.info(f"Features engineered: {X.shape}")

    # 3. Train Isolation Forest
    # Contamination defines the proportion of outliers in the dataset.
    contamination = max(0.01, sum(labels) / len(labels) if len(labels) > 0 else 0.05)
    model = IsolationForest(
        n_estimators=100, 
        contamination=contamination,
        random_state=RANDOM_STATE
    )
    
    logger.info(f"Training model with contamination={contamination:.4f}...")
    model.fit(X)
    logger.success("Model trained successfully!")

    # 4. Evaluate (Sanity Check)
    preds = model.predict(X)
    # Isolation forest predicts -1 for anomalies, 1 for normal
    pred_anomalies = (preds == -1).sum()
    logger.info(f"Model detected {pred_anomalies} anomalies out of {len(X)} records.")

    # 5. Save Model
    os.makedirs(MODEL_OUTPUT_DIR, exist_ok=True)
    save_path = os.path.join(MODEL_OUTPUT_DIR, MODEL_NAME)
    joblib.dump(model, save_path)
    logger.success(f"Model saved to {save_path}")

if __name__ == "__main__":
    train()
