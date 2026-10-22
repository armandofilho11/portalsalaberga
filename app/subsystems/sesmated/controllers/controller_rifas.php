<?php
require_once('../models/main.model.php');

//adicionar turma
if (
    isset($_POST['turma']) && !empty($_POST['turma']) &&
    isset($_POST['rifas']) &&
    isset($_POST['id_usuario']) && !empty($_POST['id_usuario'])
) {
    $id_turma = $_POST['turma'];
    $rifas = $_POST['rifas'];
    $id_usuario = $_POST['id_usuario'];

    $main_model = new main_model();
    $result = $main_model->adicionar_turma($id_turma, $rifas, $id_usuario);

    switch ($result) {
        case 1:
            header('location:../views/rifa.php?turma_adicionada');
            exit();
        case 2:
            header('location:../views/rifa.php?erro');
            exit();
        case 3:
            header('location:../views/rifa.php?ja_adicionada');
            exit();
    }
} else {

    header('location:../views/rifa.php?empty');
    exit();
}
