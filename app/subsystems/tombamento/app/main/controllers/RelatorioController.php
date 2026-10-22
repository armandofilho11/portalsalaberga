<?php
session_start();
require_once '../includes/conexao.php';

$tipo = $_POST['tipo'];
$setor_id = $_POST['setor_id'] ?? null;
$categoria_id = $_POST['categoria_id'] ?? null;

$query = "SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
          FROM Bem
          LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
          LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria";
$condicoes = [];

if ($tipo === "setor" && $setor_id) {
    $condicoes[] = "Bem.setor_id = $setor_id";
}
if ($tipo === "categoria" && $categoria_id) {
    $condicoes[] = "Bem.categoria_id = $categoria_id";
}
if ($condicoes) {
    $query .= " WHERE " . implode(" AND ", $condicoes);
}

$stmt = $pdo->query($query);
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gerar HTML simples como conteúdo do relatório
$html = "<h2>Relatório de Bens</h2><table border='1' cellpadding='5'><tr><th>Nome</th><th>Tombamento</th><th>Setor</th><th>Categoria</th></tr>";
foreach ($bens as $bem) {
    $html .= "<tr><td>{$bem['nome']}</td><td>{$bem['numero_tombamento']}</td><td>{$bem['setor_nome']}</td><td>{$bem['categoria_nome']}</td></tr>";
}
$html .= "</table>";

// Inserir relatório no banco
$stmt = $pdo->prepare("INSERT INTO Relatorio (tipo, data_geracao, conteudo) VALUES (?, NOW(), ?)");
$stmt->execute([$tipo, $html]);
$relatorio_id = $pdo->lastInsertId();

// Relacionar bens ao relatório
foreach ($bens as $bem) {
    $stmt = $pdo->prepare("INSERT INTO Relatorio_Bem (id_relatorio, id_bem) VALUES (?, ?)");
    $stmt->execute([$relatorio_id, $bem['id_bem']]);
}

$_SESSION['relatorio_html'] = $html;
header("Location: ../views/ver_relatorio.php");
exit;