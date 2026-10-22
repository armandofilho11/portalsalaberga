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
    <title>Esqueceu a Senha - EEEP Salaberga</title>
    <style>
        .Email  , .senha{
            position: relative;
            margin-bottom: -15px;
        }
        .links {
            margin-top: 30px;
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
            padding-right: 40px;
        }
        .input-field {
            position: relative;
            margin-bottom: 15px;
        }
        .iconeEmail {
            position: absolute;
            right: 10px;
            top: 55px;
            transform: translateY(-50%);
            color: #4CAF50;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .iconeSenha {
            position: absolute;
            right: 10px;
            top: 115px;
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
            margin-top: 120px;
            font-family: 'Poppins', sans-serif;
        }
        .botao {
            position: relative;
            z-index: 2;
            border-radius: 3px;
            top: 20px;
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
    </style>
</head>
<body class="corpo">
    <div class="retangulo">
        <div class="img-gradient"></div>
        <div class="img-hover"></div>
        <h1>EEEP Salaberga</h1>
        <p>Transformando o futuro através <br> da educação e inovação</p>
        <h2>Recuperar Senha</h2>
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
            <form id="forgot-password-form" action="../controllers/passwordcontroller.php" method="POST">
                <input type="hidden" name="acao" value="verificar">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="Email">
                    <label for="inputEmail" class="col-form-label"></label>
                    <div class="iconeEmail"><i class="fas fa-envelope"></i></div>
                    <input type="email" class="form-control" id="inputEmail" name="login" placeholder="Email" required>
                </div>
                <div class="nome">
                    <label for="inputName" class="col-form-label"></label>
                    <div class="iconeSenha"><i class="fas fa-user"></i></div>
                    <input type="text" class="form-control" id="inputName" name="nome" placeholder="Nome" required>
                </div>
                <div class="botao">
                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">VERIFICAR</button>
                    </div>
                </div>
                <div class="links">
                    <a href="login.php">Voltar ao login</a>
                    <a href="register.php">Não tem conta? Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('forgot-password-form').addEventListener('submit', function(event) {
            const email = document.getElementById('inputEmail').value;
            const name = document.getElementById('inputName').value;

            if (!email || !name) {
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

            console.log('Enviando: Email=' + email + ', Nome=' + name);
        });
    </script>
</body>
</html>