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
    <link rel="icon" href="../img/salaberga.svg" type="image/svg+xml">
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <title>Menu - EEEP Salaberga</title>
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

        .navbar img {
            max-height: 45px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .navbar img:hover {
            transform: scale(1.05);
        }

        .school-name {
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            margin-left: 15px;
            letter-spacing: 0.5px;
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

        .logout-btn {
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

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
            margin: 70px auto 30px auto;
            animation: slideUp 0.5s ease-out forwards;
            flex: 1;
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

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .menu-item {
            background-color: #f8f9fa;
            border: 2px solid #007A33;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #007A33;
            color: white;
        }

        .menu-item i {
            font-size: 2em;
            margin-bottom: 10px;
            color: #007A33;
            transition: color 0.3s ease;
        }

        .menu-item:hover i {
            color: white;
        }

        .menu-item h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .menu-item p {
            font-size: 14px;
            color: #666;
            transition: color 0.3s ease;
        }

        .menu-item:hover p {
            color: #fff;
        }

        @media (max-width: 768px) {
            .container {
                margin: 60px 20px 20px 20px;
                padding: 20px;
            }

            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .menu-item {
                padding: 15px;
            }

            .menu-item i {
                font-size: 1.5em;
            }

            .menu-item h3 {
                font-size: 16px;
            }

            .menu-item p {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 50px 10px 15px 10px;
                padding: 15px;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }

            h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }
        }

        .about-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            justify-content: center;
            align-items: center;
        }

        .about-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            position: relative;
            animation: slideUp 0.3s ease;
        }

        .about-content h3 {
            color: #007A33;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .about-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .about-content .close {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .about-content .close:hover {
            color: #007A33;
        }

        .about-features {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .feature-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #007A33;
        }

        .feature-item i {
            color: #007A33;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .feature-item h4 {
            color: #333;
            margin-bottom: 5px;
        }

        .feature-item p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-preview {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-preview:hover {
            background-color: #45a049;
        }

        .footer {
            background: linear-gradient(135deg, #007A33, #005A24);
            color: white;
            padding: 10px 0 0 0;
            width: 100%;
            margin-top: auto;
            position: relative;
            z-index: 1000;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .footer-section {
            padding: 5px;
        }

        .footer-section h4 {
            color: #FF8C00;
            font-size: 0.9rem;
            margin-bottom: 5px;
            font-weight: 600;
            display: inline-block;
            margin-right: 10px;
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.3;
            margin-bottom: 4px;
            font-size: 0.75rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 3px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
        }

        .footer-section ul li:before {
            content: "•";
            color: #FF8C00;
            margin-right: 4px;
            font-size: 0.7rem;
        }

        .social-links {
            display: inline-flex;
            align-items: center;
            gap: 15px;
        }

        .social-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .social-link:hover {
            transform: translateY(-2px);
            color: #FF8C00;
        }

        .social-link i {
            font-size: 1.1rem;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 5px 0;
            margin-top: 8px;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.7rem;
            margin: 0;
        }

        @media (max-width: 768px) {
            .footer {
                padding: 8px 0 0 0;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 8px;
            }

            .footer-section {
                padding: 5px;
            }

            .footer-section ul li {
                justify-content: center;
            }

            .footer-bottom {
                padding: 4px 0;
                margin-top: 8px;
            }
        }

        @media (max-width: 480px) {
            .footer {
                padding: 5px 0 0 0;
            }

            .footer-content {
                padding: 0 8px;
            }

            .footer-section h4 {
                font-size: 0.8rem;
                margin-bottom: 4px;
            }

            .footer-section p,
            .footer-section ul li {
                font-size: 0.7rem;
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
            <div class="user-info" onclick="toggleUserDropdown()">
                <i class="fas fa-user"></i>
                <span><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário'; ?></span>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-dropdown-header">
                        <i class="fas fa-user-circle"></i>
                        <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário'; ?>
                    </div>
                    <div class="user-dropdown-divider"></div>
                    <a href="perfil.php" class="user-dropdown-item">
                        <i class="fas fa-user"></i>
                        Meu Perfil
                    </a>
                    <div class="user-dropdown-divider"></div>
                    <a href="sobre.php" class="user-dropdown-item">
                        <i class="fas fa-info-circle"></i>
                        Sobre o Site
                    </a>
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
        <h2>Menu Principal</h2>
        <?php
        if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'deficiencia') {
            echo '<div class="success-message" style="margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> Deficiência registrada com sucesso!
                  </div>';
        }
        ?>
        <div class="menu-grid">
            <a href="Registro.php" class="menu-item">
                <i class="fas fa-user-plus"></i>
                <h3>Novo Registro</h3>
                <p>Cadastrar novo aluno PCD</p>
            </a>
            
            <a href="RegistroDeficiencia.php" class="menu-item">
                <i class="fas fa-wheelchair"></i>
                <h3>Registro de Deficiência</h3>
                <p>Cadastrar deficiência do aluno</p>
            </a>
            
            <a href="RegistroDia.php" class="menu-item">
                <i class="fas fa-calendar-check"></i>
                <h3>Registro Diário</h3>
                <p>Gerenciar registros diários</p>
            </a>
            
            <a href="visualizar.php" class="menu-item">
                <i class="fas fa-table"></i>
                <h3>Visualizar Registros</h3>
                <p>Ver todos os registros cadastrados</p>
            </a>

            <a href="RegistroAcompanhante.php" class="menu-item">
                <i class="fas fa-user-nurse"></i>
                <h3>Registro de Acompanhante</h3>
                <p>Cadastrar novo acompanhante</p>
            </a>

            <a href="../../../../../main/views/autenticacao/login.php" class="menu-item">
                <i class="fas fa-home"></i>
                <h3>Página Inicial</h3>
                <p>Voltar para a página inicial</p>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Sistema de Registros PCD</h4>
                <p>Desenvolvido para a EEEP Salaberga</p>
                <p>Em caso de dúvidas, entre em contato com o desenvolvedor!</p>
            </div>
            <div class="footer-section">
                <h4>Funcionalidades</h4>
                <ul>
                    <li>Registro de Alunos PCD</li>
                    <li>Acompanhamento Diário</li>
                    <li>Geração de Relatórios</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Desenvolvido por:</h4>
                <div class="social-links">
                    <a href="https://instagram.com/ssilvaaxsz__" target="_blank" class="social-link">
                        <i class="fab fa-instagram"></i>
                        @ssilvaaxsz__
                    </a>
                    <a href="https://wa.me/5585985591438" target="_blank" class="social-link">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 EEEP Salaberga. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');

            // Close dropdown when clicking outside
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
