<?php
require_once("../model/modelprofessor.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifica se a chave 'acao' foi definida
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
    } else {
        echo "<p style='color:red;'>Nenhuma ação foi especificada.</p>";
        exit;
    }

    if ($acao === 'selecionar') {
        // MODO DE SELEÇÃO MANUAL

        if (isset($_POST['selecionadas']) && is_array($_POST['selecionadas'])) {
            $selecionadas = $_POST['selecionadas'];

            if (count($selecionadas) > 0) {
                echo "<h2>Você selecionou as seguintes questões:</h2>";
                echo "<ul>";
                foreach ($selecionadas as $id) {
                    echo "<li>ID da questão: " . htmlspecialchars($id) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color:red;'>Nenhuma questão foi selecionada.</p>";
            }

        } else {
            echo "<p style='color:red;'>Nenhuma questão foi selecionada.</p>";
        }

    } elseif ($acao === 'aleatorias') {
        // MODO DE SORTEIO ALEATÓRIO

        // Verifica se a quantidade foi enviada e é um número válido
        if (isset($_POST['quantidade']) && is_numeric($_POST['quantidade'])) {
            $quantidade = (int) $_POST['quantidade'];
        } else {
            echo "<p style='color:red;'>Informe uma quantidade válida de questões.</p>";
            exit;
        }

        // Verifica se dificuldade foi enviada
        if (isset($_POST['dificuldade']) && !empty($_POST['dificuldade'])) {
            $dificuldade = $_POST['dificuldade'];
        } else {
            echo "<p style='color:red;'>Campo 'dificuldade' não informado.</p>";
            exit;
        }

        // Verifica se disciplina foi enviada
        if (isset($_POST['disciplina']) && !empty($_POST['disciplina'])) {
            $disciplina = $_POST['disciplina'];
        } else {
            echo "<p style='color:red;'>Campo 'disciplina' não informado.</p>";
            exit;
        }

        // Instancia e busca as questões
        $prof = new professor();
        $todas = $prof->acessar_questoes_prova($dificuldade, $disciplina);

        // Verifica se vieram resultados
        if (!is_array($todas) || count($todas) === 0) {
            echo "<p style='color:red;'>Não há questões disponíveis para os critérios informados.</p>";
            exit;
        }

        // Verifica se há questões suficientes
        if ($quantidade > count($todas)) {
            echo "<p style='color:red;'>Solicitou $quantidade questões, mas só existem " . count($todas) . " disponíveis.</p>";
            exit;
        }

        // Sorteia
        shuffle($todas);
        $sorteadas = array();
        for ($i = 0; $i < $quantidade; $i++) {
            $sorteadas[] = $todas[$i];
        }

        // Exibe
        echo "<h2>Questões sorteadas:</h2>";
        foreach ($sorteadas as $q) {
            echo "<div style='border:1px solid #ccc; margin-bottom:10px; padding:10px;'>";
            echo "<strong>Enunciado:</strong> " . htmlspecialchars($q['enunciado']) . "<br>";
            echo "<strong>Dificuldade:</strong> " . htmlspecialchars($q['grau_de_dificuldade']) . "<br>";
            echo "<strong>Disciplina:</strong> " . htmlspecialchars($q['disciplina']) . "<br>";
            echo "</div>";
        }

    } else {
        echo "<p style='color:red;'>Ação inválida.</p>";
    }

} else {
    echo "<p style='color:red;'>Requisição inválida. Use o método POST.</p>";
}
?>
