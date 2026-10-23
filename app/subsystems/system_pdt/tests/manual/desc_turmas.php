<?php
session_start();
require_once __DIR__ . '/../../app/main/config/connect.php';

$db = new connect();
$reflection = new ReflectionClass($db);
$connProperty = $reflection->getProperty('conn');
$connProperty->setAccessible(true);
$conn = $connProperty->getValue($db);

$stmt = $conn->prepare("DESC turmas");
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($columns, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
