<?php
require_once('../../assets/fpdf/fpdf.php');

$turmas = [
    ["Enfermagem", "1º A", 230],
    ["Enfermagem", "2º A", 245],
    ["Enfermagem", "3º A", 235],
    ["Informática", "1º B", 230],
    ["Informática", "2º B", 230],
    ["Informática", "3º B", 245],
    ["Meio Ambiente", "2º C", 230],
    ["Administração", "1º C", 225],
    ["Administração", "3º C", 240],
    ["Edificações", "1º D", 230],
    ["Edificações", "2º D", 235],
    ["Edificações", "3º D", 250],
];

$pdf = new FPDF("P", "mm", "A4");
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

$numeroRifa = 1;

// Layout
$xInicial = 10;
$yInicial = 10;
$largura = 90;
$altura = 24;
$espacoX = 10;
$espacoY = 3;

$linhasPorPagina = 10;
$linAtual = 0;

// Função para gerar código de segurança
function gerarCodigoSeguranca($numero, $semente = "SECRETO123") {
    return strtoupper(substr(md5($semente . $numero), 0, 6));
}

foreach ($turmas as $turma) {
    list($curso, $turmaLabel, $quantidade) = $turma;

    for ($i = 0; $i < $quantidade; $i++) {
        // Calcula posição Y
        $y = $yInicial + $linAtual * ($altura + $espacoY);

        // Coluna esquerda - canhoto
        $xEsquerda = $xInicial;
        desenhaRifa1($pdf, $xEsquerda, $y, $largura, $altura, $numeroRifa, $curso, $turmaLabel);

        // Coluna direita - organizador
        $xDireita = $xInicial + $largura + $espacoX;
        desenhaRifa2($pdf, $xDireita, $y, $largura, $altura, $numeroRifa, $curso, $turmaLabel);

        $numeroRifa++;
        $linAtual++;

        if ($linAtual == $linhasPorPagina) {
            $linAtual = 0;
            $pdf->AddPage();
        }
    }

    // Quebra de página ao final de cada turma
    $linAtual = 0;
    $pdf->AddPage();
}

$pdf->Output("I", "rifas_duas_colunas_separadas.pdf");

// Função desenha o lado 1 da rifa (canhoto)
function desenhaRifa1($pdf, $x, $y, $largura, $altura, $numero, $curso, $turmaLabel)
{
    // Retângulo
    $pdf->Rect($x, $y, $largura, $altura);

    // Logo
    $logoLargura = 4;
    $logoAltura = 6;
    if (file_exists("logo.png")) {
        $pdf->Image("logo.png", $x + $largura - $logoLargura - 2, $y + 2, $logoLargura, $logoAltura);
    }

    // Número da rifa
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(0, 5, utf8_decode(sprintf("RIFA Nº %04d", $numero)), 0, 1);

    // Código de segurança (substituindo Turma / Curso)
    $pdf->SetFont("Arial", "", 9);
    $pdf->SetX($x + 2);
    $codigoSeguranca = gerarCodigoSeguranca($numero);
    $pdf->Cell(0, 5, utf8_decode("Código de Segurança: $codigoSeguranca - $turmaLabel - $curso"), 0, 1);

    // Espaços para preenchimento
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 6, "Nome: ...........................................................................", 0, 1);

    $pdf->SetX($x + 2);
    $pdf->Cell(0, 6, "Fone/Whats: .................................................................", 0, 1);

    // SESMATED no rodapé
    $pdf->SetXY($x + $largura - 43, $y + $altura - 5);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(0, -28, "SESMATED 2025");
}

// Função desenha o lado 2 da rifa (organizador)
function desenhaRifa2($pdf, $x, $y, $largura, $altura, $numero, $curso, $turmaLabel)
{
    // Retângulo
    $pdf->Rect($x, $y, $largura, $altura);

    // Logo
    $logoLargura = 4;
    $logoAltura = 6;
    if (file_exists("logo.png")) {
        $pdf->Image("logo.png", $x + $largura - $logoLargura - 2, $y + 2, $logoLargura, $logoAltura);
    }

    // Número da rifa
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(0, 5, utf8_decode(sprintf("RIFA Nº %04d", $numero)), 0, 1);

    // Código de segurança logo abaixo do número
    $pdf->SetFont("Arial", "", 10);
    $pdf->SetX($x + 2);
    $codigoSeguranca = gerarCodigoSeguranca($numero);
    $pdf->Cell(0, 5, utf8_decode(""), 0, 1);

    // Texto original turma / curso
    $pdf->SetFont("Arial", "", 10);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 5, utf8_decode("Cód. $codigoSeguranca"), 0, 1);

    // Espaços para preenchimento
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 5, utf8_decode("Turma / Curso: $turmaLabel - $curso"), 0, 1);

    // SESMATED no rodapé
    $pdf->SetXY($x + $largura - 43, $y + $altura - 5);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(0, -28, "SESMATED 2025");
}
?>
