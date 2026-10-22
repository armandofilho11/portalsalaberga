<?php

if (isset($_GET['alt'])) {
   header('Location: ../../views/autenticação/login.php');
   exit();  
}


if (isset($_POST['Telefone']) && !empty($_POST['Telefone'])) {
   
   $telefone = $_POST['Telefone'];
   require_once('../../models/model_dados.php');
   alterarTelefone($telefone);
}

?>