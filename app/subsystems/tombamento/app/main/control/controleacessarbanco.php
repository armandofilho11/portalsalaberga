<?php

require_once("../model/modelprofessor.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'buscar') {
    $id = $_GET['id'];
    header("Location: ../view/professor/atualizar_questao.php?id=" . $id);
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inicializar variáveis com valores vazios
    $enunciado = isset($_POST['enunciado']) ? trim($_POST['enunciado']) : '';
    $disciplina = isset($_POST['disciplina']) ? trim($_POST['disciplina']) : '';
    $grau_de_dificuldade = isset($_POST['dificuldade']) ? trim($_POST['dificuldade']) : '';
    $subtopico = isset($_POST['subtopico']) ? trim($_POST['subtopico']) : '';

    // Debug information
    error_log("Filtering with parameters:");
    error_log("Enunciado: " . $enunciado);
    error_log("Disciplina: " . $disciplina);
    error_log("Dificuldade: " . $grau_de_dificuldade);
    error_log("Subtopico: " . $subtopico);

    // Determinar o ID do professor baseado na disciplina
    if(!empty($disciplina) && (
        $disciplina == "lab._software" ||
        $disciplina == "programacao_web" ||
        $disciplina == "banco_de_dados" ||
        $disciplina == "logica" ||
        $disciplina == "gerenciador_de_conteudo" ||
        $disciplina == "htmlcss"
    )) {
        $id_professor = 2;
    } else {
        $id_professor = 1;
    }

    $professor = new Professor();
    $resultado = $professor->acessar_banco($enunciado, $disciplina, $grau_de_dificuldade, $id_professor, $subtopico);

    // Debug information
    error_log("Found " . count($resultado) . " results");

    // Inclui o arquivo de visualização
    include("../view/professor/visualizar_questoes.php");
}
?>