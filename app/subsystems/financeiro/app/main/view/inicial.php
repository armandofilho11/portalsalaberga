<?php


require_once '../model/Declaracao.php';
$declaracao = new Declaracao();
$declaracoes = $declaracao->listarTodas();

// Função para processar os arquivos de declarações
function processarDeclaracoes() {
    $pasta = 'gerar/declaracoes/';
    $arquivos = scandir($pasta);
    $declaracoes = [];

    foreach ($arquivos as $arquivo) {
        if ($arquivo != '.' && $arquivo != '..' && pathinfo($arquivo, PATHINFO_EXTENSION) == 'pdf') {
            // Extrair informações do nome do arquivo
            $partes = explode('_', $arquivo);
            $tipo = $partes[0]; // tipo da declaração
            $nup = $partes[1]; // número do processo
            $data = substr($partes[2], 0, 8); // data
            $hora = substr($partes[2], 8, 6); // hora

            // Formatar a data
            $data_formatada = date('d/m/Y', strtotime($data));
            $hora_formatada = substr($hora, 0, 2) . ':' . substr($hora, 2, 2) . ':' . substr($hora, 4, 2);

            $declaracoes[] = [
                'tipo' => ucfirst($tipo),
                'nup' => $nup,
                'data' => $data_formatada,
                'hora' => $hora_formatada,
                'arquivo' => $arquivo
            ];
        }
    }

    // Ordenar por data e hora (mais recente primeiro)
    usort($declaracoes, function($a, $b) {
        $dataA = strtotime($a['data'] . ' ' . $a['hora']);
        $dataB = strtotime($b['data'] . ' ' . $b['hora']);
        return $dataB - $dataA;
    });

    return $declaracoes;
}

$declaracoesArquivos = processarDeclaracoes();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Financeiro</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #218838; /* verde escuro */
            --secondary-color: #ff9800; /* laranja */
            --accent-color: #43a047; /* verde claro */
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

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--accent-color);
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table th {
            font-weight: 600;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }

        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .stats-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .stats-card p {
            color: #666;
            margin-bottom: 0;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .btn-declaracao {
            background-color: #1976d2;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-declaracao:hover {
            background-color: #125a9c;
            color: white;
            transform: translateY(-2px);
        }

        .painel-botoes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 18px;
            margin-bottom: 32px;
        }

        .btn-declaracao {
            min-width: 240px;
            text-align: left;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.08);
        }

        @media (max-width: 700px) {
            .painel-botoes {
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }
            .btn-declaracao {
                min-width: 180px;
                width: 100%;
                text-align: center;
            }
        }

        .tabela-full-width {
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(-50vw + 50%);
            padding-left: 2vw;
            padding-right: 2vw;
            margin-bottom: 0;
        }
        .tabela-full-width .card {
            border-radius: 0;
            box-shadow: none;
        }
        .tabela-full-width .table {
            width: 100% !important;
            min-width: 900px;
        }
        @media (max-width: 900px) {
            .tabela-full-width .table {
                min-width: 600px;
            }
        }
        @media (max-width: 600px) {
            .tabela-full-width {
                padding-left: 0.5vw;
                padding-right: 0.5vw;
            }
            .tabela-full-width .table {
                min-width: 350px;
            }
        }
        .menu-hamburger-fixo {
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 1051;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.10);
        }
        @media (max-width: 600px) {
            .menu-hamburger-fixo {
                top: 8px;
                left: 8px;
                width: 42px;
                height: 42px;
            }
        }
        .menu-hamburger-fixo.offcanvas-open {
            display: none !important;
        }
        .navbar-btn {
            padding: 7px 18px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            margin-left: 10px;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            outline: none;
        }
        .btn-inicio {
            background: var(--secondary-color);
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.08);
        }
        .btn-inicio:hover {
            background: #ff9800cc;
            color: #fff !important;
        }
        .btn-sair {
            background: #e53935;
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(229, 57, 53, 0.08);
        }
        .btn-sair:hover {
            background: #b71c1c;
            color: #fff !important;
        }
    </style>
</head>
<body>
<!-- Botão Hamburger fixo no canto superior esquerdo -->
<button class="btn btn-primary menu-hamburger-fixo" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral">
    <i class="fas fa-bars"></i>
</button>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container position-relative">
            <a class="navbar-brand ms-lg-5" href="inicial.php">
                <i class="fas fa-chart-line me-2"></i>
                Sistema Financeiro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="navbar-btn btn-inicio" href="inicial.php">
                            <i class="fas fa-home me-1"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="navbar-btn btn-sair" href="login.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Menu Lateral -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menuLateralLabel">Menu de Declarações</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body d-flex flex-column gap-2">
        <a href="listar/listar_observancia.php" class="btn btn-primary text-start"><i class="fas fa-check-circle me-2"></i>Declarações de Observância</a>
        <a href="listar/listar_validacao.php" class="btn btn-primary text-start"><i class="fas fa-file-alt me-2"></i>Validações de Documentação</a>
        <a href="listar/listar_loa.php" class="btn btn-primary text-start"><i class="fas fa-file-invoice-dollar me-2"></i>Declarações LOA</a>
        <a href="listar/listar_autoridade.php" class="btn btn-primary text-start"><i class="fas fa-user-tie me-2"></i>Declarações da Autoridade</a>
        <a href="listar/listar_atendimento.php" class="btn btn-primary text-start"><i class="fas fa-handshake me-2"></i>Declarações de Atendimento</a>
        <a href="listar/listar_pca.php" class="btn btn-primary text-start"><i class="fas fa-file-contract me-2"></i>Declarações PCA</a>
      </div>
    </div>

    <!-- Remover container centralizador e usar full width -->
    <div class="tabela-full-width">
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipo_mensagem']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['mensagem']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']); ?>
        <?php endif; ?>

        <h1 class="mb-4">Painel de Declarações</h1>

       

        <!-- Main Content -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-list me-2"></i>Últimas Declarações</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover w-100" id="tabelaDeclaracoes">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>NUP</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($declaracoesArquivos as $d): ?>
                            <tr>
                                <td><?php echo $d['tipo']; ?></td>
                                <td><?php echo $d['nup']; ?></td>
                                <td><?php echo $d['data']; ?></td>
                                <td><?php echo $d['hora']; ?></td>
                                <td>
                                    <a href="visualizar_documento.php?arquivo=<?php echo $d['arquivo']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Visualizar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaDeclaracoes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
                },
                order: [[2, 'desc'], [3, 'desc']], // Ordenar por data e hora
                pageLength: 10,
                columns: [
                    { width: "20%" },
                    { width: "25%" },
                    { width: "20%" },
                    { width: "15%" },
                    { width: "20%" }
                ]
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var offcanvas = document.getElementById('menuLateral');
        var hamburger = document.querySelector('.menu-hamburger-fixo');
        if (offcanvas && hamburger) {
            offcanvas.addEventListener('show.bs.offcanvas', function () {
                hamburger.classList.add('offcanvas-open');
            });
            offcanvas.addEventListener('hidden.bs.offcanvas', function () {
                hamburger.classList.remove('offcanvas-open');
            });
        }
    });
    </script>
</body>
</html>
