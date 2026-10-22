<?php
require_once(__DIR__ . '/../../models/sessions.php');
$session = new sessions();
$session->autenticar_session();
$session->tempo_session();

require_once(__DIR__ . '/../../config/connect.php');
require_once(__DIR__ . '/../../assets/libs/FPDF/fpdf.php');

class relatorio extends connect
{
    private string $table1;
    private string $table2;
    private string $table3;
    private string $table4;

    function __construct()
    {
        parent::__construct();
        require(__DIR__ . '/../../../../../.env/tables.php');
        $this->table1 = $table['salaberga_estoque'][1]; // Categories table
        $this->table2 = $table['salaberga_estoque'][2];
        $this->table3 = $table['salaberga_estoque'][3];
        $this->table4 = $table['salaberga_estoque'][4]; // Products table
        $this->relatorio_produtos_geral();
    }

    public function relatorio_produtos_geral()
    {
        $pdf = new FPDF('L', 'cm', 'A4');
        $pdf->AddPage();

        // Add image as background
        $pdf->Image('../../assets/images/header.png', 0, 0, $pdf->GetPageWidth(), 2, 'png', '', 0.1);

        // Add date on the top right
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $data_geracao = date('d/m/Y');
        $pdf->SetXY($pdf->GetPageWidth() - 7.2, 18.5);
        $pdf->Cell(4, 0, 'GERADO EM: ' . mb_strtoupper($data_geracao, 'UTF-8'), 0, 1, 'R');

        // Get date range from POST
        $data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : '';
        $data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : '';

        // Fetch movimentacoes within date range with product details
        $query = $this->connect->prepare("SELECT p.nome_produto, m.*
                                         FROM $this->table2 m 
                                         LEFT JOIN $this->table4 p ON m.id_produtos = p.id 
                                         WHERE m.datareg BETWEEN :data_inicio AND :data_fim 
                                         ORDER BY m.datareg");
        $query->bindValue(':data_inicio', $data_inicio . ' 00:00:00');
        $query->bindValue(':data_fim', $data_fim . ' 23:59:59');
        $query->execute();
        $movimentacoes = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calculate summary (total movimentacoes)
        $total_movimentacoes = count($movimentacoes);

        // Add summary on the left side
        $y_position = 3.0;
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetY(18.39);
        $pdf->SetX(1);
        $pdf->Cell(3, 0.5, utf8_decode('TOTAL DE MOVIMENTAÇÕES: ') . $total_movimentacoes, 0, 0, 'L');
        $y_position += 0.7;

        $products_per_page = 0; // Counter for products per page

        // Table header for movimentacoes
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 221, 119);
        $pdf->SetY($y_position);
        $pdf->SetX(1);
        $pdf->Cell(11, 0.6, 'NOME', 1, 0, 'C', true);
        $pdf->Cell(2, 0.6, 'QTD', 1, 0, 'C', true);
        $pdf->Cell(5, 0.6, 'LIBERADOR', 1, 0, 'C', true);
        $pdf->Cell(5, 0.6, 'SOLICITANTE', 1, 0, 'C', true);
        $pdf->Cell(2, 0.6, 'TIPO', 1, 0, 'C', true);
        $pdf->Cell(3, 0.6, 'DATA', 1, 1, 'C', true);
        $y_position += 0.6;

        // Movimentacoes
        $pdf->SetFont('Arial', '', 9);
        $fill = false;
        foreach ($movimentacoes as $mov) {
            // Check for page break after 15 products
            if ($products_per_page >= 22) {
                $pdf->AddPage();
                $pdf->Image('../../assets/images/header.png', 0, 0, $pdf->GetPageWidth(), 2, 'png', '', 0.1);

                // Repeat summary
                $y_position = 3.0;

                $y_position += 0.7;

                // Repeat table header
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(255, 221, 119);
                $pdf->SetY($y_position);
                $pdf->SetX(1);
                $pdf->Cell(11, 0.6, 'NOME', 1, 0, 'C', true);
                $pdf->Cell(2, 0.6, 'QTD', 1, 0, 'C', true);
                $pdf->Cell(5, 0.6, 'LIBERADOR', 1, 0, 'C', true);
                $pdf->Cell(5, 0.6, 'SOLICITANTE', 1, 0, 'C', true);
                $pdf->Cell(2, 0.6, 'TIPO', 1, 0, 'C', true);
                $pdf->Cell(3, 0.6, 'DATA', 1, 1, 'C', true);
                $y_position += 0.6;
                $pdf->SetFont('Arial', '', 9);
                $fill = false;
                $products_per_page = 0; // Reset counter for new page
            }

            $pdf->SetY($y_position);
            $pdf->SetX(1);
            $cor1 = $fill ? 230 : 255;
            $cor2 = $fill ? 230 : 255;
            $cor3 = $fill ? 230 : 255;
            $pdf->SetFillColor($cor1, $cor2, $cor3);

            // Set text color to red for quantity
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(11, 0.6, utf8_decode(mb_strtoupper($mov['nome_produto'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->SetTextColor(255, 0, 0); // Red for quantity
            $pdf->Cell(2, 0.6, mb_strtoupper($mov['quantidade_retirada'], 'UTF-8'), 1, 0, 'C', true);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(5, 0.6,  utf8_decode(mb_strtoupper($mov['liberador'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell(5, 0.6,  utf8_decode(mb_strtoupper($solicitador = $mov['solicitador'] === NULL ? utf8_decode('não se aplica') : $mov['solicitador'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell(2, 0.6, utf8_decode(mb_strtoupper($mov['tipo_movimentacao'])), 1, 0, 'L', true);
            $pdf->Cell(3, 0.6, mb_strtoupper(date('d/m/Y H:i', strtotime($mov['datareg'])), 'UTF-8'), 1, 1, 'C', true);

            $y_position += 0.6;
            $fill = !$fill;
            $products_per_page++; // Increment product counter
        }

        // Output PDF
        $pdf->Output('I', 'RELATORIO_MOVIMENTACOES.PDF');
    }
}

$relatorio = new relatorio();
