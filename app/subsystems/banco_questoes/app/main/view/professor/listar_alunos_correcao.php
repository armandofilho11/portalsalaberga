<?php
if (!isset($alunos) || !isset($ano_selecionado)) {
    header('Location: ../professor/corrigir_prova.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correção de Provas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #006400;
            color: white;
        }
        .student-card {
            transition: all 0.3s ease;
        }
        .student-card:hover {
            background-color: #f8f9fa;
        }
        .grade-input {
            max-width: 80px;
        }
        .status-badge {
            font-size: 0.9em;
            padding: 5px 10px;
        }
    </style>
</head>
<body class="bg-light">
    <header class="header-bg py-3 px-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
            <h5 class="mb-0">Correção de Provas</h5>
        </div>
        <nav class="text-white">
            <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
            <a href="verquestoes.php" class="text-white mx-2 text-decoration-none">Questões</a>
            <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
            <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
            <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
        </nav>
    </header>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Alunos - <?php echo htmlspecialchars($ano_selecionado); ?>º B</h5>
                <a href="../professor/corrigir_prova.php" class="btn btn-outline-light btn-sm">Voltar para Seleção de Turma</a>
            </div>
            <div class="card-body">
                <?php if (empty($alunos)): ?>
                    <div class="alert alert-info">
                        Nenhum aluno encontrado para esta turma.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nome do Aluno</th>
                                    <th>Avaliação</th>
                                    <th>Data de Envio</th>
                                    <th>Status</th>
                                    <th>Nota</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alunos as $aluno): ?>
                                    <tr class="student-card">
                                        <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                        <td>
                                            <?php echo isset($aluno['nome_avaliacao']) ? 
                                                htmlspecialchars($aluno['nome_avaliacao']) : 
                                                'Nenhuma avaliação disponível'; ?>
                                        </td>
                                        <td>
                                            <?php echo isset($aluno['data_envio']) ? 
                                                date('d/m/Y', strtotime($aluno['data_envio'])) : 
                                                '-'; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!isset($aluno['nome_avaliacao'])) {
                                                echo '<span class="badge bg-secondary status-badge">Sem avaliação</span>';
                                            } elseif (!isset($aluno['nota'])) {
                                                echo '<span class="badge bg-warning status-badge">Pendente</span>';
                                            } else {
                                                echo '<span class="badge bg-success status-badge">Corrigido</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (isset($aluno['nome_avaliacao'])): ?>
                                                <input type="number" 
                                                       class="form-control grade-input" 
                                                       min="0" 
                                                       max="10" 
                                                       step="0.1"
                                                       value="<?php echo isset($aluno['nota']) ? htmlspecialchars($aluno['nota']) : ''; ?>"
                                                       data-aluno-id="<?php echo $aluno['id']; ?>"
                                                       data-avaliacao-id="<?php echo $aluno['id_avaliacao']; ?>">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($aluno['nome_avaliacao'])): ?>
                                                <button class="btn btn-sm btn-primary ver-prova" 
                                                        data-aluno-id="<?php echo $aluno['id']; ?>"
                                                        data-avaliacao-id="<?php echo $aluno['id_avaliacao']; ?>">
                                                    Ver Prova
                                                </button>
                                                <button class="btn btn-sm btn-success salvar-nota"
                                                        data-aluno-id="<?php echo $aluno['id']; ?>"
                                                        data-avaliacao-id="<?php echo $aluno['id_avaliacao']; ?>">
                                                    Salvar Nota
                                                </button>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manipuladores para os botões de salvar nota
        const salvarNotaBtns = document.querySelectorAll('.salvar-nota');
        salvarNotaBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const alunoId = this.dataset.alunoId;
                const avaliacaoId = this.dataset.avaliacaoId;
                const notaInput = document.querySelector(`input[data-aluno-id="${alunoId}"][data-avaliacao-id="${avaliacaoId}"]`);
                
                if (notaInput) {
                    const nota = notaInput.value;
                    if (nota === '' || isNaN(nota) || nota < 0 || nota > 10) {
                        alert('Por favor, insira uma nota válida entre 0 e 10.');
                        return;
                    }

                    // Aqui você pode implementar a chamada AJAX para salvar a nota
                    alert('Funcionalidade de salvar nota será implementada em breve!');
                }
            });
        });

        // Manipuladores para os botões de ver prova
        const verProvaBtns = document.querySelectorAll('.ver-prova');
        verProvaBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const alunoId = this.dataset.alunoId;
                const avaliacaoId = this.dataset.avaliacaoId;
                
                // Aqui você pode implementar a lógica para visualizar a prova
                alert('Funcionalidade de visualizar prova será implementada em breve!');
            });
        });
    });
    </script>
</body>
</html> 