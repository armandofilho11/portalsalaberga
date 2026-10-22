<?php
require_once("../model/modelprofessor.php");

// Verifica se é uma ação de remoção
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $professor = new Professor();
    $questao_id = (int)$_POST['numero_questao'];
    
    if($professor->remover_questao($questao_id)){
        header("Location: ../view/professor/removerquestao.php?success=1");
    } else {
        header("Location: ../view/professor/removerquestao.php?error=1");
    }
    exit();
}
?> 