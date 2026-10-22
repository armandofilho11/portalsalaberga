<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Professor | Gestão de Estágio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/img/Design sem nome.svg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F5F5;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .input-group input:focus {
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
        }

        .input-group label {
            transition: all 0.3s ease;
        }

        .toggle-password {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            color: #4CAF50;
        }

        .error-message {
            display: none;
            color: #ff4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .input-group.error .error-message {
            display: block;
        }

        .input-group.error input {
            border-color: #ff4444;
        }

        .input-group.error label {
            color: #ff4444;
        }

        .btn-submit {
            border-radius: 12px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl flex bg-white rounded-3xl shadow-xl overflow-hidden">
        <!-- Left Side: Image and Welcome Message -->
        <div class="hidden md:block w-1/2 bg-gradient-to-br from-green-600 to-orange-500 p-8 text-white">
            <div class="h-full flex flex-col justify-center">
                <img src="https://i.postimg.cc/ryxHRNkj/lavosier-nas-2.png" alt="Logo EEEP Salaberga" class="w-40 mb-8">
                <h1 class="text-3xl font-bold mb-4">EEEP Salaberga</h1>
                <p class="text-lg opacity-90">Transformando o futuro através da educação e inovação</p>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="w-full md:w-1/2 p-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 mb-4">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Cadastro do Professor</h2>
                <p class="text-gray-600">Crie sua conta para começar</p>
            </div>

            <form action="../controllers/Controller-Cadastros.php" method="POST" class="space-y-6">
                <div class="input-group">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail Institucional</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-green-500"
                               placeholder="seu.email@escola.edu.br" required>
                        <i class="fas fa-envelope absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="input-group">
                    <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                    <div class="relative">
                        <input type="password" name="senha" id="senha" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-green-500"
                               placeholder="Digite sua senha" required>
                        <i class="fas fa-eye toggle-password absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"
                           role="button" tabindex="0" aria-label="Mostrar senha"></i>
                    </div>
                    <span class="error-message" id="senhaError"></span>
                </div>

                <input type="submit" name="btn" value="Cadastrar" 
                       class="w-full bg-gradient-to-r from-green-600 to-green-500 text-white py-3 px-4 rounded-xl font-semibold hover:from-green-700 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-300 btn-submit">
                
                <div class="text-center">
                    <p class="block text-sm font-medium text-gray-700 mb-1">Já tem uma conta? <a href="../views/Login_professor.php" class="text-sm text-green-600 hover:text-green-700">Fazer login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('senha');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
                this.setAttribute('aria-label', type === 'password' ? 'Mostrar senha' : 'Ocultar senha');
            });

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('senha');

                // Email validation
                if (!emailInput.value) {
                    emailInput.parentElement.parentElement.classList.add('error');
                    document.getElementById('emailError').textContent = 'Por favor, insira seu e-mail';
                    isValid = false;
                } else if (!emailInput.value.includes('@')) {
                    emailInput.parentElement.parentElement.classList.add('error');
                    document.getElementById('emailError').textContent = 'Por favor, insira um e-mail válido';
                    isValid = false;
                }

                // Password validation
                if (!passwordInput.value) {
                    passwordInput.parentElement.parentElement.classList.add('error');
                    document.getElementById('senhaError').textContent = 'Por favor, insira sua senha';
                    isValid = false;
                } else if (passwordInput.value.length < 6) {
                    passwordInput.parentElement.parentElement.classList.add('error');
                    document.getElementById('senhaError').textContent = 'A senha deve ter pelo menos 6 caracteres';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Clear errors on input
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    this.parentElement.parentElement.classList.remove('error');
                });
            });
        });
    </script>
</body>
</html>