<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Configuração de logs
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getDatabaseConnection();
    error_log("[LoginController] Conexão com banco de dados estabelecida");
} catch (Exception $e) {
    error_log("[LoginController] Erro na conexão com o banco: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("[LoginController] Método não permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');
$tipo = trim($_POST['tipo'] ?? 'aluno');

error_log("[LoginController] Tentativa de login - Email: $email, Tipo: $tipo");
error_log("[LoginController] POST data: " . print_r($_POST, true));

if (empty($email) || empty($senha)) {
    error_log("[LoginController] Campos vazios - Email: " . (empty($email) ? 'vazio' : 'preenchido') . ", Senha: " . (empty($senha) ? 'vazio' : 'preenchido'));
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos']);
    exit;
}

try {
    if ($tipo === 'responsavel') {
        $sql = "SELECT id, nome, senha FROM responsaveis WHERE email = :email LIMIT 1";
        error_log("[LoginController] Tentando login como responsável");
        error_log("[LoginController] SQL Query: " . $sql);
        error_log("[LoginController] Email sendo buscado: " . $email);
        
        // Debug da consulta
        $stmt = $conn->prepare("SELECT COUNT(*) FROM responsaveis");
        $stmt->execute();
        $total = $stmt->fetchColumn();
        error_log("[LoginController] Total de responsáveis na tabela: " . $total);
        
        // Listar todos os emails de responsáveis
        $stmt = $conn->query("SELECT email FROM responsaveis");
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
        error_log("[LoginController] Emails cadastrados: " . implode(", ", $emails));
    } else {
        $sql = "SELECT a.id, a.nome, a.senha_hash, t.id as turma_id, t.nome as turma_nome 
               FROM alunos a 
               LEFT JOIN turmas t ON a.turma_id = t.id 
               WHERE a.email = :email LIMIT 1";
        error_log("[LoginController] Tentando login como aluno - Email: " . $email);
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    error_log("[LoginController] Usuário encontrado: " . ($usuario ? 'Sim' : 'Não'));
    if ($usuario) {
        error_log("[LoginController] Dados do usuário encontrado: " . json_encode($usuario));
    }

    if ($tipo === 'responsavel') {
        // Para responsável, usa password_verify com o campo senha
        $senhaCorreta = ($usuario && password_verify($senha, $usuario['senha']));
        error_log("[LoginController] Verificação de senha do responsável");
        error_log("[LoginController] Senha fornecida: " . $senha);
        error_log("[LoginController] Hash do banco: " . ($usuario ? $usuario['senha'] : 'usuário não encontrado'));
        error_log("[LoginController] Resultado da verificação: " . ($senhaCorreta ? 'true' : 'false'));
    } else {
        // Para aluno, usa password_verify com senha_hash
        $senhaCorreta = ($usuario && password_verify($senha, $usuario['senha_hash']));
    }

    if ($senhaCorreta) {
        // Configurar a sessão
        if ($tipo === 'responsavel') {
            $_SESSION['responsavel_id'] = $usuario['id'];
            $_SESSION['responsavel_nome'] = $usuario['nome'];
            $_SESSION['user_type'] = 'responsavel';
            error_log("[LoginController] Sessão do responsável configurada - ID: " . $usuario['id']);
            error_log("[LoginController] Sessão completa: " . print_r($_SESSION, true));
        } else {
            $_SESSION['aluno_id'] = $usuario['id'];
            $_SESSION['aluno_nome'] = $usuario['nome'];
            $_SESSION['aluno_turma_id'] = $usuario['turma_id'] ?? null;
            $_SESSION['aluno_turma_nome'] = $usuario['turma_nome'] ?? null;
            $_SESSION['user_type'] = 'aluno';
        }

        // Definir o redirecionamento
        $redirect = $tipo === 'responsavel' 
            ? '/sistema_aee_completo/app/main/view/painel_responsavel.php'
            : '/sistema_aee_completo/app/main/view/painel_aluno.php';

        error_log("[LoginController] Login bem-sucedido - Redirecionando para: " . $redirect);
        echo json_encode([
            'success' => true,
            'redirect' => $redirect,
            'message' => 'Login realizado com sucesso'
        ]);
        exit;
    }

    error_log("[LoginController] Credenciais inválidas: Email=$email, Tipo=$tipo");
    echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos']);
    exit;
} catch (PDOException $e) {
    error_log("[LoginController] Erro no banco de dados: " . $e->getMessage());
    error_log("[LoginController] SQL State: " . $e->getCode());
    error_log("[LoginController] Stack trace: " . $e->getTraceAsString());
    echo json_encode(['success' => false, 'message' => 'Erro ao processar login']);
    exit;
}
?>