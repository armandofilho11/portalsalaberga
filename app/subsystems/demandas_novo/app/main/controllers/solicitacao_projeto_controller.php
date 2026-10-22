<?php
// Suprimir warnings/notices que possam corromper JSON
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once(__DIR__ . '/../models/solicitacao_projeto_model.php');
require_once(__DIR__ . '/../models/autorizacao.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(["sucesso" => false, "erro" => "Usuário não autenticado!"]);
        exit;
    }

    $id_usuario = (int) $_SESSION['id_usuario'];

    // ===== CRIAR SOLICITAÇÃO =====
    if ($action === 'criar_solicitacao') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_projeto = (int) ($dados['id_projeto'] ?? 0);
        $mensagem = $dados['mensagem'] ?? null;

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID do projeto inválido!"]);
            exit;
        }

        $solicitacao_model = new solicitacao_projeto_model();
        $resultado = $solicitacao_model->criarSolicitacao($id_usuario, $id_projeto, $mensagem);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "id" => $resultado['id'], "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== LISTAR SOLICITAÇÕES PENDENTES (para Master) =====
    else if ($action === 'listar_pendentes') {
        
        $id_projeto = $_GET['id_projeto'] ?? null;
        $id_projeto = $id_projeto ? (int) $id_projeto : null;

        $solicitacao_model = new solicitacao_projeto_model();
        $auth = new autorizacao();

        // Apenas Master pode ver solicitações
        if (!$auth->usuarioPodeGerenciarSolicitacoes($id_usuario)) {
            echo json_encode(["sucesso" => false, "erro" => "Você não tem permissão!"]);
            exit;
        }

        $solicitacoes = $solicitacao_model->listarSolicitacoesPendentes($id_usuario, $id_projeto);
        echo json_encode(["sucesso" => true, "dados" => $solicitacoes]);
        exit;
    }

    // ===== LISTAR SOLICITAÇÕES DO USUÁRIO =====
    else if ($action === 'listar_minhas_solicitacoes') {
        
        $solicitacao_model = new solicitacao_projeto_model();
        $solicitacoes = $solicitacao_model->listarSolicitacoesUsuario($id_usuario);
        echo json_encode(["sucesso" => true, "dados" => $solicitacoes]);
        exit;
    }

    // ===== APROVAR SOLICITAÇÃO =====
    else if ($action === 'aprovar') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_solicitacao = (int) ($dados['id_solicitacao'] ?? 0);

        if ($id_solicitacao <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID da solicitação inválido!"]);
            exit;
        }

        $solicitacao_model = new solicitacao_projeto_model();
        $resultado = $solicitacao_model->aprovarSolicitacao($id_solicitacao, $id_usuario);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== RECUSAR SOLICITAÇÃO =====
    else if ($action === 'recusar') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_solicitacao = (int) ($dados['id_solicitacao'] ?? 0);
        $motivo = $dados['motivo'] ?? null;

        if ($id_solicitacao <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID da solicitação inválido!"]);
            exit;
        }

        $solicitacao_model = new solicitacao_projeto_model();
        $resultado = $solicitacao_model->recusarSolicitacao($id_solicitacao, $id_usuario, $motivo);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== OBTER STATUS DE SOLICITAÇÃO =====
    else if ($action === 'obter_status') {
        
        $id_projeto = (int) ($_GET['id_projeto'] ?? 0);

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID do projeto inválido!"]);
            exit;
        }

        $solicitacao_model = new solicitacao_projeto_model();
        $status = $solicitacao_model->obterStatusSolicitacao($id_usuario, $id_projeto);

        echo json_encode(["sucesso" => true, "status" => $status]);
        exit;
    }

    else {
        echo json_encode(["sucesso" => false, "erro" => "Ação não reconhecida!"]);
        exit;
    }
}

echo json_encode(["sucesso" => false, "erro" => "Requisição inválida!"]);
exit;
?>
