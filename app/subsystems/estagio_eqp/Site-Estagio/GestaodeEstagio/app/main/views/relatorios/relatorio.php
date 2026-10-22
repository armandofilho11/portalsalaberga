<?php
require('../../models/model-function.php');
require('../../assets/fpdf/fpdf.php');

// Definir fuso horário para Brasil/São Paulo
date_default_timezone_set('America/Sao_Paulo');

// Array com os cursos corretos
$cursos = [
    'enfermagem' => 'Enfermagem',
    'informatica' => 'Informática',
    'administracao' => 'Administração',
    'edificacoes' => 'Edificações',
    'meio_ambiente' => 'Meio Ambiente'
];

class PDF extends FPDF {
    // Cores personalizadas
    private $headerColor = array(45, 71, 57); // Verde musgo
    private $headerTextColor = array(255, 255, 255); // Branco
    private $alternateColor = array(240, 248, 255); // Azul muito claro
    private $borderColor = array(200, 200, 200); // Cinza claro
    private $textColor = array(50, 50, 50); // Cinza escuro
    private $highlightColor = array(220, 237, 200); // Verde claro

    // Page header
    function Header() {
        // Logo
        $this->Image(__DIR__ . '/../../config/img/logo_Salaberga-removebg-preview.png', 10, 10, 35);
        
        // Retângulo de fundo para o título
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->Rect(50, 10, $this->GetPageWidth() - 60, 25, 'F');
        
        // Title
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor($this->headerTextColor[0], $this->headerTextColor[1], $this->headerTextColor[2]);
        $this->SetXY(50, 15);
        $this->Cell($this->GetPageWidth() - 60, 10, utf8_decode('Relatório de Empresas Concedentes'), 0, 1, 'C');
        
        // Subtitle
        $this->SetFont('Arial', 'I', 11);
        $this->SetXY(50, 25);
        $this->Cell($this->GetPageWidth() - 60, 5, utf8_decode('Sistema de Gestão de Estágios'), 0, 1, 'C');
        
        // Date and search info
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $this->SetXY(10, 40);
        $this->Cell(0, 5, utf8_decode('Data de geração: ' . date('d/m/Y H:i:s')), 0, 1, 'L');
        
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $this->SetFont('Arial', 'I', 9);
            $this->Cell(0, 5, utf8_decode('Termo de busca: "' . htmlspecialchars($_GET['search']) . '"'), 0, 1, 'L');
        }
        
        // Linha decorativa
        $this->SetDrawColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY() + 5, $this->GetPageWidth() - 10, $this->GetY() + 5);
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        
        // Linha decorativa
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->Line(10, $this->GetY() - 5, $this->GetPageWidth() - 10, $this->GetY() - 5);
        
        // Texto do rodapé
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->SetXY(10, $this->GetY());
        $this->Cell(0, 10, utf8_decode('Sistema de Gestão de Estágios - ' . date('Y')), 0, 0, 'L');
    }

    // Table header
    function TableHeader() {
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->SetTextColor($this->headerTextColor[0], $this->headerTextColor[1], $this->headerTextColor[2]);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(0.3);
        $this->SetFont('Arial', 'B', 11);

        // Ajustar larguras das colunas para melhor visualização
        $this->CellUTF8(70, 12, 'Empresa', 1, 0, 'C', true);
        $this->CellUTF8(60, 12, 'Perfis', 1, 0, 'C', true);
        $this->CellUTF8(25, 12, 'Vagas', 1, 0, 'C', true);
        $this->CellUTF8(50, 12, 'Contato', 1, 0, 'C', true);
        $this->CellUTF8(70, 12, 'Endereço', 1, 1, 'C', true);
    }

    // Table content
    function TableContent($data) {
        global $cursos;
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $fill = false;

        foreach($data as $row) {
            if($this->GetY() > 260) {
                $this->AddPage();
                $this->TableHeader();
            }

            // Alternar cores das linhas
            if ($fill) {
                $this->SetFillColor($this->alternateColor[0], $this->alternateColor[1], $this->alternateColor[2]);
            } else {
                $this->SetFillColor(255, 255, 255);
            }

            // Processar perfis com destaque
            $perfis = [];
            if (!empty($row['perfis'])) {
                $perfis_array = json_decode($row['perfis'], true);
                if (is_array($perfis_array)) {
                    foreach ($perfis_array as $p) {
                        if (isset($cursos[$p])) {
                            $perfis[] = $cursos[$p];
                        } else {
                            $perfis[] = ucfirst($p);
                        }
                    }
                }
            }
            $perfis_text = !empty($perfis) ? implode(', ', $perfis) : 'Nenhum';

            // Destacar número de vagas se for maior que 0
            if ($row['numero_vagas'] > 0) {
                $this->SetFillColor($this->highlightColor[0], $this->highlightColor[1], $this->highlightColor[2]);
            }

            // Dados com UTF-8 e melhor espaçamento
            $this->CellUTF8(70, 10, $row['nome'], 1, 0, 'L', $fill);
            $this->CellUTF8(60, 10, $perfis_text, 1, 0, 'L', $fill);
            $this->CellUTF8(25, 10, $row['numero_vagas'], 1, 0, 'C', $fill);
            $this->CellUTF8(50, 10, $row['contato'], 1, 0, 'L', $fill);
            $this->CellUTF8(70, 10, $row['endereco'], 1, 1, 'L', $fill);
            
            $fill = !$fill;
        }
    }

    // Função para célula com UTF-8
    function CellUTF8($w, $h, $txt, $border, $ln, $align, $fill) {
        $txt = utf8_decode($txt);
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill);
    }
}

// Create PDF document
$pdf = new PDF('L'); // Landscape
$pdf->SetMargins(10, 40, 10);
$pdf->AliasNbPages();
$pdf->AddPage();

// Database connection
$pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio", "root", "");

// Get search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare SQL query
if (!empty($search)) {
    $consulta = 'SELECT * FROM concedentes WHERE 
                 LOWER(nome) LIKE LOWER(:search) OR 
                 LOWER(contato) LIKE LOWER(:search) OR 
                 LOWER(endereco) LIKE LOWER(:search) OR 
                 LOWER(perfis) LIKE LOWER(:search) 
                 ORDER BY nome ASC';
    $query = $pdo->prepare($consulta);
    $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
} else {
    $consulta = 'SELECT * FROM concedentes ORDER BY nome ASC';
    $query = $pdo->prepare($consulta);
}

// Execute query
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

// Add table
$pdf->TableHeader();
$pdf->TableContent($result);

// Output PDF
$pdf->Output('I', 'relatorio_empresas.pdf');
?>