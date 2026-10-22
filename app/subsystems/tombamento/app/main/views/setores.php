<?php
require_once '../includes/conexao.php';

$stmt = $pdo->query("SELECT * FROM Setor ORDER BY nome");
$setores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Setores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --background-color: #F5F5F5;
            --table-color: #FFFFFF;
            --primary-color: #2E7D32;
            --secondary-color: #FF8C00;
            --text-color: #2D2D2D;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --input-bg: #F5F5F5;
            --table-bg: #F1F1F1;
            --danger-color: #DC3545;
        }
        .seta-voltar {
            position: absolute;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            right: 96%;
            top: 15px;
            bottom: 1090px;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: var(--table-color);
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 30px;
            margin: 50px auto;
        }

        /* Formulário de Adição e Edição */
        .add-form, .edit-form {
            background-color: var(--input-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .add-form label, .edit-form label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .add-form input, .edit-form input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background-color: #FFFFFF;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            margin-bottom: 15px;
        }


        .add-form button, .edit-form button {
            width: 100%;
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .add-form button:hover, .edit-form button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .add-form button:active, .edit-form button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Botão Cancelar no Formulário de Edição */
        .edit-form button.cancel {
            background-color: #6C757D;
            margin-top: 10px;
        }

        .edit-form button.cancel:hover {
            background-color: #5A6268;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--table-color);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #E0E0E0;
        }

        th {
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            background-color: #FFFFFF;
            font-size: 14px;
            color: var(--text-color);
        }

        /* Botões da Tabela */
        td button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
            margin-right: 5px;
        }

        td button.edit {
            background-color: var(--primary-color);
            color: #FFFFFF;
        }

        td button.edit:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        td button.delete {
            background-color: var(--danger-color);
            color: #FFFFFF;
        }

        td button.delete:hover {
            background-color: #C82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        td button:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .relatorio{
            margin-top: 8px;
        }

        /* Botão Voltar */
        a button {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        a button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        a button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Esconder Formulário de Edição Inicialmente */
        .edit-form {
            display: none;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 30px auto;
            }

            h2 {
                font-size: 24px;
            }

            .add-form, .edit-form {
                padding: 15px;
            }

            .add-form input, .edit-form input,
            .add-form button, .edit-form button {
                font-size: 13px;
                padding: 8px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 10px;
                font-size: 13px;
            }

            td button {
                padding: 6px 10px;
                font-size: 12px;
            }

            a button {
                padding: 8px 15px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <a href="../includes/menu.php" class="seta-voltar">
    <i class="bi bi-arrow-left"></i>
</a>
    <div class="container">
        <h2>Adicionar setores</h2>
        <form action="../controllers/SetorController.php" method="post" class="add-form">
            <input type="hidden" name="acao" value="adicionar">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required placeholder="Digite o nome do setor">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" placeholder="Digite a descrição (opcional)">
            <button type="submit">Adicionar</button>
        </form>
          <div class="relatorio">
            <a href="relatorio_setor.php"> <button>Gerar relatorio</button></a>
    </div>
        <form action="../controllers/SetorController.php" method="post" class="edit-form" id="edit-form">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id" id="edit-id">
            <label for="edit-nome">Nome:</label>
            <input type="text" id="edit-nome" name="nome" required placeholder="Digite o nome do setor">
            <label for="edit-descricao">Descrição:</label>
            <input type="text" id="edit-descricao" name="descricao" placeholder="Digite a descrição (opcional)">
            <button type="submit">Salvar</button>
            <button type="button" class="cancel" onclick="hideEditForm()">Cancelar</button>
            
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($setores as $setor): ?>
                    <tr>
                        <td><?= htmlspecialchars($setor['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($setor['descricao'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <button class="edit" onclick="showEditForm(<?= $setor['id_setor'] ?>, '<?= htmlspecialchars($setor['nome'], ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($setor['descricao'], ENT_QUOTES, 'UTF-8') ?>')">Editar</button>
                            <form action="../controllers/SetorController.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $setor['id_setor'] ?>">
                                <input type="hidden" name="acao" value="excluir">
                                <button type="submit" class="delete" onclick="return confirm('Excluir setor?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    <script>
        function showEditForm(id, nome, descricao) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nome').value = nome;
            document.getElementById('edit-descricao').value = descricao;
            document.getElementById('edit-form').style.display = 'block';
            window.scrollTo({ top: document.getElementById('edit-form').offsetTop, behavior: 'smooth' });
        }

        function hideEditForm() {
            document.getElementById('edit-form').style.display = 'none';
            document.getElementById('edit-form').reset();
        }
    </script>
</body>
</html>