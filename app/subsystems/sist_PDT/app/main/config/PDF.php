<?php
require_once 'C:/xampp/htdocs/SIS_PDT2/app/main/assets/vendor/fpdf/fpdf.php';

class PDF extends FPDF {
    private $backgroundImage;
    
    public function setBackgroundImage($imagePath) {
        $this->backgroundImage = $imagePath;
    }
    
    function Header() {
        // Se houver uma imagem de fundo definida
        if ($this->backgroundImage) {
            // Adiciona a imagem de fundo
            $this->Image($this->backgroundImage, 0, 0, $this->w, $this->h);
        }
    }
    
    function Footer() {
        // Posiciona a 1.5 cm do final da página
        $this->SetY(-15);
        // Define a fonte
        $this->SetFont('Arial', 'I', 8);
        // Adiciona o número da página
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
?> 