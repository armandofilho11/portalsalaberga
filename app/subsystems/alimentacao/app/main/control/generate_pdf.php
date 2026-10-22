<?php
require('../assets/FPDF/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(0, 0, 0); // Preto para o texto do cabeçalho
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Relatório de Cardápios'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 0, 0); // Preto para o texto do rodapé
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function MenuTable($menus) {
        // Definindo cor verde para bordas (#00FF00)
        $this->SetDrawColor(0, 255, 0);
        // Definindo cor laranja para fundo (#FFA500)
        $this->SetFillColor(255, 165, 0);
        // Definindo cor preta para texto
        $this->SetTextColor(0, 0, 0);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(30, 10, iconv('UTF-8', 'windows-1252', 'Data'), 1, 0, 'C', true);
        $this->Cell(40, 10, iconv('UTF-8', 'windows-1252', 'Tipo'), 1, 0, 'C', true);
        $this->Cell(50, 10, iconv('UTF-8', 'windows-1252', 'Nome'), 1, 0, 'C', true);
        $this->Cell(70, 10, iconv('UTF-8', 'windows-1252', 'Descrição'), 1, 1, 'C', true);

        $this->SetFont('Arial', '', 10);
        foreach ($menus as $menu) {
            $data = iconv('UTF-8', 'windows-1252//TRANSLIT', $menu['data']);
            $tipo = iconv('UTF-8', 'windows-1252//TRANSLIT', $menu['tipo']);
            $nome = iconv('UTF-8', 'windows-1252//TRANSLIT', $this->shorten($menu['nome'], 25));
            $descricao = iconv('UTF-8', 'windows-1252//TRANSLIT', $this->shorten($menu['descricao'], 35));

            $this->Cell(30, 10, $data, 1, 0, 'C', true);
            $this->Cell(40, 10, $tipo, 1, 0, 'C', true);
            $this->Cell(50, 10, $nome, 1, 0, 'C', true);
            $this->Cell(70, 10, $descricao, 1, 1, 'C', true);
        }
    }

    function shorten($text, $max) {
        if (strlen($text) > $max) {
            return substr($text, 0, $max - 3) . '...';
        }
        return $text;
    }
}

header('Content-Type: application/json');

// Habilitar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Erro ao decodificar JSON: ' . json_last_error_msg()]);
    exit;
}

$menus = isset($input['menus']) ? $input['menus'] : [];

if (empty($menus)) {
    echo json_encode(['success' => false, 'message' => 'Nenhum dado de cardápio recebido.']);
    exit;
}

// Verificando se a extensão iconv está disponível
if (!function_exists('iconv')) {
    echo json_encode(['success' => false, 'message' => 'A extensão iconv não está disponível. Instale-a para suportar acentos corretamente.']);
    exit;
}

try {
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->MenuTable($menus);
    $pdf->Output('F', 'cardapio_relatorio.pdf');

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="cardapio_relatorio.pdf"');
    readfile('cardapio_relatorio.pdf');
    unlink('cardapio_relatorio.pdf');
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao gerar PDF: ' . $e->getMessage()]);
    exit;
}
?>