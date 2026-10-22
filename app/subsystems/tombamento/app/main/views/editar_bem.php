<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Editar Itens - Sistema de Tombamento</title>
</head>
<body>
    <a href="../includes/menu.php" class="seta-voltar">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2>Edição</h2>
    <p>de Itens</p>    
    <div class="sub-texto">Aqui você pode editar seus itens</div>   
    <div class="Container"> 
        <div class="img">
            <img src="../img/logo-gp-2.png" alt="Logo">
        </div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Pesquisar itens...">
        </div>

        <main class="content">
            <section class="item-list">
                <?php
                session_start();
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname ="sistema_tombamento";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Fetch items from the database
                    $stmt = $conn->prepare("SELECT id_bem, nome, observacoes, numero_tombamento, ano_aquisicao, estado_conservacao, valor, setor_id FROM Bem");
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        echo '<div class="table-wrapper">';
                        echo '<table class="item-table" id="item-table">';
                        echo '<thead class="table-header">';
                        echo '<tr>';
                        echo '<th class="col-id">ID</th>';
                        echo '<th class="col-descricao">Descrição</th>';
                        echo '<th class="col-observacoes">Observações</th>';
                        echo '<th class="col-tombamento">Nº de Tombamento</th>';
                        echo '<th class="col-ano">Ano de Aquisição</th>';
                        echo '<th class="col-estado">Estado de Conservação</th>';
                        echo '<th class="col-valor">Valor</th>';
                        echo '<th class="col-edit">Ação</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody class="table-body">';
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr class="table-row">';
                            echo '<td class="col-id">' . htmlspecialchars($row['id_bem']) . '</td>';
                            echo '<td class="col-descricao">' . htmlspecialchars($row['nome']) . '</td>';
                            echo '<td class="col-observacoes">' . htmlspecialchars($row['observacoes'] ?? '') . '</td>';
                            echo '<td class="col-tombamento">' . htmlspecialchars($row['numero_tombamento']) . '</td>';
                            echo '<td class="col-ano">' . htmlspecialchars($row['ano_aquisicao'] ?? '') . '</td>';
                            echo '<td class="col-estado">' . htmlspecialchars($row['estado_conservacao']) . '</td>';
                            echo '<td class="col-valor">' . htmlspecialchars($row['valor'] ?? '') . '</td>';
                            echo '<td class="col-edit"><button class="edit-button" onclick="openEditModal(' . htmlspecialchars(json_encode($row)) . ')">Editar</button></td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    }
                } catch(PDOException $e) {
                    // Error message removed as per request
                }

                $conn = null;
                ?>
            </section>
        </main>
    </div> 

    <!-- Modal para edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeEditModal()">×</span>
            <form id="editForm" action="../controllers/BemController.php?action=update" method="POST">
                <input type="hidden" id="id_bem" name="id_bem">
                <div class="form-group">
                    <label for="nome">Descrição:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes"></textarea>
                </div>
                <div class="form-group">
                    <label for="numero_tombamento">Nº de Tombamento:</label>
                    <input type="text" id="numero_tombamento" name="numero_tombamento">
                </div>
                <div class="form-group">
                    <label for="ano_aquisicao">Ano de Aquisição:</label>
                    <input type="number" id="ano_aquisicao" name="ano_aquisicao" required>
                </div>
                <div class="form-group">
                    <label for="estado_conservacao">Estado de Conservação:</label>
                    <select id="estado_conservacao" name="estado_conservacao" required>
                        <option value="Novo">Novo</option>
                        <option value="Bom">Bom</option>
                        <option value="Regular">Regular</option>
                        <option value="Ruim">Ruim</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="valor">Valor:</label>
                    <input type="number" id="valor" name="valor" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="setor_id">Setor:</label>
                    <select id="setor_id" name="setor_id" required>
                        <?php
                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $stmt = $conn->prepare("SELECT id_setor, nome FROM Setor");
                            $stmt->execute();
                            while ($setor = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . htmlspecialchars($setor['id_setor']) . '">' . htmlspecialchars($setor['nome']) . '</option>';
                            }
                        } catch (PDOException $e) {
                            echo '<option value="">Erro ao carregar setores</option>';
                        }
                        $conn = null;
                        ?>
                    </select>
                </div>
                <button type="submit" class="submit-button">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <style>
        :root {
            --backcrond-color: #F5F5F5;
            --table-color: #FFFFFF;
            --primary-color: #2E7D32;
            --secondary-color: #FF8C00;
            --text-color: #2D2D2D;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --input-bg: #F5F5F5;
            --bg-color: #FFFFFF;
            --table-bg: #F1F1F1;
        }

        body {
            background-color: var(--backcrond-color);
            align-items: center;
            justify-content: center;
            display: flex;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .Container {
            width: 90%;
            max-width: 900px;
            background-color: var(--table-color);
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 20px;
            margin: 20px auto;
            margin-top: 15%;
        }

        .seta-voltar {
            position: absolute;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
            left: 10px;
            top: 15px;
        }

        h2 {
            position: absolute;
            top: 40px;
            left: 20px;
            margin: 0;
            font-size: 2.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }

        p {
            position: absolute;
            top: 80px;
            left: 20px;
            margin: 0;
            font-size: 1.5rem;
            color: var(--secondary-color);
            font-weight: 700;
        }

        .sub-texto {
            position: absolute;
            top: 110px;
            left: 20px;
            margin: 0;
            font-size: 1.125rem;
            color: var(--text-color);
        }

        .img {
            width: 100%;
            max-width: 200px;
            height: auto;
            display: block;
            margin-right: 90%;
        }

        .img img {
            width: 100%;
            height: auto;
            display: block;
        }

        .message-box {
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .success-message, .error-message {
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px auto;
            box-shadow: 0 2px 8px var(--shadow-color);
            width: fit-content;
            max-width: 80%;
            position: relative;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            width: 100%;
            max-width: 400px;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            background-color: var(--input-bg);
            color: var(--text-color);
            font-size: 1rem;
            outline: none;
            box-shadow: 0 2px 5px var(--shadow-color);
            font-family: 'Poppins', sans-serif;
        }

        .search-bar input::placeholder {
            color: #888;
        }

        .table-wrapper {
            overflow-x: auto;
            width: 100%;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background-color: var(--table-bg);
        }

        .item-table th, .item-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 4px solid #FFFFFF;
            font-size: 0.9rem;
        }

        .item-table thead {
            background-color: var(--primary-color);
            color: var(--table-color);
        }

        .table-row:hover {
            background-color: #e0e0e0;
        }

        th:first-child {
            border-top-left-radius: 12px;
        }

        th:last-child {
            border-top-right-radius: 12px;
        }

        tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        .edit-button {
            padding: 8px 12px;
            background-color: var(--primary-color);
            color: var(--table-color);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: var(--table-color);
            margin: 5% auto;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 20px var(--shadow-color);
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 1.75rem;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: var(--text-color);
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: var(--table-color);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        /* Media Queries for Smaller Screens */
        @media screen and (max-width: 768px) {
            .Container {
                width: 95%;
                margin-top: 20%;
                padding: 15px;
            }

            h2 {
                font-size: 1.75rem;
                top: 30px;
                left: 15px;
            }

            p {
                font-size: 1.25rem;
                top: 70px;
                left: 15px;
            }

            .sub-texto {
                font-size: 0.875rem;
                top: 100px;
                left: 15px;
            }

            .seta-voltar {
                font-size: 1.25rem;
                left: 10px;
                top: 10px;
            }

            .img {
                max-width: 150px;
                margin-right: 90%;
            }

            .search-bar input {
                max-width: 100%;
                font-size: 0.875rem;
                padding: 8px 12px;
            }

            .item-table th, .item-table td {
                padding: 8px;
                font-size: 0.75rem;
            }

            .edit-button {
                font-size: 0.75rem;
                padding: 6px 10px;
            }

            .modal-content {
                width: 95%;
                padding: 15px;
            }

            .form-group label {
                font-size: 0.875rem;
            }

            .form-group input,
            .form-group textarea,
            .form-group select {
                font-size: 0.875rem;
                padding: 8px;
            }

            .submit-button {
                font-size: 0.875rem;
                padding: 8px 15px;
                font-family: 'Poppins';
            }

            .close-button {
                font-size: 1.5rem;
            }

            /* Hide less critical columns on small screens */
            .col-observacoes, .col-ano {
                display: none;
            }
        }

        @media screen and (max-width: 480px) {
            .Container {
                margin-top: 25%;
                padding: 10px;
            }

            h2 {
                font-size: 1.5rem;
                top: 15px;
                left: 10px;
            }

            p {
                font-size: 1rem;
                top: 50px;
                left: 10px;
            }

            .sub-texto {
                font-size: 0.75rem;
                top: 75px;
                left: 10px;
            }

            .seta-voltar {
                font-size: 1rem;
                left: 5px;
                top: 5px;
            }

            .img {
                max-width: 120px;
            }

            .search-bar input {
                font-size: 0.75rem;
                padding: 6px 10px;
            }

            .item-table th, .item-table td {
                font-size: 0.7rem;
                padding: 6px;
            }

            .edit-button {
                font-size: 0.7rem;
                padding: 5px 8px;
            }

            .modal-content {
                width: 98%;
                padding: 10px;
            }

            .form-group label {
                font-size: 0.75rem;
            }

            .form-group input,
            .form-group textarea,
            .form-group select {
                font-size: 0.75rem;
                padding: 6px;
            }

            .submit-button {
                font-size: 0.75rem;
                padding: 6px 12px;
            }

            .close-button {
                font-size: 1.25rem;
            }
        }
    </style>

    <script>
        function openEditModal(item) {
            const modal = document.getElementById('editModal');
            document.getElementById('id_bem').value = item.id_bem;
            document.getElementById('nome').value = item.nome;
            document.getElementById('observacoes').value = item.observacoes || '';
            document.getElementById('numero_tombamento').value = item.numero_tombamento;
            document.getElementById('ano_aquisicao').value = item.ano_aquisicao || '';
            document.getElementById('estado_conservacao').value = item.estado_conservacao;
            document.getElementById('valor').value = item.valor || '';
            document.getElementById('setor_id').value = item.setor_id || '';
            modal.style.display = 'block';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#item-table tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>