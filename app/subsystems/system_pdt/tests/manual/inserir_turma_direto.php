<?php
session_start();
$_SESSION['id_usuario'] = 2;
$_SESSION['tipo'] = 'gestor';

require_once __DIR__ . '/../../app/main/config/connect.php';

// Try direct insert
$db = new connect();
$reflection = new ReflectionClass($db);
$connProperty = $reflection->getProperty('conn');
$connProperty->setAccessible(true);
$conn = $connProperty->getValue($db);

try {
    $stmt = $conn->prepare("
        INSERT INTO turmas (id_curso, nome_turma, serie_turma, sala_turma, ano_letivo, descricao_turma, id_professor)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $resultado = $stmt->execute([
        1,              // id_curso
        'A',            // nome_turma
        1,              // serie_turma
        '06',           // sala_turma
        2026,           // ano_letivo
        'Teste',        // descricao_turma
        3               // id_professor
    ]);
    
    echo json_encode([
        'sucesso' => true,
        'id' => $conn->lastInsertId(),
        'resultado' => $resultado
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}
