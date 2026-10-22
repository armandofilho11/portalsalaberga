<?php
$conn = new mysqli('localhost', 'root', '', 'financeiro');
if ($conn->connect_error) die("Erro na conexão: " . $conn->connect_error);

// === GARANTE A PASTA DE PDF ===
$pasta_declaracoes = __DIR__ . '/declaracoes';
if (!is_dir($pasta_declaracoes)) {
    mkdir($pasta_declaracoes, 0755, true);
    chmod($pasta_declaracoes, 0755);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declarações de Observância - Sistema Financeiro</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #218838;
            --secondary-color: #ff9800;
            --accent-color: #43a047;
            --background-color: #f8f9fa;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
        }

        .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="inicial.php">
                <i class="fas fa-chart-line me-2"></i>
                Sistema Financeiro
            </a>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Erro ao excluir: <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Erro desconhecido'; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Documento(s) excluído(s) com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Declarações de Observância</h4>
                <div class="d-flex gap-2">
                    <a href="../inicial.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Voltar ao inicio
                    </a>
                    <a href="../relatorios/relatorio_observancia.php" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Gerar Relatório
                    </a>
                    <a href="../forms/form_observancia.html" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Novo Documento
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="formExcluir" method="post" action="excluir/excluir_observancia.php">
                    <div class="mb-3">
                        <button type="button" class="btn btn-danger btn-sm" id="btnAbrirModalExcluir">
                            <i class="fas fa-trash"></i> Excluir Selecionados
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 40px">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selecionarTodos">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Natureza</th>
                                    <th>NUP</th>
                                    <th>Escola</th>
                                    <th>Ordenador</th>
                                    <th>PDF</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM observancias ORDER BY criado_em DESC");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<tr>
                                            <td>
                                                <div class='form-check'>
                                                    <input class='form-check-input' type='checkbox' name='ids[]' value='{$row['id']}'>
                                                </div>
                                            </td>
                                            <td>{$row['id']}</td>
                                            <td>{$row['natureza']}</td>
                                            <td>{$row['nup']}</td>
                                            <td>{$row['escola']}</td>
                                            <td>{$row['ordenador']}</td>
                                            <td>";
                                        echo "<a href='/financeiro/app/main/view/gerar/declaracoes/" . basename($row['caminho_pdf']) . "' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-file-pdf me-1'></i>Ver PDF</a>";
                                        echo "</td>";
                                        echo "<td>{$row['criado_em']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>Nenhuma declaração de observância encontrada.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('selecionarTodos').addEventListener('change', function() {
        var checkboxes = document.getElementsByName('ids[]');
        for(var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
    </script>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="modalConfirmarExclusao" tabindex="-1" aria-labelledby="modalConfirmarExclusaoLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="modalConfirmarExclusaoLabel">Confirmar Exclusão</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            Tem certeza que deseja excluir as declarações selecionadas?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">Excluir</button>
          </div>
        </div>
      </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var btnAbrirModal = document.getElementById('btnAbrirModalExcluir');
        var btnConfirmar = document.getElementById('btnConfirmarExclusao');
        var formExcluir = document.getElementById('formExcluir');
        var modal = new bootstrap.Modal(document.getElementById('modalConfirmarExclusao'));

        btnAbrirModal.addEventListener('click', function(e) {
            e.preventDefault();
            modal.show();
        });

        btnConfirmar.addEventListener('click', function() {
            formExcluir.submit();
        });
    });
    </script>
</body>
</html>
<?php $conn->close(); ?>