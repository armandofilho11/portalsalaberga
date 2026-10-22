<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

if (!isset($_GET['setor_id'])) {
    echo "Setor não especificado.";
    exit;
}

$setor_id = $_GET['setor_id'];

$stmt = $pdo->prepare("SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
                       FROM Bem
                       LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
                       LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria
                       WHERE Bem.setor_id = ?");
$stmt->execute([$setor_id]);
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$setorNome = $bens[0]['setor_nome'] ?? 'Setor não encontrado';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Itens do Setor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Itens do setor: <?= htmlspecialchars($setorNome) ?></h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tombamento</th>
                <th>Categoria</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bens as $bem): ?>
                <tr>
                    <td><?= $bem['nome'] ?></td>
                    <td><?= $bem['numero_tombamento'] ?></td>
                    <td><?= $bem['categoria_nome'] ?></td>
                    <td><?= $bem['estado_conservacao'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="atalhos_setores.php"><button>⬅ Voltar aos Setores</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>