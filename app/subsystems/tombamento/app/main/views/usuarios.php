<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/conexao.php';

$stmt = $pdo->query("SELECT * FROM Usuario");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>

    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 30px;">
    <h2>Usuários</h2>
    <a href="form_usuario.php"><button>+ Novo Usuário</button></a>
    <br><br>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Login</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['nome'] ?></td>
                    <td><?= $usuario['login'] ?></td>
                    <td>
                        <a href="form_usuario.php?id=<?= $usuario['id_usuario'] ?>">Editar</a> |
                        <a href="../controllers/UsuarioController.php?excluir=<?= $usuario['id_usuario'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="index.php"><button>Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>