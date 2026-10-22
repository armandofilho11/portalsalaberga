<?php
session_start();

// Check if user is logged in and is an administrator
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
    header("Location: ../view/login.php?error=Por favor, faça login como administrador para acessar o portal");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEEP STGM - Gerenciar Cardápio</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/Design-logo.svg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1f5632',
                        'secondary': '#f46815',
                        'neutral': '#f5f5f5',
                        'accent': '#333333',
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary-color: #1f5632;
            --secondary-color: #f46815;
            --bg-color: #f5f5f5;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --dark-green: #0f2a1d;
            --medium-dark-green: #1a3c2e;
            font-size: 16px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s, color 0.3s;
            font-size: 1rem;
        }
        body.dark {
            --bg-color: #1a1a1a;
            --accent: #d1d5db;
            background: var(--bg-color);
            color: var(--accent);
        }
        .header {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
            color: white;
            width: 100%;
            box-shadow: 0 2px 4px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .main-content {
            margin-top: 1rem;
            padding: 2rem 1rem;
            width: 100%;
            max-width: 1200px;
            flex: 1;
            margin-left: auto;
            margin-right: auto;
        }
        body.dark .main-content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .message {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
        }
        .message.success {
            background: #E8F5E9;
            color: #66BB6A;
        }
        body.dark .message.success {
            background: #2a3e2b;
            color: #A5D6A7;
        }
        .message.error {
            background: #FFEBEE;
            color: #EF5350;
        }
        body.dark .message.error {
            background: #4a2a2a;
            color: #FF8A80;
        }
        .nav-tabs {
            display: flex;
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: 2rem;
        }
        .nav-tab {
            flex: 1;
            text-align: center;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent);
            background-color: #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px 8px 0 0;
        }
        body.dark .nav-tab {
            background-color: #4a4a4a;
            color: #d1d5db;
        }
        .nav-tab.active {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
        }
        .nav-tab:hover {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .menu-items {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: nowrap;
            flex-shrink: 0;
        }
        .menu-items a {
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: color 0.3s ease;
            white-space: nowrap;
        }
        .menu-items a:hover {
            color: var(--secondary-color);
        }
        body.dark .form-group label {
            color: #d1d5db;
        }
        .form-group select,
        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background-color: white;
        }
        body.dark .form-group select,
        body.dark .form-group input[type="text"],
        body.dark .form-group input[type="date"] {
            background-color: #333;
            color: #d1d5db;
            border-color: #555;
        }
        .form-group select:focus,
        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        .action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-submit {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
        }
        .btn-submit:hover {
            background: linear-gradient(45deg, #1a3c2e, #2a6d43);
        }
        .btn-cancel {
            background: #EF5350;
            color: white;
        }
        .btn-cancel:hover {
            background: #FF8A80;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px var(--shadow-color);
        }
        body.dark table {
            background-color: #2d2d2d;
        }
        th, td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #ddd;
        }
        body.dark th, body.dark td {
            border-color: #555;
        }
        th {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
            font-weight: 600;
        }
        body.dark th {
            background: linear-gradient(45deg, #1a3c2e, #2a6d43);
        }
        #selectAll {
            width: 16px;
            height: 16px;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 3px;
            cursor: pointer;
            vertical-align: middle;
        }
        body.dark #selectAll {
            background-color: #333;
            border-color: #555;
        }
        #selectAll:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        body.dark tr:nth-child(even) {
            background-color: #3a3a3a;
        }
        .action-buttons button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 0.25rem;
            font-weight: 600;
        }
        .action-buttons .edit {
            background-color: #007bff;
            color: white;
        }
        .action-buttons .edit:hover {
            background-color: #0056b3;
        }
        .action-buttons .delete {
            background-color: #dc3545;
            color: white;
        }
        .action-buttons .delete:hover {
            background-color: #c82333;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px var(--shadow-color);
            position: relative;
            border: 1px solid #A5D6A7;
        }
        body.dark .modal-content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .modal-content h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-align: center;
        }
        body.dark .modal-content h2 {
            color: #d1d5db;
        }
        .close-modal {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            font-size: 1.2rem;
            color: var(--accent);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        body.dark .close-modal {
            color: #d1d5db;
        }
        .close-modal:hover {
            color: #EF5350;
        }
        .message-modal .modal-content {
            max-width: 300px;
            text-align: center;
        }
        .message-modal .modal-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .message-modal .icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .message-modal .success .icon {
            color: var(--primary-color);
        }
        .message-modal .error .icon {
            color: #dc3545;
        }
        .message-modal h3 {
            margin: 0.5rem 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent);
        }
        body.dark .message-modal h3 {
            color: #d1d5db;
        }
        .message-modal p {
            color: #666;
            margin-bottom: 1rem;
        }
        body.dark .message-modal p {
            color: #d1d5db;
        }
        .accessibility-controls {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        @media (max-width: 1024px) {
            .main-content {
                padding: 1.5rem;
            }
            .nav-tab {
                font-size: 1rem;
                padding: 0.75rem;
            }
            .form-group select,
            .form-group input {
                font-size: 0.95rem;
                padding: 0.65rem;
            }
            th, td {
                font-size: 0.9rem;
                padding: 0.75rem;
            }
            .modal-content {
                width: 95%;
                padding: 1.5rem;
            }
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            .nav-tab {
                font-size: 0.9rem;
                padding: 0.5rem;
            }
            .form-group select,
            .form-group input {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
            th, td {
                font-size: 0.8rem;
                padding: 0.5rem;
                word-wrap: break-word;
            }
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .action-buttons button {
                width: 100%;
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            .modal-content {
                width: 90%;
                padding: 1rem;
                max-height: 80vh;
                overflow-y: auto;
            }
            .modal-content h2 {
                font-size: 1.1rem;
            }
            .accessibility-controls {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
        @media (max-width: 480px) {
            .nav-tabs {
                flex-direction: column;
            }
            .nav-tab {
                width: 100%;
                border-radius: 8px;
                margin-bottom: 0.5rem;
            }
            .form-group {
                margin-bottom: 1rem;
            }
            .form-group label {
                font-size: 0.85rem;
            }
            th, td {
                font-size: 0.75rem;
                padding: 0.4rem;
            }
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            .modal-content {
                width: 95%;
                padding: 0.75rem;
            }
            .message-modal .modal-content {
                max-width: 280px;
            }
            .message-modal .icon {
                font-size: 2.5rem;
            }
            .message-modal h3 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body class="font-sans bg-neutral" x-data="{ mobileMenuOpen: false }">
    <header class="header text-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4">
            <nav class="flex items-center justify-between flex-wrap py-4">
                <div class="accessibility-controls flex items-center space-x-2">
                    <span class="text-sm"><b>Acessibilidade</b></span>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Diminuir tamanho do texto" onclick="changeTextSize(-2)">
                        <i class="fas fa-a"></i><b>-</b>
                    </button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Tamanho padrão do texto" onclick="changeTextSize(0)">
                        <i class="fas fa-a"></i>
                    </button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Aumentar tamanho do texto" onclick="changeTextSize(2)">
                        <i class="fas fa-a"></i><b>+</b>
                    </button>
                    <button class="hover:text-secondary transition duration-300 px-1" aria-label="Alternar modo escuro" @click="toggleDarkMode()">
                        <i class="fas fa-circle-half-stroke"></i>
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="hidden lg:flex space-x-4 menu-items">
                        <a href="usuarios.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fas fa-users mr-2"></i> Gerenciamento de usuários
                        </a>
                        <a href="relatorios.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-edit mr-1"></i> Relatórios de satisfação
                        </a>
                        <a href="sugestoes.php">
                            <i class="fa-solid fa-comment mr-1"></i> Sugestões
                        </a>
                        <a href="sistemaAdministrador.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fas fa-home mr-1"></i> Início
                        </a>
                    </div>
                    <div class="block lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="flex items-center px-3 py-2 border rounded text-secondary border-secondary hover:text-white hover:border-white transition duration-300">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>
            <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="lg:hidden">
                <div class="py-2 space-y-2">
                    <a href="sistemaAdministrador.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-house mr-1"></i> Início
                    </a>
                    <a href="usuarios.php" class="block text-white hover:text-secondary transition duration-300" onclick="activateTab('cadastrarCardapio')">
                        <i class="fas fa-users mr-1"></i> Gerenciamento de usuários
                    </a>
                    <a href="relatorios.php" class="block text-white hover:text-secondary transition duration-300" onclick="activateTab('alterarCardapio')">
                        <i class="fa-solid fa-edit mr-1"></i> Relatórios de satisfação
                    </a>
                    <a href="sugestoes.php">
                            <i class="fa-solid fa-comment mr-1"></i> Sugestões
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="nav-tabs">
            <div class="nav-tab active" data-tab="cadastrarCardapio">Cadastrar Cardápio</div>
            <div class="nav-tab" data-tab="alterarCardapio">Alterar Cardápio</div>
        </div>

        <!-- Cadastrar Cardápio -->
        <div class="tab-content active" id="cadastrarCardapio">
            <form id="cadastrarCardapioForm" method="POST">
                <div class="form-group">
                    <label for="tipoCardapio">Tipo</label>
                    <select name="tipo" id="tipoCardapio" class="border rounded-lg p-2 w-full">
                        <option value="" selected>Selecione uma opção</option>
                        <option value="lanche-manha">Lanche da manhã</option>
                        <option value="almoco">Almoço</option>
                        <option value="lanche-tarde">Lanche da tarde</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dataCardapio">Data</label>
                    <input type="date" name="data" id="dataCardapio" class="border rounded-lg p-2 w-full" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="nomeCardapio">Nome</label>
                    <input type="text" name="nome" id="nomeCardapio" class="border rounded-lg p-2 w-full">
                </div>
                <div class="form-group">
                    <label for="descricaoCardapio">Descrição</label>
                    <input type="text" name="descricao" id="descricaoCardapio" class="border rounded-lg p-2 w-full">
                </div>
                <div class="flex justify-end gap-4">
                    <button type="submit" class="action-btn btn-submit">Cadastrar</button>
                    <button type="reset" class="action-btn btn-cancel" onclick="resetForm('cadastrarCardapioForm')">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Alterar Cardápio -->
        <div class="tab-content" id="alterarCardapio">
            <div class="flex justify-between items-center mb-4">
                <button class="action-btn btn-submit" onclick="generateReport()">Gerar Relatório</button>
                <button id="bulkDeleteBtn" class="action-btn btn-cancel hidden" onclick="bulkDelete()">Excluir Selecionados</button>
            </div>
            <?php
            require_once("../model/Cardapio.class.php");
            $cardapioView = new CardapioView();
            $cardapios = $cardapioView->exibirCardapio();
            ?>

            <table id="cardapioTable">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
                <?php
                foreach (['lanche-manha', 'almoco', 'lanche-tarde'] as $tipo) {
                    if (!empty($cardapios[$tipo])) {
                        foreach ($cardapios[$tipo] as $data => $entries) {
                            foreach ($entries as $cardapio) {
                                echo "<tr data-id='" . $cardapio['id'] . "'>";
                                echo "<td><input type='checkbox' class='cardapio-checkbox' data-id='" . $cardapio['id'] . "'></td>";
                                echo "<td>" . date('d/m/Y', strtotime($cardapio['data'])) . "</td>";
                                echo "<td>" . ucfirst(str_replace('-', ' ', $cardapio['tipo'])) . "</td>";
                                echo "<td>" . htmlspecialchars($cardapio['nome']) . "</td>";
                                echo "<td>" . htmlspecialchars($cardapio['descricao']) . "</td>";
                                echo "<td class='action-buttons'>";
                                echo "<button class='edit' data-id='" . $cardapio['id'] . "' data-tipo='" . $cardapio['tipo'] . "' data-data='" . $cardapio['data'] . "' data-nome='" . htmlspecialchars($cardapio['nome']) . "' data-descricao='" . htmlspecialchars($cardapio['descricao']) . "'>Editar</button>";
                                echo "<button class='delete' onclick='deleteItem(" . $cardapio['id'] . ")'>Excluir</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                }
                ?>
            </table>

            <!-- Edit Modal for Cardápio -->
            <div class="modal" id="editCardapioModal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeEditModal('editCardapioModal')">×</span>
                    <h2>Editar Cardápio</h2>
                    <form id="editCardapioForm">
                        <input type="hidden" name="id" id="editCardapioId">
                        <div class="form-group">
                            <label for="editTipoCardapio">Tipo</label>
                            <select name="tipo" id="editTipoCardapio" class="border rounded-lg p-2 w-full">
                                <option value="lanche-manha">Lanche da manhã</option>
                                <option value="almoco">Almoço</option>
                                <option value="lanche-tarde">Lanche da tarde</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editDataCardapio">Data</label>
                            <input type="date" name="data" id="editDataCardapio" class="border rounded-lg p-2 w-full" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="editNomeCardapio">Nome</label>
                            <input type="text" name="nome" id="editNomeCardapio" class="border rounded-lg p-2 w-full">
                        </div>
                        <div class="form-group">
                            <label for="editDescricaoCardapio">Descrição</label>
                            <input type="text" name="descricao" id="editDescricaoCardapio" class="border rounded-lg p-2 w-full">
                        </div>
                        <div class="flex justify-end gap-4">
                            <button type="button" class="action-btn btn-cancel" onclick="closeEditModal('editCardapioModal')">Cancelar</button>
                            <button type="button" class="action-btn btn-submit" onclick="saveChanges('cardapio')">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Modals -->
            <div class="modal message-modal" id="successModal">
                <div class="modal-content">
                    <div class="modal-body success">
                        <i class="fas fa-check-circle icon"></i>
                        <h3>Sucesso</h3>
                        <p id="successMessage"></p>
                        <button class="action-btn btn-submit" onclick="closeMessageModal('successModal', false)">OK</button>
                    </div>
                </div>
            </div>
            <div class="modal message-modal" id="errorModal">
                <div class="modal-content">
                    <div class="modal-body error">
                        <i class="fas fa-times-circle icon"></i>
                        <h3>Erro</h3>
                        <p id="errorMessage"></p>
                        <button class="action-btn btn-cancel" onclick="closeMessageModal('errorModal', false)">Tentar Novamente</button>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal message-modal" id="deleteCardapioModal">
                <div class="modal-content">
                    <div class="modal-body">
                        <i class="fas fa-exclamation-triangle icon" style="color: #dc3545;"></i>
                        <h3>Confirmar Exclusão</h3>
                        <p>Tem certeza que deseja excluir este cardápio?</p>
                        <div class="flex justify-end gap-4">
                            <button class="action-btn btn-cancel" onclick="closeMessageModal('deleteCardapioModal', false)">Cancelar</button>
                            <button class="action-btn btn-submit" id="confirmDeleteBtn">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold">EEEP STGM</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            EEEP Salaberga Torquato Gomes de Matos - Educação profissional de qualidade.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold">Endereço</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Avenida Marta Maria Carvalho Nojoza, Outra Banda<br>
                            Maranguape - CE<br>
                            CEP: 61942-355
                        </p>
                    </div>
                </div>
                <div class="md:col-span-4 md:col-start-5 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Contatos</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.instagram.com/eeepsalabergamtg/" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-brands fa-instagram mr-2"></i>Instagram</a></li>
                        <li><a href="tel:+558533414000" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-phone mr-2"></i>(85) 3341-4000</a></li>
                        <li><a href="mailto:eeepsalab@gmail.com?subject=Contato%20EEEP%20STGM" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&to=eeepsalab@gmail.com', '_blank'); return true;" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-envelope mr-2"></i>Email</a></li>
                    </ul>
                </div>
                <div class="md:col-span-4 md:col-start-10 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Desenvolvedores</h3>
                    <p class="text-sm text-gray-400">Christian Santos</p>
                    <p class="text-sm text-gray-400">José Arimatéia</p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-sm text-gray-400">© 2025. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        // Initialize VLibras
        document.addEventListener('DOMContentLoaded', () => {
            try {
                new window.VLibras.Widget('https://vlibras.gov.br/app');
            } catch (e) {
                console.error('Erro ao inicializar VLibras:', e);
            }
        });

        // VLibras button action
        document.addEventListener('DOMContentLoaded', () => {
            const vlibrasButton = document.getElementById('vlibrasButton');
            if (vlibrasButton) {
                vlibrasButton.addEventListener('click', function() {
                    const vwButton = document.querySelector('div[vw-access-button]');
                    if (vwButton) {
                        vwButton.click();
                    } else {
                        window.open('https://www.gov.br/governodigital/pt-br/acessibilidade-e-usuario/vlibras', '_blank');
                    }
                });
            }
        });

        // Text size adjustment
        function changeTextSize(step) {
            const root = document.documentElement;
            let currentSize = parseFloat(getComputedStyle(root).fontSize);
            if (step === 0) {
                root.style.fontSize = '16px';
            } else {
                currentSize = Math.max(12, Math.min(24, currentSize + step));
                root.style.fontSize = currentSize + 'px';
            }
            localStorage.setItem('fontSize', root.style.fontSize);
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        }

        // Apply saved settings on load
        document.addEventListener('DOMContentLoaded', () => {
        const savedFontSize = localStorage.getItem('fontSize');
        if (savedFontSize) {
            document.documentElement.style.fontSize = savedFontSize;
        }
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }

        // Set minimum date to today
        const minDate = new Date().toISOString().split('T')[0];
        document.getElementById('dataCardapio').setAttribute('min', minDate);
        document.getElementById('editDataCardapio').setAttribute('min', minDate);
    });

        // Tab navigation
        const tabs = document.querySelectorAll('.nav-tab');
        const contents = document.querySelectorAll('.tab-content');

        function activateTab(tabId) {
            tabs.forEach(tab => tab.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));

            const tab = document.querySelector(`.nav-tab[data-tab="${tabId}"]`);
            const content = document.getElementById(tabId);
            if (tab && content) {
                tab.classList.add('active');
                content.classList.add('active');
            } else {
                console.error(`Tab or content not found for tabId: ${tabId}`);
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                console.log(`Clicked tab: ${tab.dataset.tab}`);
                activateTab(tab.dataset.tab);
            });
        });

        // Form handling
        function resetForm(formId) {
            document.getElementById(formId).reset();
        }

        // Function to attach event listeners to edit buttons
        function attachEditButtonListeners() {
            const editButtons = document.querySelectorAll('.edit');
            editButtons.forEach(button => {
                button.removeEventListener('click', handleEditClick); // Prevent duplicate listeners
                button.addEventListener('click', handleEditClick);
            });
        }

        // Handler for edit button click
        function handleEditClick(event) {
            openEditModal(event.target, 'cardapio');
        }

        // Modified openEditModal function
        function openEditModal(button, type) {
            const modalId = 'editCardapioModal';
            const prefix = 'Cardapio';

            // Retrieve data attributes
            const id = button.getAttribute('data-id');
            const tipo = button.getAttribute('data-tipo');
            const data = button.getAttribute('data-data');
            const nome = button.getAttribute('data-nome');
            const descricao = button.getAttribute('data-descricao');

            // Debugging to ensure data is retrieved correctly
            console.log('Opening edit modal with:', { id, tipo, data, nome, descricao });

            // Get the modal and form elements
            const modal = document.getElementById(modalId);
            if (!modal) {
                console.error(`Modal with ID ${modalId} not found`);
                return;
            }

            // Populate form fields
            const idField = document.getElementById(`edit${prefix}Id`);
            const tipoField = document.getElementById(`editTipo${prefix}`);
            const dataField = document.getElementById(`editData${prefix}`);
            const nomeField = document.getElementById(`editNome${prefix}`);
            const descricaoField = document.getElementById(`editDescricao${prefix}`);

            if (idField && tipoField && dataField && nomeField && descricaoField) {
                idField.value = id || '';
                tipoField.value = tipo || '';
                dataField.value = data || '';
                nomeField.value = nome || '';
                descricaoField.value = descricao || '';
            } else {
                console.error('One or more form fields not found');
                return;
            }

            // Display the modal
            modal.style.display = 'flex';
        }

        // Add new table row
        function addTableRow(id, tipo, data, nome, descricao) {
            const table = document.getElementById('cardapioTable');
            const row = document.createElement('tr');
            row.setAttribute('data-id', id);

            const dateObj = new Date(data);
            const formattedDate = `${dateObj.getDate().toString().padStart(2, '0')}/${(dateObj.getMonth() + 1).toString().padStart(2, '0')}/${dateObj.getFullYear()}`;
            const formattedTipo = tipo.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());

            row.innerHTML = `
                <td><input type="checkbox" class="cardapio-checkbox" data-id="${id}"></td>
                <td>${formattedDate}</td>
                <td>${formattedTipo}</td>
                <td>${nome}</td>
                <td>${descricao}</td>
                <td class="action-buttons">
                    <button class="edit" data-id="${id}" data-tipo="${tipo}" data-data="${data}" data-nome="${nome}" data-descricao="${descricao}">Editar</button>
                    <button class="delete" onclick="deleteItem(${id})">Excluir</button>
                </td>
            `;
            table.appendChild(row);

            // Attach event listeners
            row.querySelector('.cardapio-checkbox').addEventListener('change', () => {
                const anyChecked = Array.from(document.querySelectorAll('.cardapio-checkbox')).some(cb => cb.checked);
                document.getElementById('bulkDeleteBtn').classList.toggle('hidden', !anyChecked);
                document.getElementById('selectAll').checked = document.querySelectorAll('.cardapio-checkbox:checked').length === document.querySelectorAll('.cardapio-checkbox').length;
            });

            attachEditButtonListeners();
        }

        // Attach event listeners to existing edit buttons on page load
        document.addEventListener('DOMContentLoaded', () => {
            attachEditButtonListeners();
        });

        // Close edit modal
        function closeEditModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Save changes
        function saveChanges(type) {
            const prefix = 'Cardapio';
            const form = document.getElementById(`edit${prefix}Form`);
            const formData = new FormData(form);
            const action = `alterar${type.charAt(0).toUpperCase() + type.slice(1)}`;

            fetch(`../control/controlGerenciarCardapio.php?action=${action}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTableRow(formData.get('id'), formData);
                    showMessageModal('success', 'Cardápio atualizado com sucesso!', false);
                    closeEditModal(`edit${prefix}Modal`);
                } else {
                    showMessageModal('error', `Erro ao atualizar o cardápio: ${data.message || 'Erro desconhecido'}`);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showMessageModal('error', 'Erro ao atualizar o cardápio: Falha na conexão com o servidor.');
            });
        }

        // Delete item
        function deleteItem(id) {
            const modal = document.getElementById('deleteCardapioModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.dataset.id = id; // Armazena o ID no botão
            modal.style.display = 'flex';

            confirmBtn.onclick = function() {
                const formData = new FormData();
                formData.append('id', id);

                fetch(`../control/controlGerenciarCardapio.php?action=excluirCardapio`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`#cardapioTable tr[data-id="${id}"]`);
                        if (row) row.remove();
                        showMessageModal('success', `Cardápio excluído com sucesso!`, false);
                    } else {
                        showMessageModal('error', `Erro ao excluir o cardápio: ${data.message}`);
                    }
                    modal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showMessageModal('error', `Erro ao excluir o cardápio.`);
                    modal.style.display = 'none';
                });
            };
        }

        // Bulk delete
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.cardapio-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            document.getElementById('bulkDeleteBtn').classList.toggle('hidden', !this.checked && !Array.from(checkboxes).some(cb => cb.checked));
        });

        document.querySelectorAll('.cardapio-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const anyChecked = Array.from(document.querySelectorAll('.cardapio-checkbox')).some(cb => cb.checked);
                document.getElementById('bulkDeleteBtn').classList.toggle('hidden', !anyChecked);
                document.getElementById('selectAll').checked = document.querySelectorAll('.cardapio-checkbox:checked').length === document.querySelectorAll('.cardapio-checkbox').length;
            });
        });

        function bulkDelete() {
            const ids = Array.from(document.querySelectorAll('.cardapio-checkbox:checked')).map(cb => cb.getAttribute('data-id'));
            if (ids.length === 0) return;

            const modal = document.getElementById('deleteCardapioModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.dataset.ids = JSON.stringify(ids); // Armazena os IDs no botão
            document.querySelector('#deleteCardapioModal p').textContent = `Tem certeza que deseja excluir ${ids.length} cardápio(s)?`;
            modal.style.display = 'flex';

            confirmBtn.onclick = function() {
                const formData = new FormData();
                ids.forEach(id => formData.append('ids[]', id));

                fetch(`../control/controlGerenciarCardapio.php?action=excluirCardapios`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ids.forEach(id => {
                            const row = document.querySelector(`#cardapioTable tr[data-id="${id}"]`);
                            if (row) row.remove();
                        });
                        showMessageModal('success', `${ids.length} cardápio(s) excluído(s) com sucesso!`, false);
                        document.getElementById('selectAll').checked = false;
                        document.getElementById('bulkDeleteBtn').classList.add('hidden');
                    } else {
                        showMessageModal('error', `Erro ao excluir os cardápios: ${data.message}`);
                    }
                    modal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showMessageModal('error', 'Erro ao excluir os cardápios.');
                    modal.style.display = 'none';
                });
            };
        }

        // Generate report
        function generateReport() {
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');
            const menus = [];

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('td');
                if (cells.length >= 4) {
                    menus.push({
                        data: cells[1].textContent,
                        tipo: cells[2].textContent,
                        nome: cells[3].textContent,
                        descricao: cells[4].textContent
                    });
                }
            }

            if (menus.length === 0) {
                showMessageModal('error', 'Nenhum cardápio disponível para gerar o relatório.');
                return;
            }

            fetch('../control/controlGerenciarCardapio.php?action=gerarRelatorio', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ menus })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao gerar o relatório.');
                }
                return response.blob();
            })
            .then(blob => {
                showMessageModal('success', 'Relatório gerado com sucesso!', false);
                const okButton = document.querySelector('#successModal .btn-submit');
                okButton.dataset.blobUrl = window.URL.createObjectURL(blob);
            })
            .catch(error => {
                console.error('Erro:', error);
                showMessageModal('error', 'Erro ao gerar o relatório.');
            });
        }

        // Update table row
      function updateTableRow(id, formData) {
    const row = document.querySelector(`#cardapioTable tr[data-id="${id}"]`);
    if (row) {
        const tipo = formData.get('tipo');
        const data = formData.get('data');
        const nome = formData.get('nome');
        const descricao = formData.get('descricao');

        const dateObj = new Date(data);
        const formattedDate = `${dateObj.getDate().toString().padStart(2, '0')}/${(dateObj.getMonth() + 1).toString().padStart(2, '0')}/${dateObj.getFullYear()}`;
        const formattedTipo = tipo.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());

        row.cells[1].textContent = formattedDate;
        row.cells[2].textContent = formattedTipo;
        row.cells[3].textContent = nome;
        row.cells[4].textContent = descricao;

        const editButton = row.querySelector('.edit');
        editButton.setAttribute('data-tipo', tipo);
        editButton.setAttribute('data-data', data);
        editButton.setAttribute('data-nome', nome);
        editButton.setAttribute('data-descricao', descricao);
    }
}

        // Cadastrar Cardápio
        document.getElementById('cadastrarCardapioForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const tipo = formData.get('tipo');
    const data = formData.get('data');
    const nome = formData.get('nome').trim();
    const descricao = formData.get('descricao').trim();

    if (!tipo || !data || !nome || !descricao) {
        showMessageModal('error', 'Por favor, preencha todos os campos.');
        return;
    }

    fetch('../control/controlGerenciarCardapio.php?action=cadastrarCardapio', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            addTableRow(data.id, tipo, data, nome, descricao);
            showMessageModal('success', 'Cardápio cadastrado com sucesso!', false);
            resetForm('cadastrarCardapioForm');
            activateTab('alterarCardapio');
        } else {
            showMessageModal('error', data.message || 'Erro ao cadastrar o cardápio: Erro desconhecido');
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        showMessageModal('error', 'Erro ao cadastrar o cardápio: ' + error.message);
    });
});

        // Message modals
        function showMessageModal(type, message, reload = false) {
            closeMessageModal('successModal', false);
            closeMessageModal('errorModal', false);
            closeMessageModal('deleteCardapioModal', false);

            const modalId = type + 'Modal';
            const messageId = type + 'Message';
            document.getElementById(messageId).textContent = message;
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeMessageModal(modalId, reload) {
            document.getElementById(modalId).style.display = 'none';
            if (modalId === 'successModal') {
                const okButton = document.querySelector('#successModal .btn-submit');
                if (okButton.dataset.blobUrl) {
                    window.open(okButton.dataset.blobUrl, '_blank');
                    window.URL.revokeObjectURL(okButton.dataset.blobUrl);
                    delete okButton.dataset.blobUrl;
                }
            }
            if (reload) {
                location.reload();
            }
        }
    </script>
</body>
</html>