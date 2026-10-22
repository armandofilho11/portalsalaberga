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
                SUM(m.animacao) as total_animacao,
                SUM(m.vestimenta) as total_vestimenta,
                SUM(m.identidade_curso) as total_identidade
            FROM cursos c 
            INNER JOIN tarefa_03_mascote m ON m.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY (SUM(m.animacao) + SUM(m.vestimenta) + SUM(m.identidade_curso)) DESC;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 03: Mascote'), 0, 1, 'C');

        // Título da primeira tabela
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->SetY(180);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Detalhamento por Critério'), 0, 1, 'C');

        // Configurações da primeira tabela
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetTextColor(0, 0, 0);

        // Cabeçalho da primeira tabela
        $fpdf->SetY(220);
        $fpdf->SetX(100);
        $fpdf->Cell(100, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Animação'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Vestimenta'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Identidade Curso'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results as $row) {
            $fpdf->SetX(100);
            $fpdf->Cell(100, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_animacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_vestimenta']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_identidade']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Título da segunda tabela
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Ln(20);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Soma Total dos Critérios por Curso'), 0, 1, 'C');
        $fpdf->Ln(20);
        // Configurações da segunda tabela
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetTextColor(0, 0, 0);

        // Cabeçalho da segunda tabela
        $fpdf->SetX(130);
        $fpdf->Cell(120, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Soma Total'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Pontuação'), 1, 1, 'C', true);

        // Dados da segunda tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        $rank = 1;
        foreach ($results as $row) {
            $total = $row['total_animacao'] + $row['total_vestimenta'] + $row['total_identidade'];
            $bonus = 0;
            switch ($rank) {
                case 1:
                    $bonus = 500;
                    break;
                case 2:
                    $bonus = 450;
                    break;
                case 3:
                    $bonus = 400;
                    break;
                case 4:
                    $bonus = 350;
                    break;
                case 5:
                    $bonus = 300;
                    break;
                default:
                    $bonus = 0;
                    break;
            }
            $fpdf->SetX(130);
            $fpdf->Cell(120, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($total), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($bonus), 1, 1, 'C', $fill);
            $fill = !$fill;
            $rank++;
        }

        // Segunda página: Relatório detalhado por avaliador
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Consulta SQL para a segunda página
        $query_original = "
            SELECT 
                c.nome_curso, 
                a.nome, 
                m.animacao, 
                m.vestimenta, 
                m.identidade_curso
            FROM cursos c 
            INNER JOIN tarefa_03_mascote m ON m.curso_id = c.curso_id 
            INNER JOIN avaliadores a ON a.id = m.id_avaliador
            ORDER BY c.curso_id, a.nome;
        ";
        $stmt_original = $this->connect->query($query_original);
        $results_original = $stmt_original->fetchAll(PDO::FETCH_ASSOC);

        // Título da tabela na segunda página
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Relatório Detalhado por Avaliador'), 0, 1, 'C');

        // Configurações da tabela
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetTextColor(0, 0, 0);

        // Cabeçalho da tabela
        $fpdf->SetY(170);
        $fpdf->SetX(70);
        $fpdf->Cell(100, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(120, 20, utf8_decode('Avaliador'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Animação'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Vestimenta'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Identidade'), 1, 1, 'C', true);

        // Dados da tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results_original as $row) {
            $avaliador_nome = $row['nome'];
            if ($avaliador_nome == 'Brenda Kathellen Melo de Almeida') {
                $avaliador_nome = 'Avaliador 1';
            }
            if ($avaliador_nome == 'Sigliany Freires Lemos') {
                $avaliador_nome = 'Avaliador 4';
            }
            if ($avaliador_nome == 'JAMILYS GOSSON VIANA COLOMBO') {
                $avaliador_nome = 'Avaliador 2';
            }
            if ($avaliador_nome == 'Kananda Beatriz Pinto de Sena') {
                $avaliador_nome = 'Avaliador 3';
            }
            $fpdf->SetX(70);
            $fpdf->Cell(100, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(120, 20, utf8_decode($avaliador_nome), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['animacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['vestimenta']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['identidade_curso']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }
        // Gera o PDF
        $fpdf->Output('relatorio_mascote.pdf', 'I');
    }
}

$relatorio = new PDF();
?>