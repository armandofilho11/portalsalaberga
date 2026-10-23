<?php
// Test turma creation flow

session_start();
$_SESSION['id_usuario'] = 2;
$_SESSION['tipo'] = 'gestor';

require_once __DIR__ . '/../../app/main/models/turmas_model.php';

$turmaModel = new TurmasModel();

// Test data
$id_curso = 1;
$serie = 1;
$sala = '04';
$ano = 2026;
$descricao = 'Turma de teste';
$id_professor = 3;  // ID do professor que criamos

// Test cadastro
$resultado = $turmaModel->cadastrarTurma(
    $id_curso,
    $serie,
    $sala,
    $ano,
    $descricao,
    $id_professor
);

echo json_encode([
    'resultado' => $resultado,
    'mensagem' => 'Turma cadastrada com sucesso!'
]);
