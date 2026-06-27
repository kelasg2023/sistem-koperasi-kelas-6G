"""
Data preprocessing utilities.
"""

import numpy as np
import pandas as pd
from sklearn.preprocessing import StandardScaler, LabelEncoder
from loguru import logger
from typing import Tuple


def load_csv(filepath: str) -> pd.DataFrame:
    """Load a CSV file into a DataFrame."""
    logger.info(f"Loading data from {filepath}")
    return pd.read_csv(filepath)


def split_features_labels(
    df: pd.DataFrame, target_col: str
) -> Tuple[pd.DataFrame, pd.Series]:
    """Split DataFrame into features X and target y."""
    X = df.drop(columns=[target_col])
    y = df[target_col]
    return X, y


def scale_features(X_train: np.ndarray, X_test: np.ndarray):
    """Fit scaler on train, transform both sets."""
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)
    return X_train_scaled, X_test_scaled, scaler


def encode_labels(y: pd.Series) -> Tuple[np.ndarray, LabelEncoder]:
    """Encode categorical labels to integers."""
    le = LabelEncoder()
    y_encoded = le.fit_transform(y)
    return y_encoded, le
