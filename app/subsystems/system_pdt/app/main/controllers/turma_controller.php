<?php
require_once('../config/security.php');
require_once('../models/turmas_model.php');
require_once('../models/notificacao_model.php');

iniciarSessaoSegura();
exigirAutenticacao(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'] ?? '';

    if ($action === 'listar_cursos') {
        header('Content-Type: application/json; charset=utf-8');

        $turma = new TurmasModel();
        $dados = $turma->listarCursos();

        if ($dados) {
            echo json_encode([
                "sucesso" => true,
                "dados" => $dados
            ]);
            exit;
        } else {
            echo json_encode([
                "sucesso" => false,
                "erro" => "Erro ao listar cursos"
            ]);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($action === 'cadastrar_turma') {
            header('Content-Type: application/json; charset=utf-8');

            if (normalizarTipoUsuario($_SESSION['tipo'] ?? '') !== 'gestor') {
                echo json_encode(["sucesso" => false, "erro" => "Ação permitida apenas para gestor"]);
                exit;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $dados = json_decode(file_get_contents("php://input"), true);

            if (!$dados) {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "JSON inválido!"
                ]);
                exit;
            }

            if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
                echo json_encode(["sucesso" => false, "erro" => "Token CSRF inválido"]);
                exit;
            }

            $id_curso = intval($dados["id_curso"] ?? 0);
            $serie = trim($dados["serie_turma"] ?? "");
            $sala = trim($dados["sala_turma"] ?? "");
            $ano = intval($dados["ano_letivo"] ?? 0);
            $descricao = trim($dados["descricao_turma"] ?? "");
            $id_professor = intval($dados["id_professor"] ?? 0);
            
            // Validações
            if (empty($id_curso) || empty($serie) || empty($sala) || empty($ano) || empty($id_professor)) {
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "Preencha todos os campos obrigatórios."
                ]);
                exit;
            }

            // Validar se sala contém apenas números
            if (!preg_match('/^\d+$/', $sala)) {
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "O campo sala deve conter apenas números."
                ]);
                exit;
            }

            $turma = new TurmasModel();
            $resultado = $turma->cadastrarTurma($id_curso, $serie, $sala, $ano, $descricao, $id_professor);

            if (is_array($resultado) && isset($resultado['status']) && $resultado['status'] == 1) {
                $notificacoes = new NotificacaoModel();
                $notificacoes->criar(
                    (int) ($_SESSION['id_usuario'] ?? 0),
                    'Turma criada',
                    'Uma nova turma foi criada no sistema.',
                    'success',
                    null,
                    'turma',
                    $resultado['id'] ?? null
                );
                echo json_encode([
                    "sucesso" => true,
                    "mensagem" => "Turma criada com sucesso!"
                ]);
                exit;
            } else if ($resultado == 4) {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Este professor já está vinculado a uma turma! Desvincule-o ou altere o professor antes."
                ]);
                exit;
            } else if ($resultado == 5) {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Curso não encontrado!"
                ]);
                exit;
            } else {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Erro ao criar turma!"
                ]);
                exit;
            }
        }

        else if ($action === 'atualizar_turma') {
            header('Content-Type: application/json; charset=utf-8');

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $dados = json_decode(file_get_contents("php://input"), true);

            if (!$dados) {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "JSON inválido!"
                ]);
                exit;
            }

            if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
                echo json_encode(["sucesso" => false, "erro" => "Token CSRF inválido"]);
                exit;
            }

            $id_turma = intval($dados["id_turma"] ?? 0);
            if (!validarPermissaoTurmaParaAcao($id_turma)) {
                echo json_encode(["sucesso" => false, "erro" => "Sem permissão para atualizar esta turma"]);
                exit;
            }
            $id_curso = intval($dados["id_curso"] ?? 0);
            $serie = trim($dados["serie_turma"] ?? "");
            $sala = trim($dados["sala_turma"] ?? "");
            $descricao = trim($dados["descricao_turma"] ?? "");
            $id_professor = intval($dados["id_professor"] ?? 0);
            
            if (empty($id_turma) || empty($id_curso) || empty($serie) || empty($sala) || empty($id_professor)) {
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "Preencha todos os campos obrigatórios."
                ]);
                exit;
            }

            // Validar se sala contém apenas números
            if (!preg_match('/^\d+$/', $sala)) {
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "O campo sala deve conter apenas números."
                ]);
                exit;
            }

            $turma = new TurmasModel();
            $resultado = $turma->atualizarTurma($id_turma, $id_curso, $serie, $sala, $descricao, $id_professor);

            if ($resultado) {
                echo json_encode([
                    "sucesso" => true,
                    "mensagem" => "Turma atualizada com sucesso!"
                ]);
                exit;
            } else {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Erro ao atualizar turma!"
                ]);
                exit;
            }
        }

        else if ($action === 'deletar_turma') {
            header('Content-Type: application/json; charset=utf-8');

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $dados = json_decode(file_get_contents("php://input"), true);

            if (!validarCsrfToken($dados['csrf_token'] ?? '')) {
                echo json_encode(["sucesso" => false, "erro" => "Token CSRF inválido"]);
                exit;
            }

            if (!$dados || !isset($dados['id_turma'])) {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "ID da turma não fornecido!"
                ]);
                exit;
            }

            $id_turma = intval($dados['id_turma']);
            if (!validarPermissaoTurmaParaAcao($id_turma)) {
                echo json_encode(["sucesso" => false, "erro" => "Sem permissão para remover esta turma"]);
                exit;
            }

            $turma = new TurmasModel();
            $resultado = $turma->deletarTurma($id_turma);

            if ($resultado) {
                $notificacoes = new NotificacaoModel();
                $notificacoes->criar(
                    (int) ($_SESSION['id_usuario'] ?? 0),
                    'Turma removida',
                    'A turma foi removida do sistema.',
                    'warning',
                    null,
                    'turma',
                    $id_turma
                );
                echo json_encode([
                    "sucesso" => true,
                    "mensagem" => "Turma desvinculada com sucesso!"
                ]);
                exit;
            } else {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Erro ao desvincular turma!"
                ]);
                exit;
            }
        }

        else if ($action === 'listar_turmas') {
            header('Content-Type: application/json; charset=utf-8');

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['id_usuario'])) {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Usuário não autenticado!"
                ]);
                exit;
            }

            $turma = new TurmasModel();

            $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
            if ($tipoUsuario === 'gestor' || $tipoUsuario === 'coordenador') {
                $dados = $turma->listarTurmas();
            } else {
                $dados = $turma->listarTurmas((int) $_SESSION['id_usuario']);
            }

            if (is_array($dados)) {
                echo json_encode([
                    "sucesso" => true,
                    "dados" => $dados
                ]);
                exit;
            } else {
                echo json_encode([
                    "sucesso" => false,
                    "erro" => "Erro ao listar turmas"
                ]);
                exit;
            }
        }
    }
}
