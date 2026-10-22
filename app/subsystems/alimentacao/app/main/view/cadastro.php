<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEEP Salaberga - Cadastro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/Design sem nome.svg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #2e7d32; /* Verde principal */
            --dark-green: #0f2a1d; /* Verde bem escuro */
            --medium-dark-green: #1a3c2e; /* Verde escuro médio */
            --text-color: #333333;
            --bg-color: #F0F2F5;
            --input-bg: #FFFFFF;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-color);
            margin: 0;
            overflow-x: hidden;
        }

        .main-container {
            display: flex;
            width: 90%;
            max-width: 900px;
            height: 650px;
            background-color: var(--bg-color);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 20px 40px var(--shadow-color);
        }

        .image-container {
            flex: 1;
            background: linear-gradient(40deg, var(--dark-green), var(--medium-dark-green));
            position: relative;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
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
            font-weight: 600;
            font-size: 65px;
            letter-spacing: 1px;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid var(--primary-color);
            border-radius: 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--input-bg);
            color: var(--text-color);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.3);
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

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
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

        .radio-group {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            padding: 0.5rem;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: var(--text-color);
            cursor: pointer;
        }

        .radio-group input[type="radio"] {
            margin-right: 0.5rem;
            accent-color: var(--primary-color);
        }

        .btn-confirmar {
            width: 200px;
            height: 45px;
            margin-top: 15px;
            padding: 10px;
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
            color: #FFFFFF;
            border: none;
            border-radius: 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px var(--shadow-color);
            text-align: center;
        }

        .btn-confirmar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px var(--shadow-color);
            opacity: 0.9;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: #e0e0e0;
            margin-top: 1rem;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress {
            width: 0;
            height: 100%;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .strength-meter {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.7rem;
            color: #999;
        }

        .error-message {
            color: #ef4444;
            text-align: center;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .success-message {
            color: #22c55e;
            text-align: center;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .password-links-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #f97316;
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

            .radio-group {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
        }

        @media (max-width: 640px) {
            .image-container {
                display: none;
            }

            .main-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="image-container">
            <div class="image-overlay">
                <h1>EEEP Salaberga</h1>
                <p>Transformando o futuro através da educação e inovação</p>
            </div>
        </div>
        <div class="form-container">
            <h2 class="text-3xl font-semibold">Cadastro</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message"><?php echo htmlentities($_GET['error'], ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message"><?php echo htmlentities($_GET['success'], ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <form id="cadastroForm" action="../control/controlCadastro.php" method="POST">
                <div class="radio-group">
                    <label><input type="radio" name="userType" value="aluno" required> Aluno</label>
                    <label><input type="radio" name="userType" value="administrador"> Administrador</label>
                </div>
                <div class="input-group">
                    <input type="text" name="nome" id="nome" placeholder=" " required>
                    <label for="nome">Nome</label>
                    <i class="fas fa-user"></i>
                </div>
                <div class="input-group">
                    <input type="email" name="login" id="login" placeholder=" " required>
                    <label for="login">E-mail Institucional</label>
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Senha</label>
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
                <div class="progress-bar">
                    <div class="progress" id="passwordStrength"></div>
                </div>
                <div class="strength-meter">
                    <span>Fraca</span>
                    <span>Média</span>
                    <span>Forte</span>
                </div>
                <div class="input-group">
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder=" " required>
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <i class="fas fa-lock"></i>
                </div>
                <div class="password-links-container">
                    <div class="forgot-password">
                        <a href="../view/login.php">Já tem uma conta? Faça login</a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="submit" class="btn-confirmar">CADASTRAR</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('cadastroForm');
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');
            const passwordStrength = document.getElementById('passwordStrength');
            const confirmarSenhaInput = document.getElementById('confirmar_senha');

            // Definir como senha oculta por padrão (olhinho fechado)
            passwordInput.setAttribute('type', 'password');
            confirmarSenhaInput.setAttribute('type', 'password');
            togglePassword.classList.add('fa-eye-slash');
            togglePassword.classList.remove('fa-eye');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                confirmarSenhaInput.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });

            passwordInput.addEventListener('input', function() {
                const strength = calculatePasswordStrength(this.value);
                passwordStrength.style.width = `${strength}%`;
            });

            form.addEventListener('submit', function(event) {
                if (passwordInput.value !== confirmarSenhaInput.value) {
                    event.preventDefault();
                    alert('As senhas não coincidem!');
                }
            });

            function calculatePasswordStrength(password) {
                const length = password.length;
                if (length === 0) return 0;
                if (length < 4) return 25;
                if (length < 8) return 50;
                if (length < 12) return 75;
                return 100;
            }
        });
    </script>
</body>
</html>