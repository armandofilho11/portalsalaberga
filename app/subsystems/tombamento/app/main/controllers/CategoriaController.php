<?php
require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['salvar'])) {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];

        $stmt = $pdo->prepare("INSERT INTO Categoria (nome, descricao) VALUES (?, ?)");
        $stmt->execute([$nome, $descricao]);

        header("Location: ../views/categorias.php");
        exit;
    }

    if (isset($_POST['excluir']) && isset($_POST['id_categoria'])) {
        $id = $_POST['id_categoria'];

        $stmt = $pdo->prepare("DELETE FROM Categoria WHERE id_categoria = ?");
        $stmt->execute([$id]);

        header("Location: ../views/categorias.php");
        exit;
    }
}