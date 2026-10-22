<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Questões da Prova</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .questao {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .questao h3 {
            margin: 0 0 10px 0;
        }
        .questao .meta {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>

<h1>Questões da Prova</h1>

<?php
// exemplo: $resultado vindo da consulta
require("../../control/controlecriarprova.php");

echo $enunciado, $dificuldade,$disciplina;

// foreach ($resultado as $linha) {
//     echo '<div class="questao">';
//     echo '<h3>' . htmlspecialchars($linha['enunciado']) . '</h3>';
//     echo '<div class="meta">';
//     echo 'Disciplina: ' . htmlspecialchars($linha['disciplina']) . '<br>';
//     echo 'Dificuldade: ' . htmlspecialchars($linha['grau_de_dificuldade']);
//     echo '</div>';
//     echo '</div>';
// }
?>

</body>
</html>
