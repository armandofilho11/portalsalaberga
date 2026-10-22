<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$database_path = __DIR__ . '/../config/database.php';
if (!file_exists($database_path)) {
    die('Arquivo de configuração do banco não encontrado: ' . $database_path);
}
require_once $database_path;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $turma_id = $_POST['turma_id'] ?? null;

    // Validações
    $errors = [];

    if (empty($nome)) {
        $errors[] = "Nome é obrigatório";
    }

    if (empty($email)) {
        $errors[] = "E-mail é obrigatório";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@aluno\.ce\.gov\.br$/', $email)) {
        $errors[] = "E-mail institucional inválido";
    }

    if (empty($senha)) {
        $errors[] = "Senha é obrigatória";
    } elseif (strlen($senha) < 6) {
        $errors[] = "A senha deve ter pelo menos 6 caracteres";
    }

    if ($senha !== $confirmar_senha) {
        $errors[] = "As senhas não coincidem";
    }

    if (empty($errors)) {
        try {
            $conn = getDatabaseConnection();

            // Verificar se o e-mail já está cadastrado
            $stmt = $conn->prepare("SELECT id FROM alunos WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "Este e-mail já está cadastrado";
            } else {
                // Hash da senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Gerar matrícula (pode ser ajustado conforme necessidade)
                $matricula = date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

                // Inserir novo aluno
                $stmt = $conn->prepare("INSERT INTO alunos (nome, email, senha_hash, matricula, turma_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $result = $stmt->execute([$nome, $email, $senha_hash, $matricula, $turma_id]);

                if ($result) {
                    // Redirecionar para a página de login com mensagem de sucesso
                    header("Location: /sistema_aee_completo/app/main/index.php?success=1");
                    exit;
                } else {
                    $errors[] = "Erro ao inserir dados no banco de dados";
                    error_log("Erro ao inserir aluno: " . print_r($stmt->errorInfo(), true));
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Erro ao processar o cadastro. Por favor, tente novamente.";
            error_log("Erro no cadastro: " . $e->getMessage());
            error_log("Detalhes do erro: " . print_r($e->errorInfo, true));
        }
    }

    if (!empty($errors)) {
        // Redirecionar de volta para o formulário com os erros
        $error_string = implode(",", $errors);
        header("Location: /sistema_aee_completo/app/main/view/register.php?error=" . urlencode($error_string));
        exit;
    }
} else {
    // Se não for POST, redirecionar para a página de registro
    header("Location: /sistema_aee_completo/app/main/view/register.php");
    exit;
}
?> 