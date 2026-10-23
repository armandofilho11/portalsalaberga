<?php
// Test turma listing directly

session_start();
$_SESSION['id_usuario'] = 2;
$_SESSION['tipo'] = 'gestor';

require_once __DIR__ . '/../../app/main/config/connect.php';
require_once __DIR__ . '/../../app/main/models/turmas_model.php';

// Check turmas table directly
$db = new connect();

// Check if we can access conn through reflection
$reflection = new ReflectionClass($db);
$connProperty = $reflection->getProperty('conn');
$connProperty->setAccessible(true);
$conn = $connProperty->getValue($db);

$stmt = $conn->prepare("SELECT * FROM turmas");
$stmt->execute();
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'turmas_count' => count($turmas),
    'turmas' => $turmas
], JSON_PRETTY_PRINT);
