<?php
require_once("../../model/modelprofessor.php");

$professor = new Professor();
$ano = isset($_GET['ano']) ? $_GET['ano'] : null;
$alunos = $ano ? $professor->corrigir_prova($ano) : array();
$avaliacoes = $ano ? $professor->get_avaliacoes_por_ano($ano) : array();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corrigir Provas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #006400;
            color: white;
        }
        .btn-gradient-green {
            background: linear-gradient(45deg, #2e8b57, #32cd32);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient-green:hover {
            background: linear-gradient(45deg, #32cd32, #2e8b57);
            color: white;
            transform: translateY(-2px);
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="header-bg py-3 px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                <h5 class="mb-0">Corrigir Provas</h5>
            </div>
            <nav>
                <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
                <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
                <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
                <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
                <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <!-- Year Selection -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Selecione a Turma</h5>
                <div class="d-flex gap-2">
                    <a href="?ano=1b" class="btn <?php echo $ano === '1b' ? 'btn-success' : 'btn-outline-success'; ?>">1º Ano</a>
                    <a href="?ano=2b" class="btn <?php echo $ano === '2b' ? 'btn-success' : 'btn-outline-success'; ?>">2º Ano</a>
                    <a href="?ano=3b" class="btn <?php echo $ano === '3b' ? 'btn-success' : 'btn-outline-success'; ?>">3º Ano</a>
                </div>
            </div>
        </div>

        <?php if ($ano): ?>
        <!-- Students List -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Alunos do <?php echo strtoupper($ano); ?></h5>
                
                <?php if (empty($alunos)): ?>
                    <div class="alert alert-info">Nenhum aluno encontrado para esta turma.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alunos as $aluno): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($aluno['matricula']); ?></td>
                                    <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-gradient-green dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Corrigir Prova
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($avaliacoes as $avaliacao): ?>
                                                <li>
                                                    <a class="dropdown-item" href="corrigir_avaliacao.php?aluno=<?php echo $aluno['id']; ?>&avaliacao=<?php echo $avaliacao['id']; ?>">
                                                        <?php echo htmlspecialchars($avaliacao['nome']); ?>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                                
                                                <?php if (empty($avaliacoes)): ?>
                                                <li><span class="dropdown-item-text">Nenhuma avaliação disponível</span></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 