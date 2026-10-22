<?php 
 
 if (isset($_POST['btn'])) {
     $opc = $_POST['btn'];
 
     switch ($opc) {
         case 'Aluno':
             header("Location: ../views/Login_Aluno.php");
             break;
         case 'Professor':
             header("Location: ../views/Login_Professor.php");
             break;
         default:
             break;
     }
 }