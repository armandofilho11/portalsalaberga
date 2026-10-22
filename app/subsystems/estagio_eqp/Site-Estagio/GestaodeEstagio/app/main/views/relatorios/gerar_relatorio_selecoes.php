<?php
// Prevenir qualquer saída antes do PDF
ob_start();

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
        $this->Cell($this->GetPageWidth() - 60, 10, utf8_decode('Relatório de Processos Seletivos'), 0, 1, 'C');
        
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

    // Função para célula com UTF-8
    function CellUTF8($w, $h, $txt, $border, $ln, $align, $fill) {
        $txt = utf8_decode($txt);
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill);
    }

    // Table Header
    function TableHeader($tipo_relatorio) {
        $this->SetFont('Arial', 'B', 11); // Fonte maior para cabeçalho da tabela
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->SetTextColor($this->headerTextColor[0], $this->headerTextColor[1], $this->headerTextColor[2]);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(0.3);

        switch ($tipo_relatorio) {
            case 'processo_seletivo':
                $this->CellUTF8(70, 12, 'Empresa', 1, 0, 'C', true);
                $this->CellUTF8(60, 12, 'Perfis', 1, 0, 'C', true);
                $this->CellUTF8(25, 12, 'Vagas', 1, 0, 'C', true);
                $this->CellUTF8(30, 12, 'Alocados', 1, 0, 'C', true);
                $this->CellUTF8(50, 12, 'Contato', 1, 0, 'C', true);
                $this->CellUTF8(60, 12, 'Endereço', 1, 1, 'C', true);
                break;
            case 'inscricoes':
                $this->CellUTF8(55, 12, 'Aluno', 1, 0, 'C', true);
                $this->CellUTF8(40, 12, 'Curso', 1, 0, 'C', true);
                $this->CellUTF8(55, 12, 'Empresa', 1, 0, 'C', true);
                $this->CellUTF8(55, 12, 'Perfis', 1, 0, 'C', true);
                $this->CellUTF8(35, 12, 'Data', 1, 0, 'C', true);
                $this->CellUTF8(35, 12, 'Status', 1, 1, 'C', true);
                break;
            case 'alunos_alocados':
                $this->CellUTF8(60, 12, 'Aluno', 1, 0, 'C', true);
                $this->CellUTF8(40, 12, 'Curso', 1, 0, 'C', true);
                $this->CellUTF8(60, 12, 'Empresa', 1, 0, 'C', true);
                $this->CellUTF8(60, 12, 'Perfis', 1, 0, 'C', true);
                $this->CellUTF8(55, 12, 'Data Alocação', 1, 1, 'C', true);
                break;
        }
    }

    // Table Content
    function TableContent($data, $tipo_relatorio, $cursos) {
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $fill = false;

        foreach ($data as $row) {
            if($this->GetY() > 260) { // Check for page break
                $this->AddPage();
                $this->TableHeader($tipo_relatorio); // Pass tipo_relatorio to recreate header
            }

            // Alternar cores das linhas
            if ($fill) {
                $this->SetFillColor($this->alternateColor[0], $this->alternateColor[1], $this->alternateColor[2]);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            // Processar perfis selecionados
            $perfis = [];
            if (!empty($row['perfis_selecionados'])) {
                $perfis_array = explode(',', $row['perfis_selecionados']);
                foreach ($perfis_array as $p) {
                    $p = trim($p, '[]"');
                    if (!empty($p) && !in_array($p, $perfis)) {
                        // Formatar nome do curso se o perfil for um curso
                        $p = isset($cursos[$p]) ? $cursos[$p] : $p;
                        $perfis[] = $p;
                    }
                }
            }
            $perfis_text = !empty($perfis) ? implode(', ', $perfis) : 'Nenhum';
            
            // Formatar nome do curso apenas se existir
            $curso_formatado = '';
            if (isset($row['aluno_curso'])) {
                $curso_formatado = isset($cursos[$row['aluno_curso']]) ? $cursos[$row['aluno_curso']] : ucfirst($row['aluno_curso']);
            }
            
            switch ($tipo_relatorio) {
                case 'processo_seletivo':
                    // Destacar empresas com vagas > 0
                    if ($row['numero_vagas'] > 0) {
                        $this->SetFillColor($this->highlightColor[0], $this->highlightColor[1], $this->highlightColor[2]);
                    }
                    $this->CellUTF8(70, 10, $row['nome'], 1, 0, 'L', true);
                    $this->CellUTF8(60, 10, $perfis_text, 1, 0, 'L', true);
                    $this->CellUTF8(25, 10, $row['numero_vagas'], 1, 0, 'C', true);
                    $this->CellUTF8(30, 10, $row['total_alocados'] ?? '0', 1, 0, 'C', true);
                    $this->CellUTF8(50, 10, $row['contato'], 1, 0, 'L', true);
                    $this->CellUTF8(60, 10, $row['endereco'], 1, 1, 'L', true);
                    break;
                case 'inscricoes':
                    // Destacar status "alocado"
                    if ($row['status'] === 'alocado') {
                        $this->SetFillColor($this->highlightColor[0], $this->highlightColor[1], $this->highlightColor[2]);
                    }
                    $this->CellUTF8(55, 10, $row['aluno_nome'], 1, 0, 'L', true);
                    $this->CellUTF8(40, 10, $curso_formatado, 1, 0, 'L', true);
                    $this->CellUTF8(55, 10, $row['concedente_nome'], 1, 0, 'L', true);
                    $this->CellUTF8(55, 10, $perfis_text, 1, 0, 'L', true);
                    $this->CellUTF8(35, 10, date('d/m/Y H:i', strtotime($row['hora'])), 1, 0, 'C', true);
                    $this->CellUTF8(35, 10, $row['status'], 1, 1, 'C', true);
                    break;
                case 'alunos_alocados':
                    $this->CellUTF8(60, 10, $row['aluno_nome'], 1, 0, 'L', true);
                    $this->CellUTF8(40, 10, $curso_formatado, 1, 0, 'L', true);
                    $this->CellUTF8(60, 10, $row['concedente_nome'], 1, 0, 'L', true);
                    $this->CellUTF8(60, 10, $perfis_text, 1, 0, 'L', true);
                    $this->CellUTF8(55, 10, date('d/m/Y H:i', strtotime($row['data_selecao'])), 1, 1, 'C', true);
                    break;
            }
            $fill = !$fill;
        }
    }
}

try {
    // Conexão com o banco de dados
    $conn = getConnection();

    // Processar filtros
    $tipo_relatorio = $_POST['tipo_relatorio'] ?? 'processo_seletivo';
    $curso = $_POST['curso_selecao'] ?? 'todos';

    // Construir a query base de acordo com o tipo de relatório
    switch ($tipo_relatorio) {
        case 'processo_seletivo':
            $sql = "SELECT c.*, 
                    GROUP_CONCAT(DISTINCT s.perfis_selecionados) as perfis_selecionados,
                    COUNT(DISTINCT CASE WHEN s.status = 'alocado' THEN s.id_aluno END) as total_alocados
                    FROM concedentes c 
                    LEFT JOIN selecao s ON c.id = s.id_concedente
                    GROUP BY c.id
                    ORDER BY c.nome ASC";
            if ($curso !== 'todos') {
                $sql .= " HAVING perfis_selecionados LIKE ?";
            }
            break;
        case 'inscricoes':
            $sql = "SELECT s.*, a.nome as aluno_nome, a.curso as aluno_curso,
                    c.nome as concedente_nome, s.perfis_selecionados
                    FROM selecao s 
                    JOIN aluno a ON s.id_aluno = a.id 
                    JOIN concedentes c ON s.id_concedente = c.id 
                    WHERE 1=1";
            if ($curso !== 'todos') {
                $sql .= " AND a.curso = ?";
            }
            $sql .= " ORDER BY s.hora DESC";
            break;
        case 'alunos_alocados':
            $sql = "SELECT a.nome as aluno_nome, a.curso as aluno_curso,
                    c.nome as concedente_nome, s.perfis_selecionados,
                    s.hora as data_selecao 
                    FROM aluno a 
                    JOIN selecao s ON a.id = s.id_aluno 
                    JOIN concedentes c ON s.id_concedente = c.id 
                    WHERE s.status = 'alocado'";
            if ($curso !== 'todos') {
                $sql .= " AND a.curso = ?";
            }
            $sql .= " ORDER BY s.hora DESC";
            break;
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Erro ao preparar a query: " . $conn->error);
    }

    if ($curso !== 'todos') {
        $stmt->bind_param("s", $curso);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Criar PDF
    $pdf = new PDF('L'); // Landscape
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Configurar informação do filtro
    $pdf->filtro_info = 'Tipo de Relatório: ' . ucfirst(str_replace('_', ' ', $tipo_relatorio));
    if ($curso !== 'todos') {
        $curso_formatado = isset($cursos[$curso]) ? $cursos[$curso] : ucfirst($curso);
        $pdf->filtro_info .= ' | Curso: ' . $curso_formatado;
    }

    // Add table
    $pdf->TableHeader($tipo_relatorio);
    $pdf->TableContent($result, $tipo_relatorio, $cursos);

    // Limpar qualquer saída anterior
    ob_clean();

    // Nome do arquivo
    $filename = 'relatorio_selecoes_' . date('Y-m-d_H-i-s') . '.pdf';

    // Enviar o PDF para o navegador
    $pdf->Output('I', $filename);

} catch (Exception $e) {
    // Limpar qualquer saída anterior em caso de erro
    ob_clean();
    die('Erro ao gerar relatório: ' . $e->getMessage());
}

// Limpar o buffer de saída
ob_end_flush();
?> 