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
                SUM(i.originalidade_inovacao) as total_originalidade,
                SUM(i.relevancia_aplicabilidade) as total_relevancia,
                SUM(i.viabilidade_tecnica) as total_viabilidade,
                SUM(i.sustentabilidade_socioambiental) as total_sustentabilidade,
                SUM(i.clareza_organizacao) as total_clareza
            FROM cursos c 
            INNER JOIN tarefa_11_inovacao i ON i.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY (SUM(i.originalidade_inovacao) + SUM(i.relevancia_aplicabilidade) + SUM(i.viabilidade_tecnica) + SUM(i.sustentabilidade_socioambiental) + SUM(i.clareza_organizacao)) DESC;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 11: Inovação'), 0, 1, 'C');

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
        $fpdf->SetX(40);
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Originalidade'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Relevância'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Viabilidade'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Sustentabilidade'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Clareza'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results as $row) {
            $fpdf->SetX(40);
            $fpdf->Cell(80, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_originalidade']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_relevancia']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_viabilidade']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_sustentabilidade']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_clareza']), 1, 1, 'C', $fill);
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
        $fpdf->SetX(120);
        $fpdf->Cell(120, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Soma Total'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Pontuação'), 1, 1, 'C', true);

        // Dados da segunda tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        $rank = 1;
        foreach ($results as $row) {
            $total = $row['total_originalidade'] + $row['total_relevancia'] + $row['total_viabilidade'] + $row['total_sustentabilidade'] + $row['total_clareza'];
            $bonus = 0;
            switch ($rank) {
                case 1:
                    $bonus = 1000;
                    break;
                case 2:
                    $bonus = 850;
                    break;
                case 3:
                    $bonus = 700;
                    break;
                case 4:
                    $bonus = 600;
                    break;
                case 5:
                    $bonus = 500;
                    break;
                default:
                    $bonus = 0;
                    break;
            }
            $fpdf->SetX(120);
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
                i.originalidade_inovacao, 
                i.relevancia_aplicabilidade, 
                i.viabilidade_tecnica, 
                i.sustentabilidade_socioambiental, 
                i.clareza_organizacao
            FROM cursos c 
            INNER JOIN tarefa_11_inovacao i ON i.curso_id = c.curso_id 
            INNER JOIN avaliadores a ON a.id = i.id_avaliador
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
        $fpdf->SetX(10);
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Avaliador'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Originalidade'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Relevância'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Viabilidade'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Sustentabilidade'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Clareza'), 1, 1, 'C', true);

        // Dados da tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results_original as $row) {
            $avaliador_nome = $row['nome'];
            if ($avaliador_nome == '') {
                $avaliador_nome = 'Avaliador 1';
            }
            if ($avaliador_nome == '') {
                $avaliador_nome = 'Avaliador 2';
            }
            if ($avaliador_nome == '') {
                $avaliador_nome = 'Avaliador 3';
            }
            if ($avaliador_nome == '') {
                $avaliador_nome = 'Avaliador 4';
            }
            $fpdf->SetX(10);
            $fpdf->Cell(80, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($avaliador_nome), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['originalidade_inovacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['relevancia_aplicabilidade']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['viabilidade_tecnica']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['sustentabilidade_socioambiental']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['clareza_organizacao']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }
        // Gera o PDF
        $fpdf->Output('relatorio_inovacao.pdf', 'I');
    }
}

$relatorio = new PDF();
?>