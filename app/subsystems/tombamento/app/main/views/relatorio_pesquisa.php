<?php
session_start(
);
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/conexao.php';

// Filtros opcionais
$filtros = [];
$parametros = [];

if (!empty($_GET['setor'])) {
    $filtros[] = "Bem.setor_id = ?";
    $parametros[] = $_GET['setor'];
}

if (!empty($_GET['estado'])) {
    $filtros[] = "Bem.estado_conservacao = ?";
    $parametros[] = $_GET['estado'];
}

if (!empty($_GET['nome'])) {
    $filtros[] = "Bem.nome LIKE ?";
    $parametros[] = "%" . $_GET['nome'] . "%";
}

if (!empty($_GET['tipo'])) {
    $filtros[] = "Categoria.nome LIKE ?";
    $parametros[] = "%" . $_GET['tipo'] . "%";
}

if (!empty($_GET['cor'])) {
    $filtros[] = "Bem.observacoes LIKE ?"; // Supondo que a cor está nas observações
    $parametros[] = "%" . $_GET['cor'] . "%";
}

$sql = "SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
        FROM Bem
        LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
        LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria
        WHERE Bem.ativo = 1";

if (!empty($filtros)) {
    $sql .= " AND " . implode(" AND ", $filtros);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>

    <meta charset="UTF-8">
    <title>Consulta e Relatórios</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="padding: 40px;">
    <h2>Consulta de Bens com Filtro</h2>
    <form method="get" action="">
    <form class="needs-validation" ...>
        <input type="text" name="nome" placeholder="Nome do item">
        <input type="text" name="tipo" placeholder="Tipo">
        <input type="text" name="cor" placeholder="Cor">
        <select name="estado">
            <option value="">Estado</option>
            <option value="Ótimo">Ótimo</option>
            <option value="Bom">Bom</option>
            <option value="Ruim">Ruim</option>
            <option value="Inutilizável">Inutilizável</option>
        </select>
        <select name="setor">
            <option value="">Setor</option>
            <?php
            $setores = $pdo->query("SELECT * FROM Setor")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($setores as $setor) {
                echo "<option value=\"{$setor['id_setor']}\">{$setor['nome']}</option>";
            }
            ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <?php
if (isset($_SESSION['resultados_filtro'])) {
    $bens = $_SESSION['resultados_filtro'];
    unset($_SESSION['resultados_filtro']);
}
?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tombamento</th>
                <th>Setor</th>
                <th>Categoria</th>
                <th>Estado</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bens as $bem): ?>
                <tr>
                    <td><?= $bem['nome'] ?></td>
                    <td><?= $bem['numero_tombamento'] ?></td>
                    <td><?= $bem['setor_nome'] ?></td>
                    <td><?= $bem['categoria_nome'] ?></td>
                    <td><?= $bem['estado_conservacao'] ?></td>
                    <td>R$ <?= number_format($bem['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php include '../includes/footer.php'; ?>

</body>
</html>
