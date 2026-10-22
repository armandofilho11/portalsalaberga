<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $usuario = Usuario::autenticar($login, $senha);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        header("Location: ../views/login.php?erro=1");
        exit;
    }
}
?>