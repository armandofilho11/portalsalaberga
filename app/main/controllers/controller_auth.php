<?php
require_once(__DIR__ . '/../models/model.usuario.php');

$loginUrl = '../views/autenticacao/login.php';
$firstAccessUrl = '../views/autenticacao/primeiro_acesso.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $loginUrl);
    exit();
}

$acao = $_POST['acao'] ?? 'login';
if ($acao === 'verificar_primeiro_acesso') {
    $email = $_POST['email'] ?? '';
    $cpf = $_POST['CPF'] ?? '';
    $csrfToken = $_SESSION['first_access_csrf_token'] ?? '';
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($csrfToken)
        || $csrfToken === ''
        || !is_string($submittedToken)
        || !hash_equals($csrfToken, $submittedToken)
    ) {
        header('Location: ' . $loginUrl . '?primeiro_acesso=sessao_expirada');
        exit();
    }

    if (
        !is_string($email)
        || !filter_var(trim($email), FILTER_VALIDATE_EMAIL)
        || !is_string($cpf)
    ) {
        header('Location: ' . $loginUrl . '?primeiro_acesso=dados_invalidos');
        exit();
    }

    $cpf = preg_replace('/\D+/', '', $cpf);
    if (!is_string($cpf) || strlen($cpf) !== 11) {
        header('Location: ' . $loginUrl . '?primeiro_acesso=dados_invalidos');
        exit();
    }

    $model_usuario = new model_usuario();
    try {
        $usuarioExiste = $model_usuario->verificar_primeiro_acesso($cpf, trim($email));
    } catch (Throwable $e) {
        error_log('Falha ao verificar primeiro acesso: ' . $e->getMessage());
        header('Location: ' . $loginUrl . '?primeiro_acesso=falha');
        exit();
    }

    if (!$usuarioExiste) {
        header('Location: ' . $loginUrl . '?primeiro_acesso=nao_encontrado');
        exit();
    }

    session_regenerate_id(true);
    $_SESSION['primeiro_acesso_email'] = trim($email);
    $_SESSION['primeiro_acesso_cpf'] = $cpf;
    header('Location: ' . $firstAccessUrl);
    exit();
}

if ($acao === 'solicitar_primeiro_acesso') {
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    $csrfToken = $_SESSION['first_access_csrf_token'] ?? '';
    $submittedToken = $_POST['csrf_token'] ?? '';
    $email = $_SESSION['primeiro_acesso_email'] ?? '';
    $cpf = $_SESSION['primeiro_acesso_cpf'] ?? '';

    if (
        !is_string($csrfToken)
        || $csrfToken === ''
        || !is_string($submittedToken)
        || !hash_equals($csrfToken, $submittedToken)
    ) {
        header('Location: ' . $loginUrl . '?primeiro_acesso=sessao_expirada');
        exit();
    }

    if (
        !is_string($email)
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || !is_string($cpf)
        || strlen($cpf) !== 11
        || !is_string($senha)
        || !is_string($confirmarSenha)
    ) {
        header('Location: ' . $loginUrl . '?primeiro_acesso=sessao_expirada');
        exit();
    }

    if (strlen($senha) < 6) {
        header('Location: ' . $firstAccessUrl . '?erro=senha_curta');
        exit();
    }

    if ($senha !== $confirmarSenha) {
        header('Location: ' . $firstAccessUrl . '?erro=senhas_nao_condizem');
        exit();
    }

    $model_usuario = new model_usuario();
    $result = $model_usuario->solicitar_primeiro_acesso($cpf, $email, $senha);

    switch ($result) {
        case 1:
            unset($_SESSION['primeiro_acesso_email'], $_SESSION['primeiro_acesso_cpf']);
            header('Location: ' . $loginUrl . '?primeiro_acesso=enviado');
            exit();
        case 2:
            header('Location: ' . $loginUrl . '?primeiro_acesso=nao_encontrado');
            exit();
        case 3:
            header('Location: ' . $firstAccessUrl . '?erro=pendente');
            exit();
        default:
            header('Location: ' . $firstAccessUrl . '?erro=falha');
            exit();
    }
}

if (
    $acao !== 'login'
    || !isset($_POST['senha'], $_POST['email'])
    || !is_string($_POST['senha'])
    || !is_string($_POST['email'])
    || $_POST['senha'] === ''
    || trim($_POST['email']) === ''
) {
    header('Location: ' . $loginUrl);
    exit();
}

$model_usuario = new model_usuario();
$result = $model_usuario->login(trim($_POST['email']), $_POST['senha']);

switch ($result) {
    case 1:
        header('Location: ../views/subsystems.php');
        exit();
    case 2:
        header('Location: ' . $loginUrl . '?login=erro');
        exit();
    case 3:
        header('Location: ' . $loginUrl . '?login=erro_email');
        exit();
    case 4:
        header('Location: ' . $loginUrl . '?login=erro_senha');
        exit();
    case 5:
        header('Location: ' . $loginUrl . '?login=aguardando_aprovacao');
        exit();
    default:
        header('Location: ' . $loginUrl . '?login=falha');
        exit();
}
