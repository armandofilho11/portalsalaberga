<?php
session_start();
require_once '../includes/conexao.php';

$acao = $_POST['acao'] ?? null;
$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';

if ($acao === 'adicionar') {
    $stmt = $pdo->prepare("INSERT INTO Setor (nome, descricao) VALUES (?, ?)");
    $stmt->execute([$nome, $descricao]);

} elseif ($acao === 'editar' && $id) {
    $stmt = $pdo->prepare("UPDATE Setor SET nome = ?, descricao = ? WHERE id_setor = ?");
    $stmt->execute([$nome, $descricao, $id]);

} elseif ($acao === 'excluir' && $id) {
    $stmt = $pdo->prepare("DELETE FROM Setor WHERE id_setor = ?");
    $stmt->execute([$id]);
}

header("Location: ../views/setores.php");
exit;