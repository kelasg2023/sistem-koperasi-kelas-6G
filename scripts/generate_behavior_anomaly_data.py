"""
Generate synthetic data untuk Behavior Anomaly Detection.

Konsep: User Profiling & Anomaly Detection
- Deteksi penyimpangan drastis dari behavior harian user
- Feature: kategori produk, nominal belanja, jumlah item, frekuensi kategori
- Normal: transaksi sesuai pattern user
- Anomali: lompatan drastis volume/kategori (contoh: dari beli jajan ribuan jadi beli 50 seragam)

Target:
- Training data dengan label normal (0) dan anomali (1)
- Pastikan data realistic sesuai business logic
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import random

# ── Konfigurasi ──────────────────────────────────────────────────────────────
NUM_USERS = 100
NUM_TRANSACTIONS_PER_USER = 20
OUTPUT_FILE = "data/raw/behavior_anomaly_data.csv"

# Kategori produk (sesuai db.sql kategori table)
CATEGORIES = {
    1: "Makanan & Minuman",
    2: "Pakaian",
    3: "Elektronik",
    4: "Peralatan Rumah Tangga",
    5: "Kesehatan & Kecantikan",
    6: "Mainan & Hobi",
    7: "Buku & Alat Tulis",
    8: "Olahraga",
}

# Price range per kategori (dalam Rp)
PRICE_RANGES = {
    1: (1000, 50000),          # Makanan
    2: (50000, 500000),        # Pakaian
    3: (100000, 5000000),      # Elektronik
    4: (20000, 500000),        # Peralatan Rumah
    5: (10000, 200000),        # Kesehatan
    6: (20000, 300000),        # Mainan
    7: (5000, 100000),         # Buku
    8: (50000, 1000000),       # Olahraga
}

# Volume range per kategori
VOLUME_RANGES = {
    1: (1, 5),      # Makanan
    2: (1, 3),      # Pakaian
    3: (1, 2),      # Elektronik
    4: (1, 2),      # Peralatan
    5: (1, 4),      # Kesehatan
    6: (1, 3),      # Mainan
    7: (1, 5),      # Buku
    8: (1, 3),      # Olahraga
}


def generate_user_profile():
    """Generate user behavior profile."""
    # Setiap user punya preference kategori utama + secondary
    primary_category = random.choice(list(CATEGORIES.keys()))
    secondary_categories = random.sample(
        [c for c in CATEGORIES.keys() if c != primary_category],
        k=random.randint(1, 2)
    )

    return {
        'primary_category': primary_category,
        'secondary_categories': secondary_categories,
        'avg_transaction_value': np.random.uniform(10000, 500000),
    }


def generate_transaction(user_id, user_profile, is_anomaly=False):
    """Generate single transaction."""

    if is_anomaly:
        # ANOMALI: Lompatan drastis dari normal pattern
        anomaly_type = random.choice(
            ['volume_spike', 'category_shift', 'price_spike'])

        if anomaly_type == 'volume_spike':
            # Contoh: biasanya beli 1-3 item pakaian, tiba-tiba beli 50 seragam
            category = user_profile['primary_category']
            volume = random.randint(20, 100)  # Drastis lebih tinggi

        elif anomaly_type == 'category_shift':
            # Contoh: user biasa beli makanan, tiba-tiba belanja elektronik besar-besaran
            category = random.choice([c for c in CATEGORIES.keys(
            ) if c not in user_profile['secondary_categories']])
            volume = random.randint(5, 15)

        else:  # price_spike
            # Contoh: biasanya beli makanan ribuan, tiba-tiba beli gadget jutaan
            category = random.choice(list(CATEGORIES.keys()))
            volume = random.randint(3, 10)

        price_min, price_max = PRICE_RANGES[category]
        # Spike: kalikan dengan faktor 5-10x lipat
        unit_price = random.uniform(price_min * 5, price_max * 2)

    else:
        # NORMAL: Sesuai user profile
        category = random.choices(
            [user_profile['primary_category']] +
            user_profile['secondary_categories'],
            weights=[0.7] + [0.15] * len(user_profile['secondary_categories']),
            k=1
        )[0]

        price_min, price_max = PRICE_RANGES[category]
        unit_price = random.uniform(price_min, price_max)

        vol_min, vol_max = VOLUME_RANGES[category]
        volume = random.randint(vol_min, vol_max)

    total_price = unit_price * volume

    # Timestamp
    days_ago = random.randint(1, 180)
    hour = random.randint(6, 23)
    date = datetime.now() - timedelta(days=days_ago, hours=hour)

    return {
        'user_id': user_id,
        'category_id': category,
        'category_name': CATEGORIES[category],
        'volume': volume,
        'unit_price': unit_price,
        'total_price': total_price,
        'transaction_date': date.strftime('%Y-%m-%d'),
        'transaction_hour': hour,
        'is_anomaly': 1 if is_anomaly else 0,
    }


def generate_dataset():
    """Generate complete dataset."""
    transactions = []

    for user_id in range(1, NUM_USERS + 1):
        user_profile = generate_user_profile()

        # Generate normal transactions
        normal_count = random.randint(12, 18)
        for _ in range(normal_count):
            tx = generate_transaction(user_id, user_profile, is_anomaly=False)
            transactions.append(tx)

        # Generate anomaly transactions (10-20% dari total)
        anomaly_count = random.randint(2, 8)
        for _ in range(anomaly_count):
            tx = generate_transaction(user_id, user_profile, is_anomaly=True)
            transactions.append(tx)

    return pd.DataFrame(transactions)


def main():
    print("🔄 Generating behavior anomaly data...")

    df = generate_dataset()

    # Shuffle
    df = df.sample(frac=1).reset_index(drop=True)

    # Statistik
    print(f"\n✅ Dataset generated: {len(df)} transactions")
    print(f"   - Normal: {(df['is_anomaly'] == 0).sum()}")
    print(f"   - Anomaly: {(df['is_anomaly'] == 1).sum()}")
    print(f"\nSample data:")
    print(df.head(10))

    # Save
    import os
    os.makedirs(os.path.dirname(OUTPUT_FILE), exist_ok=True)
    df.to_csv(OUTPUT_FILE, index=False)
    print(f"\n💾 Data saved to {OUTPUT_FILE}")


if __name__ == "__main__":
    main()
