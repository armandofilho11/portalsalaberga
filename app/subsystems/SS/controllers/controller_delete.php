<?php 

if (isset($_POST['senha'])) {
    $senha = $_POST['senha'];
    require_once('../models/model.php');
    $resultado = delete($senha);
    
    if ($resultado === true) {
        header('Location: ../views/delete.php');
        exit();
    } else {
        header('Location: ../views/inicio_ADM.php?delete=erro');
        exit();
    }
}