<?php
require_once __DIR__ . '/../../../assets/lib/fpdf/BasePDF.php';
require_once __DIR__ . '/../../../models/Aluno.php';
require_once __DIR__ . '/../../../config/Database.php';

class AutorizacaoEntradaPDF extends BasePDF
{
    protected $colors = [
        'primary'     => [0, 122, 51],      // Verde Principal
        'secondary'   => [255, 165, 0],    // Laranja / Amarelo Destaque
        'dark'        => [55, 65, 81],     // Texto Escuro
        'gray_border' => [229, 231, 235]  // Bordas Suaves
    ];

    public function __construct()
    {
        parent::__construct('P', 'pt', 'A4');
        $this->SetMargins(30, 30, 30);
        $this->SetAutoPageBreak(true, 40);
    }

    /**
     * Tratamento para acentuação e caracteres UTF-8
     */
    private function txt($string)
    {
        if (function_exists('iconv')) {
            return iconv('UTF-8', 'windows-1252//TRANSLIT', $string);
        }
        return utf8_decode($string);
    }

    public function Header()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $w = $this->GetPageWidth();

        // Faixa do Topo
        $this->SetFillColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Rect(0, 0, $w, 65, 'F');

        // Logo
        $logo = __DIR__ . '/../../../assets/img/logo.png';
        if (file_exists($logo)) {
            $this->Image($logo, 20, 12, 40, 40);
        }

        // Título do Cabeçalho
        $this->SetY(15);
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor($this->colors['secondary'][0], $this->colors['secondary'][1], $this->colors['secondary'][2]);
        $this->Cell(0, 20, $this->txt('Comprovante de Autorização'), 0, 1, 'C');

        // Subtítulo
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 16, $this->txt('Controle de Acesso Escolar'), 0, 1, 'C');

        $this->SetY(85);
    }

    public function Footer()
    {
        $this->SetY(-35);
        $this->SetDrawColor($this->colors['secondary'][0], $this->colors['secondary'][1], $this->colors['secondary'][2]);
        $this->SetLineWidth(0.5);
        $this->Line(30, $this->GetY(), $this->GetPageWidth() - 30, $this->GetY());
        $this->Ln(6);

        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Cell(0, 10, $this->txt('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 10, $this->txt('Gerado em: ' . date('d/m/Y H:i:s')), 0, 0, 'R');
    }

    public function gerarAutorizacao($aluno, $turma, $data, $hora, $motivo = 'Entrada fora do horário regular')
    {
        $this->AliasNbPages();
        $this->AddPage();

        // Título Interno
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Cell(0, 20, $this->txt('DECLARAÇÃO DE AUTORIZAÇÃO DE ENTRADA'), 0, 1, 'C');
        $this->Ln(15);

        // Montagem do Texto com Variáveis
        $textoDeclaracao = "Eu, responsável pela autorização, declaro que o aluno {$aluno}, pertencente à turma {$turma}, está autorizado a entrar na sala na data {$data} às {$hora}. Esta autorização foi concedida com base no motivo descrito abaixo e serve para garantir a segurança e o controle interno do acesso escolar.";

        $this->SetFont('Arial', '', 11);
        $this->SetTextColor($this->colors['dark'][0], $this->colors['dark'][1], $this->colors['dark'][2]);
        
        // MultiCell com conversão e alinhamento justificado
        $this->MultiCell(0, 18, $this->txt($textoDeclaracao), 0, 'J');
        $this->Ln(20);

        // Bloco do Motivo
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(245, 245, 245);
        $this->SetDrawColor($this->colors['gray_border'][0], $this->colors['gray_border'][1], $this->colors['gray_border'][2]);
        
        $this->Cell(0, 20, $this->txt(' Motivo da Autorização:'), 1, 1, 'L', true);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 16, $this->txt($motivo), 1, 'L');
        $this->Ln(50);

        // Seção de Assinaturas
        $w = $this->GetPageWidth() - 60;
        $colW = $w / 2 - 10;

        $yAssinatura = $this->GetY();
        
        // Linha e Texto Assinatura 1
        $this->Line(30, $yAssinatura, 30 + $colW, $yAssinatura);
        $this->SetXY(30, $yAssinatura + 5);
        $this->SetFont('Arial', '', 9);
        $this->Cell($colW, 12, $this->txt('Assinatura do Responsável'), 0, 0, 'C');

        // Linha e Texto Assinatura 2
        $this->Line(30 + $colW + 20, $yAssinatura, 30 + (2 * $colW) + 20, $yAssinatura);
        $this->SetXY(30 + $colW + 20, $yAssinatura + 5);
        $this->Cell($colW, 12, $this->txt('Coordenação / Direção'), 0, 0, 'C');
    }
}

// Instanciação e Parâmetros
$aluno  = 'João Pedro Alves';
$turma  = '1 ano / C';
$data   = '30/07/2026';
$hora   = '13:22';
$motivo = 'Atraso decorrente de atendimento médico/externo.';

$pdf = new AutorizacaoEntradaPDF();
$pdf->gerarAutorizacao($aluno, $turma, $data, $hora, $motivo);
$pdf->Output('Autorizacao_Entrada.pdf', 'I');
?>