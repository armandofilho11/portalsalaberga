(function () {
    const storageKey = 'controle-estoque-theme';
    const root = document.documentElement;

    try {
        root.classList.toggle('stock-dark', localStorage.getItem(storageKey) === 'dark');
    } catch (error) {
        console.warn('Não foi possível carregar a preferência de tema do estoque:', error);
    }
    root.style.colorScheme = root.classList.contains('stock-dark') ? 'dark' : 'light';

    function setupThemeToggle() {
        if (document.querySelector('.stock-theme-toggle')) {
            return;
        }

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'stock-theme-toggle';
        button.innerHTML = '<i aria-hidden="true"></i>';

        function updateButton(isDark) {
            button.setAttribute('aria-pressed', String(isDark));
            button.setAttribute('aria-label', isDark ? 'Alternar para modo claro' : 'Alternar para modo escuro');
            button.title = isDark ? 'Alternar para modo claro' : 'Alternar para modo escuro';
            button.querySelector('i').className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }

        updateButton(root.classList.contains('stock-dark'));
        button.addEventListener('click', function () {
            const isDark = root.classList.toggle('stock-dark');
            root.style.colorScheme = isDark ? 'dark' : 'light';
            updateButton(isDark);
            document.dispatchEvent(new CustomEvent('stock-theme-change', { detail: { dark: isDark } }));

            try {
                localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
            } catch (error) {
                console.warn('Não foi possível salvar a preferência de tema do estoque:', error);
            }
        });

        document.body.appendChild(button);
    }

    function setupSidebarCollapseToggle() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar || document.querySelector('.stock-sidebar-collapse-toggle')) {
            return;
        }

        const storageKey = 'controle-estoque-sidebar-collapsed';
        const desktopQuery = window.matchMedia('(min-width: 769px)');
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'stock-sidebar-collapse-toggle';
        button.innerHTML = '<i class="fas fa-chevron-left" aria-hidden="true"></i>';

        let collapsedPreference = false;
        try {
            collapsedPreference = localStorage.getItem(storageKey) === 'true';
        } catch (error) {
            console.warn('Não foi possível carregar a preferência da sidebar do estoque:', error);
        }

        function updateButton() {
            const collapsed = desktopQuery.matches && collapsedPreference;
            document.body.classList.toggle('stock-sidebar-collapsed', collapsed);
            sidebar.setAttribute('aria-hidden', String(collapsed));
            sidebar.inert = collapsed;
            button.setAttribute('aria-expanded', String(!collapsed));
            button.setAttribute('aria-label', collapsed ? 'Mostrar menu lateral' : 'Ocultar menu lateral');
            button.title = collapsed ? 'Mostrar menu lateral' : 'Ocultar menu lateral';
            button.querySelector('i').className = collapsed
                ? 'fas fa-chevron-right'
                : 'fas fa-chevron-left';
        }

        button.addEventListener('click', function () {
            collapsedPreference = !collapsedPreference;
            try {
                localStorage.setItem(storageKey, String(collapsedPreference));
            } catch (error) {
                console.warn('Não foi possível salvar a preferência da sidebar do estoque:', error);
            }
            updateButton();
        });

        desktopQuery.addEventListener('change', updateButton);
        updateButton();
        document.body.appendChild(button);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setupThemeToggle();
            setupSidebarCollapseToggle();
        }, { once: true });
    } else {
        setupThemeToggle();
        setupSidebarCollapseToggle();
    }
})();
