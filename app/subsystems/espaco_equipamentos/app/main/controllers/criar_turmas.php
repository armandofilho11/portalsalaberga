<?php
require_once __DIR__ . '/../config/database.php';

try {
    $conn = getDatabaseConnection();
    
    // Criar tabela turmas se não existir
    $sql = "CREATE TABLE IF NOT EXISTS turmas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL
    )";
    $conn->exec($sql);
    echo "Tabela turmas criada ou já existe<br>";
    
    // Verificar se há dados
    $sql = "SELECT COUNT(*) as total FROM turmas";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['total'] == 0) {
        // Inserir dados de exemplo
        $sql = "INSERT INTO turmas (nome) VALUES 
            ('Turma A'),
            ('Turma B'),
            ('Turma C')";
        $conn->exec($sql);
        echo "Dados de exemplo inseridos<br>";
    } else {
        echo "Já existem " . $result['total'] . " turmas cadastradas<br>";
    }
    
    // Listar todas as turmas
    $sql = "SELECT * FROM turmas";
    $stmt = $conn->query($sql);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Turmas cadastradas:</h3>";
    foreach ($turmas as $turma) {
        echo "ID: " . $turma['id'] . " - Nome: " . $turma['nome'] . "<br>";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?> 