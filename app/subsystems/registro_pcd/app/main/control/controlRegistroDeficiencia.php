<?php
require_once '../model/model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = $_POST['aluno_id'] ?? '';
    $deficiencia = $_POST['deficiencia'] ?? '';

    if (empty($aluno_id) || empty($deficiencia)) {
        header('Location: ../view/RegistroDeficiencia.php?erro=1');
        exit;
    }

    try {
        $model = new Model();
        $resultado = $model->registrarDeficiencia($aluno_id, $deficiencia);

        if ($resultado) {
            header('Location: ../view/menu.php?sucesso=deficiencia');
        } else {
            header('Location: ../view/RegistroDeficiencia.php?erro=1');
        }
    } catch (Exception $e) {
        header('Location: ../view/RegistroDeficiencia.php?erro=1');
    }
} else {
    header('Location: ../view/menu.php');
} 