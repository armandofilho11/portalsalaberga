<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/conexao.php';

$stmt = $pdo->query("SELECT * FROM Categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Categorias</h2>

    <form action="../controllers/CategoriaController.php" method="post">
    <form class="needs-validation" ...>

        <input type="text" name="nome" placeholder="Nome da Categoria" required>
        <textarea name="descricao" placeholder="Descrição (opcional)"></textarea>
        <button type="submit" name="salvar">Salvar</button>
    </form>

    <h3>Lista de Categorias</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?= $categoria['nome'] ?></td>
                <td><?= $categoria['descricao'] ?></td>
                <td>
                    <form action="../controllers/CategoriaController.php" method="post" style="display:inline;">
                        <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
                        <button type="submit" name="excluir">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="dashboard.php"><button>⬅ Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>