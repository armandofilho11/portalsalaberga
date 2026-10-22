<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gestão de Estágio da EEEP Salaberga - Gerencie estágios, alunos e empresas de forma eficiente">
    <title>Sistema de Gestão de Estágio</title>
    <link rel="icon" type="image/png" href="../config/img/logo_Salaberga-removebg-preview.png" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        'ceara-moss': '#2d4739',
                        primary: '#008C45',
                        secondary: '#FFA500',
                    }
                }
            }
        }
    </script>
    <style>
        /* Accessibility and UX Improvements */
        :root {
            --focus-ring: 3px solid #FFA500;
            --focus-offset: 2px;
            --transition-speed: 0.3s;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }

        @media (prefers-contrast: high) {
            :root {
                --text-color: #000;
                --border-color: #000;
            }
            .card {
                border: 2px solid #000;
            }
        }

        *:focus {
            outline: var(--focus-ring);
            outline-offset: var(--focus-offset);
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #f3f4f6;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header-moss {
            background: #2d4739;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 100%;
        }

        .header-moss * {
            color: #fff !important;
        }

        .school-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .school-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }

        .hover-scale {
            transition: transform var(--transition-speed) ease-in-out;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-container {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            margin: 2rem auto;
            max-width: 1200px;
            padding: 2rem;
            flex: 1;
            width: 100%;
            box-sizing: border-box;
        }

        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all var(--transition-speed) ease;
            border: 1px solid rgba(0,0,0,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.1);
        }

        .card-icon {
            background: rgba(0, 140, 69, 0.1);
            padding: 1rem;
            border-radius: 0.75rem;
            color: #008C45;
        }

        .btn-primary {
            background: linear-gradient(to right, #008C45, #FFA500);
            color: white;
            transition: all var(--transition-speed) ease;
            font-weight: 500;
            width: 100%;
            text-align: center;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #FFA500, #008C45);
            transform: translateY(-1px);
        }

        .btn-secondary {
            border: 2px solid #008C45;
            color: #008C45;
            transition: all var(--transition-speed) ease;
            font-weight: 500;
            width: 100%;
            text-align: center;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: linear-gradient(to right, #008C45, #FFA500);
            color: white;
            border-color: transparent;
        }

        /* Accessibility Controls */
        .accessibility-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .accessibility-btn {
            padding: 0.5rem;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            transition: all var(--transition-speed) ease;
            cursor: pointer;
            border: none;
            color: white;
        }

        .accessibility-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Responsive Design Improvements */
        @media (max-width: 1200px) {
            .main-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 0.5rem;
                padding: 1rem;
            }

            .header-moss {
                padding: 0.5rem 0;
            }

            .school-name {
                font-size: 1rem;
            }

            .accessibility-controls {
                margin-top: 1rem;
            }

            .card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                padding: 0.75rem;
            }

            .btn-primary, .btn-secondary {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .card-icon {
                padding: 0.75rem;
            }
        }

        /* Skip to main content link */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #008C45;
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s;
        }

        .skip-link:focus {
            top: 0;
        }

        /* Grid Layout */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            width: 100%;
        }

        /* Footer */
        footer {
            margin-top: auto;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Skip to main content link -->
    <a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>

    <!-- Cabeçalho verde musgo -->
    <header class="header-moss" role="banner">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                    <div class="bg-white/10 p-2 rounded-xl">
                        <img src="../config/img/logo_Salaberga-removebg-preview.png" alt="Logo EEEP Salaberga" class="school-logo">
                    </div>
                    <div>
                        <span class="school-name">EEEP Salaberga</span>
                        <h1 class="text-xl sm:text-2xl font-bold">Sistema de Gestão de Estágio</h1>
                    </div>
                </div>
                <div class="accessibility-controls">
                    <button class="accessibility-btn" aria-label="Diminuir tamanho do texto" onclick="changeFontSize(-1)">
                        <i class="fa-solid fa-a"></i><b>-</b>
                    </button>
                    <button class="accessibility-btn" aria-label="Tamanho padrão do texto" onclick="resetFontSize()">
                        <i class="fa-solid fa-a"></i>
                    </button>
                    <button class="accessibility-btn" aria-label="Aumentar tamanho do texto" onclick="changeFontSize(1)">
                        <i class="fa-solid fa-a"></i><b>+</b>
                    </button>
                    <button class="accessibility-btn" aria-label="Alto contraste" onclick="toggleHighContrast()">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                    <button class="accessibility-btn" aria-label="Ativar narração de tela" onclick="toggleScreenReader()">
                        <i class="fa-solid fa-ear-listen"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main id="main-content" class="container mx-auto px-4 py-8 fade-in" role="main">
        <div class="main-container">
            <div class="grid-container">
                <!-- Seção de Relatórios -->
                <div class="card p-6" role="region" aria-labelledby="relatorios-title">
                    <div class="flex items-center mb-6">
                        <div class="card-icon mr-4">
                            <i class="fas fa-chart-bar text-xl" aria-hidden="true"></i>
                        </div>
                        <h2 id="relatorios-title" class="text-xl font-bold text-gray-800">Relatórios</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="../views/relatorios/Gerar_relatorios.php" class="btn-primary">
                            <i class="fas fa-file-alt" aria-hidden="true"></i>
                            Gerar Relatório
                        </a>
                        <a href="../views/processoseletivo.php" class="btn-secondary">
                            <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                            Processo Seletivo
                        </a>
                    </div>
                </div>

                <!-- Seção de Alunos -->
                <div class="card p-6" role="region" aria-labelledby="alunos-title">
                    <div class="flex items-center mb-6">
                        <div class="card-icon mr-4">
                            <i class="fas fa-user-graduate text-xl" aria-hidden="true"></i>
                        </div>
                        <h2 id="alunos-title" class="text-xl font-bold text-gray-800">Alunos</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="../views/cadastroaluno.php" class="btn-primary">
                            <i class="fas fa-user-plus" aria-hidden="true"></i>
                            Cadastrar Aluno
                        </a>
                        <a href="../views/perfildoaluno.php" class="btn-secondary">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                            Ver Perfil do Aluno
                        </a>
                    </div>
                </div>

                <!-- Seção de Empresa -->
                <div class="card p-6" role="region" aria-labelledby="empresa-title">
                    <div class="flex items-center mb-6">
                        <div class="card-icon mr-4">
                            <i class="fas fa-building text-xl" aria-hidden="true"></i>
                        </div>
                        <h2 id="empresa-title" class="text-xl font-bold text-gray-800">Empresa</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="../views/cadastrodaempresa.php" class="btn-primary">
                            <i class="fas fa-plus-circle" aria-hidden="true"></i>
                            Cadastrar Empresa
                        </a>
                        <a href="../views/dadosempresa.php" class="btn-secondary">
                            <i class="fas fa-building" aria-hidden="true"></i>
                            Ver Empresas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-ceara-moss text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm sm:text-base mb-4 sm:mb-0">© 2025 Sistema de Gestão de Estágio. Todos os direitos reservados.</p>
                <div class="flex space-x-6">
                    <a href="https://www.instagram.com/eeepsalabergampe/" class="text-gray-400 hover:text-ceara-orange transition-colors" aria-label="Instagram da EEEP Salaberga">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Accessibility Functions
        function changeFontSize(delta) {
            const body = document.body;
            const currentSize = window.getComputedStyle(body).fontSize;
            const newSize = parseFloat(currentSize) + delta;
            body.style.fontSize = `${newSize}px`;
        }

        function resetFontSize() {
            document.body.style.fontSize = '';
        }

        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
        }

        function toggleScreenReader() {
            // Implement screen reader functionality
            alert('Funcionalidade de leitor de tela ativada');
        }

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Responsive adjustments
        function adjustLayout() {
            const container = document.querySelector('.main-container');
            const cards = document.querySelectorAll('.card');
            
            if (window.innerWidth < 768) {
                cards.forEach(card => {
                    card.style.height = 'auto';
                });
            }
        }

        window.addEventListener('resize', adjustLayout);
        window.addEventListener('load', adjustLayout);
    </script>
</body>
</html>