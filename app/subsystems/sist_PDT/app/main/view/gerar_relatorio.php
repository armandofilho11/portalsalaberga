<?php
mb_internal_encoding("UTF-8");
require_once('../config/database.php');
require_once('../assets/vendor/fpdf/fpdf.php');

class PDF extends FPDF {
    // Cores
    private $headerColor = array(100, 160, 40); // Green from logo for accents/table headers
    private $titleColor = array(50, 50, 50); // Dark grey for titles
    private $textColor = array(70, 70, 70); // Standard dark grey for text
    private $borderColor = array(220, 220, 220); // Very light grey for borders
    private $highlightColor = array(245, 245, 245); // Very light grey for highlights/alternating rows

    public function __construct($orientation='P', $unit='mm', $size='A4') {
        parent::__construct($orientation, $unit, $size);
        
        // Set default font
        $this->SetFont('helvetica', '', 12);
        
        // Set default colors
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetFillColor(255, 255, 255);
        
        // Set margins (in mm)
        $this->SetMargins(20, 20, 20);
        
        // Set auto page break
        $this->SetAutoPageBreak(true, 20);
        
        // Set document properties
        $this->SetCreator('Sistema de Gestão Escolar');
        $this->SetAuthor('Sistema de Gestão Escolar');
        $this->SetTitle('Relatório');
    }

    // Cabeçalho
    function Header() {
        // Logo Salaberga
        $logoSalabergaPath = '../assets/img/salaberga.png';
        if (file_exists($logoSalabergaPath)) {
            $this->Image($logoSalabergaPath, 20, 15, 40);
        }

        // Logo Ceará
        $logoCearaPath = '../assets/img/ceara.png'; // Assuming this path
        if (file_exists($logoCearaPath)) {
            $this->Image($logoCearaPath, $this->GetPageWidth() - 20 - 40, 15, 40); // Position on the right
        }
        
        // Título
        $this->SetFont('helvetica', 'B', 18);
        $this->SetTextColor($this->titleColor[0], $this->titleColor[1], $this->titleColor[2]);
        $this->Cell(0, 20, mb_convert_encoding('Relatório de Gestão Escolar', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', false); // No fill for header title
        
        // Data
        $this->SetFont('helvetica', 'I', 10);
        $this->Cell(0, 10, mb_convert_encoding('Data: ' . date('d/m/Y'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R', false); // No fill for date
        
        // Linha decorativa
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->Line(20, 45, 190, 45);
        $this->Ln(15);
        
        // Resetar cores - already handled by default in __construct if not overridden
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
    }

    // Rodapé
    function Footer() {
        // Wave design image
        $footerWavePath = '../assets/img/footer_wave.png'; // Assuming this path for the wave design
        if (file_exists($footerWavePath)) {
            $this->Image($footerWavePath, 0, $this->GetPageHeight() - 40, $this->GetPageWidth(), 40); // Adjust height (40) as needed
        }

        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $this->Cell(0, 10, mb_convert_encoding('Página ' . $this->PageNo() . '/{nb}', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    }

    // Método para criar uma seção com título
    function SectionTitle($title) {
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor($this->titleColor[0], $this->titleColor[1], $this->titleColor[2]);
        $this->Cell(0, 15, mb_convert_encoding($title, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $this->Ln(10);
    }

    // Método para criar um bloco de informação
    function InfoBlock($label, $value) {
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor($this->titleColor[0], $this->titleColor[1], $this->titleColor[2]);
        $this->Cell(50, 10, mb_convert_encoding($label, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        
        $this->SetFont('helvetica', '', 12);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $this->Cell(0, 10, mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
    }

    // Método para criar uma tabela
    function Table($header, $data) {
        // Cores
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]); // Using accent color for table header
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(.3);
        $this->SetFont('helvetica', 'B', 12);

        // Cabeçalho
        $w = array(40, 100, 50);
        for($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 10, mb_convert_encoding($header[$i], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Ln();

        // Dados
        $this->SetFillColor($this->highlightColor[0], $this->highlightColor[1], $this->highlightColor[2]); // Using highlight color for alternating rows
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $this->SetFont('helvetica', '', 12);
        $fill = false;
        foreach($data as $row) {
            for($i = 0; $i < count($row); $i++)
                $this->Cell($w[$i], 10, mb_convert_encoding($row[$i], 'ISO-8859-1', 'UTF-8'), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// Verifica o tipo de relatório
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Cria o PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

try {
    // Consulta específica para cada tipo de relatório
    switch($tipo) {
        case 'lideranca':
            $sql = "SELECT l.matricula_lider, a.nome, l.bimestre 
                   FROM lider l 
                   JOIN alunos a ON l.matricula_lider = a.matricula 
                   ORDER BY l.bimestre, a.nome";
            $titulo = "Relatório de Liderança";
            break;
            
        case 'vice_lideranca':
            $sql = "SELECT vl.matricula_vice_lider, a.nome, vl.bimestre 
                   FROM vice_lider vl 
                   JOIN alunos a ON vl.matricula_vice_lider = a.matricula 
                   ORDER BY vl.bimestre, a.nome";
            $titulo = "Relatório de Vice-Liderança";
            break;
            
        case 'secretaria':
            $sql = "SELECT s.matricula_secretario, a.nome, s.bimestre 
                   FROM secretario s 
                   JOIN alunos a ON s.matricula_secretario = a.matricula 
                   ORDER BY s.bimestre, a.nome";
            $titulo = "Relatório de Secretaria";
            break;
            
        case 'avisos':
            $sql = "SELECT id_aviso, aviso, data_aviso, matricula_aluno 
                   FROM avisos 
                   ORDER BY data_aviso DESC";
            $titulo = "Relatório de Avisos";
            break;
            
        case 'ocorrencias':
            $sql = "SELECT o.id_ocorrencias, o.ocorrencia, o.data_ocorrencia, o.matricula_aluno, a.nome 
                   FROM ocorrencias o 
                   JOIN alunos a ON o.matricula_aluno = a.matricula 
                   ORDER BY o.data_ocorrencia DESC";
            $titulo = "Relatório de Ocorrências";
            break;
            
        case 'mapeamento':
            $sql = "SELECT m.id_mapeamento, m.numero_carteira, m.matricula_aluno, m.data_mapeamento, a.nome 
                   FROM mapeamento m 
                   JOIN alunos a ON m.matricula_aluno = a.matricula 
                   ORDER BY m.data_mapeamento DESC";
            $titulo = "Relatório de Mapeamento";
            break;

        default:
            $pdf->Cell(0, 10, mb_convert_encoding('Tipo de relatório não especificado.', 'ISO-8859-1', 'UTF-8'), 0, 1);
            return;
    }

    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll();

    $pdf->SectionTitle($titulo);

    if (count($result) > 0) {
        foreach($result as $row) {
            switch($tipo) {
                case 'lideranca':
                    $pdf->InfoBlock('Líder:', $row['nome']);
                    $pdf->InfoBlock('Matrícula:', $row['matricula_lider']);
                    $pdf->InfoBlock('Bimestre:', $row['bimestre']);
                    $pdf->Ln(5);
                    break;

                case 'vice_lideranca':
                    $pdf->InfoBlock('Vice-Líder:', $row['nome']);
                    $pdf->InfoBlock('Matrícula:', $row['matricula_vice_lider']);
                    $pdf->InfoBlock('Bimestre:', $row['bimestre']);
                    $pdf->Ln(5);
                    break;

                case 'secretaria':
                    $pdf->InfoBlock('Secretário:', $row['nome']);
                    $pdf->InfoBlock('Matrícula:', $row['matricula_secretario']);
                    $pdf->InfoBlock('Bimestre:', $row['bimestre']);
                    $pdf->Ln(5);
                    break;

                case 'avisos':
                    $pdf->InfoBlock('ID do Aviso:', $row['id_aviso']);
                    $pdf->InfoBlock('Aviso:', $row['aviso']);
                    $pdf->InfoBlock('Data:', date('d/m/Y', strtotime($row['data_aviso'])));
                    
                    // Buscar nome do aluno para o aviso
                    $stmt_aluno = $pdo->prepare("SELECT nome FROM alunos WHERE matricula = :matricula");
                    $stmt_aluno->bindParam(':matricula', $row['matricula_aluno']);
                    $stmt_aluno->execute();
                    $aluno = $stmt_aluno->fetch(PDO::FETCH_ASSOC);
                    if ($aluno) {
                        $pdf->InfoBlock('Aluno:', $aluno['nome']);
                    }
                    $pdf->Ln(5);
                    break;

                case 'ocorrencias':
                    $pdf->InfoBlock('Aluno:', $row['nome']);
                    $pdf->InfoBlock('Matrícula:', $row['matricula_aluno']);
                    $pdf->InfoBlock('Ocorrência:', $row['ocorrencia']);
                    $pdf->InfoBlock('Data:', date('d/m/Y', strtotime($row['data_ocorrencia'])));
                    $pdf->Ln(5);
                    break;

                case 'mapeamento':
                    $pdf->InfoBlock('Aluno:', $row['nome']);
                    $pdf->InfoBlock('Matrícula:', $row['matricula_aluno']);
                    $pdf->InfoBlock('Número da Carteira:', $row['numero_carteira']);
                    $pdf->InfoBlock('Data do Mapeamento:', date('d/m/Y', strtotime($row['data_mapeamento'])));
                    $pdf->Ln(5);
                    break;

                default:
                    $pdf->Cell(0, 10, mb_convert_encoding('Erro: Tipo de relatório desconhecido ou dados ausentes.', 'ISO-8859-1', 'UTF-8'), 0, 1);
            }
        }
    } else {
        $pdf->Cell(0, 10, mb_convert_encoding('Nenhuma ' . $titulo . ' registrado.', 'ISO-8859-1', 'UTF-8'), 0, 1);
    }
} catch(PDOException $e) {
    $pdf->Cell(0, 10, mb_convert_encoding('Erro ao gerar relatório: ' . $e->getMessage(), 'ISO-8859-1', 'UTF-8'), 0, 1);
}

// Gera o PDF
$pdf->Output();
?>
