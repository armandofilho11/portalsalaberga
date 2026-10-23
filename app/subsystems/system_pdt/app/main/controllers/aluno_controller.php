<?php
session_start();
require_once('../config/security.php');
require_once('../models/aluno_model.php');
require_once('../models/notificacao_model.php');

iniciarSessaoSegura();
exigirAutenticacao(true);

function obterDadosRequisicao(): array
{
    $raw = file_get_contents('php://input');
    $dados = json_decode($raw, true);
    if (!is_array($dados)) {
        $dados = $_POST ?? [];
    }
    return $dados;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? ($_POST['action'] ?? '');

    header('Content-Type: application/json; charset=utf-8');

    if ($action === 'perfil_pedagogico') {
        $idAluno = filter_var($_GET['id_aluno'] ?? null, FILTER_VALIDATE_INT);
        if (!$idAluno || $idAluno < 1) {
            http_response_code(422);
            echo json_encode(['sucesso' => false, 'erro' => 'Aluno inválido']);
            exit;
        }

        try {
            $model = new AlunoModel();
            $aluno = $model->buscarAlunoPorId($idAluno);
            $idTurma = (int) ($aluno['id_turma'] ?? 0);
            if (
                !$idTurma
                || !podeVisualizarTurma(
                    (new connect())->getConnection(),
                    $idTurma,
                    (int) ($_SESSION['id_usuario'] ?? 0)
                )
            ) {
                http_response_code(403);
                echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para visualizar este perfil']);
                exit;
            }

            $perfil = $model->obterPerfilPedagogico($idAluno);
            if (!$perfil) {
                http_response_code(404);
                echo json_encode(['sucesso' => false, 'erro' => 'Aluno não encontrado']);
                exit;
            }
            echo json_encode(['sucesso' => true, 'dados' => $perfil]);
        } catch (RuntimeException $e) {
            error_log('Erro ao carregar perfil pedagógico: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível carregar o perfil agora']);
        }
        exit;

    } else if ($action === 'listar_alunos') {
        $id_turma = isset($_GET['id_turma']) ? intval($_GET['id_turma']) : (isset($_POST['id_turma']) ? intval($_POST['id_turma']) : null);

        if (!$id_turma || !podeVisualizarTurma((new connect())->getConnection(), $id_turma, (int) ($_SESSION['id_usuario'] ?? 0))) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para visualizar esta turma']);
            exit;
        }

        $model = new AlunoModel();
        $dados = $model->listarPorTurma($id_turma);

        echo json_encode([
            'sucesso' => true,
            'dados' => $dados
        ]);
        exit;

    } else if ($action === 'listar_notas') {
        $id_turma = isset($_GET['id_turma']) ? intval($_GET['id_turma']) : (isset($_POST['id_turma']) ? intval($_POST['id_turma']) : null);
        $bimestre = isset($_GET['bimestre']) ? intval($_GET['bimestre']) : (isset($_POST['bimestre']) ? intval($_POST['bimestre']) : 0);
        $disciplina = isset($_GET['disciplina']) ? trim($_GET['disciplina']) : (isset($_POST['disciplina']) ? trim($_POST['disciplina']) : '');

        if (!$id_turma || !podeVisualizarTurma((new connect())->getConnection(), $id_turma, (int) ($_SESSION['id_usuario'] ?? 0))) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para visualizar esta turma']);
            exit;
        }

        $model = new AlunoModel();
        $dados = $model->listarNotasPorTurmaBimestre($id_turma, $bimestre, $disciplina);

        echo json_encode([
            'sucesso' => true,
            'dados' => $dados
        ]);
        exit;

    } else if ($action === 'cadastrar_aluno') {

        $dados = obterDadosRequisicao();
        if (!$dados) {
            echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido ou dados ausentes']);
            exit;
        }

        if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
            echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
            exit;
        }

        $nome = trim($dados['nome'] ?? '');
        $matricula = trim($dados['matricula'] ?? '');
        $email = trim($dados['email'] ?? '');
        $pcd = ($dados['pcd'] ?? 'nao') ?: 'nao';
        $rendimento = floatval($dados['rendimento'] ?? 0);
        $turma_id = isset($dados['turma_id']) ? intval($dados['turma_id']) : null;

        if (!$turma_id) {
            echo json_encode(['sucesso' => false, 'erro' => 'Turma é obrigatória']);
            exit;
        }

        if (!validarPermissaoTurmaParaAcao($turma_id)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para gerenciar esta turma']);
            exit;
        }

        // 1. Validar nome
        if (!$nome) {
            echo json_encode(['sucesso' => false, 'erro' => 'Nome é obrigatório']);
            exit;
        }

        if (strlen($nome) < 3) {
            echo json_encode(['sucesso' => false, 'erro' => 'Nome deve ter no mínimo 3 caracteres']);
            exit;
        }

        // 2. Validar matrícula
        if (!preg_match('/^\d{7}$/', $matricula)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Matrícula deve ter exatamente 7 dígitos numéricos']);
            exit;
        }

        // 3. Validar email com filter_var
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Email inválido ou não informado']);
            exit;
        }

        // 4. Validar turma_id obrigatória
        if (!$turma_id) {
            echo json_encode(['sucesso' => false, 'erro' => 'Turma é obrigatória']);
            exit;
        }

        // 5. Validar PCD
        if (!in_array($pcd, ['sim', 'nao'])) {
            $pcd = 'nao';
        }

        // 6. Validar rendimento
        if ($rendimento < 0 || $rendimento > 10) {
            $rendimento = 0;
        }

        // 7. Verificar se turma existe (antes de cadastrar)
        try {
            require_once('../models/turmas_model.php');
            $turmasModel = new TurmasModel();
            $turmaExiste = $turmasModel->obterTurmaById($turma_id);
            if (!$turmaExiste) {
                echo json_encode(['sucesso' => false, 'erro' => 'Turma não encontrada no sistema']);
                exit;
            }
        } catch (Exception $e) {
            error_log('Erro ao verificar turma: ' . $e->getMessage());
            // continua mesmo com erro (compatibilidade)
        }

        $model = new AlunoModel();
        $result = $model->cadastrarAluno($nome, $matricula, $email, $pcd, $rendimento, $turma_id);
        $notificacoes = new NotificacaoModel();
        if (!empty($result['success'])) {
            $notificacoes->criar(
                (int) ($_SESSION['id_usuario'] ?? 0),
                'Aluno cadastrado',
                'O aluno ' . $nome . ' foi cadastrado na turma.',
                'success',
                null,
                'aluno',
                $result['id'] ?? null
            );
        }

        if (is_array($result)) {
            if (!empty($result['success'])) {
                echo json_encode(['sucesso' => true, 'id_aluno' => $result['id']]);
            } else {
                echo json_encode(['sucesso' => false, 'erro' => $result['error'] ?? 'Erro ao cadastrar aluno']);
            }
        } else {
            // compat fallback
            if ($result) {
                echo json_encode(['sucesso' => true, 'id_aluno' => $result]);
            } else {
                echo json_encode(['sucesso' => false, 'erro' => 'Erro ao cadastrar aluno']);
            }
        }

        exit;

    } else if ($action === 'excluir_aluno') {

        $dados = obterDadosRequisicao();
        if (!$dados) {
            echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido ou dados ausentes']);
            exit;
        }

        if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
            echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
            exit;
        }

        $id = isset($dados['id_aluno']) ? intval($dados['id_aluno']) : 0;
        $turma_id = isset($dados['turma_id']) ? intval($dados['turma_id']) : 0;

        if (!$turma_id && $id) {
            $model = new AlunoModel();
            $aluno = $model->buscarAlunoPorId($id);
            if (!empty($aluno)) {
                $turma_id = isset($aluno['turma_id']) ? intval($aluno['turma_id']) : (isset($aluno['id_turma']) ? intval($aluno['id_turma']) : 0);
            }
        }

        if (!validarPermissaoTurmaParaAcao($turma_id)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para gerenciar esta turma']);
            exit;
        }

        if (!$id) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido']);
            exit;
        }

        $model = new AlunoModel();
        $ok = $model->excluirAluno($id);
        $notificacoes = new NotificacaoModel();
        if ($ok) {
            $notificacoes->criar(
                (int) ($_SESSION['id_usuario'] ?? 0),
                'Aluno excluído',
                'Um aluno foi removido da turma.',
                'warning',
                null,
                'aluno',
                $id
            );
        }

        if ($ok) echo json_encode(['sucesso' => true]);
        else echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir aluno']);

        exit;

    } else if ($action === 'salvar_notas') {

        $dados = obterDadosRequisicao();
        if (!$dados) {
            echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido ou dados ausentes']);
            exit;
        }

        if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
            echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
            exit;
        }

        $id_aluno = isset($dados['id_aluno']) ? intval($dados['id_aluno']) : 0;
        $bimestre = isset($dados['bimestre']) ? intval($dados['bimestre']) : 0;
        $disciplina = isset($dados['disciplina']) ? trim($dados['disciplina']) : null;
        $media_final = isset($dados['media_final']) ? $dados['media_final'] : null;
        $id_turma = isset($dados['id_turma']) ? (intval($dados['id_turma']) ?: null) : (isset($dados['id_turma']) ? $dados['id_turma'] : null);

        if (!validarPermissaoTurmaParaAcao($id_turma)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para gerenciar esta turma']);
            exit;
        }

        if (!$id_aluno || !$bimestre || !$disciplina) {
            echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos ou disciplina ausente']);
            exit;
        }

        error_log('[salvar_notas] id_aluno='.$id_aluno.' bimestre='.$bimestre.' disciplina='.$disciplina.' media_final='.$media_final.' id_turma='.$id_turma);
        $model = new AlunoModel();
        $res = $model->salvarNotas($id_aluno, $bimestre, $media_final, $id_turma, $_SESSION['id_usuario'] ?? null, $disciplina);
        error_log('[salvar_notas] response: '.json_encode($res));

        if (!empty($res['success'])) echo json_encode(['sucesso' => true]);
        else echo json_encode(['sucesso' => false, 'erro' => $res['error'] ?? 'Erro ao salvar nota']);
        exit;

    } else if ($action === 'salvar_notas_lote') {

        $dados = json_decode(file_get_contents('php://input'), true);
        if (!$dados) {
            echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido']);
            exit;
        }

        if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
            echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
            exit;
        }

        $bimestre = isset($dados['bimestre']) ? intval($dados['bimestre']) : 0;
        $disciplina = isset($dados['disciplina']) ? trim($dados['disciplina']) : null;
        $notas = isset($dados['notas']) && is_array($dados['notas']) ? $dados['notas'] : [];
        $id_turma = isset($dados['id_turma']) ? (intval($dados['id_turma']) ?: null) : (isset($dados['id_turma']) ? $dados['id_turma'] : null);

        if (!validarPermissaoTurmaParaAcao($id_turma)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para gerenciar esta turma']);
            exit;
        }

        if (!$bimestre || !$disciplina || empty($notas)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos ou disciplina ausente']);
            exit;
        }

        $model = new AlunoModel();
        $res = $model->salvarNotasLote($bimestre, $notas, $id_turma, $_SESSION['id_usuario'] ?? null, $disciplina);

        if (!empty($res['success'])) echo json_encode(['sucesso' => true]);
        else echo json_encode(['sucesso' => false, 'erro' => $res['error'] ?? 'Erro ao salvar notas em lote']);
        exit;

    } else if ($action === 'contar_alunos') {

        $id_professor = isset($_GET['id_professor']) ? intval($_GET['id_professor']) : 0;
        if (!$id_professor) {
            $id_professor = $_SESSION['id_usuario'] ?? 0;
        }

        if (!$id_professor) {
            echo json_encode(['sucesso' => false, 'erro' => 'Professor não identificado']);
            exit;
        }
        if ($bimestre < 1 || $bimestre > 4) {
            http_response_code(422);
            echo json_encode(['sucesso' => false, 'erro' => 'Bimestre inválido']);
            exit;
        }

        $model = new AlunoModel();
        $total = $model->contarAlunosPorProfessor($id_professor);

        echo json_encode(['sucesso' => true, 'total' => $total]);
        exit;

    } else if ($action === 'editar_aluno') {

        $dados = json_decode(file_get_contents('php://input'), true);
        if (!$dados) {
            echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido']);
            exit;
        }

        if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
            echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
            exit;
        }

        $id_aluno = isset($dados['id_aluno']) ? intval($dados['id_aluno']) : 0;
        $turma_id = isset($dados['turma_id']) ? intval($dados['turma_id']) : 0;
        $nome = trim($dados['nome'] ?? '');
        $matricula = trim($dados['matricula'] ?? '');
        $email = trim($dados['email'] ?? '');
        $pcd = ($dados['pcd'] ?? 'nao') ?: 'nao';
        $rendimento = floatval($dados['rendimento'] ?? 0);

        if (!$turma_id && $id_aluno) {
            $model = new AlunoModel();
            $aluno = $model->buscarAlunoPorId($id_aluno);
            if (!empty($aluno)) {
                $turma_id = isset($aluno['turma_id']) ? intval($aluno['turma_id']) : (isset($aluno['id_turma']) ? intval($aluno['id_turma']) : 0);
            }
        }

        if (!validarPermissaoTurmaParaAcao($turma_id)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para gerenciar esta turma']);
            exit;
        }

        if (!$id_aluno) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID do aluno inválido']);
            exit;
        }

        if (!$nome) {
            echo json_encode(['sucesso' => false, 'erro' => 'Nome é obrigatório']);
            exit;
        }

        if (!preg_match('/^\d{7}$/', $matricula)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Matrícula deve ter exatamente 7 dígitos numéricos']);
            exit;
        }

        if (!$email) {
            echo json_encode(['sucesso' => false, 'erro' => 'Email é obrigatório']);
            exit;
        }

        $model = new AlunoModel();
        $result = $model->atualizarAluno($id_aluno, $nome, $matricula, $email, $pcd, $rendimento);
        $notificacoes = new NotificacaoModel();
        if (!empty($result['success'])) {
            $notificacoes->criar(
                (int) ($_SESSION['id_usuario'] ?? 0),
                'Aluno editado',
                'Os dados do aluno foram atualizados.',
                'info',
                null,
                'aluno',
                $id_aluno
            );
        }

        if (!empty($result['success'])) {
            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'erro' => $result['error'] ?? 'Erro ao editar aluno']);
        }

        exit;

    } else if ($action === 'desempenho_turma') {

        $id_turma = isset($_GET['id_turma']) ? intval($_GET['id_turma']) : 0;
        if (!$id_turma) {
            require_once('../models/turmas_model.php');
            $turmasModel = new TurmasModel();
            $turmas = $turmasModel->listarTurmas((int) ($_SESSION['id_usuario'] ?? 0));
            if (!empty($turmas) && isset($turmas[0]['id_turma'])) {
                $id_turma = intval($turmas[0]['id_turma']);
            }
        }

        if (!$id_turma) {
            echo json_encode(['sucesso' => false, 'erro' => 'Turma não informada']);
            exit;
        }

        if (!validarPermissaoTurmaParaAcao($id_turma)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para acessar o desempenho desta turma']);
            exit;
        }

        try {
            $model = new AlunoModel();
            $desempenho = $model->calcularDesempenhoPorTurma($id_turma);
        } catch (RuntimeException $e) {
            error_log('Erro ao consultar desempenho da turma: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível consultar o desempenho agora']);
            exit;
        }

        if (!$desempenho) {
            echo json_encode(['sucesso' => false, 'erro' => 'Turma não encontrada ou sem dados suficientes']);
            exit;
        }

        echo json_encode(['sucesso' => true, 'dados' => $desempenho]);
        exit;

    } else if ($action === 'calcular_desempenho') {

        $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
        if (!in_array($tipoUsuario, ['professor', 'gestor'], true)) {
            http_response_code(403);
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para consultar este desempenho']);
            exit;
        }

        $id_professor = $tipoUsuario === 'professor'
            ? (int) ($_SESSION['id_usuario'] ?? 0)
            : (isset($_GET['id_professor']) ? intval($_GET['id_professor']) : 0);
        $bimestre = isset($_GET['bimestre']) ? intval($_GET['bimestre']) : (date('n') <= 3 ? 1 : (date('n') <= 6 ? 2 : (date('n') <= 9 ? 3 : 4)));

        if (!$id_professor) {
            $id_professor = $_SESSION['id_usuario'] ?? 0;
        }

        if (!$id_professor) {
            echo json_encode(['sucesso' => false, 'erro' => 'Professor não identificado']);
            exit;
        }

        try {
            $model = new AlunoModel();
            $desempenho = $model->calcularDesempenhoMedioProfessor($id_professor, $bimestre);
        } catch (RuntimeException $e) {
            error_log('Erro ao consultar desempenho do professor: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível consultar o desempenho agora']);
            exit;
        }

        echo json_encode(['sucesso' => true, 'desempenho' => $desempenho]);
        exit;

    }

}

http_response_code(400);
echo json_encode(['sucesso' => false, 'erro' => 'Ação inválida']);

?>
