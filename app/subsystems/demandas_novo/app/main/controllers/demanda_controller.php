<?php
// Suprimir warnings/notices que possam corromper JSON
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once(__DIR__ . '/../models/demanda_model.php');
require_once(__DIR__ . '/../models/categoria_model.php');
require_once(__DIR__ . '/../models/autorizacao.php');

set_exception_handler(static function (Throwable $exception): void {
    error_log('Erro no controller de demandas: ' . $exception->getMessage());
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Não foi possível concluir a operação agora. Tente novamente.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
});

// Aceitar tanto POST quanto GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? '';
    header('Content-Type: application/json; charset=utf-8');

    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(["sucesso" => false, "erro" => "Usuário não autenticado!"]);
        exit;
    }

    $id_usuario = (int) $_SESSION['id_usuario'];
    $auth = new autorizacao();

    if ($action === 'obter_filtro_projeto') {
        $id_projeto = (int) ($_SESSION['filtro_projeto_id'] ?? 0);
        $projetosAtivos = array_filter(
            $auth->listarProjetosDoUsuario($id_usuario),
            static fn (array $projeto): bool => (int) ($projeto['ativo'] ?? 0) === 1
        );
        $idsProjetosAtivos = array_map('intval', array_column($projetosAtivos, 'id'));
        if ($id_projeto > 0 && !in_array($id_projeto, $idsProjetosAtivos, true)) {
            unset($_SESSION['filtro_projeto_id']);
            $id_projeto = 0;
        }

        echo json_encode([
            'sucesso' => true,
            'id_projeto' => $id_projeto > 0 ? $id_projeto : null
        ]);
        exit;
    }

    if ($action === 'definir_filtro_projeto') {
        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $valor = $dados['id_projeto'] ?? 'todos';
        $id_projeto = ($valor === '' || $valor === 'todos' || $valor === null) ? 0 : (int) $valor;
        $projetosAtivos = array_filter(
            $auth->listarProjetosDoUsuario($id_usuario),
            static fn (array $projeto): bool => (int) ($projeto['ativo'] ?? 0) === 1
        );
        $idsProjetosAtivos = array_map('intval', array_column($projetosAtivos, 'id'));

        if ($id_projeto < 0 || ($id_projeto > 0 && !in_array($id_projeto, $idsProjetosAtivos, true))) {
            http_response_code(403);
            echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso a este projeto.']);
            exit;
        }

        if ($id_projeto > 0) {
            $_SESSION['filtro_projeto_id'] = $id_projeto;
        } else {
            unset($_SESSION['filtro_projeto_id']);
        }

        echo json_encode([
            'sucesso' => true,
            'id_projeto' => $id_projeto > 0 ? $id_projeto : null
        ]);
        exit;
    }

    // ===== CRIAR DEMANDA =====
    if ($action === 'nova_demanda') {
        
        // Master também possui permissão administrativa.
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(["sucesso" => false, "erro" => "Apenas administradores podem criar demandas!"]);
            exit;
        }

        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }
        
        $titulo = trim($dados['titulo'] ?? '');
        $descricao = trim($dados['descricao'] ?? '');
        $prioridade = trim($dados['prioridade'] ?? '');
        $categoria = trim($dados['categoria'] ?? '');
        $prazo_input = trim($dados['prazo'] ?? '');
        // NOVO: Aceitar id_projeto ao invés de 'projeto' string
        $id_projeto = (int) ($dados['id_projeto'] ?? 0);
        $usuarios_atribuidos = $dados['usuarios_atribuidos'] ?? [];
        $quantidade_alunos = isset($dados['quantidade_alunos']) ? max(1, (int) $dados['quantidade_alunos']) : 1;

        if (!is_array($usuarios_atribuidos)) {
            echo json_encode(["sucesso" => false, "erro" => "Usuários atribuídos inválidos!"]);
            exit;
        }
        
        // Validações
        if (empty($titulo) || strlen($titulo) < 3) {
            echo json_encode(["sucesso" => false, "erro" => "Título inválido (mín. 3 caracteres)!"]);
            exit;
        }

        $prioridadesValidas = ['alta', 'media', 'baixa'];
        if (!in_array($prioridade, $prioridadesValidas, true)) {
            echo json_encode(["sucesso" => false, "erro" => "Prioridade inválida!"]);
            exit;
        }

        if ($id_projeto <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "Projeto inválido!"]);
            exit;
        }

        if (empty($categoria) || !(new categoria_model())->pertenceAoProjeto($categoria, $id_projeto)) {
            echo json_encode(["sucesso" => false, "erro" => "Categoria inválida!"]);
            exit;
        }

        // NOVO: Verificar se usuário tem acesso ao projeto
        if (!$auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            echo json_encode(["sucesso" => false, "erro" => "Você não tem acesso a este projeto!"]);
            exit;
        }

        if (empty($prazo_input)) {
            echo json_encode(["sucesso" => false, "erro" => "Prazo é obrigatório!"]);
            exit;
        }

        try {
            $prazo = new DateTime($prazo_input);
        } catch (Exception $e) {
            echo json_encode(["sucesso" => false, "erro" => "Prazo inválido!"]);
            exit;
        }

        if ($prazo < new DateTime('today')) {
            echo json_encode(["sucesso" => false, "erro" => "O prazo não pode ser anterior à data atual!"]);
            exit;
        }

        // NOVO: Usar id_projeto ao invés de 'projeto'
        $demanda = new demanda_model();
        $resultado = $demanda->nova_demanda($titulo, $descricao, $prioridade, 'aberta', $categoria, $prazo, $id_projeto, $id_usuario, $usuarios_atribuidos, $quantidade_alunos);

        if ($resultado['status'] === 1) {
            echo json_encode(["sucesso" => true, "id" => $resultado['id'], "usuarios_atribuidos" => $resultado['usuarios_atribuidos']]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $resultado['message'] ?? "Erro ao criar demanda!"]);
        }
        exit;
    }

    // ===== LISTAR DEMANDAS =====
    else if ($action === 'listar_demandas') {
        
        $pesquisa = $_GET['pesquisa'] ?? null;
        $status = $_GET['status'] ?? null;
        $prioridade = $_GET['prioridade'] ?? null;
        $categoria = $_GET['categoria'] ?? null;
        $filtroProjetoInformado = array_key_exists('id_projeto', $_GET);
        $id_projeto = $filtroProjetoInformado
            ? (int) ($_GET['id_projeto'] ?: 0)
            : (int) ($_SESSION['filtro_projeto_id'] ?? 0);
        if ($id_projeto > 0 && !$auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            if ($filtroProjetoInformado) {
                http_response_code(403);
                echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso a este projeto.']);
                exit;
            }

            unset($_SESSION['filtro_projeto_id']);
            $id_projeto = 0;
        }
        $id_projeto = $id_projeto > 0 ? $id_projeto : null;
        $data_inicio = $_GET['data_inicio'] ?? null;
        $data_fim = $_GET['data_fim'] ?? null;

        $demanda = new demanda_model();
        $demanda->finalizar_demandas_expiradas();
        $dados = $demanda->listar_demandas_filtradas(
            $pesquisa, $status, $prioridade, $categoria, $id_projeto, $data_inicio, $data_fim, $id_usuario
        );

        echo json_encode(["sucesso" => true, "dados" => $dados]);
        exit;
    }

    // ===== BUSCAR DEMANDA PELO QR CODE =====
    else if ($action === 'buscar_demanda') {
        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);

        if ($id_demanda <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'QR Code inválido.']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->buscar_demanda($id_demanda);
        if ($resultado['status'] !== 1 || empty($resultado['dados'])) {
            echo json_encode(['sucesso' => false, 'erro' => 'Demanda não encontrada.']);
            exit;
        }

        echo json_encode(['sucesso' => true, 'dados' => $resultado['dados']]);
        exit;
    }

    // ===== MINHAS DEMANDAS =====
    else if ($action === 'minhas_demandas') {
        
        $demanda = new demanda_model();
        $demanda->finalizar_demandas_expiradas();
        $dados = $demanda->listar_demandas_usuario((int) $_SESSION['id_usuario']);

        echo json_encode(["sucesso" => true, "dados" => $dados]);
        exit;
    }

    else if ($action === 'meus_registros_ponto') {
        $demanda = new demanda_model();
        $id_projeto = isset($_GET['id_projeto']) && $_GET['id_projeto'] !== '' ? (int) $_GET['id_projeto'] : null;
        if ($id_projeto !== null && !$auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso a este projeto.']);
            exit;
        }
        echo json_encode([
            'sucesso' => true,
            'dados' => $demanda->listar_registros_usuario((int) $_SESSION['id_usuario'], $id_projeto)
        ]);
        exit;
    }

        // ===== MÉTRICAS DO USUÁRIO =====
        else if ($action === 'minhas_metricas') {
            $demanda = new demanda_model();
            $demanda->finalizar_demandas_expiradas();
            $id_projeto = isset($_GET['id_projeto']) && $_GET['id_projeto'] !== '' ? (int) $_GET['id_projeto'] : null;
            if ($id_projeto !== null && !$auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
                echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso a este projeto.']);
                exit;
            }
            echo json_encode([
                'sucesso' => true,
                'dados' => $demanda->obter_metricas_usuario((int) $_SESSION['id_usuario'], $id_projeto)
            ]);
            exit;
        }

    /*
    // Rota sem consumidor interno identificado. Mantida comentada para
    // preservar a capacidade de detalhes para eventual integração externa.
    // ===== BUSCAR DEMANDA (DETALHES) =====
    else if ($action === 'buscar_demanda') {
        
        $id_demanda = (int) ($_GET['id'] ?? 0);

        if ($id_demanda <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID inválido!"]);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->buscar_demanda_detalhes($id_demanda);

        echo json_encode([
            "sucesso" => $resultado['status'] === 1,
            "dados" => $resultado['dados'] ?? null,
            "responsaveis" => $resultado['responsaveis'] ?? [],
            "erro" => $resultado['status'] === 1 ? null : "Demanda não encontrada"
        ]);
        exit;
    }
    */

    // ===== ATUALIZAR DEMANDA =====
    else if ($action === 'atualizar_demanda') {
        
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(["sucesso" => false, "erro" => "Apenas administradores podem editar demandas!"]);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);
        $titulo = trim($dados['titulo'] ?? '');
        $descricao = trim($dados['descricao'] ?? '');
        $prioridade = trim($dados['prioridade'] ?? '');
        $categoria = trim($dados['categoria'] ?? '');
        $prazo_input = trim($dados['prazo'] ?? '');
        $id_projeto = (int) ($dados['id_projeto'] ?? 0);
        $quantidade_alunos = isset($dados['quantidade_alunos']) ? (int) $dados['quantidade_alunos'] : 0;

        $prioridadesValidas = ['alta', 'media', 'baixa'];

        if ($id_demanda <= 0 || strlen($titulo) < 3 || !in_array($prioridade, $prioridadesValidas, true) ||
            empty($categoria) || $id_projeto <= 0 || empty($prazo_input) || $quantidade_alunos < 1 || $quantidade_alunos > 10) {
            echo json_encode(['sucesso' => false, 'erro' => 'Campos inválidos!']);
            exit;
        }

        try {
            $prazo = new DateTime($prazo_input);
        } catch (Exception $e) {
            echo json_encode(['sucesso' => false, 'erro' => 'Prazo inválido!']);
            exit;
        }

        $auth = new autorizacao();
        if (!$auth->usuarioPodeAcessarProjeto((int) $_SESSION['id_usuario'], $id_projeto)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso ao projeto informado.']);
            exit;
        }

        if (!(new categoria_model())->pertenceAoProjeto($categoria, $id_projeto)) {
            echo json_encode(['sucesso' => false, 'erro' => 'A categoria não pertence ao projeto informado.']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->atualizar_demanda($id_demanda, $titulo, $descricao, $prioridade, $categoria, $prazo, $id_projeto, $quantidade_alunos, (int) $_SESSION['id_usuario']);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao atualizar demanda.')
        ]);
        exit;
    }

    // ===== EXCLUIR DEMANDA =====
    else if ($action === 'excluir_demanda') {
        
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem excluir demandas!']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true);
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);

        if ($id_demanda <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido!']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->excluir_demanda($id_demanda, (int) $_SESSION['id_usuario']);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao excluir demanda.')
        ]);
        exit;
    }

    // ===== ASSUMIR DEMANDA =====
    else if ($action === 'assumir_demanda') {
        if (in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(["sucesso" => false, "erro" => "Administradores não podem assumir demandas."]);
            exit;
        }
        
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados) {
            echo json_encode(["sucesso" => false, "erro" => "JSON inválido!"]);
            exit;
        }

        $id_demanda = (int) ($dados['id_demanda'] ?? 0);

        if ($id_demanda <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID da demanda inválido!"]);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->assumir_demanda($id_demanda, (int) $_SESSION['id_usuario']);

        echo json_encode([
            "sucesso" => $resultado['status'] === 1,
            "message" => $resultado['message'] ?? null,
            "erro" => $resultado['status'] === 1 ? null : ($resultado['message'] ?? "Erro ao assumir demanda!")
        ]);
        exit;
    }

    // ===== CONCLUIR DEMANDA =====
    else if ($action === 'concluir_demanda') {
        
        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);
        $situacao = trim($dados['situacao'] ?? '');
        $solucao = trim($dados['solucao'] ?? '');
        $justificativa = trim($dados['justificativa'] ?? '');

        if ($id_demanda <= 0) {
            echo json_encode(["sucesso" => false, "erro" => "ID da demanda inválido!"]);
            exit;
        }

        $situacoesValidas = ['concluida', 'nao_concluida'];
        if (!in_array($situacao, $situacoesValidas, true)) {
            echo json_encode(["sucesso" => false, "erro" => "Situação inválida!"]);
            exit;
        }

        if ($situacao === 'nao_concluida' && empty($justificativa)) {
            echo json_encode(["sucesso" => false, "erro" => "Justificativa é obrigatória para demandas não concluídas!"]);
            exit;
        }

        $demanda = new demanda_model();
        $res = $demanda->concluir_demanda($id_demanda, (int) $_SESSION['id_usuario'], $situacao, $solucao, $justificativa);

        echo json_encode([
            'sucesso' => $res['status'] === 1,
            'erro' => $res['status'] === 1 ? null : ($res['message'] ?? 'Erro ao concluir demanda.'),
            'message' => $res['status'] === 1 ? 'Demanda concluída com sucesso.' : null
        ]);
        exit;
    }

    // ===== VALIDAR JUSTIFICATIVA DE NÃO CONCLUSÃO =====
    else if ($action === 'validar_justificativa') {
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem validar justificativas.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);
        $decisao = trim((string) ($dados['decisao'] ?? ''));

        if ($id_demanda <= 0 || !in_array($decisao, ['validada', 'invalidada'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Dados de validação inválidos.']);
            exit;
        }

        $demanda = new demanda_model();
        $res = $demanda->validar_justificativa($id_demanda, (int) $_SESSION['id_usuario'], $decisao);
        echo json_encode([
            'sucesso' => $res['status'] === 1,
            'message' => $res['status'] === 1 ? ($res['message'] ?? null) : null,
            'erro' => $res['status'] === 1 ? null : ($res['message'] ?? 'Erro ao validar justificativa.')
        ]);
        exit;
    }

    // ===== LISTAR ORIENTADORES =====
    else if ($action === 'listar_orientadores') {
        $demanda = new demanda_model();
        echo json_encode([
            'sucesso' => true,
            'dados' => $demanda->listar_orientadores()
        ]);
        exit;
    }

    // ===== LISTAR ATENDENTES PARA ATRIBUIÇÃO =====
    else if ($action === 'listar_atendentes') {
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem acessar!']);
            exit;
        }

        $demanda = new demanda_model();
        echo json_encode(['sucesso' => true, 'dados' => $demanda->listar_atendentes()]);
        exit;
    }

    // ===== LISTAR ATRIBUIÇÕES ATIVAS DA DEMANDA (ADMIN) =====
    else if ($action === 'listar_atribuicoes_ativas') {
        if (!in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem acessar!']);
            exit;
        }

        $id_demanda = (int) ($_GET['id_demanda'] ?? 0);
        if ($id_demanda <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID da demanda inválido.']);
            exit;
        }

        $demanda = new demanda_model();
        echo json_encode(['sucesso' => true, 'dados' => $demanda->listar_atribuicoes_ativas($id_demanda)]);
        exit;
    }

    // ===== DESVINCULAR ATRIBUIÇÃO (ADMIN) =====
    else if ($action === 'desvincular_atribuicao') {
        if (!in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem desvincular usuários.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);
        $id_atribuicao = (int) ($dados['id_atribuicao'] ?? 0);
        if ($id_demanda <= 0 || $id_atribuicao <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'Dados da atribuição inválidos.']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->desvincular_atribuicao_admin($id_demanda, $id_atribuicao);
        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['status'] === 1 ? $resultado['message'] : null,
            'novo_status' => $resultado['novo_status'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao desvincular usuário.')
        ]);
        exit;
    }

    // ===== LISTAR HISTÓRICO (DEMANDAS FINALIZADAS) =====
    else if ($action === 'listar_historico') {
        
        $pesquisa = $_GET['pesquisa'] ?? null;
        $prioridade = $_GET['prioridade'] ?? null;
        $categoria = $_GET['categoria'] ?? null;
        $id_projeto = isset($_GET['id_projeto']) ? (int) $_GET['id_projeto'] : null;
        $situacao = $_GET['situacao'] ?? null;
        $id_responsavel = isset($_GET['id_responsavel']) ? (int)$_GET['id_responsavel'] : null;
        $data_inicio = $_GET['data_inicio'] ?? null;
        $data_fim = $_GET['data_fim'] ?? null;

        $demanda = new demanda_model();
        $dados = $demanda->listar_historico($pesquisa, $prioridade, $categoria, $id_projeto, $situacao, $id_responsavel, $data_inicio, $data_fim, $id_usuario);

        echo json_encode(["sucesso" => true, "dados" => $dados]);
        exit;
    }

    // ===== SOLICITAR REABERTURA =====
    else if ($action === 'solicitar_reabertura') {
        
        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);
        $justificativa = trim($dados['justificativa'] ?? '');

        if ($id_demanda <= 0 || empty($justificativa)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos!']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->solicitar_reabertura($id_demanda, (int) $_SESSION['id_usuario'], $justificativa);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao solicitar reabertura.')
        ]);
        exit;
    }

    // ===== REABRIR DEMANDA (ADMINISTRADOR) =====
    else if ($action === 'reabrir_demanda') {
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem reabrir demandas diretamente.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_demanda = (int) ($dados['id_demanda'] ?? 0);
        $prazo_input = trim((string) ($dados['prazo'] ?? ''));
        if ($id_demanda <= 0 || $prazo_input === '') {
            echo json_encode(['sucesso' => false, 'erro' => 'Informe a demanda e o novo prazo.']);
            exit;
        }

        try {
            $prazo = new DateTime($prazo_input);
        } catch (Exception $exception) {
            echo json_encode(['sucesso' => false, 'erro' => 'Prazo inválido.']);
            exit;
        }

        if ($prazo < new DateTime('today')) {
            echo json_encode(['sucesso' => false, 'erro' => 'O novo prazo não pode ser anterior à data atual.']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->reabrir_demanda($id_demanda, (int) $_SESSION['id_usuario'], $prazo);
        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['status'] === 1 ? ($resultado['message'] ?? 'Demanda reaberta.') : null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Não foi possível reabrir a demanda.')
        ]);
        exit;
    }

    // ===== LISTAR SOLICITAÇÕES DE REABERTURA (ADMIN) =====
    else if ($action === 'listar_solicitacoes_reabertura') {
        
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem acessar!']);
            exit;
        }

        $demanda = new demanda_model();
        $dados = $demanda->listar_solicitacoes_reabertura_pendentes($id_usuario);

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
        exit;
    }

    // ===== APROVAR REABERTURA (ADMIN) =====
    else if ($action === 'aprovar_reabertura') {
        
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem aprovar!']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_solicitacao = (int) ($dados['id_solicitacao'] ?? 0);

        if ($id_solicitacao <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido!']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->aprovar_reabertura($id_solicitacao, (int) $_SESSION['id_usuario']);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao aprovar.')
        ]);
        exit;
    }

    // ===== REJEITAR REABERTURA (ADMIN) =====
    else if ($action === 'rejeitar_reabertura') {
        
        if (!in_array(($_SESSION['tipo'] ?? ''), ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem rejeitar!']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id_solicitacao = (int) ($dados['id_solicitacao'] ?? 0);

        if ($id_solicitacao <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido!']);
            exit;
        }

        $demanda = new demanda_model();
        $resultado = $demanda->rejeitar_reabertura($id_solicitacao, (int) $_SESSION['id_usuario']);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao rejeitar.')
        ]);
        exit;
    }

    else {
        echo json_encode(["sucesso" => false, "erro" => "Ação não reconhecida!"]);
        exit;
    }
}

?>
