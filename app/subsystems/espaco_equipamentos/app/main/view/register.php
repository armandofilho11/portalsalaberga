<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | EEEP Salaberga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="/sistema_aee_completo/assets/img/logo.png" type="image/x-icon">
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
            --success-color: #28a745;
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
            padding: 2rem;
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

        .image-section {
            flex: 0.8;
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
            font-size: 1.1rem;
            max-width: 80%;
            line-height: 1.6;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .form-section {
            flex: 1.2;
            padding: 3rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #FFFFFF;
        }

        .form-row {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 15px 48px 15px 25px;
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

        .input-group i.toggle-password {
            right: 16px;
            color: var(--primary-color);
            font-size: 1.05rem;
            cursor: pointer;
            pointer-events: auto;
        }

        .input-group i.fa-envelope,
        .input-group i.fa-user {
            font-size: 1.05rem;
        }

        .btn-register {
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

        .btn-register:hover {
            background-color: #006428;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px var(--shadow-color);
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: #f8d7da;
            color: var(--error-color);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: none;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background-color: #d4edda;
            color: var(--success-color);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: none;
            border: 1px solid #c3e6cb;
        }

        .styled-select {
            width: 100%;
            padding: 15px 25px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            background-color: var(--input-bg);
            color: var(--text-color);
            appearance: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .styled-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(0, 122, 51, 0.1);
            outline: none;
        }

        .styled-select:invalid {
            color: #999;
        }

        .select-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .select-wrapper::after {
            content: '\f078';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            pointer-events: none;
        }

        .form-row-full {
            width: 100%;
        }

        .input-group input[type="email"] {
            width: 100%;
            padding: 15px 48px 15px 25px;
            box-sizing: border-box;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                height: auto;
                max-width: 100%;
            }

            .image-section {
                height: 200px;
            }

            .form-section {
                padding: 2rem;
            }

            .image-section {
                display: none;
            }

            .form-row {
                flex-direction: column;
                gap: 1rem;
            }

            .input-group {
                margin-bottom: 1rem;
            }

            .input-group input,
            .input-group select,
            .input-group textarea {
                font-size: 16px;
                padding: 12px 20px;
            }

            .select-wrapper {
                margin-bottom: 1rem;
            }

            .btn-register {
                margin-top: 1rem;
            }

            .login-link {
                margin-top: 1rem;
            }

            h2 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .form-section {
                padding: 1.5rem;
            }

            .input-group input,
            .input-group select,
            .input-group textarea {
                padding: 10px 15px;
            }

            .btn-register {
                padding: 10px;
            }
        }

        input#email::placeholder,
        input#confirmar_senha::placeholder {
            font-size: 0.85rem !important;
        }

        #email,
        #senha,
        #confirmar_senha {
            padding-right: 60px !important;
        }

        .input-group i {
            font-size: 1rem !important;
        }
        .input-group i.toggle-password {
            font-size: 1.05rem !important;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="image-section">
            <div class="image-overlay">
                <h1>Bem-vindo!</h1>
                <p>Junte-se à nossa comunidade educacional e comece sua jornada de aprendizado.</p>
            </div>
        </div>
        <div class="form-section">
            <h2>Criar Conta</h2>
            <?php
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            $database_path = __DIR__ . '/../config/database.php';
            if (!file_exists($database_path)) {
                die('Arquivo de configuração do banco não encontrado: ' . $database_path);
            }
            require_once $database_path;
            if (isset($_GET['error'])) {
                echo '<div class="error-message" style="display: block;">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            <form id="registerForm" action="/sistema_aee_completo/app/main/view/process_register.php" method="POST">
                <div class="form-row">
                    <div class="input-group">
                        <input type="text" id="nome" name="nome" placeholder=" " required>
                        <label for="nome">Nome completo</label>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder=" " required pattern="[a-z0-9._%+-]+@aluno\.ce\.gov\.br$" style="padding-right:60px;font-size:0.95rem;">
                        <label for="email">E-mail institucional</label>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <input type="password" id="senha" name="senha" placeholder=" " required minlength="6" style="padding-right:60px;font-size:0.95rem;">
                        <label for="senha">Senha</label>
                        <i class="fas fa-eye toggle-password" style="cursor:pointer;"></i>
                    </div>
                    <div class="input-group">
                        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder=" " required style="padding-right:60px;font-size:0.95rem;">
                        <label for="confirmar_senha">Confirmar senha</label>
                        <i class="fas fa-eye toggle-password" style="cursor:pointer;"></i>
                    </div>
                </div>
                <div class="form-row-full">
                    <div class="select-wrapper">
                        <select id="turma_id" name="turma_id" required class="styled-select">
                            <option value="" disabled selected hidden>Selecione a turma</option>
                            <?php
                            $conn = getDatabaseConnection();
                            $stmt = $conn->query("SELECT id, nome FROM turmas ORDER BY nome");
                            $turmas_exibidas = [];
                            while ($row = $stmt->fetch()) {
                                // Normaliza: tira espaços extras, troca variações de 'º', etc.
                                $nome_corrigido = str_replace(['??', '°', 'º', 'ª'], ['º', 'º', 'º', 'ª'], trim(preg_replace('/\s+/', ' ', $row['nome'])));
                                if (!isset($turmas_exibidas[$nome_corrigido])) {
                                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($nome_corrigido) . '</option>';
                                    $turmas_exibidas[$nome_corrigido] = true;
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="error-message" id="errorMessage"></div>
                <div class="success-message" id="successMessage"></div>
                <button type="submit" class="btn-register">Criar Conta</button>
            </form>
            <div class="login-link">
                <a href="/sistema_aee_completo/app/main/index.php">Já tem uma conta? Faça login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Garante que todos os campos de senha iniciem ocultos e o olho aberto
            document.querySelectorAll('.toggle-password').forEach(function(btn) {
                const input = btn.parentElement.querySelector('input');
                input.type = 'password';
                btn.classList.remove('fa-eye-slash');
                btn.classList.add('fa-eye');
            });

            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const senha = document.getElementById('senha').value;
                const confirmarSenha = document.getElementById('confirmar_senha').value;
                const email = document.getElementById('email').value;
                const errorMessage = document.getElementById('errorMessage');
                
                // Validar email institucional
                if (!email.endsWith('@aluno.ce.gov.br')) {
                    errorMessage.textContent = 'Por favor, use um e-mail institucional válido (@aluno.ce.gov.br)';
                    errorMessage.style.display = 'block';
                    return;
                }
                
                // Validar senhas
                if (senha !== confirmarSenha) {
                    errorMessage.textContent = 'As senhas não coincidem';
                    errorMessage.style.display = 'block';
                    return;
                }
                
                if (senha.length < 6) {
                    errorMessage.textContent = 'A senha deve ter pelo menos 6 caracteres';
                    errorMessage.style.display = 'block';
                    return;
                }
                
                // Se todas as validações passarem, envia o formulário
                this.submit();
            });

            // Mostrar/ocultar senha ao clicar no olho (alternância imediata)
            document.querySelectorAll('.toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
</body>
</html> 