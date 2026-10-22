<?php
session_start();
require_once '../model/Opiniao.class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_sugestao'])) {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit();
}

$id_sugestao = filter_var($_POST['id_sugestao'], FILTER_VALIDATE_INT);
if ($id_sugestao === false) {
    echo json_encode(['success' => false, 'message' => 'ID da sugestão inválido.']);
    exit();
}

$opiniao = new Opiniao();
$result = $opiniao->excluirSugestao($id_sugestao);

echo json_encode($result);
?>