<?php
require_once __DIR__ . '/../models/select_model.php';
require_once __DIR__ . '/../models/main_model.php';

$model = new main_model();
$select_model = new select_model();

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (isset($_POST['livro_emprestimo']) && !empty($_POST['livro_emprestimo']) && isset($_POST['data_devolucao']) && !empty($_POST['data_devolucao'])) {
    $id_emprestimo = $_POST['livro_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];

    $sucesso = $model->registrar_devolucao($id_emprestimo, $data_devolucao);

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        if ($sucesso) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'registro']);
        }
    } else {
        if ($sucesso) {
            header('Location: ../index.php?section=devolucao_livro&action=devolucao_sucesso');
        } else {
            header('Location: ../index.php?section=devolucao_livro&action=devolucao_falha&erro=registro');
        }
    }
    exit;
} else if (isset($_POST['aluno']) && !empty($_POST['aluno'])) {
    $id_aluno = $_POST['aluno'];
    $info = $select_model->id_aluno_selecionado($id_aluno);
    if ($info) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['info' => $info]);
        } else {
            header('Location: ../index.php?section=devolucao_livro&' . http_build_query([
                'nome' => $info['nome'],
                'turma' => $info['turma'],
                'curso' => $info['curso'],
                'id_aluno' => $info['id_aluno']
            ]));
        }
    } else {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'dados_incompletos']);
        } else {
            header('Location: ../index.php?section=devolucao_livro&action=erro_dados_incompletos');
        }
    }
    exit;
} else {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => 'dados_incompletos']);
    } else {
        error_log('Nenhum dado POST recebido');
        header('Location: ../index.php?section=devolucao_livro&action=erro_dados_incompletos');
    }
    exit;
}
