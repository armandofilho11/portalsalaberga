<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
    <title>Cadastro - EEEP Salaberga</title>
</head>
<body class="corpo">
    <div class="retangulo">
        <div class="img-gradient"></div>
        <div class="img-hover"></div>
        <h1>EEEP Salaberga</h1>
        <p>Transformando o futuro através <br> da educação e inovação</p>
        <h2>Cadastro</h2>
        <div class="img">
            <img src="../img/salaberga_logo.png" alt="Logo Salaberga">
        </div>
        <div class="form-container">
            <?php
            if (isset($_SESSION['sucesso'])) {
                echo '<div class="success">' . htmlspecialchars($_SESSION['sucesso']) . '</div>';
                unset($_SESSION['sucesso']);
            }
            if (isset($_SESSION['erro'])) {
                echo '<div class="error">' . htmlspecialchars($_SESSION['erro']) . '</div>';
                unset($_SESSION['erro']);
            }
            ?>
            <form id="register-form" action="../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="salvar" value="1">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="nome">
                    <label for="inputNome" class="col-form-label"></label>
                    <div class="icone"><i class="fas fa-user"></i></div>
                    <input type="text" class="form-control" id="inputNome" name="nome" placeholder="Nome" required>
                </div>
                <div class="email">
                    <label for="inputEmail" class="col-form-label"></label>
                    <div class="icone"><i class="fas fa-envelope"></i></div>
                    <input type="email" class="form-control" id="inputEmail" name="login" placeholder="Email" required>
                </div>
                <div class="senha">
                    <label for="inputPassword" class="col-form-label"></label>
                    <div class="icone olho"><i class="fas fa-eye toggle-password"></i></div>
                    <input type="password" class="form-control" id="inputPassword" name="senha" placeholder="Senha" required>
                </div>
                <div class="botao">
                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">CADASTRAR</button>
                    </div>
                </div>
                <div class="links">
                    <a href="login.php">Já tem uma conta? Faça login</a>
                </div>
            </form>
        </div>
    </div>
    <style>
        .links {
            margin-top: 50px;
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
            margin-top: 12px;
            background-color: rgb(245, 245, 245);
            padding-right: 40px; /* Espaço para o ícone */
        }
        .nome, .email, .senha {
            position: relative;
            margin-bottom: -15px;
        }
        .icone {
            position: absolute;
            right: 10px;
            top: 75%;
            transform: translateY(-50%);
            color: #4CAF50;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .form-container {
            position: relative;
            left: 200px;
            z-index: 2;
            width: 43%;
            margin-top: 80px;
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
            height: 600px;
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
            margin-bottom: 200px;
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
            margin-bottom: 350px;
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
            top: 280px;
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
    </style>
    <script>
     document.getElementById('register-form').addEventListener('submit', function(event) {
            const nome = document.getElementById('inputNome').value;
            const email = document.getElementById('inputEmail').value;
            const senha = document.getElementById('inputPassword').value;

            if (!nome || !email || !senha) {
                event.preventDefault();
                alert('Por favor, preencha todos os campos.');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                event.preventDefault();
                alert('Por favor, insira um email válido.');
                return;
            }

            if (senha.length < 6) {
                event.preventDefault();
                alert('A senha deve ter pelo menos 6 caracteres.');
                return;
            }

            console.log('Enviando: Nome=' + nome + ', Email=' + email + ', Senha=' + senha);
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
    </script>
</body>
</html>