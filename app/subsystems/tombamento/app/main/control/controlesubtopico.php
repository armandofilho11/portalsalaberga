<?php
require_once("../model/modelprofessor.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $professor = new Professor();
    
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'adicionar':
                $disciplina = $_POST['disciplina'];
                $nome = $_POST['nome_subtopico'];
                
                if ($professor->adicionar_subtopico($disciplina, $nome)) {
                    header("Location: ../view/professor/gerenciar_subtopicos.php?success=1");
                } else {
                    header("Location: ../view/professor/gerenciar_subtopicos.php?error=1");
                }
                break;
                
            case 'excluir':
                $id = $_POST['id'];
                if ($professor->excluir_subtopico($id)) {
                    header("Location: ../view/professor/gerenciar_subtopicos.php?success=2");
                } else {
                    header("Location: ../view/professor/gerenciar_subtopicos.php?error=2");
                }
                break;
        }
    }
}

// Para requisições AJAX para carregar subtópicos de uma disciplina
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['disciplina'])) {
    $professor = new Professor();
    $subtopicos = $professor->get_subtopicos_por_disciplina($_GET['disciplina']);
    header('Content-Type: application/json');
    echo json_encode($subtopicos);
    exit;
}
?> 