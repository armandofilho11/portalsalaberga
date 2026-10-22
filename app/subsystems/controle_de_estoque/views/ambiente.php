
<?php
require_once(__DIR__ . '/../models/sessions.php');
$session = new sessions();
$session->autenticar_session();
$session->tempo_session();

require_once(__DIR__ . '/../models/model.select.php');
$select = new select();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Ambientes - STGM Estoque</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005A24',
                        secondary: '#FFA500',
                        accent: '#E6F4EA',
                        dark: '#1A3C34',
                        light: '#F8FAF9',
                        white: '#FFFFFF',
                        success: '#28A745',
                        warning: '#FFC107',
                        danger: '#DC3545',
                        info: '#17A2B8'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    },
                    boxShadow: {
                        card: '0 10px 15px -3px rgba(0, 90, 36, 0.1), 0 4px 6px -2px rgba(0, 90, 36, 0.05)',
                        'card-hover': '0 20px 25px -5px rgba(0, 90, 36, 0.2), 0 10px 10px -5px rgba(0, 90, 36, 0.1)',
                        'glow': '0 0 20px rgba(255, 165, 0, 0.3)'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-slow': 'bounce 2s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
            background: linear-gradient(135deg, #F8FAF9 0%, #E6F4EA 100%);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #005A24 0%, #1A3C34 100%);
        }

        .page-title {
            position: relative;
            width: 100%;
            text-align: center;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #FFA500, #FF8C00);
            border-radius: 3px;
        }

        .stats-card, .report-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #FFFFFF 0%, #F8FAF9 100%);
            border: 2px solid transparent;
        }

        .stats-card::before, .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.1) 0%, rgba(0, 90, 36, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .stats-card:hover::before, .report-card:hover::before {
            opacity: 1;
        }

        .stats-card:hover, .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 90, 36, 0.2), 0 10px 10px -5px rgba(0, 90, 36, 0.1);
            border-color: #FFA500;
        }

        .card-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            transition: all 0.75s ease;
            z-index: 2;
        }

        .stats-card:hover .card-shine, .report-card:hover .card-shine {
            left: 150%;
        }

        .stats-card:hover .card-icon, .report-card:hover .card-icon {
            transform: scale(1.1);
            color: #FFA500;
        }

        .stats-card p, .report-card p {
            z-index: 2;
            position: relative;
            transition: color 0.3s ease;
        }

        .stats-card:hover p, .report-card:hover p {
            color: #005A24;
        }

        .stats-card a, .stats-card button, .report-card a, .report-card button {
            position: relative;
            z-index: 3;
        }

        .header-nav-link {
            position: relative;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .header-nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .header-nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #FFA500;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .header-nav-link:hover::after {
            width: 80%;
        }

        .header-nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .header-nav-link.active::after {
            width: 80%;
        }

        .mobile-menu-button {
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8FAF9 100%);
            padding: 2rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 90, 36, 0.25);
            animation: slideUp 0.5s ease-out;
            position: relative;
            border: 2px solid #E6F4EA;
        }

        .modal-content h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #005A24;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .modal-content .form-group {
            margin-bottom: 1.5rem;
        }

        .modal-content label {
            display: block;
            font-size: 0.875rem;
            color: #1A3C34;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .modal-content input[type="text"],
        .modal-content input[type="date"],
        .modal-content select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #E6F4EA;
            border-radius: 0.5rem;
            font-size: 1rem;
            color: #1A3C34;
            background-color: #F8FAF9;
            transition: all 0.3s ease;
        }

        .modal-content input[type="text"]:focus,
        .modal-content input[type="date"]:focus,
        .modal-content select:focus {
            border-color: #FFA500;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
        }

        .modal-content .confirm-btn {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            color: #FFFFFF;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .modal-content .confirm-btn:hover {
            background: linear-gradient(135deg, #E59400 0%, #E67E00 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 165, 0, 0.3);
        }

        .modal-content .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1A3C34;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-content .close-btn:hover {
            color: #DC3545;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: white;
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        }

        .notification.success {
            background-color: #28A745;
        }

        .notification.error {
            background-color: #DC3545;
        }

        .notification.warning {
            background-color: #FFC107;
            color: #1A3C34;
        }

        @media (max-width: 768px) {
            .header-nav {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #005A24 0%, #1A3C34 100%);
                padding: 2rem 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                z-index: 50;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                backdrop-filter: blur(10px);
            }

            .header-nav.show {
                display: flex;
                animation: slideIn 0.3s ease-out;
            }

            @keyframes slideIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .header-nav-link {
                padding: 1rem 1.5rem;
                text-align: center;
                margin: 0.5rem 0;
                font-size: 1.1rem;
                border-radius: 0.75rem;
                transition: all 0.3s ease;
                width: 100%;
                max-width: 300px;
            }

            .header-nav-link:hover {
                background-color: rgba(255, 255, 255, 0.15);
                transform: translateX(5px);
            }

            .mobile-menu-button {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                width: 30px;
                height: 21px;
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 0;
                z-index: 60;
                position: relative;
            }

            .mobile-menu-button span {
                width: 100%;
                height: 3px;
                background-color: white;
                border-radius: 10px;
                transition: all 0.3s ease;
                position: relative;
                transform-origin: center;
            }

            .mobile-menu-button span:first-child.active {
                transform: rotate(45deg) translate(6px, 6px);
            }

            .mobile-menu-button span:nth-child(2).active {
                opacity: 0;
                transform: scale(0);
            }

            .mobile-menu-button span:nth-child(3).active {
                transform: rotate(-45deg) translate(6px, -6px);
            }

            .header-nav::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: -1;
            }

            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #menuButton {
                transition: all 0.3s ease;
            }

            #menuButton.hidden {
                opacity: 0;
                visibility: hidden;
                transform: scale(0.8);
            }

            footer {
                margin-left: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            footer .ml-64 {
                margin-left: 0 !important;
            }

            main {
                margin-left: 0 !important;
            }
        }

        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(0.5rem);
        }

        .sidebar-link.active {
            background-color: rgba(255, 165, 0, 0.2);
            color: #FFA500;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans">
    <!-- Sidebar -->
    <div class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-primary to-dark text-white shadow-xl z-50 transform transition-transform duration-300 ease-in-out" id="sidebar">
        <div class="flex flex-col h-full">
            <div class="p-6 border-b border-white/20">
                <div class="flex items-center">
                    <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo STGM" class="h-12 mr-3 transition-transform hover:scale-105">
                    <span class="text-white font-heading text-lg font-semibold">STGM Estoque</span>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <?php if (isset($_SESSION['Admin_estoque']) || isset($_SESSION['liberador_estoque']) || isset($_SESSION['Dev_estoque'])) { ?>
                    <a href="index.php" class="sidebar-link flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-white/10 hover:translate-x-2">
                        <i class="fas fa-home mr-3 text-lg"></i>
                        <span>Início</span>
                    </a>
                    <a href="estoque.php" class="sidebar-link flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-white/10 hover:translate-x-2">
                        <i class="fas fa-boxes mr-3 text-lg"></i>
                        <span>Estoque</span>
                    </a>
                    <a href="./products/adc_produto.php" class="sidebar-link flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-white/10 hover:translate-x-2">
                        <i class="fas fa-plus-circle mr-3 text-lg"></i>
                        <span>Adicionar</span>
                    </a>
                    <a href="solicitar.php" class="sidebar-link flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-white/10 hover:translate-x-2">
                        <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                        <span>Solicitar</span>
                    </a>
                    <a href="relatorios.php" class="sidebar-link flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-white/10 hover:translate-x-2">
                        <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                        <span>Relatórios</span>
                    </a>
                <?php } ?>
                <?php if (isset($_SESSION['Admin_estoque']) || isset($_SESSION['Dev_estoque'])) { ?>
                    <a href="ambiente.php" class="sidebar-link flex items-center p-3 rounded-lg transition-all duration-200 hover:bg-white/10 hover:translate-x-2 active">
                        <i class="fas fa-building mr-3 text-lg"></i>
                        <span>Ambiente</span>
                    </a>
                <?php } ?>
            </nav>
            <div class="p-4 border-t border-white/20">
                <a href="../../../main/views/subsystems.php" class="w-full bg-transparent border border-white/40 hover:bg-white/10 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Sair
                </a>
            </div>
            <div class="p-4 border-t border-white/20 md:hidden">
                <button class="w-full bg-white/10 hover:bg-white/20 text-white py-2 px-4 rounded-lg transition-all duration-200" id="closeSidebar">
                    <i class="fas fa-times mr-2"></i>
                    Fechar Menu
                </button>
            </div>
        </div>
    </div>

    <button class="fixed top-4 left-4 z-50 md:hidden text-primary p-3 rounded-lg hover:bg-primary/90 transition-all duration-200" id="menuButton">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <div class="fixed inset-0 bg-black/50 z-40 md:hidden hidden" id="overlay"></div>

    <button class="back-to-top hidden fixed bottom-6 right-6 z-50 bg-secondary hover:bg-secondary/90 text-white w-12 h-12 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center group">
        <i class="fas fa-chevron-up text-lg group-hover:scale-110 transition-transform duration-300"></i>
    </button>

    <main class="ml-0 md:ml-64 px-4 py-8 md:py-12 flex-1 transition-all duration-300">
        <div class="bg-white p-6 rounded-xl shadow-card mb-8">
            <h2 class="text-xl font-bold text-primary mb-4">Criar Novo Ambiente</h2>
            <form action="../controllers/controller_ambiente.php" method="post" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="ambiente" placeholder="Nome do ambiente" class="flex-1 px-4 py-3 border-2 border-accent rounded-lg focus:border-secondary focus:outline-none transition-all" required>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-dark transition-all font-semibold">Criar</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $ambientes = $select->select_ambientes();
            foreach ($ambientes as $ambiente) { ?>
                <div class="report-card bg-white border-2 border-primary rounded-xl shadow-card p-6 flex flex-col items-center animate-fade-in">
                    <div class="card-shine"></div>
                    <div class="card-icon w-16 h-16 text-primary mb-4 flex items-center justify-center">
                        <i class="fas fa-building text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-primary mb-2 text-center"><?= htmlspecialchars($ambiente['nome_ambiente']) ?></h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Ambiente cadastrado</p>
                    <div class="flex space-x-2 w-full">
                        <button onclick="openEditAmbiente(<?= json_encode($ambiente['id']) ?>, '<?= addslashes($ambiente['nome_ambiente']) ?>')" class="flex-1 bg-primary text-white py-2 px-4 rounded-lg hover:bg-dark transition-all font-medium text-sm">Editar</button>
                        <button onclick="openDeleteAmbiente(<?= json_encode($ambiente['id']) ?>, '<?= addslashes($ambiente['nome_ambiente']) ?>')" class="flex-1 bg-danger text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-all font-medium text-sm">Excluir</button>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Modal Editar Ambiente -->
        <div id="modalEditAmbiente" class="modal">
            <div class="modal-content">
                <button class="close-btn" onclick="closeModal('modalEditAmbiente')">&times;</button>
                <h2>Editar Ambiente</h2>
                <form id="editAmbienteForm" action="../controllers/controller_ambiente.php" method="post">
                    <input type="hidden" name="id_ambiente" id="inpEditAmbienteId">
                    <div class="form-group">
                        <label for="inpEditAmbienteNome">Nome do Ambiente *</label>
                        <input type="text" id="inpEditAmbienteNome" name="ambiente" required>
                    </div>
                    <button type="submit" class="confirm-btn">Salvar Alterações</button>
                </form>
            </div>
        </div>

        <!-- Modal Confirmar Exclusão -->
        <div id="modalDeleteAmbiente" class="modal">
            <div class="modal-content">
                <button class="close-btn" onclick="closeModal('modalDeleteAmbiente')">&times;</button>
                <h2>Confirmar Exclusão</h2>
                <p>Tem certeza que deseja excluir o ambiente <span id="deleteAmbienteName"></span>?</p>
                <form id="deleteAmbienteForm" action="../controllers/controller_ambiente.php" method="post">
                    <input type="hidden" name="id_excluir" id="inpDeleteAmbienteId">
                    <button type="submit" class="confirm-btn bg-danger hover:bg-red-700">Excluir</button>
                </form>
            </div>
        </div>

        <!-- Modal de Notificação -->
        <div id="notificationModal" class="modal">
            <div class="modal-content">
                <button class="close-btn" onclick="closeModal('notificationModal')">&times;</button>
                <h2 id="notificationTitle"></h2>
                <p id="notificationMessage"></p>
            </div>
        </div>
    </main>

    <footer class="bg-gradient-to-r from-primary to-dark text-white py-8 md:py-10 mt-auto relative transition-all duration-300">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-secondary to-transparent opacity-30"></div>
        <div class="px-4 md:px-8 transition-all duration-300" id="footerContent">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                    <div class="group">
                        <h3 class="font-heading text-lg md:text-xl font-semibold mb-4 flex items-center text-white group-hover:text-secondary transition-colors duration-300">
                            <i class="fas fa-school mr-3 text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                            EEEP STGM
                        </h3>
                        <p class="text-sm md:text-base leading-relaxed text-gray-200 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-map-marker-alt mr-2 text-secondary"></i>
                            AV. Marta Maria Carvalho Nojoza, SN<br>
                            Maranguape - CE
                        </p>
                    </div>
                    <div class="group">
                        <h3 class="font-heading text-lg md:text-xl font-semibold mb-4 flex items-center text-white group-hover:text-secondary transition-colors duration-300">
                            <i class="fas fa-address-book mr-3 text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                            Contato
                        </h3>
                        <div class="space-y-3">
                            <a href="tel:+558533413990" class="flex items-center text-sm md:text-base text-gray-200 hover:text-white transition-colors duration-300 group/item">
                                <i class="fas fa-phone-alt mr-3 text-secondary group-hover/item:scale-110 transition-transform duration-300"></i>
                                (85) 3341-3990
                            </a>
                            <a href="mailto:eeepsantariamata@gmail.com" class="flex items-center text-sm md:text-base text-gray-200 hover:text-white transition-colors duration-300 group/item">
                                <i class="fas fa-envelope mr-3 text-secondary group-hover/item:scale-110 transition-transform duration-300"></i>
                                eeepsantariamata@gmail.com
                            </a>
                        </div>
                    </div>
                    <div class="group">
                        <h3 class="font-heading text-lg md:text-xl font-semibold mb-4 flex items-center text-white group-hover:text-secondary transition-colors duration-300">
                            <i class="fas fa-code mr-3 text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                            Dev Team
                        </h3>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="#" class="flex items-center text-sm md:text-base text-gray-200 hover:text-white transition-all duration-300 group/item hover:translate-x-1">
                                <i class="fab fa-instagram mr-3 text-secondary group-hover/item:scale-110 transition-transform duration-300"></i>
                                Matheus Felix
                            </a>
                            <a href="#" class="flex items-center text-sm md:text-base text-gray-200 hover:text-white transition-all duration-300 group/item hover:translate-x-1">
                                <i class="fab fa-instagram mr-3 text-secondary group-hover/item:scale-110 transition-transform duration-300"></i>
                                Pedro Uchoa
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-6 mt-8 text-center">
                    <p class="text-sm md:text-base text-gray-300 hover:text-white transition-colors duration-300">
                        © 2024 STGM v1.2.0 | Desenvolvido por alunos EEEP STGM
                    </p>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-secondary to-transparent opacity-30"></div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar mobile toggle
            const menuButton = document.getElementById('menuButton');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const closeSidebar = document.getElementById('closeSidebar');

            if (menuButton && sidebar) {
                menuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('hidden');
                    if (sidebar.classList.contains('show')) {
                        menuButton.classList.add('hidden');
                    } else {
                        menuButton.classList.remove('hidden');
                    }
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                });

                if (overlay) {
                    overlay.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        overlay.classList.add('hidden');
                        menuButton.classList.remove('hidden');
                        document.body.style.overflow = '';
                    });
                }

                if (closeSidebar) {
                    closeSidebar.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        overlay.classList.add('hidden');
                        menuButton.classList.remove('hidden');
                        document.body.style.overflow = '';
                    });
                }

                const navLinks = sidebar.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('show');
                            overlay.classList.add('hidden');
                            menuButton.classList.remove('hidden');
                            document.body.style.overflow = '';
                        }
                    });
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        overlay.classList.add('hidden');
                        menuButton.classList.remove('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }

            // Funções para abrir e fechar modais
            window.openModal = function(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.add('show');
            }

            window.closeModal = function(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.remove('show');
                if (modalId === 'modalEditAmbiente') {
                    document.getElementById('editAmbienteForm').reset();
                } else if (modalId === 'modalDeleteAmbiente') {
                    document.getElementById('deleteAmbienteForm').reset();
                }
            }

            // Abrir modal de edição
            window.openEditAmbiente = function(id, nome) {
                document.getElementById('inpEditAmbienteId').value = id;
                document.getElementById('inpEditAmbienteNome').value = nome;
                openModal('modalEditAmbiente');
            }

            // Abrir modal de exclusão
            window.openDeleteAmbiente = function(id, nome) {
                document.getElementById('deleteAmbienteName').textContent = nome;
                document.getElementById('inpDeleteAmbienteId').value = id;
                openModal('modalDeleteAmbiente');
            }

            // Fechar modais ao clicar fora
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });

            // Validação do formulário de edição
            document.getElementById('editAmbienteForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const nome = document.getElementById('inpEditAmbienteNome').value.trim();
                if (!nome) {
                    showNotification('Por favor, preencha o nome do ambiente.', 'error');
                    return;
                }
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Salvando...';
                submitBtn.disabled = true;
                setTimeout(() => {
                    this.submit();
                }, 1000);
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 5000);
            });

            // Validação do formulário de exclusão
            document.getElementById('deleteAmbienteForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Excluindo...';
                submitBtn.disabled = true;
                setTimeout(() => {
                    this.submit();
                }, 1000);
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 5000);
            });

            // Função para mostrar notificações
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `notification ${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }

            // Mostrar notificações baseadas em GET
            const urlParams = new URLSearchParams(window.location.search);
            let title = '';
            let message = '';
            let type = 'info';

            if (urlParams.has('cadastrado')) {
                title = 'Sucesso!';
                message = 'Ambiente cadastrado com sucesso.';
                type = 'success';
            } else if (urlParams.has('editado')) {
                title = 'Sucesso!';
                message = 'Ambiente editado com sucesso.';
                type = 'success';
            } else if (urlParams.has('excluido')) {
                title = 'Sucesso!';
                message = 'Ambiente excluído com sucesso.';
                type = 'success';
            } else if (urlParams.has('erro')) {
                title = 'Erro!';
                message = 'Ocorreu um erro ao processar a operação.';
                type = 'error';
            } else if (urlParams.has('ja_cadastrado')) {
                title = 'Aviso!';
                message = 'Ambiente já cadastrado.';
                type = 'warning';
            } else if (urlParams.has('falha')) {
                title = 'Falha!';
                message = 'Falha ao executar a operação.';
                type = 'error';
            }

            if (message) {
                document.getElementById('notificationTitle').textContent = title;
                document.getElementById('notificationMessage').textContent = message;
                const notificationModal = document.getElementById('notificationModal');
                notificationModal.classList.add(type);
                openModal('notificationModal');
                setTimeout(() => closeModal('notificationModal'), 3000);
            }
        });
    </script>
</body>
</html>
