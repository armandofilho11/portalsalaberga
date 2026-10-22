<?php
require_once('../models/main.model.php');

if (
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador']) &&
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_adequacao']) && isset($_POST['nota_conteudo']) && isset($_POST['nota_ambientacao']) && isset($_POST['nota_didatica']) && isset($_POST['nota_equipe']) && isset($_POST['nota_sustentabilidade'])
) {
    $nota_adequacao = $_POST['nota_adequacao'];
    $nota_conteudo = $_POST['nota_conteudo'];
    $nota_ambientacao = $_POST['nota_ambientacao'];
    $nota_didatica = $_POST['nota_didatica'];
    $nota_equipe = $_POST['nota_equipe'];
    $nota_sustentabilidade = $_POST['nota_sustentabilidade'];
    $curso = $_POST['curso'];

    $main_model = new main_model();
    $result = $main_model->confirmar_sala_tematica($nota_adequacao, $nota_conteudo, $nota_ambientacao, $nota_didatica, $nota_equipe, $nota_sustentabilidade, $curso, $_POST['id_avaliador']);

    switch ($result) {
        case 1:
            header('location:../views/Sala_Tematica.php?confirmado');
            exit();
        case 2:
            header('location:../views/Sala_Tematica.php?erro');
            exit();
        case 3:
            header('location:../views/Sala_Tematica.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/Sala_Tematica.php?empty');
    exit();
}