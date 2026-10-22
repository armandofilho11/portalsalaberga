<?php
require_once '../includes/conexao.php';

try {
    $stmt = $pdo->prepare("SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
                           FROM Bem
                           LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
                           LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria
                           WHERE estado_conservacao = 'Lixeira'");
    $stmt->execute();
    $bens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erro = "Erro ao consultar os dados: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lixeira</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #FF8C00;
            --text-color: #2D2D2D;
            --bg-color: #f5f5f5;
            --input-bg: #F5F5F5;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --table-bg: #FFFFFF;
            --delete-color: #d32f2f;

        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 850px;
            background-color: var(--table-bg);
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 20px;
            margin: 20px;
        }

        h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
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
            font-size: 16px;
            outline: none;
            box-shadow: 0 2px 5px var(--shadow-color);
        }

        .search-bar input::placeholder {
            color: #888;
        }

        table {
            border: none;
            border-radius: 12px;
            width: 100%;
            border-collapse: collapse;
            background-color: var(--table-bg);
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        th {
            background-color: var(--primary-color);
            color: var(--bg-color);
            font-weight: 600;
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

        tr:hover {
            background-color: #e0e0e0;
        }

        .action-buttons {   
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            font-family: 'Poppins';
            flex: 1;
            min-width: 100px;
        }

        button:hover {
            background-color: #1B5E20;
        }

        .delete-button {
            background-color: var(--delete-color);
        }

        .delete-button:hover {
            background-color: #b71c1c;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-button button {
            background-color: var(--secondary-color);
            color: var(--text-color);
            width: auto;
            min-width: 120px;
        }

        .back-button button:hover {
            background-color: #FFA000;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                padding: 15px;
            }

            th, td {
                padding: 8px;
                font-size: 14px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            button {
                padding: 6px 10px;
                font-size: 12px;
                min-width: auto;
            }

            .search-bar input {
                font-size: 14px;
                padding: 8px 12px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Itens na Lixeira</h2>
       
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Pesquisar itens...">
        </div>
        <?php if (!isset($erro)): ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tombamento</th>
                    <th>Setor</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach ($bens as $bem): ?>
                    <tr>
                        <td><?= htmlspecialchars($bem['nome']) ?></td>
                        <td><?= htmlspecialchars($bem['numero_tombamento']) ?></td>
                        <td><?= htmlspecialchars($bem['setor_nome'] ?? 'Sem setor') ?></td>
                        <td><?= htmlspecialchars($bem['categoria_nome'] ?? 'Sem categoria') ?></td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="confirmRestore(<?= $bem['id_bem'] ?>)">↩ Restaurar</button>
                                <button class="delete-button" onclick="confirmPermanentDelete(<?= $bem['id_bem'] ?>)">🗑 Excluir Permanentemente</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <a href="../includes/menu.php" class="back-button"><button>Voltar</button></a>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).slice(0, 4).map(cell => cell.textContent.toLowerCase()).join(' ');
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });

        function confirmRestore(id_bem) {
            if (confirm('Tem certeza que deseja restaurar o item ' + id_bem + '?')) {
                console.log('Restaurando item: ' + id_bem);
                console.log('URL completa:', window.location.origin + '/sistema-tombamento/app/main/controllers/BemController.php');
                fetch('/sistema-tombamento/app/main/controllers/BemController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=restaurar&id_bem=' + encodeURIComponent(id_bem)
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
                            alert('Item restaurado com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro ao restaurar o item: ' + (data.error || 'Erro desconhecido'));
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

        function confirmPermanentDelete(id_bem) {
            if (confirm('Tem certeza que deseja excluir permanentemente o item ' + id_bem + '? Esta ação não pode ser desfeita.')) {
                console.log('Excluindo permanentemente item: ' + id_bem);
                console.log('URL completa:', window.location.origin + '/sistema-tombamento/app/main/controllers/BemController.php');
                fetch('/sistema-tombamento/app/main/controllers/BemController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=excluir_permanente&id_bem=' + encodeURIComponent(id_bem)
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
                            alert('Item excluído permanentemente com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro aoexcluir o item: ' + (data.error || 'Erro desconhecido'));
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
    </script>
</body>
</html> 