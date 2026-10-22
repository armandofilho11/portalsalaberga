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
    <meta name="description" content="Perfil do usuário - EEEP Salaberga">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">
    <title>Perfil do Usuário</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            max-width: 800px;
            margin: 100px auto 30px;
            padding: 20px;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
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

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: #007A33;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }

        .profile-info h2 {
            color: #333;
            margin-bottom: 5px;
        }

        .profile-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .profile-section {
            margin-bottom: 25px;
        }

        .profile-section h3 {
            color: #007A33;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007A33;
        }

        .info-item h4 {
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .info-item p {
            color: #333;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #007A33;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                margin: 80px 20px 20px;
                padding: 15px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
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
        </div>
    </nav>

    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-info">
                </div>
            </div>

            <div class="profile-section">
                <h3>Informações Pessoais</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <h4>Nome Completo</h4>
                        <p>Alexandre</p>
                    </div>
                    <div class="info-item">
                        <h4>Email</h4>
                        <p>alexandre@gmail.com</p>
                    </div>
                    <div class="info-item">
                        <h4>Último Acesso</h4>
                        <p>2025-01-01</p>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <h3>Atividades Recentes</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <h4>Registros Realizados</h4>
                        <p>0 registros</p>
                    </div>
                    <div class="info-item">
                        <h4>Último Registro</h4>
                        <p>Nenhum registro encontrado</p>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.location.href='Recsenha.php'">
                    <i class="fas fa-key"></i>
                    Alterar Senha
                </button>
                <button class="btn btn-secondary" onclick="window.location.href='../index.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair
                </button>
            </div>
        </div>
    </div>
</body>
</html> 