<?php
require_once('../../../assets/fpdf/fpdf.php');
require_once('../../../config/connect.php');
require_once('../../../../../main/models/sessions.php');
$session = new sessions();
$session->autenticar_session();

// Definindo constantes
define('TURMAS', [
    '1A' => 230,
    '2A' => 245,
    '3A' => 235,
    '1B' => 230,
    '2B' => 230,
    '3B' => 245,
    '1C' => 225,
    '2C' => 230,
    '3C' => 240,
    '1D' => 230,
    '2D' => 235,
    '3D' => 250
]);

define('DINHEIRO', [
    '1A' => 230 * 2,
    '2A' => 245 * 2,
    '3A' => 235 * 2,
    '1B' => 230 * 2,
    '2B' => 230 * 2,
    '3B' => 245 * 2,
    '1C' => 225 * 2,
    '2C' => 230 * 2,
    '3C' => 240 * 2,
    '1D' => 230 * 2,
    '2D' => 235 * 2,
    '3D' => 250 * 2
]);

define('TOTAL_RIFAS', [
    'Enfermagem' => 710,
    'Informática' => 705,
    'Meio Ambiente' => 230,
    'Administração' => 465,
    'Edificações' => 715
]);

define('TOTAL_DINHEIRO', [
    'Enfermagem' => 710 * 2,
    'Informática' => 705 * 2,
    'Meio Ambiente' => 230 * 2,
    'Administração' => 465 * 2,
    'Edificações' => 715 * 2
]);

class PDF extends connect
{
    public function __construct()
    {
        parent::__construct();
        $this->main();
    }

    private function getDadosRelatorio()
    {
        $query = "
            SELECT t.turma_id, t.nome_turma, c.nome_curso, c.curso_id,
                   rifa.valor_arrecadado, rifa.quantidades_rifas, a.nome AS nome_avaliador
            FROM turmas t
            INNER JOIN cursos c ON c.curso_id = t.curso_id
            INNER JOIN tarefa_01_rifas rifa ON rifa.turma_id = t.turma_id
            INNER JOIN avaliadores a ON a.id = rifa.id_usuario
            ORDER BY c.curso_id, t.nome_turma
        ";
        $stmt = $this->connect->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function main()
    {
        $fpdf = new FPDF('P', 'pt', 'A4');
        $fpdf->AliasNbPages();
        $dados = $this->getDadosRelatorio();

        // Primeira página: Tabelas com somatório por turma e curso
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Título principal
        $fpdf->SetFont('Arial', 'B', 24);
        $fpdf->SetY(140);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Tarefa 01: Rifas'), 0, 1, 'C');

        // Título da primeira tabela
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->SetY(180);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Detalhamento por Critério - Turmas'), 0, 1, 'C');

        // Configurações da primeira tabela (por turma)
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetTextColor(0, 0, 0);

        // Consulta SQL para somatórios por turma
        $query_sum_turma = "
            SELECT 
                t.nome_turma,
                SUM(rifa.quantidades_rifas) as total_rifas,
                SUM(rifa.valor_arrecadado) as total_valor
            FROM turmas t
            INNER JOIN tarefa_01_rifas rifa ON rifa.turma_id = t.turma_id
            GROUP BY t.nome_turma
            ORDER BY SUM(rifa.valor_arrecadado) DESC;
        ";
        $stmt_sum_turma = $this->connect->query($query_sum_turma);
        $results_sum_turma = $stmt_sum_turma->fetchAll(PDO::FETCH_ASSOC);

        // Cabeçalho da primeira tabela
        $fpdf->SetY(220);
        $fpdf->SetX(50);
        $fpdf->Cell(100, 20, utf8_decode('Turma'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Meta Rifas'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Rifas Entregues'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Meta Dinheiro'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Valor Arrecadado'), 1, 1, 'C', true);

        // Dados da primeira tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($results_sum_turma as $row) {
            $nome_turma = $row['nome_turma'];
            $fpdf->SetX(50);
            $fpdf->Cell(100, 20, utf8_decode($nome_turma), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode(TURMAS[$nome_turma] ?? 0), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_rifas']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode('R$ ' . number_format(DINHEIRO[$nome_turma] ?? 0, 2, ',', '.')), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode('R$ ' . number_format($row['total_valor'], 2, ',', '.')), 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Título da segunda tabela
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Ln(20);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Soma Total dos Critérios por Curso'), 0, 1, 'C');

        // Configurações da segunda tabela (por curso)
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetTextColor(0, 0, 0);

        // Consulta SQL para somatórios por curso
        $query_sum_curso = "
            SELECT 
                c.nome_curso,
                SUM(rifa.quantidades_rifas) as total_rifas,
                SUM(rifa.valor_arrecadado) as total_valor
            FROM cursos c
            INNER JOIN turmas t ON t.curso_id = c.curso_id
            INNER JOIN tarefa_01_rifas rifa ON rifa.turma_id = t.turma_id
            GROUP BY c.nome_curso
            ORDER BY SUM(rifa.valor_arrecadado) DESC;
        ";
        $stmt_sum_curso = $this->connect->query($query_sum_curso);
        $results_sum_curso = $stmt_sum_curso->fetchAll(PDO::FETCH_ASSOC);

        // Cabeçalho da segunda tabela
        $fpdf->SetX(30);
        $fpdf->Cell(80, 20, utf8_decode('Curso'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Meta Rifas'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Rifas Entregues'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Meta Dinheiro'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Valor Arrecadado'), 1, 0, 'C', true);
        $fpdf->Cell(80, 20, utf8_decode('Pontuação'), 1, 1, 'C', true);

        // Dados da segunda tabela
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetFillColor(240, 240, 240);
        $fill = false;
        $grand_total = 0;
        foreach ($results_sum_curso as $row) {
            $nome_curso = $row['nome_curso'];
            $total = $row['total_valor'];
            $grand_total += $total;
            $bonus = 0;
            if ($total - (TOTAL_DINHEIRO[$nome_curso] ?? 0) == 0) {
                $bonus = 500;
            }
            $fpdf->SetX(30);
            $fpdf->Cell(80, 20, utf8_decode($nome_curso), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode(TOTAL_RIFAS[$nome_curso] ?? 0), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode($row['total_rifas']), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode('R$ ' . number_format(TOTAL_DINHEIRO[$nome_curso] ?? 0, 2, ',', '.')), 1, 0, 'C', $fill);
            $fpdf->Cell(100, 20, utf8_decode('R$ ' . number_format($total, 2, ',', '.')), 1, 0, 'C', $fill);
            $fpdf->Cell(80, 20, utf8_decode($bonus), 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Exibir valor total arrecadado
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Ln(20);
        $fpdf->SetX(0);
        $fpdf->Cell(595, 20, utf8_decode('Valor total arrecadado: R$ ' . number_format($grand_total, 2, ',', '.')), 0, 1, 'C');

        // Gera o PDF
        $fpdf->Output('relatorio_rifas', 'I');
    }
}

$relatorio = new PDF();
?>