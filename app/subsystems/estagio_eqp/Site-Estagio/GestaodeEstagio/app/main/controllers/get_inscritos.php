<?php
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID não fornecido']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('
        SELECT a.nome, a.curso, s.data_inscricao
        FROM selecao s 
        INNER JOIN aluno a ON s.id_aluno = a.id 
        WHERE s.id = ?
        ORDER BY s.data_inscricao DESC
    ');
    $stmt->execute([$_GET['id']]);
    $inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($inscritos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} 