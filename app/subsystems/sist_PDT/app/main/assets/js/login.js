document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('senha');
    const cpfInput = document.getElementById('cpf');
    const formGroups = document.querySelectorAll('.form-group');

    // Toggle password visibility
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle visibility icons
            const showPasswordIcon = this.querySelector('.show-password');
            const hidePasswordIcon = this.querySelector('.hide-password');
            showPasswordIcon.style.display = type === 'password' ? 'block' : 'none';
            hidePasswordIcon.style.display = type === 'password' ? 'none' : 'block';
        });
    }

    // CPF mask
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
        });
    }

    // Remove error state on input
    formGroups.forEach(group => {
        const input = group.querySelector('input, select');
        if (input) {
            input.addEventListener('input', () => {
                group.classList.remove('error');
            });
        }
    });

    // Validate CPF
    function isValidCPF(cpf) {
        cpf = cpf.replace(/[^\d]/g, '');
        
        if (cpf.length !== 11) return false;
        
        // Check for repeated numbers
        if (/^(\d)\1+$/.test(cpf)) return false;
        
        // Validate digits
        let sum = 0;
        let remainder;
        
        for (let i = 1; i <= 9; i++) {
            sum += parseInt(cpf.substring(i-1, i)) * (11 - i);
        }
        
        remainder = (sum * 10) % 11;
        if (remainder === 10 || remainder === 11) remainder = 0;
        if (remainder !== parseInt(cpf.substring(9, 10))) return false;
        
        sum = 0;
        for (let i = 1; i <= 10; i++) {
            sum += parseInt(cpf.substring(i-1, i)) * (12 - i);
        }
        
        remainder = (sum * 10) % 11;
        if (remainder === 10 || remainder === 11) remainder = 0;
        if (remainder !== parseInt(cpf.substring(10, 11))) return false;
        
        return true;
    }

    // Form validation
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            let hasError = false;
            
            // Reset errors
            formGroups.forEach(group => group.classList.remove('error'));
            
            const nome = document.getElementById('nome').value.trim();
            const cpf = document.getElementById('cpf').value.trim();
            const senha = document.getElementById('senha').value;
            const tipoUsuario = document.getElementById('tipoUsuario').value;

            // Validate name
            if (!nome || nome.length < 3) {
                document.getElementById('nome').parentElement.classList.add('error');
                hasError = true;
            }

            // Validate CPF
            if (!cpf || !isValidCPF(cpf)) {
                document.getElementById('cpf').parentElement.classList.add('error');
                hasError = true;
            }

            // Validate password
            if (!senha || senha.length < 6) {
                document.getElementById('senha').parentElement.classList.add('error');
                hasError = true;
            }

            // Validate user type
            if (!tipoUsuario) {
                document.getElementById('tipoUsuario').parentElement.classList.add('error');
                hasError = true;
            }

            if (hasError) {
                return;
            }

            // Show loading state
            const submitButton = loginForm.querySelector('.btn-login');
            submitButton.classList.add('loading');
            submitButton.disabled = true;

            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                // Redirect based on user type
                switch(tipoUsuario) {
                    case 'professor':
                        window.location.href = 'dashboard-professor.html';
                        break;
                    case 'pdt':
                        window.location.href = 'dashboard-pdt.html';
                        break;
                    case 'regular':
                        window.location.href = 'dashboard-aluno.html';
                        break;
                }
            } catch (error) {
                console.error('Erro ao fazer login:', error);
                alert('Erro ao fazer login. Por favor, tente novamente.');
            } finally {
                submitButton.classList.remove('loading');
                submitButton.disabled = false;
            }
        });
    }
}); 