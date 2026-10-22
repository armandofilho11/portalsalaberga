<?php
require_once 'model.php';
require_once '../fpdf/fpdf.php';

class Relatorio extends FPDF {
    private $model;

    public function __construct() {
        parent::__construct('L'); // Orientação paisagem
        $this->model = new Model();
    }

    // Cabeçalho do relatório
    public function Header() {
        // Logo
        if (file_exists('../assets/img/logo.png')) {
            $this->Image('../assets/img/logo.png', 10, 6, 30);
        }
        
        // Título
        $this->SetFont('Helvetica', 'B', 15);
        $this->Cell(0, 10, 'Relatorio de Alunos PCD', 0, 1, 'C');
        $this->Ln(10);
        
        // Cabeçalho da tabela
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(50, 7, 'Nome', 1);
        $this->Cell(15, 7, 'Idade', 1);
        $this->Cell(30, 7, 'Turma', 1);
        $this->Cell(40, 7, 'Deficiencia', 1);
        $this->Cell(30, 7, 'Data Registro', 1);
        $this->Cell(25, 7, 'Presenca', 1);
        $this->Cell(40, 7, 'Observacoes', 1);
        $this->Ln();
    }

    // Rodapé do relatório
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Método para gerar o relatório completo
    public function gerarRelatorio() {
        try {
            // Configurar o PDF
            $this->AliasNbPages();
            $this->AddPage();
            $this->SetFont('Helvetica', '', 9);

            // Buscar dados do banco
            $dados = $this->model->buscarTodosRegistros();

            if (empty($dados)) {
                $this->SetFont('Helvetica', 'I', 12);
                $this->Cell(0, 10, 'Nenhum registro encontrado.', 0, 1, 'C');
            } else {
                // Preencher a tabela com os dados
                foreach ($dados as $row) {
                    $this->Cell(50, 6, $row['nome'] ?? 'N/A', 1);
                    $this->Cell(15, 6, isset($row['idade']) ? (int)$row['idade'] : 'N/A', 1);
                    $this->Cell(30, 6, $row['turma'] ?? 'N/A', 1);
                    $this->Cell(40, 6, $row['deficiencia'] ?? '-', 1);
                    $this->Cell(30, 6, $row['data_registro'] ?? '-', 1);
                    $this->Cell(25, 6, isset($row['presenca']) && $row['presenca'] ? 'Presente' : 'Ausente', 1);
                    $this->Cell(40, 6, $row['observacoes'] ?? '-', 1);
                    $this->Ln();
                }
            }

            // Gerar nome do arquivo
            $nomeArquivo = 'relatorio_pcd_' . date('Y-m-d_H-i-s') . '.pdf';

            // Configurar headers para download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            // Gerar o PDF
            $this->Output('D', $nomeArquivo);
            exit;

        } catch (Exception $e) {
            throw new Exception("Erro ao gerar relatório: " . $e->getMessage());
        }
    }
}
?> 