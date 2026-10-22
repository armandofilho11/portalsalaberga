document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidade do Menu Mobile
    const mobileMenuBtn = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }

    // Fechar menu ao clicar fora
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.nav-actions') && navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
        }
    });

    // Fechar menu ao redimensionar a tela
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
        }
    });

    // Modal functionality
    const modalTrigger = document.querySelector('[data-toggle="modal"]');
    const modal = document.getElementById('modal-container-743975');

    // Abrir modal
    if (modalTrigger) {
        modalTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }

    // Função para abrir o modal
    function openModal() {
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }
    }

    // Função para fechar o modal
    function closeModal() {
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Fechar modal ao clicar fora
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Fechar modal com tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Validação do formulário
    const loginForm = document.querySelector('form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const senha = document.querySelector('input[name="senha"]').value;

            if (!email || !senha) {
                e.preventDefault();
                Swal.fire({
                  icon: 'error',
                  title: 'Campos obrigatórios',
                  text: 'Por favor, preencha todos os campos!',
                  customClass: {
                    popup: 'rounded-xl shadow-xl',
                  },
                });
                return false;
            }
        });
    }

    // Prevenção de múltiplos submits
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
            }
        });
    });
});


