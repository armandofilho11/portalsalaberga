<?php


require_once '../includes/conexao.php';

$stmt = $pdo->query("SELECT * FROM Setor ORDER BY nome");
$setores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="../assets/js/script.js" defer></script>
<script src="../assets/js/validacoes.js"></script>

    <meta charset="UTF-8">
    <title>Atalhos por Setor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .grid-setores {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }
        .grid-setores button {
            padding: 20px;
            font-size: 16px;
            border-radius: 10px;
            background-color: #6ca0dc;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .grid-setores button:hover {
            background-color: #4a80c1;
        }
    </style>
</head>
<body style="padding: 40px;">
    <h2>Visualizar Itens por Setor</h2>

    <div class="grid-setores">
        <?php foreach ($setores as $setor): ?>
            <form action="listar_por_setor.php" method="get">
                <input type="hidden" name="setor_id" value="<?= $setor['id_setor'] ?>">
                <button type="submit"><?= $setor['nome'] ?></button>
            </form>
        <?php endforeach; ?>
    </div>

    <br>
    <a href="dashboard.php"><button>⬅ Voltar</button></a>
    <?php include '../includes/footer.php'; ?>

</body>
</html>
