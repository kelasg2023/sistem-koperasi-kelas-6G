"""Models management endpoint."""

from fastapi import APIRouter
from typing import List

router = APIRouter()


@router.get("/")
async def list_models() -> List[str]:
    """List all available models."""
    # TODO: Scan models/saved directory for available models
    return ["model_v1", "model_v2"]


@router.get("/{model_name}")
async def get_model_info(model_name: str):
    """Get metadata for a specific model."""
    return {
        "name": model_name,
        "version": "1.0.0",
        "type": "classification",
        "status": "active",
    }
