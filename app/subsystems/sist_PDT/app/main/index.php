<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema PDT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#007A33',
                        secondary: '#FFA500',
                        'primary-hover': '#006429',
                        'secondary-hover': '#E69500',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="bg-gradient-to-br from-primary to-secondary min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">Bem-vindo Professor</h1>
            <p class="text-sm text-gray-500">Faça login para acessar o sistema PDT</p>
        </div>
        
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">';
            echo htmlspecialchars($_SESSION['error']);
            echo '</div>';
            unset($_SESSION['error']);
        }
        ?>
        
        <form id="loginForm" class="space-y-6" action="process/login.php" method="POST">
            <div class="space-y-2">
                <label for="matricula" class="block text-sm font-medium text-gray-700">Matrícula</label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="matricula" 
                        name="matricula" 
                        placeholder="Digite sua matrícula" 
                        required
                        maxlength="8"
                        pattern="[0-9]*"
                        inputmode="numeric"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/10 focus:border-primary transition-colors text-sm pr-20"
                    >
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-2">
                        <div id="ultimaMatricula" class="text-sm text-gray-500 cursor-pointer hover:text-primary hidden">
                            Última: <span id="ultimaMatriculaValor"></span>
                        </div>
                        <button 
                            type="button" 
                            id="toggleMatricula" 
                            class="text-gray-500 hover:text-primary focus:outline-none"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <span class="hidden text-red-500 text-xs">Por favor, digite sua matrícula (8 números)</span>
            </div>

            <button 
                type="submit" 
                name="entrar"
                class="w-full bg-primary hover:bg-primary-hover text-white font-medium py-3 px-6 rounded-lg transition-colors text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
            >
                Entrar
            </button>
        </form>
    </div>

    <script>
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            let isValid = true;

            // Reset previous errors
            form.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            form.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));

            // Validate matrícula format (8 numbers)
            const matricula = form.querySelector('#matricula');
            const matriculaRegex = /^\d{8}$/;
            if (!matricula.value || !matriculaRegex.test(matricula.value)) {
                isValid = false;
                matricula.parentElement.classList.add('error');
                matricula.parentElement.querySelector('.text-red-500').classList.remove('hidden');
            }

            if (isValid) {
                // Salvar a matrícula no localStorage antes de enviar o formulário
                localStorage.setItem('ultimaMatricula', matricula.value);
                form.submit();
            }
        });

        // Allow only numbers in matrícula field
        document.getElementById('matricula').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 8);
        });

        // Toggle matrícula visibility
        document.getElementById('toggleMatricula').addEventListener('click', function() {
            const matriculaInput = document.getElementById('matricula');
            const type = matriculaInput.type === 'password' ? 'text' : 'password';
            matriculaInput.type = type;
            
            // Atualizar o ícone do olho
            this.innerHTML = type === 'password' 
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" /><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" /></svg>';
        });

        // Carregar última matrícula ao iniciar a página
        document.addEventListener('DOMContentLoaded', function() {
            const ultimaMatricula = localStorage.getItem('ultimaMatricula');
            const ultimaMatriculaDiv = document.getElementById('ultimaMatricula');
            const ultimaMatriculaValor = document.getElementById('ultimaMatriculaValor');
            
            if (ultimaMatricula) {
                ultimaMatriculaDiv.classList.remove('hidden');
                ultimaMatriculaValor.textContent = ultimaMatricula;
                
                // Adicionar evento de clique para preencher a matrícula
                ultimaMatriculaDiv.addEventListener('click', function() {
                    document.getElementById('matricula').value = ultimaMatricula;
                });
            }
        });
    </script>
</body>
</html>