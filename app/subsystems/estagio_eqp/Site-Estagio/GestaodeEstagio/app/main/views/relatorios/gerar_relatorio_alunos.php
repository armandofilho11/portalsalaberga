<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../assets/fpdf/fpdf.php';

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
    public $filtro_info;

    // Cores personalizadas
    private $headerColor = array(45, 71, 57); // Verde musgo
    private $headerTextColor = array(255, 255, 255); // Branco
    private $alternateColor = array(240, 248, 255); // Azul muito claro
    private $borderColor = array(200, 200, 200); // Cinza claro
    private $textColor = array(50, 50, 50); // Cinza escuro
    private $highlightColor = array(220, 237, 200); // Verde claro

    function Header() {
        // Logo
        $this->Image(__DIR__ . '/../../config/img/logo_Salaberga-removebg-preview.png', 10, 10, 35);
        
        // Retângulo de fundo para o título
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->Rect(50, 10, $this->GetPageWidth() - 60, 25, 'F');
        
        // Título
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor($this->headerTextColor[0], $this->headerTextColor[1], $this->headerTextColor[2]);
        $this->SetXY(50, 15);
        $this->Cell($this->GetPageWidth() - 60, 10, utf8_decode('Relatório de Alunos'), 0, 1, 'C');
        
        // Subtitle
        $this->SetFont('Arial', 'I', 11);
        $this->SetXY(50, 25);
        $this->Cell($this->GetPageWidth() - 60, 5, utf8_decode('Sistema de Gestão de Estágios'), 0, 1, 'C');
        
        // Data e informação do filtro
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $this->SetXY(10, 40);
        $this->Cell(0, 5, utf8_decode('Data de geração: ' . date('d/m/Y H:i:s')), 0, 1, 'L');
        
        if (isset($this->filtro_info)) {
            $this->SetFont('Arial', 'I', 9);
            $this->Cell(0, 5, utf8_decode('Filtro: ' . $this->filtro_info), 0, 1, 'L');
        }
        
        // Linha decorativa
        $this->SetDrawColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY() + 5, $this->GetPageWidth() - 10, $this->GetY() + 5);
        $this->Ln(10);
    }

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

    // Função para converter texto para UTF-8
    function TextUTF8($x, $y, $txt) {
        $txt = utf8_decode($txt);
        $this->Text($x, $y, $txt);
    }

    // Table header
    function TableHeader() {
        $this->SetFont('Arial', 'B', 11); // Fonte maior para cabeçalho da tabela
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->SetTextColor($this->headerTextColor[0], $this->headerTextColor[1], $this->headerTextColor[2]);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(0.3);
        
        // Ajustar larguras das colunas
        $this->CellUTF8(45, 12, 'Nome', 1, 0, 'C', true);
        $this->CellUTF8(25, 12, 'Matrícula', 1, 0, 'C', true);
        $this->CellUTF8(35, 12, 'Curso', 1, 0, 'C', true);
        $this->CellUTF8(55, 12, 'Email', 1, 0, 'C', true);
        $this->CellUTF8(25, 12, 'Contato', 1, 0, 'C', true);
        $this->CellUTF8(30, 12, 'Status', 1, 0, 'C', true);
        $this->CellUTF8(60, 12, 'Endereço', 1, 1, 'C', true);
    }

    // Função para célula com UTF-8
    function CellUTF8($w, $h, $txt, $border, $ln, $align, $fill) {
        $txt = utf8_decode($txt);
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill);
    }

    // Table content
    function TableContent($data) {
        global $cursos;
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $fill = false; // Initialize $fill here
        
        foreach ($data as $row) {
            if($this->GetY() > 260) { // Check for page break
                $this->AddPage();
                $this->TableHeader();
            }

            // Formatar nome do curso
            $curso_formatado = isset($cursos[$row['curso']]) ? $cursos[$row['curso']] : ucfirst($row['curso']);
            
            // Formatar status
            $status = $row['status_atual'];
            $status_formatado = $status === 'alocado' ? 'Alocado' : ($status === 'pendente' ? 'Em Espera' : 'Não Alocado');

            // Alternar cores das linhas e destacar status
            if ($status === 'alocado') {
                $this->SetFillColor($this->highlightColor[0], $this->highlightColor[1], $this->highlightColor[2]);
            } else if ($fill) {
                $this->SetFillColor($this->alternateColor[0], $this->alternateColor[1], $this->alternateColor[2]);
            } else {
                $this->SetFillColor(255, 255, 255);
            }

            // Dados com UTF-8
            $this->CellUTF8(45, 10, $row['nome'], 1, 0, 'L', true);
            $this->CellUTF8(25, 10, $row['matricula'], 1, 0, 'C', true);
            $this->CellUTF8(35, 10, $curso_formatado, 1, 0, 'C', true);
            $this->CellUTF8(55, 10, $row['email'], 1, 0, 'L', true);
            $this->CellUTF8(25, 10, $row['contato'], 1, 0, 'C', true);
            $this->CellUTF8(30, 10, $status_formatado, 1, 0, 'C', true);
            $this->CellUTF8(60, 10, $row['endereco'], 1, 1, 'L', true);
            
            $fill = !$fill; // Toggle $fill for next row
        }
    }
}

try {
    // Conexão com o banco de dados
    $conn = getConnection();

    // Processar filtros
    $filtro = $_POST['filtro_aluno'] ?? 'todos';
    $curso = $_POST['curso'] ?? '';
    $nome = $_POST['nome_aluno'] ?? '';
    $local = $_POST['local_aluno'] ?? '';

    // Construir a query base
    $sql = "SELECT a.*, 
            COALESCE((SELECT status FROM selecao WHERE id_aluno = a.id ORDER BY id DESC LIMIT 1), 'nao_alocado') as status_atual
            FROM aluno a WHERE 1=1";
    
    if ($filtro === 'curso' && !empty($curso)) {
        $sql .= " AND a.curso = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $curso);
    } elseif ($filtro === 'nome' && !empty($nome)) {
        $sql .= " AND a.nome LIKE ?";
        $nome = "%$nome%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nome);
    } elseif ($filtro === 'local' && !empty($local)) {
        $sql .= " AND a.endereco LIKE ?";
        $local = "%$local%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $local);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Criar PDF
    $pdf = new PDF('L'); // Landscape
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Configurar informação do filtro
    if ($filtro !== 'todos') {
        switch ($filtro) {
            case 'curso':
                $curso_formatado = isset($cursos[$curso]) ? $cursos[$curso] : ucfirst($curso);
                $pdf->filtro_info = 'Filtrado por Curso: ' . $curso_formatado;
                break;
            case 'nome':
                $pdf->filtro_info = 'Filtrado por Nome: ' . $nome;
                break;
            case 'local':
                $pdf->filtro_info = 'Filtrado por Local: ' . $local;
                break;
        }
    }

    // Add table
    $pdf->TableHeader(); // Call the TableHeader method to set header styles and render header row
    $pdf->TableContent($result); // Pass $result to TableContent

    // Nome do arquivo
    $filename = 'relatorio_alunos_' . date('Y-m-d_H-i-s') . '.pdf';

    // Enviar o PDF para o navegador
    $pdf->Output('I', $filename);

} catch (Exception $e) {
    die('Erro ao gerar relatório: ' . $e->getMessage());
}
?> 