<?php

file_put_contents('post_log.txt', print_r($_POST, true), FILE_APPEND);

require_once('../models/model.php');
print_r($_POST);
if (isset($_POST['id_excluir_empresa']) && !empty($_POST['id_excluir_empresa'])) {

    $id_empresa = $_POST['id_excluir_empresa'];
    $model = new main_model;
    $result = $model->excluir_empresa($id_empresa);

    switch ($result) {

        case 1:
            header('location:../views/gerenciar_empresas.php?deletado');
            exit();
        case 2:
            header('location:../views/gerenciar_empresas.php?erro');
            exit();
        case 3:
            header('location:../views/gerenciar_empresas.php?erro_delete_vaga');
            exit();
    }
} else if (isset($_POST['id_excluir_vaga']) && !empty($_POST['id_excluir_vaga'])) {

    $id_vaga = $_POST['id_excluir_vaga'];
    $model = new main_model;
    $result = $model->excluir_vaga($id_vaga);

    switch ($result) {

        case 1:
            header('location:../views/vagas.php?deletado');
            exit();
        case 2:
            header('location:../views/vagas.php?erro');
            exit();
        case 3:
            header('location:../views/vagas?erro_delete_vaga');
            exit();
    }
} else if (isset($_POST['id_editar_empresa']) && !empty($_POST['id_editar_empresa']) && isset($_POST['nome_editar_empresa']) && !empty($_POST['nome_editar_empresa']) && isset($_POST['endereco_editar_empresa']) && !empty($_POST['endereco_editar_empresa']) && isset($_POST['contato_editar_empresa']) && !empty($_POST['contato_editar_empresa'])) {

    $id_empresa = $_POST['id_editar_empresa'];
    $nome_empresa = $_POST['nome_editar_empresa'];
    $endereco_empresa = $_POST['endereco_editar_empresa'];
    $contato_empresa = $_POST['contato_editar_empresa'];
    $nome_contato_empresa = $_POST['nome_contato_editar_empresa'] ?? '';

    $model = new main_model;
    $result = $model->editar_empresa($id_empresa, $nome_empresa, $contato_empresa, $endereco_empresa, $nome_contato_empresa);

    switch ($result) {

        case 1:
            header('location:../views/gerenciar_empresas.php?editado');
            exit();
        case 2:
            header('location:../views/gerenciar_empresas.php?erro');
            exit();
        case 3:
            header('location:../views/gerenciar_empresas.php?ja_existe');
            exit();
    }
} else if (
    isset($_POST['id_editar_vaga']) && !empty($_POST['id_editar_vaga']) &&
    isset($_POST['empresa_editar_vaga']) && !empty($_POST['empresa_editar_vaga']) &&
    isset($_POST['perfil_editar_vaga']) && !empty($_POST['perfil_editar_vaga']) &&
    isset($_POST['quantidade_editar_vaga']) && !empty($_POST['quantidade_editar_vaga']) &&
    isset($_POST['quant_candidatos_editar_vaga']) && !empty($_POST['quant_candidatos_editar_vaga']) &&
    isset($_POST['tipo_editar_vaga']) && !empty($_POST['tipo_editar_vaga']) &&
    isset($_POST['data_editar_vaga']) && !empty($_POST['data_editar_vaga']) &&
    isset($_POST['hora_editar_vaga']) && !empty($_POST['hora_editar_vaga'])
) {

    $id = $_POST['id_editar_vaga'];
    $empresa = $_POST['empresa_editar_vaga'];
    $perfil = $_POST['perfil_editar_vaga'];
    $quantidades_vagas = $_POST['quantidade_editar_vaga'];
    $quant_candidatos = $_POST['quant_candidatos_editar_vaga'];
    $tipo_vaga = $_POST['tipo_editar_vaga'];
    $data = $_POST['data_editar_vaga'];
    $hora = $_POST['hora_editar_vaga'];

    $model = new main_model;
    $result = $model->editar_vaga($id, $empresa, $perfil, $quantidades_vagas, $quant_candidatos, $tipo_vaga, $data, $hora);

    switch ($result) {
        case 1:
            header('location:../views/vagas.php?editado');
            exit();
        case 2:
            header('location:../views/vagas.php?erro');
            exit();
        case 3:
            header('location:../views/vagas.php?ja_existe');
            exit();
    }
} else if( isset($_GET['id_aluno']) && !empty($_GET['id_aluno'])){

     $id_aluno = $_GET['id_aluno'];

    $model = new main_model;
    $result = $model->excluir_aluno($id_aluno);

    switch ($result) {
        case 1:
            header('location:../views/gerenciar_alunos.php?deletado');
            exit();
        case 2:
            header('location:../views/gerenciar_alunos.php?erro');
            exit();
        case 3:
            header('location:../views/gerenciar_alunos.php?erro2');
            exit();
    }
}else{
    header('location:../views/login.php?erro');
    exit();
}

