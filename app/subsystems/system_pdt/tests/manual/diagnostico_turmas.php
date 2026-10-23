<?php
require_once __DIR__ . '/../../app/main/config/connect.php';

echo "<h1>Diagnóstico - Turmas do Professor</h1>";

// Verificar professores na tabela
$conn = new PDO(
    "mysql:host=localhost;dbname=sistema_pdt;charset=utf8",
    "root",
    ""
);

// Listar todas as tabelas
$result = $conn->query("SHOW TABLES");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);
echo "<h2>Tabelas do BD:</h2>";
foreach ($tables as $table) {
    echo "- " . htmlspecialchars($table) . "<br>";
}

// Verificar estrutura da tabela turmas
echo "<h2>Estrutura de turmas:</h2>";
$result = $conn->query("DESCRIBE turmas");
$columns = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo "- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
}

// Verificar se tem id_professor em turmas
echo "<h2>Dados da turma 1:</h2>";
$result = $conn->query("SELECT * FROM turmas WHERE id_turma = 1");
$turma = $result->fetch(PDO::FETCH_ASSOC);
if ($turma) {
    echo "<pre>" . json_encode($turma, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
} else {
    echo "Turma não encontrada";
}

// Listar professores
echo "<h2>Professores:</h2>";
$result = $conn->query("SELECT id, nome, email, tipo FROM usuarios WHERE tipo = 'professor'");
$professores = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($professores as $prof) {
    echo "- ID: " . $prof['id'] . ", Nome: " . $prof['nome'] . ", Email: " . $prof['email'] . "<br>";
}
?>
