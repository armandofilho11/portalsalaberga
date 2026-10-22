<?php
require_once '../config/conexao.php';
require_once '../control/avisosControl.php';

// Inicializa a conexão com o banco de dados
$conexao = new Conexao('localhost', 'root', '', 'sis_pdt2');
$pdo = $conexao->getConnection();

// Inicializa o controlador de avisos
$avisosControl = new AvisosControl();

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recebe e valida os dados do formulário
        $aviso = filter_input(INPUT_POST, 'aviso', FILTER_SANITIZE_STRING);
        $matricula_aluno = filter_input(INPUT_POST, 'matricula_aluno', FILTER_SANITIZE_NUMBER_INT);
        $data_aviso = filter_input(INPUT_POST, 'data_aviso', FILTER_SANITIZE_STRING);

        // Validações básicas
        if (empty($aviso)) {
            throw new Exception("O campo aviso é obrigatório!");
        }

        if (empty($matricula_aluno)) {
            throw new Exception("A matrícula do aluno é obrigatória!");
        }

        if (empty($data_aviso)) {
            throw new Exception("A data do aviso é obrigatória!");
        }

        // Verifica se o aluno existe
        if (!$avisosControl->verificarAluno($matricula_aluno)) {
            throw new Exception("Aluno não encontrado!");
        }

        // Adiciona o aviso
        if ($avisosControl->adicionarAviso($aviso, $matricula_aluno, $data_aviso)) {
            // Redireciona com mensagem de sucesso
            header("Location: ../view/avisos.php?status=success&message=Aviso cadastrado com sucesso!");
            exit();
        } else {
            throw new Exception("Erro ao cadastrar aviso!");
        }

    } catch (Exception $e) {
        // Redireciona com mensagem de erro
        header("Location: ../view/avisos.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Se não for POST, redireciona para a página de avisos
    header("Location: ../view/avisos.php");
    exit();
}
?> 