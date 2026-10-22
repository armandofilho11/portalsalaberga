<?php
session_start();
require_once '../includes/conexao.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Consulta inválida!";
    exit;
}

$stmt = $pdo->prepare("
    SELECT Bem.* FROM Bem
    JOIN Consulta_Bem ON Bem.id_bem = Consulta_Bem.id_bem
    WHERE Consulta_Bem.id_consulta = ?
");
$stmt->execute([$id]);
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Resultado da Consulta</h2>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Número Tombamento</th>
            <th>Estado</th>
            <th>Ano Aquisição</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bens as $bem): ?>
            <tr>
                <td><?= $bem['nome'] ?></td>
                <td><?= $bem['numero_tombamento'] ?></td>
                <td><?= $bem['estado_conservacao'] ?></td>
                <td><?= $bem['ano_aquisicao'] ?></td>
            </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>

<a href="consultas_salvas.php"><button>← Voltar</button></a>
