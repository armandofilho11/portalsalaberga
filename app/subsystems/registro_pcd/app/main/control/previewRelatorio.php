<?php
session_start();
require_once dirname(__FILE__) . '/../config/conexao.php';
require_once dirname(__FILE__) . '/../fpdf/fpdf.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    die('Acesso negado');
}

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image(dirname(__FILE__) . '/../assets/img/logo.png', 10, 6, 30);
        
        // Título
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Relatório de Registros PCD - EEEP Salaberga', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

try {
    // Criar instância do PDF
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Processar os parâmetros do relatório
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'todos';
    $where = "1=1";
    $params = [];
    $types = "";

    if ($tipo === 'turma' && !empty($_POST['turma'])) {
        $turma = $_POST['turma'];
        $where .= " AND turma = ?";
        $params[] = $turma;
        $types .= "s";
    } elseif ($tipo === 'periodo') {
        if (!empty($_POST['data_inicio']) && DateTime::createFromFormat('Y-m-d', $_POST['data_inicio'])) {
            $data_inicio = $_POST['data_inicio'];
            $where .= " AND data_registro >= ?";
            $params[] = $data_inicio;
            $types .= "s";
        }
        if (!empty($_POST['data_fim']) && DateTime::createFromFormat('Y-m-d', $_POST['data_fim'])) {
            $data_fim = $_POST['data_fim'];
            $where .= " AND data_registro <= ?";
            $params[] = $data_fim;
            $types .= "s";
        }
    }

    // Preparar e executar a consulta com prepared statements
    $sql = "SELECT * FROM registro_pcd WHERE $where ORDER BY data_registro DESC";
            
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Erro na preparação da consulta: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("Erro na execução da consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Cabeçalho da tabela
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'Data', 1);
        $pdf->Cell(40, 10, 'Nome', 1);
        $pdf->Cell(30, 10, 'Turma', 1);
        $pdf->Cell(90, 10, 'Observação', 1);
        $pdf->Ln();

        // Dados da tabela
        $pdf->SetFont('Arial', '', 12);
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(30, 10, date('d/m/Y', strtotime($row['data_registro'])), 1);
            $pdf->Cell(40, 10, utf8_decode($row['nome']), 1);
            $pdf->Cell(30, 10, utf8_decode($row['turma']), 1);
            $pdf->Cell(90, 10, utf8_decode(substr($row['observacao'], 0, 50)), 1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'Nenhum registro encontrado.', 0, 1, 'C');
    }

    // Definir cabeçalho HTTP
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="relatorio_preview.pdf"');

    // Gerar o PDF
    $pdf->Output('I', 'relatorio_preview.pdf');

} catch (Exception $e) {
    die('Erro ao gerar o relatório: ' . $e->getMessage());
} finally {
    // Fechar a conexão e o statement
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>