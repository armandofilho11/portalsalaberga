<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_originalidade']) &&
    isset($_POST['nota_relevancia']) &&
    isset($_POST['nota_viabilidade']) &&
    isset($_POST['nota_sustentabilidade']) &&
    isset($_POST['nota_clareza']) &&
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $curso = $_POST['curso'];
    $nota_originalidade = $_POST['nota_originalidade'];
    $nota_relevancia = $_POST['nota_relevancia'];
    $nota_viabilidade = $_POST['nota_viabilidade'];
    $nota_sustentabilidade = $_POST['nota_sustentabilidade'];
    $nota_clareza = $_POST['nota_clareza'];
    $id_avaliador = $_POST['id_avaliador'];
    $main_model = new main_model();
    $resultado = $main_model->confirmar_inovacao($nota_originalidade, $nota_relevancia, $nota_viabilidade, $nota_sustentabilidade, $nota_clareza, $curso, $id_avaliador);

    switch ($resultado) {
        case 1:
            header('location:../views/Inovacao.php?confirmado');
            exit();
        case 2:
            header('location:../views/Inovacao.php?erro');
            exit();
        case 3:
            header('location:../views/Inovacao.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/Inovacao.php?empty');
    exit();
}
