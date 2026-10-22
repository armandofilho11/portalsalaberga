<?php

require_once('../models/model.php');
if (isset($_POST['id_excluir_empresa']) && !empty($_POST['id_excluir_empresa'])) {

    $id_empresa = $_POST['id_excluir_empresa'];
    $model = new main_model;
    $result = $model->excluir_empresa($id_empresa);

    switch ($result) {

        case 1:
            header('location:../views/dashboard.php#empresas');
            exit();
        case 2:
            header('location:../views/dashboard.php#empresas');
            exit();
        case 3:
            header('location:../views/dashboard.php#empresas');
            exit();
    }
} else if (isset($_POST['id_excluir_ficha']) && !empty($_POST['id_excluir_ficha'])) {

    $id_ficha = filter_var($_POST['id_excluir_ficha'], FILTER_VALIDATE_INT);
    $model = new main_model;
    $result = $model->excluir_ficha_concedente($id_ficha);

    header('location:../views/dashboard.php#empresas');
    exit();

} else if (isset($_POST['id_excluir_vaga']) && !empty($_POST['id_excluir_vaga'])) {

    $id_vaga = $_POST['id_excluir_vaga'];
    $model = new main_model;
    $result = $model->excluir_vaga($id_vaga);

    switch ($result) {

        case 1:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 2:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 3:
            header('location:../views/dashboard.php#vagas');
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
            header('location:../views/dashboard.php#empresas');
            exit();
        case 2:
            header('location:../views/dashboard.php#empresas');
            exit();
        case 3:
            header('location:../views/dashboard.php#empresas');
            exit();
    }
} else if (
    isset($_POST['id_editar_vaga']) && $_POST['id_editar_vaga'] !== '' &&
    isset($_POST['empresa_editar_vaga']) && $_POST['empresa_editar_vaga'] !== '' &&
    isset($_POST['perfil_editar_vaga']) && $_POST['perfil_editar_vaga'] !== '' &&
    isset($_POST['quantidade_editar_vaga']) && $_POST['quantidade_editar_vaga'] !== '' &&
    isset($_POST['quant_candidatos_editar_vaga']) && $_POST['quant_candidatos_editar_vaga'] !== '' &&
    isset($_POST['tipo_editar_vaga']) && $_POST['tipo_editar_vaga'] !== ''
) {

    $id = (int) $_POST['id_editar_vaga'];
    $empresa = (int) $_POST['empresa_editar_vaga'];
    $perfil = (int) $_POST['perfil_editar_vaga'];
    $quantidades_vagas = (int) $_POST['quantidade_editar_vaga'];
    $quant_candidatos = (int) $_POST['quant_candidatos_editar_vaga'];
    $tipo_vaga = $_POST['tipo_editar_vaga'];
    $data = $_POST['data_editar_vaga'] ?? null;
    $hora = $_POST['hora_editar_vaga'] ?? null;

    $model = new main_model;
    $result = $model->editar_vaga($id, $empresa, $perfil, $quantidades_vagas, $quant_candidatos, $tipo_vaga, $data, $hora);

    switch ($result) {
        case 1:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 2:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 3:
            header('location:../views/dashboard.php#vagas');
            exit();
    }
} else if( isset($_GET['id_aluno']) && !empty($_GET['id_aluno'])){

     $id_aluno = $_GET['id_aluno'];

    $model = new main_model;
    $result = $model->excluir_aluno($id_aluno);

    switch ($result) {
        case 1:
            header('location:../views/dashboard.php#alunos');
            exit();
        case 2:
            header('location:../views/dashboard.php#alunos');
            exit();
        case 3:
            header('location:../views/dashboard.php#alunos');
            exit();
    }
    
    
    
    
    
    
} else if (
    isset($_POST['id_selecao_vaga']) && !empty($_POST['id_selecao_vaga']) &&
    isset($_POST['empresa_selecao_vaga']) && !empty($_POST['empresa_selecao_vaga']) &&
    isset($_POST['quant_candidatos_selecao']) && !empty($_POST['quant_candidatos_selecao']) &&
    isset($_POST['data_selecao']) && !empty($_POST['data_selecao']) &&
    isset($_POST['hora_selecao']) && !empty($_POST['hora_selecao'])
) {

    $id = $_POST['id_selecao_vaga'];
    $empresa = $_POST['empresa_selecao_vaga'];
    $quant_candidatos = $_POST['quant_candidatos_selecao'];
    $data = $_POST['data_selecao'];
    $hora = $_POST['hora_selecao'];

    $model = new main_model;
    $result = $model->cadastrar_selecao(
        $id,
        $empresa,
        $quant_candidatos,
        $data,
        $hora
    );

    switch ($result) {
        case 1:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 2:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 3:
            header('location:../views/dashboard.php#vagas');
            exit();
    }
    
    
    
    
    
    
    
    
    
    
}else{
    header('location:../views/login.php?erro');
    exit();
}

