<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_tema']) &&
    isset($_POST['nota_letra']) && 
    isset($_POST['nota_diccao']) &&
    isset($_POST['nota_desempenho']) && 
    isset($_POST['nota_trilha']) && 
    isset($_POST['nota_criatividade']) &&
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $curso = $_POST['curso'];
    $nota_tema = $_POST['nota_tema'];
    $nota_letra = $_POST['nota_letra'];
    $nota_diccao = $_POST['nota_diccao'];
    $nota_desempenho = $_POST['nota_desempenho'];
    $nota_trilha = $_POST['nota_trilha'];
    $nota_criatividade = $_POST['nota_criatividade'];
    $id_avaliador = $_POST['id_avaliador'];
    
    $main_model = new main_model();
    $result = $main_model->confirmar_parodia($nota_tema, $nota_letra, $nota_diccao, $nota_desempenho, $nota_trilha, $nota_criatividade, $curso, $id_avaliador);

    switch ($result) {
        case 1:
            header('location:../views/parodia.php?confirmado');
            exit();
        case 2:
            header('location:../views/parodia.php?erro');
            exit();
        case 3:
            header('location:../views/parodia.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/parodia.php?empty');
    exit();
}