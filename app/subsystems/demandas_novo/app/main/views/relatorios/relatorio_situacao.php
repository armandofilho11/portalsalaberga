<?php

require_once(__DIR__ . '/../../../libs/FPDF/fpdf.php');
require_once(__DIR__ . '/../../models/demanda_model.php');
require_once(__DIR__ . '/../../config/connect.php');
require_once(__DIR__ . '/../../models/autorizacao.php');
require_once(__DIR__ . '/_layout_pdf.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_usuario'])) { http_response_code(401); exit('Não autenticado'); }

// ===================================
// OBTER SITUAÇÃO DOS PARÂMETROS
// ===================================

$situacao = isset($_GET['situacao']) ? $_GET['situacao'] : '';

if (empty($situacao)) {
    die('Situação não especificada');
}

// Mapear situação para status no banco
$status_map = [
    'aberta' => 'aberta',
    'em_andamento' => 'em_andamento',
    'finalizada' => 'finalizada'
];

$status = isset($status_map[$situacao]) ? $status_map[$situacao] : $situacao;
$nomesArquivoSituacao = [
    'aberta' => 'abertas',
    'em_andamento' => 'em_andamento',
    'finalizada' => 'finalizadas'
];
$nomeArquivo = 'relatorio_demandas_' . ($nomesArquivoSituacao[$situacao] ?? 'por_situacao') . '.pdf';

// ===================================
// BUSCAR DADOS
// ===================================

try {
    $conn = (new connect())->getConnection();
    $idProjeto = isset($_GET['id_projeto']) && $_GET['id_projeto'] !== '' ? (int) $_GET['id_projeto'] : null;
    $whereProjeto = '';
    $params = [':status' => $status];
    if ($idProjeto !== null) {
        $projetos = (new autorizacao())->listarProjetosDoUsuario((int) $_SESSION['id_usuario']);
        if (!in_array($idProjeto, array_map('intval', array_column($projetos, 'id')), true)) { $idProjeto = -1; }
        $whereProjeto = ' AND d.id_projeto = :id_projeto';
        $params[':id_projeto'] = $idProjeto;
    }
    
    $stmt = $conn->prepare("
        SELECT d.*, u.nome AS orientador_nome
        FROM demandas d
        LEFT JOIN usuarios u ON u.id = d.orientador
        WHERE d.status = :status$whereProjeto
        ORDER BY d.categoria, d.prioridade DESC, d.data_criacao DESC
    ");
    
    $stmt->execute($params);
    $todasDemandas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    die('Erro ao buscar demandas: ' . $e->getMessage());
}

if (empty($todasDemandas)) {
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 10);
    aplicarCabecalhoRelatorio($pdf, 'RELATÓRIO DE DEMANDAS POR SITUAÇÃO');
    $pdf->Ln(20);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, txt('Nenhuma demanda encontrada para esta situação.'), 0, 1, 'C');
    $pdf->Output($nomeArquivo, 'I');
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
// MAPEAMENTO DE SITUAÇÕES
// ===================================

$situacao_label = [
    'aberta' => 'Em Aberto',
    'em_andamento' => 'Em Andamento',
    'finalizada' => 'Finalizada'
];

// ===================================
// PDF RETRATO
// ===================================

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 10);
aplicarCabecalhoRelatorio($pdf, 'RELATORIO DE DEMANDAS - ' . strtoupper($situacao_label[$situacao] ?? $situacao));

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

$pdf->Output($nomeArquivo, 'I');
