<?php
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once __DIR__ . '/../../../assets/lib/fpdf/BasePDF.php';
require_once __DIR__ . '/../../../models/Aluno.php';

class RelatorioSaidasDoDiaPDF extends BasePDF
{
    private $alunoModel;

    // Paleta de cores padronizada com a identidade visual do sistema
    protected $colors = [
        'primary'        => [0, 122, 51],      // Verde Principal
        'secondary'      => [255, 165, 0],    // Laranja / Amarelo Destaque
        'light_green'    => [240, 249, 244],  // Verde Claro (Cabeçalho da tabela)
        'dark'           => [55, 65, 81],     // Texto Escuro
        'gray_border'    => [229, 231, 235],  // Bordas Suaves
        'light_gray_row' => [245, 245, 245]   // Linhas Alternadas (Zebra)
    ];

    public function __construct()
    {
        // Unidade em 'pt' para manter precisão e alinhamento do layout moderno
        parent::__construct('P', 'pt', 'A4');
        $this->SetMargins(20, 20, 20);
        $this->alunoModel = new Aluno();
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
        $this->Cell(0, 20, utf8_decode('Relatório de Saídas do Dia'), 0, 1, 'C');

        // Subtítulo em Branco com a Data Atual
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 18, utf8_decode('Registros do Dia: ' . date('d/m/Y')), 0, 1, 'C');
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

        $saidas = $this->alunoModel->listarSaidasDoDia();

        if (empty($saidas)) {
            $this->SetFont('Arial', 'I', 10);
            $this->SetTextColor($this->colors['dark'][0], $this->colors['dark'][1], $this->colors['dark'][2]);
            $this->Cell(0, 20, utf8_decode('Nenhum registro de saída efetuado no dia de hoje.'), 0, 1, 'L');
            return;
        }

        // Largura útil total da página A4 em pontos (555.28pt)
        $pageWidth = $this->GetPageWidth() - 40;

        // Distribuição proporcional das 7 colunas
        $colAluno       = $pageWidth * 0.24; // 24%
        $colTurma       = $pageWidth * 0.12; // 12%
        $colData        = $pageWidth * 0.11; // 11%
        $colHora        = $pageWidth * 0.09; // 9%
        $colResponsavel = $pageWidth * 0.22; // 22%
        $colMotivo      = $pageWidth * 0.12; // 12%
        $colConducente  = $pageWidth * 0.10; // 10%

        // Cabeçalho da Tabela
        $this->SetDrawColor($this->colors['gray_border'][0], $this->colors['gray_border'][1], $this->colors['gray_border'][2]);
        $this->SetLineWidth(0.2);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor($this->colors['light_green'][0], $this->colors['light_green'][1], $this->colors['light_green'][2]);
        $this->SetTextColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);

        $this->Cell($colAluno, 14, utf8_decode('Aluno'), 1, 0, 'L', true);
        $this->Cell($colTurma, 14, utf8_decode('Turma'), 1, 0, 'L', true);
        $this->Cell($colData, 14, utf8_decode('Data'), 1, 0, 'C', true);
        $this->Cell($colHora, 14, utf8_decode('Hora'), 1, 0, 'C', true);
        $this->Cell($colResponsavel, 14, utf8_decode('Responsável'), 1, 0, 'L', true);
        $this->Cell($colMotivo, 14, utf8_decode('Motivo'), 1, 0, 'L', true);
        $this->Cell($colConducente, 14, utf8_decode('Conducente'), 1, 1, 'L', true);

        // Corpo da Tabela
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor($this->colors['dark'][0], $this->colors['dark'][1], $this->colors['dark'][2]);

        $i = 0;
        foreach ($saidas as $row) {
            // Efeito Zebra (linhas alternadas)
            $fill = ($i % 2 == 0) ? 255 : $this->colors['light_gray_row'][0];
            $this->SetFillColor($fill, $fill, $fill);

            // Formatação dos dados
            $aluno       = utf8_decode(strtoupper($row['aluno'] ?? '---'));
            $turma       = utf8_decode(strtoupper(($row['serie'] ?? '') . ' / ' . ($row['turma'] ?? '')));
            $data        = !empty($row['data_saida']) ? date('d/m/Y', strtotime($row['data_saida'])) : '--/--/----';
            $hora        = !empty($row['hora_saida']) ? date('H:i', strtotime($row['hora_saida'])) : '--:--';

            $respNome    = $row['nome_responsavel'] ?? '';
            $respTipo    = !empty($row['tipo_responsavel']) ? ucfirst($row['tipo_responsavel']) . ' - ' : '';
            $responsavel = utf8_decode(strtoupper($respTipo . $respNome));

            $motivo      = utf8_decode(ucfirst($row['motivo'] ?? 'N/A'));
            $conducente  = utf8_decode(strtoupper($row['tipo_conducente'] ?? '---'));

            $this->Cell($colAluno, 12, $aluno, 1, 0, 'L', true);
            $this->Cell($colTurma, 12, $turma, 1, 0, 'L', true);
            $this->Cell($colData, 12, $data, 1, 0, 'C', true);
            $this->Cell($colHora, 12, $hora, 1, 0, 'C', true);
            $this->Cell($colResponsavel, 12, $responsavel, 1, 0, 'L', true);
            $this->Cell($colMotivo, 12, $motivo, 1, 0, 'L', true);
            $this->Cell($colConducente, 12, $conducente, 1, 1, 'L', true);

            $i++;
        }
    }
}

// Instanciação e Renderização do Relatório
$pdf = new RelatorioSaidasDoDiaPDF();
$pdf->generateReport();
$pdf->Output('Relatorio_de_Saidas_do_Dia.pdf', 'I');
?>