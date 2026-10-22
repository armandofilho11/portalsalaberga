<?php

//matenha este if aqui se não irá ocorrer erro de lógica
if (isset($_GET['erro1'])) {
    header('location:../../views/autenticacao/cadastro.php?login=erro1');
    exit();
}
//matenha este if aqui se não irá ocorrer erro de lógica
if (isset($_GET['erro2'])) {
    header('location:../../views/autenticacao/cadastro.php?login=erro2');
    exit();
}


if (isset($_POST['cadastrar']) && isset($_POST['UserName']) && isset($_POST['Cpf']) && isset($_POST['Email']) && isset($_POST['Password']) && !empty($_POST['UserName']) && !empty($_POST['Cpf']) && !empty($_POST['Email']) && !empty($_POST['Password'])) {

    $nome = $_POST['UserName'];
    $cpf = $_POST['Cpf'];
    $email = $_POST['Email'];
    $senha = $_POST['Password'];

    require_once('../../models/model_dados.php');
    cadastrar($nome, $cpf, $email, $senha);
}
