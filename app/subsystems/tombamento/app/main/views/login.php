<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login - EEEP Salaberga</title>
</head>
<body class="corpo">
    <?php
    if (isset($_SESSION['erro'])) {
        echo '<div class="error-message">' . htmlspecialchars($_SESSION['erro']) . '</div>';
        unset($_SESSION['erro']);
    }
    ?>
    <div class="retangulo">
        <div class="img-gradient"></div>
        <div class="img-hover"></div>
        <h1>EEEP Salaberga</h1>
        <p>Transformando o futuro através <br> da educação e inovação</p>
        <h2>Login</h2>
        <div class="img">
            <img src="../img/salaberga_logo.png" alt="Logo Salaberga">
        </div>
        <div class="form-container">
            <form id="login-form" action="../controllers/autenticar.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="email">
                    <label for="inputLogin" class="col-form-label"></label>
                    <div class="user"><i class="fas fa-user"></i></div>
                </div>
                <input type="email" class="form-control" id="inputLogin" name="login" placeholder="Email" required>
                <div class="senha">
                    <label for="inputPassword" class="col-form-label"></label>
                    <div class="olho"><i class="fas fa-eye toggle-password"></i></div>
                </div>
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Senha" required>
                <div class="botao">
                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">LOGAR</button>
                    </div>
                </div>
                <div class="links">
                    <a href="forgot-password.php">Esqueceu a senha?</a>
                    <a href="register.php">Não tem login? Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>
    <style>
        .error-message {
            font-family: 'Poppins', sans-serif;
            color: red;
            text-align: center;
            margin-bottom: 10px;
            z-index: 1000;
            font-size: 16px;
        }
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
        }
        .email {
            position: absolute;
            right: 20px;
            top: 23%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
            cursor: pointer;
        }
        .user {
            margin-bottom: 81px;
        }
        .olho {
            margin-bottom: 48px;
        }
        .senha {
            position: absolute;
            right: 20px;
            top: 37%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
            cursor: pointer;
        }
        .form-container {
            position: relative;
            left: 200px;
            z-index: 2;
            width: 43%;
            margin-top: 120px;
            font-family: 'Poppins';
        }
        .email, .senha {
            margin-bottom: 2px;
        }
        .botao {
            position: relative;
            left: 0px;
            z-index: 1;
            border-radius: 3px;
            background-color: #005A24;
            top: 25px;
        }
        .corpo {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: rgb(255, 255, 255);
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
            box-shadow: 0 20px 40px var(--shadow-color);
            box-shadow: 1px 7px 9px rgba(0, 0, 0, 0.2);
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
            background: linear-gradient(135deg, #007A33, #FF8C00);
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
            color: var(--bg-color);
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
            color: var(--bg-color);
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
        document.getElementById('login-form').addEventListener('submit', function(event) {
            const login = document.getElementById('inputLogin').value;
            const password = document.getElementById('inputPassword').value;
            if (!login || !password) {
                event.preventDefault();
                alert('Por favor, preencha todos os campos.');
                return;
            }
            console.log('Enviando: Login=' + login + ', Senha=' + password);
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