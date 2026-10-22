<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Tombamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body style="display: block; padding: 40px;">

    <h2>Bem-vindo, <?php echo $_SESSION['usuario']['nome']; ?>!</h2>

    <div style="margin-top: 20px;">
        <a href="listar_bens.php"><button>Ver todos os bens</button></a>
        <a href="cadastrar_bem.php"><button>Cadastrar novo bem</button></a>
        <a href="lixeira.php"><button>Lixeira</button></a>
        <a href="logout.php"><button style="background-color: darkred;">Sair</button></a>
    </div>
    <?php include '../includes/footer.php'; ?>

</body>
</html>