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
    <title>Contact — Noor E Mahal</title>
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
        .contact-hero { position: relative; height: 45vh; min-height: 320px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #3E2723; }
    </style>

    <!-- Full Stylesheets (Async) -->
    <link rel="preload" as="style" href="<?= assetUrl('static/css/style.css') ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= assetUrl('static/css/style.css') ?>"></noscript>
    <style>
        .contact-hero {
            position: relative;
            height: 40vh;
            min-height: 280px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 4s linear;
            transform: scale(1.05);
        }

        .contact-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .contact-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .contact-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(253, 248, 239, 0.15), rgba(107, 15, 26, 0.55));
            z-index: 2;
        }

        .contact-hero h1 {
            position: relative;
            z-index: 3;
            font-family: var(--font-heading);
            font-size: 3rem;
            color: var(--cream);
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .contact-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        .contact-info h2 {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            color: var(--maroon-deep);
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--cream);
            border: 1px solid rgba(197, 163, 85, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-dark);
            font-size: 1.1rem;
        }

        .contact-item-text h4 {
            font-family: var(--font-heading);
            font-size: 1rem;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .contact-item-text p,
        .contact-item-text a {
            font-family: var(--font-ui);
            font-size: 0.85rem;
            color: var(--text-light);
            text-decoration: none;
        }

        .contact-item-text a:hover {
            color: var(--gold-dark);
        }

        /* WhatsApp CTA */
        .whatsapp-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: #fff;
            padding: 14px 32px;
            border-radius: 4px;
            font-family: var(--font-ui);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-cta:hover {
            background: #1ebe5d;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.4);
        }

        .whatsapp-cta svg {
            width: 22px;
            height: 22px;
            fill: #fff;
        }

        .map-wrap {
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(197, 163, 85, 0.2);
        }

        .map-wrap iframe {
            width: 100%;
            height: 350px;
            border: 0;
            display: block;
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
                <li><a href="about.php">About Us</a></li>
                <li class="nav-separator">|</li>
                <li><a href="facilities.php">Facilities</a></li>
                <li><a href="contact.php" class="nav-cta active">Contact</a></li>
            </ul>
            <div class="nav-toggle" id="navToggle"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <section class="contact-hero">
        <div class="contact-slide active"><img
                src="<?= $images['contact_hero_1'] ?? 'static/Noor_e_mahal_ png (1).png' ?>"
                alt="Contact Hero 1" loading="lazy"></div>
        <div class="contact-slide"><img
                src="<?= $images['contact_hero_2'] ?? 'static/Noor_e_mahal_ png (5).png' ?>"
                alt="Contact Hero 2" loading="lazy"></div>
        <h1>Contact Us</h1>
    </section>

    <div class="contact-content">
        <div class="contact-grid">
            <div class="contact-info reveal">
                <h2>Get in Touch</h2>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path
                                d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                    </div>
                    <div class="contact-item-text">
                        <h4>Phone</h4>
                        <a href="tel:<?= str_replace(' ', '', $content['contact_phone'] ?? '+919876543210') ?>"><?= $content['contact_phone'] ?? '+91 98765 43210' ?></a>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                    </div>
                    <div class="contact-item-text">
                        <h4>Email</h4>
                        <a href="mailto:<?= $content['contact_email'] ?? 'royal@nooremahal.com' ?>"><?=
                            $content['contact_email'] ?? 'royal@nooremahal.com' ?></a>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                    </div>
                    <div class="contact-item-text">
                        <h4>Address</h4>
                        <p><?= $content['contact_address'] ?? 'Noor E Mahal Palace, Punjab, India' ?></p>
                    </div>
                </div>

                <!-- WhatsApp Button -->
                <a href="https://wa.me/919876543210?text=Hi%2C%20I%27m%20planning%20something" target="_blank"
                    class="whatsapp-cta">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Message us on WhatsApp
                </a>
            </div>

            <div class="map-wrap reveal delay-1">
                <iframe
                    src="https://www.google.com/maps/place/Noor+E+Mahal/@30.0624959,75.3419284,17z/data=!4m6!3m5!1s0x39111fde1ee6b8d5:0xb67f4c5005c2557a!8m2!3d30.0621617!4d75.3448681!16s%2Fg%2F11z15lpp6k?hl=en-US&entry=ttu&g_ep=EgoyMDI2MDQwMS4wIKXMDSoASAFQAw%3D%3D"
                    title="Noor E Mahal Location" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
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
                                <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" /></svg>
                                +92 451 234 5678
                            </a>
                        </li>
                        <li><a href="mailto:info@nooremahal.com"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" /></svg>info@nooremahal.com</a></li>
                        <li><a href="<?= $content['common_footer_website'] ?? 'https://www.nooremahal.com' ?>" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" /></svg><?= $content['common_footer_website'] ?? 'www.nooremahal.com' ?></a></li>
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
                <p>Designed for the most extraordinary celebrations &nbsp;|&nbsp; &copy; 2026 Noor E Mahal &nbsp;<span class="heart">♥</span>&nbsp; All Rights Reserved</p>
            </div>
        </div>
    </footer>
    <script src="<?= assetUrl('static/js/main.js') ?>" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.contact-slide');
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