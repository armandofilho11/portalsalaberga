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
    <meta name="description" content="Sistema de registro médico da EEEP Salaberga">
    <meta name="keywords" content="registro, médico, escola, EEEP Salaberga">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Registro do Dia</title>
    <style>
        :root {
            --primary-color: #007A33;
            --primary-hover: #005e27;
            --secondary-color: #FF8C00;
            --background-color: #f8faf8;
            --card-background: #ffffff;
            --text-color: #2d3748;
            --border-color: #e2e8f0;
            --shadow-color: rgba(0, 122, 51, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e6f0e6 0%, #f0f7f0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 80px;
        }

        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
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

        .navbar img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .school-name {
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            margin-left: 15px;
            letter-spacing: 0.5px;
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
            color: var(--primary-color);
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
            font-size: 1rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .medical-box {
            background: var(--card-background);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 24px var(--shadow-color);
            width: 100%;
            max-width: 600px;
            margin: 20px;
            animation: slideUp 0.8s ease-out forwards;
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

        h1 {
            color: var(--primary-color);
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        select {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background-color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.1);
            outline: none;
        }

        textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background-color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            resize: vertical;
            min-height: 120px;
        }

        textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.1);
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 122, 51, 0.2);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .error {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            text-align: center;
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

        @media (max-width: 768px) {
            .medical-box {
                margin: 10px;
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: var(--primary-color);
                padding: 20px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

            textarea {
                min-height: 100px;
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
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-100 to-purple-100 flex items-center justify-center min-h-screen">
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
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md medical-box">
        <h1 class="text-3xl font-extrabold mb-6 text-center" style="color: #007A33">Registro Diário</h1>
        
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $_SESSION['tipo_mensagem'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php 
                echo $_SESSION['mensagem'];
                unset($_SESSION['mensagem']);
                unset($_SESSION['tipo_mensagem']);
                ?>
            </div>
        <?php endif; ?>

        <form id="medicalForm" action="../control/controlRegistroDia.php" method="POST">
            <div class="mb-5">
                <label for="aluno" class="block text-sm font-semibold text-gray-800">Selecione o Aluno</label>
                <select id="aluno" name="aluno_id" required class="mt-2 block w-full p-3 border border-gray-200 rounded-lg bg-gray-50">
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

            <div class="mb-5">
                <label for="presenca" class="block text-sm font-semibold text-gray-800">Presença</label>
                <select id="presenca" name="presenca" required class="mt-2 block w-full p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <option value="1">Presente</option>
                    <option value="0">Ausente</option>
                </select>
            </div>

            <div class="mb-5">
                <label for="observacoes" class="block text-sm font-semibold text-gray-800">Registo do Dia</label>
                <textarea id="observacoes" name="observacoes" rows="4" maxlength="500" class="mt-2 block w-full p-3 border border-gray-200 rounded-lg bg-gray-50 placeholder-gray-400" placeholder="Digite o registo do dia..." aria-required="true" aria-label="Observações"></textarea>
            </div>

            <button type="submit" class="w-full bg-green-700 text-white p-3 rounded-lg font-semibold hover:bg-green-800 transition-transform duration-300">Registrar</button>
            <div class="error" id="error-message"></div>
        </form>
    </div>

    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const navMenu = document.querySelector('.nav-menu');
        const form = document.getElementById('medicalForm');
        const errorMessage = document.getElementById('error-message');

        menuToggle.addEventListener('click', () => {
            const isExpanded = navMenu.classList.toggle('active');
            menuToggle.setAttribute('aria-expanded', isExpanded);
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const alunoId = document.getElementById('aluno').value;
            const presenca = document.getElementById('presenca').value;
            const observacoes = document.getElementById('observacoes').value.trim();

            if (!alunoId) {
                errorMessage.textContent = 'Por favor, selecione um aluno.';
                return;
            }

            if (!presenca) {
                errorMessage.textContent = 'Por favor, selecione a presença.';
                return;
            }

            if (!observacoes) {
                errorMessage.textContent = 'Por favor, preencha as observações.';
                return;
            }

            this.submit();
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
