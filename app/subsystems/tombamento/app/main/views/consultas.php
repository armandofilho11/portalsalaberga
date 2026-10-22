<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/conexao.php';

$stmt = $pdo->query("SELECT * FROM Consulta ORDER BY data_consulta DESC");
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Consultas Salvas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Consultas Salvas</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Critério</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consultas as $consulta): ?>
                <tr>
                    <td><?= $consulta['id_consulta'] ?></td>
                    <td><?= $consulta['criterio'] ?></td>
                    <td><?= $consulta['valor'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($consulta['data_consulta'])) ?></td>
                    <td>
                        <a href="ver_consulta.php?id=<?= $consulta['id_consulta'] ?>">
                            <button>Ver</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="consultar.php"><button>Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>