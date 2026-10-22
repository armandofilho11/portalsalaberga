<?php
require_once __DIR__ . '/../models/main_model.php';

$model = new main_model();

print_r($_POST);
if (isset($_POST['livro_emprestimo']) && !empty($_POST['livro_emprestimo']) && isset($_POST['data_devolucao']) && !empty($_POST['data_devolucao'])) {
    $id_emprestimo = $_POST['livro_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];

    $sucesso = $model->registrar_devolucao($id_emprestimo, $data_devolucao);

    if ($sucesso) {
        header('Location: ../index.php?action=devolucao_sucesso');
    } else {
        header('Location: ../index.php?action=devolucao_falha&erro=registro');
    }
    exit;
} else if (isset($_POST['aluno']) && !empty($_POST['aluno'])) {
    $id_aluno = $_POST['aluno'];
    $info = $select_model->id_aluno_selecionado($id_aluno);
    header('Location: ../views/emprestimo_devolucao/emprestimo_livro.php?nome=' . $info['nome'] . '&turma=' . $info['turma'] . '&curso=' . $info['curso'] . '&id_aluno=' . $info['id_aluno']);
} else {
    error_log('Nenhum dado POST recebido');
    header('Location: ../index.php?action=erro_dados_incompletos');
}
