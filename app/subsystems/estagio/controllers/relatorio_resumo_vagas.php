<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');
require_once('../assets/fpdf/fpdf.php');

// Classe FPDF com suporte a UTF-8
class PDF extends FPDF {
    function __construct($orientation='P', $unit='mm', $size='A4') {
        parent::__construct($orientation, $unit, $size);
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        if (is_string($txt)) {
            $txt = mb_convert_encoding($txt, 'ISO-8859-1', 'UTF-8');
        }
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }
}

class RelatorioResumoVagas extends PDF {
    private $select_model;
    private $vagas;
    private $cores = [
        'primaria' => [0, 122, 51],    // Verde institucional
        'secundaria' => [240, 240, 240], // Cinza claro
        'destaque' => [0, 90, 40],     // Verde escuro
        'texto' => [70, 70, 70],       // Cinza escuro
        'subtitulo' => [100, 100, 100]  // Cinza médio
    ];

    public function __construct() {
        parent::__construct('P', 'mm', 'A4');
        $this->SetAutoPageBreak(true, 25);
        $this->SetMargins(15, 15, 15);
        $this->select_model = new select_model();
        $this->vagas = $this->select_model->vagas();
    }

    function Header() {
        if ($this->PageNo() == 1) {
            $this->SetFillColor(248, 248, 248);
            $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F');
            
            $this->Image('https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png', 15, 10, 25);
            
            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->SetXY(45, 15);
            $this->Cell(100, 10, ' Resumo de Vagas', 0, 0, 'L');
            
            $this->SetFont('Arial', 'I', 9);
            $this->SetTextColor(...$this->cores['subtitulo']);
            $this->SetXY(45, 25);
            $this->Cell(100, 5, 'Gerado em: ' . date('d/m/Y H:i'), 0, 0, 'L');
            
            $this->SetDrawColor(...$this->cores['primaria']);
            $this->SetLineWidth(0.5);
            $this->Line(15, 40, 195, 40);
            
            $this->SetY(45);
        } else {
            $this->SetY(15);
        }
    }

    function Footer() {
        $this->SetDrawColor(...$this->cores['primaria']);
        $this->SetLineWidth(0.3);
        $this->Line(15, $this->GetPageHeight() - 20, $this->GetPageWidth() - 15, $this->GetPageHeight() - 20);
        
        $this->SetY(-18);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(...$this->cores['subtitulo']);
        $this->Cell(0, 5, 'Página ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
        $this->Cell(0, 5, 'Sistema de Gestão de Vagas - Todos os direitos reservados', 0, 0, 'C');
    }

    public function gerarRelatorio() {
        $this->AliasNbPages();
        $this->AddPage();
        
        // Tabela de Resumo
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(...$this->cores['primaria']);
       
        
        // Cabeçalho da tabela
        $this->SetFillColor(220, 240, 230);
        $this->SetFont('Arial', 'B', 10);
        
        // Adiciona espaço à esquerda para centralizar a tabela
        $this->SetX(15 + (180 - 170) / 2);
        
        $this->Cell(100, 7, 'Empresa', 1, 0, 'L', true);
        $this->Cell(40, 7, 'Perfil', 1, 0, 'L', true);
        $this->Cell(30, 7, 'Quantidade', 1, 1, 'C', true);
        
        // Dados da tabela
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(40, 40, 40);
        
        $row_bg1 = [255, 255, 255];
        $row_bg2 = [245, 250, 245];
        $contador_linhas = 0;

        // Agrupa vagas por empresa e perfil
        $vagas_agrupadas = [];
        $total_alunos_selecionados = 0;
        $total_alunos_espera = 0;

        foreach ($this->vagas as $vaga) {
            $chave = $vaga['nome_empresa'] . '|' . $vaga['nome_perfil'];
            if (!isset($vagas_agrupadas[$chave])) {
                $vagas_agrupadas[$chave] = [
                    'empresa' => $vaga['nome_empresa'],
                    'perfil' => $vaga['nome_perfil'],
                    'quantidade' => 0,
                    'alunos_selecionados' => 0,
                    'alunos_espera' => 0
                ];
            }
            $vagas_agrupadas[$chave]['quantidade'] += $vaga['quantidade'];

            // Conta alunos selecionados e em espera
            $alunos_selecionados = $this->select_model->alunos_selecionados($vaga['id']);
            $alunos_espera = $this->select_model->alunos_espera($vaga['id']);
            
            $vagas_agrupadas[$chave]['alunos_selecionados'] += count($alunos_selecionados);
            $vagas_agrupadas[$chave]['alunos_espera'] += count($alunos_espera);
            
            $total_alunos_selecionados += count($alunos_selecionados);
            $total_alunos_espera += count($alunos_espera);
        }

        // Ordena por empresa e perfil
        uasort($vagas_agrupadas, function($a, $b) {
            $cmp = strcmp($a['empresa'], $b['empresa']);
            if ($cmp === 0) {
                return strcmp($a['perfil'], $b['perfil']);
            }
            return $cmp;
        });

        // Imprime as linhas da tabela
        foreach ($vagas_agrupadas as $vaga) {
            $bg = ($contador_linhas % 2 == 0) ? $row_bg1 : $row_bg2;
            $this->SetFillColor($bg[0], $bg[1], $bg[2]);
            
            // Adiciona espaço à esquerda para centralizar a tabela
            $this->SetX(15 + (180 - 170) / 2);
            
            $this->Cell(100, 7, $this->ajustarTexto($this->formatarEmpresa($vaga['empresa']), 55), 1, 0, 'L', true);
            $this->Cell(40, 7, $this->ajustarTexto($vaga['perfil'], 20), 1, 0, 'L', true);
            $this->Cell(30, 7, $vaga['quantidade'], 1, 1, 'C', true);
            
            $contador_linhas++;
        }

        // Totalizadores
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(220, 240, 230);
        
        // Adiciona espaço à esquerda para centralizar os totalizadores
        $this->SetX(15 + (180 - (140 + 30)) / 2); // 140mm + 30mm = 170mm (largura dos totalizadores)

        $this->Cell(140, 7, 'Total de Vagas:', 1, 0, 'L', true);
        $this->Cell(30, 7, array_sum(array_column($vagas_agrupadas, 'quantidade')), 1, 1, 'C', true);
        
        // Adiciona espaço à esquerda para centralizar os totalizadores
        $this->SetX(15 + (180 - (140 + 30)) / 2);

        $this->Cell(140, 7, 'Total de Empresas:', 1, 0, 'L', true);
        $this->Cell(30, 7, count(array_unique(array_column($vagas_agrupadas, 'empresa'))), 1, 1, 'C', true);

        // Adiciona espaço à esquerda para centralizar os totalizadores
        $this->SetX(15 + (180 - (140 + 30)) / 2);

        $this->Cell(140, 7, 'Total de Alunos Selecionados:', 1, 0, 'L', true);
        $this->Cell(30, 7, $total_alunos_selecionados, 1, 1, 'C', true);

        // Adiciona espaço à esquerda para centralizar os totalizadores
        $this->SetX(15 + (180 - (140 + 30)) / 2);

        $this->Cell(140, 7, 'Total de Alunos em Espera:', 1, 0, 'L', true);
        $this->Cell(30, 7, $total_alunos_espera, 1, 1, 'C', true);
    }

    private function ajustarTexto($texto, $tamanho) {
        if (strlen($texto) > $tamanho) {
            return substr($texto, 0, $tamanho - 3) . '...';
        }
        return $texto;
    }

    private function formatarEmpresa($empresa) {
        return mb_strtoupper($empresa, 'UTF-8');
    }
}

// Verifica autenticação
$session = new sessions();
$session->autenticar_session();

// Gera o relatório

// Desativa a exibição de erros para evitar saída prematura
ini_set('display_errors', 0);
error_reporting(0);

try {
    error_log('RelatorioResumoVagas: Inicializando o relatório...');
    $relatorio = new RelatorioResumoVagas();
    error_log('RelatorioResumoVagas: Objeto RelatorioResumoVagas criado.');
    
    error_log('RelatorioResumoVagas: Chamando gerarRelatorio...');
    $relatorio->gerarRelatorio();
    error_log('RelatorioResumoVagas: gerarRelatorio concluído.');
    
    // Limpa o buffer de saída antes de gerar o PDF
    ob_clean();
    error_log('RelatorioResumoVagas: Buffer de saída limpo. Chamando Output...');
    
    $relatorio->Output('Relatorio_Resumo_Vagas.pdf', 'I');
    error_log('RelatorioResumoVagas: Output chamado. Script deve terminar.');
    exit;
} catch (Exception $e) {
    // Restaura a exibição de erros
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    error_log('Erro ao gerar relatório: ' . $e->getMessage());
    header('Content-Type: text/html; charset=utf-8');
    echo '<div style="color: red; padding: 20px; font-family: Arial, sans-serif;">';
    echo '<h2>Erro ao gerar relatório</h2>';
    echo '<p>Ocorreu um erro ao tentar gerar o relatório. Por favor, tente novamente mais tarde.</p>';
    echo '<p>Detalhes do erro: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="javascript:history.back()">Voltar</a></p>';
    echo '</div>';
} 