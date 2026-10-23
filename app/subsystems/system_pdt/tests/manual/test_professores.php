<?php
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

$modelUsuario = new usuario_model();
$usuarios = $modelUsuario->listar_usuarios();

$professores = array_filter($usuarios, function($u) {
    return isset($u['tipo']) && strtolower($u['tipo']) === 'professor';
});

echo json_encode([
    'total_usuarios' => count($usuarios),
    'total_professores' => count($professores),
    'professores' => $professores
]);
