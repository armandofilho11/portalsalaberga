<?php
session_start();
require_once '../model/model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro_id'])) {
    try {
        $model = new Model();
        $registro_id = $_POST['registro_id'];
        
        // Excluir o registro
        $model->excluirRegistro($registro_id);
        
        $_SESSION['mensagem'] = "Registro excluído com sucesso!";
        $_SESSION['tipo_mensagem'] = "success";
    } catch (Exception $e) {
        $_SESSION['mensagem'] = "Erro ao excluir registro: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "error";
    }
} else {
    $_SESSION['mensagem'] = "Requisição inválida!";
    $_SESSION['tipo_mensagem'] = "error";
}

// Redirecionar de volta para a página de visualização
header('Location: ../view/visualizar.php');
exit; 