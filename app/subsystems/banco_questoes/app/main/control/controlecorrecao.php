<?php
require_once("../model/modelprofessor.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_aluno = $_POST['id_aluno'] ?? null;
    $id_avaliacao = $_POST['id_avaliacao'] ?? null;
    $respostas = $_POST['resposta'] ?? [];

    if ($id_aluno && $id_avaliacao && !empty($respostas)) {
        $professor = new Professor();
        
        // Buscar as alternativas selecionadas
        $respostas_texto = [];
        foreach ($respostas as $id_questao => $resposta) {
            // Buscar o texto da alternativa selecionada
            $alternativas = $professor->buscar_alternativas_questao($id_questao);
            foreach ($alternativas as $alternativa) {
                if (($resposta === 'correta' && $alternativa['resposta'] === 'sim') ||
                    ($resposta === 'incorreta' && $alternativa['resposta'] === 'nao')) {
                    $respostas_texto[$id_questao] = $alternativa['texto'];
                    break;
                }
            }
        }

        if ($professor->salvar_correcao($id_aluno, $id_avaliacao, $respostas_texto)) {
            header("Location: ../view/professor/corrigir_prova.php?success=1");
            exit();
        }
    }
}

header("Location: ../view/professor/corrigir_prova.php?error=1");
exit();
?> 