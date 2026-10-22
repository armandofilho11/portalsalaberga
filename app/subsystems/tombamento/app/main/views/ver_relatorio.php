<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$html = $_SESSION['relatorio_html'] ?? "<p>Nenhum relatório gerado.</p>";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Relatório Gerado</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <?= $html ?>
    <br><br>
    <a href="relatorios.php"><button>⬅ Voltar</button></a>]
    <?php include '../includes/footer.php'; ?>

</body>
</html>