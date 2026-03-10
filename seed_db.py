from sqlalchemy.orm import Session
from database import SessionLocal, engine
import models

def seed():
    db = SessionLocal()
    
    # 1. Seed Site Content (Dynamic Text)
    content_items = [
        # Homepage
        ("home_hero_title", "A Union of Cultures,<br>A Legacy of Love"),
        ("home_hero_subtitle", "Where Punjabi Grandeur Meets British Elegance"),
        ("home_hero_cta", "Plan Your Royal Wedding"),
        ("home_venue_label_1", "The Grand Hall"),
        ("home_venue_label_2", "The Royal Gardens"),
        ("home_venue_label_3", "The Heritage Wing"),
        ("home_venue_label_4", "The Crown Courtyard"),
        ("home_venue_label_5", "The Crystal Ballroom"),
        ("home_showcase_title", "Where Dreams Meet Destiny"),
        ("home_showcase_p", "Experience timeless grandeur in every moment"),
        ("home_why_title", "Why Choose Noor E Mahal?"),
        ("home_why_text", "Noor E Mahal is more than just a venue; it is a legacy of royal hospitality. We specialize in blending the vibrant traditions of Punjabi weddings with the sophisticated charm of British-style events."),
        ("home_why_f1", "Authentic Royal Experience"),
        ("home_why_f2", "Bespoke Event Planning"),
        ("home_why_f3", "Premier Central Location"),
        ("home_why_badge_years", "10+"),
        
        # About Us
        ("about_hero_title", "About Us"),
        ("about_section1_title", "A Heritage of Punjabi Kings"),
        ("about_section1_p1", "Noor E Mahal is not just a marriage palace; it is a manifestation of royal Punjabi architecture fused with British colonial elegance. Every detail tells a story of unmatched hospitality and majestic celebrations."),
        ("about_section2_title", "Where Every Moment is Royal"),
        ("about_section3_title", "A Legacy Continuing"),

        # Contact
        ("contact_phone_1", "+92 451 234 5678"),
        ("contact_email", "info@nooremahal.com"),
        ("contact_address", "VPO Noorpur, Near Heritage Site, Punjab"),
        ("common_footer_text", "Where every celebration becomes a royal legacy. Experience the grandeur of Punjabi heritage fused with timeless British elegance.")
    ]
    
    for key, value in content_items:
        existing = db.query(models.SiteContent).filter_by(key=key).first()
        if existing:
            existing.value = value
        else:
            db.add(models.SiteContent(key=key, value=value))

    # 2. Seed Facilities
    facilities_data = [
        ("AC Bride & Groom Room", "Luxurious, fully air-conditioned private suites designed for comfort and preparation.", "fas fa-snowflake"),
        ("Big Parking Space", "Secure and expansive valet parking area capable of comfortably accommodating 50+ vehicles.", "fas fa-car"),
        ("Big Hall", "A magnificent, pillar-less grand hall designed to comfortably host 500+ guests.", "fas fa-building"),
        ("Garden Space", "Lush green, beautifully manicured open lawns perfect for outdoor evening receptions.", "fas fa-leaf"),
        ("Premium Stage", "Exquisitely crafted, elevated stages with custom lighting for royal portraits.", "fas fa-crown"),
        ("Guest Rooms", "Premium accommodation options ensuring a luxurious stay for your guests.", "fas fa-bed")
    ]
    
    for name, desc, icon in facilities_data:
        existing = db.query(models.Facility).filter_by(name=name).first()
        if not existing:
            db.add(models.Facility(name=name, description=desc, icon_class=icon))

    # 3. Seed FAQs
    faqs_data = [
        ("What is the total guest capacity?", "Our grand hall can comfortably host 500+ guests, while our garden lawn accommodates even larger gatherings."),
        ("Do you provide in-house catering?", "Yes, we offer premium in-house catering with both traditional Punjabi and contemporary international cuisines."),
        ("Is there ample parking for guests?", "We provide a dedicated parking space for 50+ cars, along with valet services.")
    ]
    
    for q, a in faqs_data:
        existing = db.query(models.FAQ).filter_by(question=q).first()
        if not existing:
            db.add(models.FAQ(question=q, answer=a))

    # 4. Comprehensive Image Slots (Slots based on auditing templates)
    image_slots = [
        # Homepage
        ("static/Noor_e_mahal_ png (1).png", "Hero Slider 1", "home_hero_1"),
        ("static/Noor_e_mahal_ png (5).png", "Hero Slider 2", "home_hero_2"),
        ("static/Noor_e_mahal_ png (1).png", "Venue: Grand Hall", "home_venue_1"),
        ("static/Noor_e_mahal_ png (2).png", "Venue: Royal Gardens", "home_venue_2"),
        ("static/Noor_e_mahal_ png (5).png", "Venue: Heritage Wing", "home_venue_3"),
        ("static/Noor_e_mahal_ png (1).png", "Venue: Crown Courtyard", "home_venue_4"),
        ("static/Noor_e_mahal_ png (2).png", "Venue: Crystal Ballroom", "home_venue_5"),
        ("static/Noor_e_mahal_ png (5).png", "Panoramic Showcase", "home_showcase"),
        ("static/Noor_e_mahal_ png (8).png", "Royal Elephant Welcome", "home_welcome_elephant"),
        ("static/Noor_e_mahal_ png (1).png", "Highlight: Architecture", "home_highlight_1"),
        ("static/Noor_e_mahal_ png (2).png", "Highlight: Interiors", "home_highlight_2"),
        ("static/Noor_e_mahal_ png (5).png", "Highlight: Gardens", "home_highlight_3"),
        ("static/Noor_e_mahal_ png (1).png", "Why Choose Us Main Image", "home_why_us"),

        # About Page
        ("static/Noor_e_mahal_ png (1).png", "About Hero Slider 1", "about_hero_1"),
        ("static/Noor_e_mahal_ png (5).png", "About Hero Slider 2", "about_hero_2"),
        ("static/Noor_e_mahal_ png (1).png", "Heritage Section Image", "about_section_1"),
        ("static/Noor_e_mahal_ png (2).png", "Moments Section Image", "about_section_2"),
        ("static/Noor_e_mahal_ png (5).png", "Legacy Section Image", "about_section_3"),

        # Facilities Page
        ("static/Noor_e_mahal_ png (1).png", "Facilities Hero Slider 1", "facilities_hero_1"),
        ("static/Noor_e_mahal_ png (5).png", "Facilities Hero Slider 2", "facilities_hero_2"),
        ("static/Noor_e_mahal_ png (5).png", "Facility Card 1: AC Suite", "facilities_card_1"),
        ("static/Noor_e_mahal_ png (1).png", "Facility Card 2: Parking", "facilities_card_2"),
        ("static/Noor_e_mahal_ png (2).png", "Facility Card 3: Grand Hall", "facilities_card_3"),
        ("static/Noor_e_mahal_ png (8).png", "Facility Card 4: Garden Space", "facilities_card_4"),
        ("static/Noor_e_mahal_ png (6).png", "Facility Card 5: Stage", "facilities_card_5"),
        ("static/Noor_e_mahal_ png (5).png", "Facility Card 6: Room", "facilities_card_6"),

        # Contact Page
        ("static/Noor_e_mahal_ png (1).png", "Contact Hero Slider 1", "contact_hero_1"),
        ("static/Noor_e_mahal_ png (5).png", "Contact Hero Slider 2", "contact_hero_2")
    ]
    
    for url, alt, cat in image_slots:
        existing = db.query(models.ImageAsset).filter_by(category=cat).first()
        if existing:
            existing.url = url
            existing.alt_text = alt
        else:
            db.add(models.ImageAsset(url=url, alt_text=alt, category=cat))

    db.commit()
    db.close()
    print("Database seeded with full image slots and content successfully!")

if __name__ == "__main__":
    seed()
