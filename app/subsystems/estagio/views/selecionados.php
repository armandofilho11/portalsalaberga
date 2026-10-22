<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');
$select_model = new select_model();
$session = new sessions;
$session->autenticar_session();

// --- COLOQUE O CÓDIGO AQUI ---
if (isset($_POST['aprovar']) && !empty($_POST['selecionados'])) {
    $selecionados = [];
    foreach ($_POST['selecionados'] as $valor) {
        list($id_aluno, $id_vaga) = explode('|', $valor);
        $selecionados[] = [
            'id_aluno' => $id_aluno,
            'id_vaga' => $id_vaga
        ];
    }
    $qtd = $select_model->aprovar_selecionados($selecionados);
}
// --- FIM DO CÓDIGO ---

if (isset($_POST['layout'])) {
    $session->quebra_session();
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<!-- ...restante do seu HTML... -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a1a1a">
    <meta name="description" content="Alunos Selecionados - Sistema de Estágio">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Alunos Selecionados - Sistema de Estágio</title>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#007A33',
                            '50': '#00FF6B',
                            '100': '#00EB61',
                            '200': '#00C250',
                            '300': '#00993F',
                            '400': '#00802F',
                            '500': '#007A33',
                            '600': '#00661F',
                            '700': '#00521A',
                            '800': '#003D15',
                            '900': '#002910'
                        },
                        secondary: {
                            DEFAULT: '#FFA500',
                            '50': '#FFE9C0',
                            '100': '#FFE1AB',
                            '200': '#FFD183',
                            '300': '#FFC15A',
                            '400': '#FFB232',
                            '500': '#FFA500',
                            '600': '#C78000',
                            '700': '#8F5C00',
                            '800': '#573800',
                            '900': '#1F1400'
                        },
                        dark: {
                            DEFAULT: '#1a1a1a',
                            '50': '#2d2d2d',
                            '100': '#272727',
                            '200': '#232323',
                            '300': '#1f1f1f',
                            '400': '#1a1a1a',
                            '500': '#171717',
                            '600': '#14',
                            '700': '#111111',
                            '800': '#0e0e0e',
                            '900': '#0a0a0a'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
            min-height: 100vh;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(0, 122, 51, 0.03) 0%, rgba(0, 122, 51, 0) 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 165, 0, 0.03) 0%, rgba(255, 165, 0, 0) 20%);
        }

        .sidebar {
            background-color: rgba(45, 45, 45, 0.95);
            background-image: linear-gradient(to bottom, #2d2d2d, #222222);
            border-right: 1px solid rgba(0, 122, 51, 0.2);
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            color: #ffffff;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 122, 51, 0.2);
            color: #00C250;
            transform: translateX(5px);
        }

        .sidebar-link.active {
            background-color: rgba(0, 122, 51, 0.3);
            color: #00FF6B;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 122, 51, 0.15);
        }

        .custom-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .custom-btn-primary {
            background: linear-gradient(135deg, #007A33 0%, #009940 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 122, 51, 0.3);
        }

        .custom-btn-primary:hover {
            background: linear-gradient(135deg, #00993F 0%, #00B64B 100%);
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 122, 51, 0.4);
        }

        .custom-btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .custom-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .custom-input:focus {
            outline: none;
            border-color: #007A33;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2);
        }

        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .custom-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em;
        }

        .custom-select:focus {
            outline: none;
            border-color: #007A33;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2);
        }

        .custom-select option {
            background: #2d2d2d;
            color: white;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0.5rem 0;
        }

        .custom-table th {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-table td {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
        }

        .custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-card {
            background: rgba(45, 45, 45, 0.97);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            border: 1.5px solid #007A3333;
            transition: box-shadow 0.3s, border 0.3s;
        }

        .custom-card:hover {
            box-shadow: 0 12px 32px rgba(0,122,51,0.18);
            border: 1.5px solid #00FF6B;
        }

        .custom-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .badge-info {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.4s ease-out forwards;
        }

        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .custom-checkbox:checked {
            background: #007A33;
            border-color: #007A33;
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox:hover {
            border-color: #007A33;
            background: rgba(0, 122, 51, 0.1);
        }

        @media (max-width: 640px) {
            .custom-table {
                margin: 0;
            }

            .custom-table th,
            .custom-table td {
                padding: 0.5rem;
            }

            .custom-card {
                padding: 1rem;
                border-radius: 10px;
                margin: 0.5rem 0;
            }
            .custom-btn-primary {
                position: fixed;
                bottom: 1rem;
                left: 1rem;
                right: 1rem;
                width: calc(100% - 2rem);
                max-width: 400px;
                margin: 0 auto;
                z-index: 100;
            }
        }
    </style>
</head>

<body class="select-none">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar w-64 hidden md:block">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center gap-2 mb-6">
                    <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                        <div class="h-0.5 bg-primary-500/20 rounded-full mt-1"></div>
                    </div>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link">
                        <i class="fas fa-briefcase w-5 mr-3"></i>
                        Vagas
                    </a>
                    <a href="selecionados.php" class="sidebar-link active">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link">
                        <i class="fa fa-user-circle w-5 mr-3"></i>
                        Resultados 
                    </a>
                    
                </nav>
                <div class="mt-auto pt-4 border-t border-gray-700">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        Configurações
                    </a>
                    <form action="" method="post">
                        <button type="submit" name="layout" class="sidebar-link text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <div class="md:hidden fixed top-4 left-4 z-50">
            <button id="sidebarToggle" class="bg-dark-50 p-2 rounded-lg shadow-md hover:bg-dark-100 transition-all">
                <i class="fas fa-bars text-primary-400"></i>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden sidebar">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                        <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                    </div>
                    <button id="closeSidebar" class="p-2 text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link">
                        <i class="fas fa-briefcase w-5 mr-3"></i>
                        Vagas
                    </a>
                    <a href="selecionados.php" class="sidebar-link active">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link">
                        <i class="fa fa-user-circle w-5 mr-3"></i>
                        Resultados 
                    </a>
                    
                </nav>
                <div class="mt-auto pt-4 border-t border-gray-700">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        Configurações
                    </a>
                    <form action="" method="post">
                        <button type="submit" name="layout" class="sidebar-link text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-dark-400">
            <!-- Header -->
            <header class="bg-dark-50 shadow-md sticky top-0 z-30 border-b border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <h1 class="text-xl font-bold text-white text-center sm:text-left w-full">Alunos Selecionados</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400">
                            <i class="fas fa-user-circle mr-1"></i> Admin
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <!-- Breadcrumbs -->
                <div class="text-sm text-gray-400 mb-6 flex items-center">
                    <a href="dashboard.php" class="hover:text-primary-400 transition-colors">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Alunos Selecionados</span>
                </div>

                <!-- Tabela de Alunos Selecionados -->
                <?php if (isset($qtd)) { ?>
                    <div class="fixed top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <?= $qtd ?> aluno(s) aprovado(s) com sucesso!
                    </div>
                <?php } ?>
                <form method="post" action="">
                <?php
                $vagas = $select_model->vagas_com_alunos();
                foreach ($vagas as $vaga) {
                    $id_vaga = $vaga['id_vaga'];
                    $alunos = $select_model->alunos_selecionados_estagio($id_vaga);
                    $nome_empresa = $select_model->nome_empresa_por_vaga($id_vaga);
                ?>
                    <div class="custom-card fade-in mb-6 shadow-lg border border-primary-500">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-building text-primary-400 text-xl"></i>
                                <h3 class="text-lg font-bold text-primary-400"><?php echo htmlspecialchars($nome_empresa); ?></h3>
                            </div>
                            <span class="custom-badge badge-info">
                                <i class="fas fa-users text-xs mr-1"></i>
                                Alunos Selecionados
                            </span>
                        </div>
                        <div class="overflow-x-auto rounded-lg">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th class="w-10"></th>
                                        <th>Aluno</th>
                                        <th class="w-20">ID</th>
                                        <th class="w-24">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alunos as $aluno) { ?>
                                        <tr class="hover:bg-primary-900/10 transition-colors">
                                            <td>
                                                <?php if (isset($aluno['status']) && $aluno['status'] === 'approved'): ?>
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                <?php else: ?>
                                                    <input type="checkbox" name="selecionados[]" value="<?php echo $aluno['id'] . '|' . $id_vaga; ?>" class="custom-checkbox">
                                                <?php endif; ?>
                                            </td>
                                            <td class="font-semibold text-white">
                                                <?php echo htmlspecialchars($aluno['nome']); ?>
                                                <?php if (isset($aluno['contato']) && !empty($aluno['contato'])): ?>
                                                    <?php 
                                                    $numero_whatsapp = preg_replace('/\D/', '', $aluno['contato']);
                                                    $mensagem = isset($aluno['status']) && $aluno['status'] === 'approved' 
                                                        ? "Olá " . urlencode($aluno['nome']) . "! Parabéns! Você foi aprovado na entrevista da " . urlencode($nome_empresa)
                                                        : "Olá " . urlencode($aluno['nome']) . ", infelizmente você não foi aprovado na entrevista da " . urlencode($nome_empresa);
                                                    $link_whatsapp = "https://wa.me/55{$numero_whatsapp}?text={$mensagem}";
                                                    ?>
                                                    <a href="<?php echo $link_whatsapp; ?>" target="_blank" class="ml-2">
                                                        <i class="fab fa-whatsapp <?php echo isset($aluno['status']) && $aluno['status'] === 'approved' ? 'text-green-500' : 'text-gray-500'; ?>"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="ml-2">
                                                        <i class="fab fa-whatsapp text-gray-500"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-xs text-gray-400"><?php echo htmlspecialchars($aluno['id']); ?></td>
                                            <td>
                                                <?php if (isset($aluno['status']) && $aluno['status'] === 'approved'): ?>
                                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-600 text-white">Aprovado</span>
                                                <?php else: ?>
                                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-600 text-white">Pendente</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
                    <button type="submit" name="aprovar" class="custom-btn custom-btn-primary mt-4 w-full md:w-auto fixed md:static bottom-4 left-0 right-0 mx-auto max-w-xs z-50 shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Aprovar Selecionados
                    </button>
                </form>
            </main>
        </div>
    </div>

    <script>
        // Sidebar mobile toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const closeSidebar = document.getElementById('closeSidebar');
        const mobileSidebar = document.getElementById('mobileSidebar');

        sidebarToggle.addEventListener('click', () => {
            mobileSidebar.classList.remove('-translate-x-full');
            document.body.style.overflow = 'hidden';
        });

        closeSidebar.addEventListener('click', () => {
            mobileSidebar.classList.add('-translate-x-full');
            document.body.style.overflow = 'auto';
        });

        mobileSidebar.addEventListener('click', (e) => {
            if (e.target === mobileSidebar) {
                mobileSidebar.classList.add('-translate-x-full');
                document.body.style.overflow = 'auto';
            }
        });

        // Função para selecionar todos os checkboxes de uma vaga
        document.querySelectorAll('[id^="selectAll"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const table = this.closest('.custom-card').querySelector('table');
                const checkboxes = table.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });
    </script>
</body>

</html>