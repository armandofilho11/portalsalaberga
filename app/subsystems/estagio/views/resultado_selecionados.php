<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');
$select_model = new select_model();
$session = new sessions;
$session->autenticar_session();

if (isset($_POST['layout'])) {
    $session->quebra_session();
}

$select_model = new select_model();
$dados = $select_model->alunos_aptos();
$aprovados = [];
$stmt = $select_model->getConnection()->query("SELECT id_aluno FROM selecionado");
if ($stmt) {
    $aprovados = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4A90E2">
    <meta name="description" content="Resultados da Seleção - STGM">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Resultados da Seleção - STGM</title>
</head>
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
        color: #fff;
        min-height: 100vh;
        background-image: radial-gradient(circle at 10% 20%, rgba(52, 152, 219, 0.05) 0%, rgba(52, 152, 219, 0) 20%), radial-gradient(circle at 90% 80%, rgba(46, 204, 113, 0.05) 0%, rgba(46, 204, 113, 0) 20%);
        transition: background-color 0.3s ease;
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

    .main-header,
    header.bg-dark-50 {
        background: #232323;
        border-bottom: 1px solid #222;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .search-container {
        max-width: 500px;
        margin: 2rem auto 1.5rem auto;
        position: relative;
    }

    .search-bar {
        width: 100%;
        padding: 1rem 1.5rem;
        border-radius: 32px;
        border: 2px solid #222;
        background: #232323;
        color: #fff;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .search-bar:focus {
        outline: none;
        border-color: #00b348;
        box-shadow: 0 2px 8px rgba(0, 179, 72, 0.08);
    }

    .clear-search {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #aaa;
        font-size: 1.3rem;
        cursor: pointer;
        opacity: 0.7;
        transition: color 0.2s;
    }

    .clear-search:hover {
        color: #00b348;
        opacity: 1;
    }

    .filter-button {
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        font-size: 1rem;
        font-weight: 500;
        border: none;
        background: #232323;
        color: #fff;
        margin: 0 0.25rem;
        transition: background 0.2s, color 0.2s;
    }

    .filter-button.active {
        background: #00b348;
        color: #fff;
    }

    .filter-button:not(.active):hover {
        background: #222;
        color: #00b348;
    }

    .candidate-card {
        background: #232323;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        border-left: 5px solid transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.2s, border-color 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .candidate-card.approved {
        border-left-color: #10B981;
    }

    .candidate-card.waiting {
        border-left-color: #F59E0B;
    }

    .candidate-card.no_interview {
        border-left-color: #EF4444;
    }

    .candidate-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.13);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 1rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .status-badge.approved {
        background: rgba(16, 185, 129, 0.12);
        color: #10B981;
    }

    .status-badge.waiting {
        background: rgba(245, 158, 11, 0.12);
        color: #F59E0B;
    }

    .status-badge.no_interview {
        background: rgba(239, 68, 68, 0.12);
        color: #EF4444;
    }

    .area-badge,
    .company-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 1rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-right: 0.5rem;
        background: rgba(59, 130, 246, 0.10);
        color: #3B82F6;
    }

    .company-badge {
        background: rgba(139, 92, 246, 0.10);
        color: #8B5CF6;
    }

    @media (max-width: 900px) {
        .candidate-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 640px) {
        .sidebar {
            width: 100vw;
            min-width: unset;
        }

        .main-header,
        header.bg-dark-50 {
            padding: 0.5rem 1rem;
        }

        .candidate-card {
            padding: 1rem;
        }

        .search-container {
            margin: 1rem auto;
        }
    }
</style>

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
                    <a href="selecionados.php" class="sidebar-link">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link active">
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
                    <a href="selecionados.php" class="sidebar-link">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link active">
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
            <header class="shadow-md sticky top-0 z-30 border-b border-gray-800" style="background-color: rgba(45, 45, 45, 0.95);">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <h1 class="text-xl font-bold text-white text-center sm:text-left w-full">Resultados da Seleção</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400">
                            <i class="fas fa-user-circle mr-1"></i> Admin
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <!-- Breadcrumbs -->
                <div class="text-sm text-gray-400 mb-6 flex items-center">
                    <a href="./dashboard.php" class="hover:text-primary-400 transition-colors">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Resultados da Seleção</span>
                </div>

                <!-- Header -->


                <main class="container mx-auto px-4 py-8">
                    <div class="max-w-4xl mx-auto">
                        <div class="mb-8 text-center">
                            <h1 class="text-3xl font-bold mb-2">Resultados da Seleção</h1>
                            <p class="text-gray-600 dark:text-gray-400">Confira abaixo os resultados do processo seletivo</p>
                        </div>

                        <div class="search-container mb-6">
                            <input type="text" id="search-input" class="search-bar" placeholder="Buscar por nome...">
                            <button id="clear-search" class="clear-search" style="display: none;"></button>
                        </div>

                        <div class="mb-6">
                            <div class="flex flex-wrap gap-2 justify-center">
                                <button class="filter-button status-filter active" data-filter="all">Todos</button>
                                <button class="filter-button status-filter" data-filter="approved">Aprovados</button>
                                <button class="filter-button status-filter" data-filter="waiting">Aguardando</button>
                            </div>
                        </div>

                        <div class="grid gap-4" id="candidates-container">
                            <!-- Os candidatos serão inseridos aqui via JavaScript -->
                        </div>
                    </div>
                </main>

                <nav class="mobile-nav md:hidden mobile-nav-enter">
                    <div class="flex justify-around items-center">
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-home text-xl"></i>
                            <span class="text-xs">Início</span>
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-sign-in-alt text-xl"></i>
                            <span class="text-xs">Login</span>
                        </a>
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-sign-out-alt text-xl"></i>
                            <span class="text-xs">Sair</span>
                        </a>
                    </div>
                </nav>
        </div>

        <script>
            // Dados dos candidatos (convertido para JSON válido)
            const candidates = <?php echo json_encode(array_map(function ($dado) {
                                    return [
                                        'id' => $dado['id'],
                                        'name' => $dado['nome'],
                                        'status' => $dado['status'],
                                        'area' => $dado['perfil_opc1'] ?? null, // Usando perfil_opc1 como área
                                        'company' => $dado['empresa'] ?? null, // Nome da empresa
                                        'perfil_empresa' => $dado['perfil_empresa'] ?? null // Perfil da vaga/empresa
                                    ];
                                }, $dados)); ?>;

            // Função para renderizar os candidatos
            function renderCandidates(filteredCandidates = candidates) {
                const container = document.getElementById('candidates-container');
                container.innerHTML = '';

                if (filteredCandidates.length === 0) {
                    container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Nenhum candidato encontrado com os filtros selecionados.</p>
                    </div>
                `;
                    return;
                }

                filteredCandidates.forEach(candidate => {
                    const card = document.createElement('div');
                    card.className = `candidate-card ${candidate.status}`;

                    // Tradução do status
                    let statusText = '';
                    switch (candidate.status) {
                        case 'approved':
                            statusText = 'Aprovado';
                            break;
                        case 'waiting':
                            statusText = 'Aguardando';
                            break;
                        case 'no_interview':
                            statusText = 'Em espera';
                            break;
                    }

                    let cardContent = `
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold">${candidate.name}</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="status-badge ${candidate.status}">${statusText}</span>
                `;

                    // Adicionar área, empresa e perfil da empresa para todos

                    if (candidate.area && candidate.area !== candidate.perfil_empresa) cardContent += `<span class="area-badge">${candidate.area}</span>`;
                    if (candidate.perfil_empresa) cardContent += `<span class="company-badge">${candidate.perfil_empresa}</span>`;
                    if (candidate.company) cardContent += `<span class="company-badge">${candidate.company}</span>`;

                    cardContent += `
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">#${candidate.id.toString().padStart(2, '0')}</div>
                    </div>
                `;

                    card.innerHTML = cardContent;
                    container.appendChild(card);
                });
            }

            // Inicializar a página
            document.addEventListener('DOMContentLoaded', function() {
                // Renderizar candidatos iniciais
                renderCandidates();

                // Configurar busca
                const searchInput = document.getElementById('search-input');
                const clearButton = document.getElementById('clear-search');

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    clearButton.style.display = searchTerm ? 'block' : 'none';

                    filterCandidates();
                });

                clearButton.addEventListener('click', function() {
                    searchInput.value = '';
                    clearButton.style.display = 'none';
                    filterCandidates();
                });

                // Configurar filtros de status
                const statusFilters = document.querySelectorAll('.status-filter');
                statusFilters.forEach(button => {
                    button.addEventListener('click', function() {
                        statusFilters.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        filterCandidates();
                    });
                });

                // Função para aplicar todos os filtros
                function filterCandidates() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    const activeStatusFilter = document.querySelector('.status-filter.active').dataset.filter;

                    const filtered = candidates.filter(candidate => {
                        const matchesSearch = candidate.name.toLowerCase().includes(searchTerm);
                        if (activeStatusFilter === 'all') return matchesSearch;
                        if (activeStatusFilter === 'approved') return matchesSearch && candidate.status === 'approved';
                        if (activeStatusFilter === 'waiting') return matchesSearch && candidate.status === 'waiting';
                        return false;
                    });

                    renderCandidates(filtered);
                }

                // Animação para os cards
                const animateCards = () => {
                    const cards = document.querySelectorAll('.candidate-card');
                    cards.forEach((card, index) => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';

                        setTimeout(() => {
                            card.style.transition = 'all 0.3s ease-out';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 50);
                    });
                };

                animateCards();

                // Atualizar animação quando os filtros mudam
                const observer = new MutationObserver(animateCards);
                observer.observe(document.getElementById('candidates-container'), {
                    childList: true
                });
            });

            // Função para atualizar o status de um candidato (para uso administrativo)
            function updateCandidateStatus(id, newStatus, area = null, company = null) {
                const candidate = candidates.find(c => c.id === id);
                if (candidate) {
                    candidate.status = newStatus;
                    if (area) candidate.area = area;
                    if (company) candidate.company = company;
                    renderCandidates();
                }
            }
        </script>
</body>

</html>