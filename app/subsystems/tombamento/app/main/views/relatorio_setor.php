<?php
require_once '../includes/conexao.php';
require_once '../assets/vendor/fpdf/fpdf.php';

// Buscar setores para preencher o filtro
$setores = $pdo->query("SELECT * FROM Setor")->fetchAll(PDO::FETCH_ASSOC);

// Se um setor foi selecionado
$setorSelecionado = isset($_GET['setor']) ? $_GET['setor'] : null;

$sql = "SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome 
        FROM Bem 
        LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor 
        LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria";

if ($setorSelecionado) {
    $sql .= " WHERE Bem.setor_id = :setor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':setor_id', $setorSelecionado);
} else {
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$bens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query para o relatório de setores e quantidade de itens
$sqlRelatorio = "SELECT Setor.nome AS setor_nome, COUNT(Bem.id_bem) AS total_itens 
                 FROM Setor 
                 LEFT JOIN Bem ON Setor.id_setor = Bem.setor_id 
                 GROUP BY Setor.id_setor, Setor.nome";
$stmtRelatorio = $pdo->prepare($sqlRelatorio);
$stmtRelatorio->execute();
$relatorioSetores = $stmtRelatorio->fetchAll(PDO::FETCH_ASSOC);

// Query para o total geral de itens
$sqlTotalGeral = "SELECT COUNT(id_bem) AS total_geral FROM Bem";
$stmtTotalGeral = $pdo->prepare($sqlTotalGeral);
$stmtTotalGeral->execute();
$totalGeral = $stmtTotalGeral->fetch(PDO::FETCH_ASSOC)['total_geral'];

// Gerar PDF se o botão "Gerar PDF" for clicado
if (isset($_GET['gerar_pdf'])) {
    class PDF extends FPDF {
        function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
            $txt = mb_convert_encoding($txt, 'ISO-8859-1', 'UTF-8');
            parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, mb_convert_encoding('Relatório de Setores e Quantidade de Itens', 'UTF-8'), 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(0, 90, 36); // Verde (#005A24)
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(95, 10, 'Setor', 1, 0, 'C', true);
    $pdf->Cell(95, 10, 'Quantidade de Itens', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    foreach ($relatorioSetores as $setor) {
        $pdf->Cell(95, 10, $setor['setor_nome'], 'T', 0, 'L', true);
        $pdf->Cell(95, 10, $setor['total_itens'], 'T', 1, 'C', true);
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(95, 10, 'Total Geral', 'T', 0, 'L', true);
    $pdf->Cell(95, 10, $totalGeral, 'T', 1, 'C', true);

    $pdf->Output('D', 'relatorio_setores.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Relatório por Setor</title>
    <script src="../assets/js/script.js" defer></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f0f0;
            color: #333;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header img {
            height: 40px;
            width: auto;
        }

        .card-header input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            font-family: 'Poppins', sans-serif;
        }

        form {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        label {
            font-weight: 600;
            color: #34495e;
        }

        select, button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }

        select {
            min-width: 200px;
            background-color: #fff;
        }

        button {
            background-color: #FFB300; /* Laranja */
            color: #000;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e0a800;
        }

        .table-container {
            width: 100%;
            overflow-x: auto; /* Para rolagem horizontal em telas pequenas */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            min-width: 600px; /* Garante que a tabela seja pelo menos legível */
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #005A24; /* Verde */
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        td button {
            background-color: #005A24; /* Verde */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 12px;
        }

        td button:hover {
            background-color: #00401a;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ecf0f1;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #6d7274;
        }

        @media (max-width: 768px) {
            form {
                flex-direction: column;
                align-items: stretch;
            }

            select, button {
                width: 100%;
                margin-bottom: 10px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 6px;
            }

            .card-header input[type="text"] {
                width: 100%;
                margin-top: 10px;
            }
        }

        @media print {
            body {
                background-color: #fff;
            }
            .card-header, form, .back-button {
                display: none;
            }
            table {
                border: none;
            }
            .table-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <img src="../img/logo-gp-2.png" alt="Gestão Patrimonial Logo">
            <input type="text" placeholder="Pesquisar itens...">
        </div>

        <form method="get">
            <label for="setor">Filtrar por setor:</label>
            <select name="setor" id="setor">
                <option value="">Todos os setores</option>
                <?php foreach ($setores as $setor): ?>
                    <option value="<?= $setor['id_setor'] ?>" <?= ($setorSelecionado == $setor['id_setor']) ? 'selected' : '' ?>>
                        <?= $setor['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
            <button type="button" onclick="window.print()">🖨 Imprimir</button>
            <button type="submit" name="gerar_pdf" value="1">📄 Gerar PDF</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Tombamento</th>
                        <th>Ano</th>
                        <th>Estado</th>
                        <th>Valor</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bens as $bem): ?>
                        <tr>
                            <td><?= $bem['id_bem'] ?? 'N/A' ?></td>
                            <td><?= $bem['nome'] ?></td>
                            <td><?= $bem['numero_tombamento'] ?></td>
                            <td><?= $bem['ano_aquisicao'] ?></td>
                            <td><?= $bem['estado_conservacao'] ?></td>
                            <td>R$ <?= number_format($bem['valor'], 2, ',', '.') ?></td>
                            <td><button>Editar</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <a href="../includes/menu.php" class="back-button">⬅ Voltar</a>
</body>
</html>