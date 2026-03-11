from fastapi import FastAPI, Request, Depends, Form, Response, HTTPException, status, File, UploadFile
from fastapi.responses import HTMLResponse, RedirectResponse
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from sqlalchemy.orm import Session
from pydantic import BaseModel
import uvicorn
import shutil
import os
import uuid

import models
from database import engine, get_db
from auth import verify_password, HASHED_ADMIN_PASS, create_access_token, get_current_admin

import models
from database import engine, get_db

models.Base.metadata.create_all(bind=engine)

app = FastAPI(title="Noor E Mahal")

# Mount static files directory
app.mount("/static", StaticFiles(directory="static"), name="static")

# Set up Jinja2 templates
templates = Jinja2Templates(directory="templates")


# ─── Pages ───────────────────────────────────────────────────────

@app.get("/", response_class=HTMLResponse)
async def home(request: Request, db: Session = Depends(get_db)):
    content_qs = db.query(models.SiteContent).all()
    site_content = {item.key: item.value for item in content_qs}
    facilities = db.query(models.Facility).all()
    faqs = db.query(models.FAQ).all()
    images_qs = db.query(models.ImageAsset).all()
    site_images = {img.category: img.url for img in images_qs}
    return templates.TemplateResponse("index.html", {
        "request": request, 
        "content": site_content,
        "images": site_images,
        "facilities": facilities,
        "faqs": faqs
    })


@app.get("/gallery", response_class=HTMLResponse)
async def gallery(request: Request, db: Session = Depends(get_db)):
    content_qs = db.query(models.SiteContent).all()
    site_content = {item.key: item.value for item in content_qs}
    images_qs = db.query(models.ImageAsset).all()
    site_images = {img.category: img.url for img in images_qs}
    return templates.TemplateResponse("gallery.html", {
        "request": request, 
        "images": images_qs, 
        "site_images": site_images,
        "content": site_content
    })


@app.get("/about", response_class=HTMLResponse)
async def about(request: Request, db: Session = Depends(get_db)):
    content_qs = db.query(models.SiteContent).all()
    site_content = {item.key: item.value for item in content_qs}
    images_qs = db.query(models.ImageAsset).all()
    site_images = {img.category: img.url for img in images_qs}
    return templates.TemplateResponse("about.html", {
        "request": request, 
        "content": site_content,
        "images": site_images
    })


@app.get("/facilities", response_class=HTMLResponse)
async def facilities_page(request: Request, db: Session = Depends(get_db)):
    content_qs = db.query(models.SiteContent).all()
    site_content = {item.key: item.value for item in content_qs}
    facilities = db.query(models.Facility).all()
    images_qs = db.query(models.ImageAsset).all()
    site_images = {img.category: img.url for img in images_qs}
    return templates.TemplateResponse("facilities.html", {
        "request": request, 
        "facilities": facilities, 
        "content": site_content,
        "images": site_images
    })


@app.get("/services", response_class=HTMLResponse)
async def services_page(request: Request, db: Session = Depends(get_db)):
    content_qs = db.query(models.SiteContent).all()
    site_content = {item.key: item.value for item in content_qs}
    services = db.query(models.Service).all()
    return templates.TemplateResponse("services.html", {"request": request, "services": services, "content": site_content})


@app.get("/contact", response_class=HTMLResponse)
async def contact_page(request: Request, db: Session = Depends(get_db)):
    content_qs = db.query(models.SiteContent).all()
    site_content = {item.key: item.value for item in content_qs}
    images_qs = db.query(models.ImageAsset).all()
    site_images = {img.category: img.url for img in images_qs}
    return templates.TemplateResponse("contact.html", {
        "request": request, 
        "content": site_content,
        "images": site_images
    })


# ─── Auth Routes ─────────────────────────────────────────────────

@app.get("/admin/login", response_class=HTMLResponse)
async def login_page(request: Request):
    return templates.TemplateResponse("login.html", {"request": request})

@app.post("/admin/login")
async def login_submit(request: Request, response: Response, username: str = Form(...), password: str = Form(...)):
    if username == "admin" and verify_password(password, HASHED_ADMIN_PASS):
        # Login success
        access_token = create_access_token(data={"sub": username})
        redirect = RedirectResponse(url="/admin", status_code=status.HTTP_302_FOUND)
        redirect.set_cookie(key="admin_access_token", value=access_token, httponly=True, max_age=60*60*24)
        return redirect
    
    # Login failed
    return templates.TemplateResponse("login.html", {"request": request, "error": "Invalid username or password"})

@app.get("/admin/logout")
async def logout():
    redirect = RedirectResponse(url="/admin/login", status_code=status.HTTP_302_FOUND)
    redirect.delete_cookie("admin_access_token")
    return redirect

# ─── Admin Panel ─────────────────────────────────────────────────

@app.get("/admin", response_class=HTMLResponse)
async def admin_dashboard(request: Request, db: Session = Depends(get_db), admin: str = Depends(get_current_admin)):
    return templates.TemplateResponse("admin.html", {
        "request": request,
        "content": db.query(models.SiteContent).all(),
        "images": db.query(models.ImageAsset).all(),
        "facilities": db.query(models.Facility).all(),
        "services": db.query(models.Service).all(),
        "faqs": db.query(models.FAQ).all(),
    })


# ─── Admin API ───────────────────────────────────────────────────

class ContentUpdate(BaseModel):
    key: str
    value: str

class ImageCreate(BaseModel):
    url: str
    alt_text: str
    category: str

class ImageUpdate(BaseModel):
    id: int
    url: str
    alt_text: str

class ImageDelete(BaseModel):
    id: int


@app.post("/admin/api/content")
async def update_content(data: ContentUpdate, db: Session = Depends(get_db), admin: str = Depends(get_current_admin)):
    item = db.query(models.SiteContent).filter(models.SiteContent.key == data.key).first()
    if item:
        item.value = data.value
        db.commit()
        return {"status": "success"}
    # Create if not exists
    db.add(models.SiteContent(key=data.key, value=data.value))
    db.commit()
    return {"status": "created"}


@app.post("/admin/api/image/add")
async def add_image(data: ImageCreate, db: Session = Depends(get_db), admin: str = Depends(get_current_admin)):
    img = models.ImageAsset(url=data.url, alt_text=data.alt_text, category=data.category)
    db.add(img)
    db.commit()
    return {"status": "success", "id": img.id}


@app.post("/admin/api/image/update")
async def update_image(data: ImageUpdate, db: Session = Depends(get_db), admin: str = Depends(get_current_admin)):
    item = db.query(models.ImageAsset).filter(models.ImageAsset.id == data.id).first()
    if item:
        item.url = data.url
        item.alt_text = data.alt_text
        db.commit()
        return {"status": "success"}
    return {"status": "error", "message": "Image not found"}


@app.post("/admin/api/image/delete")
async def delete_image(data: ImageDelete, db: Session = Depends(get_db), admin: str = Depends(get_current_admin)):
    item = db.query(models.ImageAsset).filter(models.ImageAsset.id == data.id).first()
    if item:
        db.delete(item)
        db.commit()
        return {"status": "success"}
    return {"status": "error", "message": "Image not found"}


@app.post("/admin/api/image/upload")
async def upload_image(
    image_id: int = Form(...),
    file: UploadFile = File(...),
    db: Session = Depends(get_db),
    admin: str = Depends(get_current_admin)
):
    # Ensure directory exists
    UPLOAD_DIR = "static/uploads"
    if not os.path.exists(UPLOAD_DIR):
        os.makedirs(UPLOAD_DIR)

    # Find the image asset
    item = db.query(models.ImageAsset).filter(models.ImageAsset.id == image_id).first()
    if not item:
        return {"status": "error", "message": "Image slot not found"}

    # Generate unique filename
    ext = os.path.splitext(file.filename)[1]
    filename = f"{uuid.uuid4()}{ext}"
    file_path = os.path.join(UPLOAD_DIR, filename)
    
    # Save file
    with open(file_path, "wb") as buffer:
        shutil.copyfileobj(file.file, buffer)

    # Update database
    relative_path = f"static/uploads/{filename}"
    item.url = relative_path
    db.commit()

    return {"status": "success", "url": relative_path}


# if __name__ == "__main__":
#     uvicorn.run("main:app", host="0.0.0.0", port=8000, reload=True)
