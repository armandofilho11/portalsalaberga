<?php
require_once __DIR__ . '/../models/select_model.php';
require_once __DIR__ . '/../models/main_model.php';

$model = new Main_model();
$select_model = new select_model();
// Debug output removed. Controller will return JSON for AJAX requests
// and perform redirects for normal form submissions.


if (isset($_POST['livro']) && !empty($_POST['livro']) && isset($_POST['data_emprestimo']) && isset($_POST['data_devolucao_estipulada']) && !empty($_POST['data_emprestimo']) && !empty($_POST['data_devolucao_estipulada'])) {

    $id_aluno = $_POST['aluno'];
    $id_catalogo = $_POST['livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao_estipulada = $_POST['data_devolucao_estipulada'];

    if ($id_aluno && $id_catalogo && $data_emprestimo && $data_devolucao_estipulada) {
        $sucesso = $model->registrar_emprestimo($id_aluno, $id_catalogo, $data_emprestimo, $data_devolucao_estipulada);

        if ($sucesso) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    echo json_encode(['success' => true]);
                } else {
                    header('Location: ../index.php?action=emprestimo_sucesso');
                }
        } else {
                error_log('Erro ao registrar empréstimo para o aluno ID: ' . $id_aluno);
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'error' => 'registro_falha']);
                } else {
                    header('Location: ../index.php?action=emprestimo_falha');
                }
        }
    } else {
        error_log('Dados incompletos no envio do formulário.');
        header('Location: ../index.php?action=erro_dados_incompletos');
    }

    exit;
} else if (isset($_POST['aluno']) && !empty($_POST['aluno'])) {

    $id_aluno = $_POST['aluno'];

    $info = $select_model->id_aluno_selecionado($id_aluno);
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['info' => $info]);
    } else {
        header('Location: ../views/emprestimo/emprestimo_livro.php?nome=' . urlencode($info['nome']) . '&turma=' . urlencode($info['turma']) . '&curso=' . urlencode($info['curso']) . '&id_aluno=' . urlencode($info['id_aluno']));
    }
} else {
    //header('Location: ../../views/emprestimo/index.php?action=erro_dados_incompletos');
}
