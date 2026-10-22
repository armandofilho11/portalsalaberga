<?php

if (isset($_POST['nome_usuario']) && !empty($_POST['nome_usuario'])) {

    $nome_usuario = $_POST['nome_usuario'];

    require_once('../../models/model.php');
    $result = excluir_usuario($nome_usuario);

    switch ($result) {

        case 1:

            header('location:../../views/success_banco.php?usuario_excluido_sucesso');
            break;
        case 2:

            header('location:../../views/inicio_ADM.php?usuario_erro');
            break;
        case 3:

            header('location:../../views/inicio_ADM.php?usuario_nao_existe');
            break;
    }
}
