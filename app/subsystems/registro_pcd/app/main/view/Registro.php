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
    <meta name="description" content="Sistema de registro de alunos da EEEP Salaberga">
    <meta name="keywords" content="registro, alunos, escola, EEEP Salaberga">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    <title>Registro de Alunos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            background-image: url('../assets/img/fundo.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .navbar img:hover {
            transform: scale(1.05);
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

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .user-info:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .user-info i {
            font-size: 18px;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            display: none;
            z-index: 1000;
            margin-top: 5px;
        }

        .user-dropdown.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-dropdown-item {
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .user-dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .user-dropdown-item i {
            color: #007A33;
            width: 20px;
        }

        .user-dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }

        .user-dropdown-header {
            padding: 12px 15px;
            color: #666;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-menu li {
            margin: 0 15px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font: 1rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FF8C00;
            transform: translateY(-2px);
        }

        .menu-toggle {
            display: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .menu-toggle:hover {
            transform: scale(1.1);
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin-top: 80px;
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
            transition: transform 0.3s ease;
        }

        .form-group:hover {
            transform: translateY(-2px);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8faf8;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #007A33;
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.1);
            outline: none;
            transform: translateY(-1px);
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

        button:active {
            transform: translateY(0);
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
            animation: fadeIn 0.3s ease-in;
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #ef9a9a;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in;
        }

        .error-message i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 80px 20px 20px 20px;
                padding: 20px;
            }

            .navbar {
                padding: 10px;
            }

            .navbar-right {
                gap: 10px;
            }

            .user-info span {
                display: none;
            }

            .user-dropdown {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                width: 100%;
                border-radius: 0;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: #007A33;
                padding: 20px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu li {
                margin: 10px 0;
            }

            .menu-toggle {
                display: block;
            }

            input[type="text"],
            input[type="number"],
            select {
                font-size: 14px;
                padding: 10px;
            }

            button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        .btn-pdf {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-pdf:hover {
            background-color: #c82333;
            color: white;
            text-decoration: none;
        }

        .btn-pdf i {
            margin-right: 5px;
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
                Voltar
            </a>
            <div class="user-info" onclick="toggleUserDropdown()">
                <i class="fas fa-user"></i>
                <span><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário'; ?></span>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-dropdown-header">
                        <i class="fas fa-user-circle"></i>
                        <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário'; ?>
                    </div>
                    <div class="user-dropdown-divider"></div>
                    <a href="../index.php" class="user-dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Registro de Alunos</h2>
        <?php
        if (isset($_GET['erro'])) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> Erro ao realizar o cadastro. Por favor, tente novamente.
                  </div>';
        }
        ?>
        <form action="../control/controlCadastrar.php" method="POST" id="alunoForm">
            <div class="form-group">
                <label for="nome">Nome do Aluno</label>
                <input type="text" id="nome" name="nome" placeholder="Nome" required aria-required="true">
            </div>
            <div class="form-group">
                <label for="idade">Idade</label>
                <input type="number" id="idade" name="idade" placeholder="Idade" min="10" max="100" required aria-required="true">
            </div>
            <div class="form-group">
                <label for="turma">Turma</label>
                <select id="turma" name="turma" required aria-required="true">
                    <option value="" disabled selected>Selecione a turma</option>
                    <option value="1A">1º Ano - Turma A</option>
                    <option value="1B">1º Ano - Turma B</option>
                    <option value="1C">1º Ano - Turma C</option>
                    <option value="1D">1º Ano - Turma D</option>
                    <option value="2A">2º Ano - Turma A</option>
                    <option value="2B">2º Ano - Turma B</option>
                    <option value="2C">2º Ano - Turma C</option>
                    <option value="2D">2º Ano - Turma D</option>
                    <option value="3A">3º Ano - Turma A</option>
                    <option value="3B">3º Ano - Turma B</option>
                    <option value="3C">3º Ano - Turma C</option>
                    <option value="3D">3º Ano - Turma D</option>
                </select>
            </div>
            <button type="submit">Registrar</button>
            <div class="error" id="error-message"></div>
        </form>
        <div class="links">
        </div>
    </div>
    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        menuToggle.addEventListener('click', () => {
            const isExpanded = navMenu.classList.toggle('active');
            menuToggle.setAttribute('aria-expanded', isExpanded);
        });

        const form = document.getElementById('alunoForm');
        const errorMessage = document.getElementById('error-message');
        const button = form.querySelector('button');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const nome = document.getElementById('nome').value.trim();
            const idade = document.getElementById('idade').value;
            const turma = document.getElementById('turma').value;

            errorMessage.textContent = '';

            if (!nome) {
                errorMessage.textContent = 'Por favor, preencha o nome do aluno.';
                return;
            }

            if (nome.length < 2) {
                errorMessage.textContent = 'O nome deve ter pelo menos 2 caracteres.';
                return;
            }

            if (!idade) {
                errorMessage.textContent = 'Por favor, preencha a idade.';
                return;
            }

            if (idade < 10 || idade > 100) {
                errorMessage.textContent = 'A idade deve estar entre 10 e 100 anos.';
                return;
            }

            if (!turma) {
                errorMessage.textContent = 'Por favor, selecione uma turma.';
                return;
            }

            button.textContent = 'Enviando...';
            button.disabled = true;

            form.submit();
        });

        document.getElementById('nome').addEventListener('input', function() {
            const nome = this.value.trim();
            if (nome.length < 2) {
                this.setCustomValidity('O nome deve ter pelo menos 2 caracteres.');
            } else {
                this.setCustomValidity('');
            }
        });

        document.getElementById('idade').addEventListener('input', function() {
            const idade = this.value;
            if (idade < 10 || idade > 100) {
                this.setCustomValidity('A idade deve estar entre 10 e 100 anos.');
            } else {
                this.setCustomValidity('');
            }
        });

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');

            // Fechar o dropdown quando clicar fora dele
            document.addEventListener('click', function closeDropdown(e) {
                if (!e.target.closest('.user-info')) {
                    dropdown.classList.remove('active');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }
    </script>
</body>
</html>