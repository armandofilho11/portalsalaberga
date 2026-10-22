<?php
require_once('../models/adm.model.php');

if (
    isset($_POST['titulo']) && !empty($_POST['titulo']) &&
    isset($_POST['descricao']) && !empty($_POST['descricao']) &&
    isset($_POST['prioridade']) && !empty($_POST['prioridade']) &&
    isset($_POST['id_admin']) && !empty($_POST['id_admin']) &&
    isset($_POST['prazo']) && !empty($_POST['prazo'])
) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $prioridade = $_POST['prioridade'];
    $id_adm = $_POST['id_admin'];
    $prazo = $_POST['prazo'];

    $adm_model = new adm_model();
    $result = $adm_model->cadastrar_demanda($titulo, $descricao, $prioridade, $id_adm, $prazo);

    switch ($result) {
        case 1:
            header('location: ../views/adm.php?status=cadastrado');
            exit();

        case 2:
            header('location: ../views/adm.php?status=error');
            exit();

        case 3:
            header('location: ../views/adm.php?status=ja_cadastrado');
            exit();
    }
} else if (isset($_POST['id_usuario']) && !empty($_POST['id_usuario']) && isset($_POST['id_demanda']) && !empty($_POST['id_demanda'])) {

    $id_usuario = $_POST['id_usuario'];
    $id_demanda = $_POST['id_demanda'];

    $adm_model = new adm_model();
    $result = $adm_model->selecionar_demanda($id_demanda, $id_usuario);

    switch ($result) {
        case 1:
            header('location: ../views/adm.php?status=selecionado');
            exit();

        case 2:
            header('location: ../views/adm.php?status=error');
            exit();

        case 3:
            header('location: ../views/adm.php?status=ja_selecionado');
            exit();
    }
} else if (isset($_POST['id_demanda_concluir']) && !empty($_POST['id_demanda_concluir'])) {

    $id_demanda = $_POST['id_demanda_concluir'];

    $adm_model = new adm_model();
    $result = $adm_model->concluir_demanda($id_demanda);

    switch ($result) {
        case 1:
            header('location: ../views/adm.php?status=concluido');
            exit();

        case 2:
            header('location: ../views/adm.php?status=error');
            exit();
    }
} else if (isset($_POST['id_demanda_excluir']) && !empty($_POST['id_demanda_excluir'])) {

    $id_demanda = $_POST['id_demanda_excluir'];

    $adm_model = new adm_model();
    $result = $adm_model->excluir_demanda($id_demanda);

    switch ($result) {
        case 1:
            header('location: ../views/adm.php?status=excluido');
            exit();

        case 2:
            header('location: ../views/adm.php?status=error');
            exit();
    }
} else if (
    isset($_POST['id_demanda']) && !empty($_POST['id_demanda']) &&
    isset($_POST['edit_titulo']) && !empty($_POST['edit_titulo']) &&
    isset($_POST['edit_descricao']) && !empty($_POST['edit_descricao']) &&
    isset($_POST['edit_status']) && !empty($_POST['edit_status']) &&
    isset($_POST['edit_prioridade']) && !empty($_POST['edit_prioridade']) &&
    isset($_POST['edit_prazo']) && !empty($_POST['edit_prazo'])
) {
    $id_demanda = $_POST['id_demanda'];
    $titulo = $_POST['edit_titulo'];
    $descricao = $_POST['edit_descricao'];
    $prioridade = $_POST['edit_prioridade'];
    $status = $_POST['edit_status'];
    $prazo = $_POST['edit_prazo'];
    $adm_model = new adm_model();
    $result = $adm_model->editar_demanda($id_demanda, $titulo, $descricao, $prioridade, $status, $prazo);

    switch ($result) {
        case 1:
            header('location: ../views/adm.php?status=editado');
            exit();

        case 2:
            header('location: ../views/adm.php?status=error');
            exit();

        case 3:
            header('location: ../views/adm.php?status=ja_existe');
            exit();
    }
} else {

    header('location: ../views/adm.php?status=empty');
    exit();
}
