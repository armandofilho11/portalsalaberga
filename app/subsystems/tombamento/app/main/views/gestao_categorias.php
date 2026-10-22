<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/conexao.php';

// Adicionar nova categoria
if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $stmt = $pdo->prepare("INSERT INTO Categoria (nome, descricao) VALUES (?, ?)");
    $stmt->execute([$nome, $descricao]);
    header("Location: gestao_categorias.php");
    exit;
}

// Atualizar categoria
if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $stmt = $pdo->prepare("UPDATE Categoria SET nome = ?, descricao = ? WHERE id_categoria = ?");
    $stmt->execute([$nome, $descricao, $id]);
    header("Location: gestao_categorias.php");
    exit;
}

// Excluir categoria
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM Categoria WHERE id_categoria = ?");
    $stmt->execute([$id]);
    header("Location: gestao_categorias.php");
    exit;
}

// Listar categorias
$categorias = $pdo->query("SELECT * FROM Categoria")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Gestão de Categorias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Gerenciar Categorias</h2>

    <form method="POST">
    <form class="needs-validation" ...>
        <input type="hidden" name="acao" value="adicionar">
        <label>Nome da Categoria:</label>
        <input type="text" name="nome" required>
        <label>Descrição:</label>
        <input type="text" name="descricao">
        <button type="submit">Adicionar</button>
    </form>

    <hr>

    <h3>Categorias Existentes</h3>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" name="id" value="<?= $categoria['id_categoria'] ?>">
                        <td><input type="text" name="nome" value="<?= $categoria['nome'] ?>" required></td>
                        <td><input type="text" name="descricao" value="<?= $categoria['descricao'] ?>"></td>
                        <td>
                            <button type="submit">Salvar</button>
                            <a href="?excluir=<?= $categoria['id_categoria'] ?>" onclick="return confirm('Deseja excluir?')">Excluir</a>
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