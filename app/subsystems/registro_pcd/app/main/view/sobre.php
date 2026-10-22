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
    <title>Sobre o Sistema - EEEP Salaberga</title>
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

        .container {
            max-width: 1000px;
            background: var(--card-background);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 24px var(--shadow-color);
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
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        h3 {
            color: var(--primary-hover);
            font-size: 1.4rem;
            font-weight: 500;
            margin: 1.5rem 0 1rem;
        }

        p {
            color: var(--text-color);
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .feature-list {
            list-style: none;
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .feature-list li {
            margin-bottom: 0.8rem;
            position: relative;
            padding-left: 1.5rem;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .feature-list li::before {
            content: "•";
            color: var(--primary-color);
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }

        .note p {
            margin: 0;
            color: #856404;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }

            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            p, .feature-list li {
                font-size: 1rem;
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
        <h1>Sobre o Sistema</h1>
        
        <h2>Introdução</h2>
        <p>O Sistema de Registro de PCD's da EEEP Salaberga é uma plataforma desenvolvida para gerenciar e acompanhar o desenvolvimento dos alunos com deficiência da escola. O sistema foi projetado para facilitar o registro e acompanhamento das atividades diárias, presença e observações importantes sobre cada aluno.</p>

        <h2>Funcionalidades Principais</h2>
        
        <h3>1. Registro de Alunos</h3>
        <ul class="feature-list">
            <li>Cadastro completo de alunos com deficiência</li>
            <li>Informações pessoais (nome, idade, turma)</li>
            <li>Registro do tipo de deficiência</li>
            <li>Data de registro automática</li>
        </ul>

        <h3>2. Registro Médico</h3>
        <ul class="feature-list">
            <li>Registro detalhado do histórico médico</li>
            <li>Observações diárias sobre o aluno</li>
            <li>Registro de medicamentos e cuidados especiais</li>
            <li>Data e hora do registro automáticas</li>
        </ul>

        <h3>3. Visualização de Dados</h3>
        <ul class="feature-list">
            <li>Tabela interativa com todos os registros</li>
            <li>Filtros e busca avançada</li>
            <li>Visualização detalhada de cada aluno</li>
            <li>Exportação de dados em PDF</li>
        </ul>

        <h2>Como Usar o Sistema</h2>

        <h3>1. Acessando o Sistema</h3>
        <p>Para acessar o sistema, você precisa ter um usuário cadastrado. Na tela de login, insira seu e-mail e senha para entrar no sistema.</p>

        <h3>2. Registrando um Aluno</h3>
        <p>No menu principal, selecione "Registrar Aluno" e preencha todos os campos obrigatórios. O sistema irá automaticamente registrar a data e hora do cadastro.</p>

        <h3>3. Registro Médico</h3>
        <p>Na seção "Registro Médico", selecione o aluno e preencha as informações necessárias sobre seu estado de saúde e observações do dia.</p>

        <h3>4. Visualizando Dados</h3>
        <p>Na página de visualização, você pode ver todos os registros em uma tabela organizada. Use os filtros para encontrar informações específicas e exporte os dados em PDF quando necessário.</p>

        <div class="note">
            <p><strong>Importante:</strong> Mantenha suas credenciais de acesso em segurança e faça logout ao sair do sistema para proteger as informações dos alunos.</p>
        </div>

        <h2>Suporte e Ajuda</h2>
        <p>Se você encontrar algum problema ou tiver dúvidas sobre o funcionamento do sistema, entre em contato com a equipe de suporte da escola.</p>
    </div>

    <script>
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