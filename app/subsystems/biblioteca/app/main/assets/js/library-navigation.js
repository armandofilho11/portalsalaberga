(() => {
    try {
        const theme = localStorage.getItem('biblioteca-theme');
        if (theme === 'light' || theme === 'dark') {
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
        }
    } catch (error) {
        console.warn('Não foi possível carregar a preferência do tema da biblioteca:', error);
    }
})();
