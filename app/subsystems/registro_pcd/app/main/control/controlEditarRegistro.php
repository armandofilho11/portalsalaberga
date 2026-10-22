<?php
require_once '../config/verificar_sessao.php';
require_once '../model/model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/visualizar.php');
    exit;
}

try {
    $model = new Model();
    
    // Validar e sanitizar os dados
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
    $turma = filter_input(INPUT_POST, 'turma', FILTER_SANITIZE_STRING);
    $deficiencia = filter_input(INPUT_POST, 'deficiencia', FILTER_SANITIZE_STRING);
    $presenca = filter_input(INPUT_POST, 'presenca', FILTER_VALIDATE_INT);
    $observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING);

    if (!$id || !$nome || !$idade || !$turma) {
        throw new Exception('Dados inválidos');
    }

    // Atualizar o registro
    $model->atualizarRegistro($id, [
        'nome' => $nome,
        'idade' => $idade,
        'turma' => $turma,
        'deficiencia' => $deficiencia,
        'presenca' => $presenca,
        'observacoes' => $observacoes
    ]);

    header('Location: ../view/visualizar.php?success=1');
} catch (Exception $e) {
    header('Location: ../view/visualizar.php?error=' . urlencode($e->getMessage()));
} 