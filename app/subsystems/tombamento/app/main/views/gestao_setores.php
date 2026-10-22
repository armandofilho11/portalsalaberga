<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

// Adicionar novo setor
if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $stmt = $pdo->prepare("INSERT INTO Setor (nome, descricao) VALUES (?, ?)");
    $stmt->execute([$nome, $descricao]);
    header("Location: gestao_setores.php");
    exit;
}

// Atualizar setor
if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $stmt = $pdo->prepare("UPDATE Setor SET nome = ?, descricao = ? WHERE id_setor = ?");
    $stmt->execute([$nome, $descricao, $id]);
    header("Location: gestao_setores.php");
    exit;
}

// Excluir setor
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM Setor WHERE id_setor = ?");
    $stmt->execute([$id]);
    header("Location: gestao_setores.php");
    exit;
}

// Listar setores
$setores = $pdo->query("SELECT * FROM Setor")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Gestão de Setores</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Gerenciar Setores</h2>

    <form method="POST">
    <form class="needs-validation" ...>
        <input type="hidden" name="acao" value="adicionar">
        <label>Nome do Setor:</label>
        <input type="text" name="nome" required>
        <label>Descrição:</label>
        <input type="text" name="descricao">
        <button type="submit">Adicionar</button>
    </form>

    <hr>

    <h3>Setores Existentes</h3>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($setores as $setor): ?>
                <tr>
                    <form method="POST">
                    <form class="needs-validation" ...>
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" name="id" value="<?= $setor['id_setor'] ?>">
                        <td><input type="text" name="nome" value="<?= $setor['nome'] ?>" required></td>
                        <td><input type="text" name="descricao" value="<?= $setor['descricao'] ?>"></td>
                        <td>
                            <button type="submit">Salvar</button>
                            <a href="?excluir=<?= $setor['id_setor'] ?>" onclick="return confirm('Deseja excluir?')">Excluir</a>
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