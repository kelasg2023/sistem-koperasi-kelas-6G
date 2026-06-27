"""Health check endpoint."""
from fastapi import APIRouter

router = APIRouter()


@router.get("/")
async def health():
    return {"status": "healthy"}


@router.get("/ping")
async def ping():
    return {"ping": "pong"}
