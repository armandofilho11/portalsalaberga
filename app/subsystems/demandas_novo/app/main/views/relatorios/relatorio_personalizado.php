<?php

require_once(__DIR__ . '/../../../libs/FPDF/fpdf.php');
require_once(__DIR__ . '/../../models/demanda_model.php');
require_once(__DIR__ . '/../../config/connect.php');
require_once(__DIR__ . '/../../models/autorizacao.php');
require_once(__DIR__ . '/_layout_pdf.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_usuario'])) { http_response_code(401); exit('Não autenticado'); }

// ===================================
// RECEBER FILTROS
// ===================================

$filtros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtros = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    // Suportar também via GET
    $filtros['situacao'] = isset($_GET['situacao']) ? explode(',', $_GET['situacao']) : [];
    $filtros['prioridade'] = isset($_GET['prioridade']) ? explode(',', $_GET['prioridade']) : [];
    $filtros['categoria'] = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    $filtros['projeto'] = isset($_GET['projeto']) ? explode(',', $_GET['projeto']) : [];
    $filtros['data_inicio'] = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
    $filtros['data_fim'] = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
}

// ===================================
// BUSCAR DADOS
// ===================================

try {
    $conn = (new connect())->getConnection();
    
    $where_conditions = [];
    $params = [];
    $autorizacao = new autorizacao();
    $projetosPermitidos = array_map('intval', array_column($autorizacao->listarProjetosDoUsuario((int) $_SESSION['id_usuario']), 'id'));
    $ehMaster = $autorizacao->ehAdministradorMaster((int) $_SESSION['id_usuario']);

    if (!$ehMaster) {
        if (!$projetosPermitidos) {
            $where_conditions[] = '1 = 0';
        } else {
            $placeholders = [];
            foreach ($projetosPermitidos as $index => $idProjetoPermitido) {
                $placeholder = ':projeto_permitido_' . $index;
                $placeholders[] = $placeholder;
                $params[$placeholder] = $idProjetoPermitido;
            }
            $where_conditions[] = 'd.id_projeto IN (' . implode(',', $placeholders) . ')';
        }
    }

    if (isset($_GET['id_projeto']) && $_GET['id_projeto'] !== '') {
        $idProjeto = (int) $_GET['id_projeto'];
        $projetos = (new autorizacao())->listarProjetosDoUsuario((int) $_SESSION['id_usuario']);
        if (!in_array($idProjeto, array_map('intval', array_column($projetos, 'id')), true)) {
            $idProjeto = -1;
        }
        $where_conditions[] = 'd.id_projeto = :id_projeto_global';
        $params[':id_projeto_global'] = $idProjeto;
    }
    
    // Filtro: Situação/Status
    if (!empty($filtros['situacao']) && is_array($filtros['situacao'])) {
        $placeholders = [];
        foreach ($filtros['situacao'] as $index => $sit) {
            $placeholder = ':situacao_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $sit;
        }
        $where_conditions[] = 'd.status IN (' . implode(',', $placeholders) . ')';
    }
    
    // Filtro: Prioridade
    if (!empty($filtros['prioridade']) && is_array($filtros['prioridade'])) {
        $placeholders = [];
        foreach ($filtros['prioridade'] as $index => $prio) {
            $placeholder = ':prioridade_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $prio;
        }
        $where_conditions[] = 'd.prioridade IN (' . implode(',', $placeholders) . ')';
    }
    
    // Filtro: Categoria
    if (!empty($filtros['categoria'])) {
        $where_conditions[] = 'd.categoria = :categoria';
        $params[':categoria'] = $filtros['categoria'];
    }
    
    // Filtro: Projeto
    $projetosFiltrados = $filtros['projeto'] ?? [];
    if (!is_array($projetosFiltrados)) {
        $projetosFiltrados = [$projetosFiltrados];
    }
    $projetosFiltrados = array_values(array_filter(array_map('intval', $projetosFiltrados), static fn (int $id): bool => $id > 0));
    if ($projetosFiltrados) {
        $placeholders = [];
        foreach ($projetosFiltrados as $index => $proj) {
            $placeholder = ':projeto_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $proj;
        }
        $where_conditions[] = 'd.id_projeto IN (' . implode(',', $placeholders) . ')';
    }
    
    // Filtro: Data de início
    if (!empty($filtros['data_inicio'])) {
        $where_conditions[] = 'DATE(d.data_criacao) >= :data_inicio';
        $params[':data_inicio'] = $filtros['data_inicio'];
    }
    
    // Filtro: Data de fim
    if (!empty($filtros['data_fim'])) {
        $where_conditions[] = 'DATE(d.data_criacao) <= :data_fim';
        $params[':data_fim'] = $filtros['data_fim'];
    }
    
    // Construir query
    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
    
    $sql = "
        SELECT d.*, u.nome AS orientador_nome
        FROM demandas d
        LEFT JOIN usuarios u ON u.id = d.orientador
        $where_clause
        ORDER BY d.categoria, d.prioridade DESC, d.data_criacao DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $todasDemandas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    die('Erro ao buscar demandas: ' . $e->getMessage());
}

if (empty($todasDemandas)) {
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 10);
    aplicarCabecalhoRelatorio($pdf, 'RELATÓRIO PERSONALIZADO DE DEMANDAS');
    $pdf->Ln(20);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, txt('Nenhuma demanda encontrada com os filtros selecionados.'), 0, 1, 'C');
    $pdf->Output('relatorio_personalizado_demandas.pdf', 'I');
    exit;
}

// ===================================
// AGRUPAR POR CATEGORIA
// ===================================

$agrupado = [];

foreach ($todasDemandas as $d) {
    $agrupado[$d['categoria']][] = $d;
}

// ===================================
// FUNÇÃO UTF-8
// ===================================

function txt($text)
{
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
}

// ===================================
// PDF RETRATO
// ===================================

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 10);
aplicarCabecalhoRelatorio($pdf, 'RELATÓRIO PERSONALIZADO DE DEMANDAS');

// ===================================
// TAMANHO DAS COLUNAS
// ===================================

$w_id = 10;
$w_titulo = 60;
$w_prioridade = 25;
$w_status = 25;
$w_prazo = 28;
$w_orientador = 50;

// ===================================
// FUNÇÃO TÍTULO CATEGORIA
// ===================================

function tituloCategoria($pdf, $categoria)
{
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(
        0,
        8,
        txt(strtoupper(str_replace('_', ' ', $categoria))),
        0,
        1
    );
    $pdf->Ln(1);
}

// ===================================
// FUNÇÃO CABEÇALHO
// ===================================

function cabecalhoTabela(
    $pdf,
    $w_id,
    $w_titulo,
    $w_prioridade,
    $w_status,
    $w_prazo,
    $w_orientador
) {
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetFillColor(34, 139, 34);
    $pdf->SetTextColor(255, 255, 255);

    $pdf->Cell($w_id, 8, 'ID', 1, 0, 'C', true);
    $pdf->Cell($w_titulo, 8, 'Titulo', 1, 0, 'C', true);
    $pdf->Cell($w_prioridade, 8, 'Prioridade', 1, 0, 'C', true);
    $pdf->Cell($w_status, 8, 'Status', 1, 0, 'C', true);
    $pdf->Cell($w_prazo, 8, 'Prazo', 1, 0, 'C', true);
    $pdf->Cell($w_orientador, 8, 'Orientador', 1, 1, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 8);
}

// ===================================
// FUNÇÃO LINHA
// ===================================

function linhaTabela(
    $pdf,
    $d,
    $w_id,
    $w_titulo,
    $w_prioridade,
    $w_status,
    $w_prazo,
    $w_orientador
) {
    $titulo = txt($d['titulo']);
    $prioridade = txt($d['prioridade']);
    $status = txt($d['status']);
    $orientador = txt($d['orientador_nome'] ?? 'N/A');
    $prazo = !empty($d['prazo'])
        ? date('d/m/Y', strtotime($d['prazo']))
        : '-';

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $h = 6;

    $pdf->Cell(
        $w_id,
        $h,
        $d['id'],
        1,
        0,
        'C'
    );

    $pdf->SetXY(
        $x + $w_id,
        $y
    );

    $pdf->MultiCell(
        $w_titulo,
        $h,
        $titulo,
        1
    );

    $y2 = $pdf->GetY();

    $pdf->SetXY(
        $x + $w_id + $w_titulo,
        $y
    );

    $pdf->Cell(
        $w_prioridade,
        $h,
        $prioridade,
        1,
        0,
        'C'
    );

    $pdf->Cell(
        $w_status,
        $h,
        $status,
        1,
        0,
        'C'
    );

    $pdf->Cell(
        $w_prazo,
        $h,
        $prazo,
        1,
        0,
        'C'
    );

    $pdf->Cell(
        $w_orientador,
        $h,
        $orientador,
        1,
        0,
        'L'
    );

    $pdf->SetY(
        max($y2, $y + $h)
    );
}

// ===================================
// GERAR RELATÓRIO
// ===================================

foreach ($agrupado as $categoria => $lista) {

    tituloCategoria($pdf, $categoria);

    cabecalhoTabela(
        $pdf,
        $w_id,
        $w_titulo,
        $w_prioridade,
        $w_status,
        $w_prazo,
        $w_orientador
    );

    foreach ($lista as $d) {

        linhaTabela(
            $pdf,
            $d,
            $w_id,
            $w_titulo,
            $w_prioridade,
            $w_status,
            $w_prazo,
            $w_orientador
        );
    }

    $pdf->Ln(5);
}

// ===================================
// RODAPÉ
// ===================================

$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(
    0,
    5,
    txt('Documento gerado pelo sistema de demandas'),
    0,
    1,
    'C'
);

// ===================================
// SAÍDA
// ===================================

$pdf->Output('relatorio_personalizado_demandas.pdf', 'I');
