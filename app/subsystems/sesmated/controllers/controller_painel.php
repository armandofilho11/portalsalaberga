<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_tema']) && isset($_POST['nota_conteudo']) && isset($_POST['nota_layout']) && isset($_POST['nota_estetica']) && isset($_POST['nota_sustentabilidade']) &&
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $curso = $_POST['curso'];
    $nota_tema = $_POST['nota_tema'];
    $nota_conteudo = $_POST['nota_conteudo'];
    $nota_layout = $_POST['nota_layout'];
    $nota_estetica = $_POST['nota_estetica'];
    $nota_sustentabilidade = $_POST['nota_sustentabilidade'];
    $id_avaliador = $_POST['id_avaliador'];

    $main_model = new main_model();
    $result = $main_model->confirmar_painel($nota_tema, $nota_conteudo, $nota_layout, $nota_estetica, $nota_sustentabilidade, $curso, $id_avaliador);

    switch ($result) {
        case 1:
            header('location:../views/Painel.php?confirmado');
            exit();
        case 2:
            header('location:../views/Painel.php?erro');
            exit();
        case 3:
            header('location:../views/Painel.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/Painel.php?empty');
    exit();
} 