<?php
session_start();

// Verifica se o usuário foi autenticado para redefinir a senha
if (!isset($_SESSION['reset_user_id'])) {
    $_SESSION['erro'] = "Acesso não autorizado. Por favor, verifique seu email e nome primeiro.";
    header("Location: forgot-password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Redefinir Senha - EEEP Salaberga</title>
    <style>
        .links {
            margin-top: 20px;
            text-align: center;
        }
        .links a {
            display: block;
            color: #005A24;
            text-decoration: none;
            margin: 5px 0;
            font-family: 'Poppins', sans-serif;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .form-control {
            margin-top: 15px;
            background-color: rgb(245, 245, 245);
            padding-right: 40px;
        }
        .senhaForm {
            position: relative;
            margin-bottom: 20px;
        }
        .icone {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #4CAF50;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .form-container {
            position: relative;
            left: 200px;
            z-index: 2;
            width: 45%;
            margin-top: 120px;
            font-family: 'Poppins', sans-serif;
        }
        .botao {
            position: relative;
            z-index: 2;
            border-radius: 3px;
            top: 25px;
        }
        .corpo {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #FFFFFF;
        }
        .retangulo {
            position: relative;
            width: 900px;
            height: 550px;
            background-color: rgb(245, 245, 245);
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .img-gradient {
            content: "";
            position: absolute;
            width: 45%;
            height: 100%;
            top: 0;
            left: 0;
            background: linear-gradient(40deg, #005A24, #FFA500);
            z-index: 0;
        }
        h2 {
            order: 2;
            margin-bottom: 160px;
            position: absolute;
            margin-left: 400px;
            font-family: 'Poppins';
            font-weight: 900;
            font-size: 40px;
            color: #4CAF50;
        }
        .btn-primary {
            color: white;
            background: linear-gradient(135deg, #007A33, #FF8C00);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 18px;
            border: none;
        }
        .btn-primary:hover {
            color: white;
            background: linear-gradient(135deg, #006400, #FFA500);
            border: none;
        }
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary:focus-visible {
            color: white;
            background: linear-gradient(135deg, #007A33, #FF8C00) !important;
            box-shadow: none !important;
            outline: none !important;
            border: none;
        }
        .img {
            order: 1;
            margin-bottom: 300px;
            margin-left: 400px;
            position: absolute;
        }
        h1 {
            order: 1;
            margin-right: 500px;
            position: absolute;
            margin-bottom: 100px;
            color: #F0F2F5;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
        }
        p {
            position: absolute;
            top: 260px;
            width: 400px;
            text-align: center;
            left: 200px;
            transform: translateX(-50%);
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            color: #F0F2F5;
        }
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #FFB74D;
            --text-color: #333333;
            --bg-color: #F0F2F5;
            --input-bg: #FFFFFF;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        .img-hover {
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
            width: 45%;
        }
        .input-field{
            bottom: 20px;; 
        }
    </style>
</head>
<body class="corpo">
    <div class="retangulo">
        <div class="img-gradient"></div>
        <div class="img-hover"></div>
        <h1>EEEP Salaberga</h1>
        <p>Transformando o futuro através <br> da educação e inovação</p>
        <h2>Redefinir Senha</h2>
        <div class="img">
            <img src="../img/salaberga_logo.png" alt="Logo Salaberga">
        </div>
        <div class="form-container">
            <?php
            if (isset($_SESSION['erro'])) {
                echo '<div style="color: red; text-align: center; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['erro']) . '</div>';
                unset($_SESSION['erro']);
            }
            if (isset($_SESSION['sucesso'])) {
                echo '<div style="color: green; text-align: center; margin-bottom: 10px;">' . htmlspecialchars($_SESSION['sucesso']) . '</div>';
                unset($_SESSION['sucesso']);
            }
            ?>
            <form id="reset-password-form" action="../controllers/passwordcontroller.php" method="POST">
                <input type="hidden" name="acao" value="redefinir">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="senhaForm">
                    <label for="inputPassword" class="col-form-label"></label>
                    <div class="icone"><i class="fas fa-eye toggle-password"></i></div>
                    <input type="password" class="form-control" id="inputPassword" name="senha" placeholder="Nova senha" required>
                </div>
                <div class="senha">
                    <label for="inputConfirmPassword" class="col-form-label"></label>
                    <div class="icone"><i class="fas fa-eye toggle-password-confirm"></i></div>
                    <input type="password" class="form-control" id="inputConfirmPassword" name="confirmar_senha" placeholder="Confirmar senha" required>
                </div>
                <div class="botao">
                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">ALTERAR SENHA</button>
                    </div>
                </div>
                <div class="links">
                    <a href="login.php">Voltar ao login</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('reset-password-form').addEventListener('submit', function(event) {
            const senha = document.getElementById('inputPassword').value;
            const confirmarSenha = document.getElementById('inputConfirmPassword').value;

            if (!senha || !confirmarSenha) {
                event.preventDefault();
                alert('Por favor, preencha todos os campos.');
                return;
            }

            if (senha.length < 6) {
                event.preventDefault();
                alert('A senha deve ter pelo menos 6 caracteres.');
                return;
            }

            if (senha !== confirmarSenha) {
                event.preventDefault();
                alert('As senhas não coincidem.');
                return;
            }

            console.log('Enviando: Senha=' + senha);
        });

        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('inputPassword');
            const icon = this;
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

        document.querySelector('.toggle-password-confirm').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('inputConfirmPassword');
            const icon = this;
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>