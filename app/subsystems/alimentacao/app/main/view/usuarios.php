<?php
session_start();

// Check if user is logged in and is an administrator
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
    header("Location: ../view/login.php?error=Por favor, faça login como administrador para acessar esta página");
    exit();
}

require_once '../model/usuario.class.php';
$usuarioModel = new Usuario();
$usuarios = $usuarioModel->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEEP STGM - Gerenciamento de Usuários</title>
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
            padding: 0.5rem 0;
        }
        .mobile-menu {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
            width: 100%;
            color: white;
            margin: 0;
            padding: 0;
        }
        .mobile-menu-content {
            padding-top: 0.5rem;
            padding-bottom: 1rem;
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
        .welcome-message {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .welcome-message h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        body.dark .welcome-message h2 {
            color: #A5D6A7;
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
        .table-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px var(--shadow-color);
            overflow-x: auto;
        }
        body.dark .table-container {
            background-color: #2d2d2d;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px var(--shadow-color);
        }
        body.dark .users-table {
            background-color: #2d2d2d;
        }
        .users-table th, .users-table td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #ddd;
        }
        body.dark .users-table th, body.dark .users-table td {
            border-color: #555;
        }
        .users-table th {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
            font-weight: 600;
        }
        .users-table td {
            color: var(--accent);
        }
        body.dark .users-table td {
            color: #d1d5db;
        }
        .users-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        body.dark .users-table tr:nth-child(even) {
            background-color: #3a3a3a;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        .action-buttons form {
            margin: 0;
            padding: 0;
            display: inline-flex;
        }
        .action-buttons button {
            padding: 0.5rem;
            margin: 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            line-height: 1.2;
            width: 90px;
            height: 40px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
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
            z-index: 1002;
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
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-group label {
            display: block;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        body.dark .input-group label {
            color: #d1d5db;
        }
        .input-group input, .input-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background-color: white;
        }
        body.dark .input-group input, body.dark .input-group select {
            background-color: #333;
            color: #d1d5db;
            border-color: #555;
        }
        .input-group input:focus, .input-group select:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        /* Add or replace within the existing .modal-buttons section */
        .modal-buttons {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
        #deleteUserModal .modal-buttons {
            flex-direction: row; /* Side by side by default */
        }
        @media (max-width: 480px) {
            #deleteUserModal .modal-buttons {
                flex-direction: column; /* Stack vertically on small screens */
            }
            .modal-buttons button {
                width: 100%; /* Full width on small screens */
            }
        }
        .modal-buttons button {
            flex: 1;
            padding: 0.75rem;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            min-height: 44px;
        }
        .save-btn {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
        }
        .save-btn:hover {
            background: linear-gradient(45deg, #1a3c2e, #2a6d43);
        }
        .cancel-btn {
            background: #EF5350;
            color: white;
        }
        .cancel-btn:hover {
            background: #FF8A80;
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
        .message-modal .modal-buttons {
            justify-content: center;
        }
        body.dark .message-modal p {
            color: #d1d5db;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
            padding: 0.5rem 0;
        }
        .accessibility-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
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
        @media (min-width: 1024px) {
            .menu-items {
                display: flex !important;
            }
            .mobile-menu-button {
                display: none;
            }
        }
        @media (max-width: 1023px) {
            .menu-items {
                display: none;
            }
            .mobile-menu-button {
                display: flex;
                align-items: center;
                padding: 0.5rem;
                border: 1px solid var(--secondary-color);
                border-radius: 4px;
                color: var(--secondary-color);
                transition: color 0.3s ease, border-color 0.3s ease;
            }
            .mobile-menu-button:hover {
                color: white;
                border-color: white;
            }
            .mobile-menu {
                display: none;
            }
            .mobile-menu.block {
                display: block;
            }
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }
            .navbar {
                padding: 0.4rem 0;
            }
            .accessibility-controls {
                gap: 0.5rem;
            }
            .menu-items {
                gap: 1.5rem;
            }
            .menu-items a {
                font-size: 0.9rem;
            }
            .users-table th, .users-table td {
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
            .navbar {
                padding: 0.3rem 0;
            }
            .accessibility-controls {
                gap: 0.4rem;
            }
            .menu-items {
                gap: 1rem;
            }
            .menu-items a {
                font-size: 0.85rem;
            }
            .users-table th, .users-table td {
                font-size: 0.8rem;
                padding: 0.5rem;
            }
            .action-buttons button {
                width: 80px;
                height: 36px;
                padding: 0.4rem;
                font-size: 0.85rem;
            }
            .modal-content {
                width: 90%;
                padding: 1rem;
            }
            .modal-content h2 {
                font-size: 1.1rem;
            }
            .input-group input, .input-group select {
                font-size: 0.9rem;
                padding: 0.5rem;
            }
            .modal-buttons button {
                font-size: 0.9rem;
            }
        }
        @media (max-width: 480px) {
            .navbar {
                flex-wrap: wrap;
                position: relative;
            }
            .accessibility-controls {
                gap: 0.3rem;
            }
            .mobile-menu-button {
                display: flex;
            }
            .menu-items {
                display: none;
            }
            .mobile-menu {
                display: none;
            }
            .mobile-menu.block {
                display: block;
            }
            .table-container {
                overflow-x: hidden;
            }
            .users-table {
                display: block;
                box-shadow: none;
            }
            .users-table thead {
                display: none;
            }
            .users-table tbody, .users-table tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 8px;
                background-color: transparent;
                box-shadow: 0 2px 4px var(--shadow-color);
                padding: 0.5rem;
            }
            body.dark .users-table tr {
                background-color: #3a3a3a;
                border-color: #555;
            }
            .users-table tr:nth-child(even) {
                background-color: white;
            }
            body.dark .users-table tr:nth-child(even) {
                background-color: #3a3a3a;
            }
            .users-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: left;
                padding: 0.5rem 0;
                border: none;
                border-bottom: 1px solid #ddd;
                font-size: 0.85rem;
            }
            body.dark .users-table td {
                border-bottom-color: #555;
            }
            .users-table td:last-child {
                border-bottom: none;
            }
            .users-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary-color);
                flex: 0 0 40%;
                text-align: left;
            }
            body.dark .users-table td::before {
                color: #d1d5db;
            }
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
                padding: 0;
            }
            .action-buttons form {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .action-buttons button {
                width: 100%;
                height: 44px;
                padding: 0.5rem;
                font-size: 0.85rem;
                line-height: 1.2;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .modal-content {
                width: 95%;
                padding: 1rem;
            }
            .modal-content h2 {
                font-size: 1rem;
            }
            .input-group input, .input-group select {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
            .modal-buttons {
                display: flex;
                flex-direction: row;
                gap: 0.5rem;
            }
            .modal-buttons button {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
            .footer {
                padding: 1.5rem 0;
            }
            .footer .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .footer .grid {
                gap: 1rem;
            }
            .footer h3, .footer p, .footer a {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body class="font-sans bg-neutral" x-data="{ mobileMenuOpen: false }">
    <header class="header">
        <div class="container mx-auto px-4">
            <nav class="navbar">
                <div class="accessibility-controls">
                    <span class="text-sm font-semibold">Acessibilidade</span>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Diminuir tamanho do texto" onclick="changeTextSize(-2)">
                        <i class="fa-solid fa-a"></i><b>-</b>
                    </button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Tamanho padrão do texto" onclick="changeTextSize(0)">
                        <i class="fa-solid fa-a"></i>
                    </button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Aumentar tamanho do texto" onclick="changeTextSize(2)">
                        <i class="fa-solid fa-a"></i><b>+</b>
                    </button>
                    <button class="hover:text-secondary transition duration-300 px-1" aria-label="Alternar modo escuro" onclick="toggleDarkMode()">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="menu-items">
                        <a href="gerenciarCardapio.php">
                            <i class="fa-solid fa-utensils mr-1"></i> Gerenciamento de Cardápios
                        </a>
                        <a href="relatorios.php">
                            <i class="fa-solid fa-edit mr-1"></i> Relatórios de Satisfação
                        </a>
                        <a href="sugestoes.php">
                            <i class="fa-solid fa-comment mr-1"></i> Sugestões
                        </a>
                        <a href="sistemaAdministrador.php">
                            <i class="fa-solid fa-house mr-1"></i> Início
                        </a>
                    </div>
                    <div class="block lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-menu-button">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>
            <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="lg:hidden mobile-menu">
                <div class="container mx-auto px-4 mobile-menu-content">
                    <a href="sistemaAdministrador.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-house mr-1"></i> Início
                    </a>
                    <a href="gerenciarCardapio.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-utensils mr-1"></i> Gerenciamento de Cardápios
                    </a>
                    <a href="relatorios.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-edit mr-1"></i> Relatórios de Satisfação
                    </a>
                    <a href="sugestoes.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-comment mr-1"></i> Sugestões
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="welcome-message">
            <h2>Gerenciamento de Usuários</h2>
        </div>
        <?php if (isset($_GET['error'])): ?>
            <div class="message error"><?php echo htmlentities($_GET['error'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="message success"><?php echo htmlentities($_GET['success'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <div class="table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo de Usuário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                        <tr data-email="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>">
                            <td data-label="ID"><?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td data-label="Nome"><?php echo htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td data-label="E-mail"><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td data-label="Tipo de Usuário"><?php echo htmlspecialchars($user['tipo_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td data-label="Ações" class="action-buttons">
                                <button class="edit-btn" onclick='openEditModal(<?php echo json_encode($user); ?>)'>Editar</button>
                                <button class="delete-btn" onclick="deleteUser('<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>')">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">×</span>
            <h2>Editar Usuário</h2>
            <form id="editForm" onsubmit="saveChanges(event)">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="original_email" id="originalEmail">
                <div class="input-group">
                    <label for="editNome">Nome</label>
                    <input type="text" name="nome" id="editNome" class="border rounded-lg p-2 w-full" required>
                </div>
                <div class="input-group">
                    <label for="editEmail">E-mail</label>
                    <input type="email" name="email" id="editEmail" class="border rounded-lg p-2 w-full" required>
                </div>
                <div class="input-group">
                    <label for="editTipoUsuario">Tipo de Usuário</label>
                    <select name="tipo_usuario" id="editTipoUsuario" class="border rounded-lg p-2 w-full" required>
                        <option value="aluno">Aluno</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="editSenha">Senha (opcional)</label>
                    <input type="password" name="senha" id="editSenha" class="border rounded-lg p-2 w-full" placeholder="Deixe em branco para não alterar">
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="save-btn">Salvar</button>
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal message-modal" id="deleteUserModal">
        <div class="modal-content">
            <div class="modal-body">
                <i class="fas fa-exclamation-triangle icon" style="color: #dc3545;"></i>
                <h3>Confirmar Exclusão</h3>
                <p>Tem certeza que deseja excluir este usuário?</p>
                <div class="modal-buttons">
                    <button class="cancel-btn" onclick="closeMessageModal('deleteUserModal')">Cancelar</button>
                    <button class="save-btn" id="confirmDeleteBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Modals -->
    <div class="modal message-modal" id="successModal">
        <div class="modal-content">
            <div class="modal-body success">
                <i class="fas fa-check-circle icon"></i>
                <h3>Sucesso</h3>
                <p id="successMessage"></p>
                <div class="modal-buttons">
                    <button class="save-btn" onclick="closeMessageModal('successModal', true)">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal message-modal" id="errorModal">
        <div class="modal-content">
            <div class="modal-body error">
                <i class="fas fa-times-circle icon"></i>
                <h3>Erro</h3>
                <p id="errorMessage"></p>
                <div class="modal-buttons">
                    <button class="cancel-btn" onclick="closeMessageModal('errorModal')">Tentar Novamente</button>
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
        document.getElementById('vlibrasButton')?.addEventListener('click', function() {
            const vwButton = document.querySelector('div[vw-access-button]');
            if (vwButton) {
                vwButton.click();
            } else {
                window.open('https://www.gov.br/governodigital/pt-br/acessibilidade-e-usuario/vlibras', '_blank');
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
        });

        // Edit modal
        const editModal = document.getElementById('editModal');

        function openEditModal(user) {
            document.getElementById('originalEmail').value = user.email;
            document.getElementById('editNome').value = user.nome;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editTipoUsuario').value = user.tipo_usuario;
            document.getElementById('editSenha').value = '';
            editModal.style.display = 'flex';
        }

        function closeEditModal() {
            editModal.style.display = 'none';
        }

        // Save changes
        function saveChanges(event) {
            event.preventDefault();
            const form = document.getElementById('editForm');
            const formData = new FormData(form);

            fetch('../control/controlUsuarios.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTableRow(formData);
                    showMessageModal('success', 'Usuário atualizado com sucesso!', true);
                    closeEditModal();
                } else {
                    showMessageModal('error', data.message || 'Erro ao atualizar o usuário.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showMessageModal('error', 'Erro ao atualizar o usuário: Falha na conexão com o servidor.');
            });
        }

        // Update table row
        function updateTableRow(formData) {
            const email = formData.get('original_email');
            const row = document.querySelector(`tr[data-email="${email}"]`);
            if (row) {
                row.cells[1].textContent = formData.get('nome');
                row.cells[2].textContent = formData.get('email');
                row.cells[3].textContent = formData.get('tipo_usuario');
                row.setAttribute('data-email', formData.get('email'));
            }
        }

        // Delete user
        function deleteUser(email) {
            const modal = document.getElementById('deleteUserModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.dataset.email = email;
            modal.style.display = 'flex';

            confirmBtn.onclick = function() {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('email', email);

                fetch('../control/controlUsuarios.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`tr[data-email="${email}"]`);
                        if (row) row.remove();
                        showMessageModal('success', 'Usuário excluído com sucesso!', true);
                    } else {
                        showMessageModal('error', data.message || 'Erro ao excluir o usuário.');
                    }
                    modal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showMessageModal('error', 'Erro ao excluir o usuário.');
                    modal.style.display = 'none';
                });
            };
        }

        // Message modals
        function showMessageModal(type, message, reload = false) {
            closeMessageModal('successModal');
            closeMessageModal('errorModal');
            closeMessageModal('deleteUserModal');

            const modalId = type + 'Modal';
            const messageId = type + 'Message';
            document.getElementById(messageId).textContent = message;
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeMessageModal(modalId, reload = false) {
            document.getElementById(modalId).style.display = 'none';
            if (reload) {
                location.reload();
            }
        }

        // Close modals on click outside
        document.addEventListener('click', (e) => {
            if (e.target === editModal) {
                closeEditModal();
            }
            if (e.target === document.getElementById('deleteUserModal')) {
                closeMessageModal('deleteUserModal');
            }
        });
    </script>
</body>
</html>