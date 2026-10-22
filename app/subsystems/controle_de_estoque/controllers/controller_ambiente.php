<?php
require_once(__DIR__ . '/../models/sessions.php');
$session = new sessions();
$session->autenticar_session();
$session->tempo_session();

require_once(__DIR__ . '/../models/model.admin.php');
//print_r($_POST);


if (
    isset($_POST['ambiente']) && !empty($_POST['ambiente']) && is_string($_POST['ambiente']) && !isset($_POST['id_ambiente']) && empty($_POST['id_ambiente'])
) {

    $nome_ambiente = $_POST['ambiente'];

    $obj = new admin();
    $result = $obj->cadastrar_ambiente($nome_ambiente);

    switch ($result) {
        case 1:
            header('Location: ../views/ambiente.php?cadastrado');
            exit();
        case 2:
            header('Location: ../views/ambiente.php?erro');
            exit();
        case 3:
            header('Location: ../views/ambiente.php?ja_cadastrado');
            exit();
        default:
            header('Location: ../views/ambiente.php?falha');
            exit();
    }
} else if (isset($_POST['id_ambiente']) && !empty($_POST['id_ambiente']) && isset($_POST['ambiente']) && !empty($_POST['ambiente']) && is_string($_POST['ambiente'])) {

    $id_ambiente = $_POST['id_ambiente'];
    $nome_ambiente = $_POST['ambiente'];
    $obj = new admin();
    $result = $obj->editar_ambiente($id_ambiente, $nome_ambiente);

    switch ($result) {
        case 1:
            header('Location: ../views/ambiente.php?editado');
            exit();
        case 2:
            header('Location: ../views/ambiente.php?erro');
            exit();
        case 3:
            header('Location: ../views/ambiente.php?nao_cadastrado');
            exit();
        default:
            header('Location: ../views/ambiente.php?falha');
            exit();
    }
} else if (isset($_POST['id_excluir']) && !empty($_POST['id_excluir'])) {

    $id_ambiente = $_POST['id_excluir'];
    $obj = new admin();
    $result = $obj->excluir_ambiente($id_ambiente);

    switch ($result) {
        case 1:
            header('Location: ../views/ambiente.php?excluido');
            exit();
        case 2:
            header('Location: ../views/ambiente.php?erro');
            exit();
        case 3:
            header('Location: ../views/ambiente.php?nao_existe');
            exit();
        default:
            header('Location: ../views/ambiente.php?falha');
            exit();
    }
} else {

    header('location:../views/index.php');
    exit();
}
