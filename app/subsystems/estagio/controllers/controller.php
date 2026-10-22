<?php
require_once('../models/model.php');

if (isset($_POST['alunos']) && !empty($_POST['alunos']) && isset($_POST['id_vaga']) && !empty($_POST['id_vaga'])) {

    $id_vaga = (int) $_POST['id_vaga'];
    $alunos = array_map('intval', (array) $_POST['alunos']);

    $model = new main_model;
    $result = $model->selecao($alunos, $id_vaga);

    switch ($result) {

        case 1:
            header('location:../views/dashboard.php#vagas');
            exit();
        case 2:
            header('location:../views/dashboard.php#vagas');
            exit();
        default:
            header('location:../views/dashboard.php#vagas');
            exit();
    }
} else if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar_selecao' && isset($_POST['id_vaga']) && !empty($_POST['id_vaga'])) {

    $id_vaga = (int) $_POST['id_vaga'];
    $adicionar = isset($_POST['adicionar']) ? array_map('intval', (array) $_POST['adicionar']) : [];
    $remover = isset($_POST['remover']) ? array_map('intval', (array) $_POST['remover']) : [];

    $model = new main_model;
    $result = $model->atualizar_selecao_por_vaga($id_vaga, $adicionar, $remover);

    header('location:../views/dashboard.php#vagas');
    exit();
} else if (isset($_POST['acao']) && $_POST['acao'] === 'finalizar_selecao' && isset($_POST['id_vaga']) && !empty($_POST['id_vaga'])) {

    $id_vaga = (int) $_POST['id_vaga'];
    $aprovados = isset($_POST['aprovados']) ? array_map('intval', (array) $_POST['aprovados']) : [];

    $model = new main_model;
    $model->finalizar_selecao_por_vaga($id_vaga, $aprovados);

    header('location:../views/dashboard.php#vagas');
    exit();
} else if (isset($_POST['acao']) && $_POST['acao'] === 'remover_aluno_vaga' && isset($_POST['id_vaga']) && !empty($_POST['id_vaga']) && isset($_POST['id_aluno']) && !empty($_POST['id_aluno'])) {

    $id_vaga = (int) $_POST['id_vaga'];
    $id_aluno = (int) $_POST['id_aluno'];

    $model = new main_model;
    $model->remover_aluno_da_vaga($id_vaga, $id_aluno);

    header('location:../views/dashboard.php#vagas');
    exit();
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
    
    
    
    
//CADASTRAR NOVA EMPRESA / CONCEDENTE    
} else if (isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['endereco']) && !empty($_POST['endereco'])) {

    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'] ?? '';
    $nome_contato = $_POST['nome_contato'] ?? '';

    $model = new main_model;
    $result = $model->cadastrar_empresa($nome, $endereco, $telefone, $nome_contato);
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
} 





else if (
    isset($_POST['empresa']) && !empty($_POST['empresa']) &&
    isset($_POST['areas']) && !empty($_POST['areas']) &&
    isset($_POST['quant_vagas']) && !empty($_POST['quant_vagas']) &&
    isset($_POST['tipo_vaga']) && !empty($_POST['tipo_vaga'])
) {

    $id_empresa = $_POST['empresa'];
    $id_area = $_POST['areas'];
    $quant_vagas = $_POST['quant_vagas'];
    $tipo_vaga = $_POST['tipo_vaga'];

    $model = new main_model;

    $result = $model->cadastrar_vaga(
        $id_empresa,
        $id_area,
        $quant_vagas,
        $tipo_vaga
    );

    switch ($result) {

        case 2:
            header('location:../views/dashboard.php#vagas');
            exit();

        case 3:
            header('location:../views/dashboard.php#vagas');
            exit();

        default:

            header('location:../views/dashboard.php#vagas');
            exit();
    }
}







else if (isset($_POST['quant_candidatos']) && !empty($_POST['quant_candidatos'])) {

    $id_vaga = $_POST['id_vaga']; // ID do registro já existente
    $quant_candidatos = $_POST['quant_candidatos'];
    $data = $_POST['data'] ?? 0;
    $hora = $_POST['hora'] ?? 0;

    $model = new main_model;

    $result = $model->cadastrar_entrevista($id_vaga,$quant_candidatos,$data,$hora);

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
} 

else if (
    isset($_POST['nome']) &&
    isset($_POST['contato']) &&
    isset($_POST['medias']) &&
    isset($_POST['email']) &&
    isset($_POST['projetos']) &&
    isset($_POST['perfil_opc1']) &&
    isset($_POST['perfil_opc2']) &&
    isset($_POST['localidade']) &&
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
    $localidade = $_POST['localidade'];
    $ocorrencia = (int) ($_POST['ocorrencia'] ?? 0);
    $custeio = (int) ($_POST['custeio'] ?? 0);
    $entregas_individuais = $_POST['entregas_individuais'];
    $entregas_grupo = $_POST['entregas_grupo'];
    $id = $_POST['id'];


    $model = new main_model;
    $result = $model->editar_aluno($id, $nome, $contato, $medias, $email, $projetos, $perfil_opc1, $perfil_opc2, $localidade, $ocorrencia, $custeio, $entregas_individuais, $entregas_grupo);
    switch ($result) {
        case 1:
            header('location:../views/dashboard.php#alunos');
            exit();
        case 2:
            header('location:../views/dashboard.php#alunos');
            exit();
    }
} /*else {
    header('location:../views/login.php?session');
    exit();
}*/
