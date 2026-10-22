<?php
require_once __DIR__ . '/fpdf.php';

class BasePDF extends FPDF
{
    protected $colors = [
        'primary' => [0, 122, 51],
        'secondary' => [255, 165, 0],
        'light_green' => [240, 249, 244],
        'dark' => [55, 65, 81],
        'gray_border' => [229, 231, 235],
        'light_gray_row' => [245, 245, 245],
        'turma_3a' => [220, 53, 69],
        'turma_3b' => [65, 105, 225],
        'turma_3c' => [13, 202, 240],
        'turma_3d' => [108, 117, 125],
        'ausentes' => [128, 128, 128]
    ];

    protected $logoPath;

    public function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->SetMargins(20, 20, 20);
        $this->logoPath = dirname(__DIR__, 3) . '/img/logo.png';
    }

    public function setLogoPath($path)
    {
        $this->logoPath = $path;
    }

    protected function encodeText($text)
    {
        if ($text === null) {
            return '';
        }

        if (!is_string($text)) {
            return (string) $text;
        }

        $isUtf8 = false;
        if (function_exists('mb_check_encoding')) {
            $isUtf8 = mb_check_encoding($text, 'UTF-8');
        } else {
            $isUtf8 = (bool) @preg_match('//u', $text);
        }

        if ($isUtf8 && function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'CP1252//TRANSLIT', $text);
            if ($converted !== false) {
                return $converted;
            }

            $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
            if ($converted !== false) {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('ISO-8859-1', 'CP1252//TRANSLIT', $text);
            if ($converted !== false) {
                return $converted;
            }
        }

        return $text;
    }

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($w, $h, $this->encodeText($txt), $border, $ln, $align, $fill, $link);
    }

    public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::MultiCell($w, $h, $this->encodeText($txt), $border, $align, $fill);
    }

    public function Write($h, $txt, $link = '')
    {
        parent::Write($h, $this->encodeText($txt), $link);
    }

    public function Text($x, $y, $txt)
    {
        parent::Text($x, $y, $this->encodeText($txt));
    }

    public function Header()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $w = $this->GetPageWidth();
        $this->SetFillColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Rect(0, 0, $w, 60, 'F');

        if (file_exists($this->logoPath)) {
            $this->Image($this->logoPath, 18, 10, 40, 40);
        }

        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor($this->colors['secondary'][0], $this->colors['secondary'][1], $this->colors['secondary'][2]);
        $this->Cell(0, 20, utf8_decode('Frequência de Saída'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 18, utf8_decode('Estágio 2025'), 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-30);
        $this->SetDrawColor($this->colors['secondary'][0], $this->colors['secondary'][1], $this->colors['secondary'][2]);
        $this->SetLineWidth(0.5);
        $this->Line(40, $this->GetY(), $this->GetPageWidth() - 40, $this->GetY());
        $this->Ln(5);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('Gerado em: ' . date('d/m/Y H:i:s')), 0, 0, 'R');
    }

    // Helper: imprime tabela simples com duas colunas (nome, info)
    public function printTwoColumnHeader($col1, $col2, $col1wRatio = 0.7)
    {
        $pageWidth = $this->GetPageWidth() - 40;
        $col1w = $pageWidth * $col1wRatio;
        $col2w = $pageWidth - $col1w;
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor($this->colors['light_green'][0], $this->colors['light_green'][1], $this->colors['light_green'][2]);
        $this->Cell($col1w, 12, utf8_decode($col1), 1, 0, 'L', true);
        $this->Cell($col2w, 12, utf8_decode($col2), 1, 1, 'L', true);
    }
}
