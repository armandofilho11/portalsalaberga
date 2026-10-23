<?php
session_start();
require_once('../config/security.php');
require_once('../models/notificacao_model.php');

iniciarSessaoSegura();
exigirAutenticacao(true);

if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['sucesso' => false, 'erro' => 'Usuário não autenticado']);
    exit;
}

require_once('../models/frequencia_model.php');

$action = $_GET['action'] ?? '';
header('Content-Type: application/json; charset=utf-8');

switch ($action) {
    case 'registrar_frequencia':
        registrarFrequencia();
        break;
    case 'listar_frequencia':
        listarFrequencia();
        break;
    case 'contar_frequencia':
        contarFrequencia();
        break;
    default:
        echo json_encode(['sucesso' => false, 'erro' => 'Ação não reconhecida']);
        break;
}

function registrarFrequencia()
{
    $dados = json_decode(file_get_contents('php://input'), true);
    if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
        echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
        return;
    }
    if (!$dados) {
        echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido']);
        return;
    }

    $turmaId = isset($dados['id_turma']) ? intval($dados['id_turma']) : 0;
    if (!validarPermissaoTurmaParaAcao($turmaId)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão para gerenciar esta turma']);
        return;
    }
    $data = trim($dados['data'] ?? '');
    $registros = $dados['registros'] ?? [];
    $professorId = $_SESSION['id_usuario'];

    if (!$turmaId || !$data || !is_array($registros)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos ou inválidos']);
        return;
    }

    $model = new FrequenciaModel();
    $salvo = true;

    foreach ($registros as $registro) {
        $idAluno = isset($registro['id_aluno']) ? intval($registro['id_aluno']) : 0;
        $status = trim($registro['status'] ?? '');
        if (!$idAluno || !in_array($status, ['presente', 'falta'], true)) {
            continue;
        }
        $ok = $model->salvarFrequencia($turmaId, $idAluno, $professorId, $data, $status);
        if (!$ok) {
            $salvo = false;
        }
    }

    if ($salvo) {
        $notificacoes = new NotificacaoModel();
        $notificacoes->criar(
            (int) ($_SESSION['id_usuario'] ?? 0),
            'Frequência lançada',
            'A frequência da turma foi registrada.',
            'success',
            null,
            'frequencia',
            $turmaId
        );
        echo json_encode(['sucesso' => true, 'mensagem' => 'Frequência registrada com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Falha ao salvar alguns registros de frequência']);
    }
}

function listarFrequencia()
{
    $turmaId = isset($_GET['id_turma']) ? intval($_GET['id_turma']) : 0;
    $data = trim($_GET['data'] ?? '');
    $professorId = $_SESSION['id_usuario'];

    if (!$turmaId || !$data) {
        echo json_encode(['sucesso' => false, 'erro' => 'Parâmetros inválidos']);
        return;
    }

    $model = new FrequenciaModel();
    $dados = $model->listarPorTurmaData($turmaId, $data, $professorId);
    echo json_encode(['sucesso' => true, 'dados' => $dados]);
}

function contarFrequencia()
{
    $professorId = $_SESSION['id_usuario'];
    $model = new FrequenciaModel();
    $media = $model->calcularMediaMensal($professorId);
    echo json_encode(['sucesso' => true, 'media' => $media]);
}
