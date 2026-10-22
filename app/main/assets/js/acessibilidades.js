class AccessibilityManager {
    constructor() {
        this.initializeMenus();
        this.initializeTextSize();
        this.initializeThemes();
        this.initializeScreenReader();
        this.loadSavedPreferences();
    }

    initializeMenus() {
        const accessibilityBtn = document.getElementById('accessibilityBtn');
        const accessibilityMenu = document.getElementById('accessibilityMenu');
        const themeBtn = document.getElementById('themeBtn');
        const themeMenu = document.getElementById('themeMenu');

        accessibilityBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isExpanded = accessibilityBtn.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                this.closeDesktopMenus();
            } else {
                this.closeDesktopMenus();
                accessibilityMenu.classList.remove('hidden');
                accessibilityBtn.setAttribute('aria-expanded', 'true');
            }
        });

        themeBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isExpanded = themeBtn.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                themeMenu.classList.add('hidden');
                themeBtn.setAttribute('aria-expanded', 'false');
            } else {
                themeMenu.classList.remove('hidden');
                themeBtn.setAttribute('aria-expanded', 'true');
            }
        });

        document.addEventListener('click', () => {
            this.closeDesktopMenus();
        });

        [accessibilityMenu, themeMenu].forEach(menu => {
            menu?.addEventListener('click', (e) => e.stopPropagation());
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeDesktopMenus();
            }
        });
    }

    closeDesktopMenus() {
        const accessibilityBtn = document.getElementById('accessibilityBtn');
        const accessibilityMenu = document.getElementById('accessibilityMenu');
        const themeBtn = document.getElementById('themeBtn');
        const themeMenu = document.getElementById('themeMenu');

        accessibilityMenu?.classList.add('hidden');
        themeMenu?.classList.add('hidden');
        accessibilityBtn?.setAttribute('aria-expanded', 'false');
        themeBtn?.setAttribute('aria-expanded', 'false');
    }

    initializeTextSize() {
        const decreaseBtns = document.querySelectorAll('[aria-label="Diminuir tamanho do texto"]');
        const resetBtns = document.querySelectorAll('[aria-label="Tamanho padrão do texto"]');
        const increaseBtns = document.querySelectorAll('[aria-label="Aumentar tamanho do texto"]');

        this.baseSize = 16;
        this.currentSize = this.loadTextSize() || this.baseSize;
        this.updateTextSize(this.currentSize);

        decreaseBtns.forEach(btn => {
            btn.addEventListener('click', () => this.changeTextSize('decrease'));
        });

        resetBtns.forEach(btn => {
            btn.addEventListener('click', () => this.changeTextSize('reset'));
        });

        increaseBtns.forEach(btn => {
            btn.addEventListener('click', () => this.changeTextSize('increase'));
        });
    }

    changeTextSize(action) {
        switch(action) {
            case 'decrease':
                this.currentSize = Math.max(this.currentSize - 2, 12);
                break;
            case 'reset':
                this.currentSize = this.baseSize;
                break;
            case 'increase':
                this.currentSize = Math.min(this.currentSize + 2, 24);
                break;
        }

        this.updateTextSize(this.currentSize);
        this.saveTextSize();
    }

    updateTextSize(size) {
        document.documentElement.style.fontSize = `${size}px`;
        document.body.style.fontSize = `${size}px`;
    }

    initializeThemes() {
        const themeButtons = document.querySelectorAll('[data-theme]');
        
        themeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const theme = button.dataset.theme;
                this.applyTheme(theme);
                this.saveTheme(theme);
                this.closeDesktopMenus();
            });
        });
    }

    applyTheme(theme) {
        document.body.classList.remove(
            'theme-monochrome',
            'theme-inverted-grayscale',
            'theme-inverted-color'
        );

        if (theme !== 'original') {
            document.body.classList.add(`theme-${theme}`);
        }

        switch(theme) {
            case 'monochrome':
                document.documentElement.style.filter = 'grayscale(100%)';
                break;
            case 'inverted-grayscale':
                document.documentElement.style.filter = 'grayscale(100%) invert(100%)';
                break;
            case 'inverted-color':
                document.documentElement.style.filter = 'invert(100%)';
                break;
            default:
                document.documentElement.style.filter = 'none';
        }
    }

    initializeScreenReader() {
        const screenReaderBtns = document.querySelectorAll('.fa-ear-listen');
        let speaking = false;
        let currentUtterance = null;

        screenReaderBtns.forEach(icon => {
            const btn = icon.parentElement;
            btn.addEventListener('click', () => {
                if (speaking) {
                    this.stopReading();
                    speaking = false;
                    btn.classList.remove('bg-gray-100');
                } else {
                    this.startReading();
                    speaking = true;
                    btn.classList.add('bg-gray-100');
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.altKey && e.key === 'r') {
                const firstBtn = screenReaderBtns[0]?.parentElement;
                firstBtn?.click();
            }
        });
    }

    startReading() {
        if ('speechSynthesis' in window) {
            const content = this.getReadableContent();
            const utterance = new SpeechSynthesisUtterance(content);
            utterance.lang = 'pt-BR';
            utterance.rate = 1;
            utterance.pitch = 1;

            window.speechSynthesis.cancel(); 
            window.speechSynthesis.speak(utterance);
            this.currentUtterance = utterance;
        } else {
            alert('Seu navegador não suporta o leitor de tela.');
        }
    }

    stopReading() {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            this.currentUtterance = null;
        }
    }

    getReadableContent() {
        const mainContent = document.querySelector('main') || document.body;
        return mainContent.textContent.trim()
            .replace(/\s+/g, ' ') 
            .replace(/\n+/g, ' ');
    }

    loadSavedPreferences() {
        const savedSize = this.loadTextSize();
        if (savedSize) {
            this.updateTextSize(savedSize);
        }

        const savedTheme = localStorage.getItem('preferredTheme');
        if (savedTheme) {
            this.applyTheme(savedTheme);
        }
    }

    saveTextSize() {
        localStorage.setItem('textSize', this.currentSize);
    }

    loadTextSize() {
        return parseInt(localStorage.getItem('textSize')) || null;
    }

    saveTheme(theme) {
        localStorage.setItem('preferredTheme', theme);
    }
}

const themeStyles = document.createElement('style');
themeStyles.textContent = `
    /* Estilos para temas de alto contraste */
    .theme-monochrome {
        background-color: white !important;
        color: black !important;
    }

    .theme-inverted-grayscale {
        background-color: black !important;
        color: white !important;
    }

    .theme-inverted-color img {
        filter: invert(100%);
    }

    /* Transições suaves para mudanças de tema */
    body {
        transition: filter 0.3s ease-in-out;
    }

    /* Indicador visual para botões ativos */
    .active-feature {
        background-color: #e2e8f0;
        border-radius: 0.375rem;
    }
`;

document.head.appendChild(themeStyles);

document.addEventListener('DOMContentLoaded', () => {
    window.accessibilityManager = new AccessibilityManager();
});