<?php
require_once('fpdf.php');

class CustomPDF extends FPDF {
    public function __construct($orientation='P', $unit='mm', $size='A4') {
        parent::__construct($orientation, $unit, $size);
        
        // Set default font to Helvetica
        $this->SetFont('helvetica', '', 12);
        
        // Set default colors
        $this->SetTextColor(0, 0, 0); // Black text
        $this->SetDrawColor(0, 0, 0); // Black lines
        $this->SetFillColor(255, 255, 255); // White background
        
        // Set margins (in mm)
        $this->SetMargins(20, 20, 20);
        
        // Set auto page break
        $this->SetAutoPageBreak(true, 20);
        
        // Set document properties
        $this->SetCreator('Sistema de Gestão Escolar');
        $this->SetAuthor('Sistema de Gestão Escolar');
        $this->SetTitle('Carta de Encaminhamento');
    }
    
    // Override Header method to add custom header
    public function Header() {
        // Logo
        $this->Image('../assets/img/salaberga.png', 20, 15, 40);
        
        // Title
        $this->SetFont('helvetica', 'B', 18);
        $this->Cell(0, 20, 'Carta de Encaminhamento', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        
        // Line break
        $this->Ln(25);
    }
    
    // Override Footer method to add custom footer
    public function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
        // Page number
        $this->Cell(0, 10, 'Página '.$this->PageNo().'/{nb}', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
    // Method to add a title to the document
    public function AddTitle($title) {
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, $title, 0, 1, 'C');
        $this->Ln(10);
    }
    
    // Method to add a subtitle
    public function AddSubtitle($subtitle) {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 12, $subtitle, 0, 1, 'L');
        $this->Ln(5);
    }
    
    // Method to add a table header
    public function AddTableHeader($headers, $widths) {
        $this->SetFont('helvetica', 'B', 12);
        $this->SetFillColor(240, 240, 240);
        
        for($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 10, $headers[$i], 1, 0, 'C', true);
        }
        $this->Ln();
    }
    
    // Method to add a table row
    public function AddTableRow($data, $widths) {
        $this->SetFont('helvetica', '', 12);
        
        for($i = 0; $i < count($data); $i++) {
            $this->Cell($widths[$i], 10, $data[$i], 1, 0, 'L');
        }
        $this->Ln();
    }
    
    // Method to add a paragraph
    public function AddParagraph($text) {
        $this->SetFont('helvetica', '', 12);
        $this->MultiCell(0, 6, $text, 0, 'J');
        $this->Ln(5);
    }
    
    // Method to add a signature line
    public function AddSignatureLine($name, $position) {
        $this->Ln(20);
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, $name, 0, 1, 'C');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, $position, 0, 1, 'C');
    }
} 