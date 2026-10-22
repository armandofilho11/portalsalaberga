<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário - SESMATED</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="https://salaberga.com/salaberga/portalsalaberga/app/main/assets/img/S.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --background-color: #181818;
            --text-color: #ffffff;
            --header-color: #00b348;
            --icon-bg: #232323;
            --icon-shadow: rgba(0, 0, 0, 0.3);
            --accent-color: #ffb733;
            --grid-color: #333333;
            --card-bg: rgba(34, 34, 34, 0.97);
            --header-bg: rgba(28, 28, 28, 0.95);
            --mobile-nav-bg: rgba(28, 28, 28, 0.95);
            --search-bar-bg: #232323;
            --card-border-hover: var(--accent-color);
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        html, body {
            height: 100%;
        }
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            color: var(--text-color);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2rem 1rem;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 28rem;
        }
        .main-container {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            border-radius: 2rem;
            border: 1.5px solid rgba(255,255,255,0.08);
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.25);
            width: 100%;
        }
        .header-section {
            background: linear-gradient(135deg, var(--header-color) 0%, #009639 100%);
            position: relative;
            overflow: hidden;
            padding: 2.5rem 2rem 1.5rem 2rem;
        }
        .header-section .icon-container {
            width: 64px;
            height: 64px;
            margin-bottom: 1.2rem;
            background: rgba(255,255,255,0.12);
            border: 2.5px solid rgba(255,255,255,0.18);
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
        }
        .header-section h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .header-section p {
            font-size: 1.1rem;
        }
        .form-section {
            background: rgba(28, 28, 28, 0.92);
            backdrop-filter: blur(10px);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-field {
            background: var(--search-bar-bg);
            border: 2px solid #232323;
            color: var(--text-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px -2px rgba(0,0,0,0.10);
            padding-left: 2.7rem;
            font-size: 1rem;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--header-color);
            background: #232323;
            box-shadow: 0 0 0 3px rgba(0,179,72,0.10);
        }
        .input-icon {
            color: #bdbdbd;
            left: 0.8rem;
            font-size: 1.1rem;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            transition: color 0.3s;
        }
        .password-toggle {
            color: #bdbdbd;
            right: 0.8rem;
            font-size: 1.1rem;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: var(--header-color);
        }
        .input-field:focus + .input-icon,
        .input-group:hover .input-icon {
            color: var(--header-color);
        }
        label {
            color: #e6e6e6;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--header-color) 0%, #009639 100%);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 1.1rem 0;
            border-radius: 1rem;
            margin-top: 0.5rem;
            box-shadow: 0 4px 16px 0 rgba(0,179,72,0.18);
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #00b348 0%, #ffb733 100%);
            color: #232323;
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 32px 0 rgba(0,179,72,0.25);
        }
        .alert-error, .alert-success {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        .divider {
            background: linear-gradient(90deg, transparent, var(--grid-color), transparent);
            height: 2px;
            margin: 2rem 0 1.5rem 0;
        }
        .logo-text {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e6a429 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .footer {
            margin-top: 2rem;
            text-align: center;
            padding: 1rem;
        }
        @media (max-width: 600px) {
            .page-container {
                padding: 1rem 0.5rem;
            }
            .main-container {
                border-radius: 1rem;
            }
            .form-section, .header-section {
                padding: 1.5rem 1rem;
            }
            .header-section h1 {
                font-size: 1.8rem;
            }
            body {
                padding: 1rem 0;
            }
        }
        @media (max-height: 700px) {
            .content-wrapper {
                align-items: flex-start;
            }
            .page-container {
                justify-content: flex-start;
                padding-top: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#1a1a1a] to-[#0f0f0f]">
    <div class="page-container">
        <div class="content-wrapper">
            <div class="main-container rounded-2xl overflow-hidden shadow-xl">
                <div class="header-section px-6 py-8 text-center relative rounded-b-3xl shadow-lg">
                    <div class="icon-container rounded-2xl flex items-center justify-center mx-auto mb-4 relative z-10">
                        <i class="fas fa-user-plus text-3xl" style="color:rgba(255,255,255,0.92);"></i>
                    </div>
                    <h1 class="font-extrabold mb-1 relative z-10 text-white drop-shadow-lg tracking-wide">
                        Fazer Registro
                    </h1>
                    <p class="relative z-10 font-medium tracking-wide drop-shadow" style="color: #fff; text-shadow: 0 2px 8px #00000022;">
                        Junte-se ao <span style="color: #ffb733; font-weight: bold;">Sistema SESMATED</span> 
                    </p>
                </div>
                <div class="form-section form-container px-6 py-8">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert-error px-5 py-4 rounded-xl mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium mb-1">Erro no cadastro</h3>
                                    <div class="text-sm opacity-90">
                                        <p class="mb-1"><?php echo htmlspecialchars($_GET['error']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert-success px-5 py-4 rounded-xl mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium mb-1">Sucesso!</h3>
                                    <p class="text-sm opacity-90"><?php echo htmlspecialchars($_GET['success']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <form action="../controllers/controller_main.php" method="POST" id="registerForm" class="space-y-6">
                        <div class="input-group">
                            <label for="nome" class="block text-sm font-semibold mb-2 text-gray-200">
                                Nome Completo
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="nome" 
                                    name="nome" 
                                    value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                                    class="input-field w-full pr-4 py-4 rounded-xl text-sm font-medium"
                                    placeholder="Digite seu nome completo"
                                    required
                                >
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="email" class="block text-sm font-semibold mb-2 text-gray-200">
                                Endereço de Email
                            </label>
                            <div class="relative">
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                    class="input-field w-full pr-4 py-4 rounded-xl text-sm font-medium"
                                    placeholder="Digite seu email"
                                    required
                                >
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="data" class="block text-sm font-semibold mb-2 text-gray-200">Data de avaliação</label>
                            <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($_POST['data'] ?? ''); ?>" class="input-field w-full pr-4 py-4 rounded-xl text-sm font-medium" required>
                        </div>
                        <div class="input-group">
                            <label for="turno" class="block text-sm font-semibold mb-2 text-gray-200">Turno</label>
                            <select id="turno" name="turno" class="input-field w-full pr-4 py-4 rounded-xl text-sm font-medium" required>
                                <option value="">Selecione o turno</option>
                                <option value="Manhã" <?php if ((isset($_POST['turno']) && $_POST['turno'] == 'Manhã')) echo 'selected'; ?>>Manhã</option>
                                <option value="Tarde" <?php if ((isset($_POST['turno']) && $_POST['turno'] == 'Tarde')) echo 'selected'; ?>>Tarde</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="senha" class="block text-sm font-semibold mb-2 text-gray-200">Senha</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="senha" 
                                    name="senha" 
                                    value="<?php echo htmlspecialchars($_POST['senha'] ?? ''); ?>"
                                    class="input-field w-full pr-12 py-4 rounded-xl text-sm font-medium"
                                    placeholder="Digite sua senha"
                                    required
                                >
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <span class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                        </div>
                        <button 
                            type="submit" 
                            class="btn-primary w-full focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 relative overflow-hidden"
                        >
                            <span class="relative z-10">
                                <i class="fas fa-user-plus mr-2"></i>
                                Criar Minha Conta
                            </span>
                        </button>
                    </form>
                    <div class="divider"></div>
                    <a href="./relatorios/avaliadores/avaliadores.php"
                       class="block w-full text-center py-3 px-6 rounded-xl font-semibold text-sm transition-all duration-200
                              bg-gradient-to-r from-gray-700 via-gray-800 to-gray-900 text-white shadow-md
                              hover:from-green-500 hover:to-green-700 hover:text-white hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400">
                       <i class="fas fa-sign-in-alt mr-2"></i>
                        Gerar relatório
                    </a>
                </div>
            </div>
        </div>
        <div class="footer">
            <p class="text-gray-500 text-xs">
                © 2025 <span class="logo-text font-semibold">SESMATED</span> - Salaberga
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('senha');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!nome || !email) {
                e.preventDefault();
                showNotification('Por favor, preencha todos os campos obrigatórios', 'error');
                return;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                showNotification('Por favor, insira um endereço de email válido', 'error');
                return;
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showNotification(message, type) {
            const existing = document.querySelector('.notification');
            if (existing) existing.remove();
            
            const notification = document.createElement('div');
            notification.className = `notification fixed top-6 right-6 p-4 rounded-xl text-white text-sm z-50 max-w-sm shadow-2xl ${
                type === 'error' 
                    ? 'bg-gradient-to-r from-red-600 to-red-700 border border-red-500' 
                    : 'bg-gradient-to-r from-green-600 to-green-700 border border-green-500'
            }`;
            notification.style.backdropFilter = 'blur(10px)';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} mr-3 text-lg"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            notification.style.transform = 'translateX(100%)';
            notification.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });
    </script>
</body>
</html>