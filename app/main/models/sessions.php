<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class sessions
{
    function autenticar_session()
    {
        try {
            if (!isset($_SESSION['email']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {

                session_unset();
                session_destroy();
                header('location:../views/autenticacao/login.php');
                exit();
            }
        } catch (PDOException $e) {
            header('location: ../views/windows/faltal_erro.php');
            exit();
        }
    }

    function tempo_session($tempo = 600)
    {
        try {
            if (isset($_SESSION['ultimo_acesso'])) {

                if (time() - $_SESSION['ultimo_acesso'] > $tempo) {

                    session_unset();
                    session_destroy();
                    header('location:../views/autenticacao/login.php');
                    exit();
                }
            }
            $_SESSION['ultimo_acesso'] = time();
        } catch (PDOException $e) {
            header('location: ../views/windows/faltal_erro.php');
            exit();
        }
    }

    function deslogar()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/app/main/models/sessions.php');
        $mainPath = dirname(dirname($scriptPath));
        header('Location: ' . rtrim($mainPath, '/') . '/views/autenticacao/login.php');
        exit();
    }
}

if (isset($_GET['sair'])) {

    $session = new sessions();
    $session->deslogar();
}
