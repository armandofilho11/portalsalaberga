<?php
session_start();


class sessions
{

    function autenticar_session()
    {
        // DEBUG: Remova depois de testar!
        // error_log('SESSION: ' . print_r($_SESSION, true));

        if (!isset($_SESSION['email']) && !isset($_SESSION['id'])) {
            unset($_SESSION['login']);
            unset($_SESSION['status']);
            header('location:../views/login.php');
            exit();
        } 
        
        // $logado = $_SESSION['Login']; // Só use se for necessário
    }
    function tempo_session($tempo = 600)
    {
        if (isset($_SESSION['ultimo_acesso'])) {
            if (time() - $_SESSION['ultimo_acesso'] > $tempo) {
                session_unset();
                session_destroy();
                header('location:../views/login.php');
                exit();
            }
        }
        $_SESSION['ultimo_acesso'] = time();
    }
    function quebra_session()
    {
        session_unset();
        session_destroy();
        header('location:../index.php');
        exit();
    }
}