<?php
session_start();
require("../../model/modelaluno.php");
$matricula = $_SESSION['matricula'];
$x = new aluno();
$nome_aluno = $x -> nome_aluno($matricula);
// Configuração da paginação

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início - Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        .reports-section {
            background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6fb936);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .welcome-text {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .student-name {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .reports-btn {
            background-color: white;
            color: #28a745;
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .reports-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            background-color: #f8f9fa;
            color: #218838;
        }
        .icon-large {
            font-size: 4rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .logout-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background-color: rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        .logout-link:hover {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }
    </style>
</head>
<body>
    <a href="../../index.php" class="logout-link">
        <i class="fas fa-sign-out-alt"></i> Sair
    </a>

    <!-- Reports Section -->
    <section class="reports-section">
        <div class="container">
            <i class="fas fa-graduation-cap icon-large"></i>
            <h1 class="welcome-text">Bem-vindo(a)</h1>
            <h2 class="student-name"><?php echo htmlspecialchars($nome_aluno); ?></h2>
            <p class="lead mb-4">Acompanhe seu desempenho e evolução nas avaliações</p>
            <a href="meus_relatorios.php" class="btn reports-btn">
                <i class="fas fa-chart-line me-2"></i>Ver Relatórios
            </a>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
