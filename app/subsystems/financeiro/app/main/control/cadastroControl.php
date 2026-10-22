<?php
require_once '../model/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $usuario = new Usuario();

    try {
        if ($usuario->cadastrar($nome, $email, $senha)) {
            $_SESSION['sucesso_cadastro'] = "Usuário cadastrado com sucesso!";
        } else {
            $_SESSION['erro_cadastro'] = "Erro ao cadastrar usuário.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['erro_cadastro'] = "E-mail já cadastrado.";
        } else {
            $_SESSION['erro_cadastro'] = "Erro no banco de dados.";
        }
    }

    header('Location: ../view/cadastro.php');
    exit;
}
