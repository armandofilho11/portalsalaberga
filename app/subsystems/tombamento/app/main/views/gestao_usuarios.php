<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

// Adicionar novo usuário
if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Segurança
    $stmt = $pdo->prepare("INSERT INTO Usuario (nome, login, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $login, $senha]);
    header("Location: gestao_usuarios.php");
    exit;
}

// Atualizar usuário
if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;

    if ($senha) {
        $stmt = $pdo->prepare("UPDATE Usuario SET nome = ?, login = ?, senha = ? WHERE id_usuario = ?");
        $stmt->execute([$nome, $login, $senha, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE Usuario SET nome = ?, login = ? WHERE id_usuario = ?");
        $stmt->execute([$nome, $login, $id]);
    }

    header("Location: gestao_usuarios.php");
    exit;
}

// Excluir usuário
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
    $stmt->execute([$id]);
    header("Location: gestao_usuarios.php");
    exit;
}

// Listar usuários
$usuarios = $pdo->query("SELECT * FROM Usuario")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Gestão de Usuários</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Gerenciar Usuários</h2>

    <form method="POST">
    <form class="needs-validation" ...>
        <input type="hidden" name="acao" value="adicionar">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Login:</label>
        <input type="text" name="login" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Adicionar</button>
    </form>

    <hr>

    <h3>Usuários Cadastrados</h3>
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
                    <form method="POST">
                    <form class="needs-validation" ...>
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" name="id" value="<?= $usuario['id_usuario'] ?>">
                        <td><input type="text" name="nome" value="<?= $usuario['nome'] ?>" required></td>
                        <td><input type="text" name="login" value="<?= $usuario['login'] ?>" required></td>
                        <td>
                            <input type="password" name="senha" placeholder="Nova senha (opcional)">
                            <button type="submit">Salvar</button>
                            <a href="?excluir=<?= $usuario['id_usuario'] ?>" onclick="return confirm('Excluir usuário?')">Excluir</a>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php"><button>Voltar ao Painel</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>