<?php
require_once __DIR__ . '/../models/select_model.php';
$selectModel = new select_model();

$totalBooks = $selectModel->count_catalogo_total();
$totalAvailable = $selectModel->count_exemplares_disponiveis();
$activeLoans = $selectModel->count_emprestimos_ativos();
$totalAuthors = $selectModel->count_autores_total();
$totalGenres = $selectModel->count_generos_total();
$topReaders = $selectModel->get_top_readers(10);
$recentBooks = $selectModel->get_recent_books(5);
$categoryDistribution = $selectModel->get_category_distribution(6);
$genreDistribution = $selectModel->get_genre_distribution(6);
$categoryTotal = array_sum(array_column($categoryDistribution, 'total')) ?: 1;
$genreTotal = array_sum(array_column($genreDistribution, 'total')) ?: 1;
$lowStockCount = $selectModel->count_low_stock_books(3);
$overdueCount = $selectModel->count_overdue_loans();
$months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
$currentDate = date('j') . ' de ' . $months[intval(date('n')) - 1] . ' de ' . date('Y');
$monthBarValues = $selectModel->get_monthly_loan_counts();
$weeklyBarValues = $selectModel->get_weekly_loan_counts();
$weekLabels = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex'];

// Cores para o gráfico de donut
$genreColors = ['#008C45', '#FFA500', '#FFD700', '#FF6B6B', '#4ECDC4', '#95A5A6'];

// Atribuir cores aos gêneros na ordem
foreach ($genreDistribution as $index => &$genre) {
    $genre['cor'] = $genreColors[$index % count($genreColors)];
}
unset($genre); // Limpar referência para evitar bugs futuros

// Atribuir cores às categorias na ordem
foreach ($categoryDistribution as $index => &$category) {
    $category['cor'] = $genreColors[$index % count($genreColors)];
}
unset($category);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/img/icon.png" type="image/x-icon">
    <title>Dashboard - Sistema de Biblioteca</title>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        primary: '#4CAF50',
                        secondary: '#FFB74D',
                    },
                },
            },
        };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Anton&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        :root {
            --bg-principal: #f8fafc;
            --azul-escuro: #0f172a;
            --azul-card: #1e293b;
            --texto-claro: #f8fafc;
            --texto-escuro: #1e293b;
            --texto-mutado: #64748b;
            --branco: #ffffff;
            --borda: #e2e8f0;
            --borda-clara: #bfdbd0; 
            --sucesso: #4DA73A;
            --sucesso-claro: #F8FCF9;
            --alerta: #f59e0b;
            --perigo: #ef4444;
            /* --azul-destaque: #3b82f6; */
        }
        .gradient-text {
            background: linear-gradient(45deg, #008C45, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-principal);
            color: var(--texto-escuro);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .dashboard-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--azul-escuro);
        }

        .dashboard-header .date {
            font-size: 14px;
            color: var(--texto-mutado);
            font-weight: 500;
        }

        /* Grid de Cards Principais - Estilo da Imagem */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .card-indicador {
            background-color: var(--branco);
            border: 1px solid var(--borda);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            opacity: 0;
            transform: translateY(20px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-indicador:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Card de Destaque Escuro igual ao topo da Imagem */
        .card-indicador.destaque {
            background-color: var(--azul-card);
            color: var(--texto-claro);
            border: none;
        }

        .card-indicador.destaque .card-title {
            color: #94a3b8;
        }

        .card-indicador.destaque .card-icon {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--branco);
        }

        .card-header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--texto-mutado);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--azul-destaque);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .card-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .card-footer {
            font-size: 12px;
            color: var(--texto-mutado);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .card-indicador.destaque .card-footer {
            color: #94a3b8;
        }

        .badge-status {
            padding: 2px 8px;
            border-radius: 12px;
            color: #94a3b8;
            font-weight: bold;
            font-size: 14px;
        }

        /* .badge-status.sucesso { background-color: rgba(16, 185, 129, 0.1); color: var(--sucesso); }
        .badge-status.alerta { background-color: rgba(245, 158, 11, 0.1); color: var(--alerta); }
        .badge-status.perigo { background-color: rgba(239, 68, 68, 0.1); color: var(--perigo); } */

        /* Seção Inferior: Gráficos e Listas Laterais */
        .dashboard-row {
            display: grid;
            gap: 16px;
        }

        @media (max-width: 1024px) {
            .dashboard-row {
                grid-template-columns: 1fr;
            }
        }

        .painel-principal {
            background-color: var(--branco);
            border: 1px solid var(--borda);
            border-radius: 16px;
            padding: 12px 20px 16px 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            opacity: 0;
            transform: translateY(20px);
        }

        .painel-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--azul-escuro);
            margin-top: 6px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Gráfico de Barras - Novo Layout */
        .chart-wrapper {
            display: flex;
            gap: 12px;
        }

        .chart-y-axis {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: right;
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
            height: 260px;
            width: 30px;
            padding-right: 8px;
            user-select: none;
        }

        .chart-container {
            flex: 1;
            position: relative;
            height: 260px;
        }

        .chart-grid {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            pointer-events: none;
        }

        .chart-grid-line {
            width: 100%;
            border-bottom: 1px solid #f1f5f9;
        }

        .chart-grid-line:last-child {
            border-bottom-color: #cbd5e1;
        }

        .chart-bars-container {
            position: absolute;
            inset: 0;
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            align-items: flex-end;
            gap: 0;
            padding: 0 8px;
            /* overflow-x: auto; */
        }

        .chart-bar-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
            padding: 0 2px;
        }

        .chart-bar-label {
            font-size: 11px;
            color: #334155;
            margin-bottom: 4px;
            line-height: 1.1;
        }

        .chart-bar {
            width: 15px;
            background-color: #008C45;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            height: 0;
            transition: height 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .chart-labels {
            margin-top: 8px;
            padding-left: 46px;
            /* overflow-x: auto; */
        }

        .chart-labels-container {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 6px;
            padding: 0;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            user-select: none;
        }

        .chart-labels {
            margin-top: 8px;
            padding-left: 46px;
        }

        .chart-labels-y-space {
            width: 50px;
        }

        .chart-labels-container {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 6px;
            padding: 0;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            user-select: none;
        }

        .chart-label {
            width: 100%;
            text-align: center;
        }

        /* Listas Laterais e Alertas */
        .lista-livros, .lista-generos {
            list-style: none;
        }

        .lista-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--borda);
        }

        .lista-item:last-child {
            border-bottom: none;
        }

        .item-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--azul-escuro);
        }

        .item-info p {
            font-size: 12px;
            color: var(--texto-mutado);
        }

        /* Classes de Animação Ativadas pelo JS */
        .show {
            opacity: 1 !important;
            transform: translateY(0) !important;
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        /* Carousel Styles */
        .carousel-container {
            display: flex;
            align-items: center;
        }

        .carousel-track {
            gap: 12px;
            padding: 0 36px;
        }

        .carousel-item {
            flex-shrink: 0;
        }

        .carousel-btn {
            border: none;
            cursor: pointer;
            user-select: none;
            will-change: transform;
        }

        .carousel-btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            box-shadow: none;
            background-color: #94a3b8;
            color: #ffffff;
        }

        .carousel-btn:not(:disabled):active {
            transform: translate(-50%, -50%) scale(0.95);
        }

        .carousel-btn-prev {
            left: 8px;
        }

        .carousel-btn-next {
            right: 8px;
        }

        .carousel-dot {
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            width: 0.625rem;
            height: 0.625rem;
            background-color: #008C45;
        }

        .carousel-dot:hover:not(.active) {
            background-color: #cbd5e1;
        }
    </style>
</head>

<body class="pr-0 lg:ml-0 xl:ml-6 2xl:pl-6 2xl:pt-6 lg:px-6">

    <div class="dashboard-header flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 px-6 sm:px-0 pt-24 lg:pt-4 md:pt-12 pb-2 sm:mr-0">

    <section class="flex-1 flex flex-col items-center lg:items-start text-center lg:text-start gap-6">
        <section class="pt-24 lg:pt-0 items-center text-center">
            <div class="space-y-4 flex flex-col items-center lg:items-start text-center lg:text-start animate-panel transform translate-y-12 opacity-0">
                <h2
                    class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold gradient-text"
                    style="font-family: 'Anton', sans-serif;"
                >
                    Gerenciamento de Acervo
                </h2>
                <p
                    class="text-sm sm:text-lg text-gray-600 max-w-2xl"
                    style="font-family: 'Poppins', sans-serif;"
                >
                    Acompanhe e gerencie todos os itens do acervo da sua biblioteca.
                </p>
            </div>
        </section>
    </section>

    <div class="flex justify-start lg:justify-end">
        <div class="date flex items-center gap-3 text-sm sm:text-base border border-[var(--borda-clara)] px-4 py-2 rounded-lg transform translate-y-12 opacity-0 animate-panel bg-[var(--sucesso-claro)] shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <svg 
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 64 64"
                    fill="none"
                    stroke="#4DA73A"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-6 h-6"
                >
                    <!-- Corpo do calendário -->
                    <rect x="10" y="14" width="44" height="40" rx="6"/>

                    <!-- Barra superior -->
                    <path d="M10 24H54"/>

                    <!-- Pinos superiores -->
                    <path d="M20 10V18"/>
                    <path d="M44 10V18"/>

                    <!-- Dias -->
                    <circle cx="20" cy="34" r="2"/>
                    <circle cx="32" cy="34" r="2"/>
                    <circle cx="44" cy="34" r="2"/>

                    <circle cx="20" cy="44" r="2"/>
                    <circle cx="32" cy="44" r="2"/>
                    <circle cx="44" cy="44" r="2"/>
                </svg>
                <span class="font-medium text-gray-500">
                    <?php echo $currentDate; ?>
                </span>
                <!-- <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg> -->
            </div>
        </div>
    </div>

    <div class="cards-grid grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4">
        
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-full transform translate-y-12 opacity-0 animate-panel hover:shadow-lg transition-all duration-300 ease-in-out">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border bg-[#EEF7F0] text-green-600 shrink-0">
                    <i class="fas fa-book-open text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Livros Cadastrados</span>
                    <div class="text-3xl font-bold text-ceara-green counter mb-1" data-target="<?php echo $totalBooks; ?>">0</div>
                    <span class="text-xs text-gray-400 block">Total de livros no acervo</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-full transform translate-y-12 opacity-0 animate-panel hover:shadow-lg transition-all duration-300 ease-in-out">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border bg-[#EEF7F0] text-green-600 shrink-0">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Exemplares Disponíveis</span>
                    <div class="text-3xl font-bold text-ceara-green counter mb-1" data-target="<?php echo $totalAvailable; ?>">0</div>
                    <span class="text-xs text-gray-400 block">Exemplares disponíveis</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-[100%] transform translate-y-12 opacity-0 animate-panel hover:shadow-lg transition-all duration-300 ease-in-out">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border border-orange-100 bg-orange-50 text-[#FFA500] shrink-0">
                    <svg 
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 64 64"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-9 h-9 text-ceara-orange"
                    >
                        <!-- Seta superior -->
                        <path d="M16 24C19 17 24 15 33 15H46" />
                        <path d="M40 9L48 15L40 21" />

                        <!-- Seta inferior -->
                        <path d="M48 40C45 47 40 49 31 49H18" />
                        <path d="M24 43L16 49L24 55" />
                    </svg>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Empréstimos Ativos</span>
                    <div class="text-3xl font-bold text-ceara-orange counter mb-1" data-target="<?php echo $activeLoans; ?>">0</div>
                    <span class="text-xs text-gray-400 block">Empréstimos realizados</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-full transform translate-y-12 opacity-0 animate-panel hover:shadow-lg transition-all duration-300 ease-in-out">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border bg-[#EEF7F0] text-green-600 shrink-0">
                    <i class="fas fa-user text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Autores Cadastrados</span>
                    <div class="text-3xl font-bold text-ceara-green counter mb-1" data-target="<?php echo $totalAuthors ?? 326; ?>">0</div>
                    <span class="text-xs text-gray-400 block">Autores no sistema</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-full transform translate-y-12 opacity-0 animate-panel hover:shadow-lg transition-all duration-300 ease-in-out">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border border-orange-100 bg-orange-50 text-[#FFA500] shrink-0">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Gêneros Literários</span>
                    <div class="text-3xl font-bold text-ceara-orange counter mb-1" data-target="<?php echo $totalGenres; ?>">0</div>
                    <span class="text-xs text-gray-400 block">Gêneros cadastrados</span>
                </div>
            </div>
        </div>

    </div>

    <div class="dashboard-row grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="painel-principal animate-panel w-auto h-[400px] relative">
            <div class="painel-title">
                <span class="textLivros">Livros por Gênero</span>
                <select id="donutTypeSelect" name="" class="font-medium text-gray-500 bg-transparent focus:outline-none w-auto h-auto cursor-pointer flex items-center gap-2 border border-gray-300 bg-white px-2 py-1 rounded-lg text-sm sm:text-base shadow-sm hover:shadow-md transition-all duration-300">
                    <option value="category">Por categoria</option>
                    <option value="genre">Por gênero</option>
                </select>
            </div>
            
            <div class="flex flex-col xl:flex-row w-full min-h-[300px] items-center justify-center xl:gap-4 2xl:gap-8 px-0 lg:px-2">
                <div class="flex-1 flex items-center justify-center">
                    <div class="w-[180px] h-[180px] xl:w-[170px] xl:h-[170px] 2xl:w-[210px] 2xl:h-[210px] justify-center items-center relative" style="transform: translateY(-18px);">
                        <canvas id="genreChart"></canvas>
                    </div>
                </div>
                
                <div class="flex-1 h-full justify-center flex flex-col gap-4">
                    <ul id="donutLegend" class="lg:space-y-4 text-sm lg:text-md grid grid-cols-2 lg:grid-cols-1 gap-6 lg:gap-3"></ul>
                </div>
            </div>
        </div>

        <div class="painel-principal animate-panel lg:w-full xl:w-full h-[400px] xl:overflow-x-hidden relative">
            <div class="painel-title">
                <span class="textEmprestimos">Empréstimos por Mês</span>
                <select id="periodSelect" name="" class="font-medium text-gray-500 bg-transparent focus:outline-none w-auto h-auto cursor-pointer flex items-center gap-2 border border-gray-300 bg-white px-2 py-1 rounded-lg text-sm sm:text-base shadow-sm hover:shadow-md transition-all duration-300">
                    <option value="month">Este mês</option>
                    <option value="week">Esta semana</option>
                </select>
            </div>
            <div class="chart-wrapper">
                <div class="chart-y-axis">
                    <div>40</div>
                    <div>30</div>
                    <div>20</div>
                    <div>10</div>
                    <div>0</div>
                </div>

                <div class="chart-container">
                    <div class="chart-grid">
                        <div class="chart-grid-line"></div>
                        <div class="chart-grid-line"></div>
                        <div class="chart-grid-line"></div>
                        <div class="chart-grid-line"></div>
                        <div class="chart-grid-line"></div>
                    </div>

                    <div class="chart-bars-container"></div>
                </div>
            </div>

            <div class="chart-labels">
                <div class="chart-labels-y-space"></div>
                <div class="chart-labels-container"></div>
            </div>
        </div>

        <div class="painel-principal animate-panel lg:w-full xl:w-full h-[400px] overflow-y-auto relative">
            <div class="painel-title">
                <span>Livros Recentemente Cadastrados</span>
            </div>
            <ul class="lista-livros">
                <?php if (!empty($recentBooks)): ?>
                    <?php foreach ($recentBooks as $book): ?>
                        <li class="lista-item">
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($book['titulo_livro']); ?></h4>
                                <p><?php echo htmlspecialchars($book['editora'] ?? 'Editora Geral'); ?></p>
                            </div>
                            <div class="badge-status border border-[#E6E9EB] bg-[#EEF7F0] text-[var(--sucesso)] text-xs font-bold px-2 py-1 rounded-full">
                                Novo
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="lista-item" style="color: var(--texto-mutado); font-size: 13px;">Nenhum livro recente.</li>
                <?php endif; ?>
            </ul>
        </div>

        
        <div class="lg:col-span-3 grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

            <!-- CARD RECURSOS -->
            <div class="painel-principal animate-panel lg:w-full xl:w-[675px] 2xl:w-[850px] h-[240px] relative">
                <div class="painel-title">
                    <span>Recursos Principais</span>
                </div>

                <!-- Desktop: Grid Layout -->
                <div class="hidden lg:flex xl:flex-row 2xl:flex-wrap xl:justify-between gap-4 2xl:gap-6 transition-all duration-300">
                    
                    <a href="cadastrar_livro.php?from=gerenciar_acervo" class="w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                        <div class="flex flex-col items-center justify-center h-full gap-2">
                            <i class="fas fa-book-open text-ceara-green text-3xl"></i>
                            <span class="text-center text-gray-700 font-semibold">
                                Cadastrar <br> Livro
                            </span>
                        </div>
                    </a>

                    <a href="consultar_livro.php?from=gerenciar_acervo" class="w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                        <div class="flex flex-col items-center justify-center h-full gap-2">
                            <i class="fas fa-search text-ceara-green text-3xl"></i>
                            <span class="text-center text-gray-700 font-semibold">
                                Consultar <br> Livro
                            </span>
                        </div>
                    </a>

                    <a href="editar_livro.php?from=gerenciar_acervo" class="w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                        <div class="flex flex-col items-center justify-center h-full gap-2">
                            <i class="fas fa-edit text-ceara-green text-3xl"></i>
                            <span class="text-center text-gray-700 font-semibold">
                                Editar <br> Livro
                            </span>
                        </div>
                    </a>

                    <a href="excluir_livro.php?from=gerenciar_acervo" class="w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                        <div class="flex flex-col items-center justify-center h-full gap-2">
                            <i class="fas fa-trash-alt text-ceara-green text-3xl"></i>
                            <span class="text-center text-gray-700 font-semibold text-sm">
                                Excluir <br> Livro
                            </span>
                        </div>
                    </a>

                    <a href="relatorios/decisao.php?from=gerenciar_acervo" class="w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                        <div class="flex flex-col items-center justify-center h-full gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" 
                                fill="currentColor" class="w-10 h-10 text-ceara-green">
                                <rect x="8" y="30" width="12" height="26" rx="3"/>
                                <rect x="26" y="12" width="12" height="44" rx="3"/>
                                <rect x="44" y="22" width="12" height="34" rx="3"/>
                            </svg>
                            <span class="text-center text-gray-700 font-semibold">
                                Relatórios <br> do Acervo
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Mobile: Carousel -->
                <div class="lg:hidden relative h-[140px]">
                    <div class="carousel-container relative overflow-hidden w-full h-full">
                        <div class="carousel-track flex transition-transform duration-500 ease-in-out h-full" id="resourcesCarousel">
                            
                            <a href="cadastrar_livro.php?from=gerenciar_acervo" class="carousel-item flex-shrink-0 w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                                <div class="flex flex-col items-center justify-center h-full gap-2">
                                    <i class="fas fa-book-open text-ceara-green text-3xl"></i>
                                    <span class="text-center text-gray-700 font-semibold text-sm">
                                        Cadastrar <br> Livro
                                    </span>
                                </div>
                            </a>
                            
                            <a href="consultar_livro.php?from=gerenciar_acervo" class="carousel-item flex-shrink-0 w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                                <div class="flex flex-col items-center justify-center h-full gap-2">
                                    <i class="fas fa-search text-ceara-green text-3xl"></i>
                                    <span class="text-center text-gray-700 font-semibold text-sm">
                                        Consultar <br> Livro
                                    </span>
                                </div>
                            </a>
                            
                            <a href="editar_livro.php?from=gerenciar_acervo" class="carousel-item flex-shrink-0 w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                                <div class="flex flex-col items-center justify-center h-full gap-2">
                                    <i class="fas fa-edit text-ceara-green text-3xl"></i>
                                    <span class="text-center text-gray-700 font-semibold text-sm">
                                        Editar <br> Livro
                                    </span>
                                </div>
                            </a>

                            <a href="excluir_livro.php?from=gerenciar_acervo" class="carousel-item flex-shrink-0 w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                                <div class="flex flex-col items-center justify-center h-full gap-2">
                                    <i class="fas fa-trash-alt text-ceara-green text-3xl"></i>
                                    <span class="text-center text-gray-700 font-semibold text-sm">
                                        Excluir <br> Livro
                                    </span>
                                </div>
                            </a>

                            <a href="relatorios/decisao.php?from=gerenciar_acervo" class="carousel-item flex-shrink-0 w-[140px] h-[140px] border border-gray-200 bg-gray-50 rounded-lg transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg flex items-center justify-center text-center no-underline">
                                <div class="flex flex-col items-center justify-center h-full gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" 
                                        fill="currentColor" class="w-10 h-10 text-ceara-green">
                                        <rect x="8" y="30" width="12" height="26" rx="3"/>
                                        <rect x="26" y="12" width="12" height="44" rx="3"/>
                                        <rect x="44" y="22" width="12" height="34" rx="3"/>
                                    </svg>
                                    <span class="text-center text-gray-700 font-semibold text-sm">
                                        Relatórios <br> do Acervo
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Carousel Controls -->
                    <button id="carouselPrev" class="carousel-btn carousel-btn-prev absolute left-0 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-ceara-green text-white shadow-lg hover:bg-[#006b34] transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <button id="carouselNext" class="carousel-btn carousel-btn-next absolute right-0 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-ceara-green text-white shadow-lg hover:bg-[#006b34] transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <!-- Carousel Indicators -->
                    <div class="flex justify-center gap-2 mt-3">
                        <button class="carousel-dot active w-2 h-2 rounded-full bg-ceara-green transition-all duration-300" data-index="0"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-gray-300 transition-all duration-300" data-index="1"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-gray-300 transition-all duration-300" data-index="2"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-gray-300 transition-all duration-300" data-index="3"></button>
                    </div>
                </div>
            </div>

            <!-- CARD RANK TOP 10 ALUNOS -->
            <div class="painel-principal animate-panel lg:w-full xl:w-[540px] 2xl:w-[690px] h-[220px] lg:h-[240px] relative xl:ml-auto">
                <div class="painel-title">
                    <span>Top 10 Alunos Leitores</span>
                </div>

                <div class="p-2 pt-1 space-y-1 overflow-y-auto" style="max-height: calc(100% - 48px);">
                    <?php if (empty($topReaders)): ?>
                        <div class="text-center text-gray-500 text-xl py-8">Nenhum empréstimo foi realizado.</div>
                    <?php endif; ?>
                    <?php foreach ($topReaders as $index => $reader): ?>
                        <div class="flex items-center justify-between pb-2 pt-1 <?php echo $index !== array_key_last($topReaders) ? 'border-b' : ''; ?>">

                            <div class="flex items-center gap-3 text-sm lg:text-md">
                                <div class="w-7 h-7 rounded-full border bg-[#EEF7F0] flex items-center justify-center text-ceara-green">
                                    <i class="fas fa-user"></i>
                                </div>
                                
                                <span class="text-gray-700 <?php echo $index < 3 ? 'font-bold' : ''; ?>">
                                    <?php echo htmlspecialchars($reader['nome']); ?>
                                </span>
                            </div>

                            <div class="pr-2 space-x-1 text-sm lg:text-md">
                                <span class="text-gray-700">Livros:</span>
                                
                                <span class="text-ceara-green font-bold">
                                    <?php echo $reader['total_emprestimos']; ?>
                                </span>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function animateCounters() {
            document.querySelectorAll('.counter').forEach(counter => {
                const target = +counter.dataset.target;
                if (target === 0) {
                    counter.textContent = '0';
                    return;
                }
                let start = 0;
                const duration = 1000; 
                const stepTime = Math.max(Math.floor(duration / Math.max(target, 1)), 15);

                const updateCount = () => {
                    start += Math.ceil(target / (duration / stepTime));
                    if (start >= target) {
                        counter.textContent = target.toLocaleString('pt-BR');
                        return;
                    }
                    counter.textContent = start.toLocaleString('pt-BR');
                    requestAnimationFrame(updateCount);
                };

                requestAnimationFrame(updateCount);
            });
        }

        function updateYAxisLabels() {
            const yAxis = document.querySelector('.chart-y-axis');
            if (!yAxis) return;
            yAxis.innerHTML = '';
            [40, 30, 20, 10, 0].forEach(value => {
                const label = document.createElement('div');
                label.textContent = value.toString();
                yAxis.appendChild(label);
            });
        }

        function animateChartBars() {
            updateYAxisLabels();
            document.querySelectorAll('.chart-bar').forEach((bar, index) => {
                const heightPercent = Number(bar.dataset.height || 0);
                setTimeout(() => {
                    bar.style.height = heightPercent + '%';
                }, 100 + index * 30);
            });
        }

        function revealPanels() {
            document.querySelectorAll('.animate-panel').forEach((panel, index) => {
                setTimeout(() => {
                    panel.classList.add('show');
                }, 100 + index * 60);
            });
        }

        const monthBarValues = <?php echo json_encode($monthBarValues); ?>;
        const weekBarValues = <?php echo json_encode($weeklyBarValues); ?>;
        const monthLabels = <?php echo json_encode(array_map(function ($m) { return substr($m, 0, 3); }, $months)); ?>;
        const weekLabels = <?php echo json_encode($weekLabels); ?>;

        const categoryDistributionData = <?php echo json_encode($categoryDistribution); ?>;
        const genreDistributionData = <?php echo json_encode($genreDistribution); ?>;

        const donutModeLabels = {
            category: 'Livros por Categoria',
            genre: 'Livros por Gênero'
        };

        // const categoryLabelMap = {
        //     geral: 'Geral',
        //     didatico: 'Didático',
        //     paradidatico: 'Paradidático',
        //     tecnico: 'Técnico'
        // };

        let donutChart = null;
        let activeDonutMode = 'category';
        let donutResizeObserver = null;

        function formatNumber(value) {
            return Number(value).toLocaleString('pt-BR');
        }

        function getDonutDistribution(mode) {
            return mode === 'category' ? categoryDistributionData : genreDistributionData;
        }

        function getDonutTotal(mode) {
            return getDonutDistribution(mode).reduce((sum, item) => sum + (Number(item.total) || 0), 0) || 1;
        }

        // function getCategoryLabel(rawCategory) {
        //     if (!rawCategory) return 'N/A';
        //     const key = String(rawCategory).trim().toLowerCase();
        //     return categoryLabelMap[key] || rawCategory;
        // }

        function renderDonutLegend(mode) {
            const legendElement = document.getElementById('donutLegend');
            if (!legendElement) return;
            legendElement.innerHTML = '';

            const distribution = getDonutDistribution(mode);
            const total = getDonutTotal(mode);

            distribution.forEach((item) => {
                const percentage = total > 0 ? ((Number(item.total) || 0) / total) * 100 : 0;
                const label = item.categoria ?? item.genero ?? 'N/A';

                const li = document.createElement('li');
                li.className = 'flex items-center gap-2';
                li.innerHTML = `
                    <i class="fa-solid fa-circle text-[0.5rem] xl:text-[0.55rem] 2xl:text-[0.75rem]" style="color: ${item.cor || '#008C45'};"></i>
                    <span class="text-gray-700">${label}</span>
                    <span class="ml-auto font-semibold text-gray-900">${formatNumber(item.total)}</span>
                    <span class="text-gray-500">(${Math.round(percentage)}%)</span>
                `;
                legendElement.appendChild(li);
            });
        }

        function scheduleDonutChartRender() {
            const canvas = document.getElementById('genreChart');
            if (!canvas) return;

            window.requestAnimationFrame(() => {
                if (canvas.clientWidth === 0 || canvas.clientHeight === 0) {
                    window.setTimeout(scheduleDonutChartRender, 120);
                    return;
                }
                renderDonutChart(activeDonutMode);
            });
        }

        function initDonutChartResilience() {
            const canvas = document.getElementById('genreChart');
            if (!canvas) return;

            const container = canvas.parentElement;
            if (container && typeof ResizeObserver !== 'undefined') {
                donutResizeObserver?.disconnect();
                donutResizeObserver = new ResizeObserver(() => scheduleDonutChartRender());
                donutResizeObserver.observe(container);
            }

            window.addEventListener('resize', scheduleDonutChartRender);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    scheduleDonutChartRender();
                }
            });
        }

        function renderDonutChart(mode) {
            const ctx = document.getElementById('genreChart');
            const titleElement = document.querySelector('.textLivros');
            if (!ctx || !titleElement) return;

            activeDonutMode = mode;
            const distribution = getDonutDistribution(mode);
            const labels = distribution.map(item => item.categoria ?? item.genero ?? 'N/A');
            const data = distribution.map(item => Number(item.total) || 0);
            const backgroundColors = distribution.map(item => item.cor || '#008C45');

            titleElement.textContent = donutModeLabels[mode] || donutModeLabels.genre;
            renderDonutLegend(mode);

            const centerTextPlugin = {
                id: 'centerText',
                afterDatasetsDraw(chart) {
                    const { width, height, ctx } = chart;
                    ctx.save();

                    const total = chart.data.datasets[0].data.reduce((a, b) => {
                        const aNum = typeof a === 'number' ? a : parseInt(a) || 0;
                        const bNum = typeof b === 'number' ? b : parseInt(b) || 0;
                        return aNum + bNum;
                    }, 0);

                    const x = width / 2;
                    const y = height / 2;

                    ctx.font = 'bold 26px Poppins';
                    ctx.fillStyle = '#008C45';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(total.toLocaleString('pt-BR'), x, y - 12);

                    ctx.font = '14px Poppins';
                    ctx.fillStyle = '#5f6977';
                    ctx.fillText('Total', x, y + 18);

                    ctx.restore();
                }
            };

            if (donutChart) {
                donutChart.destroy();
            }

            donutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = Number(context.parsed) || 0;
                                    const total = context.dataset.data.reduce((a, b) => Number(a) + Number(b), 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
                                    return `${label}: ${formatNumber(value)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                },
                plugins: [centerTextPlugin]
            });
        }

        function renderBarChart(period) {
            const yAxis = document.querySelector('.chart-y-axis');
            const barContainer = document.querySelector('.chart-bars-container');
            const labelContainer = document.querySelector('.chart-labels-container');
            const titleElement = document.querySelector('.textEmprestimos');
            const periodMode = period === 'week' ? 'week' : 'month';
            const values = periodMode === 'week' ? weekBarValues : monthBarValues;
            const labels = periodMode === 'week' ? weekLabels : monthLabels;
            const axisMax = periodMode === 'month' ? 400 : 40;
            const titleText = periodMode === 'week' ? 'Empréstimos por Semana' : 'Empréstimos por Mês';

            titleElement.textContent = titleText;
            yAxis.innerHTML = '';
            barContainer.innerHTML = '';
            labelContainer.innerHTML = '';
            barContainer.style.gridTemplateColumns = `repeat(${values.length}, minmax(0, 1fr))`;
            labelContainer.style.gridTemplateColumns = `repeat(${labels.length}, minmax(0, 1fr))`;

            values.forEach(value => {
                const wrapper = document.createElement('div');
                wrapper.className = 'chart-bar-wrapper';

                const countLabel = document.createElement('span');
                countLabel.className = 'chart-bar-label';
                countLabel.textContent = value;
                wrapper.appendChild(countLabel);

                const bar = document.createElement('div');
                bar.className = 'chart-bar';
                bar.dataset.value = value;
                bar.style.height = '0%';
                wrapper.appendChild(bar);
                barContainer.appendChild(wrapper);

                setTimeout(() => {
                    const heightPercent = axisMax > 0 ? (value / axisMax) * 100 : 0;
                    bar.style.height = `${heightPercent}%`;
                }, 100);
            });

            const step = axisMax / 4;
            [axisMax, step * 3, step * 2, step, 0].forEach(labelValue => {
                const label = document.createElement('div');
                label.textContent = Number.isInteger(labelValue) ? labelValue : labelValue.toFixed(0);
                yAxis.appendChild(label);
            });

            labels.forEach(labelText => {
                const label = document.createElement('div');
                label.className = 'chart-label';
                label.textContent = labelText;
                labelContainer.appendChild(label);  
            });
        }

        function initGenreChart() {
            activeDonutMode = 'category';
            initDonutChartResilience();
            scheduleDonutChartRender();
        }

        // Carousel functionality
        function initCarousel() {
            const carousel = document.getElementById('resourcesCarousel');
            const prevBtn = document.getElementById('carouselPrev');
            const nextBtn = document.getElementById('carouselNext');
            const dots = document.querySelectorAll('.carousel-dot');
            const items = document.querySelectorAll('.carousel-item');
            const itemWidth = 140; // Item width in pixels
            const gap = 12; // Gap between items in pixels
            const itemTotalWidth = itemWidth + gap;
            const maxIndex = Math.max(items.length - 2, 0);
            let currentIndex = 0;

            function updateCarousel() {
                const offset = -(currentIndex * itemTotalWidth);
                carousel.style.transform = 'translateX(' + offset + 'px)';

                // Update dots
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('active');
                        dot.style.backgroundColor = '#008C45';
                    } else {
                        dot.classList.remove('active');
                        dot.style.backgroundColor = '#d1d5db';
                    }
                });

                // Update button states
                if (prevBtn) {
                    prevBtn.disabled = currentIndex === 0;
                }
                if (nextBtn) {
                    nextBtn.disabled = currentIndex === maxIndex;
                }
            }

            function nextSlide() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            }

            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }

            prevBtn?.addEventListener('click', prevSlide);
            nextBtn?.addEventListener('click', nextSlide);

            dots.forEach((dot) => {
                dot.addEventListener('click', (e) => {
                    currentIndex = parseInt(e.target.dataset.index, 10);
                    updateCarousel();
                });
            });

            // Initialize carousel state
            updateCarousel();

            // Optional: Auto-scroll carousel every 5 seconds
            // setInterval(nextSlide, 5000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            revealPanels();
            animateCounters();
            initGenreChart();
            renderBarChart('month');
            initCarousel();

            const periodSelect = document.getElementById('periodSelect');
            if (periodSelect) {
                periodSelect.addEventListener('change', (event) => {
                    const selectedPeriod = event.target.value;
                    document.querySelector('.chart-y-axis').innerHTML = '';
                    renderBarChart(selectedPeriod);
                });
            }

            const donutTypeSelect = document.getElementById('donutTypeSelect');
            if (donutTypeSelect) {
                donutTypeSelect.addEventListener('change', (event) => {
                    renderDonutChart(event.target.value || 'category');
                });
            }
        });

</script>
</body>

</html>