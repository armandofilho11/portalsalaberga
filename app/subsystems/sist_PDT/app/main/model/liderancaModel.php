<?php
require_once 'conexao.php';

// Funções para Líder
function listarLideranca() {
    global $conn;
    
    $sql = "SELECT l.*, a.nome 
            FROM lider l 
            JOIN aluno a ON l.matricula = a.matricula 
            ORDER BY l.bimestre";
            
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Erro ao listar liderança: " . $conn->error);
    }
    
    $lideres = [];
    while ($row = $result->fetch_assoc()) {
        $lideres[] = $row;
    }
    
    return $lideres;
}

// Funções para Vice-Líder
function listarViceLideranca() {
    global $conn;
    
    $sql = "SELECT v.*, a.nome 
            FROM vice_lider v 
            JOIN aluno a ON v.matricula = a.matricula 
            ORDER BY v.bimestre";
            
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Erro ao listar vice-liderança: " . $conn->error);
    }
    
    $viceLideres = [];
    while ($row = $result->fetch_assoc()) {
        $viceLideres[] = $row;
    }
    
    return $viceLideres;
}

// Funções para Secretário
function listarSecretaria() {
    global $conn;
    
    $sql = "SELECT s.*, a.nome 
            FROM secretario s 
            JOIN aluno a ON s.matricula = a.matricula 
            ORDER BY s.bimestre";
            
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Erro ao listar secretaria: " . $conn->error);
    }
    
    $secretarios = [];
    while ($row = $result->fetch_assoc()) {
        $secretarios[] = $row;
    }
    
    return $secretarios;
}

function verificarSecretariaExistente($bimestre) {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM secretario WHERE bimestre = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao verificar secretaria existente: " . $conn->error);
    }
    
    $stmt->bind_param("s", $bimestre);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] > 0;
}

function salvarSecretaria($matricula, $bimestre) {
    global $conn;
    
    // Verifica se o aluno existe
    $sql = "SELECT COUNT(*) as total FROM aluno WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao verificar aluno: " . $conn->error);
    }
    
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['total'] == 0) {
        throw new Exception("Aluno não encontrado");
    }
    
    // Insere o novo secretário
    $sql = "INSERT INTO secretario (matricula, bimestre) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao salvar secretaria: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $matricula, $bimestre);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao salvar secretaria: " . $stmt->error);
    }
}

// Funções comuns
function listarAlunos() {
    global $conn;
    
    $sql = "SELECT matricula, nome FROM aluno ORDER BY nome";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Erro ao listar alunos: " . $conn->error);
    }
    
    $alunos = [];
    while ($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
    
    return $alunos;
}

function buscarAlunos($termo) {
    global $conn;
    
    $termo = "%$termo%";
    $sql = "SELECT matricula, nome 
            FROM aluno 
            WHERE nome LIKE ? OR matricula LIKE ? 
            ORDER BY nome 
            LIMIT 10";
            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Erro ao preparar busca: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $termo, $termo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $alunos = [];
    while ($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
    
    return $alunos;
}

function verificarLiderancaExistente($bimestre) {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM lider WHERE bimestre = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao verificar liderança existente: " . $conn->error);
    }
    
    $stmt->bind_param("s", $bimestre);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] > 0;
}

function verificarViceLiderancaExistente($bimestre) {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM vice_lider WHERE bimestre = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao verificar vice-liderança existente: " . $conn->error);
    }
    
    $stmt->bind_param("s", $bimestre);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] > 0;
}

function salvarLideranca($matricula, $bimestre) {
    global $conn;
    
    // Verifica se o aluno existe
    $sql = "SELECT COUNT(*) as total FROM aluno WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao verificar aluno: " . $conn->error);
    }
    
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['total'] == 0) {
        throw new Exception("Aluno não encontrado");
    }
    
    // Insere o novo líder
    $sql = "INSERT INTO lider (matricula, bimestre) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao salvar liderança: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $matricula, $bimestre);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao salvar liderança: " . $stmt->error);
    }
}

function salvarViceLideranca($matricula, $bimestre) {
    global $conn;
    
    // Verifica se o aluno existe
    $sql = "SELECT COUNT(*) as total FROM aluno WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao verificar aluno: " . $conn->error);
    }
    
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['total'] == 0) {
        throw new Exception("Aluno não encontrado");
    }
    
    // Insere o novo vice-líder
    $sql = "INSERT INTO vice_lider (matricula, bimestre) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro ao salvar vice-liderança: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $matricula, $bimestre);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao salvar vice-liderança: " . $stmt->error);
    }
} 