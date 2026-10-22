document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidades de Acessibilidade
    const decreaseFontBtn = document.querySelector('[aria-label="Diminuir tamanho do texto"]');
    const resetFontBtn = document.querySelector('[aria-label="Tamanho padrão do texto"]');
    const increaseFontBtn = document.querySelector('[aria-label="Aumentar tamanho do texto"]');
    const themeToggleBtn = document.querySelector('.theme-toggle-btn');
    const themeOptions = document.querySelector('.theme-options');
    const themeOptionBtns = document.querySelectorAll('.theme-option');
    const accessibilityBtn = document.querySelector('[aria-label="Opções de acessibilidade"]');

    // Controle de Tamanho da Fonte
    function changeFontSize(action) {
        const body = document.body;
        const currentSize = parseFloat(window.getComputedStyle(body).fontSize);
        let newSize;

        switch(action) {
            case 'decrease':
                newSize = Math.max(currentSize - 2, 12);
                break;
            case 'increase':
                newSize = Math.min(currentSize + 2, 24);
                break;
            default:
                newSize = 16;
        }

        body.style.fontSize = `${newSize}px`;
        localStorage.setItem('fontSize', newSize);
    }

    // Event Listeners para controle de fonte
    if (decreaseFontBtn) {
        decreaseFontBtn.addEventListener('click', () => changeFontSize('decrease'));
    }
    if (resetFontBtn) {
        resetFontBtn.addEventListener('click', () => changeFontSize('reset'));
    }
    if (increaseFontBtn) {
        increaseFontBtn.addEventListener('click', () => changeFontSize('increase'));
    }

    // Controle de Tema
    function toggleThemeMenu() {
        if (themeToggleBtn && themeOptions) {
            const expanded = themeToggleBtn.getAttribute('aria-expanded') === 'true';
            themeToggleBtn.setAttribute('aria-expanded', String(!expanded));
            themeOptions.style.display = expanded ? 'none' : 'block';
        }
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', toggleThemeMenu);
    }

    // Mapa de temas
    const themeMap = {
        'Escuro': 'dark-mode',
        'Contraste aumentado': 'high-contrast',
        'Monocromático': 'monochrome',
        'Escala de cinza invertida': 'inverted-grayscale',
        'Cor invertida': 'inverted-color',
        'Cores originais': 'original'
    };

    let isDarkMode = false;

    function toggleDarkMode() {
        isDarkMode = !isDarkMode;
        document.body.classList.toggle('theme-dark-mode', isDarkMode);
        
        const colors = isDarkMode ? {
            '--background-color': '#121212',
            '--text-color': '#e0e0e0',
            '--link-color': '#bb86fc',
            '--button-bg': '#3700b3',
            '--button-color': '#ffffff'
        } : {
            '--background-color': '',
            '--text-color': '',
            '--link-color': '',
            '--button-bg': '',
            '--button-color': ''
        };

        Object.entries(colors).forEach(([property, value]) => {
            document.documentElement.style.setProperty(property, value);
        });

        const darkModeBtn = document.querySelector('.theme-option[data-theme="dark"]');
        if (darkModeBtn) {
            darkModeBtn.textContent = isDarkMode ? 'Modo Claro' : 'Modo Escuro';
        }

        localStorage.setItem('darkMode', isDarkMode);
    }

    // Event Listeners para temas
    themeOptionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.dataset.theme === 'dark') {
                toggleDarkMode();
            } else {
                const themeName = themeMap[this.textContent] || this.textContent.toLowerCase().replace(/\s+/g, '-');
                document.body.className = '';
                document.body.classList.add(`theme-${themeName}`);
            }
            toggleThemeMenu();
        });
    });

    // Fechar menu de temas ao clicar fora
    document.addEventListener('click', function(event) {
        if (themeToggleBtn && themeOptions && 
            !themeToggleBtn.contains(event.target) && 
            !themeOptions.contains(event.target)) {
            themeOptions.style.display = 'none';
            themeToggleBtn.setAttribute('aria-expanded', 'false');
        }
    });

    // Opções de Acessibilidade
    if (accessibilityBtn) {
        accessibilityBtn.addEventListener('click', () => {
            console.log('Opções de acessibilidade clicadas');
            // Implementar funcionalidade adicional conforme necessário
        });
    }

    // Carregar preferências salvas
    function loadSavedPreferences() {
        // Carregar modo escuro
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode === 'true') {
            toggleDarkMode();
        }

        // Carregar tamanho da fonte
        const savedFontSize = localStorage.getItem('fontSize');
        if (savedFontSize) {
            document.body.style.fontSize = `${savedFontSize}px`;
        }
    }

    // Inicializar preferências salvas
    loadSavedPreferences();
});


