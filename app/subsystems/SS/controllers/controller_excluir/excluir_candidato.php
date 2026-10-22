<?php

if (isset($_POST['id_candidato']) && !empty($_POST['id_candidato'])) {

    echo $id_candidato = $_POST['id_candidato'];

    require_once('../../models/model.php');
    $result = excluir_candidato($id_candidato);

    switch ($result) {

        case 1:

            header('location:../../views/success_banco.php?candidato_excluido_sucesso');
            break;
        case 2:

            header('location:../../views/inicio_ADM.php?candidato_erro');
            break;
        case 3:

            header('location:../../views/inicio_ADM.php?candidato_nao_existe');
            break;
    }
}
