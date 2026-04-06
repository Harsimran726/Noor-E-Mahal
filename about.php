<?php
require_once __DIR__ . '/includes/db.php';
$content = getSiteContent($db);
$images = getSiteImages($db);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About — Noor E Mahal</title>
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
        .about-hero { position: relative; height: 45vh; min-height: 320px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #3E2723; }
    </style>

    <!-- Full Stylesheets (Async) -->
    <link rel="preload" as="style" href="<?= assetUrl('static/css/style.css') ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= assetUrl('static/css/style.css') ?>"></noscript>
    <style>
        .about-hero {
            position: relative;
            min-height: 82vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1b0f0d;
            isolation: isolate;
        }

        .about-hero::before,
        .about-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(20px);
            opacity: 0.55;
            z-index: 1;
        }

        .about-hero::before {
            width: 320px;
            height: 320px;
            top: 8%;
            left: 8%;
            background: radial-gradient(circle, rgba(197, 163, 85, 0.32), transparent 68%);
        }

        .about-hero::after {
            width: 420px;
            height: 420px;
            bottom: -8%;
            right: -4%;
            background: radial-gradient(circle, rgba(107, 15, 26, 0.5), transparent 66%);
        }

        .about-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 5s linear;
            transform: scale(1.08);
        }

        .about-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .about-slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10, 6, 5, 0.12), rgba(20, 10, 8, 0.76));
        }

        .about-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-hero-content {
            position: relative;
            z-index: 3;
            width: min(1180px, calc(100% - 48px));
            display: grid;
            justify-items: center;
            margin-top: 110px;
        }

        .about-hero-copy {
            width: min(860px, 100%);
            text-align: center;
        }

        .about-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            border-radius: 999px;
            background: rgba(197, 163, 85, 0.16);
            border: 1px solid rgba(197, 163, 85, 0.34);
            color: var(--cream);
            font-family: var(--font-body);
            letter-spacing: 3px;
            text-transform: uppercase;
            font-size: 0.8rem;
            margin-bottom: 18px;
        }

        .about-hero h1 {
            font-family: var(--font-heading);
            font-size: clamp(3.1rem, 6vw, 5.6rem);
            line-height: 0.95;
            color: var(--cream);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .about-hero-copy p,
        .about-hero-copy .about-hero-meta,
        .about-hero-card {
            display: none;
        }

        .about-content {
            width: min(1180px, calc(100% - 48px));
            margin: 0 auto;
            padding: 40px 0 110px;
            display: grid;
            gap: 28px;
        }

        .about-story {
            display: grid;
            grid-template-columns: 1fr;
            gap: 28px;
            align-items: stretch;
        }

        .about-story-panel,
        .about-story-visual,
        .about-quote,
        .about-legacy {
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid rgba(197, 163, 85, 0.18);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(250, 244, 232, 0.96));
            box-shadow: 0 25px 70px rgba(43, 24, 16, 0.08);
        }

        .about-story-panel {
            padding: 36px;
            position: relative;
        }

        .about-story-panel .about-story-intro {
            font-family: var(--font-body);
            font-size: 1.18rem;
            line-height: 1.85;
            color: var(--text-body);
            max-width: 760px;
            margin-bottom: 18px;
        }

        .about-section-label {
            display: inline-flex;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(107, 15, 26, 0.08);
            color: var(--maroon-deep);
            font-family: var(--font-body);
            letter-spacing: 3px;
            text-transform: uppercase;
            font-size: 0.78rem;
            margin-bottom: 18px;
        }

        .about-story-panel h2,
        .about-legacy h3,
        .about-quote h3 {
            font-family: var(--font-heading);
            color: var(--maroon-deep);
            letter-spacing: 1px;
        }

        .about-story-panel h2 {
            font-size: clamp(2.1rem, 3vw, 3.2rem);
            margin-bottom: 18px;
            line-height: 1.05;
        }

        .about-story-panel p,
        .about-legacy p,
        .about-quote p {
            font-family: var(--font-body);
            font-size: 1.08rem;
            line-height: 1.9;
            color: var(--text-body);
        }

        .about-story-panel p + p {
            margin-top: 14px;
        }

        .about-features {
            list-style: none;
            display: grid;
            gap: 14px;
            margin: 28px 0 0;
            padding: 0;
        }

        .about-features li {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(107, 15, 26, 0.04);
            border: 1px solid rgba(107, 15, 26, 0.08);
        }

        .about-features i {
            color: var(--gold);
            font-size: 1.15rem;
            margin-top: 4px;
        }

        .about-features strong {
            display: block;
            font-family: var(--font-heading);
            color: var(--text-dark);
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .about-features span {
            font-family: var(--font-body);
            color: var(--text-light);
            line-height: 1.6;
        }

        .about-story-visual {
            position: relative;
            min-height: 100%;
        }

        .about-story-visual img {
            width: 100%;
            height: 100%;
            min-height: 560px;
            object-fit: cover;
            display: block;
        }

        .about-visual-badge {
            position: absolute;
            left: 22px;
            bottom: 22px;
            padding: 14px 16px;
            border-radius: 18px;
            color: var(--cream);
            background: linear-gradient(135deg, rgba(107, 15, 26, 0.9), rgba(48, 22, 15, 0.88));
            border: 1px solid rgba(255, 255, 255, 0.16);
            max-width: 280px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.24);
        }

        .about-visual-badge strong {
            display: block;
            font-family: var(--font-heading);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .about-visual-badge span {
            font-family: var(--font-body);
            font-size: 0.98rem;
            line-height: 1.6;
        }

        .about-legacy {
            display: grid;
            grid-template-columns: 1fr 0.9fr;
            gap: 0;
        }

        .about-legacy-copy {
            padding: 34px 36px;
        }

        .about-legacy h3,
        .about-quote h3 {
            font-size: clamp(1.7rem, 2.4vw, 2.5rem);
            margin-bottom: 16px;
        }

        .about-legacy-media {
            min-height: 100%;
        }

        .about-legacy-media img {
            width: 100%;
            height: 100%;
            min-height: 340px;
            object-fit: cover;
            display: block;
        }

        .about-quote {
            padding: 32px 34px;
            text-align: center;
            background: linear-gradient(135deg, rgba(107, 15, 26, 0.08), rgba(197, 163, 85, 0.12));
        }

        .about-quote p {
            max-width: 780px;
            margin: 0 auto;
        }

        @media (max-width: 1024px) {
            .about-hero-content,
            .about-legacy {
                grid-template-columns: 1fr;
            }

            .about-hero-content {
                margin-top: 130px;
            }
        }

        @media (max-width: 768px) {
            .about-hero {
                min-height: 100vh;
                align-items: end;
            }

            .about-hero-content,
            .about-content {
                width: min(100% - 28px, 100%);
            }

            .about-hero-content {
                gap: 18px;
                margin-top: 0;
                padding-bottom: 28px;
            }

            .about-content {
                padding-top: 82px;
            }

            .about-story-panel,
            .about-legacy-copy,
            .about-quote {
                padding: 26px 22px;
                border-radius: 24px;
            }

            .about-story-visual img,
            .about-legacy-media img {
                min-height: 0;
                aspect-ratio: 4 / 5;
            }

            .about-visual-badge {
                left: 14px;
                right: 14px;
                bottom: 14px;
                max-width: none;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar scrolled" id="navbar">
        <div class="container">
            <a href="index.php" class="nav-brand">
                <img src="static/Noor_e_mahal_ png (6).png" alt="Logo" class="nav-logo" loading="lazy">
                <div class="nav-brand-text">Noor E Mahal <span>Story Begins in Royal Palace</span></div>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li class="nav-separator">|</li>
                <li><a href="gallery.php">Gallery</a></li>
                <li class="nav-separator">|</li>
                <li><a href="about.php" class="active">About Us</a></li>
                <li class="nav-separator">|</li>
                <li><a href="facilities.php">Facilities</a></li>
                <li><a href="contact.php" class="nav-cta">Contact</a></li>
            </ul>
            <div class="nav-toggle" id="navToggle"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <section class="about-hero">
        <div class="about-slide active"><img
                src="<?= $images['about_hero_1'] ?? 'static/Noor_e_mahal_ png (1).png' ?>"
                alt="About Hero 1" loading="lazy"></div>
        <div class="about-slide"><img
                src="<?= $images['about_hero_2'] ?? 'static/Noor_e_mahal_ png (5).png' ?>"
                alt="About Hero 2" loading="lazy"></div>
        <div class="about-hero-content">
            <div class="about-hero-copy">
                <div class="about-kicker">Palace Heritage • Bridal Grandeur • Timeless Hospitality</div>
                <h1><?= $content['about_hero_title'] ?? 'About Noor E Mahal' ?></h1>
            </div>
        </div>
    </section>

    <div class="about-content">
        <section class="about-story reveal">
            <div class="about-story-panel">
                <div class="about-section-label">Our Story</div>
                <p class="about-story-intro">Built to feel like a celebration from the future of royal hospitality,
                    Noor E Mahal blends Punjabi wedding tradition, cinematic scale, and palace elegance into one
                    unforgettable destination.</p>
                <h2><?= $content['about_section1_title'] ?? 'A heritage of Punjabi grandeur, rebuilt for tomorrow' ?></h2>
                <p><?= $content['about_section1_p1'] ?? 'Noor E Mahal is more than a marriage palace. It is a living stage where royal Punjabi architecture, ceremonial hospitality, and modern luxury are composed into one atmosphere of awe.' ?></p>
                <p>Inspired by the dignity of old courts and the drama of contemporary celebrations, the palace is shaped to
                    feel immersive from the first step inside to the final farewell under the lights.</p>

                <ul class="about-features">
                    <li>
                        <i class="fa-solid fa-crown"></i>
                        <div>
                            <strong>Palace-level presentation</strong>
                            <span>Every façade, hall, and garden is arranged like a cinematic wedding setting.</span>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-gem"></i>
                        <div>
                            <strong>Luxury with emotion</strong>
                            <span>We balance visual opulence with warmth so celebrations feel grand and personal.</span>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-ring"></i>
                        <div>
                            <strong>Made for milestone moments</strong>
                            <span>Weddings, receptions, and family traditions are supported by a setting built to impress.</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="about-story-visual">
                <img src="<?= $images['about_section_1'] ?? 'static/Noor_e_mahal_ png (1).png' ?>"
                    alt="Noor E Mahal facade" loading="lazy">
                <div class="about-visual-badge">
                    <strong>Majestic arrival</strong>
                    <span>A first impression shaped like a royal procession.</span>
                </div>
            </div>
        </section>

        <section class="about-legacy reveal">
            <div class="about-legacy-copy">
                <div class="about-section-label">Our Experience</div>
                <h3><?= $content['about_section2_title'] ?? 'Where every moment is staged like a royal procession' ?></h3>
                <p>From the grand entrance to the most intimate celebration corners, Noor E Mahal is designed to guide guests
                    through a sequence of visual moments that feel premium, calm, and memorable.</p>
                <p>Our hospitality, cuisine, and spatial design work together so the venue does not just host a wedding. It
                    becomes part of the story the family will remember for generations.</p>
            </div>
            <div class="about-legacy-media">
                <img src="<?= $images['about_section_3'] ?? 'static/Noor_e_mahal_ png (5).png' ?>"
                    alt="Palace gardens" loading="lazy">
            </div>
        </section>

        <section class="about-quote reveal">
            <h3>Built for love, framed like a legacy</h3>
            <p>Every dome, corridor, and garden path exists to create a sense of arrival that feels timeless. Noor E Mahal is
                crafted for couples and families who want their wedding to look and feel extraordinary.</p>
        </section>
    </div>

    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand -->
                <div class="footer-brand">
                    <img src="<?= $images['footer_logo'] ?? 'static/Noor_e_mahal_ png (6).png' ?>" alt="Noor E Mahal"
                        class="footer-logo" loading="lazy">
                    <h3>Noor E Mahal</h3>
                    <p><?= $content['common_footer_quote'] ?? 'Where every celebration becomes a royal legacy. Experience the grandeur of Punjabi heritage fused with timeless British elegance.' ?></p>
                </div>
                <!-- Contact -->
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li>
                            <a href="tel:+924512345678">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                                </svg>
                                +92 451 234 5678
                            </a>
                        </li>
                        <li>
                            <a href="mailto:info@nooremahal.com">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                                info@nooremahal.com
                            </a>
                        </li>
                        <li>
                            <a href="https://www.nooremahal.com" target="_blank">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                                </svg>
                                www.nooremahal.com
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php#venues">Venues</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                        <li><a href="contact.php"><?= $content['common_footer_cta'] ?? 'Book A Tour' ?></a></li>
                    </ul>
                </div>
                <!-- Social Media -->
                <div class="footer-col">
                    <h4>Social Media</h4>
                    <div class="footer-social">
                        <a href="<?= $content['common_footer_instagram'] ?? 'https://www.instagram.com/nooremahal_mansa/' ?>" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" /></svg></a>
                        <a href="<?= $content['common_footer_facebook'] ?? 'https://www.facebook.com/people/Noor-E-Mahal/61586134415662/' ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg></a>
                        <a href="#" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" /></svg></a>
                        <a href="#" aria-label="Twitter/X"><svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>Designed for the most extraordinary celebrations &nbsp;|&nbsp; &copy; 2026 Noor E Mahal &nbsp;<span
                        class="heart">♥</span>&nbsp; All Rights Reserved</p>
            </div>
        </div>
    </footer>
    <script src="<?= assetUrl('static/js/main.js') ?>" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.about-slide');
            let current = 0;
            setInterval(() => {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 5000);
        });
    </script>
</body>

</html>