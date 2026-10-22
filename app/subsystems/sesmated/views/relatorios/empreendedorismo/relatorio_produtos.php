<?php
require_once('../../../config/connect.php');
require_once('../../../assets/fpdf/fpdf.php');
require_once('../../../../../main/models/sessions.php');
$session = new sessions();
$session->autenticar_session();
class PDF extends connect
{
    public function __construct()
    {
        parent::__construct();
        $this->main();
    }

    public function main()
    {
        $fpdf = new FPDF('P', 'pt', 'A4');
        $fpdf->AliasNbPages();

        // Adiciona uma página
        $fpdf->AddPage();

        // Adiciona o fundo, ajustando as dimensões
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Consulta SQL (corrigido o alias da tabela)
        $query = "
            SELECT c.nome_curso, p.nome_produto, p.valor_unitario, p.quantidade
            FROM cursos c 
            INNER JOIN produtos p ON p.curso_id = c.curso_id 
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Configurações da tabela
        $fpdf->SetFont('Arial', 'B', 10); // Tamanho da fonte reduzido para caber mais colunas
        $fpdf->SetFillColor(255, 255, 255); // Fundo branco para o cabeçalho
        $fpdf->SetTextColor(0, 0, 0); // Texto preto

        // Cabeçalho da tabela com larguras ajustadas
        $fpdf->SetY(150);
        $colCurso = 80;
        $colProduto = 140;
        $colUnit = 90;
        $colQtd = 60;
        $colSubtotal = 80;
        $tableWidth = $colCurso + $colProduto + $colUnit + $colQtd + $colSubtotal;
        $xTable = ($fpdf->GetPageWidth() - $tableWidth) / 2;
        $fpdf->SetX($xTable);
        $fpdf->Cell($colCurso, 20, utf8_decode('Curso'), 1, 0, 'L', true);
        $fpdf->Cell($colProduto, 20, utf8_decode('Nome do Produto'), 1, 0, 'L', true);
        $fpdf->Cell($colUnit, 20, utf8_decode('Valor Unitário'), 1, 0, 'L', true);
        $fpdf->Cell($colQtd, 20, utf8_decode('Quantidade'), 1, 0, 'L', true);
        $fpdf->Cell($colSubtotal, 20, utf8_decode('Subtotal'), 1, 1, 'L', true);

        // Dados da tabela
        $fpdf->SetFont('Arial', '', 8); // Fonte menor para os dados
        $fill = false;
        $cursoAtual = null;
        $totalCurso = 0;
        foreach ($results as $i => $row) {
            $subtotal = $row['valor_unitario'] * $row['quantidade'];
            $fpdf->SetX($xTable);
            if ($fill) {
                $fpdf->SetFillColor(210, 210, 210); // cinza mais escuro
            } else {
                $fpdf->SetFillColor(255, 255, 255); // branco
            }
            $fpdf->Cell($colCurso, 18, utf8_decode($row['nome_curso']), 1, 0, 'L', true);
            $fpdf->Cell($colProduto, 18, utf8_decode($row['nome_produto']), 1, 0, 'L', true);
            $fpdf->Cell($colUnit, 18, 'R$ ' . number_format($row['valor_unitario'], 2, ',', '.'), 1, 0, 'L', true);
            $fpdf->Cell($colQtd, 18, $row['quantidade'], 1, 0, 'L', true);
            $fpdf->Cell($colSubtotal, 18, 'R$ ' . number_format($subtotal, 2, ',', '.'), 1, 1, 'L', true);
            $totalCurso += $subtotal;
            $cursoAtual = $row['nome_curso'];
            $fill = !$fill;
            // Se for o último registro ou o próximo for de outro curso, imprime o totalizador
            $next = $results[$i+1]['nome_curso'] ?? null;
            if ($next !== $cursoAtual) {
                $fpdf->SetX($xTable);
                $fpdf->SetFont('Arial', 'B', 8);
                $fpdf->SetFillColor(255, 255, 255); // totalizador sempre branco
                $fpdf->Cell($colCurso + $colProduto + $colUnit + $colQtd, 18, utf8_decode('Total ' . $cursoAtual), 1, 0, 'R', true);
                $fpdf->Cell($colSubtotal, 18, 'R$ ' . number_format($totalCurso, 2, ',', '.'), 1, 1, 'L', true);
                $fpdf->SetFont('Arial', '', 8);
                $fpdf->Ln(10); // aumenta a distância após o total
                $totalCurso = 0;
            }
        }

        // Gera o PDF
        $fpdf->Output('relatorio_produtos.pdf', 'I');
    }
}

$relatorio = new PDF();
?>