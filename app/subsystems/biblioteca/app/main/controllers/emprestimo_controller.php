<?php
require_once __DIR__ . '/../models/select_model.php';
require_once __DIR__ . '/../models/main_model.php';

$model = new Main_model();
$select_model = new select_model();
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && (
        array_key_exists('livro', $_POST)
        || array_key_exists('data_emprestimo', $_POST)
        || array_key_exists('data_devolucao_estipulada', $_POST)
    )
) {
    $error = null;
    if (empty($_POST['aluno'])) {
        $error = 'aluno_obrigatorio';
    } elseif (empty($_POST['livro']) || empty($_POST['data_emprestimo']) || empty($_POST['data_devolucao_estipulada'])) {
        $error = 'dados_incompletos';
    } elseif (!ctype_digit((string) $_POST['aluno']) || (int) $_POST['aluno'] < 1) {
        $error = 'aluno_invalido';
    } elseif (!ctype_digit((string) $_POST['livro']) || (int) $_POST['livro'] < 1) {
        $error = 'livro_invalido';
    } elseif (
        !($dataEmprestimo = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $_POST['data_emprestimo']))
        || $dataEmprestimo->format('Y-m-d') !== $_POST['data_emprestimo']
        || !($dataDevolucao = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $_POST['data_devolucao_estipulada']))
        || $dataDevolucao->format('Y-m-d') !== $_POST['data_devolucao_estipulada']
        || $dataDevolucao < $dataEmprestimo
    ) {
        $error = 'data_invalida';
    }

    if ($error !== null) {
        error_log('Empréstimo não registrado: ' . $error);
        if ($isAjax) {
            http_response_code(422);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => $error]);
        } else {
            header('Location: ../index.php?section=emprestimo_livro&erro=' . rawurlencode($error), true, 303);
        }
        exit;
    }

    $id_aluno = (int) $_POST['aluno'];
    $id_catalogo = (int) $_POST['livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao_estipulada = $_POST['data_devolucao_estipulada'];

    if (
        !$select_model->id_livro_selecionado($id_catalogo)
        || $select_model->count_emprestimos_ativos_por_catalogo($id_catalogo) > 0
    ) {
        error_log('Empréstimo não registrado: livro inexistente ou indisponível (ID ' . $id_catalogo . ').');
        if ($isAjax) {
            http_response_code(422);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'livro_indisponivel']);
        } else {
            header('Location: ../index.php?section=emprestimo_livro&erro=livro_indisponivel', true, 303);
        }
        exit;
    }

    $aluno = $select_model->id_aluno_selecionado($id_aluno);
    if (!$aluno) {
        error_log('Empréstimo não registrado: aluno não encontrado (ID ' . $id_aluno . ').');
        if ($isAjax) {
            http_response_code(422);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'aluno_invalido']);
        } else {
            header('Location: ../index.php?section=emprestimo_livro&erro=aluno_invalido', true, 303);
        }
        exit;
    }

    $sucesso = $model->registrar_emprestimo($id_aluno, $id_catalogo, $data_emprestimo, $data_devolucao_estipulada);
    if ($sucesso) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true]);
        } else {
            header('Location: ../index.php?section=emprestimo_livro&true', true, 303);
        }
    } else {
        error_log('Erro ao registrar empréstimo para o aluno ID: ' . $id_aluno);
        if ($isAjax) {
            http_response_code(422);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'registro_falha']);
        } else {
            header('Location: ../index.php?section=emprestimo_livro&false', true, 303);
        }
    }
    exit;
} else if (isset($_POST['aluno']) && !empty($_POST['aluno'])) {

    $id_aluno = $_POST['aluno'];

    $info = $select_model->id_aluno_selecionado($id_aluno);
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['info' => $info]);
    } else {
        header('Location: ../index.php?section=emprestimo_livro&' . http_build_query([
            'nome' => $info['nome'],
            'turma' => $info['turma'],
            'curso' => $info['curso'],
            'id_aluno' => $info['id_aluno']
        ]), true, 303);
    }
} else {
    if ($isAjax) {
        http_response_code(422);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => 'dados_incompletos']);
    } else {
        header('Location: ../index.php?section=emprestimo_livro&erro=dados_incompletos', true, 303);
    }
}
