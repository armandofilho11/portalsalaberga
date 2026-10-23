<?php
require_once ('../models/main_model.php');
print_r($_POST);

/*controller para excluir livro*/
if (isset($_POST['excluir_livro']) && !empty($_POST['excluir_livro'])) {
    $id_livro = $_POST['excluir_livro'];
    print_r($id_livro);
    $model = new main_model();
    $result = $model->excluir_livro($id_livro);

    switch ($result) {
        case 1:
            header('location:../views/excluir_livro.php?true');
            break;
        case 2:
            header('location:../views/excluir_livro.php?error');
            break;
    }
    /*controller para editar livro*/
} else if (
    isset($_POST['id_livro']) && !empty($_POST['id_livro']) &&
    isset($_POST['titulo_livro']) && !empty($_POST['titulo_livro']) &&
    isset($_POST['edicao']) && !empty($_POST['edicao']) &&
    isset($_POST['editora']) && !empty($_POST['editora']) &&
    isset($_POST['quantidade']) && !empty($_POST['quantidade']) &&
    isset($_POST['corredor']) && !empty($_POST['corredor']) &&
    isset($_POST['estante']) && !empty($_POST['estante']) &&
    isset($_POST['prateleira']) && !empty($_POST['prateleira']) &&
    isset($_POST['subgenero']) && !empty($_POST['subgenero']) &&
    isset($_POST['genero']) && !empty($_POST['genero']) &&
    isset($_POST['ano_publicacao']) && !empty($_POST['ano_publicacao'])
) {
    $id_livro = $_POST['id_livro'];
    $titulo = $_POST['titulo_livro'];
    $edicao = $_POST['edicao'];
    $editora = $_POST['editora'];
    $quantidade = $_POST['quantidade'];
    $corredor = $_POST['corredor'];
    $estante = $_POST['estante'];
    $prateleira = $_POST['prateleira'];
    $subgenero = $_POST['subgenero'];
    $literatura = isset($_POST['literatura']) ? 1 : 0;
    $ficcao = isset($_POST['ficcao']) ? 1 : 0;
    $cativo = isset($_POST['cativo']) ? 1 : 0;
    $genero = $_POST['genero'];
    $ano_publicacao = $_POST['ano_publicacao'];
    $subgenero = $_POST['subgenero'];
    $genero = $_POST['genero'];

    $model = new main_model();
    $result = $model->editar_livro($id_livro, $titulo, $ano_publicacao, $editora, $edicao, $quantidade, $corredor, $estante, $prateleira, $genero, $subgenero, $literatura, $ficcao, $cativo);

    switch ($result) {
        case 1:
            header('location:../views/editar_livro.php?true');
            break;
        case 2:
            header('location:../views/editar_livro.php?error');
            break;
    }
    /*controller para editar autor*/
} else if (
    isset($_POST['id_autor']) && !empty($_POST['id_autor']) &&
    isset($_POST['nome']) && !empty($_POST['nome']) &&
    isset($_POST['sobrenome']) && !empty($_POST['sobrenome'])
) {
    $id_autor = $_POST['id_autor'];
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];

    $model = new main_model();
    $result = $model->editar_autor($id_autor, $nome, $sobrenome);

    switch ($result) {
        case 1:
            header('location:../views/editar_autor.php?true');
            break;
        case 2:
            header('location:../views/editar_autor.php?error');
            break;
    }
}else{

    header('location:../views/decisao.php');
    exit();
}
