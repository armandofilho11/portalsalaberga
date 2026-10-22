<?php
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID não fornecido']);
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');

// Get form details
$stmt = $pdo->prepare('SELECT id_concedente FROM selecao WHERE id = ?');
$stmt->execute([$_GET['id']]);
$form = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$form) {
    http_response_code(404);
    echo json_encode(['error' => 'Formulário não encontrado']);
    exit;
}

// Get company details
$stmt = $pdo->prepare('SELECT nome, contato, endereco, perfil, numero_vagas FROM concedentes WHERE id = ?');
$stmt->execute([$form['id_concedente']]);
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empresa) {
    http_response_code(404);
    echo json_encode(['error' => 'Empresa não encontrada']);
    exit;
}

echo json_encode($empresa); 