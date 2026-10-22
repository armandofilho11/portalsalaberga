<?php
require_once('../models/main.model.php');
if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_tema']) && 
    isset($_POST['nota_estrutura']) &&
    isset($_POST['nota_declamacao']) && 
    isset($_POST['nota_criatividade']) &&
    isset($_POST['nota_apresentacao']) &&
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $curso = $_POST['curso'];
    $nota_tema = $_POST['nota_tema'];
    $nota_estrutura = $_POST['nota_estrutura'];
    $nota_declamacao = $_POST['nota_declamacao'];
    $nota_criatividade = $_POST['nota_criatividade'];
    $nota_apresentacao = $_POST['nota_apresentacao'];
    $id_avaliador = $_POST['id_avaliador'];
    
    $main_model = new main_model();
    $result = $main_model->confirmar_cordel($nota_tema, $nota_estrutura, $nota_declamacao, $nota_criatividade, $nota_apresentacao, $curso, $id_avaliador);

    switch ($result) {
        case 1:
            header('location:../views/cordel.php?confirmado');
            exit();
        case 2:
            header('location:../views/cordel.php?erro');
            exit();
        case 3:
            header('location:../views/cordel.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/cordel.php?empty');
    exit();
}