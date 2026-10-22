<?php
require_once('../../config/connect.php');

$db = new connect();
$pdo = $db->getConnection();

$sql = "SELECT id, nome_fantasia, razao_social
        FROM ficha_concedente
        ORDER BY nome_fantasia";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$concedentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório da Concedente</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-12 bg-white shadow-lg rounded-xl">

    <div class="bg-blue-700 text-white px-6 py-4 rounded-t-xl">
        <h1 class="text-2xl font-bold">
            Relatório de Concedente
        </h1>
        <p class="text-blue-100">
            Selecione a empresa para gerar o PDF.
        </p>
    </div>

    <form
        action="relatorio_pdf_ficha_cadastral.php"
        method="GET"
        class="p-8">

        <div class="mb-8">

            <label class="block mb-2 font-semibold">
                Concedente
            </label>

            <select
                name="id"
                required
                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

                <option value="">
                    Selecione...
                </option>

                <?php foreach($concedentes as $c): ?>

                    <option value="<?= $c['id'] ?>">
                        <?= htmlspecialchars($c['nome_fantasia']) ?>
                        -
                        <?= htmlspecialchars($c['razao_social']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="text-right">

            <button
                type="submit"
                class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-3 rounded-lg font-semibold">

                Gerar PDF

            </button>

        </div>

    </form>

</div>

</body>
</html>