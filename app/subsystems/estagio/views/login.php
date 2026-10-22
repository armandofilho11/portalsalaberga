<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#007A33">
    <meta name="description" content="Sistema de Gerenciamento de Estágio - STGM">

    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Login - Sistema de Estágio</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #007A33;
            --primary-light: #00b348;
            --primary-dark: #005a25;
            --secondary: #FFA500;
            --secondary-light: #ffb733;
            --background: #121212;
            --surface: #1e1e1e;
            --surface-light: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --error: #ff4d4d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--background);
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(0, 122, 51, 0.08) 0%, transparent 25%),
                radial-gradient(circle at 85% 85%, rgba(255, 165, 0, 0.08) 0%, transparent 25%);
            color: var(--text-primary);
            padding: 1.5rem;
            position: relative;
        }

        .back-link {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            display: inline-flex;
            align-items: center;
            color: var(--primary-light);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.3s;
            z-index: 10;
        }

        .back-link i {
            margin-right: 0.5rem;
        }

        .back-link:hover {
            color: var(--secondary-light);
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            perspective: 1000px;
        }

        .login-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.3),
                0 1px 2px rgba(0, 122, 51, 0.1),
                0 -1px 2px rgba(255, 165, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transform-style: preserve-3d;
            animation: cardEntrance 0.8s ease-out;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(20px) rotateX(5deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .logo {
            height: 90px;
            margin-bottom: 0.5rem;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .system-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-light), var(--secondary-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 0.5px;
        }

        .divider {
            height: 3px;
            width: 60%;
            margin: 0.5rem auto 1.5rem;
            background: linear-gradient(90deg, 
                rgba(0, 122, 51, 0.1), 
                rgba(0, 179, 72, 0.3), 
                rgba(255, 165, 0, 0.3), 
                rgba(0, 122, 51, 0.1));
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            padding: 0.7rem 2.5rem;
            border-radius: 12px;
            border: 2px solid var(--surface-light);
            background: rgba(30, 30, 30, 0.7);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            line-height: 1.5;
            height: 48px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(30, 30, 30, 0.9);
            box-shadow: 
                0 0 0 3px rgba(0, 122, 51, 0.15),
                inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-group:focus-within .form-label {
            color: var(--primary-light);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.7rem;
            color: var(--text-secondary);
            transition: color 0.3s;
            pointer-events: none;
            font-size: 1rem;
            line-height: 1;
        }

        .form-group:focus-within .input-icon {
            color: var(--primary-light);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 2.7rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.1rem;
            cursor: pointer;
            transition: color 0.3s;
            z-index: 2;
            line-height: 1;
        }

        .toggle-password:hover {
            color: var(--primary-light);
        }

        .login-error {
            background: rgba(255, 77, 77, 0.15);
            border-left: 4px solid var(--error);
            color: #fff;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 400;
            animation: shakeError 0.6s;
        }

        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.5rem 0;
        }

        .forgot-password {
            color: var(--primary-light);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }

        .forgot-password:after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary-light);
            transition: width 0.3s;
        }

        .forgot-password:hover {
            color: var(--secondary-light);
        }

        .forgot-password:hover:after {
            width: 100%;
            background-color: var(--secondary-light);
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 122, 51, 0.3);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .login-button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 122, 51, 0.4);
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
        }

        .login-button:hover:before {
            left: 100%;
        }

        .login-button:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(0, 122, 51, 0.3);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }

            .system-title {
                font-size: 1.5rem;
            }

            .logo {
                height: 70px;
            }

            .back-link {
                top: 1rem;
                left: 1rem;
                font-size: 0.9rem;
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        .login-button.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-button.loading:after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: calc(50% - 10px);
            left: calc(50% - 10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <a href="../index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo do Sistema de Estágio" class="logo">
                <h1 class="system-title">Sistema de Estágio</h1>
                <div class="divider"></div>
            </div>

            <form action="../controllers/controller.php" method="POST" id="loginForm">
                <?php if (isset($_GET['erro'])): ?>
                <div class="login-error">
                    <i class="fas fa-exclamation-circle"></i> Usuário ou senha incorretos. Tente novamente.
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username" class="form-label">Usuário</label>
                    <i class="fas fa-user input-icon"></i>
                    <input 
                        name="email" 
                        type="email" 
                        id="username" 
                        class="form-input" 
                        placeholder="Digite seu e-mail" 
                        required 
                        autocomplete="username"
                        aria-required="true"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Senha</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input 
                        name="senha" 
                        type="password" 
                        id="password" 
                        class="form-input" 
                        placeholder="Digite sua senha" 
                        required 
                        autocomplete="current-password"
                        aria-required="true"
                    >
                    <button 
                        type="button" 
                        class="toggle-password" 
                        tabindex="-1" 
                        aria-label="Mostrar ou ocultar senha" 
                        onclick="togglePassword()"
                    >
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>

                <div class="options-row">
                    <a href="#" class="forgot-password">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="login-button" id="loginBtn">
                    Entrar
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.getElementById('loginBtn');
            button.classList.add('loading');
            button.innerHTML = '';
            
            setTimeout(() => {
                return true;
            }, 300);
        });
    </script>
</body>

</html>