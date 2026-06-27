"""
Application configuration using Pydantic BaseSettings.
"""

from typing import List
from pydantic_settings import BaseSettings


class Settings(BaseSettings):
    # ── Project Info ────────────────────────────────────────────────────────
    PROJECT_NAME: str = "ML FastAPI Service"
    VERSION: str = "1.0.0"
    DESCRIPTION: str = "Machine Learning API"

    # ── Server ──────────────────────────────────────────────────────────────
    HOST: str = "0.0.0.0"
    PORT: int = 5610
    DEBUG: bool = True

    # ── API ─────────────────────────────────────────────────────────────────
    API_V1_STR: str = "/api/v1"

    # ── CORS ─────────────────────────────────────────────────────────────────
    ALLOWED_ORIGINS: List[str] = ["*"]

    # ── Database ────────────────────────────────────────────────────────────
    DATABASE_URL: str = "sqlite:///./data/db/app.db"

    # ── ML Model ────────────────────────────────────────────────────────────
    MODEL_DIR: str = "models/saved"
    MODEL_NAME: str = "model.joblib"

    # ── MLflow ──────────────────────────────────────────────────────────────
    MLFLOW_TRACKING_URI: str = "http://localhost:5000"
    MLFLOW_EXPERIMENT_NAME: str = "default"

    class Config:
        env_file = ".env"
        case_sensitive = True


settings = Settings()
