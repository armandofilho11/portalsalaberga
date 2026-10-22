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

        // Primeira página: Tabelas existentes
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Consulta SQL para a primeira página
        $query = "
            SELECT 
                c.nome_curso,
                SUM(cor.adequacao_tema) as total_adequacao,
                SUM(cor.estrutura_cordel) as total_estrutura,
                SUM(cor.declamacao) as total_declamacao,
                SUM(cor.criatividade) as total_criatividade,
                SUM(cor.apresentacao_impressa) as total_apresentacao
            FROM cursos c 
            INNER JOIN tarefa_06_cordel cor ON cor.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY (SUM(cor.adequacao_tema) + SUM(cor.estrutura_cordel) + SUM(cor.declamacao) + SUM(cor.criatividade) + SUM(cor.apresentacao_impressa)) DESC;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Título da primeira tabela
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 06: Cordel'), 0, 1, 'C');

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
        $fpdf->Cell(70, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Adequação'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Estrutura Cordel'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Declamação'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Criatividade'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Apresentação'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results as $row) {
            $fpdf->SetX(40);
            $fpdf->Cell(70, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_adequacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_estrutura']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_declamacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_criatividade']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($row['total_apresentacao']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Título da segunda tabela
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Ln(20);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Soma Total dos Critérios por Curso'), 0, 1, 'C');

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
            $total = $row['total_adequacao'] + $row['total_estrutura'] + $row['total_declamacao'] + $row['total_criatividade'] + $row['total_apresentacao'];
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
            $fpdf->SetX(120);
            $fpdf->Cell(120, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($total), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($bonus), 1, 1, 'C', $fill);
            $fill = !$fill;
            $rank++;
        }

        // Segunda página: Relatório original com condição para nomes dos avaliadores
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Consulta SQL para a segunda página (relatório original)
        $query_original = "
            SELECT 
                c.nome_curso, 
                a.nome, 
                cor.adequacao_tema, 
                cor.estrutura_cordel, 
                cor.declamacao, 
                cor.criatividade, 
                cor.apresentacao_impressa 
            FROM cursos c 
            INNER JOIN tarefa_06_cordel cor ON cor.curso_id = c.curso_id 
            INNER JOIN avaliadores a ON a.id = cor.id_avaliador
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
        $fpdf->SetX(25);
        $fpdf->Cell(70, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Avaliador'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Adequação'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Estrutura'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Declamação'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Criatividade'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Apresentação'), 1, 1, 'C', true);

        // Dados da tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results_original as $row) {
            $avaliador_nome = $row['nome'];
            if ($avaliador_nome == 'Brenda Kathellen Melo de Almeida') {
                $avaliador_nome = 'Avaliador 1';
            }
            if ($avaliador_nome == 'JAMILYS GOSSON VIANA COLOMBO') {
                $avaliador_nome = 'Avaliador 2';
            }
            if ($avaliador_nome == 'Kananda Beatriz Pinto de Sena') {
                $avaliador_nome = 'Avaliador 3';
            }
            if ($avaliador_nome == 'Sigliany Freires Lemos') {
                $avaliador_nome = 'Avaliador 4';
            }
            $fpdf->SetX(25);
            $fpdf->Cell(70, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($avaliador_nome), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['adequacao_tema']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['estrutura_cordel']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['declamacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['criatividade']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['apresentacao_impressa']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }
        $fpdf->Output('relatorio_cordel.pdf', 'I');
    }
}

$relatorio = new PDF();
?>