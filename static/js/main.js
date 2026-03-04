/* ============================================================
   NOOR E MAHAL — Main JavaScript
   Premium Animations & Interactivity
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ——— Preloader ———
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => preloader.classList.add('hidden'), 800);
        });
        // Fallback: hide after 3 seconds
        setTimeout(() => preloader.classList.add('hidden'), 3000);
    }

    // ——— Navbar Scroll Effect ———
    const navbar = document.querySelector('.navbar');
    const handleNavScroll = () => {
        if (window.scrollY > 80) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', handleNavScroll, { passive: true });
    handleNavScroll();

    // ——— Mobile Nav Toggle ———
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            navToggle.classList.toggle('active');
        });
        // Close on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                navToggle.classList.remove('active');
            });
        });
    }

    // ——— Smooth Scroll for anchor links ———
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const offset = 80;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

    // ——— Scroll Reveal (Intersection Observer) ———
    const revealElements = document.querySelectorAll(
        '.reveal, .reveal-left, .reveal-right, .reveal-scale'
    );
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });
    revealElements.forEach(el => revealObserver.observe(el));

    // ——— Parallax on Hero ———
    const heroBg = document.querySelector('.hero-bg img');
    if (heroBg) {
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                heroBg.style.transform = `scale(${1 + scrolled * 0.0002}) translateY(${scrolled * 0.3}px)`;
            }
        }, { passive: true });
    }

    // ——— Testimonials Carousel ———
    const track = document.querySelector('.testimonials-track');
    const dots = document.querySelectorAll('.testimonial-dot');
    if (track && dots.length > 0) {
        let currentSlide = 0;
        const cards = track.querySelectorAll('.testimonial-card');
        let cardsPerView = 3;

        const updateCardsPerView = () => {
            if (window.innerWidth <= 768) {
                cardsPerView = 1;
            } else if (window.innerWidth <= 1024) {
                cardsPerView = 2;
            } else {
                cardsPerView = 3;
            }
        };
        updateCardsPerView();

        const totalSlides = Math.ceil(cards.length / cardsPerView);

        const goToSlide = (index) => {
            currentSlide = index;
            const offset = -(currentSlide * (100 / totalSlides));
            // Actually move by cards
            const translateX = -(currentSlide * cardsPerView * (100 / cards.length));
            track.style.transform = `translateX(${translateX}%)`;
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        };

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => goToSlide(i));
        });

        // Auto-play
        let autoPlay = setInterval(() => {
            const next = (currentSlide + 1) % totalSlides;
            goToSlide(next);
        }, 5000);

        // Pause on hover
        const carousel = document.querySelector('.testimonials-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => clearInterval(autoPlay));
            carousel.addEventListener('mouseleave', () => {
                autoPlay = setInterval(() => {
                    const next = (currentSlide + 1) % totalSlides;
                    goToSlide(next);
                }, 5000);
            });
        }

        // Recalculate on resize
        window.addEventListener('resize', () => {
            updateCardsPerView();
            goToSlide(0);
        });

        goToSlide(0);
    }

    // ——— Counter Animation (for stats if present) ———
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length > 0) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.counted) {
                    entry.target.dataset.counted = 'true';
                    const target = parseInt(entry.target.dataset.count);
                    const duration = 2000;
                    const steps = 60;
                    const increment = target / steps;
                    let current = 0;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            entry.target.textContent = target + '+';
                            clearInterval(timer);
                        } else {
                            entry.target.textContent = Math.floor(current) + '+';
                        }
                    }, duration / steps);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(el => counterObserver.observe(el));
    }

    // ——— Tilt effect on service cards ———
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // ——— Typed Effect on Hero Title ———
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        heroTitle.style.opacity = '0';
        heroTitle.style.transform = 'translateY(30px)';
        setTimeout(() => {
            heroTitle.style.transition = 'opacity 1s ease, transform 1s ease';
            heroTitle.style.opacity = '1';
            heroTitle.style.transform = 'translateY(0)';
        }, 500);
    }
    const heroSubtitle = document.querySelector('.hero-subtitle');
    if (heroSubtitle) {
        heroSubtitle.style.opacity = '0';
        heroSubtitle.style.transform = 'translateY(20px)';
        setTimeout(() => {
            heroSubtitle.style.transition = 'opacity 1s ease, transform 1s ease';
            heroSubtitle.style.opacity = '1';
            heroSubtitle.style.transform = 'translateY(0)';
        }, 900);
    }
    const heroCta = document.querySelector('.hero-cta');
    if (heroCta) {
        heroCta.style.opacity = '0';
        heroCta.style.transform = 'translateY(20px)';
        setTimeout(() => {
            heroCta.style.transition = 'opacity 1s ease, transform 1s ease';
            heroCta.style.opacity = '1';
            heroCta.style.transform = 'translateY(0)';
        }, 1300);
    }

});
