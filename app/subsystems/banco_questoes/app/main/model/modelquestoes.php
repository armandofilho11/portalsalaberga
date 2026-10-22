<?php
require(__DIR__ . '/../fpdf186/fpdf.php');

class PDF extends FPDF {
    private $headerText = '';
    private $evaluationInfo = ['nome' => '', 'ano' => ''];
    private $isFirstPage;
    private $currentColumn;
    private $columnWidth;
    private $columnSpacing;
    private $maxY;
    private $headerHeight;
    private $hasDrawnHeaderLine = false;
    private $contentStartY; // Nova variável para controlar onde o conteúdo começa

    function __construct() {
        parent::__construct();
        $this->isFirstPage = true;
        $this->currentColumn = 0; // 0 = esquerda, 1 = direita
        $this->columnWidth = 90; // Largura de cada coluna
        $this->columnSpacing = 10; // Espaço entre colunas
        $this->maxY = 280; // Altura máxima da página
        $this->headerHeight = 0; // Será definido após o cabeçalho
        $this->contentStartY = 0; // Será definido após o cabeçalho
    }

    function setHeaderText($text) {
        $this->headerText = $text ?? '';
    }

    function setEvaluationInfo($info) {
        if (is_array($info)) {
            $this->evaluationInfo = array_merge(['nome' => '', 'ano' => ''], $info);
        }
    }

    function Header() {
        if ($this->isFirstPage) {
            $startY = $this->GetY();

            // Logo e Cabeçalho
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, $this->normalize('EEEP Salaberga Torquato Gomes de Matos'), 0, 1, 'C');
            $this->Ln(5);

            // Informações da Avaliação
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, $this->normalize($this->evaluationInfo['nome'] ?? ''), 0, 1, 'C');
            
            // Linha horizontal
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(5);

            // Campos para preenchimento
            $this->SetFont('Arial', '', 11);
            $this->Cell(40, 8, $this->normalize('Nome do Aluno: '), 0);
            $this->Cell(150, 8, '_________________________________________________', 0);
            $this->Ln();
            
            $this->Cell(25, 8, $this->normalize('Turma: '), 0);
            $this->Cell(45, 8, $this->normalize($this->evaluationInfo['ano'] . 'º B'), 0);
            $this->Cell(25, 8, 'Data: ', 0);
            $this->Cell(40, 8, '____/____/________', 0);
            $this->Ln(15);

            // Instruções
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(0, 8, $this->normalize('Instruções:'), 0, 1);
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(0, 5, $this->normalize("• Leia atentamente cada questão antes de respondê-la;\n• Utilize caneta azul ou preta;\n• Não é permitido o uso de corretivo;\n• A avaliação deve ser respondida individualmente;\n• Todas as questões devem ser justificadas quando necessário."));
            $this->Ln(5);

            // Linha horizontal final do cabeçalho
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(10);

            // Define a altura do cabeçalho e onde o conteúdo deve começar
            $this->headerHeight = $this->GetY();
            $this->contentStartY = $this->headerHeight;
            $this->SetY($this->contentStartY);

            $this->isFirstPage = false;
            $this->SetLeftMargin(10);
            $this->currentColumn = 0;
        } else {
            $this->SetY(15);
            $this->contentStartY = 15;
        }
    }

    function switchColumn() {
        if($this->currentColumn == 0) {
            // Muda para a coluna direita
            $this->currentColumn = 1;
            $this->SetLeftMargin(10 + $this->columnWidth + $this->columnSpacing);
            $this->SetX(10 + $this->columnWidth + $this->columnSpacing);
            $this->SetY($this->contentStartY); // Usa contentStartY em vez de headerHeight
        } else {
            // Volta para a coluna esquerda na próxima página
            $this->currentColumn = 0;
            $this->SetLeftMargin(10);
            $this->AddPage();
        }
    }

    function formatQuestion($number, $question, $alternatives) {
        if (!is_array($question) || !isset($question['enunciado']) || !is_array($alternatives)) {
            return;
        }

        // Se for a primeira questão da página, desenha a linha central
        if (!$this->hasDrawnHeaderLine) {
            $this->drawColumnLine();
            $this->hasDrawnHeaderLine = true;
        }

        // Salva a posição Y atual
        $currentY = $this->GetY();
        
        // Calcula o espaço necessário
        $spaceNeeded = $this->GetStringHeight($this->columnWidth, $question['enunciado']) + 
                      (count($alternatives) * 8) + 20;

        // Se não houver espaço suficiente na coluna atual
        if ($currentY + $spaceNeeded > $this->maxY) {
            // Se estiver na primeira coluna, tenta na segunda
            if ($this->currentColumn == 0) {
                $this->switchColumn();
            } else {
                // Se já estiver na segunda coluna, nova página
                $this->switchColumn();
                $this->hasDrawnHeaderLine = false; // Reset para a nova página
            }
        }

        $this->SetFont('Arial', 'B', 11);
        $this->Cell($this->columnWidth, 10, $this->normalize("Questão " . $number), 0, 1, 'L');
        
        // Enunciado
        $this->SetFont('Arial', '', 10);
        $this->MultiCell($this->columnWidth, 5, $this->normalize($question['enunciado']), 0, 'J');
        $this->Ln(5);

        // Alternativas
        $letters = ['a)','b)','c)','d)'];
        $this->SetFont('Arial', '', 10);
        
        foreach ($alternatives as $index => $alternative) {
            if (!isset($letters[$index]) || !isset($alternative['texto'])) {
                continue;
            }

            // Se não houver espaço para a alternativa, muda de coluna
            if ($this->GetY() + 8 > $this->maxY) {
                $this->switchColumn();
            }

            // Recuo para as alternativas
            $currentX = $this->GetX();
            
            // Letra da alternativa em negrito
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(8, 6, $letters[$index], 0);
            
            // Texto da alternativa
            $this->SetFont('Arial', '', 10);
            $this->MultiCell($this->columnWidth - 8, 6, $this->normalize($alternative['texto']), 0, 'J');
            $this->Ln(2);
        }
        $this->Ln(8);
    }

    // Função para desenhar a linha divisória
    function drawColumnLine() {
        $startY = $this->contentStartY; // Usa contentStartY em vez de GetY()
        $this->Line(
            10 + $this->columnWidth + ($this->columnSpacing/2), 
            $startY,
            10 + $this->columnWidth + ($this->columnSpacing/2), 
            $this->maxY
        );
    }

    // Função auxiliar para calcular altura do texto
    function GetStringHeight($width, $txt) {
        $cw = &$this->CurrentFont['cw'];
        $w = 0;
        $lines = 1;
        $text = str_replace("\r",'',$txt);
        $chars = str_split($text);
        foreach($chars as $char) {
            $w += $cw[$char] ?? 600;
            if($w > $width*1000) {
                $lines++;
                $w = 0;
            }
        }
        return $lines * 5; // 5 é a altura da linha
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $this->normalize('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Função para normalizar caracteres especiais
    function normalize($string) {
        $from = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
        $to   = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
        $string = strtr(utf8_decode($string), utf8_decode($from), $to);
        return $string;
    }
}

?>