<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_elementos'])  &&
    isset($_POST['nota_impressa'])  &&
    isset($_POST['nota_digital']) &&
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $cursoSelecionado = $_POST['curso'];
    $notaElementos = $_POST['nota_elementos'];
    $notaImpressa = $_POST['nota_impressa'];
    $notaDigital = $_POST['nota_digital'];
    $avaliadorId = $_POST['id_avaliador'];
    $modeloPrincipal = new main_model();
    $resultado = $modeloPrincipal->confirmar_logo($cursoSelecionado, $notaElementos, $notaImpressa, $notaDigital, $avaliadorId);

    switch ($resultado) {
        case 1:
            header('location:../views/logo.php?confirmado');
            exit();
        case 2:
            header('location:../views/logo.php?erro');
            exit();
        case 3:
            header('location:../views/logo.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/logo.php?empty');
    exit();
}
