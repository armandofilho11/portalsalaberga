<?php
session_start();
require_once '../model/Opiniao.class.php';

// Check if user is logged in and is an administrator
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
    header("Location: ../view/login.php?error=Por favor, faça login como administrador para acessar o portal");
    exit();
}

// Get user's first name and profile photo
$userData = $_SESSION['usuario'];
$firstName = explode(" ", $userData['nome'])[0]; // Extract first name
$profilePhoto = isset($userData['profile_photo']) && file_exists("../assets/img/profiles/{$userData['profile_photo']}")
    ? "../assets/img/profiles/{$userData['profile_photo']}"
    : "https://via.placeholder.com/120?text=Administrador";

// Debug: Verify Opiniao class is loaded
if (!class_exists('Opiniao')) {
    error_log("Classe Opiniao não encontrada em " . __FILE__);
    die("Erro: Classe Opiniao não encontrada.");
}

// Fetch suggestions using the Opiniao class
$opiniao = new Opiniao();

// Debug: Verify getSugestoes method exists
if (!method_exists($opiniao, 'getSugestoes')) {
    error_log("Método getSugestoes não encontrado na classe Opiniao em " . __FILE__);
    die("Erro: Método getSugestoes não encontrado na classe Opiniao.");
}

$result = $opiniao->getSugestoes();
$sugestoes = $result['data'];
$error_message = !$result['success'] ? $result['message'] : null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEEP STGM - Sugestões dos Alunos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/Design-logo.svg" type="image/x-icon">
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
            --bg-color: #f5f5f5;
            --shadow-color: rgba(0,0,0,0.2);
            --dark-green: #0f2b1e;
            --medium-dark-green: #1a3c2e;
            font-size: 16px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s, color 0.3s;
            font-size: 1rem;
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
            padding: 0.5rem 0;
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
            max-width: 1200px;
            flex: 1;
            margin-left: auto;
            margin-right: auto;
        }
        body.dark .main-content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 2rem;
        }
        .welcome-message h2 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 600;
        }
        body.dark .welcome-message h2 {
            color: #d1d5db;
        }
        .welcome-message p {
            color: #666;
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        body.dark .welcome-message p {
            color: #b0b0b0;
        }
        .message {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
        }
        .message.success {
            background: #E8F5E9;
            color: #66BB6A;
        }
        body.dark .message.success {
            background: #2a3e2b;
            color: #A5D6A7;
        }
        .message.error {
            background: #FFEBEE;
            color: #EF5350;
        }
        body.dark .message.error {
            background: #4a2a2a;
            color: #FF8A80;
        }
        .table-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px var(--shadow-color);
            overflow-x: auto;
        }
        body.dark .table-container {
            background-color: #2d2d2d;
        }
        .suggestions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px var(--shadow-color);
        }
        body.dark .suggestions-table {
            background-color: #2d2d2d;
        }
        .suggestions-table th,
        .suggestions-table td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #ddd;
        }
        body.dark .suggestions-table th,
        body.dark .suggestions-table td {
            border-color: #555;
        }
        .suggestions-table th {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
            font-weight: 600;
        }
        .suggestions-table td {
            color: var(--accent);
        }
        body.dark .suggestions-table td {
            color: #d1d5db;
        }
        .suggestions-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        body.dark .suggestions-table tr:nth-child(even) {
            background-color: #3a3a3a;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1002;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px var(--shadow-color);
            position: relative;
            border: 1px solid #A5D6A7;
        }
        body.dark .modal-content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .modal-content h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-align: center;
        }
        body.dark .modal-content h2 {
            color: #d1d5db;
        }
        .profile-pic-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }
        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .camera-icon:hover {
            background-color: var(--primary-color);
        }
        .profile-info p {
            font-size: 1rem;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        body.dark .profile-info p {
            color: #d1d5db;
        }
        .profile-info p span {
            font-weight: 600;
            color: var(--primary-color);
        }
        body.dark .profile-info p span {
            color: #d1d5db;
        }
        .close-modal {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            font-size: 1.2rem;
            color: var(--accent);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        body.dark .close-modal {
            color: #d1d5db;
        }
        .close-modal:hover {
            color: #EF5350;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
            padding: 0.5rem 0;
        }
        .accessibility-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
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
        @media (min-width: 1024px) {
            .menu-items {
                display: flex !important;
            }
            .mobile-menu-button {
                display: none;
            }
        }
        @media (max-width: 1023px) {
            .menu-items {
                display: none;
            }
            .mobile-menu-button {
                display: flex;
                align-items: center;
                padding: 0.5rem;
                border: 1px solid var(--secondary-color);
                border-radius: 4px;
                color: var(--secondary-color);
                transition: color 0.3s ease, border-color 0.3s ease;
            }
            .mobile-menu-button:hover {
                color: white;
                border-color: white;
            }
            .mobile-menu {
                display: none;
            }
            .mobile-menu.block {
                display: block;
            }
        }
        @media (max-width: 768px) {
            .main-content {
                margin-top: 70px;
                padding: 1rem;
            }
            .navbar {
                padding: 0.3rem 0;
            }
            .accessibility-controls {
                gap: 0.4rem;
            }
            .menu-items {
                gap: 1rem;
            }
            .menu-items a {
                font-size: 0.85rem;
            }
            .suggestions-table th,
            .suggestions-table td {
                font-size: 0.8rem;
                padding: 0.5rem;
            }
            .modal-content {
                width: 90%;
                padding: 1rem;
            }
            .modal-content h2 {
                font-size: 1.1rem;
            }
            .profile-pic {
                width: 100px;
                height: 100px;
            }
            .camera-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }
        @media (max-width: 480px) {
            .navbar {
                padding: 0.2rem 0;
            }
            .accessibility-controls {
                gap: 0.3rem;
                flex-wrap: wrap;
            }
            .accessibility-controls span,
            .accessibility-controls button {
                font-size: 0.8rem;
            }
            .table-container {
                overflow-x: hidden;
            }
            .suggestions-table {
                display: block;
                box-shadow: none;
            }
            .suggestions-table thead {
                display: none;
            }
            .suggestions-table tbody, .suggestions-table tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 8px;
                box-shadow: 0 2px 4px var(--shadow-color);
            }
            body.dark .suggestions-table tr {
                background-color: #3a3a3a;
                border-color: #555;
            }
            .suggestions-table tr:nth-child(even) {
                background-color: white;
            }
            body.dark .suggestions-table tr:nth-child(even) {
                background-color: #3a3a3a;
            }
            .suggestions-table td {
                display: block;
                text-align: left;
                padding: 0.5rem;
                border: none;
                border-bottom: 1px solid #ddd;
                position: relative;
                padding-left: 40%;
                font-size: 0.75rem;
            }
            body.dark .suggestions-table td {
                border-bottom-color: #555;
            }
            .suggestions-table td:last-child {
                border-bottom: none;
            }
            .suggestions-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0.5rem;
                width: 35%;
                font-weight: 600;
                color: var(--primary-color);
                text-align: left;
            }
            body.dark .suggestions-table td::before {
                color: #d1d5db;
            }
            .suggestions-table td[data-label="Sugestão"] {
                word-break: break-word;
            }
            .profile-pic {
                width: 90px;
                height: 90px;
            }
            .camera-icon {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="font-sans bg-neutral" x-data="{ mobileMenuOpen: false }">
    <header class="header">
        <div class="container mx-auto px-4">
            <nav class="navbar">
                <div class="accessibility-controls">
                    <span class="text-sm font-semibold">Acessibilidade</span>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Diminuir tamanho do texto" onclick="changeTextSize(-2)">
                        <i class="fa-solid fa-a"></i><b>-</b>
                    </button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Tamanho padrão do texto" onclick="changeTextSize(0)">
                        <i class="fa-solid fa-a"></i>
                    </button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Aumentar tamanho do texto" onclick="changeTextSize(2)">
                        <i class="fa-solid fa-a"></i><b>+</b>
                    </button>
                    <button class="hover:text-secondary transition duration-300 px-1" aria-label="Alternar modo escuro" @click="toggleDarkMode()">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="menu-items">
                        <a href="gerenciarCardapio.php">
                            <i class="fa-solid fa-utensils mr-1"></i> Gerenciamento de Cardápios
                        </a>                   
                        <a href="usuarios.php">
                            <i class="fa-solid fa-users mr-1"></i> Gerenciamento de Usuários
                        </a>
                        <a href="relatorios.php">
                            <i class="fa-solid fa-edit mr-1"></i> Relatórios de Satisfação
                        </a>
                        <a href="sistemaAdministrador.php">
                            <i class="fa-solid fa-house mr-1"></i> Início
                        </a>
                    </div>
                    <div class="block lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-menu-button">
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
                    <a href="relatorios.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-edit mr-1"></i> Relatórios de Satisfação
                    </a>
                    
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="welcome-message">
            <h2>Sugestões dos Alunos</h2>
            <p>Visualize e gerencie as sugestões enviadas pelos alunos.</p>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (empty($sugestoes)): ?>
            <div class="message error">Nenhuma sugestão encontrada.</div>
        <?php else: ?>
            <div class="table-container">
                <table class="suggestions-table">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Sugestão</th>
                            <th>Data de Envio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sugestoes as $sugestao): ?>
                            <tr>
                                <td data-label="Usuário"><?php echo htmlspecialchars($sugestao['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td data-label="Sugestão"><?php echo htmlspecialchars($sugestao['texto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td data-label="Data de Envio"><?php echo date('d/m/Y H:i', strtotime($sugestao['data_envio'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">×</span>
            <h2>Perfil do Administrador</h2>
            <div class="profile-pic-container">
                <img src="<?php echo $profilePhoto; ?>" alt="Profile Picture" class="profile-pic" id="profilePic">
                <i class="fas fa-camera camera-icon" id="cameraIcon"></i>
            </div>
            <div class="profile-info">
                <p><span>Nome:</span> <?php echo htmlspecialchars($userData['nome'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><span>E-mail:</span> <?php echo htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><span>Tipo de Usuário:</span> <?php echo htmlspecialchars(ucfirst($userData['tipo_usuario']), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </div>

    <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-6">
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
        // Initialize VLibras
        document.addEventListener('DOMContentLoaded', () => {
            try {
                new window.VLibras.Widget('https://vlibras.gov.br/app');
            } catch (e) {
                console.error('Erro ao inicializar VLibras:', e);
            }

            // Apply saved settings
            applySavedSettings();
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
            let currentSize = parseFloat(getComputedStyle(root).fontSize) || 16; // Default to 16px if invalid
            if (step === 0) {
                root.style.fontSize = '16px';
            } else {
                currentSize = Math.max(12, Math.min(24, currentSize + step));
                root.style.fontSize = `${currentSize}px`;
            }
            localStorage.setItem('fontSize', root.style.fontSize);
            console.log('Font size saved:', root.style.fontSize); // Debug log
        }

        // Dark mode toggle
        function toggleDarkMode() {
            const body = document.body;
            const isDark = body.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
            console.log('Dark mode saved:', isDark); // Debug log
        }

        // Apply saved settings
        function applySavedSettings() {
            const savedFontSize = localStorage.getItem('fontSize');
            if (savedFontSize) {
                document.documentElement.style.fontSize = savedFontSize;
                console.log('Font size loaded:', savedFontSize); // Debug log
            } else {
                document.documentElement.style.fontSize = '16px'; // Default
                console.log('No font size found, using default: 16px');
            }

            const savedDarkMode = localStorage.getItem('darkMode');
            if (savedDarkMode === 'true') {
                document.body.classList.add('dark');
                console.log('Dark mode loaded: true'); // Debug log
            } else {
                document.body.classList.remove('dark');
                console.log('Dark mode loaded: false or not found');
            }
        }

        // Profile modal functionality
        const profileModal = document.getElementById('profileModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cameraIcon = document.getElementById('cameraIcon');
        const profilePic = document.getElementById('profilePic');

        // Create hidden file input for photo selection
        const hiddenPhotoInput = document.createElement('input');
        hiddenPhotoInput.type = 'file';
        hiddenPhotoInput.accept = 'image/*';
        hiddenPhotoInput.style.display = 'none';
        document.body.appendChild(hiddenPhotoInput);

        closeModalBtn.addEventListener('click', () => {
            profileModal.style.display = 'none';
        });

        document.addEventListener('click', (e) => {
            if (e.target === profileModal) {
                profileModal.style.display = 'none';
            }
        });

        cameraIcon.addEventListener('click', () => {
            hiddenPhotoInput.click();
        });

        hiddenPhotoInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    profilePic.src = event.target.result;
                };
                reader.readAsDataURL(file);

                const formData = new FormData();
                formData.append('profilePhoto', file);

                const response = await fetch('../control/updateProfile.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    location.reload();
                } else {
                    alert(result.error || 'Erro ao atualizar a foto');
                    profilePic.src = '<?php echo $profilePhoto; ?>';
                }
            }
        });

        // Open profile modal on click of profile picture or camera icon
        profilePic.addEventListener('click', () => {
            profileModal.style.display = 'flex';
        });
        cameraIcon.addEventListener('click', () => {
            profileModal.style.display = 'flex';
        });
    </script>
</body>
</html>