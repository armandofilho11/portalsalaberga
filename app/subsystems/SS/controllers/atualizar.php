<?php

if(isset($_GET['erro']) && $_GET['erro'] == 1){
    header('Location: ../views/inicio_ADM.php?erro=1');
  }

if (isset($_POST['atualizar_nota'])) {
  session_start();
  $ef = ($_POST['ef']);
  $lp = ($_POST['lp']);
  $ar = ($_POST['ar']);
  $li = ($_POST['li']);
  $ma = ($_POST['ma']);
  $ci = ($_POST['ci']);
  $ge = ($_POST['ge']);
  $hi = ($_POST['hi']);
  $re = ($_POST['re']);
  $id = $_SESSION['id'];
  
    require_once('../models/model.php');
    atualizar($lp, $ar, $ef, $li, $ma, $ci, $ge, $hi, $re, $id);
    header('Location: ../views/success_atualizar.php');
    exit();
  } 

if (isset($_POST['id'])) {
  
  $id = $_POST['id'];

  require_once('../models/model.php');
  notas($id);

} 
  

 

