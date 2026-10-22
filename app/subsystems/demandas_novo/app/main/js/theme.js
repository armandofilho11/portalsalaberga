(function () {
    const STORAGE_KEY = 'sgtm-theme';
    const THEMES = ['light', 'dark', 'lula'];
    const THEME_LABELS = {
        light: 'Claro',
        dark: 'Escuro',
        lula: 'Lula'
    };
    let decorationLayer = null;

    const getStoredTheme = () => {
        try {
            const storedTheme = localStorage.getItem(STORAGE_KEY);
            if (THEMES.includes(storedTheme)) {
                return storedTheme;
            }
        } catch (error) {
            console.warn('Não foi possível acessar o localStorage para o tema:', error);
        }
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        return prefersDark ? 'dark' : 'light';
    };

    const applyTheme = (theme) => {
        const nextTheme = THEMES.includes(theme) ? theme : 'light';
        document.documentElement.setAttribute('data-theme', nextTheme);
        document.documentElement.style.colorScheme = nextTheme === 'dark' ? 'dark' : 'light';

        updateLulaDecorations(nextTheme === 'lula');
        updateBrandLogos(nextTheme === 'lula');

        try {
            localStorage.setItem(STORAGE_KEY, nextTheme);
        } catch (error) {
            console.warn('Não foi possível salvar a preferência do tema:', error);
        }
    };

    const updateToggleButtons = () => {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const buttons = document.querySelectorAll('.theme-toggle');
        const currentIndex = THEMES.indexOf(currentTheme);
        const nextTheme = THEMES[(currentIndex + 1) % THEMES.length];

        buttons.forEach((button) => {
            const icon = button.querySelector('.theme-toggle__icon');
            const label = button.querySelector('.theme-toggle__label');

            if (icon) {
                const iconName = nextTheme === 'lula' ? 'fa-star' : nextTheme === 'dark' ? 'fa-moon' : 'fa-sun';
                icon.className = `theme-toggle__icon fa-solid ${iconName}`;
            }

            if (label) {
                label.textContent = THEME_LABELS[nextTheme];
            }

            const accessibleLabel = `Tema atual: ${THEME_LABELS[currentTheme]}. Mudar para ${THEME_LABELS[nextTheme]}.`;
            button.setAttribute('aria-label', accessibleLabel);
            button.setAttribute('title', accessibleLabel);
        });
    };

    const updateBrandLogos = (enabled) => {
        document.querySelectorAll('.system-brand-logo').forEach((logo) => {
            logo.src = enabled ? logo.dataset.lulaSrc : logo.dataset.defaultSrc;
        });
    };

    const updateLulaDecorations = (enabled) => {
        if (!enabled) {
            decorationLayer?.remove();
            decorationLayer = null;
            return;
        }

        if (!document.body) return;
        if (decorationLayer) return;

        decorationLayer = document.createElement('div');
        decorationLayer.className = 'lula-theme-decorations';
        decorationLayer.setAttribute('aria-hidden', 'true');

        ['top-left', 'top-right', 'bottom-left', 'bottom-right'].forEach((position) => {
            const logo = document.createElement('img');
            logo.className = `lula-theme-logo lula-theme-logo--${position}`;
            logo.src = '../assets/pt-removebg-preview.png';
            logo.alt = '';
            decorationLayer.appendChild(logo);
        });

        for (let index = 0; index < 16; index += 1) {
            const star = document.createElement('span');
            star.className = 'lula-theme-falling-star';
            star.textContent = '★';
            star.style.left = `${Math.random() * 100}%`;
            star.style.animationDuration = `${7 + Math.random() * 8}s`;
            star.style.animationDelay = `${Math.random() * -15}s`;
            star.style.setProperty('--star-size', `${8 + Math.random() * 10}px`);
            decorationLayer.appendChild(star);
        }

        document.body.appendChild(decorationLayer);
    };

    const theme = getStoredTheme();
    applyTheme(theme);

    document.addEventListener('DOMContentLoaded', () => {
        updateLulaDecorations(theme === 'lula');
        updateBrandLogos(theme === 'lula');
        updateToggleButtons();

        document.querySelectorAll('.theme-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                const currentIndex = THEMES.indexOf(currentTheme);
                const nextTheme = THEMES[(currentIndex + 1) % THEMES.length];
                applyTheme(nextTheme);
                updateToggleButtons();
            });
        });
    });
})();
