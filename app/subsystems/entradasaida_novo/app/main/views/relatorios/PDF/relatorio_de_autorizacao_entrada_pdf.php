<?php
// PDF de autorização de entrada do aluno

date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../index.php');
    exit();
}

require_once __DIR__ . '/../../../assets/lib/fpdf/BasePDF.php';
require_once __DIR__ . '/../../../models/Aluno.php';

class RelatorioAutorizacaoEntradaPDF extends BasePDF
{
    private $alunoModel;
    private $registro;

    protected $colors = [
        'primary'      => [0, 122, 51],
        'secondary'    => [255, 165, 0],
        'text_dark'    => [34, 40, 49],
        'light_gray'   => [245, 245, 245],
        'border_gray'  => [221, 221, 221],
    ];

    public function __construct(int $id)
    {
        parent::__construct('P', 'pt', 'A4');
        $this->SetMargins(28, 28, 28);
        $this->alunoModel = new Aluno();
        $this->registro = $this->alunoModel->buscarEntradaPorId($id);
    }

    public function Header()
    {
        $w = $this->GetPageWidth();
        $this->SetFillColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
        $this->Rect(0, 0, $w, 60, 'F');

        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 24, utf8_decode('Autorização de Entrada na Sala'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 16, utf8_decode('Documento de autorização de acesso acadêmico emitido pelo sistema escolar.'), 0, 1, 'C');
        $this->Ln(8);
    }

    public function Footer()
    {
        $this->SetY(-60);
        $this->SetDrawColor($this->colors['border_gray'][0], $this->colors['border_gray'][1], $this->colors['border_gray'][2]);
        $this->Line(28, $this->GetY(), $this->GetPageWidth() - 28, $this->GetY());
        $this->Ln(12);

        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor($this->colors['text_dark'][0], $this->colors['text_dark'][1], $this->colors['text_dark'][2]);
        $this->Cell(0, 12, utf8_decode('Emitido em: ' . date('d/m/Y H:i:s') . '   |   Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    public function generateReport()
    {
        if (!$this->registro) {
            $this->AddPage();
            $this->SetFont('Arial', 'B', 12);
            $this->SetTextColor($this->colors['text_dark'][0], $this->colors['text_dark'][1], $this->colors['text_dark'][2]);
            $this->Cell(0, 20, utf8_decode('Registro de entrada não encontrado.'), 0, 1, 'L');
            return;
        }

        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor($this->colors['text_dark'][0], $this->colors['text_dark'][1], $this->colors['text_dark'][2]);

        $this->SetFillColor($this->colors['light_gray'][0], $this->colors['light_gray'][1], $this->colors['light_gray'][2]);
        $this->SetDrawColor($this->colors['border_gray'][0], $this->colors['border_gray'][1], $this->colors['border_gray'][2]);

        $this->Cell(0, 22, utf8_decode('Dados do Aluno'), 1, 1, 'L', true);
        $this->Ln(4);

        $this->SetFont('Arial', '', 10);
        $this->Cell(140, 18, utf8_decode('Nome do Aluno:'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 18, utf8_decode($this->registro['aluno'] ?? '---'), 0, 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->Cell(140, 18, utf8_decode('Turma / Série:'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 18, utf8_decode(trim(($this->registro['serie'] ?? '') . ' / ' . ($this->registro['turma'] ?? ''))), 0, 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->Cell(140, 18, utf8_decode('Data da Entrada:'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 18, utf8_decode(!empty($this->registro['data_entrada']) ? date('d/m/Y', strtotime($this->registro['data_entrada'])) : '--/--/----'), 0, 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->Cell(140, 18, utf8_decode('Hora da Entrada:'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 18, utf8_decode(!empty($this->registro['hora_entrada']) ? date('H:i', strtotime($this->registro['hora_entrada'])) : '--:--'), 0, 1, 'L');

        $this->Ln(12);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 20, utf8_decode('Motivo da Entrada'), 0, 1, 'L');
        $this->SetFont('Arial', '', 10);

        $motivo = utf8_decode($this->registro['motivo'] ?? 'Motivo não informado');
        $descricao = utf8_decode($this->registro['descricao'] ?? 'Sem observações adicionais.');

        $this->MultiCell(0, 16, utf8_decode('Motivo: ') . $motivo, 0, 'J');
        $this->Ln(4);
        $this->MultiCell(0, 16, utf8_decode('Detalhes da solicitação: ') . $descricao, 0, 'J');

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 20, utf8_decode('Declaração de Autorização'), 0, 1, 'L');
        $this->SetFont('Arial', '', 10);

        $texto = sprintf(
            'Eu, responsável pela autorização, declaro que o aluno %s, pertencente à turma %s, está autorizado a entrar na sala na data %s às %s. Esta autorização foi concedida com base no motivo descrito acima e serve para garantir a segurança e o controle interno do acesso escolar.',
            utf8_decode($this->registro['aluno'] ?? '---'),
            utf8_decode(trim(($this->registro['serie'] ?? '') . ' / ' . ($this->registro['turma'] ?? ''))),
            utf8_decode(!empty($this->registro['data_entrada']) ? date('d/m/Y', strtotime($this->registro['data_entrada'])) : '--/--/----'),
            utf8_decode(!empty($this->registro['hora_entrada']) ? date('H:i', strtotime($this->registro['hora_entrada'])) : '--:--')
        );

        $this->MultiCell(0, 16, $texto, 0, 'J');

        $this->Ln(28);
        $this->SetDrawColor($this->colors['text_dark'][0], $this->colors['text_dark'][1], $this->colors['text_dark'][2]);
        $this->Line(70, $this->GetY(), $this->GetPageWidth() - 70, $this->GetY());
        $this->Ln(6);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 14, utf8_decode('Assinatura do Responsável pela Autorização'), 0, 1, 'C');
        $this->Ln(18);

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 12, utf8_decode('Nome: _________________________________________________'), 0, 1, 'L');
        $this->Cell(0, 12, utf8_decode('Cargo: _________________________________________________'), 0, 1, 'L');
        $this->Cell(0, 12, utf8_decode('Data: ____/____/________'), 0, 1, 'L');
    }
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdf = new RelatorioAutorizacaoEntradaPDF($id);
$pdf->generateReport();
$pdf->Output('Autorizacao_Entrada_Aluno.pdf', 'I');
