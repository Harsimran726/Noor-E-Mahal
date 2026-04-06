<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/footer.php';
$content = getSiteContent($db);
$images = getSiteImages($db);
$facilities = $db->query('SELECT * FROM facilities')->fetchAll();
$faqs = $db->query('SELECT * FROM faqs')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Noor E Mahal — A royal wedding venue where Punjabi grandeur meets British elegance. Plan your dream wedding in our palatial venue with fusion cuisine, custom designs, and concierge service.">
    <meta name="keywords"
        content="Noor E Mahal, royal wedding venue, palace wedding, Punjabi wedding, luxury wedding, destination wedding">
    <title>Noor E Mahal — Story Begins in Royal Palace</title>

    <!-- Favicon -->
    <link rel="icon" href="static/Noor_e_mahal_ png (6).png" type="image/png">
    
    <!-- Preconnect for faster DNS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Async Google Fonts -->
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Poppins:wght@300;400;500;600&display=swap"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Poppins:wght@300;400;500;600&display=swap">
    </noscript>

    <!-- Async FontAwesome -->
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <!-- Critical CSS (Above-the-fold) -->
    <style>
        :root {
            --maroon-deep: #6B0F1A; --gold: #C5A355; --cream: #FDF8EF; --white: #FFFFFF; --text-dark: #2C1810; --text-body: #4A3728;
            --font-heading: 'Playfair Display', Georgia, serif; --font-body: 'Cormorant Garamond', serif; --transition-smooth: all 0.4s ease;
        }
        *,*::before,*::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { font-family: var(--font-body); background-color: var(--cream); color: var(--text-body); -webkit-font-smoothing: antialiased; }
        .preloader { position: fixed; inset: 0; z-index: 9999; background: var(--cream); display: flex; justify-content: center; align-items: center; transition: opacity 0.8s ease; }
        .preloader.hidden { opacity: 0; visibility: hidden; }
        .navbar { position: fixed; top: 0; width: 100%; z-index: 1000; padding: 18px 0; transition: var(--transition-smooth); }
        .hero-slider { position: relative; height: 100vh; min-height: 400px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #3E2723; }
    </style>

    <!-- Full Stylesheets (Async) -->
    <link rel="preload" as="style" href="<?= assetUrl('static/css/style.css') ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= assetUrl('static/css/style.css') ?>"></noscript>
</head>

<body>

    <!-- ============ PRELOADER ============ -->
    <div class="preloader" id="preloader">
        <img src="static/Noor_e_mahal_ png (6).png" alt="Loading..." class="preloader-logo" loading="lazy">
    </div>

    <!-- ============ NAVBAR ============ -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="#" class="nav-brand">
                <img src="static/Noor_e_mahal_ png (6).png" alt="Noor E Mahal Logo"
                    class="nav-logo" loading="lazy">
                <div class="nav-brand-text">
                    Noor E Mahal
                    <span>Story Begins in Royal Palace</span>
                </div>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="gallery.php">Gallery</a></li>
                <li class="nav-separator">|</li>
                <li><a href="about.php">About Us</a></li>
                <li class="nav-separator">|</li>
                <li><a href="facilities.php">Facilities</a></li>
                <li><a href="contact.php" class="nav-cta">Contact</a></li>
            </ul>
            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- ============ HERO SLIDER SECTION ============ -->
    <section class="hero-slider" id="hero">
        <div class="slide active">
            <img src="<?= $images['home_hero_1'] ?? 'static/Noor_e_mahal_ png (3).png' ?>"
                class="slide-bg parallax-bg" alt="Noor E Mahal Palace 1" loading="lazy">
        </div>
        <div class="slide">
            <img src="<?= $images['home_hero_2'] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                class="slide-bg parallax-bg" alt="Noor E Mahal Palace 2" loading="lazy">
        </div>

        <!-- 3D Floating Particles -->
        <div class="hero-particles">
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
        </div>

        <div class="hero-overlay" style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(107,15,26,0.5));">
        </div>

        <div class="hero-content">
            <div class="hero-frame parallax-text">
                <div class="hero-kicker">
                    <span class="hero-kicker-line"></span>
                    <span>Palace wedding venue</span>
                    <span class="hero-kicker-line"></span>
                </div>
                <h1 class="hero-title pt-5"><?= $content['home_hero_title'] ?? 'A Union of Cultures,<br>A Legacy of
                    Love' ?></h1>
                <p class="hero-subtitle"><?= $content['home_hero_subtitle'] ?? 'Where Punjabi Grandeur Meets British
                    Elegance' ?></p>
                <div class="hero-orbit hero-orbit-left" aria-hidden="true"></div>
                <div class="hero-orbit hero-orbit-right" aria-hidden="true"></div>
                <a href="#contact" class="hero-cta"><?= $content['home_hero_cta'] ?? 'Plan Your Royal Wedding' ?></a>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <span>Scroll</span>
            <div class="mouse"></div>
        </div>
    </section>

    <!-- ============ ABOUT US SECTION ============ -->
    <section class="about-us-section reveal" id="about-us">
        <div class="container">
            <div class="about-us-grid">
                <div class="about-us-panel reveal-left">
                    <div class="section-heading">
                        <h2 class="gold-shimmer"><?= $content['home_about_title'] ?? 'About Noor E Mahal' ?></h2>
                        <p><?= $content['home_about_subtitle'] ?? 'A palace where Punjabi heritage and British elegance become one unforgettable celebration.' ?></p>
                    </div>
                    <p><?= $content['home_about_text'] ?? 'Noor E Mahal is a heritage venue designed to make every ceremony majestic. From grand halls to intimate gardens, our palace provides the perfect stage for weddings that feel both royal and personal.' ?></p>
                    <ul class="about-us-features">
                        <li><i class="fas fa-crown"></i><span><?= $content['home_about_feature_1'] ?? 'Royal spaces crafted for celebration' ?></span></li>
                        <li><i class="fas fa-leaf"></i><span><?= $content['home_about_feature_2'] ?? 'Luxurious hospitality with modern comfort' ?></span></li>
                        <li><i class="fas fa-hand-holding-heart"></i><span><?= $content['home_about_feature_3'] ?? 'Curated weddings with expert planning' ?></span></li>
                    </ul>
                    <a href="about.php" class="about-us-cta">Learn Our Story</a>
                </div>
                <div class="about-us-image reveal-right">
                    <img src="<?= $images['home_about_image'] ?? 'static/Noor_e_mahal_ png (1).png' ?>"
                        alt="Noor E Mahal About" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ VENUES SECTION ============ -->
    <section class="venues-section reveal" id="venues">
        <div class="container">
            <div class="section-heading">
                <h2 class="gold-shimmer">Venues</h2>
                <p>Discover our magnificent spaces fit for royalty</p>
            </div>
            <div class="venues-grid">
                <div class="venue-card reveal delay-1" data-venue="The Grand Hall" data-img="<?= $images['home_venue_1'] ?? 'static/Noor_e_mahal_ png (3).png' ?>">
                    <img src="<?= $images['home_venue_1'] ?? 'static/Noor_e_mahal_ png (3).png' ?>"
                        alt="The Grand Hall" loading="lazy">
                    <div class="venue-shine"></div>
                    <span class="venue-card-label"><?= htmlspecialchars($content['home_venue_label_1'] ?? 'The Grand Hall') ?></span>
                </div>
                <div class="venue-card reveal delay-2" data-venue="The Royal Gardens" data-img="<?= $images['home_venue_2'] ?? 'static/Noor_e_mahal_ png (6).png' ?>">
                    <img src="<?= $images['home_venue_2'] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                        alt="The Royal Gardens" loading="lazy">
                    <div class="venue-shine"></div>
                    <span class="venue-card-label"><?= htmlspecialchars($content['home_venue_label_2'] ?? 'The Royal Gardens') ?></span>
                </div>
                <div class="venue-card reveal delay-3" data-venue="The Heritage Wing" data-img="<?= $images['home_venue_3'] ?? 'static/Noor_e_mahal_ png (4).png' ?>">
                    <img src="<?= $images['home_venue_3'] ?? 'static/Noor_e_mahal_ png (4).png' ?>"
                        alt="The Heritage Wing" loading="lazy">
                    <div class="venue-shine"></div>
                    <span class="venue-card-label"><?= htmlspecialchars($content['home_venue_label_3'] ?? 'The Heritage Wing') ?></span>
                </div>
                <div class="venue-card reveal delay-4" data-venue="The Crown Courtyard" data-img="<?= $images['home_venue_4'] ?? 'static/Noor_e_mahal_ png (3).png' ?>">
                    <img src="<?= $images['home_venue_4'] ?? 'static/Noor_e_mahal_ png (3).png' ?>"
                        alt="The Crown Courtyard" loading="lazy">
                    <div class="venue-shine"></div>
                    <span class="venue-card-label"><?= htmlspecialchars($content['home_venue_label_4'] ?? 'The Crown Courtyard') ?></span>
                </div>
                <div class="venue-card reveal delay-5" data-venue="The Crystal Ballroom" data-img="<?= $images['home_venue_5'] ?? 'static/Noor_e_mahal_ png (6).png' ?>">
                    <img src="<?= $images['home_venue_5'] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                        alt="The Crystal Ballroom" loading="lazy">
                    <div class="venue-shine"></div>
                    <span class="venue-card-label"><?= htmlspecialchars($content['home_venue_label_5'] ?? 'The Crystal Ballroom') ?></span>
                </div>
            </div>
        </div>
    </section>

    <div class="venue-modal-overlay" id="venueModal" aria-hidden="true">
        <div class="venue-modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <button class="venue-modal-close" id="modalClose" aria-label="Close venue preview">&times;</button>
            <img id="modalImg" class="venue-modal-img" src="" alt="Venue preview">
            <div class="venue-modal-content">
                <h2 id="modalTitle" class="venue-modal-title">Venue Name</h2>
                <p id="modalDesc" class="venue-modal-desc">Experience the grandeur of this magnificent venue designed for your royal celebration.</p>
                <a href="contact.php" class="venue-modal-cta">Book This Venue</a>
            </div>
        </div>
    </div>

    <!-- ============ FACILITIES SECTION ============ -->
    <section class="services-section" id="facilities">
        <div class="container">
            <div class="section-heading reveal">
                <h2 class="gold-shimmer">Palace Facilities</h2>
                <p>Unmatched amenities for an extraordinary celebration</p>
            </div>
            <div class="services-grid">
                <?php if(!empty($facilities)): ?>
                <?php $i=0; foreach($facilities as $fac): ?>
                <div class="service-card reveal delay-<?= (++$i % 3) + 1 ?>">
                    <img src="<?= $images['home_facility_' . $i] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                        class="service-card-bg" alt="<?= htmlspecialchars($fac['name'] ?? '') ?>" loading="lazy" decoding="async" fetchpriority="low">
                    <div class="service-card-overlay"></div>
                    <div class="service-card-content">
                        <div class="service-icon">
                            <?php if (!empty($fac['icon_url'])): ?>
                                <img src="<?= htmlspecialchars($fac['icon_url']) ?>" alt="<?= htmlspecialchars($fac['name'] ?? '') ?> icon" loading="lazy" decoding="async" fetchpriority="low">
                            <?php else: ?>
                                <i class="<?= htmlspecialchars($fac['icon_class'] ?? '') ?> fa-2x"></i>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($fac['name'] ?? '') ?></h3>
                        <p><?= htmlspecialchars($fac['desc'] ?? '') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <!-- Fallback static content if DB is empty -->
                <!-- Facility 1 -->
                <div class="service-card reveal delay-1">
                    <img src="static/Noor_e_mahal_ png (6).png" class="service-card-bg"
                        alt="Facility" loading="lazy">
                    <div class="service-card-overlay"></div>
                    <div class="service-card-content">
                        <div class="service-icon"><i class="fas fa-snowflake fa-2x"></i></div>
                        <h3>AC Bride & Groom Room</h3>
                        <p>Luxurious, fully air-conditioned private suites designed for comfort and preparation.</p>
                    </div>
                </div>
                <!-- Facility 2 -->
                <div class="service-card reveal delay-2">
                    <img src="static/Noor_e_mahal_ png (3).png" class="service-card-bg"
                        alt="Facility" loading="lazy">
                    <div class="service-card-overlay"></div>
                    <div class="service-card-content">
                        <div class="service-icon"><i class="fas fa-car fa-2x"></i></div>
                        <h3>Big Parking Space</h3>
                        <p>Secure and expansive valet parking area capable of comfortably accommodating 50+ vehicles.
                        </p>
                    </div>
                </div>
                <!-- Facility 3 -->
                <div class="service-card reveal delay-3">
                    <img src="static/Noor_e_mahal_ png (4).png" class="service-card-bg"
                        alt="Facility" loading="lazy">
                    <div class="service-card-overlay"></div>
                    <div class="service-card-content">
                        <div class="service-icon"><i class="fas fa-building fa-2x"></i></div>
                        <h3>Big Hall</h3>
                        <p>A magnificent, pillar-less grand hall designed to comfortably host 500+ guests.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </section>

    <!-- ============ PALACE SHOWCASE ============ -->
    <section class="palace-showcase">
        <div class="palace-showcase-bg">
            <img src="<?= $images['home_showcase'] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                alt="Noor E Mahal Palace Panoramic View" loading="lazy">
        </div>
        <div class="palace-showcase-overlay">
            <div class="palace-showcase-text">
                <h2 class="gold-shimmer"><?= $content['home_showcase_title'] ?? 'Where Dreams Meet Destiny' ?></h2>
                <p><?= $content['home_showcase_p'] ?? 'Experience timeless grandeur in every moment' ?></p>
            </div>
        </div>
    </section>

    <!-- ============ TESTIMONIALS SECTION ============ -->
    <?php
    $testimonialItems = [];
    for ($i = 1; $i <= 12; $i++) {
        $quoteKey = 'home_testimonial_' . $i . '_quote';
        $authorKey = 'home_testimonial_' . $i . '_author';
        $quote = $content[$quoteKey] ?? null;
        $author = $content[$authorKey] ?? null;

        if ($quote && $author) {
            $testimonialItems[] = ['quote' => $quote, 'author' => $author];
        }
    }

    if (empty($testimonialItems)) {
        $testimonialItems = [
            ['quote' => 'You are surrounded by the most elegant and yet that rustic reason temple, maintaining its grandeur of its Mughal glory and diversity.', 'author' => 'Almas'],
            ['quote' => 'Flowers as out the blow of winds, as rains and an incredible venue. All royals were surrounded and poised over enchantress of grandeur, love, comfort and the restaurant.', 'author' => 'Alison'],
            ['quote' => 'Maravillas la conjunción era en pantalla de fine course, the entire surrounding was comforting, exquisite sensation was entrusted and unique beyond perfection.', 'author' => 'Merhun'],
            ['quote' => 'An absolutely enchanting experience. The palace grounds were breathtaking and the service was impeccable. Our wedding was nothing short of a fairy tale.', 'author' => 'Priya & James'],
            ['quote' => 'The fusion of cultures was beautifully reflected in every detail — from the décor to the cuisine. Noor E Mahal made our dream wedding a reality.', 'author' => 'Sarah & Raj'],
            ['quote' => 'Every moment was curated with such elegance and warmth. The concierge team went above and beyond to ensure our celebration was perfect.', 'author' => 'Fatima & Oliver'],
        ];
    }
    ?>
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="section-heading reveal">
                <h2 class="gold-shimmer">Testimonials</h2>
                <p>Words from our cherished guests</p>
            </div>
            <div class="testimonials-carousel">
                <div class="testimonials-track">
                    <?php foreach ($testimonialItems as $testimonial): ?>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <span class="testimonial-quote-icon">&ldquo;</span>
                            <p><?= htmlspecialchars($testimonial['quote']) ?></p>
                            <span class="testimonial-author">— <?= htmlspecialchars($testimonial['author']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Navigation Buttons -->
                <button class="testimonial-nav testimonial-prev" aria-label="Previous testimonial">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="testimonial-nav testimonial-next" aria-label="Next testimonial">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <!-- Dots -->
                <div class="testimonial-dots">
                    <span class="testimonial-dot active" data-slide="0"></span>
                    <span class="testimonial-dot" data-slide="1"></span>
                </div>
            </div>
        </div>
    </section>


    <!-- ============ HIGHLIGHTS SECTION ============ -->
    <section class="highlights-section">
        <div class="container">
            <div class="section-heading reveal">
                <h2 class="gold-shimmer">Palace Highlights</h2>
                <p>Discover the magic in every detail</p>
            </div>
            <div class="highlights-grid">
                <div class="highlight-card reveal delay-1">
                    <img src="<?= $images['home_highlight_1'] ?? 'static/Noor_e_mahal_ png (1).png' ?>"
                        alt="Royal Architecture" loading="lazy">
                    <div class="highlight-content">
                        <h3>Royal Architecture</h3>
                    </div>
                </div>
                <div class="highlight-card reveal delay-2">
                    <img src="<?= $images['home_highlight_2'] ?? 'static/Noor_e_mahal_ png (2).png' ?>"
                        alt="Elegant Interiors" loading="lazy">
                    <div class="highlight-content">
                        <h3>Elegant Interiors</h3>
                    </div>
                </div>
                <div class="highlight-card reveal delay-3">
                    <img src="<?= $images['home_highlight_3'] ?? 'static/Noor_e_mahal_ png (5).png' ?>"
                        alt="Lush Gardens" loading="lazy">
                    <div class="highlight-content">
                        <h3>Lush Gardens</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE US ============ -->
    <section class="why-us-section" id="why">
        <div class="container">
            <div class="why-us-shell">
                <div class="why-us-grid">
                    <div class="why-content reveal-left">
                        <div class="why-label">Why choose us</div>
                        <h2 class="gold-shimmer"><?= $content['home_why_title'] ?? 'Why Choose Noor E Mahal?' ?></h2>
                        <p class="why-us-desc"><?= $content['home_why_text'] ?? 'Noor E Mahal is more than just a venue; it is a legacy of royal
                            hospitality. We specialize in blending the vibrant traditions of Punjabi weddings with the
                            sophisticated charm of British-style events.' ?></p>

                        <ul class="why-us-features">
                            <li>
                                <i class="fas fa-crown"></i>
                                <div>
                                    <h4><?= $content['home_why_f1'] ?? 'Authentic Royal Experience' ?></h4>
                                    <p>Heritage-led hospitality, designed to feel warm, elegant, and memorable.</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-gem"></i>
                                <div>
                                    <h4><?= $content['home_why_f2'] ?? 'Bespoke Event Planning' ?></h4>
                                    <p>From decor to dining, every detail is tailored around your celebration.</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h4><?= $content['home_why_f3'] ?? 'Premier Central Location' ?></h4>
                                    <p>Easy to reach, beautifully positioned, and ideal for guests arriving from everywhere.</p>
                                </div>
                            </li>
                        </ul>

                        <div class="why-us-stats">
                            <div class="why-stat">
                                <span class="why-stat-number">1000+</span>
                                <span class="why-stat-label">Guest capacity</span>
                            </div>
                            <div class="why-stat">
                                <span class="why-stat-number">3</span>
                                <span class="why-stat-label">Signature venue styles</span>
                            </div>
                            <div class="why-stat">
                                <span class="why-stat-number">1</span>
                                <span class="why-stat-label">Royal experience</span>
                            </div>
                        </div>
                    </div>

                    <div class="why-image reveal-right">
                        <div class="why-image-frame">
                            <img src="<?= $images['home_why_us'] ?? 'static/Noor_e_mahal_ png (1).png' ?>"
                                alt="Why Noor E Mahal" loading="lazy">
                            <div class="why-image-overlay"></div>
                            <div class="why-image-caption">
                                <span>Signature image</span>
                                A palace setting designed to feel grand, calm, and unforgettable.
                            </div>
                            <div class="why-image-ribbon">Palace heritage • modern hosting</div>
                            <div class="why-glow why-glow-one"></div>
                            <div class="why-glow why-glow-two"></div>
                        </div>
                        <div class="why-badge-card">
                            <span class="why-badge-number"><?= $content['home_why_badge_years'] ?? '10+' ?></span>
                            <span class="why-badge-text">Years of royal hospitality</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ SECTION ============ -->
    <section class="faq-section">
        <div class="container">
            <div class="faq-shell">
                <div class="faq-intro reveal-left">
                    <div class="faq-intro-badge">Need clarity before booking?</div>
                    <h2 class="gold-shimmer">Frequently Asked Questions</h2>
                    <p>Everything you need to know about planning your celebration, from capacity and catering to
                        timing, services, and guest experience.</p>
                    <div class="faq-intro-cards">
                        <div class="faq-mini-card">
                            <span class="faq-mini-number">1000+</span>
                            <span class="faq-mini-text">Guests hosted with ease</span>
                        </div>
                        <div class="faq-mini-card">
                            <span class="faq-mini-number">24/7</span>
                            <span class="faq-mini-text">Planning support</span>
                        </div>
                    </div>
                </div>

                <div class="faq-list reveal-right">
                    <?php if(!empty($faqs)): ?>
                    <?php foreach($faqs as $faq): ?>
                    <div class="faq-item">
                        <button class="faq-q"><?= htmlspecialchars($faq['question'] ?? '') ?> <span class="faq-ico">+</span></button>
                        <div class="faq-a">
                            <p><?= htmlspecialchars($faq['answer'] ?? '') ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <!-- Fallback FAQs -->
                    <div class="faq-item">
                        <button class="faq-q">What is the guest capacity at Noor E Mahal? <span class="faq-ico">+</span></button>
                        <div class="faq-a">
                            <p>We can comfortably accommodate up to 1000+ guests across our grand halls and lush outdoor
                                lawns.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-q">Do you provide in-house catering? <span class="faq-ico">+</span></button>
                        <div class="faq-a">
                            <p>Yes! Our master chefs specialize in a huge variety of cuisines, from Punjabi traditional
                                feasts to modern fusion.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-q">Can we customize the wedding decor? <span class="faq-ico">+</span></button>
                        <div class="faq-a">
                            <p>Absolutely. Every event is tailored around your theme, color palette, and cultural style.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        // FAQ Toggle Logic
        document.querySelectorAll('.faq-q').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.parentElement;
                const wasOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
                if (!wasOpen) item.classList.add('open');
            });
        });
    </script>

    <!-- ============ LOCATION MAP SECTION ============ -->
    <section class="map-section reveal">
        <iframe
            src="https://www.google.com/maps/place/Noor+E+Mahal/@30.0624959,75.3419284,17z/data=!4m6!3m5!1s0x39111fde1ee6b8d5:0xb67f4c5005c2557a!8m2!3d30.0621617!4d75.3448681!16s%2Fg%2F11z15lpp6k?hl=en-US&entry=ttu&g_ep=EgoyMDI2MDQwMS4wIKXMDSoASAFQAw%3D%3D"
            title="Noor E Mahal Location" allowfullscreen="" loading="lazy"></iframe>
    </section>

    <!-- ============ FOOTER ============ -->
    <?php renderSiteFooter($content, $images); ?>

    <!-- Scripts -->
    <script src="<?= assetUrl('static/js/main.js') ?>"></script>
    <script>
        // Homepage interactive logic
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.hero-slider .slide');
            if (slides.length > 0) {
                let current = 0;
                setInterval(() => {
                    slides[current].classList.remove('active');
                    current = (current + 1) % slides.length;
                    slides[current].classList.add('active');
                }, 5000);
            }

            // Parallax Effect for Homepage Hero Scroll
            window.addEventListener('scroll', () => {
                const scrolled = window.scrollY;
                const parallaxBgs = document.querySelectorAll('.hero-slider .parallax-bg');
                const parallaxText = document.querySelector('.hero-slider .parallax-text');

                parallaxBgs.forEach(bg => {
                    bg.style.transform = `translateY(${scrolled * 0.35}px)`;
                });
                if (parallaxText) {
                    parallaxText.style.transform = `translateY(${scrolled * 0.45}px)`;
                    parallaxText.style.opacity = 1 - (scrolled / 600);
                }
            });

            const venueCards = document.querySelectorAll('.venue-card');
            const venueModal = document.getElementById('venueModal');
            const modalClose = document.getElementById('modalClose');
            const modalImg = document.getElementById('modalImg');
            const modalTitle = document.getElementById('modalTitle');
            const modalDesc = document.getElementById('modalDesc');

            const venueDescriptions = {
                'The Grand Hall': 'Our most iconic venue, the Grand Hall seamlessly blends Mughal architecture with modern elegance. It is built for majestic gatherings and unforgettable ceremonies.',
                'The Royal Gardens': 'Set among lush lawns and ornate fountains, the Royal Gardens transform your celebration into a regal outdoor experience.',
                'The Heritage Wing': 'A timeless space full of heritage charm, the Heritage Wing brings authentic palace luxury to intimate events.',
                'The Crown Courtyard': 'A versatile courtyard framed by royal arches, perfect for dramatic arrivals, receptions, and evening celebrations.',
                'The Crystal Ballroom': 'A luminous ballroom with shimmering details, crafted for grand dances, lavish decor and unforgettable receptions.'
            };

            function createSparkles(x, y) {
                const count = 10;
                for (let i = 0; i < count; i++) {
                    const sparkle = document.createElement('div');
                    sparkle.className = 'sparkle';
                    const angle = Math.random() * Math.PI * 2;
                    const distance = 50 + Math.random() * 70;
                    sparkle.style.left = `${x}px`;
                    sparkle.style.top = `${y}px`;
                    sparkle.style.setProperty('--tx', `${Math.cos(angle) * distance}px`);
                    sparkle.style.setProperty('--ty', `${Math.sin(angle) * distance}px`);
                    document.body.appendChild(sparkle);
                    setTimeout(() => sparkle.remove(), 900);
                }
            }

            venueCards.forEach(card => {
                card.addEventListener('click', (event) => {
                    const venueName = card.dataset.venue;
                    const imageUrl = card.dataset.img;
                    modalTitle.textContent = venueName;
                    modalImg.src = imageUrl;
                    modalImg.alt = venueName;
                    modalDesc.textContent = venueDescriptions[venueName] || 'Experience the grandeur of this magnificent venue designed for your royal celebration.';
                    venueModal.classList.add('active');
                    venueModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    createSparkles(event.clientX, event.clientY);
                });
            });

            function closeModal() {
                venueModal.classList.remove('active');
                venueModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = 'auto';
            }

            modalClose.addEventListener('click', closeModal);
            venueModal.addEventListener('click', (event) => {
                if (event.target === venueModal) closeModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && venueModal.classList.contains('active')) {
                    closeModal();
                }
            });
        });
    </script>
</body>

</html>