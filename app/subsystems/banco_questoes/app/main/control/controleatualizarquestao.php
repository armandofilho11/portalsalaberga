<?php
require_once("../model/modelprofessor.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get all form data
    $id = $_POST['id'];
    $disciplina = $_POST['disciplina'];
    $subtopico = $_POST['subtopico'];
    $dificuldade = $_POST['dificuldade'];
    $enunciado = $_POST['enunciado'];
    $alternativaA = $_POST['alternativaA'];
    $alternativaB = $_POST['alternativaB'];
    $alternativaC = $_POST['alternativaC'];
    $alternativaD = $_POST['alternativaD'];
    $resposta_correta = $_POST['resposta_correta'];

    // Create professor instance
    $professor = new Professor();

    // Update question
    $result = $professor->atualizar_questao(
        $id,
        $disciplina,
        $subtopico,
        $dificuldade,
        $enunciado,
        $alternativaA,
        $alternativaB,
        $alternativaC,
        $alternativaD,
        $resposta_correta
    );

    // Redirect based on result
    if ($result) {
        header("Location: ../view/professor/acessar_banco.php?success=1");
    } else {
        header("Location: ../view/professor/atualizar_questao.php?id=" . $id . "&error=1");
    }
    exit();
}

// If not POST request, redirect to question bank
header("Location: ../view/professor/acessar_banco.php");
exit();
?> 