<?php
session_start();

require_once '../model/model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $idade = $_POST['idade'] ?? '';
    $turma = $_POST['turma'] ?? '';
    $deficiencia = ''; // Empty string since we moved this to a separate form

    // Validate required fields
    if (empty($nome) || empty($idade) || empty($turma)) {
        header("Location: ../view/Registro.php?erro=1");
        exit;
    }

    try {
        $model = new Model();
        if ($model->Registrar($nome, $idade, $turma, $deficiencia)) {
            header("Location: ../view/menu.php");
        } else {
            header("Location: ../view/Registro.php?erro=1");
        }
    } catch (Exception $e) {
        error_log("Erro ao registrar aluno: " . $e->getMessage());
        header("Location: ../view/Registro.php?erro=1");
    }
} else {
    header("Location: ../view/Registro.php");
}

if (!empty($_POST['aluno_id']) || !empty($_POST['data']) || !empty($_POST['relatorio_dia']) || !empty($_POST['historico_medico'])) {
    $aluno_id = $_POST['aluno_id'];
    $data = $_POST['data'];
    $relatorio_dia = $_POST['relatorio_dia'];
    $historico_medico = $_POST['historico_medico'];
    
    function registrarDiaMedico($aluno_id, $data, $relatorio_dia, $historico_medico) {
        $model = new Model();
        return $model->RegistrarDiaMedico($aluno_id, $data, $relatorio_dia, $historico_medico);
    }
    
    if (registrarDiaMedico($aluno_id, $data, $relatorio_dia, $historico_medico)) {
        header("Location: ../view/menu.php");
    } else {
        header("Location: ../view/RegistroDiaMedico.php?erro");
    }
}
?>