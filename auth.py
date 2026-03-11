import os
import bcrypt
import jwt
from datetime import datetime, timedelta
from fastapi import Request, HTTPException, status

SECRET_KEY = os.getenv("SECRET_KEY", "super-secret-royal-key-for-noor-e-mahal")
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 60 * 24  # 1 day

ADMIN_USER = os.getenv("ADMIN_USER", "admin")
ADMIN_PASS = os.getenv("ADMIN_PASS", "nooremahal2024")

def get_password_hash(password: str) -> str:
    # Bcrypt requires bytes. We truncate to 72 to be safe.
    password_bytes = password[:72].encode('utf-8')
    salt = bcrypt.gensalt()
    hashed = bcrypt.hashpw(password_bytes, salt)
    return hashed.decode('utf-8')

def verify_password(plain_password: str, hashed_password: str) -> bool:
    try:
        return bcrypt.checkpw(
            plain_password[:72].encode('utf-8'), 
            hashed_password.encode('utf-8')
        )
    except Exception:
        return False

# This will now run without the Passlib internal error
HASHED_ADMIN_PASS = get_password_hash(ADMIN_PASS)

def create_access_token(data: dict):
    to_encode = data.copy()
    expire = datetime.utcnow() + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expire})
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)

def get_current_admin(request: Request):
    token = request.cookies.get("admin_access_token")
    if not token:
        raise HTTPException(
            status_code=status.HTTP_303_SEE_OTHER,
            headers={"Location": "/admin/login"},
        )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username: str = payload.get("sub")
        if username != ADMIN_USER:
            raise HTTPException(status_code=status.HTTP_403_FORBIDDEN, detail="Unauthorized")
        return username
    except jwt.PyJWTError:
        raise HTTPException(
            status_code=status.HTTP_303_SEE_OTHER,
            headers={"Location": "/admin/login"},
        )