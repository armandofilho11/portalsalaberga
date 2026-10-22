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
        $query_sum = "
            SELECT 
                c.nome_curso,
                SUM(s.adequacao_tema) as total_adequacao,
                SUM(s.qualidade_conteudo) as total_qualidade,
                SUM(s.ambientacao_criatividade) as total_criatividade,
                SUM(s.didatica_clareza) as total_didatica,
                SUM(s.trabalho_equipe) as total_equipe,
                SUM(s.sustentabilidade_execucao) as total_sustentabilidade
            FROM cursos c 
            INNER JOIN tarefa_13_sala_tematica s ON s.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY (SUM(s.adequacao_tema) + SUM(s.qualidade_conteudo) + SUM(s.ambientacao_criatividade) + 
                      SUM(s.didatica_clareza) + SUM(s.trabalho_equipe) + SUM(s.sustentabilidade_execucao)) DESC;
        ";
        $stmt_sum = $this->connect->query($query_sum);
        $results_sum = $stmt_sum->fetchAll(PDO::FETCH_ASSOC);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 13: Sala Temática'), 0, 1, 'C');

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
        $fpdf->SetX(15);
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Adequação'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Qualidade'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Criatividade'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Didática'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Equipe'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Sustentabilidade'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results_sum as $row) {
            $fpdf->SetX(15);
            $fpdf->Cell(80, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_adequacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_qualidade']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_criatividade']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_didatica']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_equipe']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_sustentabilidade']), 1, 1, 'C', $fill);
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
        $fpdf->SetX(170);
        $fpdf->Cell(120, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Pontuação'), 1, 1, 'C', true);

        // Dados da segunda tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        $rank = 1;
        foreach ($results_sum as $row) {
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
            $fpdf->SetX(170);
            $fpdf->Cell(120, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($bonus), 1, 1, 'C', $fill);
            $fill = !$fill;
            $rank++;
        }

        // Segunda página: Relatório detalhado por avaliador
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Consulta SQL para a segunda página
        $query = "
            SELECT 
                c.nome_curso, 
                a.nome, 
                s.adequacao_tema, 
                s.qualidade_conteudo, 
                s.ambientacao_criatividade, 
                s.didatica_clareza, 
                s.trabalho_equipe, 
                s.sustentabilidade_execucao
            FROM cursos c 
            INNER JOIN tarefa_13_sala_tematica s ON s.curso_id = c.curso_id 
            INNER JOIN avaliadores a ON a.id = s.id_avaliador
            ORDER BY c.curso_id, a.nome;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        $fpdf->SetX(5);
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(130, 20, utf8_decode('Avaliador'), 1, 0, 'C', true);
        $fpdf->Cell(60, 20, utf8_decode('Adequação'), 1, 0, 'C', true);
        $fpdf->Cell(60, 20, utf8_decode('Qualidade'), 1, 0, 'C', true);
        $fpdf->Cell(70, 20, utf8_decode('Criatividade'), 1, 0, 'C', true);
        $fpdf->Cell(50, 20, utf8_decode('Didática'), 1, 0, 'C', true);
        $fpdf->Cell(50, 20, utf8_decode('Equipe'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Sustentabilidade'), 1, 1, 'C', true);

        // Dados da tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results as $row) {
            $fpdf->SetX(5);
            $fpdf->Cell(80, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(130, 20, utf8_decode($row['nome']), 1, 0, 'C', $fill);
            $fpdf->Cell(60, 20, utf8_decode($row['adequacao_tema']), 1, 0, 'C', $fill);
            $fpdf->Cell(60, 20, utf8_decode($row['qualidade_conteudo']), 1, 0, 'C', $fill);
            $fpdf->Cell(70, 20, utf8_decode($row['ambientacao_criatividade']), 1, 0, 'C', $fill);
            $fpdf->Cell(50, 20, utf8_decode($row['didatica_clareza']), 1, 0, 'C', $fill);
            $fpdf->Cell(50, 20, utf8_decode($row['trabalho_equipe']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['sustentabilidade_execucao']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }
        // Gera o PDF
        $fpdf->Output('relatorio_sala_tematica.pdf', 'I');
    }
}

$relatorio = new PDF();
?>