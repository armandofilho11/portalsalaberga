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
                SUM(p.adequacao_tema) as total_adequacao,
                SUM(p.letra_adaptada) as total_letra,
                SUM(p.diccao_clareza_entonacao) as total_diccao,
                SUM(p.desempenho_artistico) as total_desempenho,
                SUM(p.trilha_sonora_sincronia) as total_trilha,
                SUM(p.criatividade_originalidade) as total_criatividade
            FROM cursos c 
            INNER JOIN tarefa_07_parodia p ON p.curso_id = c.curso_id 
            GROUP BY c.nome_curso
            ORDER BY (SUM(p.adequacao_tema) + SUM(p.letra_adaptada) + SUM(p.diccao_clareza_entonacao) + SUM(p.desempenho_artistico) + SUM(p.trilha_sonora_sincronia) + SUM(p.criatividade_originalidade)) DESC;
        ";
        $stmt = $this->connect->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 07: Paródia'), 0, 1, 'C');

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
        $fpdf->SetX(20);
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Adequação'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Letra'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Dicção'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Desempenho'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Trilha'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Criatividade'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results as $row) {
            $fpdf->SetX(20);
            $fpdf->Cell(80, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_adequacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_letra']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_diccao']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_desempenho']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_trilha']), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($row['total_criatividade']), 1, 1, 'C', $fill);
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
            $total = $row['total_adequacao'] + $row['total_letra'] + $row['total_diccao'] + $row['total_desempenho'] + $row['total_trilha'] + $row['total_criatividade'];
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
                p.adequacao_tema, 
                p.letra_adaptada, 
                p.diccao_clareza_entonacao, 
                p.desempenho_artistico, 
                p.trilha_sonora_sincronia, 
                p.criatividade_originalidade
            FROM cursos c 
            INNER JOIN tarefa_07_parodia p ON p.curso_id = c.curso_id 
            INNER JOIN avaliadores a ON a.id = p.id_avaliador
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
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(90, 20, utf8_decode('Avaliador'), 1, 0, 'C', true);
        $fpdf->Cell(70, 20, utf8_decode('Adequação'), 1, 0, 'C', true);
        $fpdf->Cell(50, 20, utf8_decode('Letra'), 1, 0, 'C', true);
        $fpdf->Cell(50, 20, utf8_decode('Dicção'), 1, 0, 'C', true);
        $fpdf->Cell(70, 20, utf8_decode('Desempenho'), 1, 0, 'C', true);
        $fpdf->Cell(70, 20, utf8_decode('Trilha'), 1, 0, 'C', true);
        $fpdf->Cell(70, 20, utf8_decode('Criatividade'), 1, 1, 'C', true);

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
            $fpdf->SetX(25);
            $fpdf->Cell(80, 20, utf8_decode($row['nome_curso']), 1, 0, 'C', $fill);
            $fpdf->Cell(90, 20, utf8_decode($avaliador_nome), 1, 0, 'C', $fill);
            $fpdf->Cell(70, 20, utf8_decode($row['adequacao_tema']), 1, 0, 'C', $fill);
            $fpdf->Cell(50, 20, utf8_decode($row['letra_adaptada']), 1, 0, 'C', $fill);
            $fpdf->Cell(50, 20, utf8_decode($row['diccao_clareza_entonacao']), 1, 0, 'C', $fill);
            $fpdf->Cell(70, 20, utf8_decode($row['desempenho_artistico']), 1, 0, 'C', $fill);
            $fpdf->Cell(70, 20, utf8_decode($row['trilha_sonora_sincronia']), 1, 0, 'C', $fill);
            $fpdf->Cell(70, 20, utf8_decode($row['criatividade_originalidade']), 1, 1, 'C', $fill);
            $fill = !$fill;
        }
        // Gera o PDF
        $fpdf->Output('relatorio_parodia.pdf', 'I');
    }
}

$relatorio = new PDF();
?>