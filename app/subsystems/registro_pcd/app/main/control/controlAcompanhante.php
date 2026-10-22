<?php
session_start();
require_once '../model/model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar e sanitizar os dados recebidos
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);

        // Validar campos obrigatórios
        if (!$nome) {
            throw new Exception("O nome é obrigatório.");
        }

        // Criar instância do Model e salvar os dados
        $model = new Model();
        $resultado = $model->registrarAcompanhante($nome);

        if ($resultado) {
            header('Location: ../view/menu.php?sucesso=1');
            exit;
        } else {
            throw new Exception("Erro ao salvar o registro do acompanhante.");
        }

    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header('Location: ../view/RegistroAcompanhante.php?erro=1');
        exit;
    }
} else {
    header('Location: ../view/RegistroAcompanhante.php');
    exit;
} 