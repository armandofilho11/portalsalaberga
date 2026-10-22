<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/conexao.php';

$stmt_setores = $pdo->query("SELECT * FROM Setor");
$setores = $stmt_setores->fetchAll(PDO::FETCH_ASSOC);

$stmt_categorias = $pdo->query("SELECT * FROM Categoria");
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Consulta de Bens</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Consulta de Bens</h2>

    <form action="../controllers/ConsultaController.php" method="get">
    <form class="needs-validation" ...>
        <label for="criterio">Critério:</label>
        <select name="criterio" required>
            <option value="">Selecione</option>
            <option value="nome">Nome</option>
            <option value="numero_tombamento">Número de Tombamento</option>
            <option value="estado_conservacao">Estado de Conservação</option>
            <option value="cor">Cor</option>
            <option value="tipo">Tipo</option>
            <option value="setor_id">Setor</option>
            <option value="categoria_id">Categoria</option>
        </select>

        <input type="text" name="valor" placeholder="Digite o valor" required>

        <button type="submit" name="filtrar">🔍 Filtrar</button>
    </form>

    <h3>Atalhos por Setor</h3>
    <?php foreach ($setores as $setor): ?>
        <form action="../controllers/ConsultaController.php" method="get" style="display:inline;">
            <input type="hidden" name="criterio" value="setor_id">
            <input type="hidden" name="valor" value="<?= $setor['id_setor'] ?>">
            <button type="submit">📂 <?= $setor['nome'] ?></button>
        </form>
    <?php endforeach; ?>

    <br>
    <a href="consultas.php"><button>Ver Consultas Salvas</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>