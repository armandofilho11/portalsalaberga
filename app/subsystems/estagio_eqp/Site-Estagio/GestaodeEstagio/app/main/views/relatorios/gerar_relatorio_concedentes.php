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
        $this->Cell($this->GetPageWidth() - 60, 10, utf8_decode('Relatório de Empresas Concedentes'), 0, 1, 'C');
        
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

    function TableHeader() {
        $this->SetFont('Arial', 'B', 11); // Fonte maior para cabeçalho da tabela
        $this->SetFillColor($this->headerColor[0], $this->headerColor[1], $this->headerColor[2]);
        $this->SetTextColor($this->headerTextColor[0], $this->headerTextColor[1], $this->headerTextColor[2]);
        $this->SetDrawColor($this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
        $this->SetLineWidth(0.3);
        
        // Ajustar larguras das colunas
        $this->CellUTF8(70, 12, 'Empresa', 1, 0, 'C', true);
        $this->CellUTF8(60, 12, 'Perfis', 1, 0, 'C', true);
        $this->CellUTF8(25, 12, 'Vagas', 1, 0, 'C', true);
        $this->CellUTF8(50, 12, 'Contato', 1, 0, 'C', true);
        $this->CellUTF8(70, 12, 'Endereço', 1, 1, 'C', true);
    }

    // Dados da tabela
    function TableContent($data) {
        global $cursos;
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor($this->textColor[0], $this->textColor[1], $this->textColor[2]);
        $fill = false;

        foreach ($data as $row) {
            // Processar perfis da empresa
            $perfis_raw = $row['perfis'];
            $perfis = [];

            $decoded_perfis = json_decode($perfis_raw, true);

            if (is_array($decoded_perfis)) {
                $perfis = $decoded_perfis;
            } else if (!empty($perfis_raw)) {
                $perfis = array_filter(array_map('trim', explode(',', $perfis_raw)));
            }

            // Formatar nomes de cursos se o perfil for um curso
            foreach ($perfis as $key => $p) {
                $perfis[$key] = isset($cursos[$p]) ? $cursos[$p] : $p;
            }

            $perfis_text = !empty($perfis) ? implode(', ', array_unique($perfis)) : 'Nenhum perfil cadastrado';

            // Alternar cores das linhas e destacar vagas
            if ($row['numero_vagas'] > 0) {
                $this->SetFillColor($this->highlightColor[0], $this->highlightColor[1], $this->highlightColor[2]);
            } else if ($fill) {
                $this->SetFillColor($this->alternateColor[0], $this->alternateColor[1], $this->alternateColor[2]);
            } else {
                $this->SetFillColor(255, 255, 255);
            }

            $this->CellUTF8(70, 10, $row['nome'], 1, 0, 'L', true);
            $this->CellUTF8(60, 10, $perfis_text, 1, 0, 'L', true);
            $this->CellUTF8(25, 10, $row['numero_vagas'], 1, 0, 'C', true);
            $this->CellUTF8(50, 10, $row['contato'], 1, 0, 'L', true);
            $this->CellUTF8(70, 10, $row['endereco'], 1, 1, 'L', true);
            $fill = !$fill;
        }
    }
}

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");

    // Processar filtros
    $filtro = $_POST['filtro_concedente'] ?? 'todos';
    $perfil = $_POST['perfil'] ?? '';
    $endereco = $_POST['endereco'] ?? '';

    // Construir a query base
    $sql = "SELECT c.*, COALESCE((SELECT GROUP_CONCAT(DISTINCT JSON_UNQUOTE(s2.perfis_selecionados)) FROM selecao s2 WHERE s2.id_concedente = c.id), '') AS perfis_selecionados FROM concedentes c";
    
    $params = [];
    if ($filtro === 'perfil' && !empty($perfil)) {
        $perfil_escaped = str_replace(['%', '_'], ['\\%', '\\_'], $perfil);
        $sql .= " WHERE c.perfis LIKE ? OR c.perfis LIKE ?";
        $params[] = "%\"" . $perfil_escaped . "\"%";
        $params[] = "%" . $perfil_escaped . "%";
    } elseif ($filtro === 'endereco' && !empty($endereco)) {
        $sql .= " WHERE c.endereco LIKE ?";
        $params[] = "%$endereco%";
    }

    $sql .= " ORDER BY c.nome ASC";

    $stmt = $pdo->prepare($sql);
    if (!empty($params)) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Criar PDF
    $pdf = new PDF('L'); // Landscape
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Configurar informação do filtro
    if ($filtro !== 'todos') {
        switch ($filtro) {
            case 'perfil':
                // Formatar nome do curso se o perfil for um curso
                $curso_formatado = isset($cursos[$perfil]) ? $cursos[$perfil] : $perfil;
                $pdf->filtro_info = 'Filtrado por Perfil: ' . $curso_formatado;
                break;
            case 'endereco':
                $pdf->filtro_info = 'Filtrado por Endereço: ' . $endereco;
                break;
        }
    }

    // Add table
    $pdf->TableHeader();
    $pdf->TableContent($result);

    // Nome do arquivo
    $filename = 'relatorio_concedentes_' . date('Y-m-d_H-i-s') . '.pdf';

    // Enviar o PDF para o navegador
    $pdf->Output('I', $filename);

} catch (Exception $e) {
    die('Erro ao gerar relatório: ' . $e->getMessage());
}
?> 