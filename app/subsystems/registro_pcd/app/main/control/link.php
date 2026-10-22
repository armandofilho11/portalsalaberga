<?php
session_start();
require_once __DIR__ . '/../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

    // Verificar se os campos estão preenchidos
    if (empty($email) || empty($senha)) {
        header('Location: ../index.php?error=2');
        exit;
    }

    try {
        require_once __DIR__ . '/../model/model.php';
        $model = new Model();
        $resultado = $model->logar($email, $senha);

        if ($resultado === 1) {
            // Login bem sucedido
            $_SESSION['usuario'] = $email;
            $_SESSION['logado'] = true;
            header('Location: ../view/menu.php');
            exit;
        } else {
            // Email ou senha incorretos
            header('Location: ../index.php?error=1');
            exit;
        }
    } catch (Exception $e) {
        // Erro no servidor
        error_log("Erro no login: " . $e->getMessage());
        header('Location: ../index.php?error=3');
        exit;
    }
} else {
    // Método não permitido
    header('Location: ../index.php?error=3');
    exit;
}

function verificarLogin() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../index.php");
        exit();
    }
}

function processarRegistroPCD($dados) {
    require_once(__DIR__ . '/../model/model.php');
    $model = new Model();
    
    if (!empty($dados['nome']) && !empty($dados['idade']) && !empty($dados['turma'])) {
        $result = $model->Registrar(
            $dados['nome'],
            $dados['idade'],
            $dados['deficiencia'],
            $dados['turma']
        );
        
        if ($result) {
            $_SESSION['mensagem_sucesso'] = "Registro realizado com sucesso!";
            return true;
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao realizar registro.";
            return false;
        }
    }
    
    $_SESSION['mensagem_erro'] = "Todos os campos obrigatórios devem ser preenchidos.";
    return false;
}

function buscarRegistrosPCD() {
    global $conn;
    try {
        $sql = "SELECT * FROM registro_pcd ORDER BY nome";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        $_SESSION['mensagem_erro'] = "Erro ao buscar registros: " . $e->getMessage();
        return [];
    }
}

function validarDadosRegistro($dados) {
    $erros = [];
    
    if (empty($dados['nome'])) {
        $erros[] = "Nome é obrigatório";
    }
    
    if (empty($dados['idade'])) {
        $erros[] = "Idade é obrigatória";
    } elseif (!is_numeric($dados['idade']) || $dados['idade'] < 10 || $dados['idade'] > 100) {
        $erros[] = "A idade deve estar entre 10 e 100 anos";
    }
    
    if (empty($dados['turma'])) {
        $erros[] = "Turma é obrigatória";
    }
    
    if (!empty($dados['deficiencia']) && strlen($dados['deficiencia']) < 3) {
        $erros[] = "A descrição da deficiência deve ter pelo menos 3 caracteres";
    }
    
    return $erros;
}

function gerarRelatorio($tipo = 'todos', $filtro = null) {
    global $conn;
    require_once __DIR__ . '/../fpdf/fpdf.php';
    
    try {
        // Preparar a query base
        $sql = "SELECT * FROM registro_pcd";
        $params = [];
        $types = "";
        
        // Adicionar filtros se necessário
        if ($tipo === 'turma' && $filtro) {
            $sql .= " WHERE turma = ?";
            $params[] = $filtro;
            $types .= "s";
        } elseif ($tipo === 'periodo' && is_array($filtro)) {
            $sql .= " WHERE data_registro BETWEEN ? AND ?";
            $params[] = $filtro['inicio'];
            $params[] = $filtro['fim'];
            $types .= "ss";
        }
        
        $sql .= " ORDER BY nome, data_registro";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Erro na execução da consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $registros = $result->fetch_all(MYSQLI_ASSOC);
        
        // Criar o PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // Título
        $pdf->Cell(0, 10, 'Relatório de Registros PCD', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Informações do relatório
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Data de geração: ' . date('d/m/Y H:i:s'), 0, 1);
        if ($tipo === 'turma') {
            $pdf->Cell(0, 10, 'Turma: ' . $filtro, 0, 1);
        } elseif ($tipo === 'periodo') {
            $pdf->Cell(0, 10, 'Período: ' . date('d/m/Y', strtotime($filtro['inicio'])) . ' a ' . date('d/m/Y', strtotime($filtro['fim'])), 0, 1);
        }
        $pdf->Ln(10);
        
        // Cabeçalho da tabela
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Nome', 1);
        $pdf->Cell(20, 10, 'Idade', 1);
        $pdf->Cell(40, 10, 'Turma', 1);
        $pdf->Cell(40, 10, 'Deficiência', 1);
        $pdf->Cell(30, 10, 'Data', 1);
        $pdf->Ln();
        
        // Dados
        $pdf->SetFont('Arial', '', 12);
        foreach ($registros as $registro) {
            $pdf->Cell(60, 10, utf8_decode($registro['nome']), 1);
            $pdf->Cell(20, 10, $registro['idade'], 1);
            $pdf->Cell(40, 10, utf8_decode($registro['turma']), 1);
            $pdf->Cell(40, 10, utf8_decode($registro['deficiencia']), 1);
            $pdf->Cell(30, 10, date('d/m/Y', strtotime($registro['data_registro'])), 1);
            $pdf->Ln();
        }
        
        // Definir cabeçalhos para download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="relatorio_pcd.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        
        // Gerar o PDF
        $pdf->Output('D', 'relatorio_pcd.pdf');
        
    } catch (Exception $e) {
        $_SESSION['mensagem_erro'] = "Erro ao gerar relatório: " . $e->getMessage();
        return false;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>