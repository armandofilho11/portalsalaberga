<?php
require_once('../models/main_model.php');
 
//verificar tipo de relatório
if (isset($_POST['relatorio'])) {
    $relatorio = $_POST['relatorio'];

    switch ($relatorio) {
        case 'acervo':

            header('Location: ../views/relatorios/relatorio_acervo.php');
            exit;
        case '':

            header('Location: ../views/relatorios/relatorios.php?erro=1');
            exit;
        default:

            header('Location: ../views/relatorios/relatorios.php?erro=2');
            exit;
    }
} else if (isset($_POST['estante']) && isset($_POST['prateleira'])) {

    $estante = $_POST['estante'];
    $prateleira = $_POST['prateleira'];

    header('Location: ../views/relatorios/relatorio_pra_est.php?estante=' . $estante . '&prateleira=p' . $prateleira);
    exit();
}

//cadastrar livros 
else if (
    isset($_POST['nomesubGenero']) && !empty($_POST['nomesubGenero']) &&
    isset($_POST['nome']) && !empty($_POST['nome']) &&
    isset($_POST['sobrenome']) && !empty($_POST['sobrenome']) &&
    isset($_POST['titulo']) && !empty($_POST['titulo']) &&
    isset($_POST['editora']) && !empty($_POST['editora']) &&
    isset($_POST['data']) && !empty($_POST['data']) &&
    isset($_POST['corredor']) && !empty($_POST['corredor']) &&
    isset($_POST['quantidade']) && !empty($_POST['quantidade']) &&
    isset($_POST['livro_estante']) && !empty($_POST['livro_estante']) &&
    isset($_POST['livro_prateleira']) && !empty($_POST['livro_prateleira'])
) {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $editora = $_POST['editora'];
    $edicao = (!isset($_POST['edicao']) || empty($_POST['edicao'])) ? "ENI*" : $_POST['edicao'];
    $quantidade = $_POST['quantidade'];
    $corredor = $_POST['corredor'];
    $estante = $_POST['livro_estante'];
    $prateleira = $_POST['livro_prateleira'];
    $subgenero = $_POST['nomesubGenero'];
    $literatura = $_POST['literatura'] == "Brasileira" ? 1 : 0;
    $ficcao = $_POST['ficcao'] ?? 0;
    $cativo = $_POST['cativo'] ?? 0;


    $model = new main_model();
    $result = $model->cadastrar_livros($nome, $sobrenome, $titulo, $data, $editora, $edicao, $quantidade, $corredor, $estante, $prateleira, $subgenero, $literatura, $ficcao, $cativo);

    switch ($result) {
        case 1:
            header('location:../views/cadastrar_livro.php?true');
            exit();
        case 2:
            header('location:../views/cadastrar_livro.php?false');
            exit();
        case 3:
            header('location:../views/cadastrar_livro.php?ja_cadastrado');
            exit();
        default:
            header('location:../index.php');
            exit();
    }
} 
//cadastrar subgenero
else if (isset($_POST['genero']) && !empty($_POST['genero']) && isset($_POST['subgenero']) && !empty($_POST['subgenero'])) {
    $genero = $_POST['genero'];
    $subgenero = $_POST['subgenero'];

    $model = new main_model();
    $result = $model->cadastrar_subgenero($genero, $subgenero);

    switch ($result) {
        case 1:
            header('location:../views/cadastrar_subgenero.php?true');
            break;
        case 2:
            header('location:../views/cadastrar_subgenero.php?false');
            break;
        case 3:
            header('location:../views/cadastrar_subgenero.php?ja_cadastrado');
            break;
        default:
            header('location:../index.php');
            exit();
    }
} 
//cadastrar genero
else if (isset($_POST['novo_genero']) && !empty($_POST['novo_genero'])) {
    $novo_genero = $_POST['novo_genero'];
    $model = new main_model();
    $result = $model->cadastrar_genero($novo_genero);

    switch ($result) {
        case 1:
            header('location:../views/cadastrar_genero.php?true');
            break;
        case 2:
            header('location:../views/cadastrar_genero.php?false');
            break;
        case 3:
            header('location:../views/cadastrar_genero.php?ja_cadastrado');
            break;
        default:
            header('location:../index.php');
            exit();
    }
} else {

    header('location:../index.php');
    exit();
}
