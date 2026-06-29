"""Tests for prediction endpoint."""

import pytest
from fastapi.testclient import TestClient
from main import app

client = TestClient(app)


def test_root():
    response = client.get("/")
    assert response.status_code == 200
    assert "message" in response.json()


def test_health():
    response = client.get("/health")
    assert response.status_code == 200
    assert response.json()["status"] == "healthy"


def test_predict():
    payload = {"features": [5.1, 3.5, 1.4, 0.2]}
    response = client.post("/api/v1/predict/", json=payload)
    assert response.status_code == 200
    data = response.json()
    assert "prediction" in data
    assert data["status"] == "success"


def test_fraud_check():
    # Test normal transaction
    payload_normal = {
        "transaction_id": 1,
        "user_id": 10,
        "total_harga": 50000.0,
        "payment_method": "qris",
        "created_at": "2026-06-27 12:00:00"
    }
    response = client.post("/api/v1/fraud/check", json=payload_normal)
    assert response.status_code == 200
    data = response.json()
    assert data["status"] in ["normal", "suspicious"]
    assert "fraud_score" in data

