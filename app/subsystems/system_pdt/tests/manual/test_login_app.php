<?php
session_start();
require_once __DIR__ . '/../../app/main/config/connect.php';
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

$modelo = new UsuarioModel();

// Tentar fazer login com credenciais de teste
$resultado = $modelo->login('professor@teste.com', '123456');

if ($resultado['sucesso']) {
    $_SESSION['id_usuario'] = $resultado['id'];
    $_SESSION['tipo'] = $resultado['tipo'];
    $_SESSION['nome'] = $resultado['nome'];
    $_SESSION['email'] = $resultado['email'];
    header('Location: ../views/professor.php');
} else {
    echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px; margin: 20px;">';
    echo '<h3>Erro no login</h3>';
    echo '<p>' . $resultado['erro'] . '</p>';
    echo '<p><a href="../../app/main/index.php">Voltar para o login</a></p>';
    echo '</div>';
}
?>
