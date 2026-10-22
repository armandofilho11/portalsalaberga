<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('
        SELECT s.id, s.data, s.local, s.hora, c.nome as nome_empresa, c.numero_vagas
        FROM selecao s 
        INNER JOIN concedentes c ON s.id_concedente = c.id 
        WHERE s.id_aluno IS NULL AND c.numero_vagas > 0
        ORDER BY s.data DESC
    ');
    $stmt->execute();
    $processos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($processos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} 