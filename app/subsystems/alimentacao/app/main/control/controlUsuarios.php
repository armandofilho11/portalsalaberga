<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
    header("Location: ../view/login.php?error=Acesso não autorizado");
    exit();
}

require_once '../model/usuario.class.php';
$usuarioModel = new Usuario();

$action = isset($_POST['action']) ? $_POST['action'] : '';

header('Content-Type: application/json');

try {
    if ($action === 'edit') {
        $originalEmail = $_POST['original_email'];
        $nome = $_POST['nome'];
        $email = strtolower($_POST['email']);
        $tipo_usuario = $_POST['tipo_usuario'];
        $senha = $_POST['senha'] ?: null;

        $validTypes = ['aluno', 'nutricionista', 'administrador'];
        if (!in_array($tipo_usuario, $validTypes)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de usuário inválido']);
            exit();
        }

        if ($usuarioModel->emailExists($email, $originalEmail)) {
            echo json_encode(['success' => false, 'message' => 'E-mail já está em uso por outro usuário']);
            exit();
        }

        $updated = $usuarioModel->atualizarUsuario($originalEmail, $nome, $email, $tipo_usuario, $senha);

        if ($updated) {
            if ($originalEmail === $_SESSION['usuario']['email']) {
                $_SESSION['usuario']['nome'] = $nome;
                $_SESSION['usuario']['email'] = $email;
                $_SESSION['usuario']['tipo_usuario'] = $tipo_usuario;
                if ($senha) {
                    $_SESSION['usuario']['senha'] = $senha;
                }
            }
            echo json_encode(['success' => true, 'message' => 'Usuário atualizado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma alteração feita ou usuário não encontrado']);
        }
    } elseif ($action === 'delete') {
        $email = $_POST['email'];

        if ($email === $_SESSION['usuario']['email']) {
            echo json_encode(['success' => false, 'message' => 'Você não pode excluir sua própria conta']);
            exit();
        }

        $usuarioModel->excluirUsuario($email);
        echo json_encode(['success' => true, 'message' => 'Usuário excluído com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao processar a solicitação: ' . $e->getMessage()]);
}
?>