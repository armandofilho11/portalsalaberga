<?php
require_once('../models/main.model.php');
//confirmar grito
if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['grito']) && 
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $id_curso = $_POST['curso'];
    $grito = $_POST['grito'];
    $id_avaliador = $_POST['id_avaliador'];


    $main_model = new main_model();
    $result = $main_model->confirmar_grito($id_curso, $grito, $id_avaliador);

    switch ($result) {
        case 1:
            header('location:../views/grito.php?confirmado');
            exit();
        case 2:
            header('location:../views/grito.php?erro');
            exit();
        case 3:
            header('location:../views/grito.php?ja_confirmado');
            exit();
    }
}else {

    header('location:../views/grito.php?empty');
    exit();
}