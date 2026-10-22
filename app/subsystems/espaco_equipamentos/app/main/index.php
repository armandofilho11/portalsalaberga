<?php
require_once 'config/session.php';
// Se o usuário já estiver logado, redireciona para a página apropriada
if (isLoggedIn()) {
    $userType = getUserType();
    switch ($userType) {
        case 'aluno':
            header("Location: view/painel_aluno.php");
            break;
        case 'responsavel':
            header("Location: view/painel_responsavel.php");
            break;
        default:
            redirectToLogin();
    }
    exit();
}

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Debug - Remover em produção
    error_log("Tentativa de login - Email: " . $email . ", Tipo: " . $tipo_usuario);

    // Tenta fazer login
    if (login($email, $senha, $tipo_usuario)) {
        // Login bem-sucedido, redireciona para a página apropriada
        error_log("Redirecionando usuário do tipo: " . $tipo_usuario);
        
        if ($tipo_usuario === 'aluno') {
            header("Location: view/painel_aluno.php");
        } else if ($tipo_usuario === 'responsavel') {
            header("Location: view/painel_responsavel.php");
        } else {
            redirectToLogin();
        }
        exit();
    } else {
        $error = "Email ou senha incorretos.";
        error_log("Falha no login - Email: " . $email . ", Tipo: " . $tipo_usuario);
    }
}

// Se não estiver logado, continua para a página de login
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistema AEE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/img/logo.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #007A33;
            --secondary-color: #FFA500;
            --text-color: #333333;
            --bg-color: #F0F2F5;
            --input-bg: #FFFFFF;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --error-color: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-color);
        }

        .main-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            height: 550px;
            background-color: var(--bg-color);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px var(--shadow-color);
        }

        .image-container {
            flex: 1;
            background: #007A33;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.366), rgba(0, 0, 0, 0.355));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
            color: #FFFFFF;
        }

        .image-overlay h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .image-overlay p {
            font-size: 1rem;
            max-width: 80%;
        }

        .form-container {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #FFFFFF;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .user-type-selector {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .user-type-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 8px 16px;
            background-color: var(--input-bg);
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-color);
            box-shadow: 0 2px 4px var(--shadow-color);
        }

        .user-type-btn:hover {
            background-color: rgba(0, 122, 51, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        .user-type-btn.active {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border: none;
            box-shadow: 0 4px 8px var(--shadow-color);
        }

        .user-type-btn svg {
            width: 20px;
            height: 20px;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--input-bg);
            color: var(--text-color);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(0, 122, 51, 0.1);
        }

        .input-group label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            padding: 0 5px;
            background-color: var(--input-bg);
        }

        .input-group input:focus+label,
        .input-group input:not(:placeholder-shown)+label {
            top: 0;
            font-size: 0.8rem;
            color: var(--primary-color);
        }

        .input-group i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
            cursor: pointer;
        }

        .btn-confirmar {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirmar:hover {
            background-color: #006428;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px var(--shadow-color);
        }

        .forgot-password {
            text-align: center;
            margin-top: 1rem;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--secondary-color);
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                height: auto;
                max-width: 100%;
            }

            .image-container {
                height: 200px;
            }

            .form-container {
                padding: 2rem;
            }

            .image-container {
                display: none;
            }

            .user-type-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .user-type-btn svg {
                width: 18px;
                height: 18px;
            }

            .error-message {
                font-size: 0.8rem;
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="image-container">
            <div class="image-overlay">
                <h1>Bem Vindos!!</h1>
                <p>Sistema Gerenciador de Espaços e Equipamentos</p>
            </div>
        </div>
        <div class="form-container">
            <h2>Login</h2>
            <?php
            if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
                echo '<div class="success-message">Você foi desconectado com sucesso!</div>';
            }
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div class="message success-message">Cadastro realizado com sucesso! Faça login para continuar.</div>';
            }
            if (isset($error)) {
                echo '<div class="message error-message">' . htmlspecialchars($error) . '</div>';
            }
            ?>
            <div class="user-type-selector">
                <div class="user-type-btn" data-type="aluno">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span>Aluno</span>
                </div>
                <div class="user-type-btn active" data-type="responsavel">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span>Responsável</span>
                </div>
            </div>
            <form id="loginForm" method="POST" action="">
                <input type="hidden" name="tipo" id="tipo_usuario" value="responsavel">
                <div class="input-group">
                    <input type="text" name="email" id="username" placeholder=" " required>
                    <label for="username">E-mail Institucional</label>
                    <i class="fas fa-user"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="senha" id="password" placeholder=" " required>
                    <label for="password">Senha</label>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                <button type="submit" class="btn-confirmar">Entrar</button>
            </form>
            <div class="register-link">
                <a href="/sistema_aee_completo/app/main/view/register.php">Criar conta de aluno</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevenção de erro do cursor
            window.addEventListener('error', function(e) {
                if (e.message.includes("Cannot read properties of null (reading 'offsetX')")) {
                    console.warn('Cursor effect disabled due to missing element');
                    return;
                }
            });

            const userTypeBtns = document.querySelectorAll('.user-type-btn');
            const tipoUsuarioInput = document.getElementById('tipo_usuario');
            const emailInput = document.getElementById('username');
            const emailLabel = document.querySelector('label[for="username"]');

            // Inicialização - garante que o tipo está correto ao carregar
            const activeBtn = document.querySelector('.user-type-btn.active');
            if (activeBtn) {
                const initialType = activeBtn.dataset.type;
                tipoUsuarioInput.value = initialType;
                console.log('Tipo inicial:', initialType);
                
                if (initialType === 'responsavel') {
                    emailLabel.textContent = 'E-mail';
                    emailInput.placeholder = ' ';
                } else {
                    emailLabel.textContent = 'E-mail Institucional';
                    emailInput.placeholder = ' ';
                }
            }

            userTypeBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // Previne qualquer comportamento padrão
                    
                    // Remove active class from all buttons
                    userTypeBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const newType = this.dataset.type;
                    console.log('Mudando tipo para:', newType);
                    
                    // Update hidden input value
                    tipoUsuarioInput.value = newType;
                    console.log('Valor do input após mudança:', tipoUsuarioInput.value);
                    
                    // Update email label based on user type
                    if (newType === 'responsavel') {
                        emailLabel.textContent = 'E-mail';
                        emailInput.placeholder = ' ';
                    } else {
                        emailLabel.textContent = 'E-mail Institucional';
                        emailInput.placeholder = ' ';
                    }
                });
            });

            // Handle form submission
            const form = document.getElementById('loginForm');
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const tipoUsuario = document.querySelector('.user-type-btn.active').dataset.type;
                console.log('Tipo de usuário antes de enviar:', tipoUsuario);
                console.log('Valor atual do input hidden:', document.getElementById('tipo_usuario').value);
                
                formData.delete('tipo'); // Remove o tipo existente
                formData.append('tipo', tipoUsuario); // Adiciona o novo tipo
                
                try {
                    console.log('Enviando login como:', tipoUsuario); // Debug
                    console.log('Dados do FormData:');
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    const response = await fetch('/sistema_aee_completo/app/main/controllers/LoginController.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    console.log('Resposta do servidor:', data); // Debug
                    
                    if (data.success) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            const defaultRedirect = tipoUsuario === 'responsavel' ? 
                                '/sistema_aee_completo/app/main/view/painel_responsavel.php' : 
                                '/sistema_aee_completo/app/main/view/painel_aluno.php';
                            window.location.href = defaultRedirect;
                        }
                    } else {
                        const errorDiv = document.querySelector('.error-message') || document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.textContent = data.message || 'Erro ao fazer login. Tente novamente.';
                        
                        if (!document.querySelector('.error-message')) {
                            form.insertBefore(errorDiv, form.firstChild);
                        }
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    const errorDiv = document.querySelector('.error-message') || document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.textContent = 'Erro ao processar o login. Tente novamente.';
                    
                    if (!document.querySelector('.error-message')) {
                        form.insertBefore(errorDiv, form.firstChild);
                    }
                }
            });
        });
    </script>
</body>
</html> 