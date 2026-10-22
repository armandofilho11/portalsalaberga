class EnhancedScroll {
    constructor(options = {}) {
        this.options = {
            smoothness: 0.1,
            ...options
        };
        this.init();
    }

    init() {
        this.addScrollListeners();
        this.setupFadeInObserver();
    }

    addScrollListeners() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', this.smoothScroll.bind(this));
        });
    }

    smoothScroll(e) {
        e.preventDefault();
        const targetId = e.currentTarget.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            this.scrollToElement(targetElement);
        }
    }

    scrollToElement(targetElement) {
        const startPosition = window.pageYOffset;
        const targetPosition = targetElement.getBoundingClientRect().top + startPosition;
        const windowHeight = window.innerHeight;
        const targetHeight = targetElement.offsetHeight;

        let targetY;
        if (targetHeight < windowHeight) {
            // Centralizar a seção na tela
            targetY = targetPosition - (windowHeight - targetHeight) / 2;
        } else {
            // Rolar até o topo da seção
            targetY = targetPosition;
        }

        this.scrollTo(targetY);
    }

    scrollTo(targetY) {
        const startPosition = window.pageYOffset;
        const distance = targetY - startPosition;
        let startTime = null;

        const animation = (currentTime) => {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = this.ease(timeElapsed, startPosition, distance, 600);
            window.scrollTo(0, run);
            if (timeElapsed < 600) requestAnimationFrame(animation);
        };

        requestAnimationFrame(animation);
    }

    ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }

    setupFadeInObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('section').forEach((section) => {
            observer.observe(section);
        });
    }
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    new EnhancedScroll();
});
