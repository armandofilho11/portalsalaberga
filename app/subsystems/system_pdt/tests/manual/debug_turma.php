<?php
// Test with error logging

session_start();
$_SESSION['id_usuario'] = 2;
$_SESSION['tipo'] = 'gestor';

// Enable error logging to file for debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

require_once __DIR__ . '/../../app/main/models/turmas_model.php';

$turmaModel = new TurmasModel();

$resultado = $turmaModel->cadastrarTurma(
    1,      // id_curso
    1,      // serie
    '05',   // sala
    2026,   // ano
    'Turma de teste 2',  // descricao
    3       // id_professor
);

// Also check database directly
echo json_encode([
    'resultado' => $resultado,
    'debug' => 'Check debug.log for errors'
], JSON_PRETTY_PRINT);

// If there was no exception, list turmas
if (is_array($resultado) && isset($resultado['status']) && $resultado['status'] == 1) {
    $turmas = $turmaModel->listarTurmas();
    echo "\n\nTurmas after insert:\n";
    print_r($turmas);
}
