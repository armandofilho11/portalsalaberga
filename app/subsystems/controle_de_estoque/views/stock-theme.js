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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupThemeToggle, { once: true });
    } else {
        setupThemeToggle();
    }
})();
