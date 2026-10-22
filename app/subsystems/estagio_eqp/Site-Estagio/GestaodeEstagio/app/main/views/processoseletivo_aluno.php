<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processo Seletivo - Aluno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../config/img/logo_Salaberga-removebg-preview.png" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        'ceara-moss': '#2d4739',
                        primary: '#008C45',
                        secondary: '#FFA500',
                    }
                }
            }
        }
    </script>
    <style>
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }

        @media (prefers-contrast: high) {
            :root {
                --text-color: #000;
                --border-color: #000;
            }
        }

        *:focus {
            outline: 3px solid #FFA500;
            outline-offset: 2px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #f3f4f6;
        }

        .header {
            background: #2d4739;
            padding: 0.5rem 0;
        }

        .header * {
            color: #ffffff !important;
        }

        .transparent-button {
            background: none;
            transition: all 0.3s ease;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            color: #ffffff;
        }

        .transparent-button:hover {
            color: #FFA500;
            transform: translateY(-1px);
        }

        .search-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin: 0.75rem 0;
        }

        .search-input {
            background: #ffffff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            box-shadow: 0 0 0 2px rgba(255, 165, 0, 0.3);
        }

        .table-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: #f8fafc;
        }

        .action-button {
            padding: 0.4rem;
            border-radius: 0.4rem;
            transition: all 0.2s ease;
            border: none;
            background: none;
        }

        .action-button:hover {
            transform: scale(1.1);
        }

        .modal-button {
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.4rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .modal-button.cancel {
            color: #6B7280;
        }

        .modal-button.cancel:hover {
            background: #F3F4F6;
        }

        .modal-button.submit {
            background: linear-gradient(to right, #FFA500, #008C45);
            color: white;
        }

        .modal-button.submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .card {
            display: none;
        }

        @media (max-width: 768px) {
            .table-container {
                display: none;
            }

            .card {
                display: block;
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                margin-bottom: 1rem;
                padding: 1rem;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .card-content {
                padding: 0.5rem;
            }

            .card-actions {
                display: flex;
                justify-content: flex-end;
                gap: 0.5rem;
                margin-top: 0.75rem;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
        }

        .modal.flex {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-button {
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.4rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .modal-button.cancel {
            color: #6B7280;
        }

        .modal-button.cancel:hover {
            background: #F3F4F6;
        }

        .modal-button.submit {
            background: linear-gradient(to right, #FFA500, #008C45);
            color: white;
        }

        .modal-button.submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .suggestions-container {
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }

        .suggestion-item {
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .suggestion-item:hover {
            background-color: #f3f4f6;
        }

        .suggestion-item.selected {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body class="min-h-screen font-['Roboto'] select-none">
    <!-- Cabeçalho -->
    <header class="header w-full mb-4">
        <div class="container mx-auto px-4">
            <!-- Main header content -->
            <div class="flex items-center justify-between">
                <!-- Left section with back button, logo and school name -->
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" class="transparent-button">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <img src="../config/img/logo_Salaberga-removebg-preview.png" alt="Logo EEEP Salaberga" class="w-10 h-10 object-contain">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium">EEEP Salaberga</span>
                        <h1 class="text-lg font-bold">Processo Seletivo</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="container mx-auto px-4">
        <div class="search-container">
            <form action="" method="GET" class="relative" role="search">
                <label for="search" class="sr-only">Pesquisar vagas</label>
                <input type="text" 
                       id="search"
                       name="search"
                       class="search-input"
                       placeholder="Pesquisar empresa, local ou horário..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       aria-label="Pesquisar vagas">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-4">
        <!-- Table View (Desktop) -->
        <div class="table-container">
            <table class="min-w-full">
                <thead class="table-header">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Local</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Horário</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);
                    
                    try {
                        $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        
                        $sql = 'SELECT DISTINCT c.id as id_concedente, c.nome as nome_empresa,
                               s.local, s.hora,
                               (SELECT COUNT(*) FROM selecao WHERE id_concedente = c.id AND id_aluno IS NOT NULL) as total_inscritos,
                               (SELECT MIN(id) FROM selecao WHERE id_concedente = c.id) as primeiro_id
                               FROM selecao s 
                               INNER JOIN concedentes c ON s.id_concedente = c.id';
                        
                        if (!empty($search)) {
                            $sql .= ' WHERE (c.nome LIKE :search OR s.local LIKE :search)';
                        }
                        
                        $sql .= ' GROUP BY c.id, c.nome, s.local, s.hora ORDER BY c.nome';
                        
                        $query = $pdo->prepare($sql);
                        
                        if (!empty($search)) {
                            $query->bindValue(':search', '%' . $search . '%');
                        }
                        
                        $query->execute();
                        $result = $query->rowCount();

                        if ($result > 0) {
                            foreach ($query as $form) {
                                echo "<tr class='hover:bg-gray-50'>";
                                echo "<td class='px-4 py-3'>" . htmlspecialchars($form['nome_empresa']) . "</td>";
                                echo "<td class='px-4 py-3'>" . htmlspecialchars($form['local']) . "</td>";
                                echo "<td class='px-4 py-3'>" . htmlspecialchars($form['hora']) . "</td>";
                                echo "<td class='px-4 py-3 text-center'>";
                                echo "<div class='flex justify-center gap-2'>";
                                    // Botão Inscrever-se (apenas para aluno)
                                    echo "<button onclick='showInscricaoModal(" . $form['primeiro_id'] . ")' 
                                          class='text-green-600 hover:text-green-800 bg-green-50 rounded-full p-2 transition-colors' 
                                          title='Inscrever aluno no processo seletivo'
                                          aria-label='Inscrever aluno'>";
                                    echo "<i class='fas fa-user-plus'></i>";
                                    echo "</button>";
                                    
                                    // Botão Ver Inscritos
                                    echo "<button onclick='showInscritosModal(" . $form['primeiro_id'] . ")' 
                                          class='text-blue-600 hover:text-blue-800 bg-blue-50 rounded-full p-2 transition-colors' 
                                          title='Ver alunos inscritos'
                                          aria-label='Ver inscritos'>";
                                    echo "<i class='fas fa-users'></i>";
                                    echo "</button>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='px-4 py-3 text-center text-gray-500'>Nenhum processo seletivo disponível no momento.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='4' class='px-4 py-3 text-center text-red-500'>Erro ao conectar ao banco de dados: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Card View (Mobile) -->
        <div class="md:hidden">
            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                
                $sql = 'SELECT DISTINCT c.id as id_concedente, c.nome as nome_empresa,
                               s.local, s.hora,
                               (SELECT COUNT(*) FROM selecao WHERE id_concedente = c.id AND id_aluno IS NOT NULL) as total_inscritos,
                               (SELECT MIN(id) FROM selecao WHERE id_concedente = c.id) as primeiro_id
                               FROM selecao s 
                               INNER JOIN concedentes c ON s.id_concedente = c.id';
                
                if (!empty($search)) {
                    $sql .= ' WHERE (c.nome LIKE :search OR s.local LIKE :search)';
                }
                
                $sql .= ' GROUP BY c.id, c.nome, s.local, s.hora ORDER BY c.nome';
                
                $query = $pdo->prepare($sql);
                
                if (!empty($search)) {
                    $query->bindValue(':search', '%' . $search . '%');
                }
                
                $query->execute();
                $result = $query->rowCount();

                if ($result > 0) {
                    foreach ($query as $form) {
                        echo "<div class='card'>";
                        echo "<div class='card-content'>";
                        echo "<p class='text-lg font-semibold text-gray-800'>" . htmlspecialchars($form['nome_empresa']) . "</p>";
                        echo "<p class='text-sm text-gray-600'>" . htmlspecialchars($form['local']) . "</p>";
                        echo "<p class='text-sm text-gray-600'>" . htmlspecialchars($form['hora']) . "</p>";
                        echo "</div>";
                        echo "<div class='card-actions'>";
                        echo "<button onclick='showInscricaoModal(" . $form['primeiro_id'] . ")' 
                              class='text-green-600 hover:text-green-800 bg-green-50 rounded-full p-2 transition-colors' 
                              title='Inscrever aluno no processo seletivo'
                              aria-label='Inscrever aluno'>";
                        echo "<i class='fas fa-user-plus'></i>";
                        echo "</button>";
                        echo "<button onclick='showInscritosModal(" . $form['primeiro_id'] . ")' 
                              class='text-blue-600 hover:text-blue-800 bg-blue-50 rounded-full p-2 transition-colors' 
                              title='Ver alunos inscritos'
                              aria-label='Ver inscritos'>";
                        echo "<i class='fas fa-users'></i>";
                        echo "</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='card'><div class='card-content'><p class='text-center text-gray-500'>Nenhum processo seletivo disponível no momento.</p></div></div>";
                }
            } catch (PDOException $e) {
                echo "<div class='card'><div class='card-content'><p class='text-center text-red-500'>Erro ao conectar ao banco de dados: " . $e->getMessage() . "</p></div></div>";
            }
            ?>
        </div>
    </div>

    <!-- Modal de Inscritos -->
    <div id="inscritosModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden items-center justify-center z-50">
        <div class="modal-content p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto bg-white rounded-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Alunos Inscritos</h3>
                <button onclick="closeModal('inscritosModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="inscritosList" class="space-y-4">
                <!-- Lista de alunos inscritos será preenchida via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Inscrição -->
    <div id="inscricaoModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden items-center justify-center z-50">
        <div class="modal-content p-6 max-w-2xl w-full mx-4 bg-white rounded-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Nova Inscrição</h3>
                <button onclick="closeModal('inscricaoModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="inscricaoForm" class="space-y-4">
                <input type="hidden" id="modal_form_id" name="id_formulario">
                <input type="hidden" id="id_aluno" name="id_aluno">
                
                <div id="empresaDetails" class="bg-gray-50 p-4 rounded-lg mb-4">
                    <!-- Detalhes da empresa serão preenchidos via JavaScript -->
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="nome_aluno" class="block text-sm font-medium text-gray-700 mb-1">Nome do Aluno</label>
                        <div class="relative">
                            <input type="text" 
                                   id="nome_aluno" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ceara-green focus:border-transparent"
                                   placeholder="Digite o nome do aluno"
                                   autocomplete="off">
                            <div id="alunoSuggestions" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Perfis Disponíveis</label>
                        <div id="perfisContainer" class="space-y-2 bg-gray-50 p-4 rounded-lg">
                            <!-- Checkboxes dos perfis serão preenchidos via JavaScript -->
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('inscricaoModal')" class="modal-button cancel">
                        Cancelar
                    </button>
                    <button type="submit" class="modal-button submit">
                        Confirmar Inscrição
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Adicionar o contêiner para as mensagens toast no final do corpo (body) do HTML -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col-reverse space-y-2"></div>

    <script>
        // Adicionar esta função para exibir mensagens toast
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                console.error('Toast container not found!');
                alert(message); // Fallback para alert se o contêiner não for encontrado
                return;
            }

            const toast = document.createElement('div');
            toast.className = `p-4 rounded-lg shadow-lg text-white flex items-center space-x-2 opacity-0 transition-all duration-300 transform translate-y-full`;

            let bgColor = '';
            let icon = '';

            switch (type) {
                case 'success':
                    bgColor = 'bg-green-500';
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    bgColor = 'bg-red-500';
                    icon = '<i class="fas fa-exclamation-circle"></i>';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-500';
                    icon = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
                default:
                    bgColor = 'bg-blue-500';
                    icon = '<i class="fas fa-info-circle"></i>';
                    break;
            }

            toast.classList.add(bgColor);
            toast.innerHTML = `${icon}<span>${message}</span>`;
            toastContainer.appendChild(toast);

            // Animar entrada
            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-full');
                toast.classList.add('opacity-100', 'translate-y-0');
            }, 100);

            // Animar saída e remover após 5 segundos
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-full');
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            }, 5000);
        }

        function showInscritosModal(processoId) {
            const modal = document.getElementById('inscritosModal');
            const inscritosList = document.getElementById('inscritosList');
            
            // Mostrar loading
            inscritosList.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin text-2xl text-gray-500"></i></div>';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Buscar alunos inscritos neste processo específico
            fetch(`../controllers/Controller-Buscas.php?action=get_inscritos_processo&processo_id=${processoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        inscritosList.innerHTML = `<p class="text-center text-red-500">${data.error}</p>`;
                        showToast(data.error, 'error');
                        return;
                    }
                    
                    if (data.length > 0) {
                        const nomeEmpresa = data[0].nome_empresa;
                        inscritosList.innerHTML = `
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <p class="text-lg font-semibold text-gray-800">Empresa: ${nomeEmpresa}</p>
                                <p class="text-sm text-gray-600">Total de Inscritos: ${data.length}</p>
                            </div>
                            <div class="space-y-3">
                                ${data.map((aluno, index) => `
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div>
                                            <p class="font-medium text-gray-900">${aluno.nome}</p>
                                            <p class="text-sm text-gray-500">${aluno.curso}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            ${aluno.perfil ? `
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                                    ${aluno.perfil}
                                                </span>
                                            ` : ''}
                                            <span class="px-2 py-1 ${aluno.alocado ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'} rounded-full text-xs font-semibold">
                                                ${aluno.alocado ? 'Alocado' : 'Inscrito'}
                                            </span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    } else {
                        inscritosList.innerHTML = '<p class="text-center text-gray-500">Nenhum aluno inscrito nesta empresa ainda.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    inscritosList.innerHTML = '<p class="text-center text-red-500">Erro ao carregar inscritos.</p>';
                    showToast('Erro ao carregar inscritos.', 'error');
                });
        }

        // Função para mostrar o modal de inscrição
        function showInscricaoModal(id) {
            const modal = document.getElementById('inscricaoModal');
            document.getElementById('modal_form_id').value = id;
            
            // Limpar campos anteriores
            document.getElementById('nome_aluno').value = '';
            document.getElementById('id_aluno').value = '';
            document.getElementById('perfisContainer').innerHTML = '';
            
            // Buscar detalhes do processo e perfis da empresa
            fetch(`../controllers/Controller-Buscas.php?action=get_processo_details&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    const empresaDetails = document.getElementById('empresaDetails');
                    empresaDetails.innerHTML = `
                        <div class="space-y-2">
                            <h4 class="text-lg font-semibold mb-3">Detalhes do Processo</h4>
                            <p><span class="text-gray-600">Empresa:</span> <span class="font-medium">${data.nome_empresa}</span></p>
                            <p><span class="text-gray-600">Local:</span> <span class="font-medium">${data.local}</span></p>
                            <p><span class="text-gray-600">Hora:</span> <span class="font-medium">${data.hora}</span></p>
                        </div>
                    `;

                    // Preencher os perfis disponíveis
                    const perfisContainer = document.getElementById('perfisContainer');
                    perfisContainer.className = 'space-y-2 bg-gray-50 p-4 rounded-lg';

                    if (data.perfis && data.perfis.length > 0) {
                        // Limpar os perfis removendo aspas e colchetes
                        const perfisLimpos = data.perfis.map(perfil => perfil.replace(/[\[\]"]/g, '').trim());
                        perfisContainer.innerHTML = perfisLimpos.map(perfil => `
                            <label class="block">
                                <input type="checkbox" 
                                       name="perfis[]" 
                                       value="${perfil}"
                                       class="h-4 w-4 text-blue-600 border border-gray-300 align-top">
                                <span class="text-gray-700 align-top ml-1">${perfil}</span>
                            </label>
                        `).join('');
                    } else {
                        perfisContainer.innerHTML = '<p class="text-gray-500 p-2">Nenhum perfil disponível</p>';
                    }
                })
                .catch(error => {
                    console.error('Error ao carregar detalhes do processo:', error);
                    showToast('Erro ao carregar detalhes do processo.', 'error');
                });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Event listeners para o campo de busca de alunos
        const nomeAlunoInput = document.getElementById('nome_aluno');
        const idAlunoInput = document.getElementById('id_aluno');
        const alunoSuggestions = document.getElementById('alunoSuggestions');

        nomeAlunoInput.addEventListener('input', function() {
            const search = this.value.trim();
            if (search.length < 2) {
                alunoSuggestions.classList.add('hidden');
                return;
            }

            fetch(`../controllers/Controller-Buscas.php?action=get_alunos_suggestions&search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        alunoSuggestions.innerHTML = data.map(aluno => `
                            <div class="suggestion-item p-2 hover:bg-gray-100 cursor-pointer" 
                                 data-id="${aluno.id}" 
                                 data-nome="${aluno.nome.replace(/'/g, "\\'")}">
                                <div class="font-medium">${aluno.nome}</div>
                                <div class="text-sm text-gray-600">${aluno.curso} - ${aluno.matricula}</div>
                            </div>
                        `).join('');
                        alunoSuggestions.classList.remove('hidden');
                    } else {
                        alunoSuggestions.innerHTML = '<div class="p-2 text-gray-500">Nenhum aluno encontrado</div>';
                        alunoSuggestions.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alunoSuggestions.innerHTML = '<div class="p-2 text-red-500">Erro ao buscar alunos</div>';
                    alunoSuggestions.classList.remove('hidden');
                    showToast('Erro ao buscar alunos.', 'error');
                });
        });

        // Adicionar um listener de evento delegado para as sugestões de alunos
        alunoSuggestions.addEventListener('click', function(e) {
            const suggestionItem = e.target.closest('.suggestion-item');
            if (suggestionItem) {
                const id = suggestionItem.dataset.id;
                const nome = suggestionItem.dataset.nome;
                selectAluno(id, nome);
            }
        });

        // Função para selecionar um aluno
        function selectAluno(id, nome) {
            idAlunoInput.value = id;
            nomeAlunoInput.value = nome;
            alunoSuggestions.classList.add('hidden');
        }

        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!nomeAlunoInput.contains(e.target) && !alunoSuggestions.contains(e.target)) {
                alunoSuggestions.classList.add('hidden');
            }
        });

        // Form submission
        document.getElementById('inscricaoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!idAlunoInput.value) {
                showToast('Por favor, selecione um aluno da lista', 'warning');
                return;
            }

            const perfisSelecionados = document.querySelectorAll('input[name="perfis[]"]:checked');
            if (perfisSelecionados.length === 0) {
                showToast('Por favor, selecione pelo menos um perfil', 'warning');
                return;
            }

            const formData = new FormData(this);
            formData.append('perfis', JSON.stringify(Array.from(perfisSelecionados).map(cb => cb.value)));
            
            // Mostrar loading
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
            
            fetch('../controllers/controller_inscrever.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Inscrição realizada com sucesso!', 'success');
                    closeModal('inscricaoModal');
                    window.location.reload();
                } else {
                    showToast(data.message || 'Erro ao realizar inscrição', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Erro ao realizar inscrição.', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            if (modalId === 'inscricaoModal') {
                document.getElementById('inscricaoForm').reset();
                document.getElementById('id_aluno').value = '';
                document.getElementById('nome_aluno').value = '';
                document.getElementById('alunoSuggestions').innerHTML = '';
                document.getElementById('alunoSuggestions').classList.add('hidden');
                document.getElementById('perfisContainer').innerHTML = '';
            }
        }
    </script>
</body>
</html>