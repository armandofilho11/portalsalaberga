<?php 
require("../models/model-function.php");

// Verifica se a requisição é POST e se tem o botão de editar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-editar'])) {
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    
    if ($tipo === 'aluno') {
        // Edição de aluno
        if (isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['matricula']) && 
            isset($_POST['contato']) && isset($_POST['curso']) && isset($_POST['email']) && 
            isset($_POST['endereco'])) {
            
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $matricula = $_POST['matricula'];
            $contato = $_POST['contato'];
            $curso = $_POST['curso'];
            $email = $_POST['email'];
            $endereco = $_POST['endereco'];
            
            $cadastro = new Cadastro();
            $resultado = $cadastro->editar_aluno_sem_senha($id, $nome, $matricula, $contato, $curso, $email, $endereco);
            
            if ($resultado) {
                header("Location: ../views/editar_aluno.php?success=aluno_atualizado");
            } else {
                header("Location: ../views/editar_aluno.php?error=erro_ao_atualizar");
            }
            exit;
        }
    } 
    else if ($tipo === 'empresa') {
        // Edição de empresa
        if (isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['contato']) && 
            isset($_POST['endereco']) && isset($_POST['numero_vagas'])) {
            
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $contato = $_POST['contato'];
            $endereco = $_POST['endereco'];
            $perfis = isset($_POST['perfis']) ? json_encode($_POST['perfis']) : json_encode([]);
            $vagas = $_POST['numero_vagas'];

            $cadastro = new Cadastro();
            $resultado = $cadastro->editar_empresa($id, $nome, $contato, $endereco, $perfis, $vagas);
            
            if ($resultado) {
                header('Location: ../views/dadosempresa.php?resultado=editar');
            } else {
                header('Location: ../views/dadosempresa.php?resultado=erro');
            }
            exit;
        }
    }
    
    // Se chegou aqui, é porque houve algum erro na validação
    if ($tipo === 'aluno') {
        header("Location: ../views/editar_aluno.php?error=dados_invalidos");
    } else if ($tipo === 'empresa') {
        header('Location: ../views/dadosempresa.php?resultado=erro');
    }
    exit;
}

// Se não for uma requisição POST válida, redireciona para a página apropriada
if (isset($_POST['tipo']) && $_POST['tipo'] === 'aluno') {
    header("Location: ../views/editar_aluno.php");
} else {
    header('Location: ../views/dadosempresa.php');
}
exit;
?> 