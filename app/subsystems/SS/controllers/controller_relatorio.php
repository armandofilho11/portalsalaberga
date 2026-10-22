<?php

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
    $curso = isset($_POST['curso']) ? $_POST['curso'] : '';

    switch ($tipo) {
        case '1':
            require_once('../views/relatorios/publicaGeral.php');
            publicaGeral($curso);
            break;
        case '2':
            require_once('../views/relatorios/publicaAC.php');
            publicaAC($curso);
            break;
        case '3':
            require_once('../views/relatorios/publicaCotas.php');
            publicaCotas($curso);
            break;
        case '4':
            require_once('../views/relatorios/privadaGeral.php');
            privadaGeral($curso);
            break;
        case '5':
            require_once('../views/relatorios/privadaAC.php');
            privadaAC($curso);
            break;
        case '6':
            require_once('../views/relatorios/privadaCotas.php');
            privadaCotas($curso);
            break;
        case '7':
            require_once('../views/relatorios/usuarios.php');
            usuarios();
            break;
        default:
            header('location:../views/inicio.php');
            exit;
    }
} else {
    header('location:../views/inicio.php');
}
