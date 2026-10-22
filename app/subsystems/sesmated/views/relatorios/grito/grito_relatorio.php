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

        // Primeira página: Tabelas com somatório e bônus
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Consulta SQL para a primeira página
        $query = "
            SELECT 
                c.nome_curso,
                SUM(g.pontuacao) as total_pontuacao
            FROM cursos c 
            INNER JOIN tarefa_02_grito_guerra g ON g.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY SUM(g.pontuacao) DESC;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 02: Grito de Guerra'), 0, 1, 'C');

        // Título da segunda tabela
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Ln(20);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Soma Total dos Critérios por Curso'), 0, 1, 'C');
        $fpdf->Ln(20);
        // Configurações da segunda tabela
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetTextColor(0, 0, 0);

        // Cabeçalho da segunda tabela
        $fpdf->SetX(180);
        $fpdf->Cell(120, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Pontuação'), 1, 1, 'C', true);

        // Dados da segunda tabela
        $fpdf->SetFont('Arial', '', 10);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        $rank = 1;
        foreach ($results as $row) {
            $total = $row['total_pontuacao'];
            $bonus = 0;
            switch ($rank) {
                case 1:
                    $bonus = 500;
                    break;
                case 2:
                    $bonus = 500;
                    break;
                case 3:
                    $bonus = 500;
                    break;
                case 4:
                    $bonus = 500;
                    break;
                case 5:
                    $bonus = 500;
                    break;
                default:
                    $bonus = 0;
                    break;
            }
            $fpdf->SetX(180);
            $fpdf->Cell(120, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($bonus), 1, 1, 'C', $fill);
            $fill = !$fill;
            $rank++;
        }
        // Gera o PDF
        $fpdf->Output('relatorio_grito_de_guerra.pdf', 'I');
    }
}

$relatorio = new PDF();
?>