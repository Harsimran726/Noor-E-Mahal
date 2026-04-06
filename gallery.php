<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/footer.php';
$content = getSiteContent($db);
$images = getSiteImages($db);
$images_qs = getAllImageAssets($db);
$gallery_cats = getGalleryCategories($db);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery — Noor E Mahal</title>
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
        .gallery-slider { position: relative; height: 72vh; min-height: 520px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: radial-gradient(circle at top, rgba(197, 163, 85, 0.18), transparent 34%), linear-gradient(180deg, #24130f 0%, #4a1f18 50%, #1a0d0a 100%); }
    </style>

    <!-- Full Stylesheets (Async) -->
    <link rel="preload" as="style" href="<?= assetUrl('static/css/style.css') ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= assetUrl('static/css/style.css') ?>"></noscript>
    <style>
        /* --- Gallery Hero Slider --- */
        .gallery-slider {
            position: relative;
            height: 72vh;
            min-height: 520px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-slider::before,
        .gallery-slider::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        .gallery-slider::before {
            width: 420px;
            height: 420px;
            left: -120px;
            top: -80px;
            background: radial-gradient(circle, rgba(197, 163, 85, 0.2), transparent 70%);
            animation: galleryOrbFloat 14s ease-in-out infinite;
        }

        .gallery-slider::after {
            width: 320px;
            height: 320px;
            right: -100px;
            bottom: -80px;
            background: radial-gradient(circle, rgba(107, 15, 26, 0.24), transparent 70%);
            animation: galleryOrbFloat 16s ease-in-out infinite reverse;
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 4s linear;
            transform: scale(1.05);
            /* Slight zoom out effect during slide */
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .slide-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background-position: center;
            /* Parallax effect on scroll via JS, but object-fit handles the image nicely */
        }

        .gallery-slider::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to bottom, rgba(253, 248, 239, 0.1), rgba(107, 15, 26, 0.72)),
                radial-gradient(circle at center, transparent 40%, rgba(0, 0, 0, 0.18) 100%);
            z-index: 2;
        }

        .gallery-slider h1 {
            position: relative;
            z-index: 3;
            font-family: var(--font-heading);
            font-size: clamp(3rem, 5vw, 5.2rem);
            color: var(--cream);
            letter-spacing: 7px;
            text-transform: uppercase;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.55), 0 0 40px rgba(197, 163, 85, 0.2);
            animation: fadeInDown 1.2s ease forwards;
            padding: 0 24px;
        }

        .gallery-slider h1::before,
        .gallery-slider h1::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 90px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(197, 163, 85, 0.85), transparent);
        }

        .gallery-slider h1::before { left: -110px; }
        .gallery-slider h1::after { right: -110px; }

        @keyframes galleryOrbFloat {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(18px, 28px, 0) scale(1.08); }
        }

        .gallery-hero-badge {
            position: absolute;
            bottom: 36px;
            z-index: 3;
            padding: 12px 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(12px);
            color: rgba(253, 248, 239, 0.88);
            font-family: var(--font-ui);
            font-size: 0.76rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.22);
        }

        /* Slider Controls */
        .slider-nav {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 4;
            display: flex;
            gap: 12px;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(253, 248, 239, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .slider-dot.active {
            background: var(--gold);
            transform: scale(1.3);
            border-color: var(--white);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- Filter Bar --- */
        .filter-bar {
            background: rgba(253, 248, 239, 0.84);
            padding: 20px 0;
            border-bottom: 1px solid rgba(197, 163, 85, 0.18);
            position: sticky;
            top: 70px;
            z-index: 50;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(14px);
        }

        .filter-bar .container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .filter-btn {
            font-family: var(--font-ui);
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 11px 24px;
            border: 1px solid rgba(197, 163, 85, 0.38);
            background: rgba(255, 255, 255, 0.72);
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.4s ease;
            border-radius: 999px;
            /* More premium pill shape */
            font-weight: 500;
            box-shadow: 0 10px 20px rgba(54, 31, 16, 0.04);
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--maroon-deep);
            color: var(--cream);
            border-color: var(--maroon-deep);
            box-shadow: 0 6px 15px rgba(107, 15, 26, 0.2);
            transform: translateY(-2px);
        }

        /* --- Premium Grid Layout --- */
        .gallery-grid {
            max-width: 1480px;
            margin: 0 auto;
            padding: 76px 24px 96px;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-auto-rows: 120px;
            gap: 22px;
            perspective: 1200px;
            /* For 3D transforms */
            position: relative;
        }

        .gallery-grid::before {
            content: '';
            position: absolute;
            inset: 28px 20px;
            border-radius: 34px;
            border: 1px solid rgba(197, 163, 85, 0.12);
            pointer-events: none;
        }

        .gallery-grid::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, transparent 0%, rgba(197, 163, 85, 0.05) 50%, transparent 100%),
                radial-gradient(circle at 30% 20%, rgba(197, 163, 85, 0.08), transparent 28%),
                radial-gradient(circle at 70% 75%, rgba(107, 15, 26, 0.08), transparent 26%);
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: 1fr;
                grid-auto-rows: 350px;
                padding: 52px 18px 72px;
            }

            .gallery-slider h1::before,
            .gallery-slider h1::after {
                width: 50px;
            }

            .gallery-slider h1::before { left: -68px; }
            .gallery-slider h1::after { right: -68px; }
        }

        /* 1, 2, 1, 2, 2 Pattern applied via nth-child (every 8 items repeat) 
           Row 1: 1 item (spans 2)
           Row 2: 2 items (span 1 each)
           Row 3: 1 item (spans 2)
           Row 4: 2 items (span 1 each)
           Row 5: 2 items (span 1 each)
        */
        @media (min-width: 769px) {

            .gallery-item {
                grid-column: span 4;
                grid-row: span 4;
            }
        }

        .gallery-item {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            cursor: pointer;
            box-shadow:
                0 18px 50px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
            /* Transform-style for 3D children */
            transform-style: preserve-3d;
            /* Will handle tilt in JS */
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.45s ease, filter 0.45s ease;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(18px) saturate(145%);
        }

        .gallery-item-inner {
            aspect-ratio: 1 / 1;
        }

        /* Inner Wrapper for Parallax & Zoom */
        .gallery-item-inner {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02));
        }

        .gallery-item-inner::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(0, 0, 0, 0.02) 0%, rgba(0, 0, 0, 0.18) 100%),
                radial-gradient(circle at top right, rgba(197, 163, 85, 0.22), transparent 26%);
            z-index: 1;
            transition: opacity 0.5s ease;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 1s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 1s ease;
            filter: saturate(1.06) contrast(1.04);
        }

        .gallery-item:hover img {
            transform: scale(1.05) translateZ(8px);
            filter: saturate(1.18) contrast(1.08) brightness(1.02);
            /* 3D pop effect */
        }

        .gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, transparent 28%, rgba(7, 5, 4, 0.12) 55%, rgba(107, 15, 26, 0.8) 100%),
                linear-gradient(90deg, rgba(255, 255, 255, 0.08), transparent 28%, transparent 72%, rgba(255, 255, 255, 0.08));
            opacity: 0.95;
            transition: opacity 0.4s ease;
            pointer-events: none;
            z-index: 2;
        }

        .gallery-item:hover::after {
            opacity: 0.98;
        }

        .gallery-item::before {
            content: '';
            position: absolute;
            inset: 14px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            z-index: 3;
            pointer-events: none;
            transition: opacity 0.45s ease, transform 0.45s ease;
        }

        .gallery-item:hover::before {
            transform: scale(0.985);
            border-color: rgba(197, 163, 85, 0.38);
        }

        .gallery-item:hover {
            transform: translateY(-6px) scale(1.005);
            box-shadow:
                0 28px 80px rgba(0, 0, 0, 0.22),
                0 0 0 1px rgba(197, 163, 85, 0.14);
        }

        @media (max-width: 768px) {
            .gallery-item {
                aspect-ratio: auto;
            }
        }

        .img-label {
            position: absolute;
            left: 22px;
            right: 22px;
            bottom: 20px;
            font-family: var(--font-heading);
            font-size: 1.15rem;
            color: var(--cream);
            letter-spacing: 0.4px;
            transform: translateY(30px) translateZ(40px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            pointer-events: none;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
            z-index: 4;
            min-height: 56px;
            display: flex;
            align-items: end;
        }

        .gallery-card-glass {
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 16px;
            z-index: 4;
            padding: 14px 16px 12px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.28), rgba(255, 255, 255, 0.12));
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(14px) saturate(150%);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.16);
            color: var(--cream);
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.55);
        }

        .gallery-card-glass .img-label {
            position: static;
            opacity: 1;
            transform: none;
            min-height: auto;
            display: block;
            font-size: 1.05rem;
            line-height: 1.25;
        }

        .gallery-card-glass .gallery-card-meta {
            display: block;
            margin-top: 8px;
            font-family: var(--font-ui);
            font-size: 0.68rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(253, 248, 239, 0.82);
        }

        .gallery-item:hover .img-label {
            transform: translateY(0) translateZ(40px);
            opacity: 1;
        }

        .gallery-item:hover .gallery-item-inner::before {
            opacity: 0.85;
        }

        .gallery-item:nth-child(3n + 1) img {
            object-position: center top;
        }

        .gallery-item:nth-child(3n + 2) img {
            object-position: center center;
        }

        .gallery-item:nth-child(3n + 3) img {
            object-position: center 20%;
        }

        .gallery-item[data-hidden="true"] {
            display: none !important;
        }

        .gallery-popup {
            position: fixed;
            inset: 0;
            background: rgba(18, 11, 9, 0.6);
            backdrop-filter: blur(18px) saturate(160%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 7000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        .gallery-popup.active {
            opacity: 1;
            visibility: visible;
        }

        .gallery-popup-panel {
            width: min(92vw, 760px);
            border-radius: 30px;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0.1)),
                radial-gradient(circle at top left, rgba(197, 163, 85, 0.18), transparent 34%);
            border: 1px solid rgba(255, 255, 255, 0.28);
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.38);
            color: var(--cream);
            backdrop-filter: blur(16px) saturate(155%);
        }

        .gallery-popup-media {
            position: relative;
            min-height: 360px;
            background: linear-gradient(145deg, rgba(107, 15, 26, 0.9), rgba(48, 24, 13, 0.95));
        }

        .gallery-popup-media img {
            width: 100%;
            height: 100%;
            min-height: 360px;
            object-fit: cover;
            display: block;
        }

        .gallery-popup-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(7, 5, 4, 0.05), rgba(7, 5, 4, 0.58));
        }

        .gallery-popup-content {
            padding: 28px 28px 30px;
            position: relative;
        }

        .gallery-popup-content::before {
            content: '';
            position: absolute;
            inset: 16px 24px auto;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(197, 163, 85, 0.8), transparent);
        }

        .gallery-popup-title {
            font-family: var(--font-heading);
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            margin-bottom: 12px;
            color: var(--cream);
        }

        .gallery-popup-text {
            font-family: var(--font-ui);
            font-size: 0.98rem;
            line-height: 1.9;
            color: rgba(253, 248, 239, 0.86);
            margin-bottom: 18px;
        }

        .gallery-popup-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-family: var(--font-ui);
            text-transform: uppercase;
            letter-spacing: 1.8px;
            font-size: 0.72rem;
        }

        .gallery-popup-close {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: none;
            background: rgba(253, 248, 239, 0.14);
            color: var(--cream);
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 2;
            backdrop-filter: blur(10px);
        }

        .gallery-popup-close:hover {
            background: var(--gold);
            color: var(--maroon-deep);
        }

        .gallery-parallax-layer {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .gallery-parallax-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(18px);
            opacity: 0.7;
            animation: orbPulse 10s ease-in-out infinite;
        }

        .gallery-parallax-orb.one { width: 260px; height: 260px; left: -90px; top: 18%; background: rgba(197, 163, 85, 0.16); }
        .gallery-parallax-orb.two { width: 180px; height: 180px; right: 5%; top: 42%; background: rgba(107, 15, 26, 0.14); animation-delay: -3s; }
        .gallery-parallax-orb.three { width: 320px; height: 320px; left: 58%; bottom: -120px; background: rgba(255, 255, 255, 0.08); animation-delay: -6s; }

        .gallery-mesh {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.5), transparent 78%);
            opacity: 0.35;
        }

        @keyframes orbPulse {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(12px, -18px, 0) scale(1.08); }
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar scrolled" id="navbar">
        <div class="container">
            <a href="index.php" class="nav-brand">
                <img src="static/Noor_e_mahal_ png (6).png" alt="Logo" class="nav-logo" loading="lazy">
                <div class="nav-brand-text">Noor E Mahal <span>Story Begins in Royal Palace</span></div>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li class="nav-separator">|</li>
                <li><a href="gallery.php" class="active">Gallery</a></li>
                <li class="nav-separator">|</li>
                <li><a href="about.php">About Us</a></li>
                <li class="nav-separator">|</li>
                <li><a href="facilities.php">Facilities</a></li>
                <li><a href="contact.php" class="nav-cta">Contact</a></li>
            </ul>
            <div class="nav-toggle" id="navToggle"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <!-- Hero Slider -->
    <section class="gallery-slider" id="gallerySlider">
        <div class="gallery-parallax-layer" aria-hidden="true">
            <span class="gallery-parallax-orb one"></span>
            <span class="gallery-parallax-orb two"></span>
            <span class="gallery-parallax-orb three"></span>
            <span class="gallery-mesh"></span>
        </div>
        <div class="slide active">
            <img src="<?= $images['gallery_hero_1'] ?? 'static/Noor_e_mahal_ png (1).png' ?>" class="slide-bg parallax-bg"
                alt="Gallery Hero 1" loading="lazy">
        </div>
        <div class="slide">
            <img src="<?= $images['gallery_hero_2'] ?? 'static/Noor_e_mahal_ png (5).png' ?>" class="slide-bg parallax-bg"
                alt="Gallery Hero 2" loading="lazy">
        </div>

        <h1 class="parallax-text">Our Grandeur</h1>
        <div class="gallery-hero-badge">premium kings wedding visual archive</div>

        <div class="slider-nav">
            <div class="slider-dot active" data-index="0"></div>
            <div class="slider-dot" data-index="1"></div>
        </div>
    </section>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="container">
            <button class="filter-btn active" data-cat="all">All</button>
            <?php foreach ($gallery_cats as $cat): ?>
                <button class="filter-btn" data-cat="<?= htmlspecialchars($cat['slug']) ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid" id="galleryGrid">
        <?php foreach($images_qs as $img): ?>
            <?php if (strpos($img['category'], 'gallery_') === 0): ?>
            <div class="gallery-item tilt-card" data-category="<?= htmlspecialchars($img['category'] ?? '') ?>">
                <div class="gallery-item-inner">
                    <img src="<?= htmlspecialchars($img['url'] ?? '') ?>" alt="<?= htmlspecialchars($img['alt_text'] ?? '') ?>" loading="lazy">
                </div>
                <div class="gallery-card-glass">
                    <div class="img-label"><?= htmlspecialchars($img['alt_text'] ?? '') ?></div>
                    <span class="gallery-card-meta">Tap to Open Preview</span>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="gallery-popup" id="galleryPopup" aria-hidden="true">
        <div class="gallery-popup-panel" role="dialog" aria-modal="true" aria-labelledby="galleryPopupTitle">
            <button class="gallery-popup-close" id="galleryPopupClose" aria-label="Close preview">&times;</button>
            <div class="gallery-popup-media">
                <img id="galleryPopupImg" src="" alt="Gallery preview">
                <div class="gallery-popup-overlay"></div>
            </div>
            <div class="gallery-popup-content">
                <div class="gallery-popup-chip">glassmorphic royal gallery</div>
                <h2 class="gallery-popup-title" id="galleryPopupTitle">Preview</h2>
                <p class="gallery-popup-text" id="galleryPopupText">A closer look at Noor E Mahal’s premium celebration imagery.</p>
            </div>
        </div>
    </div>

    <?php renderSiteFooter($content, $images); ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');
            const galleryPopup = document.getElementById('galleryPopup');
            const galleryPopupImg = document.getElementById('galleryPopupImg');
            const galleryPopupTitle = document.getElementById('galleryPopupTitle');
            const galleryPopupText = document.getElementById('galleryPopupText');
            const galleryPopupClose = document.getElementById('galleryPopupClose');
            const gallerySlider = document.querySelector('.gallery-hero-slider');
            const galleryGrid = document.getElementById('galleryGrid');
            const galleryDescriptions = {
                default: 'A closer look at Noor E Mahal’s premium celebration imagery.'
            };

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const cat = btn.dataset.cat;

                    galleryItems.forEach(item => {
                        // Reset tilt transforms before hiding to prevent layout bugs
                        item.style.transform = 'none';

                        if (cat === 'all' || item.dataset.category === cat) {
                            item.removeAttribute('data-hidden');
                        } else {
                            item.setAttribute('data-hidden', 'true');
                        }
                    });
                });
            });

            galleryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const img = item.querySelector('img');
                    const label = item.querySelector('.img-label')?.textContent?.trim() || 'Royal preview';
                    if (!galleryPopup || !galleryPopupImg || !galleryPopupTitle || !galleryPopupText) return;

                    galleryPopupImg.src = img?.src || '';
                    galleryPopupImg.alt = img?.alt || label;
                    galleryPopupTitle.textContent = label;
                    galleryPopupText.textContent = galleryDescriptions.default;
                    galleryPopup.classList.add('active');
                    galleryPopup.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                });
            });

            const closeGalleryPopup = () => {
                if (!galleryPopup) return;
                galleryPopup.classList.remove('active');
                galleryPopup.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            galleryPopupClose?.addEventListener('click', closeGalleryPopup);
            galleryPopup?.addEventListener('click', (event) => {
                if (event.target === galleryPopup) closeGalleryPopup();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeGalleryPopup();
            });

            // --- 2. Hero Slider ---
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;
            let slideInterval;

            const goToSlide = (index) => {
                slides[currentSlide].classList.remove('active');
                dots[currentSlide].classList.remove('active');
                currentSlide = index;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            };

            const nextSlide = () => {
                let next = (currentSlide + 1) % slides.length;
                goToSlide(next);
            };

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    clearInterval(slideInterval);
                    goToSlide(index);
                    slideInterval = setInterval(nextSlide, 5000);
                });
            });

            slideInterval = setInterval(nextSlide, 5000);

            // --- 3. Parallax Effect on scroll ---
            window.addEventListener('scroll', () => {
                const scrolled = window.scrollY;
                const parallaxBgs = document.querySelectorAll('.parallax-bg');
                const parallaxText = document.querySelector('.parallax-text');
                const galleryOrbs = document.querySelectorAll('.gallery-parallax-orb');

                // Move backgrounds down slightly
                parallaxBgs.forEach(bg => {
                    bg.style.transform = `translateY(${scrolled * 0.4}px)`;
                });
                // Move text down a bit faster for depth
                if (parallaxText) {
                    parallaxText.style.transform = `translateY(${scrolled * 0.6}px)`;
                    parallaxText.style.opacity = 1 - (scrolled / 500);
                }

                galleryOrbs.forEach((orb, index) => {
                    const depth = (index + 1) * 0.12;
                    orb.style.transform = `translate3d(0, ${scrolled * depth}px, 0)`;
                });

                if (gallerySlider) {
                    const sliderOffset = gallerySlider.getBoundingClientRect().top;
                    const sliderShift = Math.max(Math.min(sliderOffset * -0.06, 28), -28);
                    const galleryTitle = gallerySlider.querySelector('h1');
                    const galleryBadge = gallerySlider.querySelector('.gallery-hero-badge');

                    if (galleryTitle) {
                        galleryTitle.style.transform = `translateY(${sliderShift}px)`;
                    }

                    if (galleryBadge) {
                        galleryBadge.style.transform = `translateY(${sliderShift * 0.55}px)`;
                    }
                }

                if (galleryGrid) {
                    const gridShift = Math.max(Math.min(scrolled * 0.035, 28), 0);
                    galleryGrid.style.transform = `translateY(${gridShift}px)`;
                }
            });

            // --- 4. JS 3D Hover Tilt Effect (Premium feel) ---
            const tiltCards = document.querySelectorAll('.tilt-card');

            tiltCards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    // Only apply 3D tilt on desktop
                    if (window.innerWidth <= 768) return;

                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left; // x position within the element.
                    const y = e.clientY - rect.top; // y position within the element.

                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    const rotateX = ((y - centerY) / centerY) * -4;
                    const rotateY = ((x - centerX) / centerX) * 4;

                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.008, 1.008, 1.008)`;
                    card.style.zIndex = 10;
                    card.style.transition = 'transform 0.1s ease-out';
                });

                card.addEventListener('mouseleave', () => {
                    if (window.innerWidth <= 768) return;
                    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                    card.style.zIndex = 1;
                    card.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), z-index 0s 0.6s';
                });
            });
        });
    </script>
</body>

</html>