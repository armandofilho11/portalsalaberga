<?php 
 session_start();
 function redirect_to_login()
 {
   header('Location: ../../../../../main/views/autenticacao/login.php');
 }
 if (!isset($_SESSION['Email'])) {
   session_destroy();
   redirect_to_login();
 } 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de registro de deficiência da EEEP Salaberga">
    <meta name="keywords" content="registro, deficiência, escola, EEEP Salaberga">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/salaberga.svg" type="image/svg+xml">
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <title>Registro de Deficiência - EEEP Salaberga</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            background-image: url('/img/fundo.png');
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #007A33;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
       
         .school-name {
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            margin-left: 15px;
            letter-spacing: 0.5px;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin: 80px auto 30px auto;
            animation: slideUp 0.5s ease-out forwards;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8faf8;
        }

        select:focus, textarea:focus {
            border-color: #007A33;
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.1);
            outline: none;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            background-color: #007A33;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        button:hover {
            background-color: #005e27;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 122, 51, 0.2);
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #ef9a9a;
        }

        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #a5d6a7;
        }

        @media (max-width: 768px) {
            .container {
                margin: 70px 20px 20px 20px;
                padding: 20px;
            }

            .navbar {
                padding: 10px;
            }

            .navbar-right {
                gap: 10px;
            }

            .back-btn span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="school-name">EEEP Salaberga</span>
        </div>
        <div class="navbar-right">
            <a href="menu.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>
    </nav>

    <div class="container">
        <h2>Registro de Deficiência</h2>
        <?php
        if (isset($_GET['erro'])) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> Erro ao registrar deficiência. Por favor, tente novamente.
                  </div>';
        } elseif (isset($_GET['sucesso'])) {
            echo '<div class="success-message">
                    <i class="fas fa-check-circle"></i> Deficiência registrada com sucesso!
                  </div>';
        }
        ?>
        <form action="../control/controlRegistroDeficiencia.php" method="POST" id="deficienciaForm">
            <div class="form-group">
                <label for="aluno">Selecione o Aluno</label>
                <select id="aluno" name="aluno_id" required>
                    <option value="">Selecione um aluno...</option>
                    <?php
                    require_once '../model/model.php';
                    $model = new Model();
                    $alunos = $model->buscarTodosRegistros();
                    foreach ($alunos as $aluno) {
                        echo "<option value='" . $aluno['id'] . "'>" . htmlspecialchars($aluno['nome']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="deficiencia">Descrição da Deficiência</label>
                <textarea id="deficiencia" name="deficiencia" required placeholder="Descreva a deficiência do aluno..."></textarea>
            </div>
            <button type="submit">Registrar Deficiência</button>
            <div class="error" id="error-message"></div>
        </form>
    </div>

    <script>
        const form = document.getElementById('deficienciaForm');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const alunoId = document.getElementById('aluno').value;
            const deficiencia = document.getElementById('deficiencia').value.trim();

            errorMessage.textContent = '';

            if (!alunoId) {
                errorMessage.textContent = 'Por favor, selecione um aluno.';
                return;
            }

            if (!deficiencia) {
                errorMessage.textContent = 'Por favor, descreva a deficiência.';
                return;
            }

            if (deficiencia.length < 3) {
                errorMessage.textContent = 'A descrição da deficiência deve ter pelo menos 3 caracteres.';
                return;
            }

            this.submit();
        });
    </script>
</body>
</html> 