<?php
require("../model/modelprofessor.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $disciplina = $_POST['disciplina'];
    $grau_de_dificuldade = $_POST['dificuldade'];
    $enunciado = $_POST['enunciado'];
    $alternativaA = $_POST['alternativaA'];
    $alternativaB = $_POST['alternativaB'];
    $alternativaC = $_POST['alternativaC'];
    $alternativaD = $_POST['alternativaD'];
    $autor = $_POST['autor']; 
    $resposta_correta = $_POST['resposta_correta'];
    $subtopico = isset($_POST['subtopico']) ? $_POST['subtopico'] : null;

    // echo $disciplina,$grau_de_dificuldade,$enunciado,$alternativaA,$alternativaB,$alternativaC,$alternativaD,$autor;

    if ($autor == "Otavio") {
        $id_professor = 2;
        $x = new professor();
        $x->criar_questao($disciplina, $grau_de_dificuldade, $enunciado, $alternativaA, $alternativaB, $alternativaC, $alternativaD, $id_professor, $resposta_correta, $subtopico) ? 
            header("Location: ../view/professor/criarquestao.php?success=1") : 
            header("Location: ../view/professor/criarquestao.php?error=1");
    } else {
        $id_professor = 1;
        $x = new professor();
        $x->criar_questao($disciplina, $grau_de_dificuldade, $enunciado, $alternativaA, $alternativaB, $alternativaC, $alternativaD, $id_professor, $resposta_correta, $subtopico) ? 
            header("Location: ../view/professor/criarquestao.php?success=1") : 
            header("Location: ../view/professor/criarquestao.php?error=1");
    }
}

   

    
    // $x = new professor();
    // if($x->criar_questao($disciplina,$classificacao,$grau_de_dificuldade,$enunciado,$alternativaA,$alternativaB,$alternativaC,$alternativaD,$id_professor)){
    //     echo 'deu certo';







?>