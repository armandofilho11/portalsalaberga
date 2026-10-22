<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Questões</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #006400;
            --secondary-green: #228B22;
            --primary-orange: #D35400;
            --secondary-orange: #E67E22;
            --correct-answer-bg: #E8F5E9;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            padding: 1.5rem;
            border-radius: 0 0 1rem 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .questao-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .questao-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .questao-header {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
            padding: 1rem;
            border-radius: 1rem 1rem 0 0;
        }

        .questao-body {
            padding: 1.5rem;
        }

        .badge-disciplina {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
        }

        .badge-dificuldade {
            background: #f8f9fa;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
        }

        .questao-enunciado {
            margin: 1rem 0;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #2c3e50;
        }

        .alternativas-list {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }

        .alternativa-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #eee;
            transition: background-color 0.2s;
        }

        .alternativa-item.correta {
            background-color: var(--correct-answer-bg);
            border-color: var(--primary-green);
        }

        .alternativa-item:hover {
            background-color: #f8f9fa;
        }

        .alternativa-item.correta:hover {
            background-color: var(--correct-answer-bg);
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-results i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

  <!-- Cabeçalho -->
  <!-- <header class="bg-green-900 p-4">
    <div class="max-w-7xl mx-auto text-white">
      <h1 class="text-lg font-bold">Seja Bem-Vindo, Professor!</h1>
      <div class="mt-2 flex flex-col md:flex-row text-sm space-y-2 md:space-y-0 md:space-x-8">
        <p>Boas vindas ao Banco de Questões STGM, onde priorizamos o melhor para relacionamento Aluno-professor e digitalização desse processo.</p>
        <p>Aqui você pode gerenciar suas questões de forma segura. Pesquise e remova questões com facilidade!</p>
      </div>
    </div>
  </header> -->

<!-- Header -->
<div class="page-header">
    <div class="container">
        <h1 class="h3 mb-2">Questões Encontradas</h1>
        <p class="mb-0">Resultados da sua pesquisa no banco de questões</p>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="container">
    <?php if (!empty($resultado)): ?>
        <?php foreach ($resultado as $questao): ?>
            <div class="questao-card card">
                <div class="questao-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Questão #<?php echo htmlspecialchars($questao['id']); ?></h5>
                        <div>
                            <span class="badge-disciplina">
                                <?php echo ucwords(str_replace('_', ' ', htmlspecialchars($questao['disciplina']))); ?>
                            </span>
                            <?php if (!empty($questao['subtopico'])): ?>
                                <span class="badge-subtopico">
                                    <?php 
                                    $subtopico = $professor->get_subtopico_by_id($questao['subtopico']);
                                    echo htmlspecialchars($subtopico['nome']); 
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="questao-body">
                    <div class="questao-enunciado">
                        <?php echo nl2br(htmlspecialchars($questao['enunciado'])); ?>
                    </div>
                    
                    <?php if (!empty($questao['alternativas'])): ?>
                        <ul class="alternativas-list">
                            <?php foreach ($questao['alternativas'] as $alternativa): ?>
                                <li class="alternativa-item <?php echo $alternativa['resposta'] === 'sim' ? 'correta' : ''; ?>">
                                    <?php echo htmlspecialchars($alternativa['texto']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="d-flex justify-content-end">
                        <span class="badge-dificuldade">
                            <?php echo ucfirst(htmlspecialchars($questao['grau_de_dificuldade'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-results">
            <i class="fas fa-search mb-3 d-block"></i>
            <h3>Nenhuma questão encontrada</h3>
            <p class="text-muted">Tente ajustar seus critérios de busca</p>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>
</html> 