<?php 

// Incluir a classe Cadastro
require_once '../models/model-function.php';

if(isset($_GET['btn-editar'])){
    $id = $_GET['btn-editar'];
    
    // Buscar dados do aluno
    $aluno = new Cadastro();
    $dados_aluno = $aluno->editar_alunoById($id);
    
    if($dados_aluno) {
        // Incluir a view de edição com os dados do aluno
        include '../views/editar_aluno.php';
    } else {
        // Redirecionar para a lista com mensagem de erro
        header('Location: ../views/perfildoaluno.php?error=aluno_nao_encontrado');
        exit;
    }
} else if(isset($_GET['btn-editar_empresa'])) {
    $id = $_GET['btn-editar_empresa'];
    // Buscar dados da empresa
    $empresa = new Cadastro();
    $dados_empresa = $empresa->editar_empresaById($id);
    if($dados_empresa) {
        include '../views/editar_empresa.php';
    } else {
        header('Location: ../views/dadosempresa.php?error=empresa_nao_encontrada');
        exit;
    }
} else {
    // Se não houver ID, redirecionar para a lista
    header('Location: ../views/perfildoaluno.php');
    exit;
}



?>