"""
Training script — trains and saves an ML model.

Usage:
    python scripts/train.py
"""

import os
import joblib
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import classification_report, accuracy_score
from loguru import logger

# ── Config ───────────────────────────────────────────────────────────────────
MODEL_OUTPUT_DIR = "models/saved"
MODEL_NAME = "model.joblib"
RANDOM_STATE = 42


def train():
    logger.info("Starting training pipeline...")

    # ── 1. Load Data ──────────────────────────────────────────────────────────
    # TODO: Replace with your actual data loading logic
    from sklearn.datasets import load_iris
    data = load_iris()
    X, y = data.data, data.target

    # ── 2. Split ──────────────────────────────────────────────────────────────
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=RANDOM_STATE
    )
    logger.info(f"Train: {len(X_train)} | Test: {len(X_test)}")

    # ── 3. Train ──────────────────────────────────────────────────────────────
    model = RandomForestClassifier(n_estimators=100, random_state=RANDOM_STATE)
    model.fit(X_train, y_train)
    logger.success("Model trained!")

    # ── 4. Evaluate ───────────────────────────────────────────────────────────
    y_pred = model.predict(X_test)
    acc = accuracy_score(y_test, y_pred)
    logger.info(f"Accuracy: {acc:.4f}")
    print(classification_report(y_test, y_pred, target_names=data.target_names))

    # ── 5. Save ───────────────────────────────────────────────────────────────
    os.makedirs(MODEL_OUTPUT_DIR, exist_ok=True)
    save_path = os.path.join(MODEL_OUTPUT_DIR, MODEL_NAME)
    joblib.dump(model, save_path)
    logger.success(f"Model saved to {save_path}")


if __name__ == "__main__":
    train()
