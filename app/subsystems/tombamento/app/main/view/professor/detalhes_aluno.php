<?php
require_once("../../model/modelprofessor.php");

$professor = new Professor();
$id_aluno = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_aluno) {
    header("Location: relatorios_alunos.php");
    exit();
}

// Buscar informações do aluno específico usando o ID
$alunos = $professor->visualizar_alunos('', '', $id_aluno);
$aluno_info = !empty($alunos) ? $alunos[0] : null;

if (!$aluno_info) {
    header("Location: relatorios_alunos.php");
    exit();
}

$relatorios = $professor->get_relatorios_aluno($id_aluno);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6fb936);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .student-info {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .report-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .grade-badge {
            font-size: 2.5rem;
            font-weight: bold;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
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
        .progress {
            height: 1.5rem;
            border-radius: 1rem;
            margin: 1rem 0;
        }
        .progress-bar {
            transition: width 1s ease-in-out;
        }
        .stat-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="header-bg">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                    <h4 class="mb-0">Detalhes do Aluno</h4>
                </div>
                <nav>
                    <a href="relatorios_alunos.php" class="text-white mx-2 text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Student Info -->
        <div class="student-info">
            <div class="row">
                <div class="col-md-6">
                    <h3><?php echo isset($aluno_info['nome']) ? htmlspecialchars($aluno_info['nome']) : 'Nome não disponível'; ?></h3>
                    <p class="text-muted">Matrícula: <?php echo isset($aluno_info['matricula']) ? htmlspecialchars($aluno_info['matricula']) : 'Não disponível'; ?></p>
                    <p class="text-muted">Ano: <?php echo isset($aluno_info['ano']) ? htmlspecialchars($aluno_info['ano']) : 'Não disponível'; ?></p>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <?php
                        $total_avaliacoes = count($relatorios);
                        $media_geral = 0;
                        $total_acertos = 0;
                        $total_questoes = 0;

                        foreach ($relatorios as $relatorio) {
                            $media_geral += $relatorio['nota'];
                            $total_acertos += $relatorio['acertos'];
                            $total_questoes += ($relatorio['acertos'] + $relatorio['erros']);
                        }
                        $media_geral = $total_avaliacoes > 0 ? $media_geral / $total_avaliacoes : 0;
                        $taxa_acerto = $total_questoes > 0 ? ($total_acertos / $total_questoes) * 100 : 0;
                        ?>
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="stat-value"><?php echo number_format($media_geral, 1); ?></div>
                                <div class="stat-label">Média Geral</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="stat-value"><?php echo $total_avaliacoes; ?></div>
                                <div class="stat-label">Avaliações Realizadas</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: <?php echo $taxa_acerto; ?>%"
                             aria-valuenow="<?php echo $taxa_acerto; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo number_format($taxa_acerto, 1); ?>% de Acertos
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <h4 class="mb-4">Histórico de Avaliações</h4>
        <div class="row">
            <?php foreach ($relatorios as $relatorio): ?>
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
                <div class="col-md-4">
                    <div class="card report-card">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-3"><?php echo htmlspecialchars($relatorio['nome_avaliacao']); ?></h5>
                            <div class="grade-badge <?php echo $gradeClass; ?>">
                                <?php echo number_format($nota, 1); ?>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <div class="text-success h5"><?php echo $relatorio['acertos']; ?></div>
                                    <small class="text-muted">Acertos</small>
                                </div>
                                <div class="col">
                                    <div class="text-danger h5"><?php echo $relatorio['erros']; ?></div>
                                    <small class="text-muted">Erros</small>
                                </div>
                                <div class="col">
                                    <div class="h5"><?php echo ucfirst($relatorio['dificuldade']); ?></div>
                                    <small class="text-muted">Dificuldade</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 