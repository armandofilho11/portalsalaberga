<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Excluir Itens - Sistema de Tombamento</title>
</head>
<body>
    <a href="../includes/menu.php" class="seta-voltar">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2>Exclusão</h2>
    <p>de Itens</p>    
    <div class="sub-texto">Aqui você pode excluir seus itens</div>   
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
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "sistema_tombamento";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $sql = "SELECT * FROM bem WHERE estado_conservacao != 'Lixeira'";
                    $stmt = $conn->prepare($sql);
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
                            echo '<td class="col-observacoes">' . htmlspecialchars($row['observacoes']) . '</td>';
                            echo '<td class="col-tombamento">' . htmlspecialchars($row['numero_tombamento']) . '</td>';
                            echo '<td class="col-ano">' . htmlspecialchars($row['ano_aquisicao']) . '</td>';
                            echo '<td class="col-estado">' . htmlspecialchars($row['estado_conservacao']) . '</td>';
                            echo '<td class="col-valor">' . htmlspecialchars($row['valor']) . '</td>';
                            echo '<td class="col-edit"><button class="delete-button" onclick="confirmDelete(' . htmlspecialchars($row['id_bem']) . ')">Excluir</button></td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    }
                } catch(PDOException $e) {
                    echo '<p class="error-message">Erro ao conectar ou consultar o banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
                }

                $conn = null;
                ?>
            </section>
        </main>
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
            color: #007A33;
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
            margin: 0 auto;
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

        .delete-button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #d32f2f;
            color: var(--table-color);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #b71c1c;
            color: var(--table-color);
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
                top: 20px;
                left: 15px;
            }

            p {
                font-size: 1.25rem;
                top: 60px;
                left: 15px;
            }

            .sub-texto {
                font-size: 0.875rem;
                top: 90px;
                left: 15px;
            }

            .seta-voltar {
                font-size: 1.25rem;
                left: 10px;
                top: 10px;
            }

            .img {
                max-width: 150px;
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

            .delete-button {
                font-size: 0.75rem;
                padding: 6px 10px;
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

            .delete-button {
                font-size: 0.7rem;
                padding: 5px 8px;
            }

            /* Hide additional column for very small screens */
            .col-estado {
                display: none;
            }
        }
    </style>

    <script>
        function confirmDelete(id_bem) {
            if (confirm('Tem certeza que deseja excluir o item ' + id_bem + '? Esta ação não pode ser desfeita.')) {
                console.log('Enviando requisição para excluir item: ' + id_bem);
                console.log('URL completa:', window.location.origin + '/sistema-tombamento/app/main/controllers/BemController.php');
                fetch('/sistema-tombamento/app/main/controllers/BemController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_bem=' + encodeURIComponent(id_bem) + '&action=delete'
                })
                .then(response => {
                    console.log('Status da resposta:', response.status);
                    if (!response.ok) {
                        throw new Error('Erro HTTP: ' + response.status);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Resposta bruta do servidor:', text);
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            alert('Item excluído com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro ao excluir o item: ' + (data.error || 'Erro desconhecido'));
                        }
                    } catch (e) {
                        console.error('Erro ao parsear JSON:', e.message);
                        alert('A resposta do servidor não é válida. Verifique o console para detalhes.');
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Erro ao conectar com o servidor: ' + error.message);
                });
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