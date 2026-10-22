<?php

function textoRelatorioPdf(string $texto): string
{
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

function aplicarCabecalhoRelatorio(FPDF $pdf, string $titulo, string $subtitulo = ''): void
{
    $pdf->SetFillColor(238, 243, 236);
    $pdf->Rect(0, 0, $pdf->GetPageWidth(), 38, 'F');

    $cabecalhoInstitucional = __DIR__ . '/../../assets/cabeçalho_relatorios.jpg';
    if (is_file($cabecalhoInstitucional)) {
        $pdf->Image($cabecalhoInstitucional, 10, 5, 135, 0, 'JPG');
    }

    $logoAreaDev = __DIR__ . '/../../assets/logo_area_dev.png';
    if (is_file($logoAreaDev)) {
        $pdf->Image($logoAreaDev, 161, 8, 30, 0, 'PNG');
    }

    // $pdf->SetDrawColor(0, 122, 51);
    // $pdf->SetLineWidth(0.5);
    // $pdf->Line(10, 38, 200, 38);

    $pdf->SetTextColor(0, 122, 51);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->SetXY(10, 44);
    $pdf->Cell(190, 7, textoRelatorioPdf($titulo), 0, 1, 'L');

    if ($subtitulo !== '') {
        $pdf->SetTextColor(90, 90, 90);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 51);
        $pdf->Cell(190, 4, textoRelatorioPdf($subtitulo), 0, 1, 'L');
    }

    $pdf->SetTextColor(100, 100, 100);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->SetXY(10, $subtitulo !== '' ? 56 : 51);
    $agora = new DateTimeImmutable('now', new DateTimeZone('America/Fortaleza'));
    $pdf->Cell(190, 4, textoRelatorioPdf('Gerado em: ' . $agora->format('d/m/Y H:i')), 0, 1, 'L');
    $pdf->SetY($subtitulo !== '' ? 65 : 60);
}
