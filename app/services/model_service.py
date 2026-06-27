"""
Model service — handles loading and running ML models.
"""

import os
import joblib
from loguru import logger
from app.core.config import settings


class ModelService:
    def __init__(self):
        self.models: dict = {}

    def load_model(self, model_name: str = None):
        """Load a model from disk into memory."""
        name = model_name or settings.MODEL_NAME
        path = os.path.join(settings.MODEL_DIR, name)

        if not os.path.exists(path):
            logger.warning(f"Model not found at {path}")
            return None

        model = joblib.load(path)
        self.models[name] = model
        logger.info(f"Model '{name}' loaded from {path}")
        return model

    def predict(self, features: list, model_name: str = None):
        """Run inference on features."""
        name = model_name or settings.MODEL_NAME

        if name not in self.models:
            self.load_model(name)

        model = self.models.get(name)
        if model is None:
            raise ValueError(f"Model '{name}' is not available.")

        import numpy as np
        X = np.array(features).reshape(1, -1)
        return model.predict(X)[0]


# Singleton instance
model_service = ModelService()
