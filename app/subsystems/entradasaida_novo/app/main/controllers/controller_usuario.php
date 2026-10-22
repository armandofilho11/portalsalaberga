<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? null;

if (!$action) {
    header("location: ../views/dashboard.php");
    exit();
}

require_once "../models/usuario.php";

$usuario = new Usuario();

switch ($action) {

    case 'logout':

        if ($usuario->logout()) {

            header("location: ../index.php");
            exit();
        }

        header("location: ../views/dashboard.php?message=ERRO_AO_DESCONECTAR");
        exit();

        break;

    case 'login':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("location: ../index.php");
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {

            header("location: ../index.php?erro=campos");
            exit();
        }

        $check = $usuario->autenticar($email, $senha);

        if ($check === false) {

            header("location: ../index.php?erro=login");
            exit();
        }

        header("location: ../views/dashboard.php");
        exit();

        break;

    case 'cadastrar':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("location: ../views/dashboard.php");
            exit();
        }

        $nome      = trim($_POST['nome'] ?? '');
        $matricula = trim($_POST['matricula'] ?? '');
        $serie     = trim($_POST['serie'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $senha     = $_POST['senha'] ?? '';
        $tipo      = trim($_POST['tipo'] ?? '');

        // VALIDA CAMPOS
        if (
            empty($nome) ||
            empty($email) ||
            empty($senha) ||
            empty($tipo)
        ) {

            header("location: ../../index.php?erro=campos");
            exit();
        }

        // VALIDA EMAIL
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            header("location: ../index.php?erro=email");
            exit();
        }

        // TIPOS PERMITIDOS (inclui os usados no formulário)
        $tiposPermitidos = [
            'admin',
            'funcionario',
            'professor',
            'desenvolvedor',
            'suporte'
        ];

        // VALIDA TIPO
        if (!in_array($tipo, $tiposPermitidos)) {

            header("location: ../index.php?erro=tipo");
            exit();
        }

        // Se fornecida, podemos validar a matrícula (não obrigatória para criar usuário)
        /*if (!empty($matricula)) {
            require_once __DIR__ . "../models/Aluno.php";
            $alunoModel = new Aluno();
            $aluno = $alunoModel->buscarPorMatricula($matricula);
            // não bloqueamos o cadastro se matrícula não encontrada, apenas não vinculamos
        } */

        // CADASTRA USUÁRIO
        $cadastrar = $usuario->cadastrarUsuario(
            $nome,
            $email,
            $senha,
            $tipo
        );

        if (!$cadastrar) {

            header("location: ../index.php?erro=cadastro");
            exit();
        }

        header("location: ../index.php?sucesso=1");
        exit();

        break;

    case 'registrar_entrada':

        // VERIFICA LOGIN
        if (!$usuario->verificarLogin()) {

            header("location: ../index.php?erro=login");
            exit();
        }

        // VERIFICA MÉTODO
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("location: ../views/dashboard.php");
            exit();
        }

        // RECEBE DADOS
        $id_aluno = intval($_POST['id_aluno'] ?? 0);

        // VALIDA ALUNO
        if ($id_aluno <= 0) {

            header("location: ../views/registros/registro_entrada.php?erro=aluno");
            exit();
        }

        $data_entrada = trim($_POST['data_entrada'] ?? date('Y-m-d'));
        $hora_entrada = trim($_POST['hora_entrada'] ?? date('H:i'));
        $motivo = trim($_POST['motivo'] ?? '');
        $descricao = $_POST['descricao'] ?? '';

        // VALIDA DATA E HORA
        if (empty($data_entrada) || empty($hora_entrada)) {

            header("location: ../views/registros/registro_entrada.php?erro=data");
            exit();
        }

        // VALIDA MOTIVO
        if (empty($motivo)) {

            header("location: ../views/registros/registro_entrada.php?erro=motivo");
            exit();
        }

        // REGISTRA ENTRADA
        $registrar = $usuario->registrarEntrada(
            $id_aluno,
            $data_entrada,
            $hora_entrada,
            $motivo,
            $descricao
        );

        // ERRO AO REGISTRAR
        if (!$registrar) {

            header("location: ../views/registros/registro_entrada.php?erro=registro");
            exit();
        }

        // SUCESSO
        header("location: ../views/registros/registro_entrada.php?sucesso=1");
        exit();

        break;

 case 'registrar_saida':

        // VERIFICA LOGIN
        if (!$usuario->verificarLogin()) {

            header("location: ../index.php?erro=login");
            exit();
        }

        // VERIFICA MÉTODO
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("location: ../views/dashboard.php");
            exit();
        }

        // RECEBE DADOS
        $id_aluno = intval($_POST['id_aluno'] ?? 0);

        // VALIDA ALUNO
        if ($id_aluno <= 0) {

            header("location: ../views/registros/registro_saida.php?erro=aluno");
            exit();
        }

        $tipo_responsavel = trim($_POST['tipo_responsavel'] ?? '');
        $nome_responsavel = trim($_POST['nome_responsavel'] ?? '');
        $motivo = trim($_POST['motivo'] ?? '');
        $data_saida = trim($_POST['data_saida'] ?? date('Y-m-d'));
        $hora_saida = trim($_POST['hora_saida'] ?? date('H:i'));
        $tipo_conducente = trim($_POST['tipo_conducente'] ?? '');
        $descricao = $_POST['descricao'] ?? '';

        // VALIDA CAMPOS OBRIGATÓRIOS
        if (empty($tipo_responsavel) || empty($nome_responsavel) || empty($motivo) || empty($tipo_conducente) || empty($data_saida) || empty($hora_saida)) {

            header("location: ../views/registros/registro_saida.php?erro=campos");
            exit();
        }

        // REGISTRA SAÍDA
        $registrar = $usuario->registrarSaida(
            $id_aluno,
            $tipo_responsavel,
            $nome_responsavel,
            $motivo,
            $data_saida,
            $hora_saida,
            $tipo_conducente,
            $descricao
        );

        // ERRO AO REGISTRAR
        if (!$registrar) {

            error_log("Erro ao registrar saída no controller_usuario: aluno={$id_aluno}, responsavel={$tipo_responsavel}, conducente={$tipo_conducente}");
            header("location: ../views/registros/registro_saida.php?erro=registro");
            exit();
        }

        // SUCESSO
        header("location: ../views/registros/registro_saida.php?sucesso=1");
        exit();

        break;

    case 'registrar_saida_estagio':

        // VERIFICA LOGIN
        if (!$usuario->verificarLogin()) {

            header("location: ../index.php?erro=login");
            exit();
        }

        // VERIFICA MÉTODO
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("location: ../views/dashboard.php");
            exit();
        }

        $id_aluno = intval($_POST['id_aluno'] ?? 0);

        if ($id_aluno <= 0) {

            header("location: ../views/registros/registro_saida_estagio.php?erro=aluno");
            exit();
        }

        $data_saida = trim($_POST['data_saida'] ?? date('Y-m-d'));
        $hora_saida = trim($_POST['hora_saida'] ?? date('H:i'));

        if (empty($data_saida) || empty($hora_saida)) {

            header("location: ../views/registros/registro_saida_estagio.php?erro=campos");
            exit();
        }

        $registrar = $usuario->registrarSaidaEstagio(
            $id_aluno,
            $data_saida,
            $hora_saida
        );

        if (!$registrar) {

            header("location: ../views/registros/registro_saida_estagio.php?erro=registro");
            exit();
        }

        header("location: ../views/registros/registro_saida_estagio.php?sucesso=1");
        exit();

        break;

    case 'registrar_pre_estagio':

        if (!$usuario->verificarLogin()) {
            header("location: ../index.php?erro=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("location: ../views/dashboard.php");
            exit();
        }

        $id_aluno = intval($_POST['id_aluno'] ?? 0);
        $data_registro = trim($_POST['data_registro'] ?? date('Y-m-d'));
        $hora_registro = trim($_POST['hora_registro'] ?? date('H:i'));
        $momento = trim($_POST['momento'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');

        $momentosPermitidos = [
            'ida_almoco',
            'retorno_almoco',
            'ida_lanche',
            'retorno_lanche'
        ];

        if ($id_aluno <= 0 || empty($data_registro) || empty($hora_registro) || !in_array($momento, $momentosPermitidos, true)) {
            header("location: ../views/registros/registro_pre_estagio.php?erro=campos");
            exit();
        }

        $registrar = $usuario->registrarPreEstagio(
            $id_aluno,
            $data_registro,
            $hora_registro,
            $momento,
            $descricao
        );

        if (!$registrar) {
            header("location: ../views/registros/registro_pre_estagio.php?erro=registro");
            exit();
        }

        header("location: ../views/registros/registro_pre_estagio.php?sucesso=1");
        exit();

        break;

    default:

        header("location: ../views/dashboard.php");
        exit();

        break;
}
