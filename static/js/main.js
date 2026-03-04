/* ============================================================
   NOOR E MAHAL — Main JavaScript
   Premium 3D Parallax Animations & Interactivity
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ——— Preloader ———
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => preloader.classList.add('hidden'), 800);
        });
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

    // ============================================================
    //  3D PARALLAX — Hero Mouse Tracking
    //  Background moves in opposite direction, content follows mouse
    // ============================================================
    const hero = document.querySelector('.hero');
    const heroBg = document.querySelector('.hero-bg');
    const heroContent = document.querySelector('.hero-content');

    if (hero && heroBg) {
        // Mouse-tracking 3D parallax on hero
        hero.addEventListener('mousemove', (e) => {
            const rect = hero.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;  // -0.5 to 0.5
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            // Background: moves opposite to mouse for depth
            const bgX = x * -25;
            const bgY = y * -15;
            heroBg.style.transform = `translate3d(${bgX}px, ${bgY}px, -50px) scale(1.05)`;

            // Content: moves slightly with mouse for floating feel
            if (heroContent) {
                const contentX = x * 12;
                const contentY = y * 8;
                heroContent.style.transform = `translate3d(${contentX}px, ${contentY}px, 30px)`;
            }
        });

        hero.addEventListener('mouseleave', () => {
            heroBg.style.transform = 'translate3d(0, 0, -50px) scale(1.05)';
            if (heroContent) {
                heroContent.style.transform = 'translate3d(0, 0, 30px)';
            }
        });

        // Scroll parallax on hero (depth scrolling)
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                const parallaxY = scrolled * 0.4;
                const scale = 1.05 + scrolled * 0.0002;
                heroBg.style.transform = `translate3d(0, ${parallaxY}px, -50px) scale(${scale})`;

                // Fade content as you scroll down
                if (heroContent) {
                    const opacity = Math.max(0, 1 - scrolled / (window.innerHeight * 0.6));
                    heroContent.style.opacity = opacity;
                }
            }
        }, { passive: true });
    }

    // ============================================================
    //  3D PARALLAX — Venue Cards Mouse Tracking
    //  Each card tilts and shifts based on cursor position
    // ============================================================
    const venueCards = document.querySelectorAll('.venue-card');
    venueCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            const centerX = x - 0.5;
            const centerY = y - 0.5;

            // 3D rotation
            const rotateX = centerY * -15;
            const rotateY = centerX * 15;

            card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.03, 1.03, 1.03)`;
            card.style.boxShadow = `
                ${-centerX * 20}px ${-centerY * 20}px 40px rgba(0,0,0,0.25),
                0 0 20px rgba(197,163,85,0.15)
            `;

            // Move shine layer to follow mouse
            const shine = card.querySelector('.venue-shine');
            if (shine) {
                shine.style.background = `
                    radial-gradient(
                        circle at ${x * 100}% ${y * 100}%,
                        rgba(255,255,255,0.3) 0%,
                        rgba(255,255,255,0.05) 50%,
                        transparent 80%
                    )
                `;
                shine.style.opacity = '1';
            }
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(800px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
            card.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
            const shine = card.querySelector('.venue-shine');
            if (shine) shine.style.opacity = '0';
        });
    });

    // ============================================================
    //  3D PARALLAX — Palace Showcase Scroll
    //  Image moves slower than scroll for depth feel
    // ============================================================
    const palaceShowcase = document.querySelector('.palace-showcase');
    const palaceBg = document.querySelector('.palace-showcase-bg');
    const palaceText = document.querySelector('.palace-showcase-text');

    if (palaceShowcase) {
        window.addEventListener('scroll', () => {
            const rect = palaceShowcase.getBoundingClientRect();
            const viewH = window.innerHeight;

            if (rect.top < viewH && rect.bottom > 0) {
                const progress = (viewH - rect.top) / (viewH + rect.height);
                const translateY = (progress - 0.5) * -80;

                if (palaceBg) {
                    palaceBg.style.transform = `translate3d(0, ${translateY}px, 0) scale(1.05)`;
                }
                if (palaceText) {
                    const textY = (progress - 0.5) * 30;
                    palaceText.style.transform = `translate3d(0, ${textY}px, 0)`;
                }
            }
        }, { passive: true });
    }

    // ============================================================
    //  3D PARALLAX — Sections Scroll Depth
    //  Elements get subtle 3D rotation on scroll
    // ============================================================
    const parallaxSections = document.querySelectorAll('.services-section, .testimonials-section, .elephant-section');
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progress = entry.intersectionRatio;
                const el = entry.target;
                const rotateX = (1 - progress) * 3;
                el.style.transform = `perspective(1500px) rotateX(${rotateX}deg)`;
                el.style.transformOrigin = 'center bottom';
            }
        });
    }, { threshold: Array.from({ length: 20 }, (_, i) => i / 20) });
    parallaxSections.forEach(s => sectionObserver.observe(s));

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
            const translateX = -(currentSlide * cardsPerView * (100 / cards.length));
            track.style.transform = `translateX(${translateX}%)`;
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        };

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => goToSlide(i));
        });

        let autoPlay = setInterval(() => {
            const next = (currentSlide + 1) % totalSlides;
            goToSlide(next);
        }, 5000);

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

        window.addEventListener('resize', () => {
            updateCardsPerView();
            goToSlide(0);
        });
        goToSlide(0);
    }

    // ——— 3D Tilt on Service Cards ———
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 15;
            const rotateY = (centerX - x) / 15;
            card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px) translateZ(20px)`;
            card.style.boxShadow = `
                ${(centerX - x) / 8}px ${(centerY - y) / 8}px 40px rgba(0,0,0,0.12),
                0 0 0 1px rgba(197,163,85,0.2)
            `;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(800px) rotateX(0) rotateY(0) translateY(0) translateZ(0)';
            card.style.boxShadow = '';
        });
    });

    // ——— 3D Tilt on Testimonial Cards ———
    const testimonialInners = document.querySelectorAll('.testimonial-inner');
    testimonialInners.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            card.style.transform = `perspective(600px) rotateX(${y * -8}deg) rotateY(${x * 8}deg) scale(1.02)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(600px) rotateX(0) rotateY(0) scale(1)';
        });
    });

    // ——— Hero Title Reveal Animation ———
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        heroTitle.style.opacity = '0';
        heroTitle.style.transform = 'translateY(30px) translateZ(0)';
        setTimeout(() => {
            heroTitle.style.transition = 'opacity 1.2s ease, transform 1.2s ease';
            heroTitle.style.opacity = '1';
            heroTitle.style.transform = 'translateY(0) translateZ(0)';
        }, 500);
    }
    const heroSubtitle = document.querySelector('.hero-subtitle');
    if (heroSubtitle) {
        heroSubtitle.style.opacity = '0';
        heroSubtitle.style.transform = 'translateY(20px)';
        setTimeout(() => {
            heroSubtitle.style.transition = 'opacity 1.2s ease, transform 1.2s ease';
            heroSubtitle.style.opacity = '1';
            heroSubtitle.style.transform = 'translateY(0)';
        }, 900);
    }
    const heroCta = document.querySelector('.hero-cta');
    if (heroCta) {
        heroCta.style.opacity = '0';
        heroCta.style.transform = 'translateY(20px) scale(0.95)';
        setTimeout(() => {
            heroCta.style.transition = 'opacity 1s ease, transform 1s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            heroCta.style.opacity = '1';
            heroCta.style.transform = 'translateY(0) scale(1)';
        }, 1300);
    }

});
