<?php
session_start();
require_once __DIR__ . '/../../app/main/config/connect.php';

$db = new connect();
$reflection = new ReflectionClass($db);
$connProperty = $reflection->getProperty('conn');
$connProperty->setAccessible(true);
$conn = $connProperty->getValue($db);

try {
    // Check if id_curso column exists
    $stmt = $conn->prepare("SHOW COLUMNS FROM turmas LIKE 'id_curso'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        // Column doesn't exist, add it
        $stmt = $conn->prepare("
            ALTER TABLE turmas 
            ADD COLUMN id_curso INT NOT NULL DEFAULT 1 AFTER id_turma,
            ADD CONSTRAINT fk_turmas_cursos
            FOREIGN KEY (id_curso)
            REFERENCES cursos(id_curso)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
        ");
        $stmt->execute();
        echo json_encode(['sucesso' => true, 'mensagem' => 'Coluna id_curso adicionada']);
    } else {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Coluna id_curso já existe']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
