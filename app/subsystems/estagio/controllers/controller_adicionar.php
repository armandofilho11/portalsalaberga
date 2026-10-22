<?php
require_once('../models/model.php');
if (
    isset($_POST['acao']) &&
    isset($_POST['nome']) &&
    isset($_POST['contato']) &&
    isset($_POST['medias']) &&
    isset($_POST['email']) &&
    isset($_POST['projetos']) &&
    isset($_POST['perfil_opc1']) &&
    isset($_POST['perfil_opc2']) &&
    isset($_POST['localidade']) &&
    isset($_POST['ocorrencia']) &&
    isset($_POST['custeio']) &&
    isset($_POST['entregas_individuais']) &&
    isset($_POST['entregas_grupo'])
) {
 
    $nome = $_POST['nome'];
    $contato = $_POST['contato'];
    $medias = $_POST['medias'];
    $email = $_POST['email'];
    $projetos = $_POST['projetos'];
    $perfil_opc1 = $_POST['perfil_opc1'];
    $perfil_opc2 = $_POST['perfil_opc2'];
    $localidade = $_POST['localidade'];
    $ocorrencia = (int) ($_POST['ocorrencia'] ?? 0);
    $custeio = (int) ($_POST['custeio'] ?? 0);
    $entregas_individuais = $_POST['entregas_individuais'];
    $entregas_grupo = $_POST['entregas_grupo'];

    $model = new main_model;
    $result = $model->criar_aluno($nome, $contato, $medias, $email, $projetos, $perfil_opc1, $perfil_opc2, $localidade, $ocorrencia, $custeio, $entregas_individuais, $entregas_grupo);
    switch ($result) {
        case 1:
            header('location:../views/dashboard.php#alunos');
            exit();
        case 2:
            header('location:../views/dashboard.php#alunos');
            exit();
        case 3:
            header('location:../views/dashboard.php#alunos');
            exit();
        default:
            header('location:../views/dashboard.php#alunos');
            exit();
    }
}
