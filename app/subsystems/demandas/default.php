<?php 
session_start();
$dados = explode('(',$_SESSION['user_systems_permissions'][0]['permissao_descricao']);
$dado = $dados[0];

if($dado == 'adm'){

    header('location:./views/adm.php');
    exit();
}else if($dado == 'usuario'){

    header('location:./views/usuario.php');
    exit();
}else{

    header('location:../../main/views/autenticacao/login.php');
    exit();
}

?>