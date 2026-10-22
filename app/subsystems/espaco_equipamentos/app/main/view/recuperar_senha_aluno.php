<?php
session_start();
if (isset($_SESSION['aluno_id'])) {
    header("Location: painel_aluno.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Aluno</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Poppins', sans-serif; 
        }
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background: linear-gradient(135deg, #007A33, #FFFFFF); 
        }
        .container { 
            background: #fff; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); 
            width: 100%; 
            max-width: 400px; 
        }
        h2 { 
            text-align: center; 
            color: #007A33; 
            margin-bottom: 20px; 
        }
        .input-group { 
            position: relative;
            margin-bottom: 20px; 
        }
        .input-group label { 
            display: block; 
            color: #333; 
            margin-bottom: 5px; 
        }
        .input-group input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-size: 16px; 
            transition: border-color 0.3s;
        }
        .input-group input:focus {
            border-color: #007A33;
            outline: none;
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #007A33; 
            color: #fff; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
            transition: background 0.3s; 
        }
        button:hover { 
            background: #005F27; 
        }
        .error, .success { 
            text-align: center; 
            margin-top: 10px; 
            display: none; 
        }
        .error { 
            color: red; 
        }
        .success { 
            color: green; 
        }
        .back-to-login { 
            text-align: center; 
            margin-top: 15px; 
        }
        .back-to-login a { 
            color: #007A33; 
            text-decoration: none; 
        }
        .back-to-login a:hover { 
            text-decoration: underline; 
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        @media (max-width: 768px) {
          body {
            padding: 1rem;
            min-height: 100vh;
            background: linear-gradient(135deg, #007A33, #FFFFFF);
            display: flex;
            align-items: center;
            justify-content: center;
          }
          .container {
            padding: 1.5rem;
            margin: 0;
            border-radius: 15px;
            width: 100%;
            max-width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
          }
          h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
          }
          .input-group {
            margin-bottom: 1rem;
          }
          .input-group label {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
          }
          .input-group input {
            padding: 12px;
            font-size: 1rem;
            width: 100%;
            border-radius: 8px;
          }
          button {
            padding: 12px;
            font-size: 1rem;
            width: 100%;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
          }
          .error, .success {
            font-size: 0.9rem;
            margin-top: 0.8rem;
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
          }
          .back-to-login {
            margin-top: 1rem;
          }
          .back-to-login a {
            font-size: 0.9rem;
            padding: 0.5rem;
            display: inline-block;
          }
          .logo img {
            max-width: 120px;
          }
        }

        @media (max-width: 480px) {
          body {
            padding: 0.8rem;
          }
          .container {
            padding: 1.2rem;
            border-radius: 12px;
          }
          h2 {
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
          }
          .input-group label {
            font-size: 0.85rem;
          }
          .input-group input {
            padding: 10px;
            font-size: 0.95rem;
          }
          button {
            padding: 10px;
            font-size: 0.95rem;
          }
          .error, .success {
            font-size: 0.85rem;
            padding: 0.7rem;
          }
          .back-to-login a {
            font-size: 0.85rem;
          }
          .logo img {
            max-width: 100px;
          }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../../../assets/img/Design sem nome.svg" alt="Logo">
        </div>
        <h2><i class="fas fa-key"></i> Recuperar Senha</h2>
        <form id="recuperar-senha-form">
            <div class="input-group">
                <label for="matricula">Matrícula</label>
                <input type="text" id="matricula" name="matricula" required placeholder="Digite sua matrícula">
            </div>
            <button type="submit">Recuperar Senha</button>
            <p class="error" id="error-message"></p>
            <p class="success" id="success-message"></p>
        </form>
        <div class="back-to-login">
            <a href="login_aluno.php">Voltar para o login</a>
        </div>
    </div>
    <script>
        document.getElementById('recuperar-senha-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const matricula = document.getElementById('matricula').value;
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');

            try {
                const response = await fetch('../controllers/RecuperarSenhaController.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=recuperar&matricula=${encodeURIComponent(matricula)}`
                });
                const data = await response.json();
                if (data.success) {
                    successMessage.textContent = data.message;
                    successMessage.style.display = 'block';
                    errorMessage.style.display = 'none';
                    setTimeout(() => {
                        window.location.href = data.redirect || 'definir_senha_aluno.php?matricula=' + encodeURIComponent(matricula);
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                    successMessage.style.display = 'none';
                }
            } catch (error) {
                errorMessage.textContent = 'Erro ao conectar ao servidor';
                errorMessage.style.display = 'block';
                successMessage.style.display = 'none';
            }
        });
    </script>
</body>
</html> 