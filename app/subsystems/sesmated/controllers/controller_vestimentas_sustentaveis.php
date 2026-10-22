<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['pontuacao_total']) &&
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $curso = $_POST['curso'];
    $id_avaliador = $_POST['id_avaliador'];
    $pontuacao = $_POST['pontuacao_total'];

    $modeloPrincipal = new main_model();
    echo $resultado = $modeloPrincipal->confirmar_vestimentas($curso, $id_avaliador, $pontuacao);

    switch ($resultado) {
        case 1:
            header('location:../views/vestimentas_sustentaveis.php?confirmado');
            exit();
        case 2:
            header('location:../views/vestimentas_sustentaveis.php?erro');
            exit();
    }
} else {
    header('location:../views/esquete.php?empty');
    exit();
}
