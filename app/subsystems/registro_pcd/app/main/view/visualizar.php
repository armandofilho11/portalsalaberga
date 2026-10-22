<?php 
 session_start();
 function redirect_to_login()
 {
   header('Location: ../../../../../main/views/autenticacao/login.php');
 }
 if (!isset($_SESSION['Email'])) {
   session_destroy();
   redirect_to_login();
 } 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Dados de Alunos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/Design sem nome.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        :root {
            --primary-color: #007A33;
            --primary-hover: #005e27;
            --secondary-color: #FF8C00;
            --background-color: #f8faf8;
            --card-background: #ffffff;
            --text-color: #2d3748;
            --border-color: #e2e8f0;
            --shadow-color: rgba(0, 122, 51, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e6f0e6 0%, #f0f7f0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 80px;
        }
        h1 {
            text-align: center;
            color: #007A33;
            font-family: 'Poppins', Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            background: var(--card-background);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px var(--shadow-color);
            animation: slideUp 0.8s ease-out forwards;
            margin-top: 80px;
            width: 100%;
        }
        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: var(--primary-color);
            color: #fff;
            padding: 12px;
            text-align: left;
            font-family: 'Poppins', Arial, sans-serif;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            font-family: 'Poppins', Arial, sans-serif;
        }
        tr:nth-child(even) {
            background-color: var(--background-color);
        }
        tr:hover {
            background-color: #d4e6d4;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: var(--primary-color);
            color: #fff !important;
            border: none;
            border-radius: 4px;
            margin: 0 5px;
            font-family: 'Poppins', Arial, sans-serif;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--primary-hover);
        }
        .dataTables_wrapper .dataTables_filter input {
            border: none;
            background-color: var(--background-color);
            border-radius: 4px;
            padding: 5px;
            font-family: 'Poppins', Arial, sans-serif;
            transition: all 0.3s ease;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            background-color: #e8e8e8;
            transform: scale(1.02);
            outline: none;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid var(--primary-color);
            border-radius: 4px;
            padding: 5px;
            font-family: 'Poppins', Arial, sans-serif;
        }
        .dataTables_info {
            color: var(--primary-color);
            font-family: 'Poppins', Arial, sans-serif;
        }
        .button-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }
        .pdf-view, .pdf-button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .pdf-view:hover, .pdf-button:hover {
            background-color: var(--primary-hover);
        }
        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .navbar-left {
            display: flex;
            align-items: center;
        }
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .navbar img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .navbar img:hover {
            transform: scale(1.05);
        }
        .back-btn {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        .user-info:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .user-info i {
            font-size: 18px;
        }
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            display: none;
            z-index: 1000;
            margin-top: 5px;
        }
        .user-dropdown.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .user-dropdown-item {
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .user-dropdown-item:hover {
            background-color: #f5f5f5;
        }
        .user-dropdown-item i {
            color: var(--primary-color);
            width: 20px;
        }
        .user-dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }
        .user-dropdown-header {
            padding: 12px 15px;
            color: #666;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        .modify-btn {
            background-color: #007A33;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .modify-btn:hover {
            background-color: #005e27;
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .container {
                padding: 10px;
                margin-top: 60px;
                width: 95%;
                margin-left: auto;
                margin-right: auto;
            }

            table {
                display: none;
            }

            .card-view {
                display: block;
            }

            .student-card {
                background: white;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transition: transform 0.2s ease;
            }

            .student-card:active {
                transform: scale(0.98);
            }

            .student-card h3 {
                color: var(--primary-color);
                margin-bottom: 12px;
                font-size: 1.2em;
                padding-bottom: 8px;
                border-bottom: 2px solid var(--primary-color);
            }

            .student-info {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid var(--border-color);
                font-size: 0.95em;
            }

            .info-label {
                font-weight: 600;
                color: var(--primary-color);
                min-width: 120px;
            }

            .info-value {
                color: var(--text-color);
                text-align: right;
                flex: 1;
                margin-left: 10px;
            }

            .card-actions {
                display: flex;
                gap: 12px;
                margin-top: 15px;
                justify-content: flex-end;
                padding-top: 10px;
                border-top: 1px solid var(--border-color);
            }

            .card-actions button {
                padding: 8px 16px;
                font-size: 1em;
                border-radius: 6px;
                min-width: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .card-actions button i {
                font-size: 1.1em;
            }

            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length {
                display: none;
            }

            /* Mobile Search Styles */
            .mobile-search {
                position: sticky;
                top: 70px;
                z-index: 100;
                background: white;
                padding: 10px 0;
                margin-bottom: 15px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .mobile-search input {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid var(--primary-color);
                border-radius: 8px;
                font-size: 1em;
                background-color: white;
                transition: all 0.3s ease;
            }

            .mobile-search input:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.2);
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 30px 20px;
                background: white;
                border-radius: 12px;
                margin-top: 20px;
            }

            .empty-state i {
                font-size: 3em;
                color: var(--primary-color);
                margin-bottom: 15px;
            }

            .empty-state p {
                color: var(--text-color);
                font-size: 1.1em;
            }

            /* Loading State */
            .loading-state {
                text-align: center;
                padding: 20px;
                background: white;
                border-radius: 12px;
                margin-top: 20px;
            }

            .loading-state i {
                font-size: 2em;
                color: var(--primary-color);
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        }
        .school-name {
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            margin-left: 15px;
            letter-spacing: 0.5px;
        }
        /* Card styles for mobile view */
        .card-view {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="school-name">EEEP Salaberga</span>
        </div>
        <div class="navbar-right">
            <a href="menu.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
            <div class="user-info" onclick="toggleUserDropdown()">
                <i class="fas fa-user"></i>
                <span><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário'; ?></span>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-dropdown-header">
                        <i class="fas fa-user-circle"></i>
                        <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário'; ?>
                    </div>
                    <div class="user-dropdown-divider"></div>
                    <a href="../index.php" class="user-dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Visualização de Dados de Alunos</h1>
        
        <div class="button-container">
            <button class="pdf-view" onclick="viewData()">Visualizar Dados</button>
            <button class="pdf-button" onclick="generatePDF()">Baixar PDF</button>
        </div>

        <table id="alunosTable" class="display">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Turma</th>
                    <th>Deficiência</th>
                    <th>Data do Registro</th>
                    <th>Presença</th>
                    <th>Registo do Dia</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../model/model.php';
                try {
                    $model = new Model();
                    $registros = $model->buscarTodosRegistros();

                    foreach ($registros as $registro) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($registro['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($registro['idade']) . "</td>";
                        echo "<td>" . htmlspecialchars($registro['turma']) . "</td>";
                        echo "<td>" . htmlspecialchars($registro['deficiencia'] ?? '-') . "</td>";
                        echo "<td>" . htmlspecialchars($registro['data_registro'] ?? '-') . "</td>";
                        echo "<td>" . ($registro['presenca'] ? 'Presente' : '-') . "</td>";
                        echo "<td>" . htmlspecialchars($registro['observacoes'] ?? '-') . "</td>";
                        echo "<td>
                            <button class='modify-btn' onclick='editarRegistro(" . $registro['id'] . ")' title='Editar registro'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <button class='delete-btn' onclick='confirmarExclusao(" . $registro['id'] . ")' title='Excluir registro'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </td>";
                        echo "</tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='8'>Erro ao carregar dados: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Card View for Mobile -->
        <div class="card-view" id="cardView">
            <?php
            if (isset($registros)) {
                foreach ($registros as $registro) {
                    echo '<div class="student-card">';
                    echo '<h3>' . htmlspecialchars($registro['nome']) . '</h3>';
                    echo '<div class="student-info">';
                    echo '<div class="info-item"><span class="info-label">Idade:</span><span class="info-value">' . htmlspecialchars($registro['idade']) . '</span></div>';
                    echo '<div class="info-item"><span class="info-label">Turma:</span><span class="info-value">' . htmlspecialchars($registro['turma']) . '</span></div>';
                    echo '<div class="info-item"><span class="info-label">Deficiência:</span><span class="info-value">' . htmlspecialchars($registro['deficiencia'] ?? '-') . '</span></div>';
                    echo '<div class="info-item"><span class="info-label">Data do Registro:</span><span class="info-value">' . htmlspecialchars($registro['data_registro'] ?? '-') . '</span></div>';
                    echo '<div class="info-item"><span class="info-label">Presença:</span><span class="info-value">' . ($registro['presenca'] ? 'Presente' : '-') . '</span></div>';
                    echo '<div class="info-item"><span class="info-label">Observações:</span><span class="info-value">' . htmlspecialchars($registro['observacoes'] ?? '-') . '</span></div>';
                    echo '</div>';
                    echo '<div class="card-actions">';
                    echo '<button class="modify-btn" onclick="editarRegistro(' . $registro['id'] . ')" title="Editar registro"><i class="fas fa-edit"></i></button>';
                    echo '<button class="delete-btn" onclick="confirmarExclusao(' . $registro['id'] . ')" title="Excluir registro"><i class="fas fa-trash-alt"></i></button>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div class="modal-content" style="background-color: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; position: relative;">
            <span class="close" onclick="fecharModalExclusao()" style="position: absolute; right: 15px; top: 15px; font-size: 24px; cursor: pointer; color: #666;">&times;</span>
            <h3 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.5rem;">Confirmar Exclusão</h3>
            <p style="color: var(--text-color); margin-bottom: 20px; line-height: 1.5;">Tem certeza que deseja excluir este registro? Esta ação não pode ser desfeita.</p>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button onclick="fecharModalExclusao()" style="padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; background-color: #6c757d; color: white;">Cancelar</button>
                <button onclick="excluirRegistro()" style="padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; background-color: #dc3545; color: white;">Excluir</button>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div class="modal-content" style="background-color: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 500px; position: relative;">
            <span class="close" onclick="fecharModalEdicao()" style="position: absolute; right: 15px; top: 15px; font-size: 24px; cursor: pointer; color: #666;">&times;</span>
            <h3 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.5rem;">Editar Registro</h3>
            <form id="editForm" method="POST" action="../control/controlEditarRegistro.php">
                <input type="hidden" id="edit_id" name="id">
                <div style="margin-bottom: 15px;">
                    <label for="edit_nome" style="display: block; margin-bottom: 5px; color: var(--text-color);">Nome:</label>
                    <input type="text" id="edit_nome" name="nome" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="edit_idade" style="display: block; margin-bottom: 5px; color: var(--text-color);">Idade:</label>
                    <input type="number" id="edit_idade" name="idade" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="edit_turma" style="display: block; margin-bottom: 5px; color: var(--text-color);">Turma:</label>
                    <input type="text" id="edit_turma" name="turma" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="edit_deficiencia" style="display: block; margin-bottom: 5px; color: var(--text-color);">Deficiência:</label>
                    <input type="text" id="edit_deficiencia" name="deficiencia" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="edit_presenca" style="display: block; margin-bottom: 5px; color: var(--text-color);">Presença:</label>
                    <select id="edit_presenca" name="presenca" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                        <option value="1">Presente</option>
                        <option value="0">Ausente</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="edit_observacoes" style="display: block; margin-bottom: 5px; color: var(--text-color);">Observações:</label>
                    <textarea id="edit_observacoes" name="observacoes" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; min-height: 100px;"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="fecharModalEdicao()" style="padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; background-color: #6c757d; color: white;">Cancelar</button>
                    <button type="submit" style="padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; background-color: var(--primary-color); color: white;">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
        <div class="modal-content">
            <span class="close" style="position: absolute; top: 10px; right: 10px; font-size: 20px; cursor: pointer;">&times;</span>
            <div id="viewContent"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const table = $('#alunosTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    searchPlaceholder: 'Pesquisar'
                },
                pageLength: 10,
                lengthChange: false,
                responsive: true,
                order: [],
                drawCallback: function() {
                    if (this.api().rows().count() === 0) {
                        $('#alunosTable tbody').html('<tr><td colspan="7" style="text-align: center;">Nenhum dado disponível na tabela</td></tr>');
                        $('#cardView').html(`
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>Nenhum registro encontrado</p>
                            </div>
                        `);
                    }
                }
            });

            // Enhanced mobile search functionality
            const searchContainer = $('<div class="mobile-search"></div>');
            const searchInput = $('<input type="text" placeholder="Pesquisar registros..." />');
            searchContainer.append(searchInput);
            $('.card-view').before(searchContainer);

            let searchTimeout;
            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = $(this).val().toLowerCase();
                
                // Show loading state
                if (searchTerm.length > 0) {
                    $('#cardView').html(`
                        <div class="loading-state">
                            <i class="fas fa-spinner"></i>
                        </div>
                    `);
                }

                searchTimeout = setTimeout(() => {
                    $('.student-card').each(function() {
                        const cardText = $(this).text().toLowerCase();
                        $(this).toggle(cardText.includes(searchTerm));
                    });

                    // Show empty state if no results
                    if ($('.student-card:visible').length === 0 && searchTerm.length > 0) {
                        $('#cardView').html(`
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>Nenhum resultado encontrado para "${searchTerm}"</p>
                            </div>
                        `);
                    }
                }, 300);
            });

            // Add pull-to-refresh functionality
            let touchStartY = 0;
            let touchEndY = 0;
            const container = $('.container');

            container.on('touchstart', function(e) {
                touchStartY = e.originalEvent.touches[0].clientY;
            });

            container.on('touchend', function(e) {
                touchEndY = e.originalEvent.changedTouches[0].clientY;
                const pullDistance = touchEndY - touchStartY;

                if (pullDistance > 100 && window.scrollY === 0) {
                    location.reload();
                }
            });

            // Menu toggle
            const menuToggle = document.querySelector('.menu-toggle');
            const navMenu = document.querySelector('.nav-menu');
            menuToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });
        });

        function viewData() {
            const table = $('#alunosTable').DataTable();
            const data = table.data().toArray();

            // Função para sanitizar strings e evitar XSS
            function sanitizeHTML(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            const viewWindow = window.open('', '_blank', 'width=800,height=600');
            viewWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Dados dos Alunos</title>
                    <style>
                        body {
                            font-family: 'Poppins', Arial, sans-serif;
                            padding: 20px;
                            background-color: #f5f5f5;
                        }
                        .student-card {
                            background: white;
                            padding: 20px;
                            margin-bottom: 20px;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        h2 {
                            color: #007A33;
                            margin-bottom: 20px;
                        }
                        h3 {
                            color: #005e27;
                            margin-bottom: 15px;
                        }
                        p {
                            margin: 5px 0;
                            line-height: 1.5;
                        }
                        strong {
                            color: #007A33;
                        }
                    </style>
                </head>
                <body>
                    <h2>Dados dos Alunos</h2>
                    ${data.length === 0 ? '<p>Nenhum dado disponível</p>' :
                        data.map(row => `
                            <div class="student-card">
                                <h3>${sanitizeHTML(row[0])}</h3>
                                <p><strong>Idade:</strong> ${sanitizeHTML(row[1])}</p>
                                <p><strong>Deficiência:</strong> ${sanitizeHTML(row[2])}</p>
                                <p><strong>Turma:</strong> ${sanitizeHTML(row[3])}</p>
                                <p><strong>Data do Registro:</strong> ${sanitizeHTML(row[4])}</p>
                                <p><strong>Presença:</strong> ${sanitizeHTML(row[5])}</p>
                                <p><strong>Observações:</strong> ${sanitizeHTML(row[6])}</p>
                            </div>
                        `).join('')}
                </body>
                </html>
            `);
            viewWindow.document.close();
        }

        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const table = $('#alunosTable').DataTable();
            const data = table.data().toArray();

            // Mostrar loader
            const loader = document.createElement('div');
            loader.innerText = 'Gerando PDF...';
            loader.style.position = 'fixed';
            loader.style.top = '50%';
            loader.style.left = '50%';
            loader.style.transform = 'translate(-50%, -50%)';
            loader.style.padding = '20px';
            loader.style.background = '#007A33';
            loader.style.color = '#fff';
            loader.style.borderRadius = '8px';
            document.body.appendChild(loader);

            // Configurar o PDF
            doc.setFont('helvetica');
            doc.setFontSize(20);
            doc.setTextColor(0, 122, 51); // Cor verde #007A33
            doc.text("Dados dos Alunos", 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0);

            let y = 40;
            const pageHeight = 280;
            const margin = 20;
            const cardHeight = 60;

            // Função para sanitizar strings
            function sanitizeHTML(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            // Gerar cards para cada aluno
            data.forEach((row, index) => {
                // Verificar se precisa de nova página
                if (y + cardHeight > pageHeight) {
                    doc.addPage();
                    y = margin;
                }

                // Desenhar card
                doc.setDrawColor(0, 122, 51);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(margin, y, 170, cardHeight, 3, 3, 'FD');

                // Nome do aluno
                doc.setFontSize(14);
                doc.setTextColor(0, 122, 51);
                doc.text(sanitizeHTML(row[0]), margin + 5, y + 10);

                // Informações do aluno
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.text(`Idade: ${sanitizeHTML(row[1])}`, margin + 5, y + 20);
                doc.text(`Deficiência: ${sanitizeHTML(row[2])}`, margin + 5, y + 30);
                doc.text(`Turma: ${sanitizeHTML(row[3])}`, margin + 5, y + 40);
                doc.text(`Data do Registro: ${sanitizeHTML(row[4])}`, margin + 5, y + 50);

                // Adicionar presença e observações se houver
                if (row[5] || row[6]) {
                    doc.text(`Presença: ${sanitizeHTML(row[5])}`, margin + 90, y + 20);
                    if (row[6]) {
                        doc.text(`Observações: ${sanitizeHTML(row[6])}`, margin + 90, y + 30);
                    }
                }

                y += cardHeight + 10;
            });

            // Adicionar número da página
            const totalPages = doc.internal.getNumberOfPages();
            for (let i = 1; i <= totalPages; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(128);
                doc.text(`Página ${i} de ${totalPages}`, 105, 290, { align: 'center' });
            }

            // Salvar o PDF
            doc.save('Dados_Alunos.pdf');
            document.body.removeChild(loader);
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');

            // Fechar o dropdown quando clicar fora dele
            document.addEventListener('click', function closeDropdown(e) {
                if (!e.target.closest('.user-info')) {
                    dropdown.classList.remove('active');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }

        let registroIdParaExcluir = null;

        function confirmarExclusao(id) {
            registroIdParaExcluir = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function fecharModalExclusao() {
            document.getElementById('deleteModal').style.display = 'none';
            registroIdParaExcluir = null;
        }

        function excluirRegistro() {
            if (registroIdParaExcluir) {
                // Criar um formulário dinâmico para enviar a requisição
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '../control/controlExcluirRegistro.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'registro_id';
                input.value = registroIdParaExcluir;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editarRegistro(id) {
            // Fazer uma requisição AJAX para buscar os dados do registro
            fetch(`../control/controlBuscarRegistro.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    // Preencher o formulário com os dados
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_nome').value = data.nome;
                    document.getElementById('edit_idade').value = data.idade;
                    document.getElementById('edit_turma').value = data.turma;
                    document.getElementById('edit_deficiencia').value = data.deficiencia;
                    document.getElementById('edit_presenca').value = data.presenca;
                    document.getElementById('edit_observacoes').value = data.observacoes;

                    // Mostrar o modal
                    document.getElementById('editModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Erro ao buscar dados:', error);
                    alert('Erro ao carregar dados do registro');
                });
        }

        function fecharModalEdicao() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Fechar o modal de edição quando clicar fora dele
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target == editModal) {
                fecharModalEdicao();
            }
            if (event.target == deleteModal) {
                fecharModalExclusao();
            }
        }
    </script>
</body>
</html>