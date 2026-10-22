<?php
require_once("../model/modelprofessor.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $professor = new professor();
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $resultado = false;

    if ($action === 'filter') {
        // Just redirect back to show filtered results
        header("Location: ../view/professor/zerar_status.php");
        exit();
    } elseif ($action === 'zerar_selecionadas' && isset($_POST['questoes']) && is_array($_POST['questoes'])) {
        // Zerar status das questões selecionadas individualmente
        $resultado = true;
        foreach ($_POST['questoes'] as $id_questao) {
            $resultado = $resultado && $professor->zerar_status_questoes(null, null, [$id_questao]);
        }
    } else {
        // Zerar status usando filtros
        $disciplina = isset($_POST['disciplina']) ? trim($_POST['disciplina']) : null;
        $subtopico = isset($_POST['subtopico']) ? trim($_POST['subtopico']) : null;
        $resultado = $professor->zerar_status_questoes($disciplina, $subtopico);
    }

    if ($resultado) {
        header("Location: ../view/professor/zerar_status.php?success=1");
    } else {
        header("Location: ../view/professor/zerar_status.php?error=1");
    }
    exit();
} else {
    header("Location: ../view/professor/zerar_status.php");
    exit();
}
?> 