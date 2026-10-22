<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados dos Alunos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            padding: 1rem 0;
        }

        .header * {
            color: #ffffff !important;
        }

        .transparent-button {
            background: none;
            transition: all 0.3s ease;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #ffffff;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .transparent-button:hover {
            color: #FFA500;
            background: rgba(255, 165, 0, 0.1);
            border-color: rgba(255, 165, 0, 0.3);
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
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1rem;
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
            cursor: pointer;
        }

        .table-row:hover {
            background: #f8fafc;
        }

        .action-button {
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            min-height: 2.5rem;
        }

        .action-button:hover {
            transform: scale(1.05);
        }

        .card {
            display: none;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            padding: 1.25rem;
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-content {
            padding: 0;
        }

        .card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        /* HEADER MOBILE COMPLETAMENTE REDESENHADO */
        @media (max-width: 768px) {
            .header {
                padding: 0.75rem 0;
            }
            
            /* Container principal do header */
            .header-container {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }
            
            /* Primeira linha: Voltar + Logo + Info da escola */
            .header-top-row {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                width: 100%;
            }
            
            /* Botão voltar compacto */
            .back-button-mobile {
                background: rgba(255, 165, 0, 0.1);
                border: 1px solid rgba(255, 165, 0, 0.3);
                border-radius: 0.5rem;
                padding: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 2.5rem;
                min-height: 2.5rem;
                flex-shrink: 0;
                text-decoration: none;
                color: #ffffff;
            }
            
            /* Logo otimizado */
            .logo-mobile {
                width: 2.5rem;
                height: 2.5rem;
                flex-shrink: 0;
                border-radius: 0.25rem;
                background: rgba(255, 255, 255, 0.1);
                padding: 0.125rem;
            }
            
            /* Info da escola responsiva */
            .school-info-mobile {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                gap: 0.125rem;
            }
            
            .school-name-mobile {
                font-size: 0.75rem;
                font-weight: 500;
                opacity: 0.9;
                line-height: 1;
            }
            
            .page-title-mobile {
                font-size: 0.9rem;
                font-weight: 700;
                line-height: 1.1;
                margin: 0;
            }
            
            /* Segunda linha: Botões de ação */
            .header-actions-row {
                display: flex;
                gap: 0.5rem;
                width: 100%;
            }
            
            /* Botões de ação otimizados */
            .action-button-mobile {
                flex: 1;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 0.5rem;
                padding: 0.625rem 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                font-size: 0.8rem;
                font-weight: 500;
                transition: all 0.2s ease;
                text-decoration: none;
                min-height: 2.75rem;
                color: #ffffff;
            }
            
            .action-button-mobile:hover {
                background: rgba(255, 165, 0, 0.2);
                border-color: rgba(255, 165, 0, 0.4);
                transform: translateY(-1px);
            }
            
            .action-button-mobile i {
                font-size: 0.875rem;
                flex-shrink: 0;
            }
            
            .action-button-mobile span {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            /* Esconder elementos desnecessários no mobile */
            .transparent-button {
                display: none;
            }
            
            /* Mostrar versões mobile */
            .back-button-mobile,
            .action-button-mobile {
                display: flex;
            }
            
            /* Esconder tabela e mostrar cards */
            .table-container {
                display: none;
            }

            .card {
                display: block;
            }
            
            /* Melhorar cards mobile */
            .card h3 {
                font-size: 1.1rem;
                margin-bottom: 0.75rem;
            }
            
            .card .student-info {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .card .student-avatar {
                width: 3rem;
                height: 3rem;
                border-radius: 50%;
                flex-shrink: 0;
            }
            
            .card .student-details p {
                display: flex;
                align-items: center;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                color: #6b7280;
            }
            
            .card .student-details i {
                width: 1rem;
                margin-right: 0.75rem;
                text-align: center;
                color: #9ca3af;
            }
            
            .card .student-name {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0;
            }
            
            .card .student-matricula {
                font-size: 0.875rem;
                color: #6b7280;
                margin: 0;
            }
            
            .action-button {
                min-width: 2.25rem;
                min-height: 2.25rem;
                padding: 0.4rem;
            }
            
            /* Melhorar busca mobile */
            .search-input {
                font-size: 1rem;
                padding: 0.875rem 1rem 0.875rem 2.75rem;
            }
            
            /* Container mobile */
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Mobile muito pequeno (< 480px) */
        @media (max-width: 480px) {
            .header {
                padding: 0.5rem 0;
            }
            
            .header-container {
                gap: 0.5rem;
            }
            
            .header-top-row {
                gap: 0.5rem;
            }
            
            .back-button-mobile {
                min-width: 2.25rem;
                min-height: 2.25rem;
                padding: 0.375rem;
            }
            
            .logo-mobile {
                width: 2.25rem;
                height: 2.25rem;
            }
            
            .school-name-mobile {
                font-size: 0.7rem;
            }
            
            .page-title-mobile {
                font-size: 0.8rem;
            }
            
            .action-button-mobile {
                padding: 0.5rem 0.625rem;
                font-size: 0.75rem;
                min-height: 2.5rem;
            }
            
            .action-button-mobile i {
                font-size: 0.8rem;
            }
            
            /* Esconder texto em telas muito pequenas, manter só ícones */
            .action-button-mobile span {
                display: none;
            }
            
            .action-button-mobile {
                justify-content: center;
                min-width: 2.5rem;
            }
            
            .card {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
            
            .card .student-avatar {
                width: 2.5rem;
                height: 2.5rem;
            }
            
            .card .student-name {
                font-size: 1rem;
            }
        }

        /* Desktop - manter layout original */
        @media (min-width: 769px) {
            .back-button-mobile,
            .action-button-mobile {
                display: none;
            }
            
            .transparent-button {
                display: flex;
            }
            
            .card {
                display: none;
            }
            
            .table-container {
                display: block;
            }
        }

        /* Estilos para mensagens */
        .message-container {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen font-['Roboto'] select-none">
    <!-- Cabeçalho Completamente Redesenhado para Mobile -->
    <header class="header w-full mb-4">
        <div class="container mx-auto px-4">
            <!-- Layout Desktop (mantido original) -->
            <div class="hidden md:flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" class="transparent-button">
                        <i class="fas fa-arrow-left"></i> 
                        <span>Voltar</span>
                    </a>
                    <img src="../config/img/logo_Salaberga-removebg-preview.png" alt="Logo EEEP Salaberga" class="w-10 h-10 object-contain">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium">EEEP Salaberga</span>
                        <h1 class="text-lg font-bold">Dados dos Alunos</h1>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="../views/relatorios/relatorio_alunos.php" class="transparent-button">
                        <i class="fas fa-file-pdf"></i> 
                        <span>Gerar PDF</span>
                    </a>
                    <a href="../views/cadastroaluno.php" class="transparent-button">
                        <i class="fas fa-plus"></i> 
                        <span>Novo Aluno</span>
                    </a>
                </div>
            </div>

            <!-- Layout Mobile (completamente novo) -->
            <div class="md:hidden header-container">
                <!-- Primeira linha: Voltar + Logo + Info -->
                <div class="header-top-row">
                    <a href="javascript:history.back()" class="back-button-mobile" title="Voltar">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <img src="../config/img/logo_Salaberga-removebg-preview.png" 
                         alt="Logo EEEP Salaberga" 
                         class="logo-mobile object-contain">
                    <div class="school-info-mobile">
                        <span class="school-name-mobile">EEEP Salaberga</span>
                        <h1 class="page-title-mobile">Dados dos Alunos</h1>
                    </div>
                </div>

                <!-- Segunda linha: Botões de ação -->
                <div class="header-actions-row">
                    <a href="../views/relatorios/relatorio_alunos.php" class="action-button-mobile" title="Gerar Relatório PDF">
                        <i class="fas fa-file-pdf"></i>
                        <span>PDF</span>
                    </a>
                    <a href="../views/cadastroaluno.php" class="action-button-mobile" title="Cadastrar Novo Aluno">
                        <i class="fas fa-plus"></i>
                        <span>Novo</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mensagens de resultado -->
    <?php if (isset($mensagem)): ?>
        <div class="container mx-auto px-4 mb-4">
            <div class="message-container p-4 rounded-lg <?php echo $tipo === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'; ?>">
                <div class="flex items-center gap-2">
                    <i class="fas <?php echo $tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                    <span><?php echo htmlspecialchars($mensagem); ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Search Bar -->
    <div class="container mx-auto px-4">
        <div class="search-container">
            <form action="" method="GET" class="relative" role="search">
                <label for="search" class="sr-only">Pesquisar alunos</label>
                <input type="text" 
                       id="search"
                       name="search"
                       class="search-input"
                       placeholder="Pesquisar por nome, matrícula, curso..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       aria-label="Pesquisar alunos">
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
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contato</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">E-mail</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Endereço</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    if (!empty($search)) {
                        $consulta = 'SELECT * FROM aluno WHERE 
                                    nome LIKE :search OR 
                                    matricula LIKE :search OR 
                                    curso LIKE :search OR 
                                    email LIKE :search OR 
                                    contato LIKE :search OR 
                                    endereco LIKE :search';
                        $query = $pdo->prepare($consulta);
                        $query->bindValue(':search', '%' . $search . '%');
                    } else {
                        $consulta = 'SELECT * FROM aluno';
                        $query = $pdo->prepare($consulta);
                    }
                    
                    $query->execute();
                    $result = $query->rowCount();

                    if ($result > 0) {
                        foreach ($query as $value) {
                            echo "<tr class='table-row cursor-pointer hover:bg-gray-50' onclick='showStudentDetails(" . json_encode($value) . ")'>";
                            echo "<td class='px-4 py-3'>";
                            echo "<div class='flex items-center'>";
                            echo "<div class='flex-shrink-0 h-8 w-8'>";
                            echo "<img class='h-8 w-8 rounded-full' src='https://ui-avatars.com/api/?name=" . urlencode($value['nome']) . "' alt='Foto do aluno " . htmlspecialchars($value['nome']) . "'>";
                            echo "</div>";
                            echo "<div class='ml-3'>";
                            echo "<div class='text-sm font-medium text-gray-900'>" . htmlspecialchars($value['nome']) . "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</td>";
                            echo "<td class='px-4 py-3 text-sm text-gray-900'>" . htmlspecialchars($value['matricula']) . "</td>";
                            echo "<td class='px-4 py-3 text-sm text-gray-900'>" . htmlspecialchars($value['contato']) . "</td>";
                            echo "<td class='px-4 py-3 text-sm text-gray-900'>" . htmlspecialchars($value['curso']) . "</td>";
                            echo "<td class='px-4 py-3 text-sm text-gray-900'>" . htmlspecialchars($value['email']) . "</td>";
                            echo "<td class='px-4 py-3 text-sm text-gray-900'>" . htmlspecialchars($value['endereco']) . "</td>";
                            echo "<td class='px-4 py-3 text-center' onclick='event.stopPropagation();'>";
                            echo "<div class='flex justify-center gap-2'>";
                            echo "<form action='../controllers/Controller-botao_acao.php' method='GET' style='display: inline;'>";
                            echo "<input type='hidden' name='btn-editar' value='" . htmlspecialchars($value['id']) . "'>";
                            echo "<button type='submit' class='text-ceara-orange hover:text-ceara-green bg-orange-50 rounded-full p-2 transition-colors' aria-label='Editar aluno " . htmlspecialchars($value['nome']) . "'>";
                            echo "<i class='fas fa-edit'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "<form action='../controllers/Controller-Exclusoes.php' method='POST' style='display: inline;' onsubmit='return confirm(\"Tem certeza que deseja excluir este aluno?\");'>";
                            echo "<input type='hidden' name='tipo' value='aluno'>";
                            echo "<button type='submit' name='btn-excluir' value='" . htmlspecialchars($value['id']) . "' class='text-red-600 hover:text-red-800 bg-red-50 rounded-full p-2 transition-colors' title='Excluir aluno' aria-label='Excluir aluno'><i class='fas fa-trash'></i></button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='px-4 py-3 text-center text-gray-500'>Nenhum aluno cadastrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Card View (Mobile) -->
        <div class="md:hidden">
            <?php
            // Reexecutar a query para os cards mobile
            $query->execute();
            if ($result > 0) {
                foreach ($query as $value) {
                    echo "<div class='card'>";
                    echo "<div class='card-content'>";
                    
                    // Header do card com foto e info básica
                    echo "<div class='student-info'>";
                    echo "<img class='student-avatar' src='https://ui-avatars.com/api/?name=" . urlencode($value['nome']) . "' alt='Foto do aluno " . htmlspecialchars($value['nome']) . "'>";
                    echo "<div>";
                    echo "<h3 class='student-name'>" . htmlspecialchars($value['nome']) . "</h3>";
                    echo "<p class='student-matricula'>Mat: " . htmlspecialchars($value['matricula']) . "</p>";
                    echo "</div>";
                    echo "</div>";
                    
                    // Detalhes do aluno
                    echo "<div class='student-details space-y-2'>";
                    echo "<p><i class='fas fa-phone'></i>" . htmlspecialchars($value['contato']) . "</p>";
                    echo "<p><i class='fas fa-graduation-cap'></i>" . htmlspecialchars($value['curso']) . "</p>";
                    echo "<p><i class='fas fa-envelope'></i>" . htmlspecialchars($value['email']) . "</p>";
                    echo "<p><i class='fas fa-map-marker-alt'></i>" . htmlspecialchars($value['endereco']) . "</p>";
                    echo "</div>";
                    
                    echo "</div>";
                    
                    // Ações do card
                    echo "<div class='card-actions'>";
                    echo "<form action='../controllers/Controller-botao_acao.php' method='GET' style='display: inline;'>";
                    echo "<input type='hidden' name='btn-editar' value='" . htmlspecialchars($value['id']) . "'>";
                    echo "<button type='submit' class='text-ceara-orange hover:text-ceara-green bg-orange-50 rounded-full p-2 transition-colors' aria-label='Editar aluno " . htmlspecialchars($value['nome']) . "'>";
                    echo "<i class='fas fa-edit'></i>";
                    echo "</button>";
                    echo "</form>";
                    echo "<form action='../controllers/Controller-Exclusoes.php' method='POST' style='display: inline;' onsubmit='return confirm(\"Tem certeza que deseja excluir este aluno?\");'>";
                    echo "<input type='hidden' name='tipo' value='aluno'>";
                    echo "<button type='submit' name='btn-excluir' value='" . htmlspecialchars($value['id']) . "' class='text-red-600 hover:text-red-800 bg-red-50 rounded-full p-2 transition-colors' title='Excluir aluno' aria-label='Excluir aluno'><i class='fas fa-trash'></i></button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='card'>";
                echo "<div class='card-content text-center py-8'>";
                echo "<i class='fas fa-user-graduate text-4xl text-gray-300 mb-4'></i>";
                echo "<p class='text-lg text-gray-500'>Nenhum aluno cadastrado</p>";
                echo "<p class='text-sm text-gray-400'>Clique em 'Novo' para adicionar o primeiro aluno</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
        function showStudentDetails(student) {
            // Implementar modal de detalhes se necessário
            console.log('Detalhes do aluno:', student);
        }

        // Auto-hide mensagens após 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.querySelector('.message-container');
            if (messageContainer) {
                setTimeout(() => {
                    messageContainer.style.opacity = '0';
                    messageContainer.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        messageContainer.remove();
                    }, 300);
                }, 5000);
            }
        });

        // Busca em tempo real
        document.getElementById('search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Aqui você pode adicionar lógica de filtro em tempo real se desejar
            console.log('Pesquisando por:', searchTerm);
        });
    </script>
</body>
</html>