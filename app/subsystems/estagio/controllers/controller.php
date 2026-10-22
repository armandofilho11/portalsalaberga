<?php
require_once('../models/model.php');

if (isset($_POST['alunos']) && !empty($_POST['alunos']) && isset($_POST['id_vaga']) && !empty($_POST['id_vaga'])) {

    $id_vaga = $_POST['id_vaga'];
    $alunos = $_POST['alunos'];

    $model = new main_model;
    $result = $model->selecao($alunos, $id_vaga);

    switch ($result) {

        case 1:
            header('location:https://www.google.com');
            exit();
        case 2:
            header('location:../views/vagas.php?erro');
            exit();
        default:
            header('location:../views/vagas.php?existe');
            exit();
    }
} else if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $model = new main_model;
    $result = $model->login($email, $senha);

    switch ($result) {

        case 1:
            header('location:../views/dashboard.php');
            exit();
        case 2:
            header('location:../views/login.php?erro'); 
            exit();
        default:
            header('location:../index.php');
            exit();
    }
} else if (isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['endereco']) && !empty($_POST['endereco'])) {

    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'] ?? '';
    $nome_contato = $_POST['nome_contato'] ?? '';

    $model = new main_model;
    $result = $model->cadastrar_empresa($nome, $endereco, $telefone, $nome_contato);
    switch ($result) {

        case 1:
            header('location:../views/gerenciar_empresas.php?certo');
            exit();
        case 2:
            header('location:../views/gerenciar_empresas.php?erro');
            exit();
        case 3:
            header('location:../views/gerenciar_empresas.php?existe');
            exit();
    }
} else if (isset($_POST['empresa']) && !empty($_POST['empresa']) && isset($_POST['areas']) && !empty($_POST['areas']) && isset($_POST['quant_vagas']) && !empty($_POST['quant_vagas']) && isset($_POST['quant_candidatos']) && !empty($_POST['quant_candidatos']) && isset($_POST['tipo_vaga']) && !empty($_POST['tipo_vaga'])) {

    $id_empresa = $_POST['empresa'];
    $id_area = $_POST['areas'];
    $quant_vagas = $_POST['quant_vagas'];
    $quant_candidatos = $_POST['quant_candidatos'];
    $data = $_POST['data'] ?? 0;
    $tipo_vaga = $_POST['tipo_vaga'];
    $hora = $_POST['hora'] ?? 0;

    $model = new main_model;
    $result = $model->cadastrar_vaga($id_empresa, $id_area, $quant_vagas, $quant_candidatos, $data, $tipo_vaga, $hora);

    switch ($result) {

        case 1:
            header('location:../views/vagas.php?certo');
            exit();
        case 2:
            header('location:../views/vagas.php?erro');
            exit();
        case 3:
            header('location:../views/vagas.php?existe');
            exit();
    }
} else if (
    isset($_POST['nome']) &&
    isset($_POST['contato']) &&
    isset($_POST['medias']) &&
    isset($_POST['email']) &&
    isset($_POST['projetos']) &&
    isset($_POST['perfil_opc1']) &&
    isset($_POST['perfil_opc2']) &&
    isset($_POST['ocorrencia']) &&
    isset($_POST['custeio']) &&
    isset($_POST['entregas_individuais']) &&
    isset($_POST['entregas_grupo']) &&
    isset($_POST['id'])
) {

    $nome = $_POST['nome'];
    $contato = $_POST['contato'];
    $medias = $_POST['medias'];
    $email = $_POST['email'];
    $projetos = $_POST['projetos'];
    $perfil_opc1 = $_POST['perfil_opc1'];
    $perfil_opc2 = $_POST['perfil_opc2'];
    $ocorrencia = $_POST['ocorrencia'];
    $custeio = $_POST['custeio'];
    $entregas_individuais = $_POST['entregas_individuais'];
    $entregas_grupo = $_POST['entregas_grupo'];
    $id = $_POST['id'];


    $model = new main_model;
    $result = $model->editar_aluno($id, $nome, $contato, $medias, $email, $projetos, $perfil_opc1, $perfil_opc2, $ocorrencia, $custeio, $entregas_individuais, $entregas_grupo);
    switch ($result) {
        case 1:
            header('location:../views/gerenciar_alunos.php?certo');
            exit();
        case 2:
            header('location:../views/gerenciar_alunos.php?erro');
            exit();
    }
} /*else {
    header('location:../views/login.php?session');
    exit();
}*/
