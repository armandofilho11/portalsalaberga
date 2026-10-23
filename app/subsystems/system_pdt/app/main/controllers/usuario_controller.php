<?php
    require_once(__DIR__ . '/../config/security.php');
    require_once(__DIR__ . '/../models/usuario_model.php');

    iniciarSessaoSegura();

    $method = $_SERVER['REQUEST_METHOD'];

    $action = $_GET['action'] ?? '';

    if ($method === 'POST') {

        // =========================
        // CADASTRAR USUÁRIO
        // =========================
        if ($action === 'primeiro_acesso') {

            $nome = trim($_POST['nome'] ?? '');
            $matricula = trim($_POST['matricula'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $tipo = trim($_POST['tipo'] ?? '');
            $csrfToken = $_POST['csrf_token'] ?? '';

            if (!validarCsrfToken($csrfToken)) {
                http_response_code(403);
                header("Location: ../index.php?erro=csrf_invalido");
                exit;
            }

            // sanitização
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            // validar campos
            if (
                empty($nome) ||
                empty($matricula) ||
                empty($email) ||
                empty($senha) ||
                empty($tipo)
            ) {
                header("Location: ../index.php?erro=campos_vazios");
                exit;
            }

            // validar email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../index.php?erro=email_invalido");
                exit;
            }

            // validar senha
            if (strlen($senha) < 6) {
                header("Location: ../index.php?erro=senha_fraca");
                exit;
            }

            // validar tipo
            $tiposPermitidos = ['gestor', 'coordenador', 'professor'];

            if (!in_array($tipo, $tiposPermitidos)) {
                header("Location: ../index.php?erro=tipo_invalido");
                exit;
            }

            $usuario = new usuario_model();

            $result = $usuario->cadastrar(
                $nome,
                $matricula,
                $email,
                $senha,
                $tipo
            );

            switch ($result) {

                case 1:
                    header("Location: ../index.php?sucesso=usuario_cadastrado");
                    exit;

                case 2:
                    header("Location: ../index.php?erro=email_existente");
                    exit;

                case 3:
                    header("Location: ../index.php?erro=matricula_existente");
                    exit;

                default:
                    header("Location: ../index.php?erro=erro_servidor");
                    exit;
            }
        }

        // =========================
        // LOGIN
        // =========================
        else if ($action === 'login') {

            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $csrfToken = $_POST['csrf_token'] ?? '';

            if (!validarCsrfToken($csrfToken)) {
                http_response_code(403);
                header("Location: ../index.php?erro=csrf_invalido");
                exit;
            }

            if (empty($email) || empty($senha)) {
                header("Location: ../index.php?erro=campos_vazios");
                exit;
            }

            $usuario = new usuario_model();

            $result = $usuario->login($email, $senha);

            switch ($result['status']) {

                case 1:

                    iniciarSessaoSegura();
                    session_regenerate_id(true);

                    $_SESSION['id_usuario'] = $result['usuario']['id'];
                    $_SESSION['nome'] = $result['usuario']['nome'];
                    $_SESSION['matricula'] = $result['usuario']['matricula'];
                    $_SESSION['email'] = $result['usuario']['email'];
                    $_SESSION['tipo'] = normalizarTipoUsuario($result['usuario']['tipo']);

                    // redirecionamento por tipo
                    if ($_SESSION['tipo'] === 'gestor') {

                        header("Location: ../views/gestor.php");
                        exit;

                    } else if ($_SESSION['tipo'] === 'coordenador') {

                        header("Location: ../views/coordenador.php");
                        exit;

                    } else if ($_SESSION['tipo'] === 'professor') {

                        header("Location: ../views/professor.php");
                        exit;
                    } else {

                        header("Location: ../index.php?erro=tipo_invalido");
                        exit;
                    }

                case 2:
                    header("Location: ../index.php?erro=usuario_nao_encontrado");
                    exit;

                case 3:
                    header("Location: ../index.php?erro=senha_incorreta");
                    exit;

                default:
                    header("Location: ../index.php?erro=erro_servidor");
                    exit;
            }
        }

        // =========================
        // LOGOUT
        // =========================
        else if ($action === 'logout') {

            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!validarCsrfToken($csrfToken)) {
                http_response_code(403);
                header("Location: ../index.php?erro=csrf_invalido");
                exit;
            }

            iniciarSessaoSegura();

            session_unset();
            session_destroy();

            header("Location: ../index.php?sucesso=logout");
            exit;
        }
    }

    // =========================
    // LISTAR PROFESSORES (GET)
    // =========================
    else if ($method === 'GET') {

        if ($action === 'listar_professores') {

            header('Content-Type: application/json; charset=utf-8');

            $usuario = new usuario_model();
            $professores = $usuario->listar_professores();

            echo json_encode([
                'sucesso' => true,
                'dados' => $professores
            ]);
            exit;

        }

    }

?>