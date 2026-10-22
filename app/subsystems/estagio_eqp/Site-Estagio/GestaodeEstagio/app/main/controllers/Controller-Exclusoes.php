<?php 
require("../models/model-function.php");

// Exclusão de Empresa
if (isset($_POST['btn-excluir']) && isset($_POST['tipo']) && $_POST['tipo'] === 'empresa') {
    try {
        $id = $_POST['btn-excluir'];
        $x = new Cadastro();
        $resultado = $x->excluir_empresa($id);
        
        if($resultado){
            header('location: ../views/dadosempresa.php?resultado=excluir');
        } else {
            header('location: ../views/dadosempresa.php?resultado=erro');
        }
    } catch (PDOException $e) {
        // Log do erro para debug
        error_log("Erro ao excluir empresa: " . $e->getMessage());
        
        // Redireciona com mensagem de erro apropriada
        if($e->getCode() == 23000) {
            header('location: ../views/dadosempresa.php?resultado=erro_fk');
        } else {
            header('location: ../views/dadosempresa.php?resultado=erro');
        }
    }
}

// Exclusão de Aluno
if (isset($_POST['btn-excluir']) && isset($_POST['tipo']) && $_POST['tipo'] === 'aluno') {
    $id = $_POST['btn-excluir'];
    $x = new Cadastro();
    $x->excluir_aluno($id);
    header('location:../views/perfildoaluno.php?resultado=excluir');
}

// Exclusão de Formulário
if (isset($_POST['btn-excluir']) && isset($_POST['tipo']) && $_POST['tipo'] === 'formulario') {
    $id = $_POST['id'];
    $x = new Cadastro();
    $x->excluir_formulario($id);
    header('location:../views/processoseletivo.php?resultado=excluir');
}

// Exclusão de Inscrito (via AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo']) && $_POST['tipo'] === 'inscrito') {
    header('Content-Type: application/json');
    
    if (isset($_POST['id_selecao'])) {
        try {
            $id_selecao = $_POST['id_selecao'];
            $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Update the selecao table to remove the student enrollment
            $stmt = $pdo->prepare('UPDATE selecao SET id_aluno = NULL, data_inscriçao = NULL WHERE id = ?');
            $result = $stmt->execute([$id_selecao]);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Inscrição removida com sucesso'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao remover inscrição'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao remover inscrição: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Dados incompletos'
        ]);
    }
    exit;
}
?> 