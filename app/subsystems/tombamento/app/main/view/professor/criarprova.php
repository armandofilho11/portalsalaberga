<?php
require("../../model/modelprofessor.php");

// Obter os parâmetros de filtro
$filtro_disciplina = isset($_GET['disciplina']) ? $_GET['disciplina'] : '';
$filtro_enunciado = isset($_GET['enunciado']) ? $_GET['enunciado'] : '';
$filtro_subtopico = isset($_GET['subtopico']) ? $_GET['subtopico'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Prova</title>
    <!-- Bootstrap CDN -->
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

        .btn-gradient-green:hover {
            opacity: 0.9;
            color: white;
        }

        .btn-gradient-orange {
            background: linear-gradient(45deg, #ffa500, #ff8c00);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gradient-orange:hover {
            opacity: 0.9;
            color: white;
        }

        .form-label {
            font-weight: bold;
        }

        .table thead th {
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
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
        <h5 class="mb-0">Criar Prova</h5>
    </div>
    <nav>
        <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
        <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
        <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
        <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
        <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
    </nav>
</header>

<div class="container">
    <!-- Dados da Prova -->
    <form action="../../control/controlecriarprova.php" method="POST">
        <div class="card p-4 mb-4 bg-gradient-green">
            <div class="mb-3">
                <label for="nome_prova" class="form-label">Nome da Prova</label>
                <input type="text" class="form-control" name="nome_prova" id="nome_prova" required>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Tipo da Prova</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_prova" id="parcial" value="parcial" required>
                    <label class="form-check-label" for="parcial">Parcial</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_prova" id="bimestral" value="bimestral">
                    <label class="form-check-label" for="bimestral">Bimestral</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_prova" id="somativa" value="somativa">
                    <label class="form-check-label" for="somativa">Somativa</label>
                </div>
            </div>
        
            <div class="mb-3">
                <label class="form-label d-block">Dificuldade</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dificuldade" id="dificuldade_fácil" value="facil" required>
                    <label class="form-check-label" for="dificuldade_fácil">Fácil</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dificuldade" id="dificuldade_médio" value="medio">
                    <label class="form-check-label" for="dificuldade_médio">Médio</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dificuldade" id="dificuldade_dificil" value="dificil">
                    <label class="form-check-label" for="dificuldade_dificil">Difícil</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Turma</label>
                <select name="turma" id="turma" class="form-select" required>
                    <option value="3">3B</option>
                    <option value="2">2B</option>
                    <option value="1">1B</option>
                </select>
            </div>
        </div>

        <!-- Seletor de Questões Aleatórias -->
        <div class="card p-4 mb-4 bg-gradient-orange">
            <label for="quantidade_questoes" class="form-label">Quantidade de Questões Aleatórias</label>
            <div class="input-group">
                <input type="number" class="form-control" id="quantidade_questoes" min="1" max="100" >
                <button type="button" class="btn btn-gradient-green" onclick="selecionarAleatorio()">Selecionar Aleatoriamente</button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card p-4 mb-4">
            <h5 class="card-title mb-4">Filtrar Questões</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="filtro_disciplina" class="form-label">Disciplina</label>
                    <select class="form-select" id="filtro_disciplina" name="filtro_disciplina">
                    <option value="">Selecione</option>
                        <option value='lab._software'>Laboratório de Software</option>
                        <option value='lab._hardware'>Laboratório de Hardware</option>
                        <option value='Start_up_1'>StartUp 1</option>
                        <option value='Start_up_2'>StartUp 2</option>
                        <option value='Start_up_3'>StartUp 3</option>
                        <option value='banco_de_dados'>Banco de Dados</option>
                        <option value='logica'>Lógica de Programação</option>
                        <option value='gerenciador_de_conteudo'>Gerenciador de Conteúdo</option>
                        <option value='Informatica_basica'>Informática Básica</option>
                        <option value='Robotica'>Robótica</option>
                        <option value='programacao_web'>Programação Web</option>
                        <option value='Sistemas_operacionais'>Sistemas Operacionais</option>
                        <option value='redes_de_computadores'>Redes de Computadores</option>
                        <option value='htmlcss'>HTML/CSS</option>
                        <option value='design'>Design</option>
                        <option value='AMC'>Arquitetura e Manutenção de Computadores</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="filtro_enunciado" class="form-label">Enunciado (Palavra-chave)</label>
                    <input type="text" class="form-control" id="filtro_enunciado" name="filtro_enunciado" placeholder="Digite uma palavra-chave">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="filtro_subtopico" class="form-label">Subtópico</label>
                    <select class="form-select" id="filtro_subtopico" name="filtro_subtopico">
                        <option value="">Selecione uma disciplina primeiro</option>
                    </select>
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-gradient-green" onclick="aplicarFiltros()">Aplicar Filtros</button>
            </div>
        </div>

        <!-- Tabela de Questões -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tabela_questoes">
                        <thead class="bg-gradient-green">
                            <tr>
                                <th>Selecionar</th>
                                <th>ID</th>
                                <th>Disciplina</th>
                                <th>Enunciado</th>
                                <th>Dificuldade</th>
                                <th>Professor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $x = new professor();
                            $resultado = $x->acessar_banco($filtro_enunciado, $filtro_disciplina, '', '', $filtro_subtopico);

                            if (!empty($resultado)) {
                                foreach ($resultado as $linha) {
                                    $subtopico_info = '';
                                    if (!empty($linha['subtopico'])) {
                                        $subtopico = $x->get_subtopico_by_id($linha['subtopico']);
                                        $subtopico_info = $subtopico ? " - " . htmlspecialchars($subtopico['nome']) : '';
                                    }
                                    
                                    $status = isset($linha['status']) && $linha['status'] == 1;
                                    $disabled = $status ? 'disabled' : '';
                                    $statusClass = $status ? 'bg-yellow-100' : '';
                                    
                                    echo "<tr class='{$statusClass}'>";
                                    echo "<td class='text-center'>";
                                    if ($status) {
                                        echo "<div class='tooltip' title='Esta questão já foi utilizada em uma prova. Zere seu status para utilizá-la novamente.'>";
                                        echo "<input type='checkbox' name='questoes_selecionadas[]' value='{$linha['id']}' disabled>";
                                        echo "</div>";
                                    } else {
                                        echo "<input type='checkbox' name='questoes_selecionadas[]' value='{$linha['id']}'>";
                                    }
                                    echo "</td>";
                                    echo "<td>{$linha['id']}</td>";
                                    echo "<td>" . htmlspecialchars($linha['disciplina']) . $subtopico_info . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['enunciado']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['grau_de_dificuldade']) . "</td>";
                                    echo "<td>{$linha['id_professor']}</td>";
                                    echo "<td class='text-center'>";
                                    if ($status) {
                                        echo "<span class='badge bg-warning text-dark'>Utilizada</span>";
                                    } else {
                                        echo "<span class='badge bg-success'>Disponível</span>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Nenhuma questão encontrada.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botão Criar Prova -->
        <div class="text-end mt-4 mb-4">
            <button type="submit" class="btn btn-gradient-orange btn-lg px-5">Criar Prova</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const disciplinaSelect = document.getElementById('filtro_disciplina');
        const subtopicosSelect = document.getElementById('filtro_subtopico');

        disciplinaSelect.addEventListener('change', function() {
            const disciplina = this.value;
            subtopicosSelect.innerHTML = '<option value="">Carregando...</option>';
            subtopicosSelect.disabled = !disciplina;
            
            if (disciplina) {
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
    });

    function selecionarAleatorio() {
        const quantidade = parseInt(document.getElementById('quantidade_questoes').value);
        const checkboxes = document.querySelectorAll('#tabela_questoes tbody input[type="checkbox"]');
        const totalQuestoes = checkboxes.length;

        // Desmarcar todas as questões antes de selecionar aleatoriamente
        checkboxes.forEach(checkbox => checkbox.checked = false);

        // Selecionar aleatoriamente as questões
        const indicesSelecionados = new Set();
        while (indicesSelecionados.size < quantidade && indicesSelecionados.size < totalQuestoes) {
            const indiceAleatorio = Math.floor(Math.random() * totalQuestoes);
            indicesSelecionados.add(indiceAleatorio);
        }

        // Marcar os checkboxes selecionados
        indicesSelecionados.forEach(indice => {
            checkboxes[indice].checked = true;
        });
    }

    function aplicarFiltros() {
        const disciplina = document.getElementById('filtro_disciplina').value;
        const enunciado = document.getElementById('filtro_enunciado').value;
        const subtopico = document.getElementById('filtro_subtopico').value;
        
        // Criar URL com os parâmetros de filtro
        const params = new URLSearchParams();
        if (disciplina) params.append('disciplina', disciplina);
        if (enunciado) params.append('enunciado', enunciado);
        if (subtopico) params.append('subtopico', subtopico);
        
        // Recarregar a página com os filtros
        window.location.href = 'criarprova.php?' + params.toString();
    }

    // Manter os valores dos filtros após recarregar a página
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        const disciplina = urlParams.get('disciplina');
        if (disciplina) {
            document.getElementById('filtro_disciplina').value = disciplina;
        }
        
        const enunciado = urlParams.get('enunciado');
        if (enunciado) {
            document.getElementById('filtro_enunciado').value = enunciado;
        }
    }
</script>

</body>
</html>
