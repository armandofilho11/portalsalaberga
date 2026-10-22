<?php
require_once("../../model/modelprofessor.php");

$professor = new Professor();
$id_relatorio = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_relatorio) {
    header("Location: meus_relatorios.php");
    exit();
}

$relatorio = $professor->get_detalhes_relatorio($id_relatorio);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #006400;
            color: white;
        }
        .detail-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .grade-large {
            font-size: 4rem;
            font-weight: bold;
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            color: white;
            margin-bottom: 1rem;
        }
        .grade-excellent {
            background: linear-gradient(45deg, #28a745, #20c997);
        }
        .grade-good {
            background: linear-gradient(45deg, #17a2b8, #0dcaf0);
        }
        .grade-average {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
        }
        .grade-poor {
            background: linear-gradient(45deg, #dc3545, #f11);
        }
        .info-item {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6c757d;
            font-weight: 500;
        }
        .info-value {
            color: #212529;
            font-weight: 600;
        }
        .stats-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .progress {
            height: 1.5rem;
            border-radius: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="header-bg py-3 px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                <h5 class="mb-0">Detalhes do Relatório</h5>
            </div>
            <nav>
                <a href="inicioaluno.php" class="text-white mx-2 text-decoration-none">Início</a>
                <a href="meus_relatorios.php" class="text-white mx-2 text-decoration-none">Meus Relatórios</a>
                <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card detail-card">
                    <div class="card-body">
                        <h3 class="text-center mb-4"><?php echo htmlspecialchars($relatorio['nome_avaliacao']); ?></h3>
                        
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
                        
                        <div class="grade-large <?php echo $gradeClass; ?>">
                            <?php echo number_format($nota, 1); ?>
                        </div>

                        <div class="stats-box">
                            <h5 class="mb-3">Desempenho</h5>
                            <div class="mb-3">
                                <label class="mb-2">Taxa de Acerto</label>
                                <?php
                                $total = $relatorio['acertos'] + $relatorio['erros'];
                                $taxa_acerto = $total > 0 ? ($relatorio['acertos'] / $total) * 100 : 0;
                                ?>
                                <div class="progress">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: <?php echo $taxa_acerto; ?>%"
                                         aria-valuenow="<?php echo $taxa_acerto; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?php echo number_format($taxa_acerto, 1); ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col">
                                    <div class="h4 text-success"><?php echo $relatorio['acertos']; ?></div>
                                    <div class="text-muted">Acertos</div>
                                </div>
                                <div class="col">
                                    <div class="h4 text-danger"><?php echo $relatorio['erros']; ?></div>
                                    <div class="text-muted">Erros</div>
                                </div>
                                <div class="col">
                                    <div class="h4"><?php echo $total; ?></div>
                                    <div class="text-muted">Total</div>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-4 info-label">Aluno</div>
                                <div class="col-md-8 info-value"><?php echo htmlspecialchars($relatorio['nome_aluno']); ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-4 info-label">Matrícula</div>
                                <div class="col-md-8 info-value"><?php echo htmlspecialchars($relatorio['matricula']); ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-4 info-label">Tipo de Avaliação</div>
                                <div class="col-md-8 info-value"><?php echo ucfirst(htmlspecialchars($relatorio['tipo'])); ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-4 info-label">Dificuldade</div>
                                <div class="col-md-8 info-value"><?php echo ucfirst(htmlspecialchars($relatorio['dificuldade'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="meus_relatorios.php" class="btn btn-secondary">Voltar para Relatórios</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 