<?php
session_start();

require_once __DIR__ . '/../../app/main/config/connect.php';
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

echo "<h1>Criar Usuário Professor de Teste</h1>";

try {
    $model = new usuario_model();
    
    // Dados do usuário
    $email = 'professor@teste.com';
    $senha = '123456';
    $nome = 'Professor Teste';
    $tipo = 'professor';
    $matricula = '9999999'; // Matrícula fictícia
    
    // Tentar criar o usuário
    $result = $model->cadastrar($nome, $matricula, $email, $senha, $tipo);
    
    if ($result) {
        echo "✅ Professor criado com sucesso!<br>";
        echo "Email: " . $email . "<br>";
        echo "Senha: " . $senha . "<br>";
        echo "Nome: " . $nome . "<br>";
        echo "Tipo: " . $tipo . "<br>";
        echo "<br>";
        echo "<p><strong>Use estas credenciais para fazer login:</strong></p>";
        echo "<ul>";
        echo "<li>Email: " . $email . "</li>";
        echo "<li>Senha: " . $senha . "</li>";
        echo "</ul>";
        
    } else {
        echo "❌ Erro ao criar professor<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Exceção: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><a href='../../app/main/index.php'>➜ Ir para Login</a></p>";
?>
