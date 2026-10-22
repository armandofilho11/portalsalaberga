<?php
require_once("../../model/modelprofessor.php");

$professor = new Professor();
$id_aluno = isset($_GET['aluno']) ? $_GET['aluno'] : null;
$id_avaliacao = isset($_GET['avaliacao']) ? $_GET['avaliacao'] : null;

if (!$id_aluno || !$id_avaliacao) {
    header("Location: corrigir_prova.php");
    exit();
}

$questoes = $professor->get_questoes_avaliacao($id_avaliacao);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corrigir Avaliação</title>
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
        .question-card {
            border-left: 4px solid #2e8b57;
            margin-bottom: 1.5rem;
        }
        .alternative-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }
        .alternative-item:hover {
            background-color: #f8f9fa;
        }
        .alternative-item.correct {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alternative-item.incorrect {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="header-bg py-3 px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                <h5 class="mb-0">Corrigir Avaliação</h5>
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
        <div class="card mb-4">
            <div class="card-body">
                <form id="correctionForm" action="../../control/controlecorrecao.php" method="POST">
                    <input type="hidden" name="id_aluno" value="<?php echo htmlspecialchars($id_aluno); ?>">
                    <input type="hidden" name="id_avaliacao" value="<?php echo htmlspecialchars($id_avaliacao); ?>">

                    <?php foreach ($questoes as $index => $questao): ?>
                    <div class="card question-card">
                        <div class="card-body">
                            <h5 class="card-title">Questão <?php echo $index + 1; ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($questao['enunciado']); ?></p>

                            <div class="alternatives mt-3">
                                <?php foreach ($questao['alternativas'] as $alternativa): ?>
                                <div class="alternative-item" data-questao="<?php echo $questao['id']; ?>">
                                    <div class="form-check">
                                        <input type="radio" 
                                               name="resposta[<?php echo $questao['id']; ?>]" 
                                               value="<?php echo $alternativa['resposta'] === 'sim' ? 'correta' : 'incorreta'; ?>"
                                               class="form-check-input"
                                               required>
                                        <label class="form-check-label">
                                            <?php echo htmlspecialchars($alternativa['texto']); ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="corrigir_prova.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-gradient-green">Salvar Correção</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alternatives = document.querySelectorAll('.alternative-item');
        
        alternatives.forEach(item => {
            const radio = item.querySelector('input[type="radio"]');
            
            item.addEventListener('click', function() {
                radio.checked = true;
                
                // Remove previous selections for this question
                const questionId = this.dataset.questao;
                document.querySelectorAll(`[data-questao="${questionId}"]`).forEach(alt => {
                    alt.classList.remove('correct', 'incorrect');
                });
                
                // Add appropriate class based on the answer
                if (radio.value === 'correta') {
                    this.classList.add('correct');
                } else {
                    this.classList.add('incorrect');
                }
            });
        });
    });
    </script>
</body>
</html> 