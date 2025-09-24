import './bootstrap';

import Alpine from 'alpinejs';

const storedTheme = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

if (storedTheme === 'dark' || (!storedTheme && prefersDark.matches)) {
    document.documentElement.classList.add('dark');
}

const handlePrefersChange = (event) => {
    if (!localStorage.getItem('theme')) {
        document.documentElement.classList.toggle('dark', event.matches);
        if (window.Alpine) {
            Alpine.store('theme').isDark = event.matches;
        }
    }
};

if (typeof prefersDark.addEventListener === 'function') {
    prefersDark.addEventListener('change', handlePrefersChange);
} else if (typeof prefersDark.addListener === 'function') {
    prefersDark.addListener(handlePrefersChange);
}

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        isDark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.isDark = !this.isDark;
            this.persist();
            this.apply();
        },
        apply() {
            document.documentElement.classList.toggle('dark', this.isDark);
        },
        persist() {
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
        init() {
            this.apply();
        },
    });

    Alpine.store('theme').init();
});

window.Alpine = Alpine;

Alpine.start();
