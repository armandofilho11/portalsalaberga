<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getDatabaseConnection();
} catch (PDOException $e) {
    error_log("[LoginResponsavelController] Erro na conexão com o banco: " . $e->getMessage());
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Erro no servidor']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    error_log("[LoginResponsavelController] Tentativa de login: Email=$email");

    if ($email && $senha) {
        try {
            $sql = "SELECT id, senha FROM responsaveis WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $responsavel = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($responsavel && password_verify($senha, $responsavel['senha'])) {
                $_SESSION['responsavel_id'] = $responsavel['id'];
                error_log("[LoginResponsavelController] Login bem-sucedido: responsavel_id=" . $responsavel['id']);
                ob_end_clean();
                echo json_encode(['success' => true, 'message' => 'Login bem-sucedido']);
                exit;
            } else {
                error_log("[LoginResponsavelController] Credenciais inválidas: Email=$email");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos']);
                exit;
            }
        } catch (PDOException $e) {
            error_log("[LoginResponsavelController] Erro: " . $e->getMessage());
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Erro no servidor']);
            exit;
        }
    }
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

ob_end_clean();
echo json_encode(['success' => false, 'message' => 'Método inválido']);
exit;
?>