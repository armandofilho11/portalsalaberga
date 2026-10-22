<?php
session_start();
require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valida CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['erro'] = "Token inválido.";
        header("Location: ../views/login.php");
        exit;
    }

    $login = filter_var($_POST['login'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['password'];

    try {
        // Busca o usuário pelo login
        $stmt = $pdo->prepare("SELECT id_usuario, nome, senha FROM Usuario WHERE login = ?");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: ../includes/menu.php");
            exit;
        } else {
            // Login falhou
            $_SESSION['erro'] = "Login ou senha incorretos.";
            header("Location: ../views/login.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro: " . $e->getMessage();
        header("Location: ../views/login.php");
        exit;
    }
} else {
    header("Location: ../views/login.php");
    exit;
}
?>