<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zerar Status das Questões</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-gradient-green {
            background: linear-gradient(45deg, #2e8b57, #32cd32);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient-green:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Cabeçalho -->
    <header class="bg-green-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" class="w-8 mr-3">
                    <h5 class="text-white text-lg font-semibold">Banco de Questões</h5>
                </div>
                <nav class="text-white">
                    <a href="inicioprofessor.php" class="mx-3 hover:text-gray-200">Início</a>
                    <a href="verquestoes.php" class="mx-3 hover:text-gray-200">Questões</a>
                    <a href="acessarrelatorioprofessor.php" class="mx-3 hover:text-gray-200">Relatórios</a>
                    <a href="veravaliacoes.php" class="mx-3 hover:text-gray-200">Avaliações</a>
                    <a href="../../index.php" class="mx-3 hover:text-gray-200">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="p-6">
        <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-8 border-green-700">
            <h2 class="text-2xl font-bold text-green-700 mb-8">Zerar Status das Questões</h2>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">Status das questões zerado com sucesso!</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">Erro ao zerar o status das questões. Por favor, tente novamente.</span>
                </div>
            <?php endif; ?>

            <?php
            require_once("../../model/modelprofessor.php");
            $professor = new professor();
            
            // Obter valores dos filtros
            $disciplina = isset($_GET['disciplina']) ? $_GET['disciplina'] : '';
            $subtopico = isset($_GET['subtopico']) ? $_GET['subtopico'] : '';
            ?>

            <form method="GET" class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Disciplina (opcional)</label>
                            <select name="disciplina" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Todas as disciplinas</option>
                                <option value="lab._software" <?php echo $disciplina === 'lab._software' ? 'selected' : ''; ?>>Laboratório de Software</option>
                                <option value="lab._hardware" <?php echo $disciplina === 'lab._hardware' ? 'selected' : ''; ?>>Laboratório de Hardware</option>
                                <option value="Start_up_1" <?php echo $disciplina === 'Start_up_1' ? 'selected' : ''; ?>>StartUp 1</option>
                                <option value="Start_up_2" <?php echo $disciplina === 'Start_up_2' ? 'selected' : ''; ?>>StartUp 2</option>
                                <option value="Start_up_3" <?php echo $disciplina === 'Start_up_3' ? 'selected' : ''; ?>>StartUp 3</option>
                                <option value="banco_de_dados" <?php echo $disciplina === 'banco_de_dados' ? 'selected' : ''; ?>>Banco de Dados</option>
                                <option value="logica" <?php echo $disciplina === 'logica' ? 'selected' : ''; ?>>Lógica de Programação</option>
                                <option value="gerenciador_de_conteudo" <?php echo $disciplina === 'gerenciador_de_conteudo' ? 'selected' : ''; ?>>Gerenciador de Conteúdo</option>
                                <option value="Informatica_basica" <?php echo $disciplina === 'Informatica_basica' ? 'selected' : ''; ?>>Informática Básica</option>
                                <option value="Robotica" <?php echo $disciplina === 'Robotica' ? 'selected' : ''; ?>>Robótica</option>
                                <option value="programacao_web" <?php echo $disciplina === 'programacao_web' ? 'selected' : ''; ?>>Programação Web</option>
                                <option value="Sistemas_operacionais" <?php echo $disciplina === 'Sistemas_operacionais' ? 'selected' : ''; ?>>Sistemas Operacionais</option>
                                <option value="redes_de_computadores" <?php echo $disciplina === 'redes_de_computadores' ? 'selected' : ''; ?>>Redes de Computadores</option>
                                <option value="htmlcss" <?php echo $disciplina === 'htmlcss' ? 'selected' : ''; ?>>HTML/CSS</option>
                                <option value="design" <?php echo $disciplina === 'design' ? 'selected' : ''; ?>>Design</option>
                                <option value="AMC" <?php echo $disciplina === 'AMC' ? 'selected' : ''; ?>>Arquitetura e Manutenção de Computadores</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtópico (opcional)</label>
                            <select name="subtopico" id="subtopico" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Selecione uma disciplina primeiro</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full btn-gradient-green text-white py-2 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Filtrar Questões
                        </button>
                    </div>
                </div>
            </form>

            <form action="../../control/controlezerar_status.php" method="POST" class="mt-8">
                <?php
                // Buscar apenas questões com status = 1
                $questoes = $professor->acessar_banco_status($disciplina, $subtopico);
                
                if (!empty($questoes)) {
                    echo '<div class="overflow-x-auto">';
                    echo '<table class="min-w-full divide-y divide-gray-200">';
                    echo '<thead class="bg-gray-50">';
                    echo '<tr>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selecionar</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disciplina</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enunciado</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dificuldade</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody class="bg-white divide-y divide-gray-200">';
                    
                    foreach ($questoes as $questao) {
                        echo '<tr class="hover:bg-gray-50">';
                        echo '<td class="px-6 py-4 whitespace-nowrap">';
                        echo '<input type="checkbox" name="questoes[]" value="' . $questao['id'] . '" class="rounded border-gray-300 text-green-600 focus:ring-green-500">';
                        echo '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($questao['id']) . '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($questao['disciplina']);
                        if (!empty($questao['subtopico'])) {
                            $subtopico_info = $professor->get_subtopico_by_id($questao['subtopico']);
                            if ($subtopico_info) {
                                echo " - " . htmlspecialchars($subtopico_info['nome']);
                            }
                        }
                        echo '</td>';
                        echo '<td class="px-6 py-4">' . htmlspecialchars($questao['enunciado']) . '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($questao['grau_de_dificuldade']) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    
                    echo '<div class="mt-6 flex justify-end">';
                    echo '<button type="submit" name="action" value="zerar_selecionadas" class="btn-gradient-green text-white py-2 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">';
                    echo 'Zerar Status das Questões Selecionadas';
                    echo '</button>';
                    echo '</div>';
                } else {
                    echo '<div class="text-center text-gray-500 mt-8">';
                    echo 'Nenhuma questão utilizada encontrada com os filtros selecionados.';
                    echo '</div>';
                }
                ?>
            </form>

            <div class="mt-8 text-sm text-gray-600">
                <p class="mb-2"><strong>Nota:</strong> Esta ação irá:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Zerar o status das questões selecionadas</li>
                    <li>Você pode selecionar questões individualmente ou usar os filtros para encontrar questões específicas</li>
                    <li>Esta ação não pode ser desfeita</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const disciplinaSelect = document.querySelector('select[name="disciplina"]');
        const subtopicosSelect = document.getElementById('subtopico');
        const urlParams = new URLSearchParams(window.location.search);
        const selectedSubtopico = urlParams.get('subtopico');

        function loadSubtopicos(disciplina) {
            if (disciplina) {
                subtopicosSelect.innerHTML = '<option value="">Carregando...</option>';
                subtopicosSelect.disabled = true;
                
                fetch('../../control/controlesubtopico.php?disciplina=' + encodeURIComponent(disciplina))
                    .then(response => response.json())
                    .then(data => {
                        subtopicosSelect.innerHTML = '<option value="">Todos os subtópicos</option>';
                        data.forEach(subtopico => {
                            const option = document.createElement('option');
                            option.value = subtopico.id;
                            option.textContent = subtopico.nome;
                            if (selectedSubtopico === subtopico.id) {
                                option.selected = true;
                            }
                            subtopicosSelect.appendChild(option);
                        });
                        subtopicosSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        subtopicosSelect.innerHTML = '<option value="">Erro ao carregar subtópicos</option>';
                        subtopicosSelect.disabled = true;
                    });
            } else {
                subtopicosSelect.innerHTML = '<option value="">Selecione uma disciplina primeiro</option>';
                subtopicosSelect.disabled = true;
            }
        }

        disciplinaSelect.addEventListener('change', function() {
            loadSubtopicos(this.value);
        });

        // Carregar subtópicos iniciais se houver disciplina selecionada
        if (disciplinaSelect.value) {
            loadSubtopicos(disciplinaSelect.value);
        }
    });
    </script>
</body>
</html> 