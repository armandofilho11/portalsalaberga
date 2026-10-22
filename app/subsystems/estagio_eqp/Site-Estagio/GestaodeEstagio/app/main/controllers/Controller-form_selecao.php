<?php 

require_once '../models/model-function.php';

if(isset($_POST['btn'])){
    // Validar e sanitizar os dados do formulário
    $hora = isset($_POST['hora']) ? trim($_POST['hora']) : '';
    $local = isset($_POST['local']) ? trim($_POST['local']) : '';
    $id_concedente = isset($_POST['id_concedente']) ? intval($_POST['id_concedente']) : 0;

    // Validar se os campos obrigatórios estão preenchidos
    if(empty($hora) || empty($local) || empty($id_concedente)) {
        header("Location: ../views/novo_formulario.php?error=campos_vazios");
        exit;
    }

    try {
        $x = new Cadastro();
        $result = $x->cadastrar_selecao($hora, $local, $id_concedente, null, null, null);
        
        if($result) {
            header("Location: ../views/processoseletivo.php?success=cadastro_realizado");
        } else {
            header("Location: ../views/novo_formulario.php?error=erro_cadastro");
        }
    } catch (PDOException $e) {
        // Log do erro para debug
        error_log("Erro ao cadastrar seleção: " . $e->getMessage());
        header("Location: ../views/novo_formulario.php?error=erro_banco");
    }
}

?>