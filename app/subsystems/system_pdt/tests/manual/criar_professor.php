<?php
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

$modelUsuario = new usuario_model();

$resultado = $modelUsuario->cadastrar(
    'Professor João Silva',
    'PROF001',
    'professor@test.com',
    '12345',
    'professor'
);

echo json_encode([
    'resultado' => $resultado,
    'mensagem' => 'Professor criado com sucesso!'
]);
