<?php
session_start();
require_once('../config/security.php');
require_once('../models/notificacao_model.php');

exigirAutenticacao(true);

$action = $_GET['action'] ?? '';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'erro' => 'Método inválido']);
    exit;
}

if (!validarCsrfToken($_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''))) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'erro' => 'Token CSRF inválido']);
    exit;
}

$model = new NotificacaoModel();
$idUsuario = (int) ($_SESSION['id_usuario'] ?? 0);

switch ($action) {
    case 'listar':
        echo json_encode(['sucesso' => true, 'dados' => $model->listarParaUsuario($idUsuario, 20), 'nao_lidas' => $model->contarNaoLidas($idUsuario)]);
        break;
    case 'marcar_lida':
        $idNotificacao = (int) ($_POST['id_notificacao'] ?? 0);
        echo json_encode(['sucesso' => $model->marcarLida($idNotificacao, $idUsuario)]);
        break;
    case 'marcar_todas_lidas':
        echo json_encode(['sucesso' => $model->marcarTodasLidas($idUsuario)]);
        break;
    default:
        echo json_encode(['sucesso' => false, 'erro' => 'Ação inválida']);
        break;
}
