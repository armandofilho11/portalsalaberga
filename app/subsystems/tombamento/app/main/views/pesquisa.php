<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

$filtros = [];
$params = [];

if (!empty($_GET['nome'])) {
    $filtros[] = "Bem.nome LIKE ?";
    $params[] = '%' . $_GET['nome'] . '%';
}
if (!empty($_GET['setor'])) {
    $filtros[] = "Setor.nome = ?";
    $params[] = $_GET['setor'];
}
if (!empty($_GET['estado'])) {
    $filtros[] = "Bem.estado_conservacao = ?";
    $params[] = $_GET['estado'];
}
if (!empty($_GET['tipo'])) {
    $filtros[] = "Categoria.nome = ?";
    $params[] = $_GET['tipo'];
}
if (!empty($_GET['cor'])) {
    $filtros[] = "Bem.observacoes LIKE ?";
    $params[] = '%' . $_GET['cor'] . '%';
}

$sql = "SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome 
        FROM Bem 
        LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor 
        LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria";

if ($filtros) {
    $sql .= " WHERE " . implode(" AND ", $filtros);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar os setores e categorias para o filtro
$setores = $pdo->query("SELECT nome FROM Setor")->fetchAll(PDO::FETCH_COLUMN);
$tipos = $pdo->query("SELECT nome FROM Categoria")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Pesquisar Bens</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Pesquisar Itens</h2>

    <form method="get">
    <form class="needs-validation" ...>
        <input type="text" name="nome" placeholder="Pesquisar por nome" value="<?= $_GET['nome'] ?? '' ?>">

        <select name="setor">
            <option value="">Todos os setores</option>
            <?php foreach ($setores as $setor): ?>
                <option value="<?= $setor ?>" <?= ($_GET['setor'] ?? '') === $setor ? 'selected' : '' ?>>
                    <?= $setor ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="estado">
            <option value="">Todos os estados</option>
            <option value="Ótimo" <?= ($_GET['estado'] ?? '') === 'Ótimo' ? 'selected' : '' ?>>Ótimo</option>
            <option value="Bom" <?= ($_GET['estado'] ?? '') === 'Bom' ? 'selected' : '' ?>>Bom</option>
            <option value="Ruim" <?= ($_GET['estado'] ?? '') === 'Ruim' ? 'selected' : '' ?>>Ruim</option>
            <option value="Inutilizável" <?= ($_GET['estado'] ?? '') === 'Inutilizável' ? 'selected' : '' ?>>Inutilizável</option>
        </select>

        <select name="tipo">
            <option value="">Todos os tipos</option>
            <?php foreach ($tipos as $tipo): ?>
                <option value="<?= $tipo ?>" <?= ($_GET['tipo'] ?? '') === $tipo ? 'selected' : '' ?>>
                    <?= $tipo ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="cor" placeholder="Cor (palavra na observação)" value="<?= $_GET['cor'] ?? '' ?>">

        <button type="submit">🔍 Filtrar</button>
    </form>

    <h3>Resultados</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Nome</th>
            <th>Tombamento</th>
            <th>Setor</th>
            <th>Categoria</th>
            <th>Estado</th>
        </tr>
        <?php foreach ($bens as $bem): ?>
            <tr>
                <td><?= $bem['nome'] ?></td>
                <td><?= $bem['numero_tombamento'] ?></td>
                <td><?= $bem['setor_nome'] ?></td>
                <td><?= $bem['categoria_nome'] ?></td>
                <td><?= $bem['estado_conservacao'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="dashboard.php"><button>⬅ Voltar</button></a>
</body>
</html>
