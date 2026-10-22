<?php
require_once("../../model/modelprofessor.php");
$professor = new Professor();

// Get question data if ID is provided
$questao = null;
if (isset($_GET['id'])) {
    $questao = $professor->get_questao_by_id($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Questão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #006400;
            color: white;
        }
        .bg-gradient-green {
            background: linear-gradient(45deg, #2e8b57, #32cd32) !important;
            color: white;
        }
        .bg-gradient-orange {
            background: linear-gradient(45deg, #ffa500, #ff8c00) !important;
            color: white;
        }
        .btn-gradient-green {
            background: linear-gradient(45deg, #2e8b57, #32cd32);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient-orange {
            background: linear-gradient(45deg, #ffa500, #ff8c00);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <header class="header-bg py-3 px-4 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
            <h5 class="mb-0">Atualizar Questão</h5>
        </div>
        <nav class="text-white">
            <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
            <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
            <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
            <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
            <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
        </nav>
    </header>

    <div class="container">
        <!-- Search Section -->
        <?php if (!$questao): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Buscar Questão</h5>
                <form action="../../control/controleacessarbanco.php" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">ID da Questão</label>
                        <input type="number" name="id" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="action" value="buscar" class="btn btn-gradient-green">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Update Form -->
        <?php if ($questao): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Atualizar Questão #<?php echo htmlspecialchars($questao['id']); ?></h5>
                <form action="../../control/controleatualizarquestao.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($questao['id']); ?>">
                    
                    <!-- Disciplina -->
                    <div class="mb-3">
                        <label class="form-label">Disciplina</label>
                        <select class="form-select" name="disciplina" id="disciplina" required>
                            <option value="">Selecione</option>
                            <option value="lab._software" <?php echo $questao['disciplina'] == 'lab._software' ? 'selected' : ''; ?>>Laboratório de Software</option>
                            <option value="lab._hardware" <?php echo $questao['disciplina'] == 'lab._hardware' ? 'selected' : ''; ?>>Laboratório de Hardware</option>
                            <option value="Start_up_1" <?php echo $questao['disciplina'] == 'Start_up_1' ? 'selected' : ''; ?>>StartUp 1</option>
                            <option value="Start_up_2" <?php echo $questao['disciplina'] == 'Start_up_2' ? 'selected' : ''; ?>>StartUp 2</option>
                            <option value="Start_up_3" <?php echo $questao['disciplina'] == 'Start_up_3' ? 'selected' : ''; ?>>StartUp 3</option>
                            <option value="banco_de_dados" <?php echo $questao['disciplina'] == 'banco_de_dados' ? 'selected' : ''; ?>>Banco de Dados</option>
                            <option value="logica" <?php echo $questao['disciplina'] == 'logica' ? 'selected' : ''; ?>>Lógica de Programação</option>
                            <option value="gerenciador_de_conteudo" <?php echo $questao['disciplina'] == 'gerenciador_de_conteudo' ? 'selected' : ''; ?>>Gerenciador de Conteúdo</option>
                            <option value="Informatica_basica" <?php echo $questao['disciplina'] == 'Informatica_basica' ? 'selected' : ''; ?>>Informática Básica</option>
                            <option value="Robotica" <?php echo $questao['disciplina'] == 'Robotica' ? 'selected' : ''; ?>>Robótica</option>
                            <option value="programacao_web" <?php echo $questao['disciplina'] == 'programacao_web' ? 'selected' : ''; ?>>Programação Web</option>
                            <option value="Sistemas_operacionais" <?php echo $questao['disciplina'] == 'Sistemas_operacionais' ? 'selected' : ''; ?>>Sistemas Operacionais</option>
                            <option value="redes_de_computadores" <?php echo $questao['disciplina'] == 'redes_de_computadores' ? 'selected' : ''; ?>>Redes de Computadores</option>
                            <option value="htmlcss" <?php echo $questao['disciplina'] == 'htmlcss' ? 'selected' : ''; ?>>HTML/CSS</option>
                            <option value="design" <?php echo $questao['disciplina'] == 'design' ? 'selected' : ''; ?>>Design</option>
                            <option value="AMC" <?php echo $questao['disciplina'] == 'AMC' ? 'selected' : ''; ?>>Arquitetura e Manutenção de Computadores</option>
                        </select>
                    </div>

                    <!-- Subtópico -->
                    <div class="mb-3">
                        <label class="form-label">Subtópico</label>
                        <select class="form-select" name="subtopico" id="subtopico" required>
                            <option value="">Selecione uma disciplina primeiro</option>
                        </select>
                    </div>

                    <!-- Dificuldade -->
                    <div class="mb-3">
                        <label class="form-label">Nível de Dificuldade</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="dificuldade" value="facil" <?php echo $questao['grau_de_dificuldade'] == 'facil' ? 'checked' : ''; ?> required>
                                <label class="form-check-label">Fácil</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="dificuldade" value="medio" <?php echo $questao['grau_de_dificuldade'] == 'medio' ? 'checked' : ''; ?> required>
                                <label class="form-check-label">Médio</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="dificuldade" value="dificil" <?php echo $questao['grau_de_dificuldade'] == 'dificil' ? 'checked' : ''; ?> required>
                                <label class="form-check-label">Difícil</label>
                            </div>
                        </div>
                    </div>

                    <!-- Enunciado -->
                    <div class="mb-3">
                        <label class="form-label">Enunciado da Questão</label>
                        <textarea class="form-control" name="enunciado" rows="4" required><?php echo htmlspecialchars($questao['enunciado']); ?></textarea>
                    </div>

                    <!-- Alternativas -->
                    <div class="mb-3">
                        <h6 class="mb-3">Alternativas</h6>
                        <div class="mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input type="radio" name="resposta_correta" value="A" <?php echo $questao['resposta_correta'] == 'A' ? 'checked' : ''; ?> required>
                                </div>
                                <input type="text" class="form-control" name="alternativaA" value="<?php echo htmlspecialchars($questao['alternativaA']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input type="radio" name="resposta_correta" value="B" <?php echo $questao['resposta_correta'] == 'B' ? 'checked' : ''; ?> required>
                                </div>
                                <input type="text" class="form-control" name="alternativaB" value="<?php echo htmlspecialchars($questao['alternativaB']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input type="radio" name="resposta_correta" value="C" <?php echo $questao['resposta_correta'] == 'C' ? 'checked' : ''; ?> required>
                                </div>
                                <input type="text" class="form-control" name="alternativaC" value="<?php echo htmlspecialchars($questao['alternativaC']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input type="radio" name="resposta_correta" value="D" <?php echo $questao['resposta_correta'] == 'D' ? 'checked' : ''; ?> required>
                                </div>
                                <input type="text" class="form-control" name="alternativaD" value="<?php echo htmlspecialchars($questao['alternativaD']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="acessar_banco.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-gradient-green">Atualizar Questão</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const disciplinaSelect = document.getElementById('disciplina');
        const subtopicosSelect = document.getElementById('subtopico');
        const currentSubtopico = '<?php echo isset($questao["subtopico"]) ? $questao["subtopico"] : ""; ?>';

        function loadSubtopicos(disciplina) {
            if (disciplina) {
                fetch('../../control/controlesubtopico.php?disciplina=' + encodeURIComponent(disciplina))
                    .then(response => response.json())
                    .then(data => {
                        subtopicosSelect.innerHTML = '<option value="">Selecione um subtópico</option>';
                        data.forEach(subtopico => {
                            const option = document.createElement('option');
                            option.value = subtopico.id;
                            option.textContent = subtopico.nome;
                            if (subtopico.id == currentSubtopico) {
                                option.selected = true;
                            }
                            subtopicosSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        subtopicosSelect.innerHTML = '<option value="">Erro ao carregar subtópicos</option>';
                    });
            } else {
                subtopicosSelect.innerHTML = '<option value="">Selecione uma disciplina primeiro</option>';
            }
        }

        disciplinaSelect.addEventListener('change', function() {
            loadSubtopicos(this.value);
        });

        // Load subtopics for initial discipline if one is selected
        if (disciplinaSelect.value) {
            loadSubtopicos(disciplinaSelect.value);
        }
    });
    </script>
</body>
</html> 