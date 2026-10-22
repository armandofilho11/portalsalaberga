<?php

require("../model/modelaluno.php");

if($_SERVER['REQUEST_METHOD'] === "POST"){

    $avaliacao_id = $_POST['avaliacao_id'];
    

    $x = new aluno();
    $x -> exibir_aval_pdf($avaliacao_id);

}

?>