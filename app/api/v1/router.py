from app.api.v1.endpoints import predict, health, models, fraud

from fastapi import APIRouter

api_router = APIRouter()

api_router.include_router(health.router, prefix="/health", tags=["Health"])
api_router.include_router(predict.router, prefix="/predict", tags=["Prediction"])
api_router.include_router(models.router, prefix="/models", tags=["Models"])
api_router.include_router(fraud.router, prefix="/fraud", tags=["Fraud"])

