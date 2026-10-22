document.addEventListener('DOMContentLoaded', function() {
    // Get user type from URL
    const urlParams = new URLSearchParams(window.location.search);
    const userType = urlParams.get('type');

    // Elementos do menu
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const menuItems = document.querySelectorAll('.menu-item');

    // Toggle do menu mobile
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }

    // Fechar menu ao clicar em um item (mobile)
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                menuToggle.classList.remove('active');
            }
        });
    });

    // Fechar menu ao clicar fora (mobile)
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !menuToggle.contains(event.target)) {
            sidebar.classList.remove('active');
            menuToggle.classList.remove('active');
        }
    });

    // Atualizar menu ativo
    function updateActiveMenu() {
        const currentPath = window.location.pathname;
        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href)) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Inicializar menu ativo
    updateActiveMenu();

    // Adicionar efeito de ripple nos itens do menu
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            
            const rect = item.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            item.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Ajustar layout em mudanças de tamanho
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            menuToggle.classList.remove('active');
        }
    });

    // Mobile Menu
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const body = document.body;

    function toggleMobileMenu() {
        mobileMenu?.classList.toggle('active');
        mobileMenuOverlay?.classList.toggle('active');
        body.style.overflow = mobileMenu?.classList.contains('active') ? 'hidden' : '';
    }

    function closeMobileMenu() {
        mobileMenu?.classList.remove('active');
        mobileMenuOverlay?.classList.remove('active');
        body.style.overflow = '';
    }

    // Event Listeners para o Menu Mobile
    mobileMenuToggle?.addEventListener('click', toggleMobileMenu);
    closeMenu?.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

    // Fechar menu ao pressionar ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu?.classList.contains('active')) {
            closeMobileMenu();
        }
    });

    // Fechar menu ao redimensionar a janela
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 768 && mobileMenu?.classList.contains('active')) {
                closeMobileMenu();
            }
        }, 250);
    });

    // Responsive adjustments
    function handleResponsive() {
        const width = window.innerWidth;
        if (width >= 768) {
            if (mobileMenu) {
                mobileMenu.classList.remove('active');
            }
        }
    }

    window.addEventListener('resize', handleResponsive);
    handleResponsive();
}); 