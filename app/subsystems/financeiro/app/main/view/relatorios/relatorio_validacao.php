<?php
require('../../assets/lib/fpdf/fpdf.php');

$conn = new mysqli('localhost', 'root', '', 'u750204740_sistemafinanceiro');
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,utf8_decode('Relatório - Validações de Documentação'),0,1,'C');
        $this->Ln(5);
        $this->SetFont('Arial','B',10);
        $this->Cell(10,8,'ID',1);
        $this->Cell(35,8,'Objeto',1);
        $this->Cell(30,8,'exercicio',1);
        $this->Cell(40,8,'NUP',1);
        $this->Cell(40,8,'Gestor',1);
        $this->Cell(30,8,'data',1);
        $this->Ln();
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
    }
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$res = $conn->query("SELECT * FROM validacoes ORDER BY criado_em DESC");
if (!$res) {
    die("Erro na consulta: " . $conn->error);
}

while ($r = $res->fetch_assoc()) {
    $pdf->Cell(10,8,$r['id'],1);
    $pdf->Cell(35,8,utf8_decode(substr($r['descricao_objeto'],0,25)),1);
    $pdf->Cell(30,8,$r['exercicio'],1);
    $pdf->Cell(40,8,$r['nup'],1);
    $pdf->Cell(40,8,utf8_decode(substr($r['gestor'],0,30)),1);
    $pdf->Cell(30,8,date('d/m/Y', strtotime($r['criado_em'])),1);
    $pdf->Ln();
}
$pdf->Output();
$conn->close();