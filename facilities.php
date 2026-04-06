<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/footer.php';
$content = getSiteContent($db);
$images = getSiteImages($db);
$facilities = $db->query('SELECT * FROM facilities')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities — Noor E Mahal</title>
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
        .fac-hero { position: relative; height: 58vh; min-height: 460px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: radial-gradient(circle at top, rgba(197, 163, 85, 0.16), transparent 32%), linear-gradient(180deg, #261310 0%, #4e1f18 45%, #180d0a 100%); }
        .fac-hero::before,
        .fac-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }
        .fac-hero::before {
            width: 360px; height: 360px; left: -120px; top: -90px; background: radial-gradient(circle, rgba(197, 163, 85, 0.22), transparent 68%);
            animation: facFloat 14s ease-in-out infinite;
        }
        .fac-hero::after {
            width: 280px; height: 280px; right: -80px; bottom: -80px; background: radial-gradient(circle, rgba(107, 15, 26, 0.24), transparent 68%);
            animation: facFloat 16s ease-in-out infinite reverse;
        }
        @keyframes facFloat {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(18px, 26px, 0) scale(1.08); }
        }
    </style>

    <!-- Full Stylesheets (Async) -->
    <link rel="preload" as="style" href="<?= assetUrl('static/css/style.css') ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= assetUrl('static/css/style.css') ?>"></noscript>
    <style>
        .fac-hero {
            position: relative;
            height: 58vh;
            min-height: 460px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fac-hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            padding: 0 24px;
        }

        .fac-kicker {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin-bottom: 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: rgba(253, 248, 239, 0.84);
            font-family: var(--font-ui);
            font-size: 0.72rem;
            letter-spacing: 2.4px;
            text-transform: uppercase;
            backdrop-filter: blur(12px);
        }

        .fac-kicker::before,
        .fac-kicker::after {
            content: '';
            width: 24px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(197, 163, 85, 0.9), transparent);
        }

        .fac-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 4s linear;
            transform: scale(1.08);
        }

        .fac-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .fac-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(1.06) contrast(1.04) brightness(0.95);
        }

        .fac-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to bottom, rgba(253, 248, 239, 0.12), rgba(107, 15, 26, 0.64)),
                radial-gradient(circle at center, transparent 35%, rgba(0, 0, 0, 0.2) 100%);
            z-index: 2;
        }

        .fac-hero h1 {
            position: relative;
            z-index: 3;
            font-family: var(--font-heading);
            font-size: clamp(3rem, 5vw, 5rem);
            color: var(--cream);
            letter-spacing: 7px;
            text-transform: uppercase;
            text-shadow: 0 6px 24px rgba(0, 0, 0, 0.42), 0 0 40px rgba(197, 163, 85, 0.16);
        }

        .fac-hero h1::before,
        .fac-hero h1::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(197, 163, 85, 0.88), transparent);
        }

        .fac-hero h1::before { left: -100px; }
        .fac-hero h1::after { right: -100px; }

        .fac-hero-badge {
            position: absolute;
            bottom: 34px;
            z-index: 3;
            padding: 12px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: rgba(253, 248, 239, 0.86);
            font-family: var(--font-ui);
            font-size: 0.74rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            backdrop-filter: blur(12px);
        }

        .fac-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 92px 24px 110px;
            position: relative;
        }

        .fac-content::before,
        .fac-content::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .fac-content::before {
            width: 360px;
            height: 360px;
            left: -120px;
            top: 120px;
            background: radial-gradient(circle, rgba(197, 163, 85, 0.12), transparent 70%);
        }

        .fac-content::after {
            width: 280px;
            height: 280px;
            right: -100px;
            bottom: 40px;
            background: radial-gradient(circle, rgba(107, 15, 26, 0.11), transparent 70%);
        }

        .fac-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 26px;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 900px) {
            .fac-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 560px) {
            .fac-grid {
                grid-template-columns: 1fr;
            }
        }

        .fac-card {
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            padding: 0;
            min-height: 520px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 0 26px 80px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(18px) saturate(150%);
            text-align: center;
            transition: transform 0.45s ease, box-shadow 0.45s ease, border-color 0.45s ease;
            cursor: default;
        }

        .fac-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 34px 95px rgba(0, 0, 0, 0.18);
            border-color: rgba(197, 163, 85, 0.42);
        }

        .fac-card::before {
            content: '';
            position: absolute;
            inset: 16px;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            z-index: 3;
            pointer-events: none;
        }

        .fac-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(7, 5, 4, 0.02) 0%, rgba(7, 5, 4, 0.16) 45%, rgba(107, 15, 26, 0.72) 100%),
                radial-gradient(circle at top right, rgba(197, 163, 85, 0.2), transparent 28%);
            z-index: 1;
            pointer-events: none;
        }

        .fac-card:nth-child(odd) { animation: facCardFloat 8s ease-in-out infinite; }
        .fac-card:nth-child(even) { animation: facCardFloat 9s ease-in-out infinite reverse; }

        @keyframes facCardFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-5px) scale(1.002); }
        }

        .fac-card-media {
            position: relative;
            min-height: 300px;
            overflow: hidden;
            border-radius: 30px 30px 24px 24px;
        }

        .fac-card-media::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.14));
            z-index: 1;
        }

        .fac-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transform: scale(1.05);
            transition: transform 0.85s ease, filter 0.85s ease;
            filter: saturate(1.08) contrast(1.05);
        }

        .fac-card:hover .fac-card-media img {
            transform: scale(1.12);
            filter: saturate(1.16) contrast(1.08) brightness(1.02);
        }

        .fac-card-glass {
            position: relative;
            z-index: 2;
            margin: -36px 18px 18px;
            padding: 24px 22px 20px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.26), rgba(255, 255, 255, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(16px) saturate(150%);
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.14);
        }

        .fac-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.24);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            font-size: 1.6rem;
            color: var(--gold);
            transition: all 0.35s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
        }

        .fac-card:hover .fac-card-icon {
            background: rgba(197, 163, 85, 0.9);
            color: var(--maroon-deep);
            border-color: rgba(197, 163, 85, 0.9);
        }

        .fac-card h3 {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            color: var(--cream);
            margin-bottom: 10px;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.36);
        }

        .fac-card p {
            font-family: var(--font-ui);
            font-size: 0.92rem;
            color: rgba(253, 248, 239, 0.86);
            line-height: 1.8;
        }

        .fac-card-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: rgba(253, 248, 239, 0.85);
            font-family: var(--font-ui);
            font-size: 0.68rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .fac-card-tag-icon {
            width: 18px;
            height: 18px;
            object-fit: contain;
            display: inline-block;
        }

        @media (max-width: 900px) {
            .fac-grid { grid-template-columns: 1fr 1fr; }
            .fac-card { min-height: 480px; }
        }

        @media (max-width: 560px) {
            .fac-grid { grid-template-columns: 1fr; }
            .fac-card { min-height: 460px; }
            .fac-hero h1::before,
            .fac-hero h1::after { width: 46px; }
            .fac-hero h1::before { left: -58px; }
            .fac-hero h1::after { right: -58px; }
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
                <li><a href="facilities.php" class="active">Facilities</a></li>
                <li><a href="contact.php" class="nav-cta">Contact</a></li>
            </ul>
            <div class="nav-toggle" id="navToggle"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <section class="fac-hero">
        <div class="fac-slide active">
            <img src="<?= $images['facilities_hero_1'] ?? 'static/Noor_e_mahal_ png (3).png' ?>"
                alt="Facilities Hero 1" loading="lazy">
        </div>
        <div class="fac-slide">
            <img src="<?= $images['facilities_hero_2'] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                alt="Facilities Hero 2" loading="lazy">
        </div>
        <div class="fac-hero-content">
            <div class="fac-kicker">Palace amenities</div>
            <h1>Facilities</h1>
        </div>
        <div class="fac-hero-badge">glassmorphic experience • royal comfort</div>
    </section>

    <!-- Facilities Content -->
    <section class="fac-content">
        <div class="container">
            <div class="section-heading reveal">
                <h2 class="gold-shimmer">Unmatched Amenities</h2>
                <p>Everything you need for an extraordinary celebration</p>
            </div>

            <div class="fac-grid">
                <?php foreach ($facilities as $index => $f): ?>
                <div class="fac-card reveal delay-<?= ($index % 6) + 1 ?>">
                    <div class="fac-card-media">
                        <?php if (!empty($f['image_url'])): ?>
                            <img src="<?= htmlspecialchars($f['image_url']) ?>" alt="<?= htmlspecialchars($f['name']) ?>" loading="lazy">
                        <?php else: ?>
                            <img src="<?= $images['facilities_card_' . ($index + 1)] ?? 'static/Noor_e_mahal_ png (6).png' ?>"
                                alt="<?= htmlspecialchars($f['name']) ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="fac-card-glass">
                        <div class="fac-card-tag">
                            <?php if (!empty($f['icon_url'])): ?>
                                <img src="<?= htmlspecialchars($f['icon_url']) ?>" alt="<?= htmlspecialchars($f['name']) ?> icon" class="fac-card-tag-icon" loading="lazy" decoding="async">
                            <?php else: ?>
                                <i class="<?= htmlspecialchars($f['icon_class'] ?? 'fas fa-star') ?>"></i>
                            <?php endif; ?>
                            Palace feature
                        </div>
                        <div class="fac-card-content">
                            <h3><?= htmlspecialchars($f['name']) ?></h3>
                            <p><?= htmlspecialchars($f['desc'] ?? '') ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php renderSiteFooter($content, $images); ?>
    <script src="<?= assetUrl('static/js/main.js') ?>" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.fac-slide');
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