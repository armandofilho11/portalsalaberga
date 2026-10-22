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
                SUM(l.elementos_cursos) as total_elementos,
                SUM(l.entrega_a3) as total_entrega_a3,
                SUM(l.entrega_digital) as total_entrega_digital
            FROM cursos c 
            INNER JOIN tarefa_04_logomarca l ON l.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY (SUM(l.elementos_cursos) + SUM(l.entrega_a3) + SUM(l.entrega_digital)) DESC;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 04: Logomarca'), 0, 1, 'C');

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
        $fpdf->Cell(100, 20, utf8_decode('Elementos'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Entrega A3'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Entrega Digital'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results as $row) {
            $fpdf->SetX(100);
            $fpdf->Cell(100, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_elementos']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_entrega_a3']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_entrega_digital']), 1, 1, 'C', $fill);
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
        $fpdf->SetX(140);
        $fpdf->Cell(120, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Soma Total'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Pontuação'), 1, 1, 'C', true);

        // Dados da segunda tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        $rank = 1;
        foreach ($results as $row) {
            $total = $row['total_elementos'] + $row['total_entrega_a3'] + $row['total_entrega_digital'];
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
            $fpdf->SetX(140);
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
                l.elementos_cursos, 
                l.entrega_a3, 
                l.entrega_digital
            FROM cursos c 
            INNER JOIN tarefa_04_logomarca l ON l.curso_id = c.curso_id 
            INNER JOIN avaliadores a ON a.id = l.id_avaliador
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
        $fpdf->Cell(80, 20, utf8_decode('Elementos'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Entrega A3'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Entrega Digital'), 1, 1, 'C', true);

        // Dados da tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results_original as $row) {
            $avaliador_nome = $row['nome'];
            if ($avaliador_nome == 'Adriana Paula da Silva Amorim') {
                $avaliador_nome = 'Avaliador 1';
            }
            if ($avaliador_nome == 'Ana Fabiane Carvalho') {
                $avaliador_nome = 'Avaliador 2';
            }
            if ($avaliador_nome == 'Brenda Kathellen Melo de Almeida') {
                $avaliador_nome = 'Avaliador 3';
            }
            if ($avaliador_nome == 'Carlos Henrique Róseo de Paula Pessoa') {
                $avaliador_nome = 'Avaliador 4';
            }
            $fpdf->SetX(70);
            $fpdf->Cell(100, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(120, 20, utf8_decode($avaliador_nome), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['elementos_cursos']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['entrega_a3']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['entrega_digital']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Gera o PDF
        $fpdf->Output('relatorio_logo.pdf', 'I');
    }
}

$relatorio = new PDF();
?>