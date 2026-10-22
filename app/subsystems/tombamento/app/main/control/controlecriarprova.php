<?php
require("../model/modelprofessor.php");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    $id_questao = $_POST['questoes_selecionadas'];
    $nome_prova = $_POST['nome_prova'];
    $tipo = $_POST['tipo_prova'];
    $dificuldade = $_POST['dificuldade'];
    $turma = $_POST['turma'];


    $x = new professor();
    $x -> criar_prova($id_questao,$nome_prova,$tipo,$dificuldade,$turma);
    echo "<h1>a prova foi cadastrada !!! </h1> ";


    
    // print_r($id_questao);

    // print_r($id_questao[2]);

    // echo print_r($id_questao),$nome_prova,$tipo;




    

}


?>