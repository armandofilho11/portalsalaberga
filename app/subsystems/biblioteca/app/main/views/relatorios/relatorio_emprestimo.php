<?php
require_once('../../assets/fpdf/fpdf.php');
require_once(__DIR__.'../../config/connect.php');
class PDF extends connect
{
    function __construct()
    {
        parent::__construct();
        $this->Header();
        $this->main();
        $this->Footer();
    }
    function Header()
    {
        $pdf = new PDF("L", "pt", "A4");
        if ($this->PageNo() == 1) {
            $this->Image('../../assets/img/logo.png', 20, 14, 50);
            $this->Ln(20);

            $this->SetX(20);
            $this->SetFont('Arial', 'B', 20);
            $this->SetTextColor(0, 122, 51);
            $this->Cell($this->GetPageWidth() - 40, 20, utf8_decode('RELATÓRIO DE EMPRÉSTIMOS'), 0, 1, 'C');

            $this->SetX(20);
            $this->SetFont('Arial', 'B', 14);
            $this->SetTextColor(255, 165, 0);
            $this->Cell($this->GetPageWidth() - 40, 20, "BIBLIOTECA STGM", 0, 1, 'C');

            $this->Ln(10);
        }
    }

    function main()
    {
        $pdf = $this->Header();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Consulta os dados dos empréstimos
        $sql = $this->connect->prepare("
            SELECT e.id, e.data_emprestimo, e.data_devolucao_estipulada, 
                   a.nome AS nome_aluno, 
                   t.turma AS turma_aluno, 
                   c.titulo_livro
            FROM emprestimo e
            INNER JOIN aluno a ON e.id_aluno = a.id_aluno
            INNER JOIN turma t ON a.id_turma = t.id_turma
            INNER JOIN catalogo c ON e.id_catalogo = c.id
            ORDER BY e.data_emprestimo DESC
        ");
        $sql->execute();
        $emprestimos = $sql->fetchAll(PDO::FETCH_ASSOC);

        $pageWidth = $pdf->GetPageWidth() - 40;
        $colunas = array(
            array('largura' => $pageWidth * 0.20, 'texto' => 'ALUNO'),
            array('largura' => $pageWidth * 0.20, 'texto' => 'TURMA'),
            array('largura' => $pageWidth * 0.30, 'texto' => 'TÍTULO DO LIVRO'),
            array('largura' => $pageWidth * 0.15, 'texto' => 'EMPRÉSTIMO'),
            array('largura' => $pageWidth * 0.15, 'texto' => 'DEVOLUÇÃO')
        );

        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 122, 51);
        $pdf->SetTextColor(255, 255, 255);

        foreach ($colunas as $coluna) {
            $pdf->Cell($coluna['largura'], 20, utf8_decode($coluna['texto']), 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        $linha = 0;

        foreach ($emprestimos as $e) {
            $cor = $linha++ % 2 == 0 ? 255 : 240;
            $pdf->SetFillColor($cor, $cor, $cor);
            $pdf->SetX(20);

            $pdf->Cell($colunas[0]['largura'], 20, utf8_decode(mb_strtoupper($e['nome_aluno'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell($colunas[1]['largura'], 20, utf8_decode(mb_strtoupper($e['turma_aluno'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell($colunas[2]['largura'], 20, utf8_decode(mb_strtoupper($e['titulo_livro'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell($colunas[3]['largura'], 20, date('d/m/Y', strtotime($e['data_emprestimo'])), 1, 0, 'C', true);
            $pdf->Cell($colunas[4]['largura'], 20, date('d/m/Y', strtotime($e['data_devolucao_estipulada'])), 1, 1, 'C', true);
        }

        $pdf->Ln(10);
        $pdf->Output('relatorio_emprestimos.pdf', 'I');
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 122, 51);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
$pdf = new PDF();
