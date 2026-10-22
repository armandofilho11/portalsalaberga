<?php
session_start();

require_once '../model/model.php';

if (!empty($_POST['nome']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $model = new Model();
    $result = $model->CriarConta($nome, $email, $senha);
    
    if ($result) {
        $_SESSION['mensagem_sucesso'] = "Conta criada com sucesso!";
        header("Location: ../index.php");
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao criar conta.";
        header("Location: ../view/Criarconta.php");
    }
    exit();
}

$_SESSION['mensagem_erro'] = "Todos os campos são obrigatórios.";
header("Location: ../view/Criarconta.php");
exit(); 