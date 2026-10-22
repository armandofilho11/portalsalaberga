<?php
require_once('../models/adm.model.php');

if (isset($_POST['id_usuario']) && !empty($_POST['id_usuario']) && isset($_POST['id_demanda']) && !empty($_POST['id_demanda'])) {

    $id_usuario = $_POST['id_usuario'];
    $id_demanda = $_POST['id_demanda'];

    $adm_model = new adm_model();
    $result = $adm_model->selecionar_demanda($id_demanda, $id_usuario);

    switch ($result) {
        case 1:
            header('location: ../views/usuario.php?status=selecionado');
            exit();

        case 2:
            header('location: ../views/usuario.php?status=error');
            exit();

        case 3:
            header('location: ../views/usuario.php?status=ja_selecionado');
            exit();
    }
} else if (isset($_POST['id_demanda_concluir']) && !empty($_POST['id_demanda_concluir'])) {

    $id_demanda = $_POST['id_demanda_concluir'];

    $adm_model = new adm_model();
    $result = $adm_model->concluir_demanda($id_demanda);

    switch ($result) {
        case 1:
            header('location: ../views/usuario.php?status=concluido');
            exit();

        case 2:
            header('location: ../views/usuario.php?status=error');
            exit();
    }
} else {

    header('location: ../views/usuario.php?status=empty');
    exit();
}
