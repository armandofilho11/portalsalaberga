<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

require_once __DIR__ . '/../model/modelAgen.php';

error_log("[CadastroController] POST: " . print_r($_POST, true));
error_log("[CadastroController] GET: " . print_r($_GET, true));
error_log("[CadastroController] Raw Input: " . file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parsear dados POST se necessário
    if (empty($_POST)) {
        parse_str(file_get_contents('php://input'), $post_data);
        $_POST = $post_data;
        error_log("[CadastroController] Parsed POST: " . print_r($_POST, true));
    }

    if (isset($_POST['btn'])) {
        $action = $_POST['btn'];

        // Cadastrar Equipamento
        if ($action === 'cadastrar_equipamento') {
            // Verificar token CSRF
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                error_log("[cadastrar_equipamento] Erro: Token CSRF inválido");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro de segurança: token inválido']);
                exit;
            }

            $nome = trim($_POST['equipamento-nome'] ?? '');
            $descricao = trim($_POST['equipamento-descricao'] ?? '');
            $quantidade = (int)($_POST['equipamento-disponivel'] ?? 0);

            error_log("[cadastrar_equipamento] Nome=$nome, Quantidade=$quantidade");

            if (empty($nome)) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'O nome do equipamento é obrigatório']);
                exit;
            }

            if (strlen($nome) > 255) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Nome muito longo (máximo 255 caracteres)']);
                exit;
            }

            if ($quantidade <= 0) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'A quantidade deve ser maior que zero']);
                exit;
            }

            try {
                $x = new Agendamento();
                if ($x->cadastro_equipamento($nome, $descricao, $quantidade)) {
                    ob_end_clean();
                    echo json_encode(['success' => true, 'message' => 'Equipamento cadastrado com sucesso!']);
                    exit;
                }
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar equipamento']);
                exit;
            } catch (Exception $e) {
                error_log("[cadastrar_equipamento] Erro: " . $e->getMessage());
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar equipamento: ' . $e->getMessage()]);
                exit;
            }
        }

        // Cadastrar Espaço
        if ($action === 'cadastrar_espaco') {
            error_log("[cadastrar_espaco] Iniciando cadastro de espaço");
            error_log("[cadastrar_espaco] POST data: " . print_r($_POST, true));
            
            // Verificar token CSRF
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                error_log("[cadastrar_espaco] Erro: Token CSRF inválido");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro de segurança: token inválido']);
                exit;
            }

            $nome = trim($_POST['espaco-nome'] ?? '');
            $descricao = trim($_POST['espaco-descricao'] ?? '');
            $quantidade = (int)($_POST['espaco-disponivel'] ?? 0);

            error_log("[cadastrar_espaco] Dados processados - Nome: $nome, Descrição: $descricao, Quantidade: $quantidade");

            if (empty($nome)) {
                error_log("[cadastrar_espaco] Erro: Nome vazio");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'O nome do espaço é obrigatório']);
                exit;
            }

            if (strlen($nome) > 255) {
                error_log("[cadastrar_espaco] Erro: Nome muito longo");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Nome muito longo (máximo 255 caracteres)']);
                exit;
            }

            if ($quantidade <= 0) {
                error_log("[cadastrar_espaco] Erro: Quantidade inválida");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'A quantidade deve ser maior que zero']);
                exit;
            }

            try {
                $x = new Agendamento();
                if ($x->cadastro_espaco($nome, $descricao, $quantidade)) {
                    error_log("[cadastrar_espaco] Sucesso no cadastro");
                    ob_end_clean();
                    echo json_encode(['success' => true, 'message' => 'Espaço cadastrado com sucesso!']);
                    exit;
                }
                error_log("[cadastrar_espaco] Falha no cadastro");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar espaço']);
                exit;
            } catch (Exception $e) {
                error_log("[cadastrar_espaco] Exceção: " . $e->getMessage());
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar espaço: ' . $e->getMessage()]);
                exit;
            }
        }

        // Atualizar Cadastro
        if ($action === 'atualizar_cadastro') {
            $id = (int)($_POST['id'] ?? 0);
            $tipo = trim($_POST['tipo'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $quantidade = (int)($_POST['quantidade'] ?? 0);

            error_log("[atualizar_cadastro] Tipo=$tipo, ID=$id, Nome=$nome, Descrição=$descricao, Quantidade=$quantidade");

            // Validações
            if (!in_array($tipo, ['Equipamento', 'Espaço'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
                exit;
            }

            if (strlen($nome) > 255) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Nome muito longo (máximo 255 caracteres)']);
                exit;
            }

            if (empty($nome)) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Nome é obrigatório']);
                exit;
            }

            if ($quantidade < 0) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Quantidade não pode ser negativa']);
                exit;
            }

            if ($id <= 0) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
                exit;
            }

            try {
                $x = new Agendamento();
                if ($x->atualizar_cadastro($tipo, $id, $nome, $descricao, $quantidade)) {
                    ob_end_clean();
                    echo json_encode(['success' => true, 'message' => 'Cadastro atualizado com sucesso!']);
                    exit;
                }
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar cadastro']);
                exit;
            } catch (Exception $e) {
                error_log("[atualizar_cadastro] Erro: " . $e->getMessage());
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }

        // Excluir Cadastro
        if ($action === 'excluir_cadastro') {
            $tipo = trim($_POST['tipo'] ?? '');
            $id = (int)($_POST['id'] ?? 0);

            error_log("[excluir_cadastro] Tipo=$tipo, ID=$id");

            if (!in_array($tipo, ['Equipamento', 'Espaço'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
                exit;
            }

            if ($tipo && $id > 0) {
                try {
                    $x = new Agendamento();
                    if ($x->excluir_cadastro($tipo, $id)) {
                        ob_end_clean();
                        echo json_encode(['success' => true, 'message' => 'Cadastro excluído com sucesso!']);
                        exit;
                    }
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => 'Erro ao excluir cadastro']);
                    exit;
                } catch (Exception $e) {
                    error_log("[excluir_cadastro] Erro: " . $e->getMessage());
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
                    exit;
                }
            }
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            exit;
        }

        // Alternar Disponibilidade
        if ($action === 'alternar_disponibilidade') {
            $responsavel_id = $_SESSION['responsavel_id'] ?? 0;
            if (!$responsavel_id) {
                error_log("[alternar_disponibilidade] Falha na autenticação: responsavel_id não definido na sessão");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado. Faça login novamente.']);
                exit;
            }

            $tipo = trim($_POST['tipo'] ?? '');
            $id = (int)($_POST['id'] ?? 0);
            $disponivel = (int)($_POST['disponivel'] ?? 0);

            error_log("[alternar_disponibilidade] Tipo=$tipo, ID=$id, Disponivel=$disponivel, Responsavel=$responsavel_id");

            if (!in_array($tipo, ['Equipamento', 'Espaço'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
                exit;
            }

            if (!in_array($disponivel, [0, 1])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Valor de disponibilidade inválido']);
                exit;
            }

            if ($tipo && $id > 0) {
                try {
                    $x = new Agendamento();
                    if ($x->alternar_disponibilidade($tipo, $id, $disponivel)) {
                        ob_end_clean();
                        echo json_encode(['success' => true, 'message' => 'Disponibilidade atualizada com sucesso!']);
                        exit;
                    }
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar disponibilidade']);
                    exit;
                } catch (Exception $e) {
                    error_log("[alternar_disponibilidade] Erro: " . $e->getMessage());
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
                    exit;
                }
            }
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            exit;
        }

        // Agendar Item
        if ($action === 'agendar_item') {
            $id_item = (int)($_POST['id_item'] ?? 0);
            $tipo = trim($_POST['tipo'] ?? '');
            $data_hora = trim($_POST['data_hora'] ?? '');
            $turma_id = (int)($_POST['turma_id'] ?? 0);

            error_log("[agendar_item] Tipo=$tipo, ID=$id_item, DataHora=$data_hora, TurmaID=$turma_id");

            // Validar entrada
            if (!$id_item || !in_array($tipo, ['Equipamento', 'Espaço']) || !$data_hora || ($turma_id <= 0 && $tipo !== 'teste')) {
                ob_end_clean();
                $errorMessage = 'Dados inválidos. Verifique os campos e tente novamente.';
                if ($turma_id <= 0 && $tipo !== 'teste') {
                    $errorMessage = 'ID da Turma inválido ou não fornecido.';
                }
                echo json_encode(['success' => false, 'message' => $errorMessage]);
                exit;
            }

            // Validar data_hora
            $data_hora_date = DateTime::createFromFormat('Y-m-d\TH:i', $data_hora);
            $now = new DateTime();
            $min_date = (new DateTime())->modify('+5 minutes');
            if (!$data_hora_date || $data_hora_date <= $min_date) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'A data e hora devem ser futuras (mínimo 5 minutos).']);
                exit;
            }

            // Obter aluno_id da sessão
            $aluno_id = $_SESSION['aluno_id'] ?? 0;
            if (!$aluno_id) {
                error_log("[agendar_item] Falha na autenticação: aluno_id não definido na sessão");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado. Faça login novamente.']);
                exit;
            }

            try {
                $x = new Agendamento();
                // Verificar disponibilidade do item
                $item = $x->verificar_disponibilidade($tipo, $id_item);
                if (!$item || $item['disponivel'] != 1 || $item['quantidade_disponivel'] <= 0) {
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => 'Item não disponível para agendamento.']);
                    exit;
                }

                if ($x->agendar_item($id_item, $tipo, $data_hora, $aluno_id, $turma_id)) {
                    ob_end_clean();
                    echo json_encode(['success' => true, 'message' => 'Item agendado com sucesso!']);
                    exit;
                }
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao agendar item. Tente novamente.']);
                exit;
            } catch (Exception $e) {
                error_log("[agendar_item] Erro: " . $e->getMessage());
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
                exit;
            }
        }

        // Assinar Agendamento
        if ($action === 'assinar_agendamento') {
            $responsavel_id = $_SESSION['responsavel_id'] ?? 0;
            if (!$responsavel_id) {
                error_log("[assinar_agendamento] Falha na autenticação: responsavel_id não definido na sessão");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado. Faça login novamente.']);
                exit;
            }

            // Verificar CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                error_log("[assinar_agendamento] Falha na validação CSRF");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro de validação. Por favor, recarregue a página e tente novamente.']);
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);
            $tipo = trim($_POST['tipo'] ?? '');
            $status = trim($_POST['status'] ?? '');

            // Normalizar tipo para minúsculo e sem acento
            $tipo_normalizado = strtolower(str_replace(['ã', 'á', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'ú', 'ç'], ['a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'u', 'c'], $tipo));
            if ($id <= 0 || !in_array($tipo_normalizado, ['equipamento', 'espaco']) || !in_array($status, ['Aprovado', 'Rejeitado'])) {
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
                exit;
            }

            try {
                $x = new Agendamento();
                // Verificar se o agendamento existe e está pendente (case insensitive)
                $agendamento = $x->obter_agendamento($id);
                if (!$agendamento || strtolower($agendamento['status']) !== 'pendente') {
                    ob_end_clean();
                    echo json_encode(['success' => false, 'message' => 'Agendamento não encontrado ou já processado']);
                    exit;
                }

                if ($x->assinar_agendamento($id, $tipo, $status)) {
                    ob_end_clean();
                    echo json_encode(['success' => true, 'message' => "Agendamento $status com sucesso!"]);
                    exit;
                }
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao processar agendamento']);
                exit;
            } catch (Exception $e) {
                error_log("[assinar_agendamento] Erro: " . $e->getMessage());
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
                exit;
            }
        }

        // Listar Assinaturas Pendentes
        if ($action === 'listar_assinaturas') {
            $responsavel_id = $_SESSION['responsavel_id'] ?? 0;
            if (!$responsavel_id) {
                error_log("[listar_assinaturas] Falha na autenticação: responsavel_id não definido na sessão");
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado. Faça login novamente.']);
                exit;
            }

            try {
                $x = new Agendamento();
                $agendamentos = $x->listar_agendamentos(); // Sem parâmetros para listar todos os pendentes
                ob_end_clean();
                echo json_encode(['success' => true, 'agendamentos' => $agendamentos]);
                exit;
            } catch (Exception $e) {
                error_log("[listar_assinaturas] Erro: " . $e->getMessage());
                ob_end_clean();
                echo json_encode(['success' => false, 'message' => 'Erro ao listar assinaturas: ' . $e->getMessage()]);
                exit;
            }
        }
    }

    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    exit;
}

// Listar Cadastros
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['btn']) && $_GET['btn'] === 'listar_cadastros') {
    error_log("[listar_cadastros] Solicitando listagem de cadastros");
    try {
        $x = new Agendamento();
        $cadastros = $x->listar_cadastros();
        ob_end_clean();
        echo json_encode(['success' => true, 'data' => $cadastros]);
        exit;
    } catch (Exception $e) {
        error_log("[listar_cadastros] Erro: " . $e->getMessage());
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
        exit;
    }
}

// Listar Agendamentos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['btn']) && $_GET['btn'] === 'listar_agendamentos') {
    $tipo = trim($_GET['tipo'] ?? '');
    error_log("[listar_agendamentos] Solicitando listagem de agendamentos para tipo: $tipo");
    try {
        $x = new Agendamento();
        $agendamentos = $x->listar_agendamentos($tipo);
        ob_end_clean();
        echo json_encode(['success' => true, 'data' => $agendamentos]);
        exit;
    } catch (Exception $e) {
        error_log("[listar_agendamentos] Erro: " . $e->getMessage());
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
        exit;
    }
}

// Listar Agendamentos do Aluno
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['btn']) && $_GET['btn'] === 'listar_agendamentos_aluno') {
    $aluno_id = $_SESSION['aluno_id'] ?? 0;
    if (!$aluno_id) {
        error_log("[listar_agendamentos_aluno] Falha na autenticação: aluno_id não definido na sessão");
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado. Faça login novamente.']);
        exit;
    }

    error_log("[listar_agendamentos_aluno] Solicitando listagem de agendamentos para aluno_id: $aluno_id");
    try {
        $x = new Agendamento();
        $agendamentos = $x->listar_agendamentos_aluno($aluno_id);
        ob_end_clean();
        echo json_encode(['success' => true, 'data' => $agendamentos]);
        exit;
    } catch (Exception $e) {
        error_log("[listar_agendamentos_aluno] Erro: " . $e->getMessage());
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
        exit;
    }
}

// Listar Assinaturas Pendentes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['btn']) && $_GET['btn'] === 'listar_assinaturas') {
    $responsavel_id = $_SESSION['responsavel_id'] ?? 0;
    if (!$responsavel_id) {
        error_log("[listar_assinaturas] Falha na autenticação: responsavel_id não definido na sessão");
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado. Faça login novamente.']);
        exit;
    }

    try {
        $x = new Agendamento();
        $agendamentos = $x->listar_agendamentos(); // Sem parâmetros para listar todos os pendentes
        ob_end_clean();
        echo json_encode(['success' => true, 'agendamentos' => $agendamentos]);
        exit;
    } catch (Exception $e) {
        error_log("[listar_assinaturas] Erro: " . $e->getMessage());
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erro ao listar assinaturas: ' . $e->getMessage()]);
        exit;
    }
}

ob_end_clean();
echo json_encode(['success' => false, 'message' => 'Método ou ação inválida']);
exit;
?>