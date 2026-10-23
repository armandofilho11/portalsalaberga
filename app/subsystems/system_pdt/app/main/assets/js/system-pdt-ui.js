(() => {
    const storageKey = 'system-pdt-theme';
    const themes = ['light', 'dark'];
    const html = document.documentElement;

    let theme = 'light';
    try {
        const storedTheme = localStorage.getItem(storageKey);
        if (themes.includes(storedTheme)) theme = storedTheme;
    } catch (error) {
        console.warn('Não foi possível carregar a preferência de tema do PDT:', error);
    }

    const applyTheme = nextTheme => {
        theme = nextTheme;
        html.setAttribute('data-pdt-theme', theme);

        const button = document.querySelector('.pdt-theme-toggle');
        if (button) {
            const isDark = theme === 'dark';
            button.innerHTML = `<i class="bi ${isDark ? 'bi-sun-fill' : 'bi-moon-fill'}" aria-hidden="true"></i>`;
            button.setAttribute('aria-label', `Tema atual: ${isDark ? 'Escuro' : 'Claro'}. Mudar para ${isDark ? 'Claro' : 'Escuro'}.`);
            button.title = `Mudar para ${isDark ? 'Claro' : 'Escuro'}`;
        }
    };

    html.setAttribute('data-pdt-theme', theme);

    const setupInterface = () => {
        if (!document.body.classList.contains('pdt-interface')) return;

        const themeButton = document.createElement('button');
        themeButton.type = 'button';
        themeButton.className = 'pdt-theme-toggle';
        themeButton.addEventListener('click', () => {
            const nextTheme = theme === 'light' ? 'dark' : 'light';
            applyTheme(nextTheme);
            try {
                localStorage.setItem(storageKey, theme);
            } catch (error) {
                console.warn('Não foi possível salvar a preferência de tema do PDT:', error);
            }
        });
        document.body.appendChild(themeButton);
        applyTheme(theme);

        const footer = document.createElement('footer');
        footer.className = 'pdt-footer';
        footer.setAttribute('aria-label', 'Desenvolvedor do sistema');
        footer.innerHTML = '<span>Desenvolvido por</span><a href="https://www.instagram.com/_kauanlmx/" target="_blank" rel="noopener noreferrer" aria-label="Instagram de Kauan Cristian"><i class="bi bi-instagram" aria-hidden="true"></i><span>Kauan Cristian</span></a>';
        document.body.appendChild(footer);

        const sidebar = document.querySelector('.pdt-sidebar');
        const header = document.querySelector('.pdt-topbar');
        if (!sidebar || !header) return;

        let menuButton = document.getElementById('openMenuBtn');
        if (!menuButton) {
            menuButton = document.createElement('button');
            menuButton.type = 'button';
            menuButton.id = 'pdtMobileMenuButton';
            menuButton.className = 'pdt-mobile-menu-button';
            menuButton.setAttribute('aria-label', 'Abrir menu');
            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
            header.insertBefore(menuButton, header.firstChild);
        } else {
            menuButton.classList.add('pdt-mobile-menu-button');
            menuButton.setAttribute('aria-expanded', 'false');
        }

        const scrim = document.createElement('button');
        scrim.type = 'button';
        scrim.className = 'pdt-sidebar-scrim';
        scrim.setAttribute('aria-label', 'Fechar menu');
        document.body.appendChild(scrim);

        const closeMenu = () => {
            sidebar.classList.remove('is-open');
            scrim.classList.remove('is-visible');
            menuButton.setAttribute('aria-expanded', 'false');
        };
        const toggleMenu = () => {
            const isOpen = sidebar.classList.toggle('is-open');
            scrim.classList.toggle('is-visible', isOpen);
            menuButton.setAttribute('aria-expanded', String(isOpen));
        };

        menuButton.addEventListener('click', toggleMenu);
        scrim.addEventListener('click', closeMenu);
        sidebar.querySelectorAll('button[data-section], button[data-section-target], button.menu-item').forEach(button => {
            button.addEventListener('click', closeMenu);
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') closeMenu();
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) closeMenu();
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupInterface, { once: true });
    } else {
        setupInterface();
    }
})();
