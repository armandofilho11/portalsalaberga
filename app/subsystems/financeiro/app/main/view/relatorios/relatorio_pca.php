<?php
require('../../assets/lib/fpdf/fpdf.php');

$conn = new mysqli('localhost', 'root', '', 'u750204740_sistemafinanceiro');
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,utf8_decode('Relatório - Declarações PCA'),0,1,'C');
        $this->Ln(5);
        $this->SetFont('Arial','B',10);
        $this->Cell(10,8,'ID',1);
        $this->Cell(50,8,'Natureza',1);
        $this->Cell(30,8,'NUP',1);
        $this->Cell(50,8,'Escola',1);
        $this->Cell(50,8,'Ordenador',1);
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
$res = $conn->query("SELECT * FROM pca ORDER BY criado_em DESC");
while ($r = $res->fetch_assoc()) {
    $pdf->Cell(10,8,$r['id'],1);
    $pdf->Cell(50,8,utf8_decode(substr($r['natureza'],0,30)),1);
    $pdf->Cell(30,8,$r['nup'],1);
    $pdf->Cell(50,8,utf8_decode(substr($r['escola'],0,25)),1);
    $pdf->Cell(50,8,utf8_decode(substr($r['ordenador'],0,30)),1);
    $pdf->Ln();
}
$pdf->Output();
$conn->close();
