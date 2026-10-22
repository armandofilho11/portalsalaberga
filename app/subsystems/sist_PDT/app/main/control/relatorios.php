<?php
require_once '../assets/vendor/fpdf/CustomPDF.php';
require_once '../config/conexao.php';
require_once 'vendor/autoload.php';

class Relatorios extends CustomPDF {
    private $conexao;

    public function __construct() {
        parent::__construct();
        $this->conexao = new Conexao('localhost', 'root', '', 'sis_pdt2');
    }

    // Relatório de Avisos
    public function gerarRelatorioAvisos($dataInicio = null, $dataFim = null) {
        $this->AddPage();
        $this->AddTitle('Relatório de Avisos');
        $this->Ln(5);

        $sql = "SELECT a.*, al.nome as nome_aluno 
                FROM avisos a 
                JOIN alunos al ON a.matricula_aluno = al.matricula";
        
        if ($dataInicio && $dataFim) {
            $sql .= " WHERE a.data_aviso BETWEEN ? AND ?";
            $stmt = $this->conexao->getConnection()->prepare($sql);
            $stmt->execute([$dataInicio, $dataFim]);
        } else {
            $stmt = $this->conexao->getConnection()->prepare($sql);
            $stmt->execute();
        }
        
        $avisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Define table headers and widths
        $headers = array('Data', 'Aluno', 'Aviso');
        $widths = array(40, 60, 90);
        
        $this->AddTableHeader($headers, $widths);

        foreach ($avisos as $aviso) {
            $this->AddTableRow(
                array(
                    date('d/m/Y', strtotime($aviso['data_aviso'])),
                    $aviso['nome_aluno'],
                    $aviso['aviso']
                ),
                $widths
            );
        }
    }

    // Relatório de Ocorrências
    public function gerarRelatorioOcorrencias($dataInicio = null, $dataFim = null) {
        $this->AddPage();
        $this->AddTitle('Relatório de Ocorrências');
        $this->Ln(5);

        $sql = "SELECT o.*, al.nome as nome_aluno 
                FROM ocorrencias o 
                JOIN alunos al ON o.matricula = al.matricula";
        
        if ($dataInicio && $dataFim) {
            $sql .= " WHERE o.data BETWEEN ? AND ?";
            $stmt = $this->conexao->getConnection()->prepare($sql);
            $stmt->execute([$dataInicio, $dataFim]);
        } else {
            $stmt = $this->conexao->getConnection()->prepare($sql);
            $stmt->execute();
        }
        
        $ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Define table headers and widths
        $headers = array('Data', 'Aluno', 'Ocorrência');
        $widths = array(40, 60, 90);
        
        $this->AddTableHeader($headers, $widths);

        foreach ($ocorrencias as $ocorrencia) {
            $this->AddTableRow(
                array(
                    date('d/m/Y', strtotime($ocorrencia['data'])),
                    $ocorrencia['nome_aluno'],
                    $ocorrencia['ocorrencia']
                ),
                $widths
            );
        }
    }

    // Relatório de Mapeamento
    public function gerarRelatorioMapeamento() {
        $this->AddPage();
        $this->AddTitle('Relatório de Mapeamento da Sala');
        $this->Ln(5);

        $sql = "SELECT m.*, al.nome as nome_aluno 
                FROM mapeamento m 
                JOIN alunos al ON m.matricula_aluno = al.matricula 
                ORDER BY m.numero_carteira";
        
        $stmt = $this->conexao->getConnection()->prepare($sql);
        $stmt->execute();
        $mapeamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Define table headers and widths
        $headers = array('Carteira', 'Aluno', 'Matrícula', 'Data');
        $widths = array(40, 60, 40, 50);
        
        $this->AddTableHeader($headers, $widths);

        foreach ($mapeamentos as $mapeamento) {
            $this->AddTableRow(
                array(
                    $mapeamento['numero_carteira'],
                    $mapeamento['nome_aluno'],
                    $mapeamento['matricula_aluno'],
                    date('d/m/Y', strtotime($mapeamento['data_mapeamento']))
                ),
                $widths
            );
        }
    }

    // Relatório de Liderança
    public function gerarRelatorioLideranca() {
        $this->AddPage();
        $this->AddTitle('Relatório de Liderança');
        $this->Ln(5);

        $sql = "SELECT l.*, al.nome as nome_aluno 
                FROM lideranca l 
                JOIN alunos al ON l.matricula_lider = al.matricula 
                ORDER BY l.bimestre";
        
        $stmt = $this->conexao->getConnection()->prepare($sql);
        $stmt->execute();
        $liderancas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Define table headers and widths
        $headers = array('Bimestre', 'Líder', 'Matrícula', 'Data');
        $widths = array(40, 60, 40, 50);
        
        $this->AddTableHeader($headers, $widths);

        foreach ($liderancas as $lideranca) {
            $this->AddTableRow(
                array(
                    $lideranca['bimestre'],
                    $lideranca['nome_aluno'],
                    $lideranca['matricula_lider'],
                    date('d/m/Y', strtotime($lideranca['data_lideranca']))
                ),
                $widths
            );
        }
    }

    // Relatório de Vice-Liderança
    public function gerarRelatorioViceLideranca() {
        $this->AddPage();
        $this->AddTitle('Relatório de Vice-Liderança');
        $this->Ln(5);

        $sql = "SELECT v.*, al.nome as nome_aluno 
                FROM vice_lideranca v 
                JOIN alunos al ON v.matricula_vice_lider = al.matricula 
                ORDER BY v.bimestre";
        
        $stmt = $this->conexao->getConnection()->prepare($sql);
        $stmt->execute();
        $viceLiderancas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Define table headers and widths
        $headers = array('Bimestre', 'Vice-Líder', 'Matrícula', 'Data');
        $widths = array(40, 60, 40, 50);
        
        $this->AddTableHeader($headers, $widths);

        foreach ($viceLiderancas as $viceLideranca) {
            $this->AddTableRow(
                array(
                    $viceLideranca['bimestre'],
                    $viceLideranca['nome_aluno'],
                    $viceLideranca['matricula_vice_lider'],
                    date('d/m/Y', strtotime($viceLideranca['data_vice_lideranca']))
                ),
                $widths
            );
        }
    }

    // Relatório de Secretaria
    public function gerarRelatorioSecretaria() {
        $this->AddPage();
        $this->AddTitle('Relatório de Secretaria');
        $this->Ln(5);

        $sql = "SELECT s.*, al.nome as nome_aluno 
                FROM secretaria s 
                JOIN alunos al ON s.matricula_secretario = al.matricula 
                ORDER BY s.bimestre";
        
        $stmt = $this->conexao->getConnection()->prepare($sql);
        $stmt->execute();
        $secretarias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Define table headers and widths
        $headers = array('Bimestre', 'Secretário', 'Matrícula', 'Data');
        $widths = array(40, 60, 40, 50);
        
        $this->AddTableHeader($headers, $widths);

        foreach ($secretarias as $secretaria) {
            $this->AddTableRow(
                array(
                    $secretaria['bimestre'],
                    $secretaria['nome_aluno'],
                    $secretaria['matricula_secretario'],
                    date('d/m/Y', strtotime($secretaria['data_secretaria']))
                ),
                $widths
            );
        }
    }

    // Relatório Completo
    public function gerarRelatorioCompleto() {
        $this->gerarRelatorioAvisos();
        $this->gerarRelatorioOcorrencias();
        $this->gerarRelatorioMapeamento();
        $this->gerarRelatorioLideranca();
        $this->gerarRelatorioViceLideranca();
        $this->gerarRelatorioSecretaria();
    }

    // Exibe o PDF
    public function exibirPDF() {
        $this->Output('relatorio.pdf', 'I');
    }
}

// Função para gerar relatório de avisos
function gerarRelatorioAvisos($dataInicio = null, $dataFim = null) {
    global $conn;
    
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório de Avisos');
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Avisos', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Query para buscar avisos
    $sql = "SELECT a.*, al.nome as nome_aluno 
            FROM avisos a 
            JOIN alunos al ON a.matricula_aluno = al.matricula";
    
    if ($dataInicio && $dataFim) {
        $sql .= " WHERE a.data_aviso BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dataInicio, $dataFim);
    } else {
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Data', 1);
    $pdf->Cell(60, 10, 'Aluno', 1);
    $pdf->Cell(90, 10, 'Aviso', 1);
    $pdf->Ln();
    
    // Dados da tabela
    $pdf->SetFont('helvetica', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, date('d/m/Y', strtotime($row['data_aviso'])), 1);
        $pdf->Cell(60, 10, $row['nome_aluno'], 1);
        $pdf->Cell(90, 10, $row['aviso'], 1);
        $pdf->Ln();
    }
    
    return $pdf;
}

// Função para gerar relatório de ocorrências
function gerarRelatorioOcorrencias($dataInicio = null, $dataFim = null) {
    global $conn;
    
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório de Ocorrências');
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Ocorrências', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Query para buscar ocorrências
    $sql = "SELECT o.*, al.nome as nome_aluno 
            FROM ocorrencias o 
            JOIN alunos al ON o.matricula = al.matricula";
    
    if ($dataInicio && $dataFim) {
        $sql .= " WHERE o.data BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dataInicio, $dataFim);
    } else {
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Data', 1);
    $pdf->Cell(60, 10, 'Aluno', 1);
    $pdf->Cell(90, 10, 'Ocorrência', 1);
    $pdf->Ln();
    
    // Dados da tabela
    $pdf->SetFont('helvetica', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, date('d/m/Y', strtotime($row['data'])), 1);
        $pdf->Cell(60, 10, $row['nome_aluno'], 1);
        $pdf->Cell(90, 10, $row['ocorrencia'], 1);
        $pdf->Ln();
    }
    
    return $pdf;
}

// Função para gerar relatório de mapeamento
function gerarRelatorioMapeamento() {
    global $conn;
    
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório de Mapeamento');
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Mapeamento da Sala', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Query para buscar mapeamento
    $sql = "SELECT m.*, al.nome as nome_aluno 
            FROM mapeamento m 
            JOIN alunos al ON m.matricula_aluno = al.matricula 
            ORDER BY m.numero_carteira";
    
    $result = $conn->query($sql);
    
    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Carteira', 1);
    $pdf->Cell(60, 10, 'Aluno', 1);
    $pdf->Cell(40, 10, 'Matrícula', 1);
    $pdf->Cell(50, 10, 'Data', 1);
    $pdf->Ln();
    
    // Dados da tabela
    $pdf->SetFont('helvetica', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['numero_carteira'], 1);
        $pdf->Cell(60, 10, $row['nome_aluno'], 1);
        $pdf->Cell(40, 10, $row['matricula_aluno'], 1);
        $pdf->Cell(50, 10, date('d/m/Y', strtotime($row['data_mapeamento'])), 1);
        $pdf->Ln();
    }
    
    return $pdf;
}

// Função para gerar relatório de liderança
function gerarRelatorioLideranca() {
    global $conn;
    
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório de Liderança');
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Liderança', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Query para buscar liderança
    $sql = "SELECT l.*, al.nome as nome_aluno 
            FROM lideranca l 
            JOIN alunos al ON l.matricula_lider = al.matricula 
            ORDER BY l.bimestre";
    
    $result = $conn->query($sql);
    
    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Bimestre', 1);
    $pdf->Cell(60, 10, 'Líder', 1);
    $pdf->Cell(40, 10, 'Matrícula', 1);
    $pdf->Cell(50, 10, 'Data', 1);
    $pdf->Ln();
    
    // Dados da tabela
    $pdf->SetFont('helvetica', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['bimestre'], 1);
        $pdf->Cell(60, 10, $row['nome_aluno'], 1);
        $pdf->Cell(40, 10, $row['matricula_lider'], 1);
        $pdf->Cell(50, 10, date('d/m/Y', strtotime($row['data_lideranca'])), 1);
        $pdf->Ln();
    }
    
    return $pdf;
}

// Função para gerar relatório de vice-liderança
function gerarRelatorioViceLideranca() {
    global $conn;
    
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório de Vice-Liderança');
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Vice-Liderança', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Query para buscar vice-liderança
    $sql = "SELECT v.*, al.nome as nome_aluno 
            FROM vice_lideranca v 
            JOIN alunos al ON v.matricula_vice_lider = al.matricula 
            ORDER BY v.bimestre";
    
    $result = $conn->query($sql);
    
    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Bimestre', 1);
    $pdf->Cell(60, 10, 'Vice-Líder', 1);
    $pdf->Cell(40, 10, 'Matrícula', 1);
    $pdf->Cell(50, 10, 'Data', 1);
    $pdf->Ln();
    
    // Dados da tabela
    $pdf->SetFont('helvetica', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['bimestre'], 1);
        $pdf->Cell(60, 10, $row['nome_aluno'], 1);
        $pdf->Cell(40, 10, $row['matricula_vice_lider'], 1);
        $pdf->Cell(50, 10, date('d/m/Y', strtotime($row['data_vice_lideranca'])), 1);
        $pdf->Ln();
    }
    
    return $pdf;
}

// Função para gerar relatório de secretaria
function gerarRelatorioSecretaria() {
    global $conn;
    
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório de Secretaria');
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Secretaria', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Query para buscar secretaria
    $sql = "SELECT s.*, al.nome as nome_aluno 
            FROM secretaria s 
            JOIN alunos al ON s.matricula_secretario = al.matricula 
            ORDER BY s.bimestre";
    
    $result = $conn->query($sql);
    
    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Bimestre', 1);
    $pdf->Cell(60, 10, 'Secretário', 1);
    $pdf->Cell(40, 10, 'Matrícula', 1);
    $pdf->Cell(50, 10, 'Data', 1);
    $pdf->Ln();
    
    // Dados da tabela
    $pdf->SetFont('helvetica', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['bimestre'], 1);
        $pdf->Cell(60, 10, $row['nome_aluno'], 1);
        $pdf->Cell(40, 10, $row['matricula_secretario'], 1);
        $pdf->Cell(50, 10, date('d/m/Y', strtotime($row['data_secretaria'])), 1);
        $pdf->Ln();
    }
    
    return $pdf;
}

// Função para gerar relatório completo
function gerarRelatorioCompleto() {
    $pdf = new Relatorios();
    $pdf->SetCreator('Sistema Escolar');
    $pdf->SetAuthor('Sistema Escolar');
    $pdf->SetTitle('Relatório Completo');
    
    // Adiciona cada relatório como uma seção
    $pdf = gerarRelatorioAvisos();
    $pdf->AddPage();
    $pdf = gerarRelatorioOcorrencias();
    $pdf->AddPage();
    $pdf = gerarRelatorioMapeamento();
    $pdf->AddPage();
    $pdf = gerarRelatorioLideranca();
    $pdf->AddPage();
    $pdf = gerarRelatorioViceLideranca();
    $pdf->AddPage();
    $pdf = gerarRelatorioSecretaria();
    
    return $pdf;
}

// Função para salvar o PDF
function salvarPDF($pdf, $nomeArquivo) {
    $pdf->Output($nomeArquivo, 'F');
}

// Função para baixar o PDF
function baixarPDF($pdf, $nomeArquivo) {
    $pdf->Output($nomeArquivo, 'D');
}

// Função para exibir o PDF no navegador
function exibirPDF($pdf) {
    $pdf->Output('relatorio.pdf', 'I');
}
?> 