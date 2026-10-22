<?php

if (isset($_POST['nomeC']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['status'])) {

    $nomeC = $_POST['nomeC'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $status = $_POST['status'];
    require_once('../models/model.php');
    $result = cadastrarUsuario($nomeC, $email, $senha, $status);

    switch ($result) {

        case 0:
            header('location:../views/inicio_ADM.php?erro_usuario');
            break;

        case 1:
            header('location:../views/inicio_ADM.php?certo_usuario');
            break;
    }
} else {

    header('location:../views/inicio_ADM.php');
}
