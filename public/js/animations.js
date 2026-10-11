// Scroll-triggered animations
document.addEventListener('DOMContentLoaded', function() {
    const hero = document.querySelector('.hero-section[data-slides]');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (hero && !prefersReducedMotion) {
        const imageSources = hero.dataset.slides.split('|').filter(Boolean);

        if (imageSources.length > 1) {
            const slideshow = document.createElement('div');
            slideshow.className = 'hero-slideshow';
            slideshow.setAttribute('aria-hidden', 'true');

            const slides = imageSources.map((source, index) => {
                const slide = document.createElement('div');
                slide.className = 'hero-slide';
                slide.style.backgroundImage = `url("${source}")`;

                if (index === 0) {
                    slide.classList.add('is-active');
                }

                slideshow.appendChild(slide);

                const preload = new Image();
                preload.src = source;

                return slide;
            });

            hero.insertBefore(slideshow, hero.firstElementChild);

            let activeIndex = 0;
            window.setInterval(() => {
                const nextIndex = (activeIndex + 1) % slides.length;
                slides[nextIndex].classList.add('is-active');
                slides[activeIndex].classList.remove('is-active');
                activeIndex = nextIndex;
            }, 7000);
        }
    }

    // Elements to animate
    const animatedElements = document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in');
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // Only animate once
            }
        });
    }, observerOptions);
    
    // Observe all animated elements
    animatedElements.forEach(element => {
        observer.observe(element);
    });
    
    // Initial check for elements already in view
    setTimeout(() => {
        animatedElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                element.classList.add('visible');
            }
        });
    }, 100);
});