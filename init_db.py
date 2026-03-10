"""Seed the database with initial content."""
from database import engine, SessionLocal, Base
import models

Base.metadata.create_all(bind=engine)
db = SessionLocal()

# --- Site Content ---
content_items = [
    ("hero_title", "A Union of Cultures, A Legacy of Love"),
    ("hero_subtitle", "Where Punjabi Grandeur Meets British Elegance"),
    ("about_title", "A Heritage of Punjabi Kings"),
    ("about_text", "Noor E Mahal is not just a marriage palace; it is a manifestation of royal Punjabi architecture fused with British colonial elegance. Every detail tells a story of unmatched hospitality and majestic celebrations."),
    ("contact_phone", "+91 98765 43210"),
    ("contact_email", "royal@nooremahal.com"),
    ("contact_address", "Noor E Mahal Palace, Punjab, India"),
]
for key, value in content_items:
    if not db.query(models.SiteContent).filter_by(key=key).first():
        db.add(models.SiteContent(key=key, value=value))

# --- Facilities ---
facilities = [
    ("Car Parking (40+)", "Spacious parking area accommodating 40+ vehicles for your guests.", "car"),
    ("Big Hall", "Grand hall with elegant interiors for large celebrations.", "building"),
    ("Bride & Groom AC Room", "Dedicated air-conditioned rooms for the bride and groom.", "snowflake"),
    ("Destination Wedding", "Punjab Culture destination wedding experience.", "plane"),
    ("Premium Amenities", "Modern premium amenities for ultimate comfort.", "gem"),
    ("Guest Capacity 1000+", "Accommodating over 1000 guests comfortably.", "users"),
]
for name, desc, icon in facilities:
    if not db.query(models.Facility).filter_by(name=name).first():
        db.add(models.Facility(name=name, description=desc, icon_class=icon))

# --- Services ---
services = [
    ("Destination Wedding", "Experience a grand Punjabi destination wedding surrounded by royal architecture and lush gardens. Our team handles every detail from décor to cuisine.", "https://images.unsplash.com/photo-1519741497674-611481863552?w=700&q=80"),
    ("Royal Marriage Ceremony", "Traditional and modern marriage ceremonies hosted in our palatial grand hall with bespoke floral arrangements and lighting.", "https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=700&q=80"),
    ("Pre-Wedding Shoots", "50+ picturesque locations within the palace grounds for stunning pre-wedding photography sessions.", "https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=700&q=80"),
    ("Fusion Cuisine Catering", "Multi-cuisine dining experience blending authentic Punjabi flavors with international gourmet, prepared by master chefs.", "https://images.unsplash.com/photo-1555244162-803834f70033?w=700&q=80"),
]
for title, desc, img in services:
    if not db.query(models.Service).filter_by(title=title).first():
        db.add(models.Service(title=title, description=desc, image_url=img))

# --- FAQs ---
faqs = [
    ("What is the guest capacity?", "Noor E Mahal can accommodate over 1000 guests across our grand hall and open lawn spaces."),
    ("Do you provide catering?", "Yes, we offer multi-cuisine catering including authentic Punjabi, North Indian, Chinese, and Continental options."),
    ("Is parking available?", "We have spacious parking for 40+ vehicles with valet service available on request."),
    ("Can we do a destination wedding?", "Absolutely! Noor E Mahal specializes in destination weddings that celebrate Punjab's rich cultural heritage."),
    ("Are AC rooms available for bride and groom?", "Yes, we offer dedicated air-conditioned preparation rooms for both the bride and groom."),
]
for q, a in faqs:
    if not db.query(models.FAQ).filter_by(question=q).first():
        db.add(models.FAQ(question=q, answer=a))

# --- Gallery Images ---
gallery_categories = {
    "gallery_outside": [
        ("https://images.unsplash.com/photo-1587474260584-136574528ed5?w=600&q=80", "Palace Exterior View"),
        ("https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=600&q=80", "Main Entrance"),
    ],
    "gallery_inside": [
        ("https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=600&q=80", "Grand Hall Interior"),
        ("https://images.unsplash.com/photo-1505236858219-8359eb29e329?w=600&q=80", "Crystal Chandelier Hall"),
    ],
    "gallery_hall": [
        ("https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=600&q=80", "Banquet Setup"),
        ("https://images.unsplash.com/photo-1478146059778-26028b07395a?w=600&q=80", "Reception Stage"),
    ],
    "gallery_garden": [
        ("https://images.unsplash.com/photo-1510076857177-7470076d4098?w=600&q=80", "Palace Gardens"),
        ("https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80", "Garden Pathway"),
    ],
    "gallery_rooms": [
        ("https://images.unsplash.com/photo-1590490360182-c33d7bdb6f81?w=600&q=80", "Bridal Suite"),
        ("https://images.unsplash.com/photo-1618773928121-c32f66e61e39?w=600&q=80", "Groom Suite"),
    ],
    "gallery_parking": [
        ("https://images.unsplash.com/photo-1573348722427-f1d6819fdf98?w=600&q=80", "Parking Area"),
    ],
}
for cat, images in gallery_categories.items():
    for url, alt in images:
        if not db.query(models.ImageAsset).filter_by(url=url).first():
            db.add(models.ImageAsset(url=url, alt_text=alt, category=cat))

db.commit()
db.close()
print("Database seeded successfully!")
