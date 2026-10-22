<?php
require_once('../models/main.model.php');

if (
    isset($_POST['curso']) && !empty($_POST['curso']) &&
    isset($_POST['nota_animacao']) &&
    isset($_POST['nota_vestimenta']) && 
    isset($_POST['nota_identidade']) && 
    isset($_POST['id_avaliador']) && !empty($_POST['id_avaliador'])
) {
    $curso = $_POST['curso'];
    $nota_animacao = $_POST['nota_animacao'];
    $nota_vestimenta = $_POST['nota_vestimenta'];
    $nota_identidade = $_POST['nota_identidade'];
    $id_avaliador = $_POST['id_avaliador'];
    $main_model = new main_model();
    $result = $main_model->confirmar_mascote($curso, $nota_animacao, $nota_vestimenta, $nota_identidade, $id_avaliador);

    switch ($result) {
        case 1:
            header('location:../views/mascote.php?confirmado');
            exit();
        case 2:
            header('location:../views/mascote.php?erro');
            exit();
        case 3:
            header('location:../views/mascote.php?ja_confirmado');
            exit();
    }
} else {
    header('location:../views/mascote.php?empty');
    exit();
}