<?php
require_once __DIR__ . '/../../app/main/config/connect.php';
$db = new connect();
$pdo = $db->getConnection();

$email = 'teste.professor@gmail.com';
$senha = '123456';
$nome = 'Professor Teste';
$matricula = '9999999';
$tipo = 'pdt';

$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO usuarios (nome, matricula, email, senha, tipo) VALUES (?, ?, ?, ?, ?)');
$result = $stmt->execute([$nome, $matricula, $email, $hash, $tipo]);

if ($result) {
    echo "✅ Usuário criado: $email com senha: $senha";
} else {
    echo "❌ Erro ao criar usuário";
    print_r($stmt->errorInfo());
}
?>