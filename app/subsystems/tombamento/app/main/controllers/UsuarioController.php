<?php
session_start();
require_once '../includes/conexao.php';

if (isset($_POST['salvar'])) {
    // Valida CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['erro'] = "Token inválido.";
        header("Location: ../views/register.php");
        exit;
    }

    $id = $_POST['id_usuario'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($login) || (empty($senha) && !$id)) {
        $_SESSION['erro'] = "Todos os campos são obrigatórios.";
        header("Location: ../views/register.php");
        exit;
    }

    try {
        if ($id) {
            // Atualização
            if (!empty($senha)) {
                $stmt = $pdo->prepare("UPDATE Usuario SET nome = ?, login = ?, senha = ? WHERE id_usuario = ?");
                $stmt->execute([$nome, $login, password_hash($senha, PASSWORD_DEFAULT), $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE Usuario SET nome = ?, login = ? WHERE id_usuario = ?");
                $stmt->execute([$nome, $login, $id]);
            }
        } else {
            // Cadastro: verifica duplicatas
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Usuario WHERE login = ?");
            $stmt->execute([$login]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['erro'] = "Este email já está cadastrado.";
                header("Location: ../views/register.php");
                exit;
            }
            $stmt = $pdo->prepare("INSERT INTO Usuario (nome, login, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $login, password_hash($senha, PASSWORD_DEFAULT)]);
        }
        header("Location: ../views/login.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro: " . $e->getMessage();
        header("Location: ../views/register.php");
        exit;
    }
}

if (isset($_GET['excluir'])) {
    $id = filter_var($_GET['excluir'], FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Usuario WHERE id_usuario = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                $stmt = $pdo->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
                $stmt->execute([$id]);
            }
            header("Location: ../views/usuarios.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../views/usuarios.php");
            exit;
        }
    }
    header("Location: ../views/usuarios.php");
    exit;
}
?>