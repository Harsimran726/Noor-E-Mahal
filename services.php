<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/footer.php';
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

    <?php renderSiteFooter($content, $images); ?>
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