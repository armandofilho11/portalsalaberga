<?php
// Inicia a sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para limpar completamente a sessão
function clearAllSessions() {
    // Limpa todas as variáveis de sessão
    $_SESSION = array();

    // Destrói o cookie da sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destrói a sessão
    session_destroy();

    // Limpa qualquer cookie de sessão que possa ter ficado
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Limpa o array $_SESSION novamente para garantir
    $_SESSION = array();

    // Força a criação de uma nova sessão
    session_regenerate_id(true);
}

// Função para verificar se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

// Função para verificar o tipo de usuário
function getUserType() {
    return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;
}

// Função para redirecionar para a página de login
function redirectToLogin() {
    // Limpa todas as sessões
    clearAllSessions();
    
    // Redireciona para o login
    header("Location: /sistema_aee_completo/app/main/index.php");
    exit();
}

// Função para verificar se o usuário tem permissão para acessar a página
function checkPermission($requiredType) {
    // Se não estiver logado, redireciona para o login
    if (!isLoggedIn()) {
        redirectToLogin();
    }
    
    // Se o tipo de usuário não corresponder ao requerido, redireciona para a página apropriada
    if ($_SESSION['user_type'] !== $requiredType) {
        switch ($_SESSION['user_type']) {
            case 'aluno':
                header("Location: /sistema_aee_completo/app/main/view/painel_aluno.php");
                break;
            case 'responsavel':
                header("Location: /sistema_aee_completo/app/main/view/painel_responsavel.php");
                break;
            default:
                redirectToLogin();
        }
        exit();
    }
}

// Função para verificar se a sessão está ativa
function checkSession() {
    if (!isLoggedIn()) {
        redirectToLogin();
    }
}

// Função para iniciar uma nova sessão de usuário
function startUserSession($userId, $userType) {
    // Limpa qualquer sessão anterior
    clearAllSessions();
    
    // Define os dados da nova sessão
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_type'] = $userType;
    
    // Busca o nome do usuário no banco de dados
    require_once __DIR__ . '/database.php';
    $conn = getDatabaseConnection();
    
    try {
        if ($userType === 'aluno') {
            $stmt = $conn->prepare("SELECT nome, turma_id FROM alunos WHERE id = ?");
        } else {
            $stmt = $conn->prepare("SELECT nome FROM responsaveis WHERE id = ?");
        }
        
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            if ($userType === 'aluno') {
                $_SESSION['aluno_nome'] = $usuario['nome'];
                $_SESSION['aluno_turma_id'] = $usuario['turma_id'];
                
                // Busca o nome da turma
                if ($usuario['turma_id']) {
                    $stmt = $conn->prepare("SELECT nome FROM turmas WHERE id = ?");
                    $stmt->execute([$usuario['turma_id']]);
                    $turma = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($turma) {
                        $_SESSION['aluno_turma_nome'] = $turma['nome'];
                    }
                }
            } else {
                $_SESSION['responsavel_nome'] = $usuario['nome'];
            }
        }
    } catch (PDOException $e) {
        error_log("Erro ao buscar dados do usuário: " . $e->getMessage());
    }
    
    // Regenera o ID da sessão para maior segurança
    session_regenerate_id(true);
}

// Função para fazer login
function login($email, $senha, $tipo_usuario) {
    require_once __DIR__ . '/database.php';
    
    try {
        $conn = getDatabaseConnection();
        
        // Log para debug
        error_log("Tentativa de login - Email: $email, Tipo: $tipo_usuario");
        
        // Prepara a consulta SQL baseada no tipo de usuário
        if ($tipo_usuario === 'aluno') {
            $stmt = $conn->prepare("SELECT id, nome, email, senha_hash as senha FROM alunos WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT id, nome, email, senha FROM responsaveis WHERE email = ?");
        }
        
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Log para debug
        error_log("Usuário encontrado: " . ($usuario ? "Sim" : "Não"));
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            startUserSession($usuario['id'], $tipo_usuario);
            error_log("Login bem-sucedido para usuário ID: " . $usuario['id'] . " do tipo: " . $tipo_usuario);
            
            // Não redireciona aqui, deixa o index.php fazer o redirecionamento
            return true;
        }
        
        error_log("Senha incorreta ou usuário não encontrado");
        return false;
    } catch (PDOException $e) {
        error_log("Erro no login: " . $e->getMessage());
        return false;
    }
}

// Função para fazer logout
function logout() {
    clearAllSessions();
    header("Location: /sistema_aee_completo/app/main/index.php");
    exit();
}

// Função para registrar um novo usuário
function register($nome, $email, $senha, $tipo_usuario, $turma_id = null) {
    require_once __DIR__ . '/database.php';
    $conn = getDatabaseConnection();
    
    try {
        // Verifica se o email já existe
        if ($tipo_usuario === 'aluno') {
            $stmt = $conn->prepare("SELECT id FROM alunos WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT id FROM responsaveis WHERE email = ?");
        }
        
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false; // Email já existe
        }
        
        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Insere o novo usuário
        if ($tipo_usuario === 'aluno') {
            $stmt = $conn->prepare("INSERT INTO alunos (nome, email, senha_hash, turma_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash, $turma_id]);
        } else {
            $stmt = $conn->prepare("INSERT INTO responsaveis (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash]);
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("Erro no registro: " . $e->getMessage());
        return false;
    }
}
?> 