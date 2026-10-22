<?php
require_once("../../model/modelprofessor.php");

$professor = new Professor();

// Buscar todas as avaliações
$todas_avaliacoes = $professor->visualizar_avaliacoes();

// Processar o formulário quando enviado
$turma_selecionada = isset($_GET['turma']) ? $_GET['turma'] : '';
$avaliacao_selecionada = isset($_GET['avaliacao']) ? $_GET['avaliacao'] : '';

// Buscar dados do relatório
$dados_relatorio = [];
$media_geral = 0;
$total_alunos = 0;
$distribuicao_notas = [
    '0-2' => 0,
    '2-4' => 0,
    '4-6' => 0,
    '6-8' => 0,
    '8-10' => 0
];

// Array para armazenar as questões mais erradas
$questoes_erradas = [];

if ($turma_selecionada) {
    // Debug para verificar a turma selecionada
    error_log("Turma selecionada: " . $turma_selecionada);
    
    // Buscar alunos da turma específica - ajustando para corresponder exatamente à turma
    $alunos_turma = $professor->visualizar_alunos('', $turma_selecionada);
    
    // Debug para verificar quantos alunos foram encontrados
    error_log("Número de alunos encontrados: " . count($alunos_turma));

    // Buscar estatísticas das questões se houver avaliação selecionada
    if ($avaliacao_selecionada) {
        error_log("Buscando estatísticas para avaliação: " . $avaliacao_selecionada);
        $questoes_stats = $professor->get_questao_stats($avaliacao_selecionada);
        error_log("Estatísticas encontradas: " . print_r($questoes_stats, true));
        
        // Processar estatísticas
        foreach ($questoes_stats as $questao) {
            $id_questao = $questao['id'];
            $questoes_erradas[$id_questao] = [
                'enunciado' => $questao['enunciado'],
                'total_respostas' => $questao['total_respostas'],
                'total_erros' => $questao['total_erros'],
                'percentual_erro' => ($questao['total_respostas'] > 0) ? 
                    ($questao['total_erros'] / $questao['total_respostas']) * 100 : 0
            ];
        }

        // Ordenar questões por total de erros (decrescente)
        if (!empty($questoes_erradas)) {
            uasort($questoes_erradas, function($a, $b) {
                return $b['total_erros'] <=> $a['total_erros'];
            });

            // Limitar para as 5 questões mais erradas
            $questoes_erradas = array_slice($questoes_erradas, 0, 5, true);
        }
    }

    foreach ($alunos_turma as $aluno) {
        $relatorios = $professor->get_relatorios_aluno($aluno['id']);
        
        if ($avaliacao_selecionada) {
            // Filtrar por avaliação específica
            $relatorios = array_filter($relatorios, function($rel) use ($avaliacao_selecionada) {
                return $rel['id_prova'] == $avaliacao_selecionada;
            });
        }

        foreach ($relatorios as $relatorio) {
            $nota = floatval($relatorio['nota']);
            $media_geral += $nota;
            $total_alunos++;

            // Distribuição de notas
            if ($nota >= 0 && $nota < 2) $distribuicao_notas['0-2']++;
            elseif ($nota < 4) $distribuicao_notas['2-4']++;
            elseif ($nota < 6) $distribuicao_notas['4-6']++;
            elseif ($nota < 8) $distribuicao_notas['6-8']++;
            else $distribuicao_notas['8-10']++;

            $dados_relatorio[] = [
                'aluno' => $aluno['nome'],
                'matricula' => $aluno['matricula'],
                'nota' => $nota,
                'acertos' => $relatorio['acertos'],
                'erros' => $relatorio['erros'],
                'avaliacao' => $relatorio['nome_avaliacao']
            ];
        }
    }

    // Debug para verificar dados finais
    error_log("Total de registros no relatório: " . count($dados_relatorio));
}

$media_geral = $total_alunos > 0 ? $media_geral / $total_alunos : 0;

// Ordenar dados por nome do aluno
if (!empty($dados_relatorio)) {
    usort($dados_relatorio, function($a, $b) {
        return strcmp($a['aluno'], $b['aluno']);
    });
}

// Debug para verificar o estado final
error_log("Estado final - Média geral: " . $media_geral . ", Total de alunos: " . $total_alunos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Coletivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .header-bg {
            background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6fb936);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .turma-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .turma-option {
            flex: 1;
            text-align: center;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            user-select: none;
            position: relative;
            overflow: hidden;
        }
        .turma-option:hover {
            border-color: #6fb936;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .turma-option.selected {
            border-color: #6fb936;
            background: linear-gradient(135deg, #6fb936, #28a745);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .turma-option::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.4s ease-out;
            pointer-events: none;
        }
        .turma-option:active::after {
            transform: translate(-50%, -50%) scale(1);
            transition: transform 0s;
        }
        .turma-option i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #6fb936;
        }
        .turma-option.selected i {
            color: white;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #28a745;
        }
        .stat-label {
            color: #6c757d;
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
                    <h4 class="mb-0">Relatório Coletivo</h4>
                </div>
                <nav>
                    <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Filtros -->
        <div class="stats-card">
            <form method="GET" class="row g-3" id="turmaForm">
                <div class="col-12">
                    <label class="form-label">Selecione a Turma</label>
                    <div class="turma-selector">
                        <label class="turma-option <?php echo $turma_selecionada === '1b' ? 'selected' : ''; ?>" data-turma="1b">
                            <input type="radio" name="turma" value="1b" class="d-none" <?php echo $turma_selecionada === '1b' ? 'checked' : ''; ?>>
                            <i class="fas fa-users"></i>
                            <div>1º Ano B</div>
                        </label>
                        <label class="turma-option <?php echo $turma_selecionada === '2b' ? 'selected' : ''; ?>" data-turma="2b">
                            <input type="radio" name="turma" value="2b" class="d-none" <?php echo $turma_selecionada === '2b' ? 'checked' : ''; ?>>
                            <i class="fas fa-users"></i>
                            <div>2º Ano B</div>
                        </label>
                        <label class="turma-option <?php echo $turma_selecionada === '3b' ? 'selected' : ''; ?>" data-turma="3b">
                            <input type="radio" name="turma" value="3b" class="d-none" <?php echo $turma_selecionada === '3b' ? 'checked' : ''; ?>>
                            <i class="fas fa-users"></i>
                            <div>3º Ano B</div>
                        </label>
                    </div>
                </div>
                <div class="col-md-10">
                    <label for="avaliacao" class="form-label">Filtrar por Avaliação (Opcional)</label>
                    <select name="avaliacao" id="avaliacao" class="form-select">
                        <option value="">Todas as avaliações</option>
                        <?php foreach ($todas_avaliacoes as $avaliacao): ?>
                            <option value="<?php echo $avaliacao['id']; ?>"
                                    <?php echo $avaliacao_selecionada == $avaliacao['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($avaliacao['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <?php if ($turma_selecionada && !empty($dados_relatorio)): ?>
            <!-- Estatísticas Gerais -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stat-value"><?php echo number_format($media_geral, 1); ?></div>
                        <div class="stat-label">Média Geral</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stat-value"><?php echo $total_alunos; ?></div>
                        <div class="stat-label">Total de Avaliações</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stat-value">
                            <?php 
                            $aprovados = $distribuicao_notas['6-8'] + $distribuicao_notas['8-10'];
                            echo $total_alunos > 0 ? number_format(($aprovados / $total_alunos) * 100, 1) : '0';
                            ?>%
                        </div>
                        <div class="stat-label">Taxa de Aprovação</div>
                    </div>
                </div>
            </div>

            <!-- Após as estatísticas gerais e antes dos gráficos -->
            <?php if ($avaliacao_selecionada): ?>
                <!-- Questões Mais Erradas -->
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Questões Mais Erradas
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($questoes_erradas)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Questão</th>
                                            <th>Total de Respostas</th>
                                            <th>Total de Erros</th>
                                            <th>Taxa de Erro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($questoes_erradas as $id => $questao): ?>
                                        <tr>
                                            <td>
                                                <div class="text-truncate" style="max-width: 400px;" title="<?php echo htmlspecialchars($questao['enunciado']); ?>">
                                                    <?php echo htmlspecialchars($questao['enunciado']); ?>
                                                </div>
                                            </td>
                                            <td><?php echo $questao['total_respostas']; ?></td>
                                            <td><?php echo $questao['total_erros']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" 
                                                             role="progressbar" 
                                                             style="width: <?php echo $questao['percentual_erro']; ?>%"
                                                             aria-valuenow="<?php echo $questao['percentual_erro']; ?>" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span><?php echo number_format($questao['percentual_erro'], 1); ?>%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Não foram encontradas informações sobre erros nas questões desta avaliação.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Gráficos -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="text-center mb-4">Distribuição de Notas</h5>
                        <canvas id="notasChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="text-center mb-4">Aprovados vs Reprovados</h5>
                        <canvas id="aprovacaoChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabela de Resultados -->
            <div class="table-container">
                <h5 class="mb-4">Resultados Detalhados</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Matrícula</th>
                                <th>Avaliação</th>
                                <th>Nota</th>
                                <th>Acertos</th>
                                <th>Erros</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados_relatorio as $dado): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($dado['aluno']); ?></td>
                                    <td><?php echo htmlspecialchars($dado['matricula']); ?></td>
                                    <td><?php echo htmlspecialchars($dado['avaliacao']); ?></td>
                                    <td><?php echo number_format($dado['nota'], 1); ?></td>
                                    <td><?php echo $dado['acertos']; ?></td>
                                    <td><?php echo $dado['erros']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                // Gráfico de distribuição de notas
                new Chart(document.getElementById('notasChart'), {
                    type: 'bar',
                    data: {
                        labels: ['0-2', '2-4', '4-6', '6-8', '8-10'],
                        datasets: [{
                            label: 'Quantidade de Alunos',
                            data: [
                                <?php echo $distribuicao_notas['0-2']; ?>,
                                <?php echo $distribuicao_notas['2-4']; ?>,
                                <?php echo $distribuicao_notas['4-6']; ?>,
                                <?php echo $distribuicao_notas['6-8']; ?>,
                                <?php echo $distribuicao_notas['8-10']; ?>
                            ],
                            backgroundColor: [
                                '#dc3545',
                                '#fd7e14',
                                '#ffc107',
                                '#20c997',
                                '#28a745'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Gráfico de aprovação
                new Chart(document.getElementById('aprovacaoChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Aprovados (≥6.0)', 'Reprovados (<6.0)'],
                        datasets: [{
                            data: [
                                <?php echo $distribuicao_notas['6-8'] + $distribuicao_notas['8-10']; ?>,
                                <?php echo $distribuicao_notas['0-2'] + $distribuicao_notas['2-4'] + $distribuicao_notas['4-6']; ?>
                            ],
                            backgroundColor: ['#28a745', '#dc3545']
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });

                // Adicionar interatividade aos botões de turma
                document.querySelectorAll('.turma-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        // Remove a classe selected de todas as opções
                        document.querySelectorAll('.turma-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        
                        // Adiciona a classe selected na opção clicada
                        this.classList.add('selected');
                        
                        // Marca o radio button
                        const radio = this.querySelector('input[type="radio"]');
                        radio.checked = true;

                        // Se não houver avaliação selecionada, submete o formulário automaticamente
                        const avaliacaoSelect = document.getElementById('avaliacao');
                        if (!avaliacaoSelect.value) {
                            document.getElementById('turmaForm').submit();
                        }
                    });

                    // Previne a perda da seleção visual ao passar o mouse
                    option.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                    });
                });

                // Mantém a seleção visual após o carregamento da página
                window.addEventListener('load', function() {
                    const checkedRadio = document.querySelector('input[type="radio"]:checked');
                    if (checkedRadio) {
                        const turmaOption = checkedRadio.closest('.turma-option');
                        if (turmaOption) {
                            turmaOption.classList.add('selected');
                        }
                    }
                });
            </script>
        <?php elseif ($turma_selecionada): ?>
            <div class="alert alert-info">
                Nenhum dado encontrado para os filtros selecionados.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 