<?php
// Simple test to list turmas

session_start();

// Mock user session if needed
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['id_usuario'] = 1;
    $_SESSION['tipo'] = 'gestor';
}

// Now call the controller
require_once __DIR__ . '/../../app/main/models/turmas_model.php';

$turma = new TurmasModel();
$dados = $turma->listarTurmas();

echo json_encode([
    'total' => count($dados),
    'data' => $dados
]);
