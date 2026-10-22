<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio - EEEP STGM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="shortcut icon" href="img/Design-logo.svg" type="image/x-icon">
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
            --bg-color: #f5f5f5;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --dark-green: #0f2a1d;
            --medium-dark-green: #1a3c2e;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark {
            --bg-color: #1a1a1a;
            --accent: #d1d5db;
            background: var(--bg-color);
            color: var(--accent);
        }
        .navbar-gradient {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
        }
        .content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px var(--shadow-color);
            margin: 1rem;
            flex: 1;
            overflow-x: auto;
        }
        body.dark .content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1rem;
            margin-bottom: 1rem;
            display: inline-block;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: var(--secondary-color);
        }
        .meses {
            background: var(--primary-color);
            padding: 0.5rem;
            border-radius: 4px;
            color: white;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
        }
        .meses span {
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.3s ease, transform 0.2s ease;
            font-size: 0.9rem;
        }
        .meses span:hover, .meses span.selected {
            background: #2a6d43;
            transform: scale(1.05);
        }
        .refeicoes {
            background: var(--secondary-color);
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            color: white;
            text-align: center;
        }
        .week-title {
            color: var(--accent);
            margin: 1.5rem 0 1rem;
            text-align: center;
            font-size: 1.2rem;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: center;
        }
        body.dark th, body.dark td {
            border-color: #444;
        }
        th {
            background: var(--primary-color);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        body.dark tr:nth-child(even) {
            background-color: #333;
        }
        td[style*="background: var(--secondary-color)"] {
            color: white;
        }
        .to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem;
            border-radius: 50%;
            box-shadow: 0 2px 4px var(--shadow-color);
            cursor: pointer;
            display: none;
        }
        .header {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .year-dropdown {
            display: inline-block;
            position: relative;
        }
        .year-dropdown-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .year-dropdown-btn:hover {
            background: #2a6d43;
            transform: scale(1.02);
        }
        .year-dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 100px;
            z-index: 1000;
            top: 2.2rem;
            right: 0;
            margin-top: 0;
        }
        body.dark .year-dropdown-menu {
            background: #2d2d2d;
            border-color: #444;
        }
        .year-dropdown-menu.active {
            display: block;
        }
        .year-dropdown-item {
            padding: 0.4rem 0.8rem;
            color: var(--accent);
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.3s ease;
        }
        body.dark .year-dropdown-item {
            color: #d1d5db;
        }
        .year-dropdown-item:hover {
            background: #f0f0f0;
        }
        body.dark .year-dropdown-item:hover {
            background: #444;
        }
        @media (max-width: 768px) {
            .content {
                margin: 0.5rem;
                padding: 1rem;
            }
            .back-link {
                font-size: 0.9rem;
            }
            .meses {
                gap: 0.3rem;
            }
            .meses span {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
            .week-title {
                font-size: 1rem;
            }
            th, td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }
            .to-top {
                padding: 0.5rem;
                bottom: 15px;
                right: 15px;
            }
            .accessibility-controls {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .year-dropdown {
                width: 100%;
                margin-top: 0;
            }
            .year-dropdown-btn {
                width: 100%;
                padding: 0.6rem 1rem;
                font-size: 1rem;
            }
            .year-dropdown-menu {
                width: 100%;
                top: 2.5rem;
                right: auto;
                left: 0;
                min-width: unset;
            }
            .year-dropdown-item {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
        }
        .hover-scale {
            transition: transform 0.3s ease-in-out;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans bg-neutral" x-data="{ mobileMenuOpen: false, yearDropdownOpen: false, selectedYear: '<?php echo $_GET['year'] ?? date('Y'); ?>' }">
    <header class="navbar-gradient text-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4">
            <nav class="flex items-center justify-between flex-wrap py-4">
                <div class="accessibility-controls flex items-center space-x-2">
                    <span class="text-sm"><b>Acessibilidade</b></span>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Diminuir tamanho do texto" onclick="changeTextSize(-1)"><i class="fa-solid fa-a"></i><b>-</b></button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Tamanho padrão do texto" onclick="changeTextSize(0)"><i class="fa-solid fa-a"></i></button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Aumentar tamanho do texto" onclick="changeTextSize(1)"><i class="fa-solid fa-a"></i><b>+</b></button>
                    <button class="hover:text-secondary transition duration-300 px-1" aria-label="Alternar modo escuro" @click="toggleDarkMode()">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                    <button id="vlibrasButton" class="hover:text-secondary transition duration-300 px-1" aria-label="VLibras">
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="hidden lg:flex space-x-4">
                        <a href="sistemaAluno.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-house mr-1"></i> Início
                        </a>
                        <a href="avaliacao.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-utensils mr-1"></i> Avaliar refeição
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
            <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="lg:hidden">
                <div class="py-2 space-y-2">
                    <a href="sistemaAluno.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-house mr-1"></i> Início
                    </a>
                    <a href="avaliacao.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-utensils mr-1"></i> Avaliar refeição
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="flex flex-col md:flex-row">
            <div class="content flex-1">
                <div class="header">
                    <h1>Cardápio</h1>
                    <div class="year-dropdown">
                        <button @click="yearDropdownOpen = !yearDropdownOpen" class="year-dropdown-btn hover-scale" x-text="'Ano: ' + selectedYear">
                            Ano: <?php echo $_GET['year'] ?? date('Y'); ?>
                        </button>
                        <div class="year-dropdown-menu" :class="{ 'active': yearDropdownOpen }">
                            <?php
                            for ($year = 2020; $year <= 2030; $year++) {
                                $selected = ($year == ($_GET['year'] ?? date('Y'))) ? 'selected' : '';
                                echo "<div class='year-dropdown-item' @click='selectedYear = $year; yearDropdownOpen = false; reloadWithYear($year)'>$year</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="meses">
                    <span data-month="0">Jan</span>
                    <span data-month="1">Fev</span>
                    <span data-month="2">Mar</span>
                    <span data-month="3">Abr</span>
                    <span data-month="4">Mai</span>
                    <span data-month="5">Jun</span>
                    <span data-month="6">Jul</span>
                    <span data-month="7">Ago</span>
                    <span data-month="8">Set</span>
                    <span data-month="9">Out</span>
                    <span data-month="10">Nov</span>
                    <span data-month="11">Dez</span>
                </div>
                <div class="refeicoes">
                    <h2>Refeições e Horários</h2>
                </div>
                <div class="table-container">
                    <table>
                        <tr>
                            <th></th>
                            <th>Segunda-feira (Dia)</th>
                            <th>Terça-feira (Dia)</th>
                            <th>Quarta-feira (Dia)</th>
                            <th>Quinta-feira (Dia)</th>
                            <th>Sexta-feira (Dia)</th>
                        </tr>
                        <tr>
                            <td style="background: var(--secondary-color); color: white;">Lanche da manhã<br>09:10</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="background: var(--secondary-color); color: white;">Almoço<br>12:00</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="background: var(--secondary-color); color: white;">Lanche da tarde<br>15:00</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <?php
                require_once("../model/cardapio.class.php");
                $cardapioView = new CardapioView();
                $refeicoes = $cardapioView->exibirCardapio($_GET['year'] ?? null);

                function getDayOfWeek($date) {
                    $dayOfWeek = date('w', strtotime($date));
                    return ($dayOfWeek == 0) ? 6 : ($dayOfWeek - 1);
                }

                $semanas = [];
                foreach (['lanche-manha', 'almoco', 'lanche-tarde'] as $tipo) {
                    if (!empty($refeicoes[$tipo])) {
                        foreach ($refeicoes[$tipo] as $data => $entries) {
                            $weekStart = date('Y-m-d', strtotime($data . ' -' . getDayOfWeek($data) . ' days'));
                            if (!isset($semanas[$weekStart])) {
                                $semanas[$weekStart] = [
                                    'lanche-manha' => array_fill(0, 5, []),
                                    'almoco' => array_fill(0, 5, []),
                                    'lanche-tarde' => array_fill(0, 5, [])
                                ];
                            }
                            $dayIndex = getDayOfWeek($data);
                            if ($dayIndex >= 0 && $dayIndex < 5) {
                                $semanas[$weekStart][$tipo][$dayIndex] = $entries;
                            }
                        }
                    }
                }

                foreach ($semanas as $weekStart => $weekData) {
                    $weekYear = date('Y', strtotime($weekStart));
                    $weekMonth = date('m', strtotime($weekStart)) - 1;
                    $weekEnd = date('Y-m-d', strtotime($weekStart . ' +4 days'));
                    echo "<div class='week-section' data-year='$weekYear' data-month='$weekMonth' style='display:none;'>";
                    echo "<h3 class='week-title'>Semana de " . date('d/m/Y', strtotime($weekStart)) . " a " . date('d/m/Y', strtotime($weekEnd)) . "</h3>";
                    echo "<div class='table-container'>";
                    echo "<table>";
                    echo "<tr><th></th><th>Segunda-feira</th><th>Terça-feira</th><th>Quarta-feira</th><th>Quinta-feira</th><th>Sexta-feira</th></tr>";

                    echo "<tr>";
                    echo "<td style='background: var(--secondary-color); color: white;'>Lanche da manhã<br>09:10</td>";
                    for ($day = 0; $day < 5; $day++) {
                        echo "<td>";
                        foreach ($weekData['lanche-manha'][$day] as $cardapio) {
                            echo $cardapio['nome'] . "<br>" . $cardapio['descricao'] . "<br>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "<tr>";
                    echo "<td style='background: var(--secondary-color); color: white;'>Almoço<br>12:00</td>";
                    for ($day = 0; $day < 5; $day++) {
                        echo "<td>";
                        foreach ($weekData['almoco'][$day] as $cardapio) {
                            echo $cardapio['nome'] . "<br>" . $cardapio['descricao'] . "<br>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "<tr>";
                    echo "<td style='background: var(--secondary-color); color: white;'>Lanche da tarde<br>15:00</td>";
                    for ($day = 0; $day < 5; $day++) {
                        echo "<td>";
                        foreach ($weekData['lanche-tarde'][$day] as $cardapio) {
                            echo $cardapio['nome'] . "<br>" . $cardapio['descricao'] . "<br>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "</table>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <div class="to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
            <i class="fas fa-arrow-up"></i>
        </div>
    </main>
        <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Contêiner para as seções EEEP STGM empilhadas -->
                <div class="md:col-span-4 space-y-6">
                    <!-- Seção EEEP STGM original -->
                    <div>
                        <h3 class="text-sm font-semibold">EEEP STGM</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            EEEP Salaberga Torquato Gomes de Matos - Educação profissional de qualidade.
                        </p>
                    </div>
                    <!-- Seção Endereço -->
                    <div>
                        <h3 class="text-sm font-semibold">Endereço</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Avenida Marta Maria Carvalho Nojoza, Outra Banda<br>
                            Maranguape - CE<br>
                            CEP: 61942-355
                        </p>
                    </div>
                </div>
                <!-- Seção Contatos -->
                <div class="md:col-span-4 md:col-start-5 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Contatos</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.instagram.com/eeepsalabergamtg/" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-brands fa-instagram mr-2"></i>Instagram</a></li>
                        <li><a href="tel:+558533414000" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-phone mr-2"></i>(85) 3341-4000</a></li>
                        <li><a href="mailto:eeepsalab@gmail.com?subject=Contato%20EEEP%20STGM" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&to=eeepsalab@gmail.com', '_blank'); return true;" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-envelope mr-2"></i>Email</a></li>
                    </ul>
                </div>
                <!-- Seção Desenvolvedores -->
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
        new window.VLibras.Widget('https://vlibras.gov.br/app');
        document.getElementById('vlibrasButton').addEventListener('click', function() {
            window.open('https://www.gov.br/governodigital/pt-br/acessibilidade-e-usuario/vlibras', '_blank');
        });

        function changeTextSize(step) {
            const root = document.documentElement;
            const currentSize = parseFloat(getComputedStyle(root).fontSize);
            if (step === 0) {
                root.style.fontSize = '16px';
            } else {
                root.style.fontSize = `${currentSize + step}px`;
            }
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        }

        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }

        function reloadWithYear(year) {
            window.location.href = `?year=${year}`;
        }

        function filterByMonthYear(month = null, year = null) {
            month = month !== null ? month : new Date().getMonth();
            year = year !== null ? year : parseInt(document.querySelector('.year-dropdown-btn').textContent.replace('Ano: ', '')) || new Date().getFullYear();
            let hasVisibleSection = false;
            document.querySelectorAll('.week-section').forEach(section => {
                const sectionYear = parseInt(section.getAttribute('data-year'));
                const sectionMonth = parseInt(section.getAttribute('data-month'));
                if (sectionYear === year && sectionMonth === month) {
                    section.style.display = 'block';
                    hasVisibleSection = true;
                } else {
                    section.style.display = 'none';
                }
            });
            document.querySelectorAll('.meses span').forEach(span => {
                span.classList.remove('selected');
                if (parseInt(span.getAttribute('data-month')) === month) {
                    span.classList.add('selected');
                }
            });
        }

        document.querySelectorAll('.meses span').forEach(span => {
            span.addEventListener('click', () => {
                const month = parseInt(span.getAttribute('data-month'));
                const year = parseInt(document.querySelector('.year-dropdown-btn').textContent.replace('Ano: ', '')) || new Date().getFullYear();
                filterByMonthYear(month, year);
            });
        });

        window.addEventListener('load', () => {
            const currentMonth = new Date().getMonth();
            const currentYear = parseInt(document.querySelector('.year-dropdown-btn').textContent.replace('Ano: ', '')) || new Date().getFullYear();
            filterByMonthYear(currentMonth, currentYear);
        });

        window.addEventListener('scroll', () => {
            const toTop = document.querySelector('.to-top');
            if (window.scrollY > 300) {
                toTop.style.display = 'block';
            } else {
                toTop.style.display = 'none';
            }
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.year-dropdown')) {
                document.querySelector('[x-data]').__x.$data.yearDropdownOpen = false;
            }
        });
    </script>
</body>
</html>