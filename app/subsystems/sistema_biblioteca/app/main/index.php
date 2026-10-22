<?php
$sidebarMenu = [
    ['title' => 'Dashboard', 'icon' => 'fas fa-home', 'id' => 'dashboard', 'items' => []],
    ['title' => 'Classificação', 'icon' => 'fas fa-tags', 'section' => 'classificacao', 'id' => 'classificacao', 'items' => [
        ['title' => 'Cadastrar Categoria', 'icon' => 'fas fa-plus', 'section' => 'cadastrar_categoria', 'description' => 'Sessão para cadastrar novas categorias.'],
        ['title' => 'Cadastrar Gênero', 'icon' => 'fas fa-plus', 'section' => 'cadastrar_genero', 'description' => 'Sessão para cadastrar novos gêneros.'],
        ['title' => 'Cadastrar Subgênero', 'icon' => 'fas fa-plus', 'section' => 'cadastrar_subgenero', 'description' => 'Sessão para cadastrar subgêneros.'],
        ['title' => 'Editar Categoria', 'icon' => 'fas fa-edit', 'section' => 'editar_categoria', 'description' => 'Sessão para editar informações de categoria'],
        ['title' => 'Editar Gênero', 'icon' => 'fas fa-edit', 'section' => 'editar_genero', 'description' => 'Sessão para editar informações de gênero'],
        ['title' => 'Editar Subgênero', 'icon' => 'fas fa-edit', 'section' => 'editar_subgenero', 'description' => 'Sessão para editar informações de subgênero']
    ]],
    ['title' => 'Gerenciar Acervo', 'icon' => 'fas fa-book', 'section' => 'gerenciar_acervo', 'id' => 'acervo', 'items' => [
        ['title' => 'Cadastrar Livro', 'icon' => 'fas fa-plus', 'section' => 'cadastrar_livro', 'description' => 'Sessão para cadastrar livros e gerenciar o acervo.'],
        ['title' => 'Consultar Livros', 'icon' => 'fas fa-search', 'section' => 'consultar_livro', 'description' => 'Sessão para consultar o acervo de livros.'],
        ['title' => 'Editar Livro', 'icon' => 'fas fa-edit', 'section' => 'editar_livro', 'description' => 'Sessão para editar informações de livros existentes.'],
        ['title' => 'Editar Autor', 'icon' => 'fas fa-edit', 'section' => 'editar_autor', 'description' => 'Sessão para editar informações de autores.'],
        ['title' => 'Excluir Livro', 'icon' => 'fas fa-trash', 'section' => 'excluir_livro', 'description' => 'Sessão para excluir livros do acervo.'],
    ]],
    // ['title' => 'Empréstimos', 'icon' => 'fas fa-exchange-alt', 'id' => 'emprestimos', 'items' => [
    //     ['title' => 'Novo Empréstimo', 'icon' => 'fas fa-arrow-right', 'section' => 'emprestimo_livro', 'description' => 'Sessão para realizar novos empréstimos.'],
    //     ['title' => 'Devolver Livro', 'icon' => 'fas fa-arrow-left', 'section' => 'devolucao_livro', 'description' => 'Sessão para registrar devoluções de livros.'],
    // ]],
    ['title' => 'QR Codes', 'icon' => 'fas fa-qrcode', 'section' => 'qrcodes', 'id' => 'qrcodes', 'items' => [
        ['title' => 'QR Code Geral', 'icon' => 'fas fa-qrcode', 'section' => 'qrcode_geral', 'description' => 'Sessão para gerar QR Codes para todos os livros.'],
        ['title' => 'QR Code Específico', 'icon' => 'fas fa-qrcode', 'section' => 'qrcode_especifico', 'description' => 'Sessão para gerar QR Code para um livro específico.'],
        ['title' => 'QR Code Específico do Livro', 'icon' => 'fas fa-qrcode', 'section' => 'qrcode_especifico_livro', 'description' => 'Sessão para gerar QR Code exclusivo de um livro.'],
    ]],
    ['title' => 'Relatórios', 'icon' => 'fas fa-chart-bar', 'section' => 'relatorios', 'id' => 'relatorios', 'items' => [
        ['title' => 'Geral', 'icon' => 'fas fa-book-open', 'section' => 'relatorio_acervo', 'description' => 'Sessão para relatórios de acervo.'],
        ['title' => 'Empréstimos', 'icon' => 'fas fa-exchange-alt', 'section' => 'relatorio_emprestimo', 'description' => 'Sessão para relatórios de empréstimos.'],
        ['title' => 'Por Estante', 'icon' => 'fas fa-file-alt', 'section' => 'relatorio_pra_est', 'description' => 'Sessão para relatórios de prestação.'],
        ['title' => 'Por Turma', 'icon' => 'fas fa-users', 'section' => 'geral_turma', 'description' => 'Sessão para relatórios por turma.'],
        ['title' => 'Por Livro', 'icon' => 'fas fa-book', 'section' => 'relatorio_por_livro', 'description' => 'Sessão para relatórios por livro.']
    ]],
];

$sectionContents = [
    'gerenciar_acervo' => ['title' => 'Gerenciar Acervo', 'description' => 'Use esta sessão para gerenciar o acervo da biblioteca.', 'view' => 'views/gerenciar_acervo.php'],
    'classificacao' => ['title' => 'Classificação', 'description' => 'Use esta sessão para gerenciar a classificação dos livros.', 'view' => 'views/classificacao.php'],
    'cadastrar_livro' => ['title' => 'Cadastrar Livro', 'description' => 'Use esta sessão para cadastrar um novo livro no acervo.', 'view' => 'views/cadastrar_livro.php'],
    'cadastrar_categoria' => ['title' => 'Cadastrar Categoria', 'description' => 'Use esta sessão para cadastrar novas categorias.', 'view' => 'views/cadastrar_categoria.php'],
    'consultar_livro' => ['title' => 'Consultar Livros', 'description' => 'Use esta sessão para consultar os livros cadastrados.', 'view' => 'views/consultar_livro.php'],
    'editar_livro' => ['title' => 'Editar Livro', 'description' => 'Use esta sessão para editar dados dos livros.', 'view' => 'views/editar_livro.php'],
    'excluir_livro' => ['title' => 'Excluir Livro', 'description' => 'Use esta sessão para excluir livros do acervo.', 'view' => 'views/excluir_livro.php'],
    'editar_autor' => ['title' => 'Editar Autor', 'description' => 'Use esta sessão para editar informações de autores.', 'view' => 'views/editar_autor.php'],
    'cadastrar_genero' => ['title' => 'Cadastrar Gênero', 'description' => 'Use esta sessão para cadastrar novos gêneros.', 'view' => 'views/cadastrar_genero.php'],
    'cadastrar_subgenero' => ['title' => 'Cadastrar Subgênero', 'description' => 'Use esta sessão para cadastrar subgêneros.', 'view' => 'views/cadastrar_subgenero.php'],
    'editar_categoria' => ['title' => 'Editar Categoria', 'description' => 'Use esta sessão para editar categorias', 'view' => 'views/editar_categoria.php'],
    'editar_genero' => ['title' => 'Editar Gênero', 'description' => 'Use esta sessão para editar gêneros', 'view' => 'views/editar_genero.php'],
    'editar_subgenero' => ['title' => 'Editar Subgênero', 'description' => 'Use esta sessão para editar subgêneros', 'view' => 'views/editar_subgenero.php'],
    'emprestimo_livro' => ['title' => 'Novo Empréstimo', 'description' => 'Use esta sessão para registrar um novo empréstimo.', 'view' => 'views/emprestimo/emprestimo_livro.php'],
    'devolucao_livro' => ['title' => 'Devolver Livro', 'description' => 'Use esta sessão para registrar a devolução de livros.', 'view' => 'views/emprestimo/devolucao_livro.php'],
    'qrcodes' => ['title' => 'QR Codes', 'description' => 'Use esta sessão para acessar as opções de geração de QR Codes.', 'view' => 'views/QRCode/decisao.php'],
    'qrcode_geral' => ['title' => 'QR Code Geral', 'description' => 'Use esta sessão para gerar QR Codes para todos os livros do acervo.', 'view' => 'views/QRCode/QRCodes.php'],
    'qrcode_especifico' => ['title' => 'QR Code Específico', 'description' => 'Use esta sessão para gerar QR Code para um livro específico.', 'view' => 'views/QRCode/geradorQR_especifico.php'],
    'qrcode_especifico_livro' => ['title' => 'QR Code Específico do Livro', 'description' => 'Use esta sessão para gerar QR Code exclusivo de um livro.', 'view' => 'views/QRCode/QRCode_especifico_livro.php'],
    'relatorios' => ['title' => 'Relatórios', 'description' => 'Use esta sessão para acessar as opções de relatórios.', 'view' => 'views/relatorios/decisao.php'],
    'relatorio_emprestimo' => ['title' => 'Relatório de Empréstimos', 'description' => 'Use esta sessão para ver os relatórios de empréstimos.', 'view' => 'views/relatorios/relatorio_emprestimo.php'],
    'relatorio_acervo' => ['title' => 'Relatório de Acervo', 'description' => 'Use esta sessão para ver os relatórios gerados do acervo.', 'view' => 'views/relatorios/relatorio_acervo.php'],
    'geral_turma' => ['title' => 'Relatório por Turma', 'description' => 'Use esta sessão para gerar relatórios por turma.', 'view' => 'views/relatorios/geral_turma.php'],
    'relatorio_por_livro' => ['title' => 'Relatório por Livro', 'description' => 'Use esta sessão para ver os relatórios gerados por livro.', 'view' => 'views/relatorios/relatorio_livros.php'],
    'relatorio_pra_est' => ['title' => 'Relatório de Prestação', 'description' => 'Use esta sessão para gerar relatórios de prestação.', 'view' => 'views/relatorios/relatorio_pra_est.php']
];

require_once __DIR__ . '/models/select_model.php';
$selectModel = new select_model();
$totalBooks = $selectModel->count_catalogo_total();
$totalLoans = $selectModel->count_emprestimos_total();
$totalStudents = $selectModel->count_alunos_total();
?> 
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/img/icon.png" type="image/x-icon">
    <title>Sistema Biblioteca</title>
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

        * {
            font-family: 'Poppins', sans-serif;        
        }
        .gradient-text {
            background: linear-gradient(45deg, #008C45, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        html, body {
            overflow-x: hidden;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .footer-link {
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: #FFA500;
            transform: translateY(-2px);
        }

        /* Menu lateral estilos */
        .sidebar {
            transition: transform 0.3s ease, width 0.3s ease;
        }


        .sidebar.-translate-x-full {
            transform: translateX(-100%);
        }

        .sidebar-text {
            transition: opacity 0.3s ease;
        }


        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.active {
            max-height: 500px;
        }

        .menu-item {
            transition: all 0.2s ease;
        }

        .menu-item {
            border-radius: 0.8rem;
            transition: transform 0.18s ease, background-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(0.5rem);
        }

        .menu-item.active,
        .menu-item.active:hover {
            background-color: rgba(255, 165, 0, 0.18);
            color: #FFA500;
        }

        .menu-item.active i,
        .menu-item.active .sidebar-text {
            color: #FFA500;
        }

        /* make icons follow text color on hover */
        .menu-item i {
            color: inherit;
            transition: color 0.18s ease, transform 0.18s ease;
        }

        .submenu .menu-item {
            padding-left: 2.5rem;
        }

        .menu-section {
            display: none;
        }

        .menu-section.active {
            display: block;
        }

    </style>
</head>

<body class="bg-gradient-to-br from-ceara-white to-gray-100 text-gray-800 select-none flex flex-col relative items-center m-0 p-0 grid xl:grid-cols-[16vw_84vw]">

    <!-- MENU LATERAL -->
    <aside id="sidebar" class="sidebar w-full lg:w-[16vw] bg-[#075c2c] text-white shadow-lg fixed left-0 top-0 max-h-[100px]  lg:min-h-screen overflow-hidden lg:overflow-y-auto transition-[max-height] duration-500 ease-in-out z-40">
        <div class="p-4 lg:p-3 flex items-center justify-between lg:justify-center pt-6 lg:pt-8">
            <div class="flex items-center lg:justify-start">
                <img class="object-cover size-12 lg:size-12" src="./assets/img/logo.png" alt="Simbolo da Biblioteca">
                <div class="flex items-center ml-2">
                    <h1 class="text-xl font-bold text-primary-400 mr-5 tracking-wider">Biblioteca <span class="text-secondary">STGM</span></h1>
                </div>
            </div>
            <div class="h-0.5 bg-primary-500/20 rounded-full mt-1"></div>
            <!-- MENU BTN MOBILE -->
            <div class="block lg:hidden translate-x-[-12px]">
                <button id="sidebar-toggle-mobile">
                    <i class="fa-solid fa-bars text-xl text-gray-400"></i>
                </button>
            </div>
        </div>


        <nav class="p-4 space-y-2 font-semibold">
            <?php foreach ($sidebarMenu as $group) : ?>
                <div class="menu-group">
                    <?php if (empty($group['items'])) : ?>
                        <button type="button" class="menu-toggle w-full flex items-center justify-between p-3 rounded-lg menu-item <?= $group['id'] === 'dashboard' ? 'active' : '' ?>" data-section="<?= $group['id'] ?>">
                            <div class="flex items-center space-x-3">
                                <i class="<?= $group['icon'] ?> text-lg"></i>
                                <span class="sidebar-text"><?= $group['title'] ?></span>
                            </div>
                        </button>
                    <?php else : ?>
                        <button type="button" class="menu-toggle w-full flex items-center justify-between p-3 rounded-lg menu-item" data-submenu="<?= $group['id'] ?>"<?= !empty($group['section']) ? ' data-section="' . $group['section'] . '"' : '' ?>>
                            <div class="flex items-center space-x-3">
                                <i class="<?= $group['icon'] ?> text-lg"></i>
                                <span class="sidebar-text"><?= $group['title'] ?></span>
                            </div>
                            <i class="fas fa-chevron-down sidebar-text transition-transform"></i>
                        </button>
                        <div id="<?= $group['id'] ?>-submenu" class="submenu">
                            <?php foreach ($group['items'] as $subgroup) : ?>
                                <?php if (!empty($subgroup['items'])) : ?>
                                    <div class="menu-subgroup">
                                        <button type="button" class="menu-toggle w-full flex items-center justify-between p-3 rounded-lg menu-item" data-submenu="<?= $subgroup['id'] ?>">
                                            <div class="flex items-center space-x-3">
                                                <i class="<?= $subgroup['icon'] ?> text-sm"></i>
                                                <span class="sidebar-text"><?= $subgroup['title'] ?></span>
                                            </div>
                                            <i class="fas fa-chevron-down sidebar-text transition-transform text-xs"></i>
                                        </button>
                                        <div id="<?= $subgroup['id'] ?>-submenu" class="submenu">
                                            <?php foreach ($subgroup['items'] as $item) : ?>
                                                <button type="button" class="w-full flex items-center space-x-3 p-3 rounded-lg menu-item text-left" data-section="<?= $item['section'] ?>">
                                                    <i class="<?= $item['icon'] ?> text-xs"></i>
                                                    <span class="sidebar-text"><?= $item['title'] ?></span>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <button type="button" class="w-full flex items-center space-x-3 p-3 rounded-lg menu-item text-left" data-section="<?= $subgroup['section'] ?>">
                                        <i class="<?= $subgroup['icon'] ?> text-xs"></i>
                                        <span class="sidebar-text"><?= $subgroup['title'] ?></span>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="flex-1 flex flex-col min-h-screen">

        <!-- CONTEÚDO -->
        <main id="main-content" class="flex-1 flex flex-col lg:ml-[16vw] lg:w-[84vw] pt-16 md:pt-24 lg:pt-0 w-full min-h-screen">

            <section id="section-dashboard" class="menu-section active flex-1 flex flex-col gap-6">
                <section class="pt-24 md:pt-10 pb-16 md:pb-8 px-4">
                    <div class="max-w-7xl mx-auto gap-8">
                        <div class="space-y-6 flex flex-col items-center text-center">
                            <h2 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl 2xl:text-8xl font-bold gradient-text"
                                style="font-family: 'Anton', serif;">
                                GERENCIE SUA BIBLIOTECA
                            </h2>
                            <p class="text-base sm:text-lg text-gray-600 font-light max-w-xl mx-auto lg:mx-0"
                                style="font-family: 'Poppins', sans-serif;">
                                Organize livros, gerencie empréstimos e simplifique a administração da sua biblioteca.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="px-4">
                    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
                        <div class="cards-section hover:transition-all hover:shadow-lg bg-white border border-gray-100 rounded-3xl shadow-sm p-6 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.24em] text-gray-500">Livros cadastrados</p>
                                    <p class="text-4xl font-bold text-ceara-green mt-4" data-counter-target="<?= $totalBooks ?>">0</p>
                                </div>
                                <i class="fas fa-book-open text-4xl text-ceara-green/80"></i>
                            </div>
                        </div>
                        <div class="cards-section hover:transition-all hover:shadow-lg bg-white border border-gray-100 rounded-3xl shadow-sm p-6 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.24em] text-gray-500">Empréstimos realizados</p>
                                    <p class="text-4xl font-bold text-ceara-orange mt-4" data-counter-target="<?= $totalLoans ?>">0</p>
                                </div>
                                <i class="fas fa-exchange-alt text-4xl text-ceara-orange/80"></i>
                            </div>
                        </div>
                        <div class="cards-section hover:transition-all hover:shadow-lg bg-white border border-gray-100 rounded-3xl shadow-sm p-6 flex flex-col justify-between">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.24em] text-gray-500">Alunos cadastrados</p>
                                    <p class="text-4xl font-bold text-ceara-green mt-4" data-counter-target="<?= $totalStudents ?>">0</p>
                                </div>
                                <i class="fas fa-users text-4xl text-ceara-green/80"></i>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="features" class="py-12 md:py-12 lg:py-16 bg-gradient-to-b from-white to-gray-50/50">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div>
                            <div class="text-center mb-10">
                                <h3 class="text-2xl md:text-3xl xl:text-4xl font-bold text-ceara-green/90">Empréstimo / Devolução</h3>
                            </div>

                            <div class="w-full max-w-5xl mx-auto px-4">
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-ceara-green">
                                        <i class="fas fa-qrcode text-xl"></i>
                                    </span>
                                    <input
                                        id="qrcodeHome"
                                        type="text"
                                        placeholder="Escaneie o QRcode aqui para verificar empréstimo/devolução"
                                        class="w-full pl-12 pr-4 py-4 bg-white border-2 border-ceara-green/50 focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/40 rounded-2xl outline-none placeholder:text-gray-400 text-gray-700 shadow-card text-lg"
                                        autocomplete="off" />
                                </div>
                                <!-- <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <p class="text-sm text-gray-500">
                                        Exemplo de teste: <span class="font-medium">id_livro=123_1_1_E1_p2</span> ou cole um URL completo do QR.
                                    </p>
                                    <button type="button" id="btn-qrcode-test" class="inline-flex items-center justify-center rounded-full border border-ceara-green px-4 py-2 text-sm font-semibold text-ceara-green hover:bg-ceara-green/10 transition">
                                        Testar QR de exemplo
                                    </button>
                                </div> -->
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                            <!-- Card 1 -->
                            <!-- <div class="group h-full">
                                <button type="button" data-section="gerenciar_acervo" class="w-full text-left bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-green/20">
                                    <div class="h-1.5 bg-gradient-to-r from-ceara-green to-ceara-green/80"></div>
                                    <div class="block p-6 flex-grow">
                                        <div class="flex flex-col items-center text-center h-full">
                                            <div class="w-16 h-16 flex items-center justify-center bg-ceara-green/5 rounded-2xl mb-5 group-hover:bg-ceara-green/10 transition-all duration-300">
                                                <i class="fas fa-book text-ceara-green text-2xl"></i>
                                            </div>
                                            <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Gerenciamento de Acervo</h4>
                                            <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Cadastre e organize seus livros de forma eficiente e intuitiva.</p>
                                        </div>
                                    </div>
                                </button>
                            </div> -->

                            <!-- Card 2 -->
                            <!-- <div class="group h-full">
                                <button type="button" data-section="relatorio_acervo" class="w-full text-left bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-green/20">
                                    <div class="h-1.5 bg-gradient-to-r from-ceara-green to-ceara-green/80"></div>
                                    <div class="block p-6 flex-grow">
                                        <div class="flex flex-col items-center text-center h-full">
                                            <div class="w-16 h-16 flex items-center justify-center bg-ceara-green/5 rounded-2xl mb-5 group-hover:bg-ceara-green/10 transition-all duration-300">
                                                <i class="fas fa-chart-line text-ceara-green text-2xl"></i>
                                            </div>
                                            <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Relatórios</h4>
                                            <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Gere relatórios detalhados para análise e tomada de decisões.</p>
                                        </div>
                                    </div>
                                </button>
                            </div> -->

                            <!-- Card 3 -->
                            <!-- <div class="group h-full">
                                <button type="button" data-section="qrcode_geral" class="w-full text-left bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-orange/20">
                                    <div class="h-1.5 bg-gradient-to-r from-ceara-orange to-ceara-orange/70"></div>
                                    <div class="block p-6 flex-grow">
                                        <div class="flex flex-col items-center text-center h-full">
                                            <div class="w-16 h-16 flex items-center justify-center bg-ceara-orange/5 rounded-2xl mb-5 group-hover:bg-ceara-orange/10 transition-all duration-300">
                                                <i class="fas fa-qrcode text-ceara-orange text-2xl"></i>
                                            </div>
                                            <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Gerar QR Code</h4>
                                            <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Crie QR Codes para facilitar o acesso e catalogação dos livros.</p>
                                        </div>
                                    </div>
                                </button>
                            </div> -->
                        </div>
                    </div>
                </section>
            </section>

            <?php foreach ($sectionContents as $sectionId => $section): ?>
                <section id="section-<?= $sectionId ?>" class="menu-section lg:px-0 lg:-ml-6">
                    <div class="max-w-screen mx-auto">
                        <!-- <div class="mb-8">
                            <h2 class="text-3xl md:text-4xl font-bold text-ceara-green" style="font-family: 'Anton', serif;"><?= $section['title'] ?></h2>
                            <p class="text-gray-600 text-base md:text-lg mt-3 max-w-3xl"><?= $section['description'] ?></p>
                        </div> -->
                        <?php if (!empty($section['view'])) : ?>
                            <div class="shadow-sm border border-gray-100 p-0 w-full overflow-hidden">
                                <iframe data-src="<?= $section['view'] ?>" class="w-full min-h-screen min-h-[500px]" frameborder="0"></iframe>
                            </div>
                        <?php else : ?>
                            <!-- <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                                <p class="text-gray-700">Esta é uma sessão interna do painel. Substitua este conteúdo pelo formulário ou pela lista referente à funcionalidade selecionada.</p>
                            </div> -->
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>

            <footer id="footer" class="bg-ceara-white py-12 mt-auto w-full">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="flex flex-col items-center md:items-start space-y-4">
                            <h1 class="text-3xl font-bold gradient-text" style="font-family: 'Anton', serif;">
                                Biblioteca
                            </h1>
                            <p class="text-gray-600 text-sm max-w-xs text-center md:text-left leading-relaxed">
                                Simplificando a gestão de bibliotecas com ferramentas eficientes.
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-8 pt-6">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <p class="text-gray-600 text-sm">© 2026 Sistema Biblioteca. Todos os direitos reservados.</p>
                            <div class="flex space-x-4 mt-4 md:mt-0">
                                <a href="#" class="text-gray-600 hover:text-ceara-orange text-sm transition-colors">Termos</a>
                                <span class="text-gray-400">•</span>
                                <a href="#"
                                    class="text-gray-600 hover:text-ceara-orange text-sm transition-colors">Privacidade</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <script>
        // Menu lateral - Alternar abas (submenu)
        document.querySelectorAll('.menu-toggle').forEach(button => {
            button.addEventListener('click', (e) => {
                // If sidebar is hidden on mobile, do not open submenus
                const sidebarEl = document.getElementById('sidebar');
                if (sidebarEl && sidebarEl.classList.contains('-translate-x-full')) {
                    return;
                }

                const submenuId = button.getAttribute('data-submenu');
                const submenu = document.getElementById(submenuId + '-submenu');
                const chevron = button.querySelector('.fa-chevron-down');

                if (submenu) {
                    submenu.classList.toggle('active');
                    if (chevron) {
                        chevron.style.transform = submenu.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                }
            });
        });

        // Configuração do sidebar
        const sidebar = document.getElementById('sidebar');
        const sidebarText = document.querySelectorAll('.sidebar-text');
        const sidebarToggleMobile = document.getElementById('sidebar-toggle-mobile');
        const sidebarToggleMobileHeader = document.getElementById('sidebar-toggle-mobile-header');


        sidebarToggleMobile?.addEventListener('click', () => {
            sidebar.classList.toggle('max-h-screen');
        });

        // Alternar exibição de seções internas via menu ou cards
        const sections = document.querySelectorAll('.menu-section');
        const html = document.documentElement;
        const dashboardSection = document.getElementById('section-dashboard');
        const footer = document.getElementById('footer');

        function alternarSecaoEfetiva(targetId, itemClicado = null) {
            // Se não foi passado o item clicado manualmente, tentamos buscar ele no sidebar para atualizar o visual
            if (!itemClicado) {
                itemClicado = document.querySelector(`#sidebar [data-section="${targetId}"]`);
            }

            document.querySelectorAll('#sidebar .menu-item').forEach(el => el.classList.remove('active'));
            
            if (itemClicado && itemClicado.closest('#sidebar')) {
                itemClicado.classList.add('active');
                
                // Força a abertura do submenu caso a seção recuperada pertença a um
                const subMenu = itemClicado.closest('.submenu');
                if (subMenu) subMenu.classList.add('active');
            }

            sections.forEach(section => section.classList.remove('active'));

            // Regras de exibição específicas para o dashboard
            if (targetId === 'dashboard') {
                dashboardSection?.classList.add('active');
                dashboardSection?.classList.remove('hidden'); // Garante que reapareça
                dashboardSection?.classList.add('lg:pt-24'); // Aplica padding-top do dashboard
                dashboardSection?.classList.remove('lg:pt-0');
                html?.classList.remove('overflow-y-hidden');
                footer?.classList.remove('hidden');
                animateDashboardCounters();
            } else {
                // Para outras seções, aplicamos as regras de ocultar o dashboard e o footer
                const targetSection = document.getElementById(`section-${targetId}`);
                if (targetSection) {
                    // Ativa a seção alvo e carrega o iframe se necessário
                    targetSection.classList.add('active');
                    dashboardSection?.classList.remove('lg:pt-24'); // Remove padding do dashboard
                    dashboardSection?.classList.add('lg:pt-0'); // Aplica padding zero
                    const iframe = targetSection.querySelector('iframe');
                    if (iframe && !iframe.hasAttribute('src')) {
                        iframe.setAttribute('src', iframe.getAttribute('data-src'));
                    }
                    html.classList.add('overflow-y-hidden');
                    if (dashboardSection) dashboardSection.classList.add('hidden');
                    if (footer) footer.classList.add('hidden');
                }
            }
        }
        
        // Configura os cliques nos itens do menu lateral para alternar as seções
        document.querySelectorAll('[data-section]').forEach(item => {
            item.addEventListener('click', () => {
                const targetId = item.getAttribute('data-section');
                
                // Salva a seção atual no localStorage sempre que houver um clique 
                localStorage.setItem('secaoSessaoAtual', targetId);

                // Executa as regras de mudança de tela
                alternarSecaoEfetiva(targetId, item);
            });
        });

        const animateDashboardCounters = () => {
            document.querySelectorAll('[data-counter-target]').forEach(el => {
                const target = parseInt(el.dataset.counterTarget, 10) || 0;
                const duration = 1200;
                let startTime = null;

                const animate = timestamp => {
                    if (!startTime) startTime = timestamp;
                    const progress = Math.min((timestamp - startTime) / duration, 1);
                    const current = Math.floor(progress * target);
                    el.textContent = current.toLocaleString('pt-BR');
                    if (progress < 1) {
                        window.requestAnimationFrame(animate);
                    } else {
                        el.textContent = target.toLocaleString('pt-BR');
                    }
                };

                window.requestAnimationFrame(animate);
            });
        }

        animateDashboardCounters();

        // Funcionalidade do campo de QR Code
        const qrcodeInput = document.getElementById('qrcodeHome');
        // const qrcodeTestButton = document.getElementById('btn-qrcode-test');

        if (qrcodeInput) {
            function resolveQrcodeUrl(qrcode) {
                const trimmed = qrcode.trim();
                if (!trimmed) return null;

                function extractIdFromUrl(url) {
                    try {
                        const u = new URL(url, window.location.origin);
                        const params = new URLSearchParams(u.search);
                        let id = params.get('id_livro') || params.get('qr_livro') || params.get('id');
                        if (!id) {
                            const hashParams = new URLSearchParams(u.hash.replace('#','?'));
                            id = hashParams.get('id_livro') || hashParams.get('qr_livro') || null;
                        }
                        if (!id) {
                            const m = url.match(/(?:id_livro|qr_livro)=([^&?#]+)/);
                            if (m) id = decodeURIComponent(m[1]);
                        }
                        return id;
                    } catch (e) {
                        return null;
                    }
                }

                const isFullUrl = trimmed.startsWith('http://') || trimmed.startsWith('https://');
                if (isFullUrl) {
                    const extracted = extractIdFromUrl(trimmed);
                    if (extracted) {
                        if (qrcodeInput) qrcodeInput.value = extracted;
                        return `views/emprestimo/decisao.php?id_livro=${encodeURIComponent(extracted)}`;
                    }
                    return trimmed;
                }

                const isDecisaoPath = trimmed.startsWith('views/emprestimo/decisao.php');
                if (isDecisaoPath) return trimmed;

                const hasIdLivro = trimmed.includes('id_livro=');
                if (hasIdLivro) {
                    if (trimmed.startsWith('?')) {
                        return `views/emprestimo/decisao.php${trimmed}`;
                    }
                    return trimmed.includes('=') ? `views/emprestimo/decisao.php?${trimmed}` : `views/emprestimo/decisao.php?id_livro=${encodeURIComponent(trimmed)}`;
                }

                return `views/emprestimo/decisao.php?id_livro=${encodeURIComponent(trimmed)}`;
            }

            
            const debounceRedirect = (() => {
                let timer = null;
                return () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        if (qrcodeInput.value.trim().length > 8) {
                            redirecionarComQrcode();
                        }
                    }, 250);
                };
            })();

            qrcodeInput.addEventListener('input', function() {
                if (this.value.trim().length > 8) {
                    debounceRedirect();
                }
            });

            qrcodeInput.addEventListener('paste', function() {
                setTimeout(redirecionarComQrcode, 100);
            });

            qrcodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    redirecionarComQrcode();
                }
            });

            qrcodeInput.addEventListener('blur', function() {
                redirecionarComQrcode();
            });

            // if (qrcodeTestButton) {
            //     qrcodeTestButton.addEventListener('click', () => {
            //         const example = 'id_livro=123_1_1_E1_p2';
            //         qrcodeInput.value = example;
            //         redirecionarComQrcode();
            //     });
            // }
            
            qrcodeInput.focus();
        }

        function redirecionarComQrcode() {
            const qrcode = qrcodeInput.value.trim();
            const url = resolveQrcodeUrl(qrcode);
            if (url) {
                // Criamos um iframe invisível temporário apenas para rodar o decisao.php sem piscar a tela
                let mestreIframe = document.getElementById('mestre-iframe-qr');
                if (!mestreIframe) {
                    mestreIframe = document.createElement('iframe');
                    mestreIframe.id = 'mestre-iframe-qr';
                    mestreIframe.style.display = 'none';
                    document.body.appendChild(mestreIframe);
                }
                // Faz o arquivo de decisão rodar em background. Ele executará a função que vamos criar abaixo
                mestreIframe.src = url;
                
                // Limpa o campo para o próximo escaneamento
                qrcodeInput.value = '';
            }
        }

        window.ativarSessaoPeloQrCode = function(sectionId, targetUrl) {
            const sections = document.querySelectorAll('.menu-section');
            const dashboardSection = document.getElementById('section-dashboard');
            const footer = document.getElementById('footer');
            const html = document.documentElement;

            // Remove classes ativas do menu lateral para limpar o visual anterior
            document.querySelectorAll('#sidebar .menu-item').forEach(el => el.classList.remove('active'));
            
            // Tenta marcar visualmente o botão correto no menu lateral
            const menuButton = document.querySelector(`[data-section="${sectionId}"]`);
            if (menuButton) {
                menuButton.classList.add('active');
                // Se estiver dentro de um submenu fechado, força a abertura dele
                const subMenu = menuButton.closest('.submenu');
                if (subMenu) subMenu.classList.add('active');
            }

            // Se ativar pelo QR Code, também atualiza a sessão salva
            localStorage.setItem('secaoSessaoAtual', sectionId);

            sections.forEach(section => section.classList.remove('active'));
            
            const targetSection = document.getElementById(`section-${sectionId}`);
            if (targetSection) {
                targetSection.classList.add('active');
                const iframe = targetSection.querySelector('iframe');
                if (iframe) {
                    iframe.setAttribute('src', targetUrl);
                }
                html.classList.add('overflow-y-hidden');
                if (dashboardSection) dashboardSection.classList.add('hidden');
                if (footer) footer.classList.add('hidden');
            }
        };
        
        // Fechar menu ao redimensionar para desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
            }
        });

        // Menu mobile - Alternar abas (submenu)
        document.querySelectorAll('.menu-toggle').forEach(button => {
            button.addEventListener('click', (e) => {
                if (window.innerWidth < 768) {
                    e.preventDefault();
                }
            });
        });
        
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const qrLivro = urlParams.get('qr_livro');

            // Se a página foi aberta via QR Code externo (Câmera do Celular/Tablet)
            if (qrLivro) {
                // Monta a URL do arquivo de decisão com o parâmetro recebido
                const urlDecisao = `views/emprestimo/decisao.php?id_livro=${encodeURIComponent(qrLivro)}`;
                
                // Cria o iframe invisível para processar a decisão sem quebrar o layout
                let mestreIframe = document.getElementById('mestre-iframe-qr');
                if (!mestreIframe) {
                    mestreIframe = document.createElement('iframe');
                    mestreIframe.id = 'mestre-iframe-qr';
                    mestreIframe.style.display = 'none';
                    document.body.appendChild(mestreIframe);
                }
                
                // Executa a decisão em background
                mestreIframe.src = urlDecisao;

                // Limpa o parâmetro da barra de endereços do navegador por estética e para evitar reprocessar no F5
                window.history.replaceState({}, document.title, window.location.pathname);
            } else {
                // SE NÃO VEIO DO QR CODE EXTERNO, BUSCA A SESSÃO SALVA OU USA DASHBOARD
                const ultimaSecaoSalva = localStorage.getItem('secaoSessaoAtual') || 'dashboard';
                // Aplica as regras de padding-top e exibição sempre, independente de qual seção está salva
                alternarSecaoEfetiva(ultimaSecaoSalva);
            }
        });
    </script>
</body>

</html>