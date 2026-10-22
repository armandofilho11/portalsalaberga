<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Questão</title>
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
            background: linear-gradient(45deg, #FF7B25, #FFA836) !important;
            color: white;
        }

        .btn-gradient-green {
            background: linear-gradient(45deg, #2e8b57, #32cd32);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gradient-green:hover {
            opacity: 0.9;
            color: white;
        }

        .btn-gradient-orange {
            background: linear-gradient(45deg, #FF7B25, #FFA836);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gradient-orange:hover {
            opacity: 0.9;
            color: white;
            box-shadow: 0 4px 8px rgba(255, 123, 37, 0.3);
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .alternativa-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .alternativa-container:hover {
            background-color: #e9ecef;
        }

        .alternativa-radio {
            margin-right: 10px;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn-action {
            padding: 10px 30px;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-light">

    <header class="header-bg py-3 px-4 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
            <h5 class="mb-0">Criar Questão</h5>
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
        <div class="card mb-4">
            <div class="card-body">
                <form action="../../control/controlecriarquestao.php" method="POST">
                    <!-- Informações Básicas -->
                    <div class="card mb-4 bg-gradient-green p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Disciplina</label>
                                <select class="form-select" name="disciplina" id="disciplina" required>
                                    <option value="">Selecione</option>
                                    <option value="lab._software">Laboratório de Software</option>
                                    <option value="lab._hardware">Laboratório de Hardware</option>
                                    <option value="Start_up_1">StartUp 1</option>
                                    <option value="Start_up_2">StartUp 2</option>
                                    <option value="Start_up_3">StartUp 3</option>
                                    <option value="banco_de_dados">Banco de Dados</option>
                                    <option value="logica">Lógica de Programação</option>
                                    <option value="gerenciador_de_conteudo">Gerenciador de Conteúdo</option>
                                    <option value="Informatica_basica">Informática Básica</option>
                                    <option value="Robotica">Robótica</option>
                                    <option value="programacao_web">Programação Web</option>
                                    <option value="Sistemas_operacionais">Sistemas Operacionais</option>
                                    <option value="redes_de_computadores">Redes de Computadores</option>
                                    <option value="htmlcss">HTML/CSS</option>
                                    <option value="design">Design</option>
                                    <option value="AMC">Arquitetura e Manutenção de Computadores</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subtópico</label>
                                <select class="form-select" name="subtopico" id="subtopico" required>
                                    <option value="">Selecione uma disciplina primeiro</option>
                                </select>
                                <div class="mt-2">
                                    <a href="gerenciar_subtopicos.php" class="text-success">
                                        <small>Gerenciar Subtópicos</small>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Autor da Questão</label>
                                <select class="form-select" name="autor" required>
                                    <option value="">Selecione</option>
                                    <option>Otavio</option>
                                    <option>Marcelo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nível de Dificuldade</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="dificuldade" value="facil" required>
                                    <label class="form-check-label">Fácil</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="dificuldade" value="medio" required>
                                    <label class="form-check-label">Médio</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="dificuldade" value="dificil" required>
                                    <label class="form-check-label">Difícil</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enunciado -->
                    <div class="card mb-4 bg-gradient-orange p-4">
                        <label class="form-label">Enunciado da Questão</label>
                        <div class="position-relative">
                            <textarea class="form-control" name="enunciado" placeholder="Digite o enunciado da questão..." required></textarea>
                        </div>
                    </div>

                    <!-- Alternativas -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Alternativas</h5>
                            <p class="text-muted mb-4">Selecione a alternativa correta:</p>

                            <div class="alternativa-container">
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="resposta_correta" value="A" class="alternativa-radio" required>
                                    <input type="text" class="form-control" placeholder="Alternativa A" name="alternativaA" required>
                                </div>
                            </div>

                            <div class="alternativa-container">
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="resposta_correta" value="B" class="alternativa-radio" required>
                                    <input type="text" class="form-control" placeholder="Alternativa B" name="alternativaB" required>
                                </div>
                            </div>

                            <div class="alternativa-container">
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="resposta_correta" value="C" class="alternativa-radio" required>
                                    <input type="text" class="form-control" placeholder="Alternativa C" name="alternativaC" required>
                                </div>
                            </div>

                            <div class="alternativa-container">
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="resposta_correta" value="D" class="alternativa-radio" required>
                                    <input type="text" class="form-control" placeholder="Alternativa D" name="alternativaD" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-end gap-3 mb-4">
                        <button type="submit" name="botao verde" value="avançar" class="btn btn-gradient-green btn-action">Avançar</button>
                        <button type="submit" name="botao cinza" value="excluir" class="btn btn-secondary btn-action">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="text-center text-muted py-3">
        <small>Desenvolvido por Letícia Barbosa, Nicole Kelly e Yudi Bezerra</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('disciplina').addEventListener('change', function() {
        const disciplina = this.value;
        const subtopicosSelect = document.getElementById('subtopico');
        
        // Clear current options
        subtopicosSelect.innerHTML = '<option value="">Carregando...</option>';
        
        if (disciplina) {
            // Fetch subtopics for selected discipline
            fetch('../../control/controlesubtopico.php?disciplina=' + encodeURIComponent(disciplina))
                .then(response => response.json())
                .then(data => {
                    subtopicosSelect.innerHTML = '<option value="">Selecione um subtópico</option>';
                    data.forEach(subtopico => {
                        const option = document.createElement('option');
                        option.value = subtopico.id;
                        option.textContent = subtopico.nome;
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
    });
    </script>
</body>
</html>
