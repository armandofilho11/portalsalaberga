<?php
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once __DIR__ . '/../../../assets/lib/fpdf/BasePDF.php';
require_once __DIR__ . '/../../../models/Usuario.php';

class RelatorioEntradasPDF extends BasePDF
{
    private $usuarioModel;

    // Paleta de cores alinhada com a identidade visual do projeto
    protected $colors = [
        'primary'         => [0, 122, 51],      // Verde Principal
        'secondary'       => [255, 165, 0],    // Laranja / Amarelo Destaque
        'light_green'     => [240, 249, 244],  // Verde Claro (Cabeçalho da tabela)
        'dark'            => [55, 65, 81],     // Texto Escuro
        'gray_border'     => [229, 231, 235],  // Bordas Suaves
        'light_gray_row'  => [245, 245, 245]   // Linhas Alternadas (Zebra)
    ];

    public function __construct()
    {
        // Unidade em 'pt' para alinhamento e precisão idênticos ao modelo
        parent::__construct('P', 'pt', 'A4');
        $this->SetMargins(20, 20, 20);
        $this->usuarioModel = new Usuario();
    }

    public function Header()
    {
        $w = $this->GetPageWidth();

        // Faixa do Topo (Verde Principal)
        $this->SetFillColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Rect(0, 0, $w, 60, 'F');

        // Logo da Instituição / Sistema (se existir)
        $logo = __DIR__ . '/../../../assets/img/logo.png';
        if (file_exists($logo)) {
            $this->Image($logo, 18, 10, 40, 40);
        }

        // Título em Destaque (Laranja/Secundário)
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor($this->colors['secondary'][0], $this->colors['secondary'][1], $this->colors['secondary'][2]);
        $this->Cell(0, 20, utf8_decode('Relatório de Entradas'), 0, 1, 'C');

        // Subtítulo em Branco
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 18, utf8_decode('Sistema de Entrada e Saída Escolar'), 0, 1, 'C');
        $this->Ln(15);
    }

    public function Footer()
    {
        $this->SetY(-30);
        $this->SetDrawColor($this->colors['secondary'][0], $this->colors['secondary'][1], $this->colors['secondary'][2]);
        $this->SetLineWidth(0.5);
        $this->Line(20, $this->GetY(), $this->GetPageWidth() - 20, $this->GetY());
        $this->Ln(5);
        
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('Gerado em: ' . date('d/m/Y H:i:s')), 0, 0, 'R');
    }

    public function generateReport()
    {
        $this->AliasNbPages();
        $this->AddPage();

        $entradas = $this->usuarioModel->listarEntradasComDetalhes();

        if (empty($entradas)) {
            $this->SetFont('Arial', 'I', 10);
            $this->SetTextColor($this->colors['dark'][0], $this->colors['dark'][1], $this->colors['dark'][2]);
            $this->Cell(0, 20, utf8_decode('Nenhum registro de entrada encontrado.'), 0, 1, 'L');
            return;
        }

        // Configuração de largura total da página útil
        $pageWidth = $this->GetPageWidth() - 40; // 20pt de margem em cada lado
        
        // Distribuição proporcional das colunas
        $colAluno  = $pageWidth * 0.32; // 32%
        $colTurma  = $pageWidth * 0.20; // 20%
        $colData   = $pageWidth * 0.15; // 15%
        $colHora   = $pageWidth * 0.13; // 13%
        $colMotivo = $pageWidth * 0.20; // 20%

        // Cabeçalho da Tabela
        $this->SetDrawColor($this->colors['gray_border'][0], $this->colors['gray_border'][1], $this->colors['gray_border'][2]);
        $this->SetLineWidth(0.2);
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor($this->colors['light_green'][0], $this->colors['light_green'][1], $this->colors['light_green'][2]);
        $this->SetTextColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);

        $this->Cell($colAluno, 14, utf8_decode('Aluno'), 1, 0, 'L', true);
        $this->Cell($colTurma, 14, utf8_decode('Turma / Série'), 1, 0, 'L', true);
        $this->Cell($colData, 14, utf8_decode('Data'), 1, 0, 'C', true);
        $this->Cell($colHora, 14, utf8_decode('Hora'), 1, 0, 'C', true);
        $this->Cell($colMotivo, 14, utf8_decode('Motivo'), 1, 1, 'L', true);

        // Corpo da Tabela
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor($this->colors['dark'][0], $this->colors['dark'][1], $this->colors['dark'][2]);

        $i = 0;
        foreach ($entradas as $row) {
            // Efeito Zebra (Alternância de linhas)
            $fill = ($i % 2 == 0) ? 255 : $this->colors['light_gray_row'][0];
            $this->SetFillColor($fill, $fill, $fill);

            // Tratamento e formatação de dados
            $aluno  = utf8_decode(strtoupper($row['aluno'] ?? '---'));
            $turma  = utf8_decode(strtoupper(($row['serie'] ?? '') . ' / ' . ($row['turma'] ?? '')));
            $data   = !empty($row['data_entrada']) ? date('d/m/Y', strtotime($row['data_entrada'])) : '--/--/----';
            $hora   = !empty($row['hora_entrada']) ? date('H:i', strtotime($row['hora_entrada'])) : '--:--';
            $motivo = utf8_decode(ucfirst($row['motivo'] ?? 'N/A'));

            $this->Cell($colAluno, 12, $aluno, 1, 0, 'L', true);
            $this->Cell($colTurma, 12, $turma, 1, 0, 'L', true);
            $this->Cell($colData, 12, $data, 1, 0, 'C', true);
            $this->Cell($colHora, 12, $hora, 1, 0, 'C', true);
            $this->Cell($colMotivo, 12, $motivo, 1, 1, 'L', true);

            $i++;
        }
    }
}

// Instanciação e Execução do Relatório
$pdf = new RelatorioEntradasPDF();
$pdf->generateReport();
$pdf->Output('Relatorio_de_Entradas.pdf', 'I');
?>