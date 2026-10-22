<?php
session_start(); 

require_once("../model/modelaluno.php");
require_once("../model/modelprofessor.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];

    if ($role == 'aluno') {
        $matricula = $_POST['matricula'];
        $x = new aluno();
        
        $aluno_info = $x->aluno_existe($matricula);
        if ($aluno_info && count($aluno_info) > 0) {
            $_SESSION['matricula'] = $matricula;
            $_SESSION['id_aluno'] = $aluno_info[0]['id']; // Store the student ID
            header('location:../view/aluno/inicioaluno.php');
            exit();
        } else {
            echo "Essa matrícula está errada, ou não existe no banco de dados";
        }
    } elseif ($role == "professor") {
        $x = new professor();
        $emailuser = $_POST['email'];
        $senhauser = $_POST['senha'];
        
        $professor_data = $x->validar_email_e_senha_prof($emailuser, $senhauser);
        if ($professor_data) {
            $_SESSION['professor'] = $professor_data;
            header('location:../view/professor/inicioprofessor.php');
            exit();
        } else {
            echo "Email ou senha incorretos";
        }
    } else {
        echo "Você NÃO selecionou se é aluno ou professor";
    }
}
?>
