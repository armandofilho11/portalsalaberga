<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_tempo']) && 
    isset($_POST['nota_tema']) &&
    isset($_POST['nota_figurino']) && 
    isset($_POST['nota_criatividade']) && 
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $cursoSelecionado = $_POST['curso'];
    $notaTempo = $_POST['nota_tempo'];
    $notaTema = $_POST['nota_tema'];
    $notaFigurino = $_POST['nota_figurino'];
    $notaCriatividade = $_POST['nota_criatividade'];
    $avaliadorId = $_POST['id_avaliador'];
    $modeloPrincipal = new main_model();
    $resultado = $modeloPrincipal->confirmar_esquete($cursoSelecionado, $notaTempo, $notaTema, $notaFigurino, $notaCriatividade, $avaliadorId);

    switch ($resultado) {
        case 1:
            header('location:../views/esquete.php?confirmado');
            exit();
        case 2:
            header('location:../views/esquete.php?erro');
            exit();
        case 3:
            header('location:../views/esquete.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/esquete.php?empty');
    exit();
}
