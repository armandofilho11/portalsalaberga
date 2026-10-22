<?php
require_once("../../model/modelprofessor.php");

session_start();
if (!isset($_SESSION['id_aluno'])) {
    header("Location: ../../index.php");
    exit();
}

$professor = new Professor();
$relatorios = $professor->get_relatorios_aluno($_SESSION['id_aluno']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #006400;
            color: white;
        }
        .report-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .grade-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto;
        }
        .grade-excellent {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }
        .grade-good {
            background: linear-gradient(45deg, #17a2b8, #0dcaf0);
            color: white;
        }
        .grade-average {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
        }
        .grade-poor {
            background: linear-gradient(45deg, #dc3545, #f11);
            color: white;
        }
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #495057;
        }
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="header-bg py-3 px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                <h5 class="mb-0">Meus Relatórios</h5>
            </div>
            <nav>
                <a href="inicioaluno.php" class="text-white mx-2 text-decoration-none">Início</a>
            
                <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if (empty($relatorios)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard"></i>
                <h3>Nenhum relatório disponível</h3>
                <p>Você ainda não realizou nenhuma avaliação.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($relatorios as $relatorio): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card report-card shadow-sm" onclick="window.location.href='detalhes_relatorio.php?id=<?php echo $relatorio['id']; ?>'">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4"><?php echo htmlspecialchars($relatorio['nome_avaliacao']); ?></h5>
                                
                                <?php
                                $nota = floatval($relatorio['nota']);
                                $gradeClass = 'grade-poor';
                                if ($nota >= 9) {
                                    $gradeClass = 'grade-excellent';
                                } elseif ($nota >= 7) {
                                    $gradeClass = 'grade-good';
                                } elseif ($nota >= 5) {
                                    $gradeClass = 'grade-average';
                                }
                                ?>
                                
                                <div class="grade-circle <?php echo $gradeClass; ?>">
                                    <?php echo number_format($nota, 1); ?>
                                </div>
                                
                                <div class="stats-container">
                                    <div class="stat-item">
                                        <div class="stat-value text-success">
                                            <?php echo $relatorio['acertos']; ?>
                                        </div>
                                        <div class="stat-label">Acertos</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value text-danger">
                                            <?php echo $relatorio['erros']; ?>
                                        </div>
                                        <div class="stat-label">Erros</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">
                                            <?php echo ucfirst($relatorio['dificuldade']); ?>
                                        </div>
                                        <div class="stat-label">Dificuldade</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 