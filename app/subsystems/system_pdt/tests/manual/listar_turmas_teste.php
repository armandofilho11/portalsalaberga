<?php
session_start();
$_SESSION['id_usuario'] = 2;
$_SESSION['tipo'] = 'gestor';

require_once __DIR__ . '/../../app/main/models/turmas_model.php';

$turmaModel = new TurmasModel();
$turmas = $turmaModel->listarTurmas();

echo json_encode([
    'total' => count($turmas),
    'turmas' => $turmas
]);
