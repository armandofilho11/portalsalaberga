<?php
session_start();
require_once '../model/model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar e sanitizar os dados recebidos
        $aluno_id = filter_input(INPUT_POST, 'aluno_id', FILTER_SANITIZE_NUMBER_INT);
        $presenca = filter_input(INPUT_POST, 'presenca', FILTER_SANITIZE_NUMBER_INT);
        $observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING);
        $acompanhante_id = filter_input(INPUT_POST, 'acompanhante_id', FILTER_SANITIZE_NUMBER_INT);

        // Validar campos obrigatórios
        if (!$aluno_id || !isset($presenca) || !$observacoes) {
            throw new Exception("Todos os campos são obrigatórios.");
        }

        
        $model = new Model();
        $resultado = $model->registrarDia($aluno_id, $presenca, $observacoes, $acompanhante_id);

        if ($resultado) {
            $_SESSION['mensagem'] = "Registro realizado com sucesso!";
            $_SESSION['tipo_mensagem'] = 'success';
            header('Location: ../view/menu.php');
            exit;
        } else {
            throw new Exception("Erro ao salvar o registro do dia.");
        }

    } catch (Exception $e) {
        $_SESSION['mensagem'] = $e->getMessage();
        $_SESSION['tipo_mensagem'] = 'error';
        header('Location: ../view/RegistroDia.php');
        exit;
    }
} else {
    header('Location: ../view/RegistroDia.php');
    exit;
} 