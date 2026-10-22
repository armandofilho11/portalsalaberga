<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Avaliativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-bg {
            background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6fb936);
            color: white;
            padding: 2rem 0;
            margin-bottom: 4rem;
        }
        .option-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            height: 400px;
            position: relative;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
            background: white;
        }
        .icon-large {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #ff6b6b, #ffd93d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .option-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #343a40;
        }
        .option-description {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-custom {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-individual {
            background: linear-gradient(45deg, #ff6b6b, #ffd93d);
            border: none;
            color: white;
        }
        .btn-individual:hover {
            background: linear-gradient(45deg, #ff5252, #ffc107);
            transform: scale(1.05);
            color: white;
        }
        .btn-collective {
            background: linear-gradient(45deg, #6fb936, #20c997);
            border: none;
            color: white;
        }
        .btn-collective:hover {
            background: linear-gradient(45deg, #5a9f2f, #1ba87e);
            transform: scale(1.05);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-bg">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                    <h4 class="mb-0">Relatórios Avaliativos</h4>
                </div>
                <nav>
                    <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
                    <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
                    <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
                    <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <!-- Individual Report Option -->
            <div class="col-md-6 mb-4">
                <div class="option-card">
                    <div class="card-overlay">
                        <i class="fas fa-user-graduate icon-large"></i>
                        <h3 class="option-title">Relatório Individual</h3>
                        <p class="option-description">
                            Visualize o desempenho detalhado de cada aluno individualmente, 
                            incluindo notas, progresso e histórico de avaliações.
                        </p>
                        <a href="relatorios_alunos.php" class="btn btn-custom btn-individual">
                            <i class="fas fa-chart-line me-2"></i>Ver Relatórios Individuais
                        </a>
                    </div>
                </div>
            </div>

            <!-- Collective Report Option -->
            <div class="col-md-6 mb-4">
                <div class="option-card">
                    <div class="card-overlay">
                        <i class="fas fa-users icon-large"></i>
                        <h3 class="option-title">Relatório Coletivo</h3>
                        <p class="option-description">
                            Analise o desempenho geral da turma, estatísticas comparativas
                            e métricas de aproveitamento em grupo.
                        </p>
                        <a href="relatorio_coletivo.php" class="btn btn-custom btn-collective">
                            <i class="fas fa-chart-bar me-2"></i>Ver Relatório Coletivo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
