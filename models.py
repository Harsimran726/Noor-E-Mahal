from sqlalchemy import Column, Integer, String, Text
from database import Base

class SiteContent(Base):
    __tablename__ = "site_content"
    id = Column(Integer, primary_key=True, index=True)
    key = Column(String(100), unique=True, index=True)
    value = Column(Text)

class ImageAsset(Base):
    __tablename__ = "image_assets"
    id = Column(Integer, primary_key=True, index=True)
    url = Column(String(500))
    alt_text = Column(String(200))
    category = Column(String(50), index=True)

class Facility(Base):
    __tablename__ = "facilities"
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(100))
    description = Column(Text)
    icon_class = Column(String(50))

class Service(Base):
    __tablename__ = "services"
    id = Column(Integer, primary_key=True, index=True)
    title = Column(String(100))
    description = Column(Text)
    image_url = Column(String(500))

class FAQ(Base):
    __tablename__ = "faqs"
    id = Column(Integer, primary_key=True, index=True)
    question = Column(String(300))
    answer = Column(Text)
