<?php

// Verifica se existe um POST email e password e se não está vazio
if (isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    require_once('../models/model.php');
    
    // Cria as variáveis
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Inicia a sessão
    session_start();
    
    // Chama a função logar
    $login = logar($email, $senha);
    
    switch ($login) {
        case 0:
            header('Location: ../views/inicio.php');
            exit();
        case 1:
            header('Location: ../views/inicio_ADM.php');
            exit();
        default:
            header('Location: ../../../main/views/autenticacao/login.php?login=erro');
            exit();
    }
}

// Verifica se o usuário deseja sair
 
if (isset($_GET['sair'])) {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../../../main/views/autenticacao/login.php'); // Redireciona para a página de escolas parceiras
    echo '<script src="../assets/js/clearCache.js"></script>';
    echo '<script>window.location.href = "../../../escolas_parceiras.php";</script>';
    exit();

}


