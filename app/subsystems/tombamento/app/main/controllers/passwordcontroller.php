<?php
session_start();
require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['acao'])) {
    header("Location: ../views/forgot-password.php");
    exit;
}

// Valida CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['erro'] = "Token inválido.";
    header("Location: ../views/" . ($_POST['acao'] === 'verificar' ? 'forgot-password.php' : 'reset-password.php'));
    exit;
}

if ($_POST['acao'] === 'verificar') {
    $login = filter_var($_POST['login'] ?? '', FILTER_SANITIZE_EMAIL);
    $nome = filter_var($_POST['nome'] ?? '', FILTER_SANITIZE_STRING);

    if (empty($login) || empty($nome)) {
        $_SESSION['erro'] = "Por favor, preencha todos os campos.";
        header("Location: ../views/forgot-password.php");
        exit;
    }

    try {
        // Verifica se o email e o nome correspondem a um usuário
        $stmt = $pdo->prepare("SELECT id_usuario FROM Usuario WHERE login = ? AND nome = ?");
        $stmt->execute([$login, $nome]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['erro'] = "Email ou nome incorretos.";
            header("Location: ../views/forgot-password.php");
            exit;
        }

        // Armazena o ID do usuário na sessão para a redefinição
        $_SESSION['reset_user_id'] = $usuario['id_usuario'];
        header("Location: ../views/reset-password.php");
        exit;
    } catch (PDOException $e) {
        error_log("Erro PDO: " . $e->getMessage());
        $_SESSION['erro'] = "Erro no sistema. Tente novamente mais tarde.";
        header("Location: ../views/forgot-password.php");
        exit;
    }
} elseif ($_POST['acao'] === 'redefinir') {
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if (empty($senha) || empty($confirmar_senha)) {
        $_SESSION['erro'] = "Todos os campos são obrigatórios.";
        header("Location: ../views/reset-password.php");
        exit;
    }

    if ($senha !== $confirmar_senha) {
        $_SESSION['erro'] = "As senhas não coincidem.";
        header("Location: ../views/reset-password.php");
        exit;
    }

    if (strlen($senha) < 6) {
        $_SESSION['erro'] = "A senha deve ter pelo menos 6 caracteres.";
        header("Location: ../views/reset-password.php");
        exit;
    }

    if (!isset($_SESSION['reset_user_id'])) {
        $_SESSION['erro'] = "Acesso não autorizado. Por favor, verifique seu email e nome primeiro.";
        header("Location: ../views/forgot-password.php");
        exit;
    }

    try {
        // Atualiza a senha
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE Usuario SET senha = ? WHERE id_usuario = ?");
        $stmt->execute([$hash, $_SESSION['reset_user_id']]);

        // Limpa a sessão
        unset($_SESSION['reset_user_id']);
        $_SESSION['sucesso'] = "Senha alterada com sucesso. Faça login com a nova senha.";
        header("Location: ../views/login.php");
        exit;
    } catch (PDOException $e) {
        error_log("Erro PDO: " . $e->getMessage());
        $_SESSION['erro'] = "Erro no sistema. Tente novamente mais tarde.";
        header("Location: ../views/reset-password.php");
        exit;
    }
}
?>