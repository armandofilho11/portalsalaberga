<?php
session_start();

require_once __DIR__ . '/../../app/main/config/connect.php';
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

echo "<h1>Testar Autenticação</h1>";

$model = new usuario_model();

// Tentar fazer login
$result = $model->login('professor@teste.com', '123456');

if ($result['status'] === 1 && $result['usuario']) {
    $usuario = $result['usuario'];
    echo "✅ Login realizado com sucesso!<br>";
    echo "ID do usuário: " . $usuario['id'] . "<br>";
    echo "Nome: " . $usuario['nome'] . "<br>";
    echo "Email: " . $usuario['email'] . "<br>";
    echo "Tipo: " . $usuario['tipo'] . "<br>";
    
    // Simular login
    $_SESSION['id_usuario'] = $usuario['id'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo'] = $usuario['tipo'];
    
    echo "<br>✅ Sessão iniciada com sucesso!<br>";
    echo "<p><a href='../../app/main/views/professor.php'>➜ Ir para Painel Professor</a></p>";
} else {
    echo "❌ Erro ao fazer login. Status: " . $result['status'] . "<br>";
}
?>
