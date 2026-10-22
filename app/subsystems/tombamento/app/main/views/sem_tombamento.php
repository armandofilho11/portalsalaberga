<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

// Buscar itens que estão com número de tombamento em branco ou NULL
$stmt = $pdo->prepare("SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
                       FROM Bem
                       LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
                       LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria
                       WHERE numero_tombamento IS NULL OR numero_tombamento = ''");
$stmt->execute();
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>

    <meta charset="UTF-8">
    <title>Itens sem Tombamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">

    <h2>Itens sem Número de Tombamento</h2>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Setor</th>
                <th>Categoria</th>
                <th>Estado</th>
                <th>Valor</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bens as $bem): ?>
                <tr>
                    <td><?= $bem['nome'] ?></td>
                    <td><?= $bem['setor_nome'] ?></td>
                    <td><?= $bem['categoria_nome'] ?></td>
                    <td><?= $bem['estado_conservacao'] ?></td>
                    <td>R$ <?= number_format($bem['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="editar_bem.php?id=<?= $bem['id_bem'] ?>">
                            <button>✏️ Editar</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php"><button>⬅ Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>