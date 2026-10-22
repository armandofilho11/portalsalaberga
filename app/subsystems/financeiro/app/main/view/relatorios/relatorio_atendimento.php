<?php 
require('../../assets/lib/fpdf/fpdf.php');
$conn = new mysqli('localhost', 'root', '', 'u750204740_sistemafinanceiro');
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,utf8_decode('Relatório - Declarações de Atendimento'),0,1,'C');
        $this->Ln(5);
        $this->SetFont('Arial','B',10);
        // Cabeçalho com fundo cinza claro
        $this->SetFillColor(200,200,200);
        $this->Cell(10,8,'ID',1,0,'C',true);
        $this->Cell(40,8,'Empresa',1,0,'C',true);
        $this->Cell(30,8,'CNPJ',1,0,'C',true);
        $this->Cell(30,8,'NUP',1,0,'C',true);
        $this->Cell(80,8,'Gestor',1,0,'C',true);
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
$res = $conn->query("SELECT * FROM atendimentos ORDER BY criado_em DESC");
while ($r = $res->fetch_assoc()) {
    // Alterna entre fundo branco e cinza muito claro para as linhas
    
    $pdf->Cell(10,8,$r['id'],1,0,'C',);
    $pdf->Cell(40,8,utf8_decode(substr($r['empresa'],0,40)),1,0,'L',);
    $pdf->Cell(30,8,$r['cnpj'],1,0,'C',);
    $pdf->Cell(30,8,$r['nup'],1,0,'C',  );
    $pdf->Cell(80,8,utf8_decode(substr($r['gestor'],0,40)),1,0,'L',);
    $pdf->Ln();
}
$pdf->Output();
$conn->close();
?>