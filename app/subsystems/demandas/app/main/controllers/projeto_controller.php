<?php
// Suprimir warnings/notices que possam corromper JSON
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once(__DIR__ . '/../models/projeto_model.php');
require_once(__DIR__ . '/../models/autorizacao.php');
require_once(__DIR__ . '/../models/categoria_model.php');

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

    // ===== LISTAR CATEGORIAS DO PROJETO =====
    if ($action === 'listar_categorias') {
        $id_projeto = (int) ($_GET['id_projeto'] ?? 0);
        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "Projeto inválido!"]);
            exit;
        }

        $auth = new autorizacao();
        if (!$auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            echo json_encode(["sucesso" => false, "erro" => "Você não tem acesso a este projeto!"]);
            exit;
        }
        echo json_encode(["sucesso" => true, "dados" => (new categoria_model())->listar($id_projeto, $id_usuario)]);
        exit;
    }

    // ===== CRIAR CATEGORIA DO PROJETO =====
    else if ($action === 'criar_categoria') {
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(["sucesso" => false, "erro" => "Apenas administradores podem criar categorias!"]);
            exit;
        }

        $dados = json_decode(file_get_contents("php://input"), true);
        $id_projeto = (int) ($dados['id_projeto'] ?? 0);
        $nome = trim($dados['nome'] ?? '');
        if ($id_projeto <= 0 || $nome === '') {
            echo json_encode(["sucesso" => false, "erro" => "Projeto e nome da categoria são obrigatórios!"]);
            exit;
        }

        $resultado = (new categoria_model())->criar($id_projeto, $nome, $id_usuario);
        echo json_encode($resultado['status'] === 1
            ? ["sucesso" => true, "id" => $resultado['id'], "message" => $resultado['message']]
            : ["sucesso" => false, "erro" => $resultado['message']]);
        exit;
    }

    // ===== EXCLUIR CATEGORIA DO PROJETO =====
    else if ($action === 'excluir_categoria') {
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(["sucesso" => false, "erro" => "Apenas administradores podem excluir categorias!"]);
            exit;
        }

        $dados = json_decode(file_get_contents("php://input"), true);
        if (!is_array($dados)) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }
        $id_categoria = (int) ($dados['id_categoria'] ?? 0);
        $id_projeto = (int) ($dados['id_projeto'] ?? 0);
        if ($id_categoria <= 0 || $id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "Categoria ou projeto inválido!"]);
            exit;
        }

        $categoriaModel = new categoria_model();
        $resultado = $categoriaModel->excluir($id_categoria, $id_projeto, $id_usuario);
        if ($resultado['status'] !== 1) {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message'] ?? "Erro ao excluir categoria."]);
            exit;
        }

        echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        exit;
    }

    // ===== CRIAR PROJETO =====
    if ($action === 'criar_projeto') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $nome = trim($dados['nome'] ?? '');
        $descricao = trim($dados['descricao'] ?? '');

        if (empty($nome)) {
            echo json_encode(["sucesso" => false, "erro" => "Nome do projeto é obrigatório!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $resultado = $projeto_model->criarProjeto($nome, $descricao ?: null, $id_usuario);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "id" => $resultado['id'], "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== ATUALIZAR PROJETO =====
    else if ($action === 'atualizar_projeto') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_projeto = (int) ($dados['id_projeto'] ?? 0);
        $nome = $dados['nome'] ?? null;
        $descricao = $dados['descricao'] ?? null;
        $ativo = $dados['ativo'] ?? null;

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID do projeto inválido!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $resultado = $projeto_model->atualizarProjeto($id_projeto, $nome, $descricao, $ativo, $id_usuario);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== LISTAR PROJETOS =====
    else if ($action === 'listar_projetos') {
        
        $apenas_ativos = $_GET['apenas_ativos'] ?? true;
        $apenas_ativos = filter_var($apenas_ativos, FILTER_VALIDATE_BOOLEAN);

        $projeto_model = new projeto_model();
        $auth = new autorizacao();

        if ($auth->ehAdministradorMaster($id_usuario)) {
            $projetos = $projeto_model->listarProjetos($apenas_ativos);
        } else {
            $projetos = $projeto_model->listarProjetos($apenas_ativos);
            $meus_projetos = $auth->listarProjetosDoUsuario($id_usuario);
            $ids_meus_projetos = array_map('intval', array_column($meus_projetos, 'id'));

            foreach ($projetos as &$projeto) {
                $projeto['vinculado'] = in_array((int) $projeto['id'], $ids_meus_projetos, true);
            }
            unset($projeto);
        }

        echo json_encode(["sucesso" => true, "dados" => $projetos]);
        exit;
    }

    // ===== LISTAR PROJETOS DO USUÁRIO ESPECÍFICO =====
    else if ($action === 'listar_projetos_usuario') {

        $id_usuario_alvo = (int) ($_GET['id_usuario'] ?? 0);
        if ($id_usuario_alvo <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "Usuário inválido!"]);
            exit;
        }

        $auth = new autorizacao();
        if (!$auth->usuarioPodeGerenciarVinculos($id_usuario)) {
            echo json_encode(["sucesso" => false, "erro" => "Você não tem permissão para gerenciar vinculos!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $projetos = $projeto_model->listarProjetos(true);
        $meus_projetos = $auth->listarProjetosDoUsuario($id_usuario_alvo);
        $ids_meus_projetos = array_map('intval', array_column($meus_projetos, 'id'));
        foreach ($projetos as &$projeto) {
            $idProjeto = (int) $projeto['id'];
            $projeto['vinculado'] = in_array($idProjeto, $ids_meus_projetos, true);
        }
        unset($projeto);

        echo json_encode(["sucesso" => true, "dados" => $projetos]);
        exit;
    }

    // ===== EXCLUIR PROJETO =====
    else if ($action === 'excluir_projeto') {
        $dados = json_decode(file_get_contents('php://input'), true);
        $id_projeto = (int) (($dados['id_projeto'] ?? $_GET['id_projeto'] ?? 0));

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID do projeto inválido!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $resultado = $projeto_model->excluirProjeto($id_projeto, $id_usuario);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== OBTER PROJETO =====
    else if ($action === 'obter_projeto') {
        
        $id_projeto = (int) ($_GET['id_projeto'] ?? 0);

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID do projeto inválido!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $auth = new autorizacao();

        // Verificar se usuário tem acesso ao projeto
        if (!$auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            echo json_encode(["sucesso" => false, "erro" => "Você não tem acesso a este projeto!"]);
            exit;
        }

        $projeto = $projeto_model->obterProjeto($id_projeto);

        if ($projeto) {
            echo json_encode(["sucesso" => true, "dados" => $projeto]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => "Projeto não encontrado!"]);
        }
        exit;
    }

    // ===== VINCULAR USUÁRIO =====
    else if ($action === 'vincular_usuario') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_usuario_vinculado = (int) ($dados['id_usuario'] ?? 0);
        $id_projeto = (int) ($dados['id_projeto'] ?? 0);

        if ($id_usuario_vinculado <= 0 || $id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "IDs inválidos!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $resultado = $projeto_model->vincularUsuario($id_usuario_vinculado, $id_projeto, $id_usuario);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== REMOVER VÍNCULO =====
    else if ($action === 'remover_vinculo') {
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_usuario_vinculado = (int) ($dados['id_usuario'] ?? 0);
        $id_projeto = (int) ($dados['id_projeto'] ?? 0);

        if ($id_usuario_vinculado <= 0 || $id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "IDs inválidos!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $resultado = $projeto_model->removerVinculo($id_usuario_vinculado, $id_projeto, $id_usuario);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "message" => $resultado['message']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message']]);
        }
        exit;
    }

    // ===== LISTAR USUÁRIOS DO PROJETO =====
    else if ($action === 'listar_usuarios_projeto') {
        
        $id_projeto = (int) ($_GET['id_projeto'] ?? 0);

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID do projeto inválido!"]);
            exit;
        }

        $projeto_model = new projeto_model();
        $auth = new autorizacao();

        // Apenas Master pode ver usuários
        if (!$auth->usuarioPodeGerenciarProjetos($id_usuario)) {
            echo json_encode(["sucesso" => false, "erro" => "Você não tem permissão!"]);
            exit;
        }

        $usuarios = $projeto_model->listarUsuariosProjeto($id_projeto);
        echo json_encode(["sucesso" => true, "dados" => $usuarios]);
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
