<?php
require_once __DIR__ . '/includes/db.php';
$content = getSiteContent($db);
$images = getSiteImages($db);
$services = $db->query('SELECT * FROM services')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services — Noor E Mahal</title>
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
        .svc-hero { position: relative; height: 45vh; min-height: 320px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #3E2723; }
    </style>

    <!-- Full Stylesheets (Async) -->
    <link rel="preload" as="style" href="<?= assetUrl('static/css/style.css') ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= assetUrl('static/css/style.css') ?>"></noscript>
    <style>
        .svc-hero {
            position: relative;
            height: 45vh;
            min-height: 320px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .svc-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 4s linear;
            transform: scale(1.05);
        }

        .svc-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .svc-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .svc-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(253, 248, 239, 0.15), rgba(107, 15, 26, 0.55));
            z-index: 2;
        }

        .svc-hero h1 {
            position: relative;
            z-index: 3;
            font-family: var(--font-heading);
            font-size: 3rem;
            color: var(--cream);
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .svc-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .svc-block {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            margin-bottom: 60px;
            padding-bottom: 60px;
            border-bottom: 1px solid rgba(197, 163, 85, 0.15);
        }

        .svc-block:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .svc-block:nth-child(even) {
            direction: rtl;
        }

        .svc-block:nth-child(even)>* {
            direction: ltr;
        }

        @media (max-width: 768px) {
            .svc-block {
                grid-template-columns: 1fr;
            }

            .svc-block:nth-child(even) {
                direction: ltr;
            }
        }

        .svc-block-img {
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .svc-block-img img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .svc-block-img:hover img {
            transform: scale(1.04);
        }

        .svc-block-text h2 {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            color: var(--maroon-deep);
            margin-bottom: 14px;
            letter-spacing: 1px;
        }

        .svc-block-text p {
            font-family: var(--font-ui);
            font-size: 0.88rem;
            color: var(--text-body);
            line-height: 1.8;
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
                <li><a href="about.php">About</a></li>
                <li class="nav-separator">|</li>
                <li><a href="facilities.php">Facilities</a></li>
                <li class="nav-separator">|</li>
                <li><a href="services.php" class="active">Services</a></li>
                <li class="nav-separator">|</li>
                <li><a href="contact.php" class="nav-cta">Contact Us</a></li>
            </ul>
            <div class="nav-toggle" id="navToggle"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <section class="svc-hero">
        <div class="svc-slide active"><img src="static/Noor_e_mahal_ png (1).png"
                alt="Services Hero 1" loading="lazy"></div>
        <div class="svc-slide"><img src="static/Noor_e_mahal_ png (5).png"
                alt="Services Hero 2" loading="lazy"></div>
        <h1>Services</h1>
    </section>

    <div class="svc-content">
        <?php foreach($services as $svc): ?>
        <div class="svc-block reveal">
            <div class="svc-block-img">
                <img src="<?= htmlspecialchars($svc['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($svc['title'] ?? '') ?>" loading="lazy">
            </div>
            <div class="svc-block-text">
                <h2><?= htmlspecialchars($svc['title'] ?? '') ?></h2>
                <p><?= htmlspecialchars($svc['description'] ?? '') ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="<?= $images['footer_logo'] ?? 'static/Noor_e_mahal_ png (6).png' ?>" alt="Noor E Mahal" class="footer-logo" loading="lazy">
                    <h3>Noor E Mahal</h3>
                    <p><?= $content['common_footer_quote'] ?? 'Where every celebration becomes a royal legacy. Experience the grandeur of Punjabi heritage fused with timeless British elegance.' ?></p>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="tel:<?= str_replace(' ', '', $content['contact_phone_1'] ?? '+924512345678') ?>"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" /></svg><?= $content['contact_phone_1'] ?? '+92 451 234 5678' ?></a></li>
                        <li><a href="mailto:<?= $content['contact_email'] ?? 'info@nooremahal.com' ?>"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" /></svg><?= $content['contact_email'] ?? 'info@nooremahal.com' ?></a></li>
                        <li><a href="<?= $content['common_footer_website'] ?? 'https://www.nooremahal.com' ?>" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" /></svg><?= $content['common_footer_website'] ?? 'www.nooremahal.com' ?></a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php#venues">Venues</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                        <li><a href="contact.php"><?= $content['common_footer_cta'] ?? 'Book A Tour' ?></a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Social Media</h4>
                    <div class="footer-social">
                        <a href="<?= $content['common_footer_instagram'] ?? 'https://www.instagram.com/nooremahal_mansa/' ?>" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" /></svg></a>
                        <a href="<?= $content['common_footer_facebook'] ?? 'https://www.facebook.com/people/Noor-E-Mahal/61586134415662/' ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Noor E Mahal &nbsp;<span class="heart">♥</span>&nbsp; All Rights Reserved</p>
            </div>
        </div>
    </footer>
    <script src="<?= assetUrl('static/js/main.js') ?>" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.svc-slide');
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