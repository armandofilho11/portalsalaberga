<?php
session_start();
require_once '../model/Opiniao.class.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: ../view/login.php?error=" . urlencode("Por favor, faça login para acessar o portal"));
    exit();
}

// Fetch satisfaction data with date and meal filter
$selectedDate = $_GET['data'] ?? date('Y-m-d');
$selectedMeal = $_GET['refeicao'] ?? 'all'; // Default to 'all' meals
$opiniao = new Opiniao();
$percentages = $opiniao->getSatisfacaoCounts($selectedDate, $selectedMeal);

// Fetch users for each satisfaction level
$usersBySatisfacao = [];
$satisfacaoLevels = ['horrivel', 'ruim', 'regular', 'bom', 'otimo'];
foreach ($satisfacaoLevels as $level) {
    $usersBySatisfacao[$level] = $opiniao->getUsersBySatisfacao($level, $selectedDate, $selectedMeal);
}

// Convert user data to JSON for JavaScript
$usersBySatisfacaoJson = json_encode($usersBySatisfacao);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Satisfação</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1f5632',
                        'secondary': '#f46815',
                        'neutral': '#f5f5f5',
                        'accent': '#333333',
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary-color: #1f5632;
            --secondary-color: #f46815;
            --text-color: #333333;
            --bg-color: #f5f5f5;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --dark-green: #0f2a1d;
            --medium-dark-green: #1a3c2e;
            font-size: 16px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color) 0%, #c3cfe2 100%);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
        }

        body.dark {
            --bg-color: #1a1a1a;
            --accent: #d1d5db;
            background: var(--bg-color);
            color: var(--accent);
        }

        .header {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
            color: white;
            width: 100%;
            box-shadow: 0 2px 4px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .mobile-menu {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
            width: 100%;
            color: white;
            margin: 0;
            padding: 0;
        }

        .mobile-menu-content {
            padding-top: 0.5rem;
            padding-bottom: 1rem;
        }

        .main-content {
            margin-top: 1rem;
            padding: 2rem 1rem;
            width: 100%;
            max-width: 600px;
            flex: 1;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px var(--shadow-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        body.dark .card {
            background-color: #2d2d2d;
            color: #d1d5db;
        }

        .filter-card {
            margin-bottom: 2rem;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            align-items: center;
        }

        .filter-form select,
        .filter-form input[type="date"] {
            padding: 0.5rem;
            border: 1px solid #A5D6A7;
            border-radius: 5px;
            font-size: 1rem;
        }

        .chart-container {
            position: relative;
            margin: auto;
            height: 400px;
            width: 400px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 6px var(--shadow-color);
            position: relative;
            border: 1px solid #A5D6A7;
        }

        body.dark .modal-content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        body.dark .modal-title {
            color: #d1d5db;
        }

        .modal-list {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 1rem;
        }

        .modal-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .modal-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--bg-color);
            font-size: 0.95rem;
            color: var(--text-color);
        }

        body.dark .modal-list li {
            color: #d1d5db;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        .menu-items {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: nowrap;
            flex-shrink: 0;
        }
        .menu-items a {
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: color 0.3s ease;
            white-space: nowrap;
        }
        .menu-items a:hover {
            color: var(--secondary-color);
        }
        .action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            background: var(--primary-color);
            color: white;
        }

        .action-btn:hover {
            background: var(--secondary-color);
        }

        .close-modal {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            font-size: 1.5rem;
            color: var(--text-color);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        body.dark .close-modal {
            color: #d1d5db;
        }

        .close-modal:hover {
            color: #EF5350;
        }

        .accessibility-controls {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .footer {
            background: #000000;
            color: white;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .footer-content {
            width: 100%;
            padding: 2rem 1rem;
        }

        .footer .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .footer .grid {
                grid-template-columns: repeat(12, 1fr);
            }
            .footer-content > div:nth-child(1) { grid-column: span 4; }
            .footer-content > div:nth-child(2) { grid-column: 5 / span 4; }
            .footer-content > div:nth-child(3) { grid-column: 10 / span 3; }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-top: 70px;
                padding: 1rem;
            }

            .card {
                padding: 1.5rem;
            }

            .chart-container {
                height: 300px;
                width: 300px;
            }

            .accessibility-controls {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .footer-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="font-sans bg-neutral" x-data="{ mobileMenuOpen: false }">
    <header class="header">
        <div class="container mx-auto px-4">
            <nav class="flex items-center justify-between flex-wrap py-4">
                <div class="accessibility-controls flex items-center space-x-2">
                    <span class="text-sm"><b>Acessibilidade</b></span>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Diminuir tamanho do texto" onclick="changeTextSize(-2)"><i class="fa-solid fa-a"></i><b>-</b></button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Tamanho padrão do texto" onclick="changeTextSize(0)"><i class="fa-solid fa-a"></i></button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Aumentar tamanho do texto" onclick="changeTextSize(2)"><i class="fa-solid fa-a"></i><b>+</b></button>
                    <button class="hover:text-secondary transition duration-300 px-1" aria-label="Alternar modo escuro" @click="toggleDarkMode()">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="hidden lg:flex space-x-4 menu-items">
                        <a href="gerenciarCardapio.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-utensils mr-1"></i> Gerenciamento de Cardápios
                        </a>
                        <a href="usuarios.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-users mr-1"></i> Gerenciamento de Usuários
                        </a>
                        <a href="sugestoes.php">
                            <i class="fa-solid fa-comment mr-1"></i> Sugestões
                        </a>
                        <a href="sistemaAdministrador.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-house mr-1"></i> Início
                        </a>
                    </div>
                    <div class="block lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="flex items-center px-3 py-2 border rounded text-secondary border-secondary hover:text-white hover:border-white transition duration-300">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>
            <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="lg:hidden mobile-menu">
                <div class="container mx-auto px-4 mobile-menu-content">
                    <a href="sistemaAdministrador.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-house mr-1"></i> Início
                    </a>
                    <a href="gerenciarCardapio.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-utensils mr-1"></i> Gerenciamento de Cardápios
                    </a>
                    <a href="usuarios.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-users mr-1"></i> Gerenciamento de Usuários
                    </a>
                    <a href="sugestoes.php">
                            <i class="fa-solid fa-comment mr-1"></i> Sugestões
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="card filter-card">
            <h2>Filtrar Relatórios</h2>
            <form method="GET" class="filter-form">
                <input type="date" name="data" value="<?php echo $selectedDate; ?>" required>
                <select name="refeicao" required>
                    <option value="all" <?php echo $selectedMeal == 'all' ? 'selected' : ''; ?>>Todas as Refeições</option>
                    <option value="lanche-manha" <?php echo $selectedMeal == 'lanche-manha' ? 'selected' : ''; ?>>Lanche da Manhã</option>
                    <option value="almoco" <?php echo $selectedMeal == 'almoco' ? 'selected' : ''; ?>>Almoço</option>
                    <option value="lanche-tarde" <?php echo $selectedMeal == 'lanche-tarde' ? 'selected' : ''; ?>>Lanche da Tarde</option>
                </select>
                <button type="submit" class="action-btn">Filtrar</button>
            </form>
        </div>
        <div class="card">
            <h2>Satisfação do Cardápio - <?php echo $selectedMeal == 'all' ? 'Todas as Refeições' : ucfirst(str_replace('-', ' ', $selectedMeal)); ?></h2>
            <div class="chart-container">
                <canvas id="satisfacaoChart"></canvas>
            </div>
        </div>
    </div>

    <div id="usersModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">×</span>
            <h2 class="modal-title" id="modalTitle"></h2>
            <div class="modal-list" id="usersList">
                <ul id="usersListUl"></ul>
            </div>
            <div class="modal-buttons">
                <button class="action-btn" onclick="closeModal()">Fechar</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold">EEEP STGM</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            EEEP Salaberga Torquato Gomes de Matos - Educação profissional de qualidade.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold">Endereço</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Avenida Marta Maria Carvalho Nojoza, Outra Banda<br>
                            Maranguape - CE<br>
                            CEP: 61942-355
                        </p>
                    </div>
                </div>
                <div class="md:col-span-4 md:col-start-5 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Contatos</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.instagram.com/eeepsalabergamtg/" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-brands fa-instagram mr-2"></i>Instagram</a></li>
                        <li><a href="tel:+558533414000" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-phone mr-2"></i>(85) 3341-4000</a></li>
                        <li><a href="mailto:eeepsalab@gmail.com?subject=Contato%20EEEP%20STGM" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&to=eeepsalab@gmail.com', '_blank'); return true;" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-envelope mr-2"></i>Email</a></li>
                    </ul>
                </div>
                <div class="md:col-span-4 md:col-start-10 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Desenvolvedores</h3>
                    <p class="text-sm text-gray-400">Christian Santos</p>
                    <p class="text-sm text-gray-400">José Arimatéia</p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-sm text-gray-400">© 2025. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        const usersBySatisfacao = <?php echo $usersBySatisfacaoJson; ?>;
        let chart;

        function openModal(satisfacao, users) {
            const modal = document.getElementById('usersModal');
            const modalTitle = document.getElementById('modalTitle');
            const usersListUl = document.getElementById('usersListUl');

            modalTitle.textContent = `Usuários que escolheram "${satisfacao}"`;
            usersListUl.innerHTML = '';

            if (users.length === 0) {
                const li = document.createElement('li');
                li.textContent = 'Nenhum usuário selecionou esta opção.';
                usersListUl.appendChild(li);
            } else {
                users.forEach(user => {
                    const li = document.createElement('li');
                    li.textContent = user;
                    usersListUl.appendChild(li);
                });
            }

            modal.style.display = 'flex';
        }

        function closeModal() {
            const modal = document.getElementById('usersModal');
            modal.style.display = 'none';
        }

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('usersModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Initialize chart with click functionality
        const ctx = document.getElementById('satisfacaoChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Horrível', 'Ruim', 'Regular', 'Bom', 'Ótimo'],
                datasets: [{
                    label: 'Satisfação',
                    data: [
                        <?php echo $percentages['horrivel']; ?>,
                        <?php echo $percentages['ruim']; ?>,
                        <?php echo $percentages['regular']; ?>,
                        <?php echo $percentages['bom']; ?>,
                        <?php echo $percentages['otimo']; ?>
                    ],
                    backgroundColor: [
                        '#AB47BC',
                        '#EF5350',
                        '#FF9800',
                        '#42A5F5',
                        '#66BB6A'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14,
                                family: "'Open Sans', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw + '%';
                                return label;
                            }
                        }
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const satisfacaoMap = ['horrivel', 'ruim', 'regular', 'bom', 'otimo'];
                        const satisfacao = satisfacaoMap[index];
                        const label = chart.data.labels[index];
                        const users = usersBySatisfacao[satisfacao] || [];
                        openModal(label, users);
                    }
                }
            }
        });

        // Initialize VLibras
        document.addEventListener('DOMContentLoaded', () => {
            try {
                new window.VLibras.Widget('https://vlibras.gov.br/app');
            } catch (e) {
                console.error('Erro ao inicializar VLibras:', e);
            }

            // Apply saved settings
            const savedFontSize = localStorage.getItem('fontSize');
            if (savedFontSize) {
                document.documentElement.style.fontSize = savedFontSize;
            }
            const savedDarkMode = localStorage.getItem('darkMode');
            if (savedDarkMode === 'true') {
                document.body.classList.add('dark');
            }
        });

        // VLibras button action
        document.getElementById('vlibrasButton').addEventListener('click', function() {
            const vwButton = document.querySelector('div[vw-access-button]');
            if (vwButton) {
                vwButton.click();
            } else {
                window.open('https://www.gov.br/governodigital/pt-br/acessibilidade-e-usuario/vlibras', '_blank');
            }
        });

        // Text size adjustment
        function changeTextSize(step) {
            const root = document.documentElement;
            let currentSize = parseFloat(getComputedStyle(root).fontSize);
            if (step === 0) {
                root.style.fontSize = '16px';
            } else {
                currentSize = Math.max(12, Math.min(24, currentSize + step));
                root.style.fontSize = currentSize + 'px';
            }
            localStorage.setItem('fontSize', root.style.fontSize); // Save font size
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark')); // Save dark mode state
        }

        // Apply saved settings on load
        window.addEventListener('load', () => {
            const savedFontSize = localStorage.getItem('fontSize');
            if (savedFontSize) {
                document.documentElement.style.fontSize = savedFontSize;
            }
            const savedDarkMode = localStorage.getItem('darkMode');
            if (savedDarkMode === 'true') {
                document.body.classList.add('dark');
            }
        });
    </script>
</body>
</html>