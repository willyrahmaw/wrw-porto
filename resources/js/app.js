import AOS from 'aos';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.AOS = AOS;

// Theme switcher store & helper
Alpine.data('themeHandler', () => ({
    darkMode: localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : true,
    init() {
        this.applyTheme();
    },
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        this.applyTheme();
    },
    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}));

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // 1. Preloader (0 - 100 counter max ~1.2s) & Trigger Hero Reveal
    const preloader = document.getElementById('preloader');
    const counterEl = document.getElementById('preloader-counter');

    const finishLoading = () => {
        document.body.classList.add('loaded');
        AOS.init({
            duration: 900,
            easing: 'ease-out-cubic',
            once: true,
            offset: 80,
            disable: prefersReducedMotion,
        });
    };

    if (preloader && counterEl && !prefersReducedMotion && !sessionStorage.getItem('preloader_shown')) {
        let count = 0;
        const interval = setInterval(() => {
            count += Math.floor(Math.random() * 14) + 8;
            if (count >= 100) {
                count = 100;
                counterEl.textContent = '100';
                clearInterval(interval);
                setTimeout(() => {
                    preloader.classList.add('-translate-y-full');
                    sessionStorage.setItem('preloader_shown', '1');
                    finishLoading();
                }, 250);
            } else {
                counterEl.textContent = String(count).padStart(2, '0');
            }
        }, 75);
    } else {
        if (preloader) {
            preloader.style.display = 'none';
        }
        finishLoading();
    }

    // 2. Custom Magnetic Cursor with "View" State (Awwwards Grade)
    const cursor = document.getElementById('custom-cursor');
    const cursorText = document.getElementById('custom-cursor-text');

    if (cursor && window.matchMedia('(pointer: fine)').matches && !prefersReducedMotion) {
        let mouseX = window.innerWidth / 2, mouseY = window.innerHeight / 2;
        let cursorX = mouseX, cursorY = mouseY;
        let initialized = false;

        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            if (!initialized) {
                cursorX = mouseX;
                cursorY = mouseY;
                initialized = true;
            }
            cursor.classList.remove('opacity-0');
        });

        document.addEventListener('mouseleave', () => {
            cursor.classList.add('opacity-0');
        });

        document.addEventListener('mouseenter', () => {
            cursor.classList.remove('opacity-0');
        });

        const renderCursor = () => {
            cursorX += (mouseX - cursorX) * 0.2;
            cursorY += (mouseY - cursorY) * 0.2;
            cursor.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0) translate(-50%, -50%)`;
            requestAnimationFrame(renderCursor);
        };
        requestAnimationFrame(renderCursor);

        // Event delegation for smooth hover states (works with dynamic Alpine filters)
        document.addEventListener('mouseover', (e) => {
            const dataCursorEl = e.target.closest('[data-cursor]');
            if (dataCursorEl) {
                const label = dataCursorEl.getAttribute('data-cursor') || 'View';
                cursor.className = 'pointer-events-none fixed top-0 left-0 z-50 hidden md:flex items-center justify-center w-20 h-20 rounded-full bg-accent text-dark shadow-2xl transition-[width,height,background-color,border-color,opacity] duration-200 ease-out-expo';
                if (cursorText) {
                    cursorText.textContent = label;
                    cursorText.className = 'text-[11px] font-mono uppercase tracking-[0.2em] font-bold text-dark opacity-100 scale-100 transition-all duration-150 select-none text-center leading-none';
                }
                return;
            }

            const interactiveEl = e.target.closest('a, button, input, [role="button"]');
            if (interactiveEl) {
                cursor.className = 'pointer-events-none fixed top-0 left-0 z-50 hidden md:flex items-center justify-center w-8 h-8 rounded-full bg-accent/20 border border-accent transition-[width,height,background-color,border-color,opacity] duration-200 ease-out-expo shadow-md';
                if (cursorText) {
                    cursorText.className = 'opacity-0 scale-50 hidden';
                }
            }
        });

        document.addEventListener('mouseout', (e) => {
            const dataCursorEl = e.target.closest('[data-cursor]');
            const interactiveEl = e.target.closest('a, button, input, [role="button"]');

            if (dataCursorEl || interactiveEl) {
                cursor.className = 'pointer-events-none fixed top-0 left-0 z-50 hidden md:flex items-center justify-center w-3 h-3 rounded-full bg-accent shadow-xl shadow-black/30 transition-[width,height,background-color,border-color,opacity] duration-200 ease-out-expo';
                if (cursorText) {
                    cursorText.className = 'opacity-0 scale-50 hidden';
                }
            }
        });
    }

    // 3. Magnetic Buttons (Vanilla JS)
    if (!prefersReducedMotion && window.matchMedia('(pointer: fine)').matches) {
        document.querySelectorAll('.magnetic-btn').forEach((btn) => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                btn.style.transform = `translate3d(${x * 0.25}px, ${y * 0.25}px, 0)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translate3d(0px, 0px, 0px)';
            });
        });
    }

    // 4. Achievement Number Counter (IntersectionObserver)
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length > 0) {
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-counter'), 10);
                    const suffix = el.getAttribute('data-suffix') || '';

                    if (prefersReducedMotion) {
                        el.textContent = target + suffix;
                        observer.unobserve(el);
                        return;
                    }

                    let current = 0;
                    const step = Math.max(1, Math.ceil(target / 45));
                    const timer = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            el.textContent = target + suffix;
                            clearInterval(timer);
                        } else {
                            el.textContent = current + suffix;
                        }
                    }, 25);

                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach((c) => counterObserver.observe(c));
    }

    // 5. Real-time Local Clock (WIB / Asia/Jakarta)
    const updateClocks = () => {
        const now = new Date();
        const formatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        });
        const timeString = `${formatter.format(now).replace(/\./g, ':')} WIB`;
        document.querySelectorAll('.local-clock').forEach((el) => {
            el.textContent = timeString;
        });
    };
    updateClocks();
    setInterval(updateClocks, 1000);
});
