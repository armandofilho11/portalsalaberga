<?php
session_start();
require_once '../includes/conexao.php';

$stmt = $pdo->query("SELECT * FROM Consulta ORDER BY data_consulta DESC");
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Consultas Salvas</h2>
<table border="1" cellpadding="10">
    <thead>

        <tr>
            <th>Código</th>
            <th>Critério</th>
            <th>Valor</th>
            <th>Data</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($consultas as $consulta): ?>
            <tr>
                <td><?= $consulta['id_consulta'] ?></td>
                <td><?= $consulta['criterio'] ?></td>
                <td><?= $consulta['valor'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($consulta['data_consulta'])) ?></td>
                <td>
                    <a href="ver_consulta.php?id=<?= $consulta['id_consulta'] ?>">🔍 Ver</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>