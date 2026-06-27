# ML FastAPI Project

Machine Learning project dengan FastAPI backend, berjalan di port **5610**.

## 📁 Struktur Folder

```
python-starter/
├── main.py                     # Entry point FastAPI (port 5610)
├── requirements.txt            # Semua dependency
├── .env.example                # Template environment variables
├── .gitignore
│
├── app/
│   ├── api/
│   │   └── v1/
│   │       ├── router.py           # API router utama
│   │       └── endpoints/
│   │           ├── health.py       # GET /api/v1/health
│   │           ├── predict.py      # POST /api/v1/predict
│   │           └── models.py       # GET /api/v1/models
│   ├── core/
│   │   └── config.py           # Konfigurasi (port, env vars, dll)
│   ├── services/
│   │   └── model_service.py    # Load & run ML model
│   └── utils/
│       └── preprocessing.py    # Utilitas preprocessing data
│
├── notebooks/                  # Jupyter notebooks eksplorasi
│   └── 01_eda.ipynb
│
├── data/
│   ├── raw/                    # Data mentah (gitignored)
│   ├── processed/              # Data bersih (gitignored)
│   └── db/                     # SQLite database
│
├── models/
│   ├── saved/                  # Model tersimpan (.joblib, .pkl, dll)
│   └── experiments/            # Hasil eksperimen MLflow
│
├── scripts/
│   ├── train.py                # Script pelatihan model
│   └── evaluate.py             # Script evaluasi model
│
├── tests/
│   └── test_api.py             # Unit tests endpoint
│
└── logs/                       # Log aplikasi
```

## 🚀 Quick Start

### 1. Buat virtual environment
```bash
python -m venv venv
venv\Scripts\activate          # Windows
source venv/bin/activate       # Linux/Mac
```

### 2. Install dependencies
```bash
pip install -r requirements.txt
```

### 3. Copy environment variables
```bash
copy .env.example .env
```

### 4. Jalankan server
```bash
python main.py
# atau
uvicorn main:app --host 0.0.0.0 --port 5610 --reload
```

### 5. Buka di browser
- **API Docs (Swagger)**: http://localhost:5610/docs
- **ReDoc**: http://localhost:5610/redoc
- **Health Check**: http://localhost:5610/health

## 🤖 Training Model

```bash
python scripts/train.py     # Train model
python scripts/evaluate.py  # Evaluasi model
```

## 🧪 Testing

```bash
pytest tests/
```

## 📡 Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/` | Root info |
| GET | `/health` | Health check |
| GET | `/api/v1/health` | API health |
| POST | `/api/v1/predict/` | Prediksi ML |
| GET | `/api/v1/models/` | List model |
| GET | `/api/v1/models/{name}` | Info model |
