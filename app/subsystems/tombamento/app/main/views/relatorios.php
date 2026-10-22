<?php
require_once '../includes/conexao.php';
require_once '../assets/vendor/fpdf/fpdf.php';

// Extend FPDF to include RoundedRect method
class PDF extends FPDF {
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        $op = ($style == 'F') ? 'f' : (($style == 'FD' || $style == 'DF') ? 'B' : 'S');
        $MyArc = 4/3 * (sqrt(2) - 1) * $r;
        $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-$y)*$k));
        $this->_Arc($xc+$MyArc, $yc-$r, $xc+$r, $yc-$MyArc, $xc+$r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$yc)*$k));
        $this->_Arc($xc+$r, $yc+$MyArc, $xc+$MyArc, $yc+$r, $xc, $yc+$r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k));
        $this->_Arc($xc-$MyArc, $yc+$r, $xc-$r, $yc+$MyArc, $xc-$r, $yc);

        $xc = $x+$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-$yc)*$k));
        $this->_Arc($xc-$r, $yc-$MyArc, $xc-$MyArc, $yc-$r, $xc, $yc-$r);

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $k = $this->k;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $k, ($h - $y1) * $k,
            $x2 * $k, ($h - $y2) * $k,
            $x3 * $k, ($h - $y3) * $k));
    }
}

// Carregar setores
$setores = $pdo->query("SELECT * FROM Setor")->fetchAll(PDO::FETCH_ASSOC);

// Lógica do filtro
$filtro_setor = $_GET['setor_id'] ?? '';
$param = [];
$sql = "SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
        FROM Bem
        LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
        LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria";

if (!empty($filtro_setor)) {
    $sql .= " WHERE Bem.setor_id = :setor_id";
    $param[':setor_id'] = $filtro_setor;
}

$bens = $pdo->prepare($sql);
$bens->execute($param);
$lista_bens = $bens->fetchAll(PDO::FETCH_ASSOC);

// Gerar relatório PDF
if (isset($_GET['gerar']) && count($lista_bens) > 0) {
    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // Definir margens
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 15);

    // Cores
    $pdf->SetFillColor(0, 90, 36); // Verde escuro (#005A24)
    $pdf->SetTextColor(255, 255, 255); // Branco
    $pdf->SetDrawColor(200, 200, 200); // Cinza claro para bordas
    $orange = [255, 179, 0]; // Laranja (#FFB300)

    // Logotipo
    $logoPath = '../img/logo-gp-2.png';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 85, 10, 50);
    }
    $pdf->Ln(40);

    // Título principal
    $pdf->SetFont('Helvetica', 'B', 20);
    $pdf->SetFillColor(0, 90, 36);
    $pdf->Rect(15, $pdf->GetY(), 180, 12, 'F');
    $pdf->SetXY(15, $pdf->GetY() + 2);
    $pdf->Cell(180, 8, utf8_decode('Relatório de Bens Patrimoniais'), 0, 1, 'C');
    $pdf->Ln(5);

    // Subtítulo (Setor e Data)
    $pdf->SetFont('Helvetica', 'I', 11);
    $pdf->SetTextColor(0, 0, 0);
    $setor_text = !empty($filtro_setor) ? 'Setor: ' . utf8_decode($lista_bens[0]['setor_nome']) : 'Todos os Setores';
    $pdf->Cell(0, 8, $setor_text, 0, 1, 'L');
    $pdf->Cell(0, 8, utf8_decode('Gerado em: ' . date('d/m/Y H:i:s')), 0, 1, 'L');
    $pdf->SetDrawColor(255, 179, 0);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(10);

    // Tabela
    $startY = $pdf->GetY();
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetLineWidth(0.2); // Bordas mais finas
    $header = [
        ['text' => 'Nome', 'width' => 40],
        ['text' => 'Tombamento', 'width' => 30],
        ['text' => 'Setor', 'width' => 30],
        ['text' => 'Conservação', 'width' => 30],
        ['text' => 'Ano', 'width' => 20],
        ['text' => 'Valor', 'width' => 30]
    ];
    $pdf->SetFillColor(0, 90, 36);
    foreach ($header as $col) {
        $pdf->Cell($col['width'], 10, utf8_decode($col['text']), 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Corpo da tabela
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $fill = false;
    $rowCount = 0;
    foreach ($lista_bens as $b) {
        $pdf->SetFillColor(245, 245, 245); // Alternar branco e cinza claro
        $pdf->Cell(40, 9, utf8_decode($b['nome']), 0, 0, 'L', $fill);
        $pdf->Cell(30, 9, utf8_decode($b['numero_tombamento']), 0, 0, 'L', $fill);
        $pdf->Cell(30, 9, utf8_decode($b['setor_nome']), 0, 0, 'L', $fill);
        $pdf->Cell(30, 9, utf8_decode($b['estado_conservacao']), 0, 0, 'L', $fill);
        $pdf->Cell(20, 9, utf8_decode($b['ano_aquisicao']), 0, 0, 'L', $fill);
        $pdf->Cell(30, 9, utf8_decode('R$ ' . number_format($b['valor'], 2, ',', '.')), 0, 0, 'R', $fill);
        $pdf->Ln();
        $fill = !$fill;
        $rowCount++;
    }

    // Total
    $total_valor = array_sum(array_column($lista_bens, 'valor'));
    $pdf->Ln(5);
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetFillColor(255, 179, 0);
    $pdf->Cell(150, 9, utf8_decode('Total:'), 1, 0, 'R', true);
    $pdf->Cell(30, 9, utf8_decode('R$ ' . number_format($total_valor, 2, ',', '.')), 1, 0, 'R', true);
    $pdf->Ln(10);

    // Rodapé
    $pdf->SetY(-30);
    $pdf->SetFont('Helvetica', 'I', 8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(255, 179, 0);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(5);
    $pdf->Cell(0, 8, utf8_decode('Sistema de Gestão Patrimonial EEEP Salaberga'), 0, 0, 'L');
    $pdf->Cell(0, 8, utf8_decode('Página ' . $pdf->PageNo()), 0, 0, 'R');

    $pdf->Output('D', 'relatorio_bens.pdf');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F5F5F5;
            --table-bg: #FFFFFF;
            --primary-color: #2E7D32;
            --green-color: #005A24;
            --secondary-color: #FFB300;
            --text-color: #1A1A1A;
            --shadow-color: rgba(0, 0, 0, 0.2);
            --input-bg: #F5F5F5;
            --filtro-btn: #E8ECEF;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .header-topo {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            padding: 30px 50px 20px 50px;
            box-sizing: border-box;
        }

        .seta-voltar {
            font-size: 1.5rem;
            color: #333;
            text-decoration: none;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            z-index: 100;
            transition: color 0.3s;
        }

        .seta-voltar:hover {
            color: var(--primary-color);
        }

        .titulo-principal {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--green-color);
            margin: 0 0 5px 0;
        }

        .titulo {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0 0 10px 0;
        }

        .header-topo p {
            font-size: 1rem;
            color: var(--text-color);
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: var(--table-bg);
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 25px;
            margin: 20px auto;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .logo {
            width: 190px;
            height: auto;
            display: block;
        }

        .filter-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .search-bar {
            width: 100%;
            max-width: 350px;
        }

        .filtro {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            background-color: var(--input-bg);
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px var(--shadow-color);
        }

        .filtro label {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-color);
            white-space: nowrap;
        }

        .filtro select {
            flex: 1;
            max-width: 200px;
            padding: 10px 12px;
            border-radius: 5px;
            font-size: 0.875rem;
            font-family: 'Poppins', sans-serif;
            background-color: #FFFFFF;
            border: 1px solid var(--primary-color);
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .filtro select:hover {
            border-color: var(--green-color);
            box-shadow: 0 0 5px rgba(0, 90, 36, 0.3);
        }

        .filtro select:focus {
            border-color: var(--green-color);
            box-shadow: 0 0 8px rgba(0, 90, 36, 0.5);
            outline: none;
        }

        .filtro button {
            background-color: var(--green-color);
            color: #FFFFFF;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .filtro button:hover {
            background-color: #1B5E20;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .filtro button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .button-container button {
            background-color: var(--green-color);
            color: #FFFFFF;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .button-container button:hover {
            background-color: #1B5E20;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .button-container button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .button-container button:nth-child(2) {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .button-container button:nth-child(2):hover {
            background-color: #FFA000;
        }

        .button-container button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .table-wrapper {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--table-bg);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #E5E5E5;
            background-color: #F5F5F5;
            color: var(--text-color);
            font-size: 0.875rem;
        }

        thead {
            background-color: var(--green-color);
        }

        th {
            background-color: var(--green-color);
            color: white;
            font-weight: 600;
            border-bottom: 2px solid var(--green-color);
        }

        th:first-child {
            border-top-left-radius: 12px;
        }

        th:last-child {
            border-top-right-radius: 12px;
        }

        tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        .no-results {
            text-align: center;
            font-size: 1rem;
            color: var(--text-color);
            padding: 20px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .header-topo {
                padding: 20px 20px 15px 20px;
            }

            .titulo-principal {
                font-size: 2rem;
                margin: 0 0 5px 0;
            }

            .titulo {
                font-size: 1.25rem;
                margin: 0 0 8px 0;
            }

            .header-topo p {
                font-size: 0.875rem;
            }

            .seta-voltar {
                font-size: 1.25rem;
                margin-bottom: 10px;
            }

            .container {
                width: 95%;
                padding: 15px;
            }

            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .logo {
                width: 150px;
                height: auto;
            }

            .filter-container {
                justify-content: center;
            }

            .search-bar {
                width: 100%;
                max-width: none;
            }

            .filtro {
                flex-direction: column;
                align-items: stretch;
                padding: 10px;
                gap: 10px;
            }

            .filtro label {
                font-size: 0.875rem;
                margin-bottom: 5px;
            }

            .filtro select {
                max-width: 100%;
                padding: 8px 12px;
                font-size: 0.8125rem;
            }

            .filtro button {
                padding: 8px;
                font-size: 0.8125rem;
            }

            .button-container {
                flex-direction: column;
                align-items: stretch;
            }

            .button-container button {
                width: 100%;
                padding: 10px;
                font-size: 0.8125rem;
            }

            th, td {
                padding: 8px;
                font-size: 0.75rem;
            }

            /* Hide less critical columns on smaller screens */
            th:nth-child(4), td:nth-child(4) { /* Ano Aquisição */
                display: none;
            }
        }

        @media (max-width: 480px) {
            .header-topo {
                padding: 15px 10px 10px 10px;
            }

            .titulo-principal {
                font-size: 1.5rem;
            }

            .titulo {
                font-size: 1rem;
            }

            .header-topo p {
                font-size: 0.75rem;
            }

            .seta-voltar {
                font-size: 1rem;
                margin-bottom: 8px;
            }

            .container {
                width: 98%;
                padding: 10px;
            }

            .logo {
                width: 120px;
            }

            .filtro label {
                font-size: 0.75rem;
            }

            .filtro select {
                font-size: 0.75rem;
                padding: 6px 10px;
            }

            .filtro button {
                font-size: 0.75rem;
                padding: 6px;
            }

            .button-container button {
                font-size: 0.75rem;
                padding: 8px;
            }

            th, td {
                padding: 6px;
                font-size: 0.7rem;
            }

            /* Hide additional column for very small screens */
            th:nth-child(3), td:nth-child(3) { /* Setor */
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header-topo">
        <a href="../includes/menu.php" class="seta-voltar">
            <i class="bi bi-arrow-left"></i>
            <svg class="fallback-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <path d="M15 18L9 12L15 6" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        <h1 class="titulo-principal">Gerar</h1>
        <h2 class="titulo">Relatórios</h2>
        <p>Aqui você pode gerar seus relatórios</p>
    </div>

    <div class="container">
        <div class="header-container">
            <img src="../img/logo-gp-2.png" alt="Logo" class="logo">
            <div class="filter-container">
                <form method="GET" class="search-bar">
                    <div class="filtro">
                        <label for="setor_id">Filtrar por Setor:</label>
                        <select name="setor_id" id="setor_id">
                            <option value="">Todos</option>
                            <?php foreach ($setores as $setor): ?>
                                <option value="<?= $setor['id_setor'] ?>" <?= ($filtro_setor == $setor['id_setor']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($setor['nome'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="cabecalho"></div>

        <?php if ($lista_bens): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tombamento</th>
                            <th>Setor</th>
                            <th>Ano Aquisição</th>
                            <th>Estado</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_bens as $bem): ?>
                            <tr>
                                <td><?= htmlspecialchars($bem['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($bem['numero_tombamento'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($bem['setor_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($bem['ano_aquisicao'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($bem['estado_conservacao'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($bem['valor'], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="button-container">
                <form method="GET">
                    <input type="hidden" name="setor_id" value="<?= htmlspecialchars($filtro_setor, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" name="gerar" value="1">Gerar Relatório</button>
                </form>
            </div>
        <?php else: ?>
            <p class="no-results">Nenhum item encontrado.</p>
            <div class="button-container">
                <form method="GET">
                    <input type="hidden" name="setor_id" value="<?= htmlspecialchars($filtro_setor, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" name="gerar" value="1" disabled>Gerar Relatório</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Debug: Check if the Bootstrap icon is present
        const arrowIcon = document.querySelector('.seta-voltar .bi-arrow-left');
        if (arrowIcon) {
            console.log('Bootstrap arrow icon found in the DOM');
        } else {
            console.log('Bootstrap arrow icon NOT found, switching to SVG fallback');
            document.querySelector('.seta-voltar .bi-arrow-left').style.display = 'none';
            document.querySelector('.seta-voltar .fallback-arrow').style.display = 'inline-block';
        }
    </script>
</body>
</html>