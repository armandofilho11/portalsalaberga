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
require_once "../models/Aluno.php"; 

$usuarioModel = new Usuario();
$alunoModel = new Aluno();

// Proteção de rota: exige login para qualquer alteração de registros
if (!$usuarioModel->verificarLogin()) {
    header("location: ../index.php?erro=login");
    exit();
}

switch ($action) {

    // ==========================================
    // AÇÕES DE EDIÇÃO DE REGISTROS
    // ==========================================

    case 'editar_entrada':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("location: ../views/dashboard.php");
            exit();
        }

        $id           = intval($_POST['id'] ?? 0);
        $id_aluno     = intval($_POST['id_aluno'] ?? 0);
        $data_entrada = trim($_POST['data_entrada'] ?? '');
        $hora_entrada = trim($_POST['hora_entrada'] ?? '');
        $motivo       = trim($_POST['motivo'] ?? '');
        $descricao    = $_POST['descricao'] ?? '';

        if ($id <= 0 || $id_aluno <= 0 || empty($data_entrada) || empty($hora_entrada) || empty($motivo)) {
            header("location: ../views/registros/editar_entrada.php?id={$id}&erro=campos");
            exit();
        }

        $editar = $alunoModel->editarEntrada($id, $id_aluno, $data_entrada, $hora_entrada, $motivo, $descricao);

        if (!$editar) {
            header("location: ../views/registros/editar_entrada.php?id={$id}&erro=registro");
            exit();
        }

        // Retorna para o relatório específico de entradas
        header("location: ../views/relatorios/relatorio_de_entrada.php?sucesso=editado");
        exit();
        break;


    case 'editar_saida':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("location: ../views/dashboard.php");
            exit();
        }

        $id               = intval($_POST['id'] ?? 0);
        $id_aluno         = intval($_POST['id_aluno'] ?? 0);
        $tipo_responsavel = trim($_POST['tipo_responsavel'] ?? '');
        $nome_responsavel = trim($_POST['nome_responsavel'] ?? '');
        $motivo           = trim($_POST['motivo'] ?? '');
        $data_saida       = trim($_POST['data_saida'] ?? '');
        $hora_saida       = trim($_POST['hora_saida'] ?? '');
        $tipo_conducente  = trim($_POST['tipo_conducente'] ?? '');
        $descricao        = $_POST['descricao'] ?? '';

        if (
            $id <= 0 || $id_aluno <= 0 || empty($tipo_responsavel) || 
            empty($nome_responsavel) || empty($motivo) || empty($data_saida) || 
            empty($hora_saida) || empty($tipo_conducente)
        ) {
            header("location: ../views/registros/editar_saida.php?id={$id}&erro=campos");
            exit();
        }

        $editar = $alunoModel->editarSaida(
            $id, 
            $id_aluno, 
            $tipo_responsavel, 
            $nome_responsavel, 
            $motivo, 
            $data_saida, 
            $hora_saida, 
            $tipo_conducente, 
            $descricao
        );

        if (!$editar) {
            header("location: ../views/registros/editar_saida.php?id={$id}&erro=registro");
            exit();
        }

        // Retorna para o relatório específico de saídas normais
        header("location: ../views/relatorios/relatorio_de_saida.php?sucesso=editado");
        exit();
        break;


    case 'editar_saida_estagio':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("location: ../views/dashboard.php");
            exit();
        }

        $id         = intval($_POST['id'] ?? 0);
        $id_aluno   = intval($_POST['id_aluno'] ?? 0);
        $data_saida = trim($_POST['data_saida'] ?? '');
        $hora_saida = trim($_POST['hora_saida'] ?? '');

        if ($id <= 0 || $id_aluno <= 0 || empty($data_saida) || empty($hora_saida)) {
            header("location: ../views/registros/editar_saida_estagio.php?id={$id}&erro=campos");
            exit();
        }

        $editar = $alunoModel->editarSaidaEstagio($id, $id_aluno, $data_saida, $hora_saida);

        if (!$editar) {
            header("location: ../views/registros/editar_saida_estagio.php?id={$id}&erro=registro");
            exit();
        }

        // Retorna para o relatório específico de estágio
        header("location: ../views/relatorios/relatorio_diario_estagio.php?sucesso=editado");
        exit();
        break;

    // ==========================================
    // AÇÃO DE APAGAR REGISTROS (DINÂMICO)
    // ==========================================

    case 'apagar_registro':
        $id     = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        $tabela = trim($_POST['tabela'] ?? $_GET['tabela'] ?? '');
        
        // Novo parâmetro recebido da View para saber para onde voltar
        $origem = trim($_POST['origem'] ?? $_GET['origem'] ?? '');

        // Mapeamento de segurança para definir a página de retorno padrão com base na tabela
        if (empty($origem)) {
            if ($tabela === 'registro_entrada') {
                $origem = 'relatorio_de_entrada.php';
            } elseif ($tabela === 'registro_saida_estagio') {
                $origem = 'relatorio_diario_estagio.php';
            } elseif ($tabela === 'registro_pre_estagio') {
                $origem = 'relatorio_pre_estagio.php';
            } else {
                $origem = 'relatorio_de_saida.php'; // Padrão para saídas normais ou últimas saídas
            }
        }

        if ($id <= 0 || empty($tabela)) {
            header("location: ../views/relatorios/{$origem}?erro=dados_invalidos");
            exit();
        }

        // Executa a deleção no banco de dados
        $apagou = $alunoModel->apagarRegistro($tabela, $id);

        if (!$apagou) {
            header("location: ../views/relatorios/{$origem}?erro=apagar");
            exit();
        }

        header("location: ../views/relatorios/{$origem}?sucesso=apagado");
        exit();
        break;

    default:
        header("location: ../views/dashboard.php");
        exit();
        break;
}