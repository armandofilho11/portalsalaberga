<?php
session_start();

// Verificar se já está logado
if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
    header("Location: resultados.php");
    exit();
}

$mensagem = '';
$tipoMensagem = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Credenciais fixas para simplificar
    if ($usuario === 'admin' && $senha === 'paocomovo') {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_usuario'] = $usuario;
        
        // Garantir que o redirecionamento seja absoluto
        $redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $redirect_url .= dirname($_SERVER['PHP_SELF']) . "/resultados.php";
        
        header("Location: " . $redirect_url);
        exit();
    } else {
        $mensagem = 'Usuário ou senha incorretos';
        $tipoMensagem = 'error';
    }
}

// Verificar se há mensagem de erro na sessão
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipoMensagem = $_SESSION['tipo_mensagem'];
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Votação Grêmio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e6f2ec',
                            100: '#cce5d9',
                            200: '#99cbb3',
                            300: '#66b18d',
                            400: '#339766',
                            500: '#007d40',
                            600: '#006a36',
                            700: '#005A24',
                            800: '#004d1f',
                            900: '#00401a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 90, 36, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(0, 90, 36, 0.2);
            transition: all 0.2s ease;
        }
        
        .input-container:focus-within .input-icon {
            color: #007d40;
            transition: color 0.2s ease;
        }
        
        .btn-login {
            transition: all 0.3s ease;
            background-size: 200% auto;
            background-image: linear-gradient(to right, #007d40 0%, #339766 50%, #007d40 100%);
        }
        
        .btn-login:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 90, 36, 0.15);
        }
        
        .form-container {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-3px, 0, 0); }
            40%, 60% { transform: translate3d(3px, 0, 0); }
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 640px) {
            .form-container {
                width: 90%;
                margin: 0 auto;
            }
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md form-container">
        <!-- Card Principal -->
        <div class="bg-white rounded-2xl overflow-hidden card-shadow">
            <!-- Cabeçalho -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-8 px-8 text-center relative">
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');"></div>
                </div>
                <h1 class="text-3xl font-bold mb-1 relative">Área Administrativa</h1>
                <p class="text-primary-100 text-lg relative">Votação do Grêmio</p>
            </div>
            
            <!-- Formulário de Login -->
            <div class="px-8 py-10">
                <div class="mb-8 text-center">
                    <div class="inline-block p-3 bg-primary-50 rounded-full mb-4">
                        <i class="fas fa-lock text-4xl text-primary-600"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-1">Login Administrativo</h2>
                    <p class="text-gray-500">Acesse para ver os resultados da votação</p>
                </div>
                
                <?php if (isset($mensagem)): ?>
                <div class="mb-4 p-4 rounded-lg <?php echo $tipoMensagem === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
                <?php endif; ?>
                
                <form method="post" class="space-y-6">
                    <div class="space-y-1">
                        <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 input-icon"></i>
                            </div>
                            <input type="text" id="usuario" name="usuario" required 
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 input-focus input-field"
                                placeholder="Nome de usuário">
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 input-icon"></i>
                            </div>
                            <input type="password" id="senha" name="senha" required 
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 input-focus input-field"
                                placeholder="Sua senha">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="toggle-password">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full btn-login text-white py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors flex items-center justify-center font-medium text-base">
                            <span>Entrar</span>
                            <i class="fas fa-sign-in-alt ml-2"></i>
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="../index.php" class="text-sm text-primary-600 hover:text-primary-800 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar para a página inicial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('senha');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html> 