from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, declarative_base
from app.core.config import settings

# Jika database URL menggunakan mysql://, ganti dengan mysql+pymysql:// secara otomatis
db_url = settings.DATABASE_URL
if db_url.startswith("mysql://"):
    db_url = db_url.replace("mysql://", "mysql+pymysql://")

engine = create_engine(
    db_url,
    pool_pre_ping=True,
    pool_recycle=3600
)

SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

def get_db():
    """Dependency injection untuk database session."""
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
