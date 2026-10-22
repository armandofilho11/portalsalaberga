<?php
session_start();
require_once '../includes/conexao.php';

$usuario = ['id_usuario' => '', 'nome' => '', 'login' => '', 'senha' => ''];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE id_usuario = ?");
    $stmt->execute([$_GET['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title><?= $usuario['id_usuario'] ? 'Editar' : 'Novo' ?> Usuário</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 30px;">
    <h2><?= $usuario['id_usuario'] ? 'Editar' : 'Novo' ?> Usuário</h2>

    <form action="../controllers/UsuarioController.php" method="post">
    <form class="needs-validation" ...>
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= $usuario['nome'] ?>" required><br><br>

        <label>Login:</label><br>
        <input type="text" name="login" value="<?= $usuario['login'] ?>" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" <?= !$usuario['id_usuario'] ? 'required' : '' ?>><br><br>

        <button type="submit" name="salvar">Salvar</button>
        <a href="usuarios.php"><button type="button">Cancelar</button></a>
    </form>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
