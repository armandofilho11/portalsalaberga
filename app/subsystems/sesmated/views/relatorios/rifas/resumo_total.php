<?php
require_once('../../../assets/fpdf/fpdf.php');
require_once('../../../config/connect.php');
require_once('../../../../../main/models/sessions.php');
$session = new sessions();
$session->autenticar_session();

// Definindo constantes
define('TOTAL_RIFAS', [
    'Enfermagem' => 710,
    'Informática' => 705,
    'Meio Ambiente' => 230,
    'Administração' => 470,
    'Edificações' => 715
]);

define('TOTAL_DINHEIRO', [
    'Enfermagem' => 710 * 2,
    'Informática' => 705 * 2,
    'Meio Ambiente' => 230 * 2,
    'Administração' => 470 * 2,
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

    private function gerarCabecalho($fpdf, $titulo)
    {
        $fpdf->SetFont('Arial', 'B', 16);
        $fpdf->SetXY(0, 50); // Margem superior maior para evitar sobrepor os logotipos
        $fpdf->Cell(0, 20, utf8_decode($titulo), 0, 1, 'C');
        $fpdf->Ln(10);
    }

    private function gerarTabela($fpdf, $header, $data)
    {
        $fpdf->SetFont('Arial', 'B', 8); // Reduzindo a fonte para 8 para evitar sobreposição
        $fpdf->SetFillColor(200, 200, 200);
        $totalWidth = 540; // Largura total das 6 colunas (90 * 6)
        $x = ($fpdf->GetPageWidth() - $totalWidth) / 2; // Centraliza a tabela
        $fpdf->SetXY($x, $fpdf->GetY());
        foreach ($header as $col) {
            $fpdf->Cell(90, 20, utf8_decode($col), 1, 0, 'C', true); // Aumentando a altura para 20
        }
        $fpdf->Ln();

        $fpdf->SetFont('Arial', '', 10);
        foreach ($data as $row) {
            $fpdf->SetXY($x, $fpdf->GetY());
            foreach ($row as $col) {
                $fpdf->Cell(90, 15, utf8_decode($col), 1);
            }
            $fpdf->Ln();
        }
    }

    public function main()
    {
        $fpdf = new FPDF('P', 'pt', 'A4');
        $fpdf->AliasNbPages();
        $dados = $this->getDadosRelatorio();

        // Relatório por Curso
        $fpdf->AddPage('P');
        $this->gerarCabecalho($fpdf, 'Relatório por Curso');

        $cursos = array_unique(array_column($dados, 'nome_curso'));
        $header = ['Curso', 'Meta Rifas Total', 'Rifas Entregues Total', 'Meta Dinheiro Total', 'Valor Arrecadado Total', 'Avaliadores'];
        $tabela_data = [];
        $total_arrecadado_geral = 0; // Variável para somar o total arrecadado de todos os cursos

        foreach ($cursos as $curso) {
            $curso_data = array_filter($dados, fn($d) => $d['nome_curso'] === $curso);
            // Usar nome_curso como chave para mapear as constantes
            $total_meta_rifas = isset(TOTAL_RIFAS[$curso]) ? TOTAL_RIFAS[$curso] : 0;
            $total_meta_dinheiro = isset(TOTAL_DINHEIRO[$curso]) ? TOTAL_DINHEIRO[$curso] : 0;
            $total_rifas_entregues = array_sum(array_column($curso_data, 'quantidades_rifas'));
            $total_valor_arrecadado = array_sum(array_column($curso_data, 'valor_arrecadado'));
            $total_arrecadado_geral += $total_valor_arrecadado; // Somando ao total geral
            $avaliadores = implode(', ', array_unique(array_column($curso_data, 'nome_avaliador')));

            $tabela_data[] = [
                $curso,
                $total_meta_rifas,
                $total_rifas_entregues,
                'R$ ' . number_format($total_meta_dinheiro, 2, ',', '.'),
                'R$ ' . number_format($total_valor_arrecadado, 2, ',', '.'),
                $avaliadores
            ];
        }

        $this->gerarTabela($fpdf, $header, $tabela_data);
        $fpdf->Ln(20);

        // Exibir o total arrecadado geral
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(0, 20, utf8_decode('Total Arrecadado Geral: R$ ' . number_format($total_arrecadado_geral, 2, ',', '.')), 0, 1, 'C');

        $fpdf->Output('relatorio_acervo.pdf', 'I');
    }
}

$relatorio = new PDF();