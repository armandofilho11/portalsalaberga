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
        $this->table1 = $table['salaberga_estoque'][1];
        $this->table2 = $table['salaberga_estoque'][2];
        $this->table3 = $table['salaberga_estoque'][3];
        $this->table4 = $table['salaberga_estoque'][4];
        $this->relatorio_perdas_geral();
    }

    public function relatorio_perdas_geral()
    {
        $pdf = new FPDF('L', 'cm', 'A4');
        $pdf->AddPage();

        // Add image as background
        $pdf->Image('../../assets/images/header.png', 0, 0, $pdf->GetPageWidth(),2, 'png', '', 0.1);

        // Add date on the top right
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $data_geracao = date('d/m/Y H:i:s');
        $pdf->SetXY($pdf->GetPageWidth() - 7.2, 18.39);
        $pdf->Cell(4, 0.6, utf8_decode('GERADO EM: ' . strtoupper($data_geracao)), 0, 0, 'R');

        // Fetch perdas with product details
        $query = $this->connect->query("SELECT p.nome_produto, pp.quantidade, pp.tipo, pp.data 
                                        FROM $this->table3 pp 
                                        LEFT JOIN $this->table4 p ON pp.id_produto = p.id 
                                        ORDER BY pp.data");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calculate summary
        $total_perdas = count($resultado);
        $total_quantidade_perdida = array_sum(array_column($resultado, 'quantidade'));

        // Add summary on the left side
        $y_position = 5;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetY(5.5);
        $pdf->SetX(2.5);
        $pdf->Cell(3, 0.8, utf8_decode('RESUMO:'), 0, 1, 'L');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetY(6.2);
        $pdf->SetX(2.5);
        $pdf->Cell(3, 0.6, utf8_decode('TOTAL DE PERDAS: ' . $total_perdas), 0, 1, 'L');
        $pdf->SetX(2.5);
        $pdf->Cell(3, 0.6, utf8_decode('TOTAL QUANTIDADE PERDIDA: ' . $total_quantidade_perdida), 0, 1, 'L');
        $y_position += 2.5;

        $page_height = $pdf->GetPageHeight();
        $margin_bottom = 2;

        // Group perdas by date
        $perdas_por_data = [];
        foreach ($resultado as $perda) {
            $data = $perda['data'];
            if (!isset($perdas_por_data[$data])) {
                $perdas_por_data[$data] = [];
            }
            $perdas_por_data[$data][] = $perda;
        }

        foreach ($perdas_por_data as $data => $perdas) {
            // Check if we need a new page
            if ($y_position > $page_height - 6) {
                $pdf->AddPage();
                $pdf->Image('../../assets/images/header.png', 0, 0, $pdf->GetPageWidth(), 2, 'png', '', 0.1);

                // Repeat date on new page
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY($pdf->GetPageWidth() - 7.2, 6.8);
                $pdf->Cell(4, 0.6, utf8_decode('GERADO EM: ' . strtoupper($data_geracao)), 0, 0, 'R');

                $y_position = 4;
                $y_position += 4;
            }

            // Table header for perdas
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(255, 221, 119);
            $pdf->SetY($y_position);
            $pdf->SetX(2.5);
            $pdf->Cell(14, 0.8, utf8_decode('NOME DO PRODUTO'), 1, 0, 'C', true);
            $pdf->Cell(3.5, 0.8, utf8_decode('QUANTIDADE'), 1, 0, 'C', true);
            $pdf->Cell(4.5, 0.8, utf8_decode('TIPO DE PERDA'), 1, 0, 'C', true);
            $pdf->Cell(3, 0.8, utf8_decode('DATA'), 1, 1, 'C', true);
            $y_position += 0.8;

            // Perdas for this date
            $pdf->SetFont('Arial', '', 11);
            $fill = false;
            foreach ($perdas as $perda) {
                if ($y_position > $page_height - 3) {
                    $pdf->AddPage();
                    $pdf->Image('../../assets/images/header.png', 0, 0, $pdf->GetPageWidth(), 2, 'png', '', 0.1);

                    // Repeat date on new page
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY($pdf->GetPageWidth() - 5, 1);
                    $pdf->Cell(4, 0.6, utf8_decode('GERADO EM: ' . strtoupper($data_geracao)), 0, 0, 'R');

                    $y_position = 1;

                    // Repeat summary on new page
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetX(1);
                    $pdf->Cell(3, 0.8, utf8_decode('RESUMO:'), 0, 1, 'L');

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetX(1);
                    $pdf->Cell(3, 0.6, utf8_decode('TOTAL DE PERDAS: ' . $total_perdas), 0, 1, 'L');
                    $pdf->SetX(1);
                    $pdf->Cell(3, 0.6, utf8_decode('TOTAL QUANTIDADE PERDIDA: ' . $total_quantidade_perdida), 0, 1, 'L');
                    $y_position += 2.5;

                    // Repeat table header
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->SetFillColor(255, 221, 119);
                    $pdf->SetY($y_position);
                    $pdf->SetX(2.5);
                    $pdf->Cell(14, 0.8, utf8_decode('NOME DO PRODUTO'), 1, 0, 'C', true);
                    $pdf->Cell(3.5, 0.8, utf8_decode('QUANTIDADE'), 1, 0, 'C', true);
                    $pdf->Cell(4.5, 0.8, utf8_decode('TIPO DE PERDA'), 1, 0, 'C', true);
                    $pdf->Cell(3, 0.8, utf8_decode('DATA'), 1, 1, 'C', true);
                    $y_position += 0.8;
                    $pdf->SetFont('Arial', '', 11);
                    $fill = false;
                }

                $pdf->SetY($y_position);
                $pdf->SetX(2.5);
                $cor1 = $fill ? 230 : 255;
                $cor2 = $fill ? 230 : 255;
                $cor3 = $fill ? 230 : 255;
                $pdf->SetFillColor($cor1, $cor2, $cor3);

                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(14, 0.8, utf8_decode(strtoupper($perda['nome_produto'])), 1, 0, 'L', true);
                $pdf->Cell(3.5, 0.8, utf8_decode(strtoupper($perda['quantidade'])), 1, 0, 'C', true);
                $pdf->Cell(4.5, 0.8, utf8_decode(strtoupper($perda['tipo'])), 1, 0, 'L', true);
                $pdf->Cell(3, 0.8, utf8_decode(strtoupper(date('d/m/Y', strtotime($perda['data'])))), 1, 1, 'C', true);

                $y_position += 0.8;
                $fill = !$fill;
            }

            $y_position += 0.5;
        }

        // Output PDF
        $pdf->Output('I', 'relatorio_perdas_geral.pdf');
    }
}

$relatorio = new relatorio();