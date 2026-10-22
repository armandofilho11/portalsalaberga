<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/conexao.php';

// Filtros
$filtro =  "";
$params = [];

if (!empty($_GET['filtro_nome'])) {
    $filtro .= " AND b.nome LIKE ?";
    $params[] = "%" . $_GET['filtro_nome'] . "%";
}

if (!empty($_GET['filtro_setor'])) {
    $filtro .= " AND s.id_setor = ?";
    $params[] = $_GET['filtro_setor'];
}

if (!empty($_GET['filtro_estado'])) {
    $filtro .= " AND b.estado_conservacao = ?";
    $params[] = $_GET['filtro_estado'];
}

// Busca setores p/ dropdown e botões
$setores = $pdo->query("SELECT * FROM Setor")->fetchAll(PDO::FETCH_ASSOC);

// Consulta de bens com joins
$sql = "SELECT b.*, s.nome as setor_nome, c.nome as categoria_nome
        FROM Bem b
        JOIN Setor s ON b.setor_id = s.id_setor
        JOIN Categoria c ON b.categoria_id = c.id_categoria
        WHERE b.ativo = 1 AND b.estado_conservacao != 'Lixeira' $filtro";
$stmt = $pdo->prepare("SELECT * 
FROM Bem
WHERE estado_conservacao != 'Lixeira';
");
$stmt->execute($params);
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../js/script.js" defer></script>
<script src="../js/validacoes.js"></script>
    <meta charset="UTF-8">
    <title>Lista de Bens</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Lista de Bens</h2>

    <!-- Filtros -->
    <form method="GET" class="needs-validation" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <input type="text" name="filtro_nome" placeholder="Pesquisar por nome" value="<?= $_GET['filtro_nome'] ?? '' ?>">
        
        <select name="filtro_setor">
            <option value="">Todos os setores</option>
            <?php foreach ($setores as $setor): ?>
                <option value="<?= $setor['id_setor'] ?>" <?= ($_GET['filtro_setor'] ?? '') == $setor['id_setor'] ? 'selected' : '' ?>>
                    <?= $setor['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="filtro_estado">
            <option value="">Todos os estados</option>
            <option value="Ótimo">Ótimo</option>
            <option value="Bom">Bom</option>
            <option value="Ruim">Ruim</option>
            <option value="Inutilizável">Inutilizável</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <!-- Botões rápidos por setor -->
    <div style="margin-top: 20px;">
        <h4>Atalhos por setor:</h4>
        <?php foreach ($setores as $setor): ?>
            <a href="?filtro_setor=<?= $setor['id_setor'] ?>"><button><?= $setor['nome'] ?></button></a>
        <?php endforeach; ?>
    </div>

    <!-- Tabela -->
    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px; width: 100%;">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Nº Tombamento</th>
                <th>Categoria</th>
                <th>Setor</th>
                <th>Estado</th>
                <th>Ano</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bens as $bem): ?>
                <tr>
                    <td><?= $bem['nome'] ?></td>
                    <td><?= $bem['numero_tombamento'] ?></td>
                    <td><?= $bem['categoria_nome'] ?? 'Categoria não definida' ?></td>
                    <td><?= $bem['setor_nome'] ?? 'Setor não definido' ?></td>
                    <td><?= $bem['estado_conservacao'] ?></td>
                    <td><?= $bem['ano_aquisicao'] ?></td>
                    <td>R$ <?= number_format($bem['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="editar_bem.php?id=<?= $bem['id_bem'] ?>">Editar</a> |
                        <a href="../controllers/BemController.php?deletar=<?= $bem['id_bem'] ?>" onclick="return confirm('Deseja mover este item para a Lixeira?')">Excluir</a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><a href="dashboard.php"><button>Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>