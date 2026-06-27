"""Prediction endpoint."""

from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from typing import Any, Dict, List, Optional

router = APIRouter()


class PredictRequest(BaseModel):
    features: List[float]
    model_name: Optional[str] = "default"


class PredictResponse(BaseModel):
    prediction: Any
    probability: Optional[List[float]] = None
    model_name: str
    status: str = "success"


@router.post("/", response_model=PredictResponse)
async def predict(request: PredictRequest):
    """
    Run prediction using the ML model.
    Replace the dummy logic with your actual model inference.
    """
    try:
        # TODO: Load model from app/services/model_service.py
        # result = model_service.predict(request.features)

        # Dummy response for demonstration
        prediction = sum(request.features) / len(request.features)

        return PredictResponse(
            prediction=prediction,
            model_name=request.model_name,
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
