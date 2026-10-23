<?php
require_once __DIR__ . '/../../app/main/models/turmas_model.php';

$turma = new TurmasModel();
$result = $turma->listarCursos();

echo json_encode([
    'total' => count($result),
    'data' => $result
]);
