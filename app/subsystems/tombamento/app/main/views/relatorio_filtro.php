<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../models/Relatorio.php';
require_once '../models/Setor.php';
require_once '../models/Categoria.php';

$setores = Setor::listar();
$categorias = Categoria::listar();

$filtros = $_GET ?? [];
$relatorio = Relatorio::gerarRelatorio($filtros);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>

    <meta charset="UTF-8">
    <title>Relatório com Filtros</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Relatório com Filtros</h2>

    <form method="GET">
    <form class="needs-validation" ...>
        <label>Setor:</label>
        <select name="setor">
            <option value="">Todos</option>
            <?php foreach ($setores as $setor): ?>
                <option value="<?= $setor['id_setor'] ?>" <?= ($filtros['setor'] ?? '') == $setor['id_setor'] ? 'selected' : '' ?>>
                    <?= $setor['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Categoria:</label>
        <select name="categoria">
            <option value="">Todas</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria'] ?>" <?= ($filtros['categoria'] ?? '') == $categoria['id_categoria'] ? 'selected' : '' ?>>
                    <?= $categoria['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Estado:</label>
        <select name="estado">
            <option value="">Todos</option>
            <option value="Ruim" <?= ($filtros['estado'] ?? '') == 'Ruim' ? 'selected' : '' ?>>Ruim</option>
            <option value="Bom" <?= ($filtros['estado'] ?? '') == 'Bom' ? 'selected' : '' ?>>Bom</option>
            <option value="Ótimo" <?= ($filtros['estado'] ?? '') == 'Ótimo' ? 'selected' : '' ?>>Ótimo</option>
            <option value="Inutilizável" <?= ($filtros['estado'] ?? '') == 'Inutilizável' ? 'selected' : '' ?>>Inutilizável</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <br>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Nº Tombamento</th>
                <th>Setor</th>
                <th>Categoria</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($relatorio as $bem): ?>
                <tr>
                    <td><?= $bem['nome'] ?></td>
                    <td><?= $bem['numero_tombamento'] ?></td>
                    <td><?= $bem['setor_nome'] ?></td>
                    <td><?= $bem['categoria_nome'] ?></td>
                    <td><?= $bem['estado_conservacao'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="listar_bens.php"><button>Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>
