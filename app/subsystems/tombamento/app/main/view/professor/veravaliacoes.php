<?php
require("../../model/modelprofessor.php");

if ($_SERVER['REQUEST_METHOD']) {
    $x = new professor;
    $resultado = $x->visualizar_avaliacoes();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Avaliações</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fef9f4 0%, #e8f5ec 100%);
            padding: 2rem 0;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, #065f46 0%, #f97316 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            border-bottom: none;
        }

        .card-body {
            padding: 2rem;
            background-color: #fff;
        }

        .btn-custom {
            background: #f97316;
            color: white;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            transition: 0.3s ease;
            border: none;
            text-decoration: none;
        }

        .btn-custom:hover {
            background: #065f46;
            color: white;
        }

        .btn-pdf,
        .btn-compartilhar {
            border-radius: 50px;
            font-size: 0.85rem;
            padding: 6px 14px;
            margin: 2px;
        }

        select.form-select {
            width: auto;
            display: inline-block;
            margin-left: 5px;
            font-size: 0.85rem;
        }

        .table th {
            background-color: #f1f3f5;
            font-weight: 600;
        }

        .table td, .table th {
            text-align: center;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9fafc;
        }

        .text-center-empty {
            font-style: italic;
            color: #888;
        }

        .actions-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                Lista de Avaliações
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="criarprova.php" class="btn btn-custom">➕ Nova Avaliação</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Nome</th>
                                <th>Dificuldade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($resultado)): ?>
                                <?php foreach ($resultado as $avaliacao): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($avaliacao['id']) ?></td>
                                        <td><?= htmlspecialchars($avaliacao['tipo']) ?></td>
                                        <td><?= htmlspecialchars($avaliacao['nome']) ?></td>
                                        <td><?= htmlspecialchars($avaliacao['dificuldade']) ?></td>
                                        <td>
                                            <div class="actions-wrapper">
                                                <form action="../../control/controleveravaliacoes.php" method="post" class="d-flex align-items-center justify-content-center gap-2">
                                                    <input type="hidden" name="avaliacao_id" value="<?= htmlspecialchars($avaliacao['id']) ?>">
                                                    <input type="hidden" name="tipo" value="<?= htmlspecialchars($avaliacao['tipo']) ?>">
                                                    <input type="hidden" name="nome" value="<?= htmlspecialchars($avaliacao['nome']) ?>">
                                                    <input type="hidden" name="dificuldade" value="<?= htmlspecialchars($avaliacao['dificuldade']) ?>">
                                                    <input type="hidden" name="ano" value="<?= htmlspecialchars($avaliacao['ano']) ?>">
                                                    
                                                    <select name="turma" class="form-select form-select-sm">
                                                        <option value="1B">1B</option>
                                                        <option value="2B">2B</option>
                                                        <option value="3B">3B</option>
                                                    </select>

                                                    <button type="submit" name="action" value="view" class="btn btn-outline-secondary btn-sm btn-pdf">
                                                        👁️ Visualizar PDF
                                                    </button>

                                                    <button type="submit" name="action" value="download" class="btn btn-outline-primary btn-sm btn-pdf">
                                                        ⬇️ Baixar PDF
                                                    </button>

                                                   
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-center-empty">Nenhuma avaliação encontrada</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
