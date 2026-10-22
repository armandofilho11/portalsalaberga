<?php
session_start();
require_once("../model/Usuario.class.php");

if (isset($_POST['submit'])) {
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['password'] ?? '');

    // Basic validation
    if (empty($login) || empty($senha)) {
        error_log("Login attempt failed: Empty login or password");
        header("Location: ../view/login.php?error=Login e senha são obrigatórios");
        exit();
    }

    $usuario = new Usuario();
    $resultado = $usuario->login($login, $senha);

    if ($resultado) {
        // Set session data
        $_SESSION['usuario'] = [
            'id' => $resultado['id'],
            'email' => $resultado['email'],
            'tipo_usuario' => $resultado['tipo_usuario'],
            'nome' => $resultado['nome'],
            'profile_photo' => $resultado['profile_photo'] ?? null
        ];
        error_log("Login successful, session set for user: " . $resultado['email']);

        // Redirect based on user type
        switch (strtolower($resultado['tipo_usuario'])) {
            case 'aluno':
                header("Location: ../view/sistemaAluno.php");
                break;
            case 'administrador':
                header("Location: ../view/sistemaAdministrador.php");
                break;
            default:
                error_log("Invalid user type for: " . $resultado['email']);
                header("Location: ../view/login.php?error=Tipo de usuário inválido");
                break;
        }
        exit();
    } else {
        error_log("Login failed for: $login");
        header("Location: ../view/login.php?error=Login ou senha inválidos");
        exit();
    }
} else {
    error_log("Unauthorized access attempt to controlLogin.php");
    header("Location: ../view/login.php?error=Acesso não autorizado");
    exit();
}
?>