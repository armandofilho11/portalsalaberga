<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

$stmt = $pdo->prepare("SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome 
                       FROM Bem 
                       LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor 
                       LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria");
$stmt->execute();
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Relatório Geral</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">

    <h2>Relatório Geral de Bens</h2>
    <button onclick="window.print()">🖨 Imprimir Relatório</button>
    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tombamento</th>
                <th>Ano</th>
                <th>Setor</th>
                <th>Categoria</th>
                <th>Estado</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bens as $bem): ?>
                <tr>
                    <td><?= $bem['nome'] ?></td>
                    <td><?= $bem['numero_tombamento'] ?></td>
                    <td><?= $bem['ano_aquisicao'] ?></td>
                    <td><?= $bem['setor_nome'] ?></td>
                    <td><?= $bem['categoria_nome'] ?></td>
                    <td><?= $bem['estado_conservacao'] ?></td>
                    <td>R$ <?= number_format($bem['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php"><button>⬅ Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>