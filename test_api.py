#!/usr/bin/env python
"""Quick test script untuk behavior anomaly API"""

import requests
import json

BASE_URL = "http://localhost:5610/api/v1"

print("=" * 60)
print("TEST 1: Get User Profile (User 1)")
print("=" * 60)
response = requests.get(f"{BASE_URL}/behavior-anomaly/profile/1")
print(json.dumps(response.json(), indent=2))

print("\n" + "=" * 60)
print("TEST 2: Check Anomaly - Volume Spike")
print("=" * 60)
payload = {
    "user_id": 1,
    "category_id": 2,
    "volume": 50,
    "total_price": 2500000,
    "transaction_hour": 14,
    "transaction_id": 999
}
response = requests.post(f"{BASE_URL}/behavior-anomaly/check", json=payload)
print(json.dumps(response.json(), indent=2))

print("\n" + "=" * 60)
print("TEST 3: Check Anomaly - Normal Transaction")
print("=" * 60)
payload = {
    "user_id": 1,
    "category_id": 2,
    "volume": 2,
    "total_price": 50000,
    "transaction_hour": 14,
    "transaction_id": 998
}
response = requests.post(f"{BASE_URL}/behavior-anomaly/check", json=payload)
print(json.dumps(response.json(), indent=2))

print("\n" + "=" * 60)
print("TEST 4: Check Anomaly - Price Spike + Unusual Hour")
print("=" * 60)
payload = {
    "user_id": 1,
    "category_id": 1,
    "volume": 1,
    "total_price": 50000000,
    "transaction_hour": 2,
    "transaction_id": 997
}
response = requests.post(f"{BASE_URL}/behavior-anomaly/check", json=payload)
print(json.dumps(response.json(), indent=2))

print("\n✅ All tests completed!")
