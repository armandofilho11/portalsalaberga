<?php
function cursos()
{

    if (file_exists('../config/connect.php')) {
        require_once('../config/connect.php');
    } else {
    
        require_once('config/connect.php');
    }
    $result_curso = $conexao->prepare("SELECT * FROM curso");
    $result_curso->execute();

    return $tabela_curso = $result_curso->fetchAll(PDO::FETCH_ASSOC);
}

function cadastrar_curso($nome_curso, $cor_curso)
{
    require_once('../config/connect.php');

    $smt_check = $conexao->prepare("SELECT * FROM curso WHERE nome_curso = :nome_curso");
    $smt_check->bindValue(':nome_curso', $nome_curso);
    $smt_check->execute();
    $rowCount = $smt_check->rowCount();

    if ($rowCount == 0) {

        $nome_curso2 = $nome_curso;
        $smt_cadastrar_curso = $conexao->prepare("INSERT INTO curso VALUES(NULL, :nome_curso2, :cor_curso)");
        $smt_cadastrar_curso->bindValue(':nome_curso2', $nome_curso2);
        $smt_cadastrar_curso->bindValue(':cor_curso', $cor_curso);
        $smt_cadastrar_curso->execute();

        if ($smt_cadastrar_curso) {

            return 1;
        } else {

            return 2;
        }
    } else {

        return 3;
    }
}

function deletar_curso($nome_curso)
{
    require_once('../config/connect.php');
    $smt_deletar_curso = $conexao->prepare("DELETE FROM curso WHERE nome_curso = :nome_curso");
    $smt_deletar_curso->bindValue(':nome_curso', $nome_curso);
    $smt_deletar_curso->execute();

    if ($smt_deletar_curso) {

        return "curso deletado com sucesso";
    } else {

        return "ERRO ao deletar o curso";
    }
}
