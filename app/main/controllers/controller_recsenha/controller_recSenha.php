<?php
if (isset($_GET['login']) && $_GET['login'] == 'erro') {
    header('Location: ../../views/autenticação/recuperacaodesenha.php?login=erro');
    exit();
}
if ($_GET['login'] && $_GET['login'] == 'erro1') {
    header('Location: ../../views/autenticação/recuperacaodesenha.php?login=erro1');
    exit();
}
if (isset($_POST['recsenha']) && isset($_POST['Email']) && !empty($_POST['Email'])) {

    $email = $_POST['Email'];

    require('../../models/model_dados.php');
    recSenha($email);
}
