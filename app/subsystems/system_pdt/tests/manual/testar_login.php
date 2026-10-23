<?php
session_start();
require_once __DIR__ . '/../../app/main/config/connect.php';

$db = new connect();
$pdo = $db->getConnection();

$email = 'teste.professor@gmail.com';
$senha = '123456';

$stmt = $pdo->prepare('SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['id_usuario'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['tipo'] = $usuario['tipo'];
    echo "✅ Login válido!\n";
    echo json_encode($usuario, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo "❌ Credenciais inválidas";
}
?>