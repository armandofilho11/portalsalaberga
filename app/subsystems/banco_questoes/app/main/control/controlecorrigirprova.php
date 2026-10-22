<?php
require_once('../model/modelprofessor.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['turma'])) {
        $ano_selecionado = $_POST['turma'];
        
        // Instancia o modelo do professor
        $modelProfessor = new Professor();
        
        // Busca os alunos da turma selecionada
        $alunos = $modelProfessor->corrigir_prova($ano_selecionado);
        
        // Passa os dados para a view
        require_once('../view/professor/listar_alunos_correcao.php');
    } else {
        header('Location: ../view/professor/corrigir_prova.php');
        exit();
    }
} else {
    header('Location: ../view/professor/corrigir_prova.php');
    exit();
}
?>
