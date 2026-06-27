"""
Evaluation script — evaluates a saved model on test data.

Usage:
    python scripts/evaluate.py
"""

import joblib
import numpy as np
from sklearn.metrics import (
    accuracy_score,
    classification_report,
    confusion_matrix,
)
from sklearn.model_selection import train_test_split
from loguru import logger


MODEL_PATH = "models/saved/model.joblib"
RANDOM_STATE = 42


def evaluate():
    logger.info("Loading model...")
    model = joblib.load(MODEL_PATH)

    # TODO: Replace with your actual test dataset
    from sklearn.datasets import load_iris
    data = load_iris()
    X, y = data.data, data.target
    _, X_test, _, y_test = train_test_split(
        X, y, test_size=0.2, random_state=RANDOM_STATE
    )

    y_pred = model.predict(X_test)

    acc = accuracy_score(y_test, y_pred)
    logger.info(f"Accuracy: {acc:.4f}")
    print("\nClassification Report:")
    print(classification_report(y_test, y_pred, target_names=data.target_names))
    print("\nConfusion Matrix:")
    print(confusion_matrix(y_test, y_pred))


if __name__ == "__main__":
    evaluate()
