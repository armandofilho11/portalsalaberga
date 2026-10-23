<?php
require_once __DIR__ . '/../../app/main/config/connect.php';

$conn = new PDO(
    "mysql:host=localhost;dbname=sistema_pdt;charset=utf8",
    "root",
    ""
);

// Atualizar a turma 1 para associar ao Professor Teste (ID 3)
$stmt = $conn->prepare("UPDATE turmas SET id_professor = :id_professor WHERE id_turma = 1");
$result = $stmt->execute([':id_professor' => 3]);

if ($result) {
    echo "✅ Turma 1 (A) agora está associada ao Professor Teste (ID 3)!<br>";
    echo "<p><a href='testar_autenticacao.php'>➜ Voltar ao login</a></p>";
} else {
    echo "❌ Erro ao atualizar turma<br>";
}
?>
