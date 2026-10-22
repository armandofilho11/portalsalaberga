<?php
require_once __DIR__ . '/../../../app/main/assets/fpdf/fpdf.php';

if (!isset($_POST['json'])) {
    die('Dados não recebidos.');
}
$data = json_decode($_POST['json'], true);
$barracas = $data['barracas'] ?? [];
$produtos = $data['produtos'] ?? [];
$vendas = $data['vendas'] ?? [];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Relatório de Prestação de Contas - Empreendedorismo'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(4);

foreach ($barracas as $barraca) {
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(0, 8, utf8_decode('Barraca: ' . $barraca['nome'] . ' (' . $barraca['curso'] . ')'), 0, 1);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 7, utf8_decode('Responsáveis: ' . $barraca['responsaveis']), 0, 1);
    $pdf->Cell(0, 7, utf8_decode('Data do relatório: ' . date('d/m/Y H:i')), 0, 1);
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, utf8_decode('Produtos Declarados:'), 0, 1);
    $pdf->SetFont('Arial', '', 11);
    foreach ($produtos as $produto) {
        if ($produto['barracaId'] != $barraca['id']) continue;
        $totalVendido = $produto['quantidadeVendida'] * $produto['preco'];
        $estoque = $produto['quantidadeInicial'] - $produto['quantidadeVendida'];
        $pdf->Cell(0, 6, utf8_decode('Produto: ' . $produto['nome']), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Preço unitário: R$ ' . number_format($produto['preco'], 2, ',', '.')), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Quantidade inicial: ' . $produto['quantidadeInicial']), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Quantidade vendida: ' . $produto['quantidadeVendida']), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Estoque restante: ' . $estoque), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('Total arrecadado: R$ ' . number_format($totalVendido, 2, ',', '.')), 0, 1);
        $pdf->Ln(1);
    }
    $vendasBarraca = array_filter($vendas, function($v) use ($barraca) { return $v['barracaId'] == $barraca['id']; });
    $totalArrecadado = array_reduce($vendasBarraca, function($sum, $v) { return $sum + $v['valorTotal']; }, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, utf8_decode('Total Geral Arrecadado: R$ ' . number_format($totalArrecadado, 2, ',', '.')), 0, 1);
    $pdf->Cell(0, 7, utf8_decode('Total de Vendas Realizadas: ' . count($vendasBarraca)), 0, 1);
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, utf8_decode('Comprovantes de Vendas:'), 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $i = 1;
    foreach ($vendasBarraca as $venda) {
        $pdf->Cell(0, 6, utf8_decode('Venda ' . $i++ . ':'), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('  Data/Hora: ' . date('d/m/Y H:i', strtotime($venda['timestamp']))), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('  Produto: ' . $venda['nomeProduto']), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('  Quantidade: ' . $venda['quantidade']), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('  Preço unitário: R$ ' . number_format($venda['precoUnitario'], 2, ',', '.')), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('  Valor total: R$ ' . number_format($venda['valorTotal'], 2, ',', '.')), 0, 1);
        $pdf->Cell(0, 6, utf8_decode('  ID da venda: ' . $venda['id']), 0, 1);
        $pdf->Ln(1);
    }
    $pdf->Ln(3);
    $pdf->Cell(0, 0, '', 'T');
    $pdf->Ln(2);
}
$pdf->Output('I', 'prestacao_contas_empreendedorismo.pdf'); 