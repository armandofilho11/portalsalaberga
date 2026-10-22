<?php
require_once("../model/Usuario.class.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $tipo_usuario = isset($_POST['userType']) ? trim($_POST['userType']) : '';
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['login']) ? trim($_POST['login']) : '';
    $senha = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirmar_senha = isset($_POST['confirmar_senha']) ? trim($_POST['confirmar_senha']) : '';

    // Validate inputs
    if (empty($tipo_usuario) || empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        header("Location: ../view/cadastro.php?error=Preencha todos os campos");
        exit();
    }

    // Validate user type
    $valid_types = ['aluno', 'administrador'];
    if (!in_array($tipo_usuario, $valid_types)) {
        header("Location: ../view/cadastro.php?error=Tipo de usuário inválido");
        exit();
    }

    // Validate password match
    if ($senha !== $confirmar_senha) {
        header("Location: ../view/cadastro.php?error=As senhas não coincidem");
        exit();
    }

    try {
        $usuario = new Usuario();
        $result = $usuario->cadastrarUsuario($tipo_usuario, $nome, $email, $senha);
        if ($result) {
            header("Location: ../view/login.php?success=Cadastro realizado com sucesso");
        } else {
            header("Location: ../view/cadastro.php?error=Erro ao cadastrar");
        }
    } catch (Exception $e) {
        header("Location: ../view/cadastro.php?error=Erro: " . urlencode($e->getMessage()));
    }
    exit();
} else {
    header("Location: ../view/cadastro.php");
    exit();
}
?>